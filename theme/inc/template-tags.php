<?php
/**
 * Output helpers shared by every template.
 *
 * These are the theme's "template tags": small functions that render one piece
 * of markup taken verbatim from the design handoff, or derive one value the
 * mockups show but the export has no source for (reading time, heading ids,
 * the breadcrumb trail).
 *
 * Nothing here invents a colour, a size or a spacing: every literal in the
 * markup below is quoted from the mockups and every colour is a `var(--token)`
 * name, so `_ds/intera/tokens/*.css` stays the single source of truth.
 *
 * Every helper echoes escaped output unless its name ends in `_get`.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/*
 * ---------------------------------------------------------------------------
 * Icons
 * ---------------------------------------------------------------------------
 *
 * The design system's `Icon` component looks a name up in the Lucide runtime
 * and renders `node[2]` — the icon's children — inside its own `<svg>` wrapper.
 * The theme never loads that runtime: the children are inlined below, copied
 * verbatim from Lucide 0.469.0 (the version the export resolves against), and
 * the wrapper is reproduced attribute for attribute.
 */

if ( ! function_exists( 'intera_icon_map' ) ) :
	/**
	 * name => inner SVG markup, for every icon the site needs.
	 *
	 * 54 names: the 44 written into the mockups, the two the site nav's burger
	 * supplies (`menu`, `x`), the ones the DS components render from the inside
	 * (`alert-triangle`, `info`, `check`, `minus`, `trending-up`,
	 * `trending-down`, `chevron-*`, `search`, `activity`, `scale`,
	 * `git-branch`, `check-circle`), and `alert-octagon`, which `Alert`
	 * defines for its `critical` tone.
	 *
	 * The values are trusted constants — they are printed unescaped.
	 *
	 * @return array<string,string>
	 */
	function intera_icon_map() {
		static $icons = null;

		if ( null !== $icons ) {
			return $icons;
		}

		$icons = array(
			'activity'           => '<path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2" />',
			'alert-octagon'      => '<path d="M12 16h.01" /><path d="M12 8v4" /><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z" />',
			'alert-triangle'     => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" /><path d="M12 9v4" /><path d="M12 17h.01" />',
			'arrow-right'        => '<path d="M5 12h14" /><path d="m12 5 7 7-7 7" />',
			'bell'               => '<path d="M10.268 21a2 2 0 0 0 3.464 0" /><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />',
			'book-open'          => '<path d="M12 7v14" /><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z" />',
			'boxes'              => '<path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3-4.03 2.42Z" /><path d="m7 16.5-4.74-2.85" /><path d="m7 16.5 5-3" /><path d="M7 16.5v5.17" /><path d="M12 13.5V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5l-5 3Z" /><path d="m17 16.5-5-3" /><path d="m17 16.5 4.74-2.85" /><path d="M17 16.5v5.17" /><path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3 5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0l-3 1.8Z" /><path d="M12 8 7.26 5.15" /><path d="m12 8 4.74-2.85" /><path d="M12 13.5V8" />',
			'check'              => '<path d="M20 6 9 17l-5-5" />',
			'check-circle'       => '<path d="M21.801 10A10 10 0 1 1 17 3.335" /><path d="m9 11 3 3L22 4" />',
			'chevron-down'       => '<path d="m6 9 6 6 6-6" />',
			'chevron-left'       => '<path d="m15 18-6-6 6-6" />',
			'chevron-right'      => '<path d="m9 18 6-6-6-6" />',
			'chevron-up'         => '<path d="m18 15-6-6-6 6" />',
			'circle-dollar-sign' => '<circle cx="12" cy="12" r="10" /><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" /><path d="M12 18V6" />',
			'circle-dot'         => '<circle cx="12" cy="12" r="10" /><circle cx="12" cy="12" r="1" />',
			'clipboard-check'    => '<rect width="8" height="4" x="8" y="2" rx="1" ry="1" /><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" /><path d="m9 14 2 2 4-4" />',
			'clock'              => '<circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" />',
			'contact'            => '<path d="M16 2v2" /><path d="M7 22v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" /><path d="M8 2v2" /><circle cx="12" cy="11" r="3" /><rect x="3" y="4" width="18" height="18" rx="2" />',
			'corner-down-right'  => '<polyline points="15 10 20 15 15 20" /><path d="M4 4v7a4 4 0 0 0 4 4h12" />',
			'database'           => '<ellipse cx="12" cy="5" rx="9" ry="3" /><path d="M3 5V19A9 3 0 0 0 21 19V5" /><path d="M3 12A9 3 0 0 0 21 12" />',
			'eye'                => '<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" /><circle cx="12" cy="12" r="3" />',
			'gauge'              => '<path d="m12 14 4-4" /><path d="M3.34 19a10 10 0 1 1 17.32 0" />',
			'git-branch'         => '<line x1="6" x2="6" y1="3" y2="15" /><circle cx="18" cy="6" r="3" /><circle cx="6" cy="18" r="3" /><path d="M18 9a9 9 0 0 1-9 9" />',
			'globe'              => '<circle cx="12" cy="12" r="10" /><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" /><path d="M2 12h20" />',
			'heart-pulse'        => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" /><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27" />',
			'info'               => '<circle cx="12" cy="12" r="10" /><path d="M12 16v-4" /><path d="M12 8h.01" />',
			'landmark'           => '<line x1="3" x2="21" y1="22" y2="22" /><line x1="6" x2="6" y1="18" y2="11" /><line x1="10" x2="10" y1="18" y2="11" /><line x1="14" x2="14" y1="18" y2="11" /><line x1="18" x2="18" y1="18" y2="11" /><polygon points="12 2 20 7 4 7" />',
			'layers'             => '<path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z" /><path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12" /><path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17" />',
			'link'               => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" /><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />',
			'lock'               => '<rect width="18" height="11" x="3" y="11" rx="2" ry="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" />',
			'mail'               => '<rect width="20" height="16" x="2" y="4" rx="2" /><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />',
			'menu'               => '<line x1="4" x2="20" y1="12" y2="12" /><line x1="4" x2="20" y1="6" y2="6" /><line x1="4" x2="20" y1="18" y2="18" />',
			'minus'              => '<path d="M5 12h14" />',
			'package'            => '<path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" /><path d="M12 22V12" /><path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7" /><path d="m7.5 4.27 9 5.15" />',
			'plug'               => '<path d="M12 22v-5" /><path d="M9 8V2" /><path d="M15 8V2" /><path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8Z" />',
			'printer'            => '<path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" /><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" /><rect x="6" y="14" width="12" height="8" rx="1" />',
			'radio-tower'        => '<path d="M4.9 16.1C1 12.2 1 5.8 4.9 1.9" /><path d="M7.8 4.7a6.14 6.14 0 0 0-.8 7.5" /><circle cx="12" cy="9" r="2" /><path d="M16.2 4.8c2 2 2.26 5.11.8 7.47" /><path d="M19.1 1.9a9.96 9.96 0 0 1 0 14.1" /><path d="M9.5 18h5" /><path d="m8 22 4-11 4 11" />',
			'receipt'            => '<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" /><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" /><path d="M12 17.5v-11" />',
			'repeat'             => '<path d="m17 2 4 4-4 4" /><path d="M3 11v-1a4 4 0 0 1 4-4h14" /><path d="m7 22-4-4 4-4" /><path d="M21 13v1a4 4 0 0 1-4 4H3" />',
			'rocket'             => '<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z" /><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z" /><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0" /><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5" />',
			'route-off'          => '<circle cx="6" cy="19" r="3" /><path d="M9 19h8.5c.4 0 .9-.1 1.3-.2" /><path d="M5.2 5.2A3.5 3.53 0 0 0 6.5 12H12" /><path d="m2 2 20 20" /><path d="M21 15.3a3.5 3.5 0 0 0-3.3-3.3" /><path d="M15 5h-4.3" /><circle cx="18" cy="5" r="3" />',
			'scale'              => '<path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" /><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" /><path d="M7 21h10" /><path d="M12 3v18" /><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2" />',
			'search'             => '<circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" />',
			'settings'           => '<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" /><circle cx="12" cy="12" r="3" />',
			'shield-check'       => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" /><path d="m9 12 2 2 4-4" />',
			'ship'               => '<path d="M12 10.189V14" /><path d="M12 2v3" /><path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6" /><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-8.188-3.639a2 2 0 0 0-1.624 0L3 14a11.6 11.6 0 0 0 2.81 7.76" /><path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1s1.2 1 2.5 1c2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />',
			'sliders-horizontal' => '<line x1="21" x2="14" y1="4" y2="4" /><line x1="10" x2="3" y1="4" y2="4" /><line x1="21" x2="12" y1="12" y2="12" /><line x1="8" x2="3" y1="12" y2="12" /><line x1="21" x2="16" y1="20" y2="20" /><line x1="12" x2="3" y1="20" y2="20" /><line x1="14" x2="14" y1="2" y2="6" /><line x1="8" x2="8" y1="10" y2="14" /><line x1="16" x2="16" y1="18" y2="22" />',
			'table-2'            => '<path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18" />',
			'terminal'           => '<polyline points="4 17 10 11 4 5" /><line x1="12" x2="20" y1="19" y2="19" />',
			'thumbs-down'        => '<path d="M17 14V2" /><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22a3.13 3.13 0 0 1-3-3.88Z" />',
			'thumbs-up'          => '<path d="M7 10v12" /><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z" />',
			'trending-down'      => '<polyline points="22 17 13.5 8.5 8.5 13.5 2 7" /><polyline points="16 17 22 17 22 11" />',
			'trending-up'        => '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17" /><polyline points="16 7 22 7 22 13" />',
			'x'                  => '<path d="M18 6 6 18" /><path d="m6 6 12 12" />',
		);

		return $icons;
	}
