<?php
/**
 * Component — Card (DS `Card`, components.md §1).
 *
 * Every declaration below is the bundle's, verbatim; `var(--token)` names are never resolved.
 * The hover state is React state in the export, so it is not emitted here — `.itr-card:hover`
 * lives in assets/css/intera.css. `interactive` only adds `cursor:pointer` plus a hook class.
 *
 * Usage:
 *
 *     get_template_part( 'template-parts/components/card', null, array(
 *         'content'     => $html,     // trusted, template-composed markup (echoed as-is)
 *         'padding'     => 'default', // none|compact|default|loose
 *         'elevated'    => false,
 *         'accent'      => '',        // 'var(--signal-event)' — drawn as the 3px TOP rule only
 *         'accent_line' => '',        // outline colour, used only when accent is set
 *         'interactive' => false,
 *         'tag'         => 'div',     // div|article|section|aside|li|figure|form
 *         'class'       => 'itr-lift',
 *         'style'       => '',        // extra declarations, appended last (React spreads ...style last)
 *         'attrs'       => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'content'     => '',
		'children'    => '',
		'padding'     => 'default',
		'elevated'    => false,
		'accent'      => '',
		'accent_line' => '',
		'interactive' => false,
		'tag'         => 'div',
		'class'       => '',
		'style'       => '',
		'attrs'       => array(),
	)
);

$intera_card_pads = array(
	'none'    => '0',
	'compact' => 'var(--pad-card-compact)',
	'default' => 'var(--pad-card)',
	'loose'   => 'var(--space-7)',
);

$intera_card_pad = isset( $intera_card_pads[ $args['padding'] ] )
	? $intera_card_pads[ $args['padding'] ]
	: $intera_card_pads['default'];

$intera_card_tag = in_array( $args['tag'], array( 'div', 'article', 'section', 'aside', 'li', 'figure', 'form' ), true )
	? $args['tag']
	: 'div';

$intera_card_border = $args['accent']
	? ( $args['accent_line'] ? $args['accent_line'] : 'var(--border-default)' )
	: 'var(--border-card)';

/*
 * Only the per-instance values are inline. Background, border, radius, shadow
 * and transition belong to `.itr-card` in assets/css/intera.css, because an
 * inline declaration would outrank the `:hover` rules and every card would sit
 * there dead. The two values that do vary per card are handed over as custom
 * properties the class reads: --itr-edge (resting border colour) and
 * --itr-shadow (resting shadow). Padding and the accent stripe stay inline —
 * hover never touches them, and the stripe is deliberately border-top, which
 * the hover rules leave alone.
 */
$intera_card_style = '--itr-edge:' . $intera_card_border;

if ( $args['elevated'] ) {
	$intera_card_style .= ';--itr-shadow:var(--shadow-md)';
}

if ( $args['accent'] ) {
	$intera_card_style .= ';border-top:3px solid ' . $args['accent'];
}

$intera_card_style .= ';padding:' . $intera_card_pad;

if ( $args['style'] ) {
	$intera_card_style .= ';' . $args['style'];
}

$intera_card_class = 'itr-card';

if ( $args['interactive'] ) {
	$intera_card_class .= ' itr-card--interactive';
}

if ( $args['class'] ) {
	$intera_card_class .= ' ' . $args['class'];
}

$intera_card_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_card_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_card_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

$intera_card_content = '' !== $args['content'] ? $args['content'] : $args['children'];

echo '<' . esc_html( $intera_card_tag )
	. ' class="' . esc_attr( $intera_card_class ) . '"'
	. ' style="' . esc_attr( $intera_card_style ) . '"'
	. $intera_card_attrs // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above.
	. '>';

echo $intera_card_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted template-composed markup; editorial text is escaped by the caller.

echo '</' . esc_html( $intera_card_tag ) . '>';
