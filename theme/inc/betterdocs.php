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
 * block, both written for its own design. On the three documentation screens
 * the theme now emits its own markup, so that CSS has nothing left to style and
 * only risks leaking `#fff` cards and 5px radii into the design.
 *
 * The FAQ is untouched: there the plugin's markup *is* the page, and
 * `assets/css/intera.css` layers the design on top of the plugin's own rules
 * rather than replacing them.
 *
 * @return void
 */
function intera_docs_dequeue_plugin_styles() {
	if ( ! intera_betterdocs_active() || ! intera_is_docs_request() ) {
		return;
	}

	foreach ( array( 'betterdocs-public', 'betterdocs-single', 'betterdocs-category-grid', 'betterdocs-sidebar', 'betterdocs-archive', 'betterdocs-elementor' ) as $handle ) {
		wp_dequeue_style( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'intera_docs_dequeue_plugin_styles', 100 );

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
 * Order documentation the way the design reads it.
 *
 * The category pages number their articles `01…10`, which only means anything
 * if the archive is ordered by the editor's own sequence rather than by
 * publication date. BetterDocs writes that sequence into the post's menu order,
 * so the theme reads it — falling back to the title, never to the date, so two
 * unordered docs still land somewhere stable.
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

	$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
}
add_action( 'pre_get_posts', 'intera_docs_archive_order' );