endif;

if ( ! function_exists( 'intera_icon_get' ) ) :
	/**
	 * Build one inline Lucide SVG.
	 *
	 * Wrapper reproduced from `components/core/Icon.jsx`: a 24x24 viewBox,
	 * `fill="none"`, the colour on `stroke`, round caps and joins, and
	 * `display:block;flex:none` so the glyph never picks up line-height.
	 *
	 * Accessible by default: the icon is `aria-hidden` unless the caller gives
	 * it an `aria_label`, in which case it becomes `role="img"` with a
	 * `<title>` the label is read from.
	 *
	 * An unknown name renders nothing at all — never a broken box.
	 *
	 * @param string $name Lucide name, kebab-case.
	 * @param array  $args {
	 *     @type int    $size       Square px size. Default 18.
	 *     @type string $color      Any CSS colour or `var(--…)`. Default currentColor.
	 *     @type float  $stroke     Stroke width. Default 1.75.
	 *     @type string $class      Extra class on the `<svg>`.
	 *     @type string $aria_label Accessible name. Default '' (decorative).
	 * }
	 * @return string SVG markup, or '' when the name is unknown.
	 */
	function intera_icon_get( $name, $args = array() ) {
		$args = wp_parse_args(
			is_array( $args ) ? $args : array(),
			array(
				'size'       => 18,
				'color'      => 'currentColor',
				'stroke'     => 1.75,
				'class'      => '',
				'aria_label' => '',
			)
		);

		$name = is_scalar( $name ) ? strtolower( trim( (string) $name ) ) : '';
		$map  = intera_icon_map();

		if ( '' === $name || ! isset( $map[ $name ] ) ) {
			return '';
		}

		$size = (int) $args['size'];
		if ( $size < 1 ) {
			$size = 18;
		}

		$stroke = (float) $args['stroke'];
		if ( $stroke <= 0 ) {
			$stroke = 1.75;
		}

		// 1.75 stays "1.75", 2.0 becomes "2", 2.20 becomes "2.2".
		$stroke = rtrim( rtrim( number_format( $stroke, 2, '.', '' ), '0' ), '.' );

		$color = trim( (string) $args['color'] );
		if ( '' === $color ) {
			$color = 'currentColor';
		}

		$label   = trim( (string) $args['aria_label'] );
		$classes = trim( (string) $args['class'] );

		$attributes = array(
			'viewBox'         => '0 0 24 24',
			'width'           => (string) $size,
			'height'          => (string) $size,
			'fill'            => 'none',
			'stroke'          => $color,
			'stroke-width'    => $stroke,
			'stroke-linecap'  => 'round',
			'stroke-linejoin' => 'round',
		);

		$title = '';

		if ( '' !== $label ) {
			static $title_seq = 0;
			++$title_seq;

			$title_id = 'intera-icon-title-' . $title_seq;

			$attributes['role']            = 'img';
			$attributes['aria-labelledby'] = $title_id;

			$title = '<title id="' . esc_attr( $title_id ) . '">' . esc_html( $label ) . '</title>';
		} else {
			$attributes['aria-hidden'] = 'true';
		}

		$attributes['focusable'] = 'false';
		$attributes['style']     = 'display:block;flex:none';

		$markup = '<svg';

		if ( '' !== $classes ) {
			$markup .= ' class="' . esc_attr( $classes ) . '"';
		}

		foreach ( $attributes as $attribute => $value ) {
			$markup .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
		}

		return $markup . '>' . $title . $map[ $name ] . '</svg>';
	}
