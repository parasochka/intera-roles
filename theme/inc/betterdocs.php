<?php
/**
 * BetterDocs interoperability.
 *
 * The live site keeps its documentation and its FAQ in BetterDocs Pro: the
 * plugin owns the `docs` post type, the `doc_category` taxonomy, the twenty-six
 * published articles and the FAQ block on the FAQ page. None of that content is
 * this theme's to move — `inc/post-types.php` already steps aside and lets the
 * plugin keep the registrations.
 *
 * What this file does is settle the two places where "the plugin owns the
 * content" and "the design owns the page" meet:
 *
 *  1. **Documentation** is rendered by the theme. The plugin's own archive,
 *     category and single layouts are a different design, so the theme takes
 *     the template back (`template_include`, last word) and draws the handoff's
 *     screens — `archive-docs.php`, `taxonomy-doc_category.php` and
 *     `single-docs.php` — from the plugin's posts and terms. Content, slugs and
 *     URLs are untouched; only the markup around them is ours.
 *  2. **The FAQ** stays the plugin's block, because that is where the questions
 *     are authored. The theme only marks the page so `assets/css/intera.css`
 *     can dress the plugin's accordion in the design's tokens.
 *  3. **Taking the template back took the plugin's FEATURES with it**, which
 *     was never the intention and is what the rest of this file exists to
 *     undo. A BetterDocs article is not only a body of text: the plugin also
 *     draws a reaction vote, a feedback form, a social-share row, the doc's
 *     tags, a print button, the AI summary and the comment thread — every one
 *     of them from `views/templates/footer.php` and its parts, none of which
 *     runs once `template_include` has pointed somewhere else. The reaction
 *     vote is the one that matters most: it is the only thing that writes to
 *     `/betterdocs/v1/feedback`, so BetterDocs → Analytics → Reactions was
 *     recording nothing at all while the docs looked finished.
 *
 *     The theme does **not** reimplement any of it. `intera_betterdocs_part()`
 *     renders the plugin's own view files, so every part keeps self-gating on
 *     the plugin's own settings — a feature the operator turned off in
 *     BetterDocs stays off here, and `betterdocs_docs_before_social` still
 *     fires, which is how BetterDocs Pro gets its own hooks back too.
 *     `assets/css/intera.css` dresses the result in the design's tokens, the
 *     same arrangement the FAQ block already lives under.
 *
 * Everything here is conditional on the plugin actually being active, so
 * switching BetterDocs off degrades to the theme's own `docs` registration
 * rather than to a fatal.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is BetterDocs the current owner of the documentation content?
 *
 * Both halves matter: the post type carries the articles and the taxonomy
 * carries the categories the design's cards and chips are built from. If only
 * one were present the theme's own registration would have filled the gap.
 *
 * @return bool
 */
function intera_betterdocs_active() {
	return post_type_exists( 'docs' ) && taxonomy_exists( 'doc_category' ) && defined( 'BETTERDOCS_VERSION' );
}

/**
 * Is the current request one of the three documentation screens?
 *
 * @return bool
 */
function intera_is_docs_request() {
	return is_singular( 'docs' ) || is_post_type_archive( 'docs' ) || is_tax( 'doc_category' );
}

/**
 * Give the documentation screens back to the theme.
 *
 * BetterDocs routes `docs` requests through its own layouts via
 * `template_include`. The theme ports the same three screens from the handoff
 * (`_design/11-docs`, `12-docs-article`, `13-docs-category`), so it asks for
 * them back — at priority 999, which is deliberately after the plugin, because
 * whoever filters last decides.
 *
 * `locate_template()` is what picks the file, so the standard hierarchy still
 * applies and a child theme can override any of the three. When none of them
 * exists — a half-landed branch — the plugin's own choice is returned
 * untouched.
 *
 * @param string $template Template chosen so far.
 * @return string
 */
function intera_docs_template_include( $template ) {
	if ( ! intera_betterdocs_active() || ! intera_is_docs_request() ) {
		return $template;
	}

	if ( is_singular( 'docs' ) ) {
		$candidates = array( 'single-docs.php', 'single.php' );
	} elseif ( is_tax( 'doc_category' ) ) {
		$candidates = array( 'taxonomy-doc_category.php', 'archive-docs.php' );
	} else {
		$candidates = array( 'archive-docs.php' );
	}

	$located = locate_template( $candidates );

	return $located ? $located : $template;
}
add_filter( 'template_include', 'intera_docs_template_include', 999 );

