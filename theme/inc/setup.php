<?php
/**
 * Theme supports, menus, editor styles, image sizes and content width.
 *
 * Everything here is *capability* registration: what the templates are allowed
 * to ask WordPress for. No copy, no URL and no term lives in this file — the
 * standing rule is that the template owns the layout and WordPress owns the
 * words.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and navigation locations.
 */
function intera_setup() {

	load_theme_textdomain( 'intera', INTERA_DIR . 'languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	/*
	 * The mockups' logo is `Logo size="26"`, whose mark renders at
	 * `markSize = round(26 * 1.42) = 37px` (recon/components.md §7), and
	 * `assets/img/logo/logo-horizontal.svg` has `viewBox="0 0 208 40"` — a
	 * 5.2:1 wordmark, so 37px tall is 192px wide. Both axes stay flexible: an
	 * uploaded logo replaces the inline SVG (contract: "a WP custom logo
	 * overrides it when set") and must not be force-cropped to our ratio.
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'               => 37,
			'width'                => 192,
			'flex-height'          => true,
			'flex-width'           => true,
			'header-text'          => array( 'site-title', 'site-description' ),
			'unlink-homepage-logo' => false,
		)
	);

	add_theme_support( 'post-thumbnails' );

	/*
	 * Screenshot sources for the `.itr-frame` product frames.
	 *
	 * The mockups crop in CSS, not in the file: every shot is
	 * `width: 100%; object-fit: cover; object-position: top left` inside an
	 * `.itr-shot` box of a fixed height (420px hero and "In action" on
	 * 01-main, 440px "Working with IT", 420px 02-product, 360px inside
	 * 09-blog-post, and 220px on mobile). Those heights differ per frame while
	 * the same file is reused across frames, so WordPress must NOT hard-crop:
	 * a second crop would throw away the top-left region the design relies on.
	 * These sizes only cap the width, and `object-fit` still does the framing.
	 *
	 * Widths are the real render widths, not estimates:
	 *   container 1160px - 2 x 24px gutter = 1112px of content;
	 *   the widest frame column is (1112 - 40) / 2 = 536px  -> 1160 covers 2x;
	 *   09-blog-post's article is `max-width: 680px`, which is also the width
	 *   mobile.css forces on the image (`.itr-shot > img { width: 680px }`).
	 */
	add_image_size( 'intera-shot', 1160, 9999, false );
	add_image_size( 'intera-shot-inline', 680, 9999, false );
	set_post_thumbnail_size( 1160, 9999, false );

	/*
	 * Block editor. `editor-styles` + `add_editor_style()` put the design
	 * system inside the editor canvas, so an author writes on the same
	 * surface, in the same type, that the front end renders.
	 */
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );

	add_editor_style( intera_editor_stylesheets() );

	/*
	 * Palette and sizes come from the tokens themselves (inc/tokens.php), so
	 * the editor re-themes with the rest of the site the moment a token
	 * changes. Guarded: another branch may not have landed tokens.php yet, and
	 * a half-landed theme must degrade, not fatal.
	 */
	if ( function_exists( 'intera_editor_palette_get' ) ) {
		$palette = intera_editor_palette_get();
		if ( ! empty( $palette ) ) {
			add_theme_support( 'editor-color-palette', $palette );
		}
	}

	if ( function_exists( 'intera_editor_font_sizes_get' ) ) {
		$sizes = intera_editor_font_sizes_get();
		if ( ! empty( $sizes ) ) {
			add_theme_support( 'editor-font-sizes', $sizes );
		}
	}

	/*
	 * The design system is explicit that "a colour used decoratively rather
	 * than to answer 'what kind of thing is this?' is out of spec", and that
	 * there are "no mesh gradients, no linear gradients, no two-colour blends
	 * anywhere" (recon/ds-rules.md §12). A free colour picker and the core
	 * gradient presets are exactly the two ways that rule gets broken by
	 * accident, so both are switched off. Authors keep the token palette.
	 */
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'disable-custom-font-sizes' );
	add_theme_support( 'disable-custom-gradients' );
	add_theme_support( 'editor-gradient-presets', array() );

	/*
	 * Five menu locations. The contract fixes these names.
	 *
	 * Inside a footer column, a menu item carrying the CSS class
	 * `group-break` opens a new group with a hairline above it — that is how
	 * the mockups' grouped footer columns stay editable from Appearance →
	 * Menus instead of being a hardcoded list.
	 */
	register_nav_menus(
		array(
			'primary'          => __( 'Primary menu', 'intera' ),
			'footer_product'   => __( 'Footer — column 1 (Product)', 'intera' ),
			'footer_resources' => __( 'Footer — column 2 (Resources)', 'intera' ),
			'footer_company'   => __( 'Footer — column 3 (Company)', 'intera' ),
			'footer_legal'     => __( 'Footer — legal strip', 'intera' ),
		)
	);

	/*
	 * Blog categories are deliberately NOT seeded here.
	 *
	 * The mockups show two ("Life stories", "Release information"), but they
	 * are content: the templates reach them through `get_the_category()` and
	 * `get_category_link()` and never name a slug, so creating them from code
	 * would buy nothing and would break CLAUDE.md's "content is not code".
	 * An editor adds, renames or removes a category in wp-admin and every
	 * template follows.
	 */
}
add_action( 'after_setup_theme', 'intera_setup' );

