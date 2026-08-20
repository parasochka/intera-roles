<?php
/**
 * Component — Select (DS `Select`, components.md §23; the `.itr-input*` sheet is §21).
 *
 *     get_template_part( 'template-parts/components/select', null, array(
 *         'id'          => 'industry',
 *         'name'        => 'industry',
 *         'options'     => array( 'Telecom', 'Banking' ),          // or array( 'value' => 'Label' ),
 *                                                                  // or array( array( 'value' => …, 'label' => … ) )
 *         'value'       => '',
 *         'placeholder' => 'Choose an industry',                   // leading <option value="">
 *         'size'        => 'md',   // sm|md|lg
 *         'invalid'     => false,
 *         'required'    => false,
 *         'disabled'    => false,
 *         'class'       => '',
 *         'style'       => '',
 *         'attrs'       => array(),
 *     ) );
 *
 * Fix over the export: `aria-invalid` is emitted when invalid, matching Input and Textarea.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'id'          => '',
		'name'        => '',
		'options'     => array(),
		'value'       => '',
		'placeholder' => '',
		'size'        => 'md',
		'invalid'     => false,
		'required'    => false,
		'disabled'    => false,
		'class'       => '',
		'style'       => '',
		'attrs'       => array(),
	)
);

$intera_select_size = in_array( $args['size'], array( 'sm', 'md', 'lg' ), true ) ? $args['size'] : 'md';

$intera_select_class = 'itr-input itr-input--' . $intera_select_size;

if ( $args['invalid'] ) {
	$intera_select_class .= ' itr-input--invalid';
}

if ( $args['class'] ) {
	$intera_select_class .= ' ' . $args['class'];
}

$intera_select_style = 'appearance:none;padding-right:32px;cursor:pointer';

if ( $args['style'] ) {
	$intera_select_style .= ';' . $args['style'];
}

$intera_select_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_select_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_select_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}
?>
<span style="position:relative;display:block">
	<select class="<?php echo esc_attr( $intera_select_class ); ?>"
		<?php echo $args['id'] ? ' id="' . esc_attr( $args['id'] ) . '"' : ''; ?>
		<?php echo $args['name'] ? ' name="' . esc_attr( $args['name'] ) . '"' : ''; ?>
		<?php echo $args['invalid'] ? ' aria-invalid="true"' : ''; ?>
		<?php echo $args['required'] ? ' required' : ''; ?>
		<?php echo $args['disabled'] ? ' disabled' : ''; ?>
		<?php echo $intera_select_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>
		style="<?php echo esc_attr( $intera_select_style ); ?>">
		<?php if ( '' !== $args['placeholder'] ) : ?>
			<option value=""><?php echo esc_html( $args['placeholder'] ); ?></option>
		<?php endif; ?>
		<?php
		foreach ( (array) $args['options'] as $intera_option_key => $intera_option ) {
			if ( is_array( $intera_option ) ) {
				$intera_option_value = isset( $intera_option['value'] ) ? $intera_option['value'] : '';
				$intera_option_label = isset( $intera_option['label'] ) ? $intera_option['label'] : $intera_option_value;
			} elseif ( is_string( $intera_option_key ) ) {
				$intera_option_value = $intera_option_key;
				$intera_option_label = $intera_option;
			} else {
				$intera_option_value = $intera_option;
				$intera_option_label = $intera_option;
			}
			?>
			<option value="<?php echo esc_attr( $intera_option_value ); ?>"<?php selected( (string) $args['value'], (string) $intera_option_value ); ?>><?php echo esc_html( $intera_option_label ); ?></option>
			<?php
		}
		?>
	</select>
	<span style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:var(--ink-500);pointer-events:none">
		<?php
		if ( function_exists( 'intera_icon' ) ) {
			intera_icon( 'chevron-down', array( 'size' => 16 ) );
		}
		?>
	</span>
</span>
