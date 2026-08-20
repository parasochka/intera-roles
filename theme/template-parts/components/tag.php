<?php
/**
 * Component — Tag (DS `Tag`, components.md §8).
 *
 * The system's mono chip — `font-family:var(--font-mono)` is unconditional.
 *
 *     get_template_part( 'template-parts/components/tag', null, array(
 *         'text'         => 'reconciliation',
 *         'content'      => '',      // trusted markup, used when 'text' is empty
 *         'href'         => '',      // renders the chip as a link (filter chips in the mockups)
 *         'selected'     => false,
 *         'icon'         => '',      // icon name — 12px
 *         'removable'    => false,   // renders the ✕ button
 *         'remove_label' => 'Remove',
 *         'class'        => '',
 *         'style'        => '',
 *         'attrs'        => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'text'         => '',
		'content'      => '',
		'href'         => '',
		'selected'     => false,
		'icon'         => '',
		'removable'    => false,
		'remove_label' => '',
		'class'        => '',
		'style'        => '',
		'attrs'        => array(),
	)
);

$intera_tag_style = 'display:inline-flex;align-items:center;gap:6px';
$intera_tag_style .= ';background:' . ( $args['selected'] ? 'var(--blue-50)' : 'var(--surface-sunken)' );
$intera_tag_style .= ';color:' . ( $args['selected'] ? 'var(--blue-700)' : 'var(--ink-700)' );
$intera_tag_style .= ';border:1px solid ' . ( $args['selected'] ? 'var(--blue-500)' : 'var(--border-default)' );
$intera_tag_style .= ';border-radius:var(--radius-badge);padding:3px 8px';
$intera_tag_style .= ';font-size:var(--text-xs);font-family:var(--font-mono);line-height:1.5';

if ( $args['style'] ) {
	$intera_tag_style .= ';' . $args['style'];
}

$intera_tag_class = 'itr-tag';

if ( $args['selected'] ) {
	$intera_tag_class .= ' itr-tag--selected';
}

if ( $args['class'] ) {
	$intera_tag_class .= ' ' . $args['class'];
}

$intera_tag_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_tag_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_tag_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

$intera_tag_is_link = ( '' !== $args['href'] );

if ( $intera_tag_is_link ) {
	echo '<a class="' . esc_attr( $intera_tag_class ) . '" href="' . esc_url( $args['href'] ) . '"'
		. ( $args['selected'] ? ' aria-current="true"' : '' )
		. ' style="' . esc_attr( $intera_tag_style ) . '"'
		. $intera_tag_attrs // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above.
		. '>';
} else {
	echo '<span class="' . esc_attr( $intera_tag_class ) . '" style="' . esc_attr( $intera_tag_style ) . '"'
		. $intera_tag_attrs // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above.
		. '>';
}

if ( $args['icon'] && function_exists( 'intera_icon' ) ) {
	intera_icon( $args['icon'], array( 'size' => 12 ) );
}

if ( '' !== $args['text'] ) {
	echo esc_html( $args['text'] );
} else {
	echo $args['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted template-composed markup.
}

if ( $args['removable'] && ! $intera_tag_is_link ) {
	$intera_tag_remove_label = $args['remove_label'] ? $args['remove_label'] : __( 'Remove', 'intera' );

	echo '<button aria-label="' . esc_attr( $intera_tag_remove_label ) . '" type="button" style="display:inline-flex;border:0;background:none;padding:0;margin-left:1px;cursor:pointer;color:inherit;opacity:.6">';

	if ( function_exists( 'intera_icon' ) ) {
		intera_icon(
			'x',
			array(
				'size'   => 12,
				'stroke' => 2,
			)
		);
	}

	echo '</button>';
}

echo $intera_tag_is_link ? '</a>' : '</span>';
