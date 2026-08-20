<?php
/**
 * Component — Field (DS `Field`, components.md §20).
 *
 * `error` takes precedence over `hint` — the two never render together.
 *
 *     get_template_part( 'template-parts/components/field', null, array(
 *         'label'    => 'Work email',
 *         'for'      => 'email',      // 'html_for' is accepted too
 *         'hint'     => '',
 *         'error'    => '',
 *         'required' => false,
 *         'content'  => $control_html, // trusted markup — the control itself
 *         'class'    => '',
 *         'style'    => '',
 *         'attrs'    => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'label'    => '',
		'for'      => '',
		'html_for' => '',
		'hint'     => '',
		'error'    => '',
		'required' => false,
		'content'  => '',
		'children' => '',
		'class'    => '',
		'style'    => '',
		'attrs'    => array(),
	)
);

$intera_field_for = $args['for'] ? $args['for'] : $args['html_for'];

$intera_field_style = 'display:flex;flex-direction:column;gap:6px';

if ( $args['style'] ) {
	$intera_field_style .= ';' . $args['style'];
}

$intera_field_class = 'itr-field';

if ( $args['class'] ) {
	$intera_field_class .= ' ' . $args['class'];
}

$intera_field_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_field_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_field_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

$intera_field_content = '' !== $args['content'] ? $args['content'] : $args['children'];
?>
<div class="<?php echo esc_attr( $intera_field_class ); ?>" style="<?php echo esc_attr( $intera_field_style ); ?>"<?php echo $intera_field_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<?php if ( '' !== $args['label'] ) : ?>
		<label<?php echo $intera_field_for ? ' for="' . esc_attr( $intera_field_for ) . '"' : ''; ?> style="font-size:var(--text-sm);font-weight:var(--weight-medium);color:var(--ink-800);line-height:1.3"><?php
			echo esc_html( $args['label'] );
			if ( $args['required'] ) :
				?><span style="color:var(--status-critical);margin-left:3px">*</span><?php
			endif;
		?></label>
	<?php endif; ?>
	<?php echo $intera_field_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted template-composed markup. ?>
	<?php if ( '' !== $args['error'] ) : ?>
		<div style="font-size:var(--text-xs);color:var(--status-critical);line-height:var(--leading-normal)"><?php echo esc_html( $args['error'] ); ?></div>
	<?php elseif ( '' !== $args['hint'] ) : ?>
		<div style="font-size:var(--text-xs);color:var(--text-muted);line-height:var(--leading-normal)"><?php echo esc_html( $args['hint'] ); ?></div>
	<?php endif; ?>
</div>