endif;

if ( ! function_exists( 'intera_icon' ) ) :
	/**
	 * Print one inline Lucide SVG.
	 *
	 * @param string $name Lucide name, kebab-case.
	 * @param array  $args See intera_icon_get().
	 * @return void
	 */
	function intera_icon( $name, $args = array() ) {
		// Attributes are escaped in intera_icon_get(); the paths are constants.
		echo intera_icon_get( $name, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

/*
 * ---------------------------------------------------------------------------
 * Reading time
 * ---------------------------------------------------------------------------
 */

if ( ! function_exists( 'intera_reading_time' ) ) :
	/**
	 * Whole minutes to read a post, at 200 words per minute.
	 *
	 * The mockups print "6 min read" on 08, 09, 10, 12 and 13 with no source
	 * behind it. Per the build contract this is computed at render time from the
	 * word count — never a field an editor has to keep up to date.
	 *
	 * @param int $post_id Post to measure. Default 0 (the current post).
	 * @return int Minutes, minimum 1.
	 */
	function intera_reading_time( $post_id = 0 ) {
		$post_id = (int) $post_id;
		$post    = get_post( $post_id > 0 ? $post_id : null );

		if ( ! $post ) {
			return 1;
		}

		static $cache = array();

		if ( isset( $cache[ $post->ID ] ) ) {
			return $cache[ $post->ID ];
		}

		$content = (string) $post->post_content;

		if ( function_exists( 'do_blocks' ) ) {
			$content = do_blocks( $content );
		}

		$content = strip_shortcodes( $content );

		// A space before every tag so `</p><p>` never welds two words together.
		$content = wp_strip_all_tags( str_replace( '<', ' <', $content ) );
		$content = html_entity_decode( $content, ENT_QUOTES, 'UTF-8' );

		$words = preg_split( '/[\s\x{00a0}]+/u', trim( $content ), -1, PREG_SPLIT_NO_EMPTY );
		$count = is_array( $words ) ? count( $words ) : 0;

		/**
		 * Reading speed used for the "N min read" stamps.
		 *
		 * @param int $wpm Words per minute. Default 200.
		 */
		$wpm = (int) apply_filters( 'intera_reading_time_wpm', 200 );

		if ( $wpm < 1 ) {
			$wpm = 200;
		}

		$minutes = (int) ceil( $count / $wpm );

		if ( $minutes < 1 ) {
			$minutes = 1;
		}

		/**
		 * Final reading time, after the word count.
		 *
		 * @param int $minutes Whole minutes, minimum 1.
		 * @param int $post_id Post measured.
		 */
		$minutes = (int) apply_filters( 'intera_reading_time', $minutes, $post->ID );

		if ( $minutes < 1 ) {
			$minutes = 1;
		}

		$cache[ $post->ID ] = $minutes;

		return $minutes;
	}
endif;

/*
 * ---------------------------------------------------------------------------
 * Headings / "Contents" rails
 * ---------------------------------------------------------------------------
 *
 * Mockups 04, 07, 09 and 12 carry a sticky "Contents" rail whose links point at
 * `#scope`, `#data`, … inside the article. Per the build contract those ids are
 * generated here from the rendered content — no JavaScript, and no field the
 * editor has to maintain. Both helpers share one parse so the ids in the rail
 * and the ids in the body can never disagree.
 */

if ( ! function_exists( 'intera_headings_parse' ) ) :
	/**
	 * Parse rendered post HTML once, giving every h2/h3 a stable, unique id.
	 *
	 * Uses DOMDocument with libxml's internal errors suppressed, so malformed
	 * editor markup degrades to "return the HTML exactly as it came in".
	 *
	 * @param string $html Rendered post content.
	 * @return array{html:string,headings:array<int,array{id:string,text:string,level:int}>}
	 */
	function intera_headings_parse( $html ) {
		$html = (string) $html;

		static $cache = array();

		$key = md5( $html );

		if ( isset( $cache[ $key ] ) ) {
			return $cache[ $key ];
		}

		$result = array(
			'html'     => $html,
			'headings' => array(),
		);

		if ( '' === trim( $html ) || ! class_exists( 'DOMDocument' ) || ! preg_match( '/<h[23][\s>]/i', $html ) ) {
			$cache[ $key ] = $result;

			return $result;
		}

		$previous = libxml_use_internal_errors( true );

		try {
			$dom = new DOMDocument( '1.0', 'UTF-8' );

			// The meta pins the parser to UTF-8; the wrapper keeps a fragment a fragment.
			$loaded = $dom->loadHTML(
				'<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><div id="intera-content-root">' . $html . '</div></body></html>'
			);

			if ( ! $loaded ) {
				throw new RuntimeException( 'intera: content could not be parsed' );
			}

			$xpath = new DOMXPath( $dom );
			$root  = $xpath->query( '//*[@id="intera-content-root"]' )->item( 0 );

			if ( ! $root ) {
				throw new RuntimeException( 'intera: content wrapper missing' );
			}

			$nodes    = $xpath->query( './/h2 | .//h3', $root );
			$headings = array();
			$used     = array();
			$index    = 0;

			foreach ( $nodes as $node ) {
				++$index;

				$text = trim( (string) preg_replace( '/\s+/u', ' ', $node->textContent ) );
				$id   = trim( $node->getAttribute( 'id' ) );

				if ( '' === $id ) {
					$id = sanitize_title( $text );
				}

				if ( '' === $id ) {
					$id = 'section-' . $index;
				}

				$base   = $id;
				$suffix = 2;

				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $suffix;
					++$suffix;
				}

				$used[ $id ] = true;

				$node->setAttribute( 'id', $id );

				$headings[] = array(
					'id'    => $id,
					'text'  => $text,
					'level' => ( 'h3' === strtolower( $node->nodeName ) ? 3 : 2 ),
				);
			}

			$inner = '';

			foreach ( $root->childNodes as $child ) {
				$inner .= $dom->saveHTML( $child );
			}

			if ( '' !== trim( $inner ) ) {
				$result['html']     = $inner;
				$result['headings'] = $headings;
			}
		} catch ( Throwable $e ) {
			$result = array(
				'html'     => $html,
				'headings' => array(),
			);
		}

		libxml_clear_errors();
		libxml_use_internal_errors( $previous );

		$cache[ $key ] = $result;

		return $result;
	}
endif;

if ( ! function_exists( 'intera_headings_get' ) ) :
	/**
	 * The h2/h3 outline of some rendered content, in document order.
	 *
	 * @param string $html Rendered post content.
	 * @return array<int,array{id:string,text:string,level:int}>
	 */
	function intera_headings_get( $html ) {
		$parsed = intera_headings_parse( $html );

		return $parsed['headings'];
	}
endif;

if ( ! function_exists( 'intera_content_with_heading_ids' ) ) :
	/**
	 * The same content, with every h2/h3 carrying the id the rail links to.
	 *
	 * Existing ids are honoured, never overwritten. Returns the HTML unchanged
	 * when it holds no h2/h3 or cannot be parsed.
	 *
	 * @param string $html Rendered post content.
	 * @return string
	 */
	function intera_content_with_heading_ids( $html ) {
		$parsed = intera_headings_parse( $html );

		return $parsed['html'];
	}
endif;

/*
 * ---------------------------------------------------------------------------
 * Breadcrumbs
 * ---------------------------------------------------------------------------
 *
 * Markup quoted from `05-contacts` L65 / `11-docs` L65: a mono, `--text-xs`
 * row of `--ink-400` links separated by a plain `/`, the closing crumb in
 * `--ink-600`. Hover (`color: var(--ink-600)`) is the `.itr-crumb` class in
 * assets/css/intera.css, never inline. The container is a `<nav>` rather than
 * the export's `<div>`: an accessibility gap in the handoff, fixed here.
 */

if ( ! function_exists( 'intera_breadcrumbs_normalise' ) ) :
	/**
	 * Coerce whatever a template passed into a list of label/url pairs.
	 *
	 * Accepts `[ 'label' => …, 'url' => … ]` (also `title`/`text` and
	 * `href`/`link`), a bare string, or a `label => url` map.
	 *
	 * @param array $crumbs Raw crumbs.
	 * @return array<int,array{label:string,url:string}>
	 */
	function intera_breadcrumbs_normalise( $crumbs ) {
		if ( ! is_array( $crumbs ) ) {
			return array();
		}

		$out = array();

		foreach ( $crumbs as $key => $crumb ) {
			$label = '';
			$url   = '';

			if ( is_array( $crumb ) ) {
				foreach ( array( 'label', 'title', 'text', 'name' ) as $field ) {
					if ( isset( $crumb[ $field ] ) && is_scalar( $crumb[ $field ] ) && '' !== (string) $crumb[ $field ] ) {
						$label = (string) $crumb[ $field ];
						break;
					}
				}

				foreach ( array( 'url', 'href', 'link', 'permalink' ) as $field ) {
					if ( isset( $crumb[ $field ] ) && is_scalar( $crumb[ $field ] ) ) {
						$url = (string) $crumb[ $field ];
						break;
					}
				}
			} elseif ( is_scalar( $crumb ) ) {
				if ( is_string( $key ) ) {
					$label = $key;
					$url   = (string) $crumb;
				} else {
					$label = (string) $crumb;
				}
			}

			$label = trim( wp_strip_all_tags( $label ) );

			if ( '' === $label ) {
				continue;
			}

			$out[] = array(
				'label' => $label,
				'url'   => trim( $url ),
			);
		}

		return $out;
	}
endif;

if ( ! function_exists( 'intera_breadcrumbs_get' ) ) :
	/**
	 * Derive the trail for the queried object.
	 *
	 * Follows the mockups exactly:
	 * `Home / Product` (02) · `Home / Contacts / Request` (06, page ancestors) ·
	 * `Home / Blog / Life stories` (09, 10) · `Home / Docs` (11) ·
	 * `Docs / Solutions and reference` (12 — no Home crumb on a doc) ·
	 * `Home / Docs / Getting started` (13). The front page has no breadcrumb.
	 *
	 * @return array<int,array{label:string,url:string}>
	 */
	function intera_breadcrumbs_get() {
		$home = array(
			'label' => __( 'Home', 'intera' ),
			'url'   => home_url( '/' ),
		);

		$crumbs = array();

		if ( is_front_page() ) {
			return array();
		}

		$posts_page_id = (int) get_option( 'page_for_posts' );

		$blog = null;

		if ( $posts_page_id > 0 ) {
			$blog = array(
				'label' => get_the_title( $posts_page_id ),
				'url'   => (string) get_permalink( $posts_page_id ),
			);
		}

		if ( is_home() ) {
			$crumbs[] = $home;
			$crumbs[] = array(
				'label' => $blog ? $blog['label'] : single_post_title( '', false ),
				'url'   => '',
			);

			return $crumbs;
		}

		if ( is_search() ) {
			$crumbs[] = $home;
			$crumbs[] = array(
				/* translators: %s: search query. */
				'label' => sprintf( __( 'Search: %s', 'intera' ), get_search_query() ),
				'url'   => '',
			);

			return $crumbs;
		}

		if ( is_404() ) {
			$crumbs[] = $home;
			$crumbs[] = array(
				'label' => __( 'Page not found', 'intera' ),
				'url'   => '',
			);

			return $crumbs;
		}

		if ( is_singular() ) {
			$post = get_queried_object();

			if ( ! $post instanceof WP_Post ) {
				return array();
			}

			if ( 'docs' === $post->post_type ) {
				// 12-docs-article opens on the archive, not on Home.
				$crumbs[] = intera_breadcrumbs_post_type_crumb( 'docs' );

				$term = intera_breadcrumbs_primary_term( $post, 'doc_category' );

				if ( $term ) {
					foreach ( intera_breadcrumbs_term_trail( $term ) as $term_crumb ) {
						$crumbs[] = $term_crumb;
					}
				}

				return array_values( array_filter( $crumbs ) );
			}

			$crumbs[] = $home;

			if ( 'post' === $post->post_type ) {
				if ( $blog ) {
					$crumbs[] = $blog;
				}

				$categories = get_the_category( $post->ID );

				if ( ! empty( $categories ) ) {
					$crumbs[] = array(
						'label' => $categories[0]->name,
						'url'   => (string) get_category_link( $categories[0] ),
					);
				}

				return $crumbs;
			}

			if ( 'page' !== $post->post_type ) {
				$archive = intera_breadcrumbs_post_type_crumb( $post->post_type );

				if ( $archive ) {
					$crumbs[] = $archive;
				}
			}

			foreach ( array_reverse( (array) get_post_ancestors( $post ) ) as $ancestor_id ) {
				$crumbs[] = array(
					'label' => get_the_title( $ancestor_id ),
					'url'   => (string) get_permalink( $ancestor_id ),
				);
			}

			$crumbs[] = array(
				'label' => get_the_title( $post ),
				'url'   => '',
			);

			return $crumbs;
		}

		if ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();

			$crumbs[] = $home;

			if ( $term instanceof WP_Term && 'doc_category' === $term->taxonomy ) {
				$crumbs[] = intera_breadcrumbs_post_type_crumb( 'docs' );
			} elseif ( is_category() && $blog ) {
				$crumbs[] = $blog;
			}

			if ( $term instanceof WP_Term ) {
				$trail = intera_breadcrumbs_term_trail( $term );

				foreach ( $trail as $position => $term_crumb ) {
					// The term the visitor is on is text, not a link.
					if ( count( $trail ) - 1 === $position ) {
						$term_crumb['url'] = '';
					}

					$crumbs[] = $term_crumb;
				}
			}

			return array_values( array_filter( $crumbs ) );
		}

		if ( is_post_type_archive() ) {
			$crumbs[] = $home;
			$crumbs[] = array(
				'label' => post_type_archive_title( '', false ),
				'url'   => '',
			);

			return $crumbs;
		}

		if ( is_archive() ) {
			$crumbs[] = $home;
			$crumbs[] = array(
				'label' => wp_strip_all_tags( get_the_archive_title() ),
				'url'   => '',
			);

			return $crumbs;
		}

		return array();
	}
