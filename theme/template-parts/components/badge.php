<?php
/**
 * Component — Badge (DS `Badge`, components.md §4).
 *
 *     get_template_part( 'template-parts/components/badge', null, array(
 *         'text'    => 'Beta',        // plain text, escaped
 *         'content' => '',            // trusted markup, used when 'text' is empty
 *         'tone'    => 'neutral',     // neutral|info|ok|warning|critical|accent
 *         'icon'    => '',            // icon name — 12px, stroke 2
 *         'solid'   => false,
 *         'class'   => '',
 *         'style'   => '',
 *         'attrs'   => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'text'    => '',
		'content' => '',
		'tone'    => 'neutral',
		'icon'    => '',
		'solid'   => false,
		'class'   => '',
		'style'   => '',
		'attrs'   => array(),
	)
);

// [ background, foreground, border ] — BADGE_TONES, verbatim.
$intera_badge_tones = array(
	'neutral'  => array( 'var(--status-neutral-soft)', 'var(--ink-700)', 'var(--status-neutral-line)' ),
	'info'     => array( 'var(--status-info-soft)', 'var(--blue-700)', 'var(--status-info-line)' ),
	'ok'       => array( 'var(--status-ok-soft)', 'var(--green-700)', 'var(--status-ok-line)' ),
	'warning'  => array( 'var(--status-warning-soft)', 'var(--amber-700)', 'var(--status-warning-line)' ),
	'critical' => array( 'var(--status-critical-soft)', 'var(--red-700)', 'var(--status-critical-line)' ),
	'accent'   => array( 'var(--violet-50)', 'var(--violet-700)', 'var(--violet-200)' ),
);

$intera_badge_tone = isset( $intera_badge_tones[ $args['tone'] ] ) ? $args['tone'] : 'neutral';
list( $intera_badge_bg, $intera_badge_fg, $intera_badge_line ) = $intera_badge_tones[ $intera_badge_tone ];

$intera_badge_style = 'display:inline-flex;align-items:center;gap:5px';
$intera_badge_style .= ';background:' . ( $args['solid'] ? $intera_badge_fg : $intera_badge_bg );
$intera_badge_style .= ';color:' . ( $args['solid'] ? 'var(--white)' : $intera_badge_fg );
$intera_badge_style .= ';border:1px solid ' . ( $args['solid'] ? 'transparent' : $intera_badge_line );
$intera_badge_style .= ';border-radius:var(--radius-badge);padding:2px 7px';
$intera_badge_style .= ';font-size:var(--text-xs);font-weight:var(--weight-medium)';
$intera_badge_style .= ';line-height:1.5;white-space:nowrap';

if ( $args['style'] ) {
	$intera_badge_style .= ';' . $args['style'];
}

$intera_badge_class = 'itr-badge itr-badge--' . $intera_badge_tone;

if ( $args['solid'] ) {
	$intera_badge_class .= ' itr-badge--solid';
}

if ( $args['class'] ) {
	$intera_badge_class .= ' ' . $args['class'];
}

$intera_badge_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_badge_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_badge_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

echo '<span class="' . esc_attr( $intera_badge_class ) . '" style="' . esc_attr( $intera_badge_style ) . '"'
	. $intera_badge_attrs // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above.
	. '>';

if ( $args['icon'] && function_exists( 'intera_icon' ) ) {
	intera_icon(
		$args['icon'],
		array(
			'size'   => 12,
			'stroke' => 2,
		)
	);
}

if ( '' !== $args['text'] ) {
	echo esc_html( $args['text'] );
} else {
	echo $args['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted template-composed markup.
}

echo '</span>';
