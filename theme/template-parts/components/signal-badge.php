<?php
/**
 * Component — SignalBadge (DS `SignalBadge` + `SIGNALS`, components.md §11–12).
 *
 * The four signals are the fixed brand vocabulary — label, icon and colour never get remapped.
 *
 *     get_template_part( 'template-parts/components/signal-badge', null, array(
 *         'type'      => 'event',  // event|reconciliation|incident|pattern
 *         'label'     => '',       // falls back to the signal's own label
 *         'size'      => 'md',     // md|sm
 *         'show_icon' => true,
 *         'class'     => '',
 *         'style'     => '',
 *         'attrs'     => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'type'      => 'event',
		'label'     => '',
		'size'      => 'md',
		'show_icon' => true,
		'class'     => '',
		'style'     => '',
		'attrs'     => array(),
	)
);

// SIGNALS — kept in step with template-parts/components/signal-chain.php.
$intera_signals = array(
	'event'          => array(
		'label'  => __( 'Event', 'intera' ),
		'icon'   => 'activity',
		'color'  => 'var(--signal-event)',
		'soft'   => 'var(--signal-event-soft)',
		'border' => 'var(--signal-event-line)',
	),
	'reconciliation' => array(
		'label'  => __( 'Reconciliation', 'intera' ),
		'icon'   => 'scale',
		'color'  => 'var(--signal-reconciliation)',
		'soft'   => 'var(--signal-reconciliation-soft)',
		'border' => 'var(--signal-reconciliation-line)',
	),
	'incident'       => array(
		'label'  => __( 'Incident', 'intera' ),
		'icon'   => 'alert-triangle',
		'color'  => 'var(--signal-incident)',
		'soft'   => 'var(--signal-incident-soft)',
		'border' => 'var(--signal-incident-line)',
	),
	'pattern'        => array(
		'label'  => __( 'Pattern', 'intera' ),
		'icon'   => 'git-branch',
		'color'  => 'var(--signal-pattern)',
		'soft'   => 'var(--signal-pattern-soft)',
		'border' => 'var(--signal-pattern-line)',
	),
);

$intera_signal_type = isset( $intera_signals[ $args['type'] ] ) ? $args['type'] : 'event';
$intera_signal      = $intera_signals[ $intera_signal_type ];
$intera_signal_sm   = 'sm' === $args['size'];

$intera_signal_style = 'display:inline-flex;align-items:center;gap:' . ( $intera_signal_sm ? '4' : '6' ) . 'px';
$intera_signal_style .= ';background:' . $intera_signal['soft'];
$intera_signal_style .= ';color:' . $intera_signal['color'];
$intera_signal_style .= ';border:1px solid ' . $intera_signal['border'];
$intera_signal_style .= ';border-radius:var(--radius-badge)';
$intera_signal_style .= ';padding:' . ( $intera_signal_sm ? '1px 6px' : '3px 8px' );
$intera_signal_style .= ';font-size:' . ( $intera_signal_sm ? 'var(--text-2xs)' : 'var(--text-xs)' );
$intera_signal_style .= ';font-weight:var(--weight-medium);letter-spacing:var(--tracking-wide);text-transform:uppercase;line-height:1.6;white-space:nowrap';

if ( $args['style'] ) {
	$intera_signal_style .= ';' . $args['style'];
}

$intera_signal_class = 'itr-signal-badge itr-signal-badge--' . $intera_signal_type;

if ( $args['class'] ) {
	$intera_signal_class .= ' ' . $args['class'];
}

$intera_signal_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_signal_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_signal_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

echo '<span class="' . esc_attr( $intera_signal_class ) . '" style="' . esc_attr( $intera_signal_style ) . '"'
	. $intera_signal_attrs // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above.
	. '>';

if ( $args['show_icon'] && function_exists( 'intera_icon' ) ) {
	intera_icon(
		$intera_signal['icon'],
		array(
			'size'   => $intera_signal_sm ? 11 : 13,
			'stroke' => 2,
		)
	);
}

echo esc_html( '' !== $args['label'] ? $args['label'] : $intera_signal['label'] );

echo '</span>';
