<?php
/**
 * Styles and scripts.
 *
 * All first-party CSS is concatenated and inlined into <head>: the design-system
 * sheets listed by the `_ds/intera/styles.css` @import manifest, then the
 * supplemental theme CSS, then style.css. DS files stay byte-identical on disk
 * (they are the source of truth) and the page ships zero render-blocking CSS
 * requests.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * Reads the `@import` manifest and returns the DS sheets in declaration order.
 *
 * @return string[] Theme-relative paths, empty while the DS has not landed yet.
 */
function intera_ds_sheets() {
	$manifest = INTERA_DS_DIR . 'styles.css';

	if ( ! is_readable( $manifest ) ) {
		return array();
	}

	$css = (string) file_get_contents( $manifest ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	preg_match_all( '/@import\s+(?:url\()?["\']([^"\']+)["\']\)?\s*;/', $css, $matches );

	$sheets = array();
	foreach ( $matches[1] as $relative ) {
		$path = INTERA_DS_DIR . ltrim( $relative, './' );
		if ( is_readable( $path ) ) {
			$sheets[] = $path;
		}
	}

	return $sheets;
}

/**
 * Inline the whole stylesheet stack, in cascade order.
 */
function intera_enqueue_assets() {
	$stack = array_merge(
		intera_ds_sheets(),
		array(
			INTERA_DIR . 'assets/css/intera.css',
			INTERA_DIR . 'style.css',
		)
	);

	$css = '';
	foreach ( $stack as $path ) {
		if ( is_readable( $path ) ) {
			$css .= (string) file_get_contents( $path ) . "\n"; // phpcs:ignore WordPress.WP.AlternativeFunctions
		}
	}

	// A registered-but-empty handle is the supported way to attach inline CSS.
	wp_register_style( 'intera', false, array(), INTERA_VERSION );
	wp_enqueue_style( 'intera' );

	if ( '' !== $css ) {
		wp_add_inline_style( 'intera', $css );
	}

	if ( is_readable( INTERA_DIR . 'assets/js/intera.js' ) ) {
		wp_enqueue_script(
			'intera',
			get_template_directory_uri() . '/assets/js/intera.js',
			array(),
			INTERA_VERSION,
			true
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'intera_enqueue_assets' );
