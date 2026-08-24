<?php
/**
 * Intera Roles — theme bootstrap.
 *
 * Thin loader only: constants + `require inc/*`. Real work lives in inc/.
 *
 * Every require is guarded with `file_exists()` on purpose: this theme is
 * deployed straight from `main` by WP Pusher, so a half-landed branch must
 * degrade into a plainer site rather than fatal the live one.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/** Theme version — bump on every release; used for cache busting. Keep in sync with style.css. */
define( 'INTERA_VERSION', '0.6.3' );

/** Absolute path to the theme root, with a trailing slash. */
define( 'INTERA_DIR', trailingslashit( get_template_directory() ) );

/** Absolute path to the design-system directory (tokens = source of truth). */
define( 'INTERA_DS_DIR', INTERA_DIR . '_ds/intera/' );

/**
 * inc/* modules, in load order.
 *
 * Order is dependency order, not preference: `tokens` exposes the editor
 * palette that `setup` registers, `enqueue` exposes the DS sheet manifest that
 * `setup` feeds to `add_editor_style()`, and `template-tags` is what every
 * template part calls. Nothing here runs work at require time — each file only
 * declares functions and attaches hooks — so a missing module costs its own
 * feature and nothing else.
 */
foreach ( array(
	'tokens',        // _ds/intera/tokens/*.css -> PHP arrays.
	'setup',         // Supports, menus, editor styles, image sizes.
	'enqueue',       // Inlined stylesheet stack + deferred JS.
	'template-tags', // intera_icon(), intera_option(), breadcrumbs, headings.
	'post-types',    // docs (+ doc_category), role, plan.
	'betterdocs',    // Hand the docs screens back to the theme; dress the FAQ block.
	'routing',       // Blog permalinks, the blog root, redirects from the old URLs.
	'meta',          // Post meta registration + meta boxes.
	'copy-defaults', // The design's own words, as registered defaults.
	'copy',          // Editable page copy for the four designed pages.
	'customizer',    // Theme options behind intera_option().
	'forms',         // Contact-request handler.
	'patterns',      // Block patterns + pattern categories.
) as $intera_module ) {
	$intera_module_path = INTERA_DIR . 'inc/' . $intera_module . '.php';

	if ( file_exists( $intera_module_path ) ) {
		require_once $intera_module_path;
	}
}
unset( $intera_module, $intera_module_path );

/*
 * Last-resort `intera_copy()`.
 *
 * The four designed templates read every run of text through it, so a deploy
 * that landed the templates but not `inc/copy.php` would fatal the live site on
 * its front page. The standing rule here is that a half-landed branch degrades
 * instead: this stub answers with the design's registered default when the
 * defaults file made it, and with an empty string when it did not — a page with
 * gaps in it, which is recoverable, rather than a white screen, which is not.
 */
if ( ! function_exists( 'intera_copy' ) ) {
	/**
	 * @param string   $key     Copy key.
	 * @param int|null $post_id Unused in the fallback.
	 * @return string
	 */
	function intera_copy( $key, $post_id = null ) {
		unset( $post_id );

		if ( ! function_exists( 'intera_copy_schema' ) ) {
			return '';
		}

		foreach ( intera_copy_schema() as $intera_copy_group ) {
			foreach ( $intera_copy_group['sections'] as $intera_copy_section ) {
				if ( isset( $intera_copy_section['fields'][ $key ] ) ) {
					return (string) $intera_copy_section['fields'][ $key ];
				}
			}
		}

		return '';
	}
}