/**
 * Theme-relative stylesheet paths for the block editor canvas.
 *
 * Reuses `intera_ds_sheets()` — the `_ds/intera/styles.css` @import manifest is
 * the only list of design-system sheets, and duplicating it here is exactly the
 * drift CLAUDE.md forbids. `add_editor_style()` wants paths relative to the
 * theme root, so the absolute paths are trimmed back down.
 *
 * @return string[] Cascade-ordered paths, relative to the theme root.
 */
function intera_editor_stylesheets() {
	$sheets = function_exists( 'intera_ds_sheets' ) ? intera_ds_sheets() : array();

	$relative = array();
	foreach ( $sheets as $path ) {
		if ( 0 === strpos( $path, INTERA_DIR ) ) {
			$relative[] = substr( $path, strlen( INTERA_DIR ) );
		}
	}

	// Same cascade order the front end uses: tokens, then supplemental CSS.
	$relative[] = 'assets/css/intera.css';

	return $relative;
}

/**
 * Content width used by oEmbeds and wide alignments.
 *
 * 1160px is the container decision in the build contract — the mockups'
 * `max-width: 1160px` wins over the `--layout-max` token.
 */
function intera_content_width() {
	$GLOBALS['content_width'] = 1160;
}
add_action( 'after_setup_theme', 'intera_content_width', 0 );

/**
 * Offer the screenshot sizes in the media library's size dropdown.
 *
 * Without this an editor placing a product screenshot in post content can only
 * pick core's sizes, none of which match the frame widths.
 *
 * @param array<string,string> $sizes Selectable size labels, keyed by size name.
 * @return array<string,string>
 */
function intera_image_size_names( $sizes ) {
	$sizes['intera-shot']        = __( 'Product frame (1160px)', 'intera' );
	$sizes['intera-shot-inline'] = __( 'Product frame, in article (680px)', 'intera' );

	return $sizes;
}
add_filter( 'image_size_names_choose', 'intera_image_size_names' );

/**
 * Show the "CSS Classes" field in Appearance → Menus by default.
 *
 * The footer columns group their links with a `group-break` class on a menu
 * item, so the field an editor needs must not be hidden behind a screen option
 * they have to know exists. Only ever un-hides; never hides anything.
 *
 * @param string[]|false $hidden Hidden column ids, or false when unset.
 * @return string[]
 */
function intera_show_menu_css_classes( $hidden ) {
	$hidden = is_array( $hidden ) ? $hidden : array();

	return array_values( array_diff( $hidden, array( 'css-classes' ) ) );
}
add_filter( 'get_user_option_managenav-menuscolumnshidden', 'intera_show_menu_css_classes' );
