<?php
/**
 * Component — SignalChain (DS `SignalChain`, components.md §13).
 *
 * The order event → reconciliation → incident → pattern is fixed; reordering or recolouring
 * the chain is off-brand. An empty `active` lights all four panels.
 *
 *     get_template_part( 'template-parts/components/signal-chain', null, array(
 *         'active'      => '',        // '' = all four on; otherwise the one key that is on
 *         'captions'    => array( 'event' => 'Something happened.' ),
 *         'compact'     => false,
 *         'interactive' => false,     // adds cursor:pointer (the export's onSelect)
 *         'class'       => '',
 *         'style'       => '',
 *         'attrs'       => array(),
 *     ) );
 *
 * Note the panel radius is `var(--radius-md)`, not `--radius-card`, and the 3px accent is a
 * top rule — the same accent pattern as Card.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'active'      => '',
		'captions'    => array(),
		'compact'     => false,
		'interactive' => false,
		'class'       => '',
		'style'       => '',
		'attrs'       => array(),
	)
);

// SIGNALS — kept in step with template-parts/components/signal-badge.php. ORDER is this array's order.
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

$intera_chain_compact = (bool) $args['compact'];
$intera_chain_gap     = $intera_chain_compact ? 'var(--space-2)' : 'var(--space-3)';

$intera_chain_style = 'display:flex;align-items:stretch;gap:' . $intera_chain_gap . ';flex-wrap:wrap';

if ( $args['style'] ) {
	$intera_chain_style .= ';' . $args['style'];
}

$intera_chain_class = 'itr-signal-chain';

if ( $intera_chain_compact ) {
	$intera_chain_class .= ' itr-signal-chain--compact';
}

if ( $args['class'] ) {
	$intera_chain_class .= ' ' . $args['class'];
}

$intera_chain_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_chain_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_chain_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

$intera_chain_captions = (array) $args['captions'];
$intera_chain_last     = count( $intera_signals ) - 1;
$intera_chain_index    = 0;
?>
<div class="<?php echo esc_attr( $intera_chain_class ); ?>" style="<?php echo esc_attr( $intera_chain_style ); ?>"<?php echo $intera_chain_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<?php
	foreach ( $intera_signals as $intera_chain_key => $intera_chain_signal ) :
		$intera_chain_on = ( ! $args['active'] || $args['active'] === $intera_chain_key );

		$intera_panel_style = 'flex:1 1 0;min-width:' . ( $intera_chain_compact ? '132' : '168' ) . 'px';
		$intera_panel_style .= ';background:' . ( $intera_chain_on ? $intera_chain_signal['soft'] : 'var(--surface-sunken)' );
		$intera_panel_style .= ';border:1px solid ' . ( $intera_chain_on ? $intera_chain_signal['border'] : 'var(--border-subtle)' );
		$intera_panel_style .= ';border-top:3px solid ' . ( $intera_chain_on ? $intera_chain_signal['color'] : 'var(--border-default)' );
		$intera_panel_style .= ';border-radius:var(--radius-md)';
		$intera_panel_style .= ';padding:' . ( $intera_chain_compact ? 'var(--space-3)' : 'var(--space-4)' );
		$intera_panel_style .= ';opacity:' . ( $intera_chain_on ? '1' : '.55' );

		if ( $args['interactive'] ) {
			$intera_panel_style .= ';cursor:pointer';
		}

		$intera_panel_style .= ';transition:var(--transition-surface), opacity var(--duration-normal) var(--ease-standard)';
		?>
		<div class="itr-signal-chain__panel itr-signal-chain__panel--<?php echo esc_attr( $intera_chain_key ); ?>" style="<?php echo esc_attr( $intera_panel_style ); ?>">
			<div style="display:flex;align-items:center;gap:7px;color:<?php echo esc_attr( $intera_chain_on ? $intera_chain_signal['color'] : 'var(--ink-500)' ); ?>">
				<?php
				if ( function_exists( 'intera_icon' ) ) {
					intera_icon( $intera_chain_signal['icon'], array( 'size' => $intera_chain_compact ? 15 : 17 ) );
				}
				?>
				<span style="font-size:<?php echo esc_attr( $intera_chain_compact ? 'var(--text-sm)' : 'var(--text-md)' ); ?>;font-weight:var(--weight-semibold);color:var(--ink-900);letter-spacing:var(--tracking-snug)"><?php echo esc_html( $intera_chain_signal['label'] ); ?></span>
			</div>
			<?php if ( ! empty( $intera_chain_captions[ $intera_chain_key ] ) ) : ?>
				<div style="font-size:var(--text-sm);color:var(--ink-600);margin-top:6px;line-height:var(--leading-normal)"><?php echo esc_html( $intera_chain_captions[ $intera_chain_key ] ); ?></div>
			<?php endif; ?>
		</div>
		<?php if ( $intera_chain_index < $intera_chain_last ) : ?>
			<div style="display:flex;align-items:center;color:var(--ink-300);flex:none">
				<?php
				if ( function_exists( 'intera_icon' ) ) {
					intera_icon( 'chevron-right', array( 'size' => 18 ) );
				}
				?>
			</div>
		<?php endif; ?>
		<?php
		$intera_chain_index++;
	endforeach;
	?>
</div>
