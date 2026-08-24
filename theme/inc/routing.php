<?php
/**
 * Permalinks, the blog root, and what happens to the URLs we replace.
 *
 * The design puts the whole editorial section under one root: `/blog/` is the
 * feed, `/blog/<category>/` is a category, `/blog/<category>/<post>/` is an
 * article. WordPress can express that exactly — a `/blog/%category%/%postname%/`
 * permalink structure with `blog` as the category base — but only as *settings*,
 * and settings are not files. So the theme writes them once, when it is
 * activated, and then leaves them alone: an editor who changes a permalink in
 * wp-admin afterwards is not overruled on the next page load.
 *
 * The site's previous structure was `/%category%/%postname%/` with no base, so
 * every existing article and category archive moves. `intera_legacy_redirect()`
 * below sends the old addresses to the new ones with a 301 rather than letting
 * them 404.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/** The permalink structure the design's blog URLs require. */
const INTERA_PERMALINK_STRUCTURE = '/blog/%category%/%postname%/';

/** The category base that puts category archives under the same root. */
const INTERA_CATEGORY_BASE = 'blog';

/**
 * The category base this site claims for archive URLs.
 *
 * `blog`, which is what puts category archives at `/blog/<category>/` — the
 * design's structure, and the reason the base is set at all. Leaving it empty
 * does not give a shorter URL, it gives a longer one: with no base, WordPress
 * registers the taxonomy `with_front`, so the archives come out at
 * `/blog/category/<category>/`.
 *
 * **This depends on nothing else stripping the base.** Rank Math ships a
 * "Strip Category Base" option, and with it on the two are not a cosmetic
 * disagreement but an infinite redirect: the plugin 301s anything under the
 * base to the same path without it, WordPress canonically 301s it back, and
 * because the post permalinks also begin with `/blog/`, every article on the
 * site vanishes between them. That happened here, on production, and the way
 * to recognise it again is the response headers — `x-redirect-by: Rank Math`
 * one way, `x-redirect-by: WordPress` the other.
 *
 * If that option is ever switched back on, switch this off with it:
 *
 *     add_filter( 'intera_category_base', '__return_empty_string' );
 *
 * Articles are unaffected either way. `/blog/<category>/<post>/` comes from the
 * permalink structure, not from here.
 *
 * @return string
 */
function intera_category_base() {
	/**
	 * Filters the category base the theme claims.
	 *
	 * @param string $base Category base. '' leaves WordPress's default.
	 */
	return (string) apply_filters( 'intera_category_base', INTERA_CATEGORY_BASE );
}

/** Slug of the page that becomes the blog root. */
const INTERA_BLOG_SLUG = 'blog';

/**
 * Categories that were renamed with the redesign: old slug => new slug.
 *
 * Only needed for the archive URLs. An article is found by its own slug, which
 * did not change, so it redirects correctly whatever category it sat under.
 *
 * @return array<string,string>
 */
function intera_legacy_category_slugs() {
	return array(
		'changelog' => 'release-information',
	);
}

/** Option that records the theme version whose one-time wiring has already run. */
const INTERA_BOOTSTRAP_OPTION = 'intera_bootstrap_version';

/** Option that records that the URL settings have been written once. */
const INTERA_ROUTING_OPTION = 'intera_routing_ready';

/** Option that asks the next front-end request to rebuild the rewrite rules. */
const INTERA_FLUSH_OPTION = 'intera_routing_flush_pending';

/**
 * Point WordPress at the design's URL structure. Written exactly once, ever.
 *
 * "Once" is the important word. These are settings an editor can change in
 * wp-admin afterwards, and a theme that re-asserted them on every load — or
 * every update — would quietly undo that change and be very hard to argue with.
 * So the write is guarded by its own option: after the first run this function
 * costs one option read and does nothing at all.
 *
 * The blog root is looked up, never created: pages are content, and this one is
 * made in wp-admin like any other. If it is missing the rest still applies and
 * the feed falls back to the front page, which is what `intera_page_url('blog')`
 * already assumes.
 *
 * @return bool Whether anything was written.
 */
function intera_bootstrap_routing() {
	if ( get_option( INTERA_ROUTING_OPTION ) ) {
		return false;
	}

	update_option( 'permalink_structure', INTERA_PERMALINK_STRUCTURE );
	update_option( 'category_base', intera_category_base() );

	$blog = get_page_by_path( INTERA_BLOG_SLUG );

	if ( $blog instanceof WP_Post ) {
		update_option( 'page_for_posts', $blog->ID );
		update_option( 'show_on_front', 'page' );
	}

	update_option( INTERA_ROUTING_OPTION, '1' );
	update_option( INTERA_FLUSH_OPTION, '1' );

	return true;
}

