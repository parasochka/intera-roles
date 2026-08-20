<?php
/**
 * Component — Input (DS `Input`, components.md §22; the `.itr-input*` sheet is §21).
 *
 * The `.itr-input*` rules (the export's injected `itr-field-css` sheet) live in
 * assets/css/intera.css. `.itr-input--md` is not defined there — the base rule is the md size —
 * but the class is still emitted, exactly as the export does.
 *
 *     get_template_part( 'template-parts/components/input', null, array(
 *         'type'        => 'text',
 *         'id'          => 'email',
 *         'name'        => 'email',
 *         'value'       => '',
 *         'placeholder' => 'a.kovalenko@company.com',
 *         'size'        => 'md',    // sm|md|lg
 *         'invalid'     => false,
 *         'mono'        => false,
 *         'icon_left'   => '',      // 16px icon, inset 11px; the input gains padding-left:34px
 *         'required'    => false,
 *         'disabled'    => false,
 *         'readonly'    => false,
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
		'type'        => 'text',
		'id'          => '',
		'name'        => '',
		'value'       => '',
		'placeholder' => '',
		'size'        => 'md',
		'invalid'     => false,
		'mono'        => false,
		'icon_left'   => '',
		'required'    => false,
		'disabled'    => false,
		'readonly'    => false,
		'class'       => '',
		'style'       => '',
		'attrs'       => array(),
	)
);

$intera_input_size = in_array( $args['size'], array( 'sm', 'md', 'lg' ), true ) ? $args['size'] : 'md';

$intera_input_class = 'itr-input itr-input--' . $intera_input_size;

if ( $args['invalid'] ) {
	$intera_input_class .= ' itr-input--invalid';
}

if ( $args['mono'] ) {
	$intera_input_class .= ' itr-input--mono';
}

if ( $args['class'] ) {
	$intera_input_class .= ' ' . $args['class'];
}

$intera_input_style = $args['icon_left'] ? 'padding-left:34px' : '';

if ( $args['style'] ) {
	$intera_input_style .= ( $intera_input_style ? ';' : '' ) . $args['style'];
}

$intera_input_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_input_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_input_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

$intera_input_html = '<input class="' . esc_attr( $intera_input_class ) . '"'
	. ' type="' . esc_attr( $args['type'] ) . '"'
	. ( $args['id'] ? ' id="' . esc_attr( $args['id'] ) . '"' : '' )
	. ( $args['name'] ? ' name="' . esc_attr( $args['name'] ) . '"' : '' )
	. ( '' !== $args['value'] ? ' value="' . esc_attr( $args['value'] ) . '"' : '' )
	. ( '' !== $args['placeholder'] ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '' )
	. ( $args['invalid'] ? ' aria-invalid="true"' : '' )
	. ( $args['required'] ? ' required' : '' )
	. ( $args['disabled'] ? ' disabled' : '' )
	. ( $args['readonly'] ? ' readonly' : '' )
	. $intera_input_attrs
	. ( $intera_input_style ? ' style="' . esc_attr( $intera_input_style ) . '"' : '' )
	. '>';

if ( ! $args['icon_left'] ) {
	echo $intera_input_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- every attribute escaped above.
	return;
}
?>
<span style="position:relative;display:block">
	<span style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none">
		<?php
		if ( function_exists( 'intera_icon' ) ) {
			intera_icon( $args['icon_left'], array( 'size' => 16 ) );
		}
		?>
	</span>
	<?php echo $intera_input_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- every attribute escaped above. ?>
</span>
