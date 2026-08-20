<?php
/**
 * Component — Button (DS `Button`, components.md §5).
 *
 * All visual state lives in the `.itr-btn*` rules in assets/css/intera.css (the export's
 * injected `itr-btn-css` sheet); this partial only emits the tag, the class list and the icons.
 *
 *     get_template_part( 'template-parts/components/button', null, array(
 *         'label'      => 'Get Early Access', // required, plain text
 *         'href'       => '',                 // renders <a> when set, else <button>
 *         'variant'    => 'primary',          // primary|secondary|ghost|danger|link|inverse|outlineInverse
 *         'size'       => 'md',               // sm|md|lg
 *         'icon_left'  => '',
 *         'icon_right' => '',
 *         'block'      => false,
 *         'disabled'   => false,
 *         'type'       => 'button',           // <button> only
 *         'class'      => '',
 *         'attrs'      => array(),
 *     ) );
 *
 * Accessibility fix over the export: a disabled Button always renders as a real
 * `<button disabled>` even when an href is supplied — a disabled `<a>` stays focusable
 * and still navigates.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'label'      => '',
		'href'       => '',
		'variant'    => 'primary',
		'size'       => 'md',
		'icon_left'  => '',
		'icon_right' => '',
		'block'      => false,
		'disabled'   => false,
		'type'       => 'button',
		'class'      => '',
		'attrs'      => array(),
	)
);

$intera_btn_variants = array( 'primary', 'secondary', 'ghost', 'danger', 'link', 'inverse', 'outlineInverse' );
$intera_btn_variant  = in_array( $args['variant'], $intera_btn_variants, true ) ? $args['variant'] : 'primary';
$intera_btn_size     = in_array( $args['size'], array( 'sm', 'md', 'lg' ), true ) ? $args['size'] : 'md';

// gsize: 18 on lg, 16 everywhere else — for both iconLeft and iconRight.
$intera_btn_icon_size = 'lg' === $intera_btn_size ? 18 : 16;

$intera_btn_class = 'itr-btn itr-btn--' . $intera_btn_variant . ' itr-btn--' . $intera_btn_size;

if ( $args['block'] ) {
	$intera_btn_class .= ' itr-btn--block';
}

if ( $args['class'] ) {
	$intera_btn_class .= ' ' . $args['class'];
}

$intera_btn_is_link = ( '' !== $args['href'] && ! $args['disabled'] );

// `type` is a named argument; drop any duplicate hiding in attrs (and never type an <a>).
if ( isset( $args['attrs']['type'] ) ) {
	if ( ! $intera_btn_is_link ) {
		$args['type'] = $args['attrs']['type'];
	}
	unset( $args['attrs']['type'] );
}

$intera_btn_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_btn_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_btn_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

if ( $intera_btn_is_link ) {
	echo '<a class="' . esc_attr( $intera_btn_class ) . '" href="' . esc_url( $args['href'] ) . '"'
		. $intera_btn_attrs // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above.
		. '>';
} else {
	echo '<button class="' . esc_attr( $intera_btn_class ) . '" type="' . esc_attr( $args['type'] ) . '"'
		. ( $args['disabled'] ? ' disabled aria-disabled="true"' : '' )
		. $intera_btn_attrs // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above.
		. '>';
}

if ( $args['icon_left'] && function_exists( 'intera_icon' ) ) {
	intera_icon( $args['icon_left'], array( 'size' => $intera_btn_icon_size ) );
}

echo esc_html( $args['label'] );

if ( $args['icon_right'] && function_exists( 'intera_icon' ) ) {
	intera_icon( $args['icon_right'], array( 'size' => $intera_btn_icon_size ) );
}

echo $intera_btn_is_link ? '</a>' : '</button>';