/**
 * Rebuild the rewrite rules on a request that has actually seen the new base.
 *
 * Flushing in the same request that wrote `category_base` is not enough, and
 * the failure is quiet. `create_initial_taxonomies()` registers the `category`
 * permastruct on `init` at priority 0, reading the option as it stood at the
 * start of that request; the settings are written later, on `admin_init`. So a
 * flush that follows immediately regenerates the rules from the *old*
 * permastruct, the option looks correct in wp-admin, and `/blog/<category>/`
 * answers 404 until something flushes again.
 *
 * Hence a flag rather than a flush: the next request registers the taxonomy
 * with the new base first, and only then are the rules rebuilt. Late on `init`,
 * so every taxonomy and post type has been registered, and once — the flag is
 * cleared before the flush so a failure cannot loop.
 *
 * @return void
 */
function intera_flush_pending_rewrites() {
	if ( ! get_option( INTERA_FLUSH_OPTION ) ) {
		return;
	}

	delete_option( INTERA_FLUSH_OPTION );

	flush_rewrite_rules();
}
add_action( 'init', 'intera_flush_pending_rewrites', 99 );

/**
 * Everything the theme has to set up outside a template, run once per version.
 *
 * `after_switch_theme` is the obvious hook and it is not enough here: this theme
 * is deployed by WP Pusher, which replaces the files of an already-active theme
 * without ever switching to it, so activation never fires again after the first
 * time. A version stamp does fire — on the first admin request after any deploy
 * that bumped `INTERA_VERSION`.
 *
 * What runs is deliberately idempotent and deliberately non-destructive: the URL
 * settings write only on their very first run, menu locations fill only where
 * they are empty, and the rewrite flush is the one thing that genuinely has to
 * happen again whenever the routing rules in this theme change.
 *
 * @return void
 */
function intera_bootstrap() {
	if ( get_option( INTERA_BOOTSTRAP_OPTION ) === INTERA_VERSION ) {
		return;
	}

	intera_bootstrap_routing();

	if ( function_exists( 'intera_assign_menu_locations' ) ) {
		intera_assign_menu_locations();
	}

	// A deploy can change a token value, and the parsed palette is cached.
	if ( function_exists( 'intera_tokens_flush' ) ) {
		intera_tokens_flush();
	}

	if ( function_exists( 'intera_flush_page_urls' ) ) {
		intera_flush_page_urls();
	}

	/*
	 * Deferred for the same reason as above: a deploy that changes a rewrite
	 * rule is read on `init`, and `admin_init` is already past it.
	 */
	update_option( INTERA_FLUSH_OPTION, '1' );

	update_option( INTERA_BOOTSTRAP_OPTION, INTERA_VERSION );
}
/*
 * `init` as well as `admin_init`, and deliberately so.
 *
 * With push-to-deploy the files change without anyone opening wp-admin: WP
 * Pusher's webhook is a front-end request, so an `admin_init`-only bootstrap
 * waits for the next human to visit the dashboard before the rewrite rules
 * catch up. Priority 98 puts it just ahead of `intera_flush_pending_rewrites()`
 * at 99, so a deploy settles inside the first request that follows it.
 *
 * Re-entry is cheap: after the first run this is one autoloaded option read.
 */
add_action( 'init', 'intera_reconcile_category_base', 97 );
add_action( 'init', 'intera_bootstrap', 98 );
add_action( 'admin_init', 'intera_bootstrap' );
add_action( 'after_switch_theme', 'intera_bootstrap', 5 );

/**
 * Keep the category base and the base-stripping plugin out of each other's way.
 *
 * This runs on every version change rather than once, because the conflict is
 * not ours to schedule: someone can switch "Strip Category Base" on in Rank
 * Math long after the theme settled its URLs, and the first symptom is every
 * article 301ing in a circle.
 *
 * Deliberately narrow. It only ever moves the option between '' and `blog` —
 * the two values this theme has an opinion about — so a base an editor set to
 * anything else is left alone, and the flush is deferred like every other one
 * here.
 *
 * @return void
 */
function intera_reconcile_category_base() {
	$current = (string) get_option( 'category_base' );
	$wanted  = intera_category_base();

	if ( $current === $wanted ) {
		return;
	}

	if ( '' !== $current && INTERA_CATEGORY_BASE !== $current ) {
		return;
	}

	update_option( 'category_base', $wanted );
	update_option( INTERA_FLUSH_OPTION, '1' );
}

