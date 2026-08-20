<?php
/**
 * Theme supports, menus and content width.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and navigation locations.
 */
function intera_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary'      => __( 'Primary menu', 'intera' ),
			'footer_legal' => __( 'Footer — legal', 'intera' ),
		)
	);
}
add_action( 'after_setup_theme', 'intera_setup' );

/**
 * Content width used by embeds and wide images.
 */
function intera_content_width() {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'intera_content_width', 0 );
