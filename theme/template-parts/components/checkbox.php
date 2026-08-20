<?php
/**
 * Component — Checkbox (DS `Checkbox`, components.md §19).
 *
 *     get_template_part( 'template-parts/components/checkbox', null, array(
 *         'id'            => '',      // generated when empty
 *         'name'          => 'consent',
 *         'value'         => '1',
 *         'label'         => 'I agree that INTERA may contact me about this request.',
 *         'description'   => '',
 *         'checked'       => false,
 *         'indeterminate' => false,
 *         'disabled'      => false,
 *         'required'      => false,
 *         'class'         => '',
 *         'style'         => '',
 *         'attrs'         => array(),   // land on the real <input>
 *     ) );
 *
 * Fix over the export: the `<label>` is `position:relative` so the visually hidden input is
 * contained instead of escaping to the nearest positioned ancestor.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'id'            => '',
		'name'          => '',
		'value'         => '1',
		'label'         => '',
		'description'   => '',
		'checked'       => false,
		'indeterminate' => false,
		'disabled'      => false,
		'required'      => false,
		'class'         => '',
		'style'         => '',
		'attrs'         => array(),
	)
);

$intera_cb_id = $args['id'] ? $args['id'] : wp_unique_id( 'intera-checkbox-' );
$intera_cb_on = ( $args['checked'] || $args['indeterminate'] );

$intera_cb_style = 'display:inline-flex;align-items:flex-start;gap:9px;position:relative';
$intera_cb_style .= ';cursor:' . ( $args['disabled'] ? 'not-allowed' : 'pointer' );
$intera_cb_style .= ';opacity:' . ( $args['disabled'] ? '.5' : '1' );

if ( $args['style'] ) {
	$intera_cb_style .= ';' . $args['style'];
}

$intera_cb_class = 'itr-checkbox';

if ( $args['class'] ) {
	$intera_cb_class .= ' ' . $args['class'];
}

$intera_cb_box_style = 'width:16px;height:16px;margin-top:2px;flex:none;display:grid;place-items:center;border-radius:var(--radius-xs)';
$intera_cb_box_style .= ';border:1px solid ' . ( $intera_cb_on ? 'var(--action-primary)' : 'var(--border-strong)' );
$intera_cb_box_style .= ';background:' . ( $intera_cb_on ? 'var(--action-primary)' : 'var(--surface-card)' );
$intera_cb_box_style .= ';color:var(--white);transition:var(--transition-control)';

$intera_cb_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_cb_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_cb_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}
?>
<label for="<?php echo esc_attr( $intera_cb_id ); ?>" class="<?php echo esc_attr( $intera_cb_class ); ?>" style="<?php echo esc_attr( $intera_cb_style ); ?>">
	<input id="<?php echo esc_attr( $intera_cb_id ); ?>" type="checkbox"
		<?php if ( $args['name'] ) : ?>name="<?php echo esc_attr( $args['name'] ); ?>" <?php endif; ?>
		value="<?php echo esc_attr( $args['value'] ); ?>"
		<?php checked( (bool) $args['checked'], true ); ?>
		<?php disabled( (bool) $args['disabled'], true ); ?>
		<?php echo $args['required'] ? ' required' : ''; ?>
		<?php echo $args['indeterminate'] ? ' aria-checked="mixed"' : ''; ?>
		<?php echo $intera_cb_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>
		style="position:absolute;opacity:0;width:16px;height:16px;margin:0">
	<span aria-hidden="true" style="<?php echo esc_attr( $intera_cb_box_style ); ?>">
		<?php
		if ( function_exists( 'intera_icon' ) ) {
			if ( $args['indeterminate'] ) {
				intera_icon(
					'minus',
					array(
						'size'   => 12,
						'stroke' => 3,
					)
				);
			} elseif ( $args['checked'] ) {
				intera_icon(
					'check',
					array(
						'size'   => 12,
						'stroke' => 3,
					)
				);
			}
		}
		?>
	</span>
	<?php if ( '' !== $args['label'] ) : ?>
		<span style="min-width:0">
			<span style="display:block;font-size:var(--text-md);color:var(--ink-800);line-height:1.4"><?php echo esc_html( $args['label'] ); ?></span>
			<?php if ( '' !== $args['description'] ) : ?>
				<span style="display:block;font-size:var(--text-xs);color:var(--text-muted);margin-top:2px"><?php echo esc_html( $args['description'] ); ?></span>
			<?php endif; ?>
		</span>
	<?php endif; ?>
</label>
