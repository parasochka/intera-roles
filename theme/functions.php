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
define( 'INTERA_VERSION', '0.2.0' );

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
	'post-types',    // docs (+ docs_category), role, plan.
	'meta',          // Post meta registration + meta boxes.
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