/**
 * Drop BetterDocs' own front-end styling from the screens the theme draws.
 *
 * The plugin ships a full layout stylesheet plus a settings-generated inline
 * block, both written for its own design. On the docs archive and the category
 * screen the theme emits its own markup end to end, so that CSS has nothing
 * left to style and only risks leaking `#fff` cards and 5px radii into the
 * design.
 *
 * **The single article is deliberately not in that set any more.** It used to
 * be, and the two decisions were made together: the theme took the template
 * back, so the plugin's stylesheet was dead weight. Then the article footer
 * came back (`intera_betterdocs_doc_footer()`), and with it real
 * `betterdocs-*` markup — the reaction vote, the feedback modal, the share
 * row, the tags. Stripping the plugin's sheet from under its own markup does
 * not restore the design, it just leaves the controls unstyled, so on a single
 * doc the plugin's sheets stand and `assets/css/intera.css` dresses them in
 * tokens, exactly the arrangement the FAQ block already lives under. The
 * sheets can only reach `betterdocs-`-classed elements, and outside that
 * footer the theme's markup has none.
 *
 * The FAQ is untouched for the same reason: there the plugin's markup *is* the
 * page.
 *
 * @return void
 */
function intera_docs_dequeue_plugin_styles() {
	if ( ! intera_betterdocs_active() || ! intera_is_docs_request() || is_singular( 'docs' ) ) {
		return;
	}

	foreach ( array( 'betterdocs-public', 'betterdocs-single', 'betterdocs-category-grid', 'betterdocs-sidebar', 'betterdocs-archive', 'betterdocs-elementor' ) as $handle ) {
		wp_dequeue_style( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'intera_docs_dequeue_plugin_styles', 100 );

/**
 * Put the article footer's component stylesheets in `<head>`.
 *
 * The reaction vote, the share row and the feedback modal are shortcodes, and
 * a BetterDocs shortcode enqueues its own sheet as it renders — which, from a
 * template, is mid-body. WordPress prints those with `print_late_styles()` in
 * the footer, so the controls paint unstyled first and snap into place after.
 * Asking for the same handles here, while `wp_enqueue_scripts` is still
 * running, moves them to `<head>`; the shortcode's own call then finds them
 * already enqueued and does nothing.
 *
 * Styles only. The reaction SCRIPT must stay late: its `betterdocsReactionsConfig`
 * (post id, REST nonce, endpoint) is localised on `betterdocs_before_render`,
 * i.e. as the shortcode renders — pull the script into `<head>` and it loads
 * before the data it needs exists, and every vote silently does nothing.
 *
 * @return void
 */
function intera_betterdocs_enqueue_doc_components() {
	if ( ! intera_betterdocs_active() || ! is_singular( 'docs' ) ) {
		return;
	}

	foreach ( array( 'betterdocs-reactions', 'betterdocs-social-share', 'betterdocs-feedback-form' ) as $handle ) {
		if ( wp_style_is( $handle, 'registered' ) ) {
			wp_enqueue_style( $handle );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'intera_betterdocs_enqueue_doc_components', 101 );

/**
 * Mark the page whose content is a BetterDocs FAQ.
 *
 * The FAQ page keeps the plugin's block — that is where an editor writes the
 * questions — so the theme cannot restyle it by owning the markup. It hangs a
 * class on `<body>` instead, and `assets/css/intera.css` uses that class to
 * out-specify the block's own settings CSS.
 *
 * Detection is on the rendered content rather than on the page template: an
 * editor may drop the FAQ block on any page, and every page carrying it should
 * look the same.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function intera_betterdocs_body_class( $classes ) {
	if ( ! is_singular() ) {
		return $classes;
	}

	$post = get_post();

	if ( ! $post instanceof WP_Post ) {
		return $classes;
	}

	if ( has_block( 'betterdocs/faq', $post ) || has_shortcode( (string) $post->post_content, 'betterdocs_faq' ) ) {
		$classes[] = 'intera-has-faq';
	}

	return $classes;
}
add_filter( 'body_class', 'intera_betterdocs_body_class' );

/**
 * The order an editor dragged one category's articles into.
 *
 * BetterDocs does not write that order into the post — `menu_order` stays 0 on
 * every doc, which is why ordering by it read as "whatever WordPress returns".
 * The sequence lives on the *term*, as a comma-separated list of post ids in
 * `_docs_order`, and the plugin's own reader is what turns it into a list: it
 * resolves the language-suffixed key on a multilingual site and puts a doc
 * nobody has dragged yet at the front, exactly as the admin screen does.
 *
 * Asked once per term per request; a docs screen asks for the same category
 * two or three times over.
 *
 * @param int $term_id doc_category term id.
 * @return int[] Post ids in the editor's order, empty when nothing was dragged.
 */
function intera_betterdocs_docs_order( $term_id ) {
	static $intera_bd_order = array();

	$term_id = (int) $term_id;

	if ( $term_id <= 0 ) {
		return array();
	}

	if ( isset( $intera_bd_order[ $term_id ] ) ) {
		return $intera_bd_order[ $term_id ];
	}

	$ids = array();

	// The plugin's own reader, so its rules are the ones that apply.
	if ( function_exists( 'betterdocs' ) ) {
		$intera_bd = betterdocs();

		if ( is_object( $intera_bd ) && isset( $intera_bd->query ) && is_object( $intera_bd->query ) && method_exists( $intera_bd->query, 'get_docs_order_by_terms' ) ) {
			try {
				$ids = (array) $intera_bd->query->get_docs_order_by_terms( $term_id );
			} catch ( \Throwable $intera_bd_error ) {
				$ids = array();
			}
		}
	}

	/*
	 * The plugin is switched off, but the order an editor dragged is still on
	 * the term — so the docs keep the sequence they were arranged in instead of
	 * falling back to alphabetical the moment BetterDocs is deactivated. A doc
	 * the list does not name goes first, which is where the plugin puts it.
	 */
	if ( empty( $ids ) ) {
		$stored = get_term_meta( $term_id, '_docs_order', true );
		$ids    = is_string( $stored ) && '' !== $stored ? explode( ',', $stored ) : array();

		if ( ! empty( $ids ) ) {
			$assigned = get_objects_in_term( array( $term_id ), 'doc_category' );
			$assigned = is_wp_error( $assigned ) ? array() : array_map( 'intval', (array) $assigned );
			$ids      = array_merge( array_diff( $assigned, array_map( 'intval', $ids ) ), $ids );
		}
	}

	$ids = array_values( array_unique( array_filter( array_map( 'intval', $ids ) ) ) );

	$intera_bd_order[ $term_id ] = $ids;

	return $ids;
}

/**
 * Where a category sits in the plugin's own category order.
 *
 * The drag order on BetterDocs → Categories is term meta, one integer per
 * category. A category nobody has dragged has no meta at all — `null` says so,
 * which is not the same as position zero.
 *
 * @param int $term_id doc_category term id.
 * @return int|null Position, or null when the category has none.
 */
function intera_betterdocs_category_order( $term_id ) {
	$stored = get_term_meta( (int) $term_id, 'doc_category_order', true );

	return ( '' === $stored || null === $stored || false === $stored ) ? null : (int) $stored;
}

/**
 * Order documentation the way the design reads it.
 *
 * The category pages number their articles `01…10`, which only means anything
 * if the archive is ordered by the editor's own sequence rather than by
 * publication date. That sequence is `_docs_order` on the category term, and
 * `intera_docs_order_ids()` reads it — the post's own Order field is the
 * fallback behind it, then the title, never the date, so two docs nobody has
 * arranged still land somewhere stable.
 *
 * BetterDocs sets the same `post__in` on this same hook, and earlier: a plugin
 * loads before a theme. Overwriting `orderby` unconditionally, which is what
 * this did, threw that away — so the ordered list is set here rather than
 * assumed, and an ordering another hand already put on the query is left alone.
 *
 * Front end only, and never on a search request, where relevance wins.
 *
 * @param WP_Query $query The query about to run.
 * @return void
 */
function intera_docs_archive_order( $query ) {
	if ( is_admin() || ! $query->is_main_query() || $query->is_search() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'docs' ) && ! $query->is_tax( 'doc_category' ) ) {
		return;
	}

	if ( $query->is_tax( 'doc_category' ) && function_exists( 'intera_docs_order_ids' ) ) {
		$term = $query->get_queried_object();
		$ids  = $term instanceof WP_Term ? intera_docs_order_ids( (int) $term->term_id ) : array();

		if ( ! empty( $ids ) ) {
			$query->set( 'post__in', $ids );
			$query->set( 'orderby', 'post__in' );

			return;
		}
	}

	// Something else already ordered this query by an explicit list of posts.
	if ( ! empty( $query->get( 'post__in' ) ) && 'post__in' === $query->get( 'orderby' ) ) {
		return;
	}

	$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
}
add_action( 'pre_get_posts', 'intera_docs_archive_order' );

/**
 * Read a BetterDocs setting without trusting the plugin's shape.
 *
 * Every part this file renders gates itself on the plugin's own settings, so
 * the theme rarely needs to ask — the two exceptions are the print button and
 * the parts that take their flag as an argument. The plugin's object graph is
 * built at `plugins_loaded` and a template runs long after that, but a fatal
 * here would take the whole article down for a knob, so every hop is checked.
 *
 * @param string $key     Settings key.
 * @param mixed  $default Value to answer with when the plugin cannot be asked.
 * @return mixed
 */
function intera_betterdocs_setting( $key, $default = false ) {
	if ( ! function_exists( 'betterdocs' ) ) {
		return $default;
	}

	$intera_bd = betterdocs();

	if ( ! is_object( $intera_bd ) || ! isset( $intera_bd->settings ) || ! is_object( $intera_bd->settings ) || ! method_exists( $intera_bd->settings, 'get' ) ) {
		return $default;
	}

	try {
		return $intera_bd->settings->get( $key, $default );
	} catch ( \Throwable $intera_bd_error ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
		return $default;
	}
}

/**
 * Render one of BetterDocs' own view files and hand back its markup.
 *
 * This is the whole integration strategy in one function: the theme does not
 * reimplement a reaction vote or a feedback modal, it asks the plugin to draw
 * its own. That keeps three things true that a port would have broken on the
 * first plugin update — the parts stay gated on the plugin's own settings, the
 * votes keep going to the plugin's own REST endpoint under its own nonce, and
 * `betterdocs_docs_before_social` still fires, which is the seam BetterDocs Pro
 * hangs its additions on.
 *
 * The markup is returned rather than echoed because the caller has to be able
 * to look at it: `single-docs.php` only keeps its own "Was this page useful?"
 * strip when what came back carries no vote control of its own.
 *
 * `Views::get()` RETURNS its error string instead of printing it, so a view the
 * plugin has renamed produces an empty buffer here and every caller treats that
 * the same way it treats a feature the operator switched off.
 *
 * @param string  $name   View path, relative to the plugin's `views/` directory.
 * @param mixed[] $params Variables the view is `extract()`ed with.
 * @return string Markup, or '' when the plugin cannot draw it.
 */
function intera_betterdocs_part( $name, $params = array() ) {
	if ( ! intera_betterdocs_active() || ! function_exists( 'betterdocs' ) ) {
		return '';
	}

	$intera_bd = betterdocs();

	if ( ! is_object( $intera_bd ) || ! isset( $intera_bd->views ) || ! is_object( $intera_bd->views ) || ! method_exists( $intera_bd->views, 'get' ) ) {
		return '';
	}

	ob_start();

	try {
		$intera_bd->views->get( $name, $params );
	} catch ( \Throwable $intera_bd_error ) {
		ob_end_clean();

		return '';
	}

	return trim( (string) ob_get_clean() );
}

/**
 * The plugin's article footer: tags, the reaction vote, share, feedback.
 *
 * One view, not four calls, because `views/templates/footer.php` is where the
 * plugin puts them in its own order *and* where it fires
 * `betterdocs_docs_before_social` — the action that draws the reaction vote and
 * the one BetterDocs Pro attaches to. Calling the four parts individually would
 * have restored the free features and quietly kept dropping the paid ones.
 *
 * Each part inside self-gates: tags on `enable_tags`, share on
 * `betterdocs_post_social_share`, the vote on `betterdocs_post_reactions`, the
 * feedback modal on `email_feedback`. Everything off ⇒ '' ⇒ the caller renders
 * its own strip instead.
 *
 * @return string
 */
function intera_betterdocs_doc_footer() {
	if ( ! is_singular( 'docs' ) ) {
		return '';
	}

	return intera_betterdocs_part( 'templates/footer' );
}

/**
 * Does that footer contain something a reader can actually vote with?
 *
 * `single-docs.php` ships a "Was this page useful?" strip of its own, which is
 * a `GET` form to the contact page and records nothing anywhere. Two of those
 * on one article is one too many, and the one that keeps a number is the
 * plugin's — so the theme's own strip renders only when the plugin drew
 * neither the reaction vote nor the feedback link.
 *
 * @param string $footer Markup from `intera_betterdocs_doc_footer()`.
 * @return bool
 */
function intera_betterdocs_footer_has_vote( $footer ) {
	$footer = (string) $footer;

	return false !== strpos( $footer, 'betterdocs-article-reactions' ) || false !== strpos( $footer, 'feedback-form' );
}

/**
 * The plugin's print button.
 *
 * Its handler lives in the plugin's own `betterdocs.js`, which is enqueued on
 * every `docs` request whoever draws the page — so the button works here as
 * long as the two ids it reads are on the page. `single-docs.php` puts
 * `betterdocs-entry-title` on the H1 and `betterdocs-single-content` on the
 * prose wrapper for exactly that reason; without them the click throws and
 * nothing prints.
 *
 * @return string
 */
function intera_betterdocs_doc_print_icon() {
	if ( ! is_singular( 'docs' ) ) {
		return '';
	}

	return intera_betterdocs_part(
		'templates/parts/print-icon',
		array( 'enable' => (bool) intera_betterdocs_setting( 'enable_print_icon', false ) )
	);
}

/**
 * The plugin's AI article summary.
 *
 * Self-gating twice over — the feature flag and "does this doc have a body" —
 * so the theme passes nothing and prints whatever comes back.
 *
 * @return string
 */
function intera_betterdocs_doc_summary() {
	if ( ! is_singular( 'docs' ) ) {
		return '';
	}

	return intera_betterdocs_part( 'templates/parts/article-summary' );
}

/**
 * Comments on a documentation article.
 *
 * BetterDocs has its own switch for this (`enable_comment`), and its part
 * respects both that and WordPress's per-post setting before handing over to
 * `comments_template()` — which loads the theme's `comments.php`, so a comment
 * thread on a doc looks like a comment thread anywhere else on the site.
 *
 * Echoed rather than returned: `comments_template()` prints, and buffering a
 * whole comment list to hand it back would buy nothing.
 *
 * @return void
 */
function intera_betterdocs_doc_comments() {
	if ( ! is_singular( 'docs' ) ) {
		return;
	}

	echo intera_betterdocs_part( 'templates/parts/comment' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin-composed markup, escaped at its source.
}

/**
 * Scope a search to the documentation without saying `post_type=docs`.
 *
 * The docs search boxes used to submit `?s=…&post_type=docs`, which looks like
 * the obvious way to search one post type and on this site is not: BetterDocs
 * parses a bare `post_type` query var as a request for its docs archive
 * (`Core\Request::$query_vars`, `'is_docs' => ['post_type']`), so the search
 * term was dropped on the floor and every query answered with the full archive
 * — the same page, whatever anyone typed. The form now sends `intera_docs=1`
 * instead and the scoping happens here, where the plugin's URL parsing cannot
 * see it. `search.php` renders the results.
 *
 * Registered unconditionally: the docs screens ship with the theme whether or
 * not BetterDocs is installed, and so does the form that reaches this.
 *
 * @param WP_Query $query The query about to run.
 * @return void
 */
function intera_docs_scope_search( $query ) {
	if ( is_admin() || ! $query instanceof WP_Query || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- a read-only scope flag on a public search form.
	if ( empty( $_GET['intera_docs'] ) || ! post_type_exists( 'docs' ) ) {
		return;
	}

	$query->set( 'post_type', 'docs' );
}
add_action( 'pre_get_posts', 'intera_docs_scope_search' );
