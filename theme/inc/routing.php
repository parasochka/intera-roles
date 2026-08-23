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
	update_option( 'category_base', INTERA_CATEGORY_BASE );

	$blog = get_page_by_path( INTERA_BLOG_SLUG );

	if ( $blog instanceof WP_Post ) {
		update_option( 'page_for_posts', $blog->ID );
		update_option( 'show_on_front', 'page' );
	}

	update_option( INTERA_ROUTING_OPTION, '1' );

	return true;
}

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

	flush_rewrite_rules();

	update_option( INTERA_BOOTSTRAP_OPTION, INTERA_VERSION );
}
add_action( 'admin_init', 'intera_bootstrap' );
add_action( 'after_switch_theme', 'intera_bootstrap', 5 );

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