endif;

if ( ! function_exists( 'intera_breadcrumbs_post_type_crumb' ) ) :
	/**
	 * The archive crumb for a post type, or null when it has no archive.
	 *
	 * @param string $post_type Post type name.
	 * @return array{label:string,url:string}|null
	 */
	function intera_breadcrumbs_post_type_crumb( $post_type ) {
		$object = get_post_type_object( $post_type );

		if ( ! $object ) {
			return null;
		}

		$label = isset( $object->labels->name ) ? $object->labels->name : $object->label;
		$link  = get_post_type_archive_link( $post_type );

		return array(
			'label' => (string) $label,
			'url'   => $link ? (string) $link : '',
		);
	}
endif;

if ( ! function_exists( 'intera_breadcrumbs_primary_term' ) ) :
	/**
	 * The term a post belongs under, preferring a top-level one.
	 *
	 * `doc_category` is hierarchical — a doc filed under a sub-group still
	 * breadcrumbs to the group the mockup shows.
	 *
	 * @param WP_Post $post     Post.
	 * @param string  $taxonomy Taxonomy name.
	 * @return WP_Term|null
	 */
	function intera_breadcrumbs_primary_term( $post, $taxonomy ) {
		$terms = get_the_terms( $post, $taxonomy );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return null;
		}

		foreach ( $terms as $term ) {
			if ( 0 === (int) $term->parent ) {
				return $term;
			}
		}

		return $terms[0];
	}
