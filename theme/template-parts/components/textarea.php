<?php
/**
 * Component — Textarea (DS `Textarea`, components.md §24; the `.itr-input*` sheet is §21).
 *
 * `.itr-input--area` carries `height:auto;padding:9px 12px;resize:vertical;min-height:88px`;
 * there is no size class on a textarea.
 *
 *     get_template_part( 'template-parts/components/textarea', null, array(
 *         'id'          => 'problem',
 *         'name'        => 'problem',
 *         'value'       => '',
 *         'rows'        => 4,
 *         'placeholder' => '',
 *         'invalid'     => false,
 *         'required'    => false,
 *         'disabled'    => false,
 *         'class'       => '',
 *         'style'       => '',
 *         'attrs'       => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'id'          => '',
		'name'        => '',
		'value'       => '',
		'rows'        => 4,
		'placeholder' => '',
		'invalid'     => false,
		'required'    => false,
		'disabled'    => false,
		'class'       => '',
		'style'       => '',
		'attrs'       => array(),
	)
);

$intera_textarea_class = 'itr-input itr-input--area';

if ( $args['invalid'] ) {
	$intera_textarea_class .= ' itr-input--invalid';
}

if ( $args['class'] ) {
	$intera_textarea_class .= ' ' . $args['class'];
}

$intera_textarea_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_textarea_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_textarea_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

echo '<textarea class="' . esc_attr( $intera_textarea_class ) . '"'
	. ' rows="' . esc_attr( (int) $args['rows'] ) . '"'
	. ( $args['id'] ? ' id="' . esc_attr( $args['id'] ) . '"' : '' )
	. ( $args['name'] ? ' name="' . esc_attr( $args['name'] ) . '"' : '' )
	. ( '' !== $args['placeholder'] ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '' )
	. ( $args['invalid'] ? ' aria-invalid="true"' : '' )
	. ( $args['required'] ? ' required' : '' )
	. ( $args['disabled'] ? ' disabled' : '' )
	. $intera_textarea_attrs // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above.
	. ( $args['style'] ? ' style="' . esc_attr( $args['style'] ) . '"' : '' )
	. '>' . esc_textarea( $args['value'] ) . '</textarea>';
