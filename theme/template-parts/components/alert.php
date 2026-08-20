<?php
/**
 * Component — Alert (DS `Alert`, components.md §14).
 *
 *     get_template_part( 'template-parts/components/alert', null, array(
 *         'tone'          => 'info',   // info|ok|warning|critical|neutral
 *         'title'         => 'Naming changed in v0.003',
 *         'content'       => $html,    // trusted markup — the body
 *         'text'          => '',       // plain-text body, escaped (used when 'content' is empty)
 *         'icon'          => '',       // overrides the tone's own icon
 *         'action'        => '',       // trusted markup
 *         'dismissible'   => false,
 *         'dismiss_label' => 'Dismiss',
 *         'class'         => '',
 *         'style'         => '',
 *         'attrs'         => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'tone'          => 'info',
		'title'         => '',
		'content'       => '',
		'text'          => '',
		'icon'          => '',
		'action'        => '',
		'dismissible'   => false,
		'dismiss_label' => '',
		'class'         => '',
		'style'         => '',
		'attrs'         => array(),
	)
);

// ALERT_TONES: [ background, border, foreground, default icon ].
$intera_alert_tones = array(
	'info'     => array( 'var(--status-info-soft)', 'var(--status-info-line)', 'var(--status-info)', 'info' ),
	'ok'       => array( 'var(--status-ok-soft)', 'var(--status-ok-line)', 'var(--status-ok)', 'check-circle' ),
	'warning'  => array( 'var(--status-warning-soft)', 'var(--status-warning-line)', 'var(--status-warning)', 'alert-triangle' ),
	'critical' => array( 'var(--status-critical-soft)', 'var(--status-critical-line)', 'var(--status-critical)', 'alert-octagon' ),
	'neutral'  => array( 'var(--surface-sunken)', 'var(--border-default)', 'var(--ink-500)', 'info' ),
);

$intera_alert_tone = isset( $intera_alert_tones[ $args['tone'] ] ) ? $args['tone'] : 'info';
list( $intera_alert_bg, $intera_alert_line, $intera_alert_fg, $intera_alert_icon ) = $intera_alert_tones[ $intera_alert_tone ];

if ( $args['icon'] ) {
	$intera_alert_icon = $args['icon'];
}

$intera_alert_style = 'display:flex;gap:var(--space-3)';
$intera_alert_style .= ';background:' . $intera_alert_bg;
$intera_alert_style .= ';border:1px solid ' . $intera_alert_line;
$intera_alert_style .= ';border-radius:var(--radius-md);padding:var(--space-3) var(--space-4)';

if ( $args['style'] ) {
	$intera_alert_style .= ';' . $args['style'];
}

$intera_alert_class = 'itr-alert itr-alert--' . $intera_alert_tone;

if ( $args['class'] ) {
	$intera_alert_class .= ' ' . $args['class'];
}

$intera_alert_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_alert_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_alert_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

$intera_alert_has_body = ( '' !== $args['content'] || '' !== $args['text'] );
?>
<div role="status" class="<?php echo esc_attr( $intera_alert_class ); ?>" style="<?php echo esc_attr( $intera_alert_style ); ?>"<?php echo $intera_alert_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<span style="color:<?php echo esc_attr( $intera_alert_fg ); ?>;margin-top:1px">
		<?php
		if ( function_exists( 'intera_icon' ) ) {
			intera_icon( $intera_alert_icon, array( 'size' => 18 ) );
		}
		?>
	</span>
	<div style="flex:1;min-width:0">
		<?php if ( '' !== $args['title'] ) : ?>
			<div style="font-size:var(--text-md);font-weight:var(--weight-semibold);color:var(--ink-900);line-height:1.4"><?php echo esc_html( $args['title'] ); ?></div>
		<?php endif; ?>
		<?php if ( $intera_alert_has_body ) : ?>
			<div style="font-size:var(--text-sm);color:var(--ink-700);line-height:var(--leading-normal);margin-top:<?php echo '' !== $args['title'] ? '3px' : '0'; ?>">
				<?php
				if ( '' !== $args['content'] ) {
					echo $args['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted template-composed markup.
				} else {
					echo esc_html( $args['text'] );
				}
				?>
			</div>
		<?php endif; ?>
		<?php if ( '' !== $args['action'] ) : ?>
			<div style="margin-top:var(--space-3)"><?php echo $args['action']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted template-composed markup. ?></div>
		<?php endif; ?>
	</div>
	<?php if ( $args['dismissible'] ) : ?>
		<button aria-label="<?php echo esc_attr( $args['dismiss_label'] ? $args['dismiss_label'] : __( 'Dismiss', 'intera' ) ); ?>" type="button" style="border:0;background:none;cursor:pointer;color:var(--ink-500);padding:0;height:18px">
			<?php
			if ( function_exists( 'intera_icon' ) ) {
				intera_icon( 'x', array( 'size' => 16 ) );
			}
			?>
		</button>
	<?php endif; ?>
</div>