endif;

if ( ! function_exists( 'intera_breadcrumbs_term_trail' ) ) :
	/**
	 * A term and its ancestors, outermost first, each one linked.
	 *
	 * @param WP_Term $term Term.
	 * @return array<int,array{label:string,url:string}>
	 */
	function intera_breadcrumbs_term_trail( $term ) {
		if ( ! $term instanceof WP_Term ) {
			return array();
		}

		$trail     = array();
		$ancestors = array_reverse( (array) get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' ) );

		foreach ( $ancestors as $ancestor_id ) {
			$ancestor = get_term( (int) $ancestor_id, $term->taxonomy );

			if ( ! $ancestor instanceof WP_Term ) {
				continue;
			}

			$link = get_term_link( $ancestor );

			$trail[] = array(
				'label' => $ancestor->name,
				'url'   => is_wp_error( $link ) ? '' : (string) $link,
			);
		}

		$link = get_term_link( $term );

		$trail[] = array(
			'label' => $term->name,
			'url'   => is_wp_error( $link ) ? '' : (string) $link,
		);

		return $trail;
	}
endif;

if ( ! function_exists( 'intera_breadcrumbs' ) ) :
	/**
	 * Print the breadcrumb row.
	 *
	 * @param array $crumbs Explicit crumbs; derived from the query when empty.
	 * @return void
	 */
	function intera_breadcrumbs( $crumbs = array() ) {
		$crumbs = intera_breadcrumbs_normalise( $crumbs );

		if ( empty( $crumbs ) ) {
			$crumbs = intera_breadcrumbs_get();
		}

		/**
		 * The finished trail, just before it is printed.
		 *
		 * @param array $crumbs List of label/url pairs.
		 */
		$crumbs = intera_breadcrumbs_normalise( apply_filters( 'intera_breadcrumbs_items', $crumbs ) );

		if ( empty( $crumbs ) ) {
			return;
		}

		$last = count( $crumbs ) - 1;

		echo '<nav class="intera-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'intera' ) . '" style="display: flex; gap: 8px; align-items: center; font-size: var(--text-xs); color: var(--ink-400); font-family: var(--font-mono)">';

		foreach ( $crumbs as $position => $crumb ) {
			if ( $position > 0 ) {
				echo '<span aria-hidden="true">/</span>';
			}

			if ( '' !== $crumb['url'] ) {
				printf(
					'<a class="itr-crumb" href="%1$s" style="color: var(--ink-400)">%2$s</a>',
					esc_url( $crumb['url'] ),
					esc_html( $crumb['label'] )
				);

				continue;
			}

			printf(
				'<span%1$s style="color: var(--ink-600)">%2$s</span>',
				$position === $last ? ' aria-current="page"' : '',
				esc_html( $crumb['label'] )
			);
		}

		echo '</nav>';
	}
endif;

/*
 * ---------------------------------------------------------------------------
 * doc_category term meta
 * ---------------------------------------------------------------------------
 *
 * The chip on `11-docs` and `13-docs-category` is an icon in a `--{tone}-600`
 * square. Both values are term meta registered in inc/post-types.php, so an
 * editor picks them per category and nothing is hardcoded in a template.
 */

if ( ! function_exists( 'intera_term_icon_get' ) ) :
	/**
	 * A docs category's Lucide icon name.
	 *
	 * Returns '' when unset, or when the stored name is not one the theme has
	 * inlined — so a caller's fallback icon takes over instead of an empty gap.
	 *
	 * @param int $term_id Term id.
	 * @return string Icon name, or ''.
	 */
	function intera_term_icon_get( $term_id ) {
		$term_id = (int) $term_id;

		if ( $term_id <= 0 ) {
			return '';
		}

		$key   = defined( 'INTERA_TERM_ICON_KEY' ) ? INTERA_TERM_ICON_KEY : '_intera_term_icon';
		$value = get_term_meta( $term_id, $key, true );
		$value = is_scalar( $value ) ? strtolower( trim( (string) $value ) ) : '';

		if ( '' === $value ) {
			return '';
		}

		$map = intera_icon_map();

		return isset( $map[ $value ] ) ? $value : '';
	}
endif;

if ( ! function_exists( 'intera_term_tone_get' ) ) :
	/**
	 * A docs category's colour family: blue | teal | violet | amber.
	 *
	 * The value is a token *family* name, never a colour — templates compose
	 * `var(--{tone}-50)`, `var(--{tone}-100)`, `var(--{tone}-600)` from it, so
	 * the tokens stay the single source of truth.
	 *
	 * @param int $term_id Term id.
	 * @return string Tone slug. Default 'blue'.
	 */
	function intera_term_tone_get( $term_id ) {
		$term_id = (int) $term_id;

		if ( $term_id <= 0 ) {
			return 'blue';
		}

		$key   = defined( 'INTERA_TERM_TONE_KEY' ) ? INTERA_TERM_TONE_KEY : '_intera_term_tone';
		$value = get_term_meta( $term_id, $key, true );
		$value = is_scalar( $value ) ? strtolower( trim( (string) $value ) ) : '';

		if ( function_exists( 'intera_sanitize_tone' ) ) {
			return intera_sanitize_tone( $value );
		}

		return in_array( $value, array( 'blue', 'teal', 'violet', 'amber' ), true ) ? $value : 'blue';
	}
endif;

if ( ! function_exists( 'intera_page_url' ) ) :
	/**
	 * The URL of one of the site's fixed destinations.
	 *
	 * The mockups link to each other by filename (`03-pricing.dc.html`). In the
	 * theme those become real WordPress URLs, and no template may hardcode a
	 * slug: an editor renames or moves a page and every link has to follow.
	 *
	 * Resolution needs no configuration at all — a page is found by the template
	 * assigned to it in the editor, which is the same act that gives the page its
	 * design. Assign "Pricing" to a page and every "See pricing" link on the site
	 * points at it.
	 *
	 * Keys: home, blog, docs, and any page template — product, pricing, faq,
	 * contacts, contact-request, legal.
	 *
	 * @param string $key Destination key.
	 * @return string URL, or '' when nothing is assigned yet.
	 */
	function intera_page_url( $key ) {
		$key = sanitize_key( str_replace( '_', '-', (string) $key ) );

		if ( '' === $key || 'home' === $key ) {
			return home_url( '/' );
		}

		if ( 'blog' === $key ) {
			$posts_page = (int) get_option( 'page_for_posts' );

			return $posts_page ? (string) get_permalink( $posts_page ) : home_url( '/' );
		}

		if ( 'docs' === $key ) {
			$archive = get_post_type_archive_link( 'docs' );

			return $archive ? (string) $archive : '';
		}

		$cache = get_transient( 'intera_page_urls' );
		$cache = is_array( $cache ) ? $cache : array();

		if ( isset( $cache[ $key ] ) ) {
			return (string) $cache[ $key ];
		}

		/*
		 * `hierarchical => false` is load-bearing, not tidying.
		 *
		 * `get_pages()` defaults to true, and in that mode it runs the matches
		 * through `get_page_children()`: a page survives only if its parent is
		 * in the result set too. The set here is filtered to one template, so a
		 * destination that happens to be a child page — the request form lives
		 * under Contacts — matched the query and was then dropped again for
		 * having a parent that did not. Every call to action pointing at it
		 * rendered as an inert <button>, because that is what the Button
		 * component does with an empty href.
		 */
		$pages = get_pages(
			array(
				'meta_key'     => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'   => 'page-' . $key . '.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'number'       => 1,
				'sort_column'  => 'menu_order',
				'hierarchical' => false,
			)
		);

		$url = $pages ? (string) get_permalink( $pages[0]->ID ) : '';

		/*
		 * Only a resolved URL is cached. Caching the miss would mean that
		 * assigning the template to a page leaves the site broken until the
		 * transient expires, and "I fixed it but nothing changed" is the worst
		 * possible answer to give an editor.
		 */
		if ( '' !== $url ) {
			$cache[ $key ] = $url;
			set_transient( 'intera_page_urls', $cache, HOUR_IN_SECONDS );
		}

		return $url;
	}
endif;

if ( ! function_exists( 'intera_flush_page_urls' ) ) :
	/**
	 * Drop the destination cache whenever a page could have moved.
	 *
	 * @param int $post_id Saved post ID.
	 * @return void
	 */
	function intera_flush_page_urls( $post_id = 0 ) {
		unset( $post_id );
		delete_transient( 'intera_page_urls' );
	}
	add_action( 'save_post_page', 'intera_flush_page_urls' );
	add_action( 'deleted_post', 'intera_flush_page_urls' );
	add_action( 'update_option_page_for_posts', 'intera_flush_page_urls' );
endif;

if ( ! function_exists( 'intera_destinations_get' ) ) :
	/**
	 * The site's main destinations, for pages that have to offer a way out.
	 *
	 * Used by `404.php` and by `search.php`'s empty state — the two places where
	 * the reader arrived somewhere with nothing on it. The list is never a
	 * hardcoded set of links: it is the top level of whatever menu the site has
	 * assigned to `primary`, labels and descriptions included, so it follows the
	 * navigation an editor actually built.
	 *
	 * When no menu is assigned yet, the theme resolves the same destinations on
	 * its own through `intera_page_url()`, preferring the words the site uses —
	 * the page's own title, the posts page's title, the `docs` post type's label
	 * — over the theme's defaults. A destination whose page does not exist is
	 * simply left out.
	 *
	 * @param int $limit Maximum destinations to return.
	 * @return array<int,array{label:string,note:string,url:string}>
	 */
	function intera_destinations_get( $limit = 6 ) {
		$destinations = array();

		if ( has_nav_menu( 'primary' ) ) {
			$locations = get_nav_menu_locations();
			$menu      = isset( $locations['primary'] ) ? wp_get_nav_menu_object( $locations['primary'] ) : false;
			$items     = $menu ? wp_get_nav_menu_items( $menu->term_id ) : array();

			foreach ( (array) $items as $item ) {
				if ( (int) $item->menu_item_parent > 0 ) {
					continue;
				}

				$url = trim( (string) $item->url );

				if ( '' === $url || '' === trim( (string) $item->title ) ) {
					continue;
				}

				$destinations[ $url ] = array(
					'label' => (string) $item->title,
					'note'  => trim( (string) $item->description ),
					'url'   => $url,
				);
			}
		}

		if ( ! $destinations ) {
			$keys = array(
				'product'  => __( 'Product', 'intera' ),
				'pricing'  => __( 'Pricing', 'intera' ),
				'docs'     => __( 'Documentation', 'intera' ),
				'blog'     => __( 'Blog', 'intera' ),
				'faq'      => __( 'FAQ', 'intera' ),
				'contacts' => __( 'Contacts', 'intera' ),
			);

			foreach ( $keys as $key => $label ) {
				$url = function_exists( 'intera_page_url' ) ? (string) intera_page_url( $key ) : '';

				if ( '' === $url ) {
					continue;
				}

				if ( 'docs' === $key ) {
					$type = get_post_type_object( 'docs' );

					if ( $type && isset( $type->labels->name ) && '' !== (string) $type->labels->name ) {
						$label = (string) $type->labels->name;
					}
				} elseif ( 'blog' === $key ) {
					$posts_page = (int) get_option( 'page_for_posts' );

					if ( $posts_page > 0 ) {
						$label = get_the_title( $posts_page );
					}
				} else {
					$page_id = url_to_postid( $url );

					if ( $page_id > 0 ) {
						$label = get_the_title( $page_id );
					}
				}

				$destinations[ $url ] = array(
					'label' => $label,
					'note'  => '',
					'url'   => $url,
				);
			}
		}

		return array_slice( array_values( $destinations ), 0, max( 1, (int) $limit ) );
	}
endif;
