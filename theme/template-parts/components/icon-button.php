<?php
/**
 * Component — IconButton (DS `IconButton`, components.md §6).
 *
 * The `.itr-iconbtn*` rules (the export's injected `itr-iconbtn-css` sheet) live in
 * assets/css/intera.css. Sizes are 28 / 36 / 44px square; the icon is 15 / 18 / 20px.
 *
 *     get_template_part( 'template-parts/components/icon-button', null, array(
 *         'icon'    => 'menu',
 *         'label'   => 'Menu',    // required — becomes aria-label and title
 *         'variant' => 'ghost',   // ghost|outline
 *         'size'    => 'md',      // sm|md|lg
 *         'type'    => 'button',
 *         'class'   => '',
 *         'attrs'   => array( 'aria-expanded' => 'false', 'aria-controls' => 'intera-nav' ),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'icon'     => '',
		'label'    => '',
		'variant'  => 'ghost',
		'size'     => 'md',
		'type'     => 'button',
		'disabled' => false,
		'class'    => '',
		'attrs'    => array(),
	)
);

$intera_iconbtn_variant = in_array( $args['variant'], array( 'ghost', 'outline' ), true ) ? $args['variant'] : 'ghost';
$intera_iconbtn_size    = in_array( $args['size'], array( 'sm', 'md', 'lg' ), true ) ? $args['size'] : 'md';

$intera_iconbtn_icon_sizes = array(
	'sm' => 15,
	'md' => 18,
	'lg' => 20,
);

$intera_iconbtn_class = 'itr-iconbtn itr-iconbtn--' . $intera_iconbtn_variant . ' itr-iconbtn--' . $intera_iconbtn_size;

if ( $args['class'] ) {
	$intera_iconbtn_class .= ' ' . $args['class'];
}

// `type` is a named argument; drop any duplicate hiding in attrs.
if ( isset( $args['attrs']['type'] ) ) {
	$args['type'] = $args['attrs']['type'];
	unset( $args['attrs']['type'] );
}

$intera_iconbtn_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_iconbtn_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_iconbtn_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

echo '<button class="' . esc_attr( $intera_iconbtn_class ) . '"'
	. ' aria-label="' . esc_attr( $args['label'] ) . '"'
	. ' title="' . esc_attr( $args['label'] ) . '"'
	. ' type="' . esc_attr( $args['type'] ) . '"'
	. ( $args['disabled'] ? ' disabled' : '' )
	. $intera_iconbtn_attrs // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above.
	. '>';

if ( $args['icon'] && function_exists( 'intera_icon' ) ) {
	intera_icon( $args['icon'], array( 'size' => $intera_iconbtn_icon_sizes[ $intera_iconbtn_size ] ) );
}

echo '</button>';
