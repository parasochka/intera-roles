<?php
/**
 * Component — MetricTile (DS `MetricTile`, components.md §10).
 *
 * `tone` colours the delta and nothing else.
 *
 *     get_template_part( 'template-parts/components/metric-tile', null, array(
 *         'label'     => 'Open incidents',
 *         'value'     => '7',
 *         'unit'      => '',
 *         'delta'     => '+2',
 *         'direction' => 'up',       // up|down|flat
 *         'tone'      => 'warning',  // neutral|ok|warning|critical
 *         'note'      => '',
 *         'sparkline' => '',         // trusted markup, sits between the value row and the delta row
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
		'label'     => '',
		'value'     => '',
		'unit'      => '',
		'delta'     => '',
		'direction' => 'flat',
		'tone'      => 'neutral',
		'note'      => '',
		'sparkline' => '',
		'class'     => '',
		'style'     => '',
		'attrs'     => array(),
	)
);

// DIRS — an unknown direction throws in the export; here it falls back to flat.
$intera_tile_dirs = array(
	'up'   => 'trending-up',
	'down' => 'trending-down',
	'flat' => 'minus',
);

$intera_tile_icon = isset( $intera_tile_dirs[ $args['direction'] ] )
	? $intera_tile_dirs[ $args['direction'] ]
	: $intera_tile_dirs['flat'];

$intera_tile_tones = array(
	'ok'       => 'var(--status-ok)',
	'warning'  => 'var(--status-warning)',
	'critical' => 'var(--status-critical)',
);

$intera_tile_color = isset( $intera_tile_tones[ $args['tone'] ] ) ? $intera_tile_tones[ $args['tone'] ] : 'var(--ink-500)';

$intera_tile_style = 'background:var(--surface-card);border:1px solid var(--border-card);border-radius:var(--radius-card);padding:var(--space-4);box-shadow:var(--shadow-xs);display:flex;flex-direction:column;gap:6px;min-width:0';

if ( $args['style'] ) {
	$intera_tile_style .= ';' . $args['style'];
}

$intera_tile_class = 'itr-metric-tile';

if ( $args['class'] ) {
	$intera_tile_class .= ' ' . $args['class'];
}

$intera_tile_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_tile_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_tile_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}
?>
<div class="<?php echo esc_attr( $intera_tile_class ); ?>" style="<?php echo esc_attr( $intera_tile_style ); ?>"<?php echo $intera_tile_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<div style="font-size:var(--text-xs);color:var(--text-secondary);letter-spacing:var(--tracking-normal);line-height:1.4"><?php echo esc_html( $args['label'] ); ?></div>
	<div style="display:flex;align-items:baseline;gap:5px;min-width:0">
		<span style="font-family:var(--font-mono);font-size:var(--text-2xl);font-weight:var(--weight-medium);color:var(--text-primary);letter-spacing:var(--tracking-snug);line-height:1.1"><?php echo esc_html( $args['value'] ); ?></span>
		<?php if ( '' !== $args['unit'] ) : ?>
			<span style="font-size:var(--text-sm);color:var(--text-muted)"><?php echo esc_html( $args['unit'] ); ?></span>
		<?php endif; ?>
	</div>
	<?php
	if ( $args['sparkline'] ) {
		echo $args['sparkline']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted template-composed markup.
	}
	?>
	<?php if ( '' !== $args['delta'] || '' !== $args['note'] ) : ?>
		<div style="display:flex;align-items:center;gap:6px;font-size:var(--text-xs);color:var(--text-muted)">
			<?php if ( '' !== $args['delta'] ) : ?>
				<span style="display:inline-flex;align-items:center;gap:3px;color:<?php echo esc_attr( $intera_tile_color ); ?>;font-family:var(--font-mono)">
					<?php
					if ( function_exists( 'intera_icon' ) ) {
						intera_icon(
							$intera_tile_icon,
							array(
								'size'   => 13,
								'stroke' => 2,
							)
						);
					}
					echo esc_html( $args['delta'] );
					?>
				</span>
			<?php endif; ?>
			<?php if ( '' !== $args['note'] ) : ?>
				<span><?php echo esc_html( $args['note'] ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