/**
 * Stop the category archive rules from swallowing every article URL.
 *
 * This structure asks WordPress for two things that overlap:
 *
 *     /blog/<category>/          category archive   (category base `blog`)
 *     /blog/<category>/<post>/   article            (/blog/%category%/%postname%/)
 *
 * The category permastruct is `blog/%category%`, and `%category%` is compiled
 * to `(.+?)` because a category can be nested — a regex that happily matches a
 * slash. So the archive rule is `blog/(.+?)/?$`, and the category rules are
 * merged into the rule set *before* the post rules. Every article URL therefore
 * matches the archive rule first and resolves to
 * `category_name=release-information/v-0-003-2026-08-13`, a category that does
 * not exist, and the request 404s. The archive still worked, which is what made
 * it look like a content problem: the whole blog was reachable except the posts.
 *
 * Verbose page rules do not save it. They run first and, for a path that is not
 * a page, `WP::parse_request()` skips to the next rule — which is the archive
 * rule, not the article rule.
 *
 * The fix is to bound the archive rules to a single path segment. `blog/([^/]+)`
 * matches `/blog/<category>/` and its feed, embed and paged variants exactly as
 * before, and stops matching anything deeper, so `/blog/<category>/<post>/`
 * falls through to the article rule it was always meant to hit.
 *
 * The cost is that a **nested** category has no archive URL: `/blog/parent/child/`
 * is read as the article `child` in the category `parent`. That ambiguity is in
 * the URL itself, not in this filter — the two addresses are identical and one
 * of them has to win. Articles win, because this is the shape the site's blog
 * links are built from. Categories here are flat; if that ever changes, the
 * category base has to move out from under `/blog/` instead.
 *
 * Applied to the finished rule set rather than to `category_rewrite_rules`, so
 * it does not depend on where in the merge those rules land. A rule is a
 * category rule when it sits under the base, sets `category_name`, and does not
 * also set `name` — which is exactly what separates the archive rules from the
 * article rules that legitimately capture a category too.
 *
 * @param array<string,string> $rules Rewrite rules, regex => query.
 * @return array<string,string>
 */
function intera_bound_category_rules( $rules ) {
	$base = intera_category_base();

	// No base, or a base the article URLs do not sit under: nothing overlaps.
	if ( '' === $base || 0 !== strpos( ltrim( INTERA_PERMALINK_STRUCTURE, '/' ), $base . '/' ) ) {
		return $rules;
	}

	$prefix = $base . '/';
	$bound  = array();

	foreach ( (array) $rules as $regex => $query ) {
		$is_archive_rule = (
			0 === strpos( $regex, $prefix )
			&& false !== strpos( $query, 'category_name=$matches[' )
			&& false === strpos( $query, '&name=$matches[' )
		);

		if ( $is_archive_rule ) {
			$tightened = preg_replace( '#\(\.\+\?\)#', '([^/]+)', $regex, 1 );

			// Only ever a narrowing, and never one that overwrites another rule.
			if ( null !== $tightened && ! isset( $bound[ $tightened ] ) ) {
				$regex = $tightened;
			}
		}

		$bound[ $regex ] = $query;
	}

	return $bound;
}
add_filter( 'rewrite_rules_array', 'intera_bound_category_rules' );

/**
 * Send a pre-redesign URL to its replacement with a 301.
 *
 * The old structure was `/<category>/<post>/` and `/<category>/`; the new one
 * prefixes both with `/blog/`. Rather than keep a table of every moved address,
 * this reads the request the same way WordPress would have: the last segment is
 * an article slug, and the first is a category.
 *
 * Deliberately narrow. It only ever runs on a request that has *already* 404ed,
 * only for one- and two-segment paths, and only ever redirects to a post or a
 * term that really exists — so a genuine typo still gets the 404 page, and no
 * live URL is ever intercepted.
 *
 * @return void
 */
function intera_legacy_redirect() {
	if ( ! is_404() || is_admin() || wp_doing_ajax() ) {
		return;
	}

	$path = wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '', PHP_URL_PATH );
	$path = trim( (string) $path, '/' );

	if ( '' === $path ) {
		return;
	}

	// Anything already living under the new root is not a legacy address.
	if ( 0 === strpos( $path . '/', INTERA_CATEGORY_BASE . '/' ) ) {
		return;
	}

	$segments = array_values( array_filter( explode( '/', $path ), 'strlen' ) );

	if ( count( $segments ) > 2 ) {
		return;
	}

	$target = '';

	// `/<category>/<post>/` — the article kept its slug, so it is found by it.
	if ( 2 === count( $segments ) ) {
		$post = get_page_by_path( $segments[1], OBJECT, 'post' );

		if ( $post instanceof WP_Post && 'publish' === $post->post_status ) {
			$target = (string) get_permalink( $post );
		}
	}

	// `/<category>/` — the archive, allowing for a category renamed since.
	if ( '' === $target && 1 === count( $segments ) ) {
		$slugs  = intera_legacy_category_slugs();
		$slug   = isset( $slugs[ $segments[0] ] ) ? $slugs[ $segments[0] ] : $segments[0];
		$term   = get_term_by( 'slug', $slug, 'category' );

		if ( $term instanceof WP_Term ) {
			$link = get_term_link( $term );

			if ( ! is_wp_error( $link ) ) {
				$target = (string) $link;
			}
		}
	}

	if ( '' === $target ) {
		return;
	}

	// A structure that has not been switched over yet would redirect to itself.
	if ( untrailingslashit( $target ) === untrailingslashit( home_url( $path ) ) ) {
		return;
	}

	wp_safe_redirect( $target, 301 );
	exit;
}
add_action( 'template_redirect', 'intera_legacy_redirect', 1 );
