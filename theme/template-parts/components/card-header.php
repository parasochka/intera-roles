<?php
/**
 * Component — CardHeader (DS `CardHeader`, components.md §2).
 *
 *     get_template_part( 'template-parts/components/card-header', null, array(
 *         'title'       => 'IT owns access',
 *         'description' => 'Business owns logic',
 *         'icon'        => '',        // icon name — rendered through intera_icon()
 *         'icon_html'   => '',        // pre-rendered node, wins over 'icon'
 *         'icon_size'   => 18,
 *         'action'      => '',        // trusted markup, rendered as the last child, unwrapped
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
		'title'       => '',
		'description' => '',
		'icon'        => '',
		'icon_html'   => '',
		'icon_size'   => 18,
		'action'      => '',
		'class'       => '',
		'style'       => '',
		'attrs'       => array(),
	)
);

$intera_ch_style = 'display:flex;align-items:flex-start;gap:var(--space-3);margin-bottom:var(--space-4)';

if ( $args['style'] ) {
	$intera_ch_style .= ';' . $args['style'];
}

$intera_ch_class = 'itr-card-header';

if ( $args['class'] ) {
	$intera_ch_class .= ' ' . $args['class'];
}

$intera_ch_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_ch_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_ch_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}
?>
<div class="<?php echo esc_attr( $intera_ch_class ); ?>" style="<?php echo esc_attr( $intera_ch_style ); ?>"<?php echo $intera_ch_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<?php if ( $args['icon_html'] || $args['icon'] ) : ?>
		<div style="margin-top:2px;color:var(--ink-500)">
			<?php
			if ( $args['icon_html'] ) {
				echo $args['icon_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted template-composed markup.
			} elseif ( function_exists( 'intera_icon' ) ) {
				intera_icon( $args['icon'], array( 'size' => (int) $args['icon_size'] ) );
			}
			?>
		</div>
	<?php endif; ?>
	<div style="flex:1;min-width:0">
		<div style="font-size:var(--text-md);font-weight:var(--weight-semibold);color:var(--text-primary);letter-spacing:var(--tracking-snug)"><?php echo esc_html( $args['title'] ); ?></div>
		<?php if ( '' !== $args['description'] ) : ?>
			<div style="font-size:var(--text-sm);color:var(--text-secondary);margin-top:2px;line-height:var(--leading-normal)"><?php echo esc_html( $args['description'] ); ?></div>
		<?php endif; ?>
	</div>
	<?php
	if ( $args['action'] ) {
		echo $args['action']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted template-composed markup.
	}
	?>
</div>
