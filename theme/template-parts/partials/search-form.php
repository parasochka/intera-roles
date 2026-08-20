<?php
/**
 * Partial — the site search field.
 *
 * No mockup draws a search form, so nothing here is ported: the field is
 * assembled out of parts the export already ships. The control is the DS
 * `Input` (`components/input.php`, `.itr-input` — ds-rules §21/§22) with the
 * `search` icon inset on the left, and the submit is the DS `Button`, so the
 * pair matches every other control on the site without a new class or a new
 * rule in assets/css/intera.css.
 *
 *     get_template_part( 'template-parts/partials/search-form', null, array(
 *         'label'        => '',      // default "Search this site"
 *         'show_label'   => false,   // true renders the DS Field label, false a visually hidden one
 *         'placeholder'  => '',      // default "Word, phrase or system name"
 *         'button_label' => '',      // default "Search"
 *         'size'         => 'md',    // md|lg — sizes the input and the button together
 *         'value'        => null,    // null = the current query
 *         'id'           => '',      // auto, and always unique per page
 *         'class'        => '',
 *         'style'        => '',      // appended last
 *         'attrs'        => array(),
 *     ) );
 *
 * The markup also has to reach `get_search_form()`, which WordPress calls from
 * places no template controls (a search widget, the block editor's search
 * block, `comments_template()`), so the same markup is exposed twice:
 *
 * - `intera_search_form_render( $args )` echoes it — what the template part does;
 * - `intera_search_form_get( $args )` returns it, and is filtered onto
 *   `get_search_form` at the bottom of this file.
 *
 * The filter binds as soon as this file has been loaded once. To have it bound
 * on every request — which is what `get_search_form()` needs — this file must be
 * loaded early; it is written to be safe to `require_once` from `inc/setup.php`,
 * because the render is gated on being loaded as a template part. See the note
 * at the foot of the file.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'intera_search_form_render' ) ) :
	/**
	 * Echo the search form.
	 *
	 * @param array $args Arguments, documented in the file header.
	 * @return void
	 */
	function intera_search_form_render( $args = array() ) {
		static $intera_search_form_count = 0;

		$args = wp_parse_args(
			is_array( $args ) ? $args : array(),
			array(
				'label'        => '',
				'show_label'   => false,
				'placeholder'  => '',
				'button_label' => '',
				'size'         => 'md',
				'value'        => null,
				'id'           => '',
				'class'        => '',
				'style'        => '',
				'attrs'        => array(),
			)
		);

		++$intera_search_form_count;

		$intera_sf_size = in_array( $args['size'], array( 'md', 'lg' ), true ) ? $args['size'] : 'md';

		$intera_sf_label  = '' !== trim( (string) $args['label'] ) ? (string) $args['label'] : __( 'Search this site', 'intera' );
		$intera_sf_button = '' !== trim( (string) $args['button_label'] ) ? (string) $args['button_label'] : __( 'Search', 'intera' );

		$intera_sf_placeholder = '' !== trim( (string) $args['placeholder'] )
			? (string) $args['placeholder']
			: __( 'Word, phrase or system name', 'intera' );

		// Every instance gets its own id: 404 and the empty search page both print two.
		$intera_sf_id = '' !== trim( (string) $args['id'] )
			? sanitize_html_class( (string) $args['id'] )
			: 'intera-search-' . $intera_search_form_count;

		$intera_sf_value = null === $args['value'] ? get_search_query( false ) : (string) $args['value'];

		$intera_sf_class = 'intera-search-form';

		if ( $args['class'] ) {
			$intera_sf_class .= ' ' . $args['class'];
		}

		$intera_sf_style = 'display:flex;flex-wrap:wrap;align-items:flex-end;gap:10px';

		if ( $args['style'] ) {
			$intera_sf_style .= ';' . $args['style'];
		}

		$intera_sf_attrs = '';

		foreach ( (array) $args['attrs'] as $intera_sf_attr_key => $intera_sf_attr_value ) {
			if ( false === $intera_sf_attr_value || null === $intera_sf_attr_value ) {
				continue;
			}
			if ( true === $intera_sf_attr_value ) {
				$intera_sf_attrs .= ' ' . esc_attr( $intera_sf_attr_key );
				continue;
			}
			$intera_sf_attrs .= ' ' . esc_attr( $intera_sf_attr_key ) . '="' . esc_attr( $intera_sf_attr_value ) . '"';
		}

		// The control itself, composed once and then either labelled by the DS
		// Field or preceded by a visually hidden label.
		ob_start();
		get_template_part(
			'template-parts/components/input',
			null,
			array(
				'type'        => 'search',
				'id'          => $intera_sf_id,
				'name'        => 's',
				'value'       => $intera_sf_value,
				'placeholder' => $intera_sf_placeholder,
				'size'        => $intera_sf_size,
				'icon_left'   => 'search',
				'attrs'       => array( 'autocomplete' => 'off' ),
			)
		);
		$intera_sf_control = (string) ob_get_clean();
		?>
<form role="search" method="get" class="<?php echo esc_attr( $intera_sf_class ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="<?php echo esc_attr( $intera_sf_style ); ?>"<?php echo $intera_sf_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<div style="flex: 1 1 220px; min-width: 0">
		<?php
		if ( $args['show_label'] ) {
			get_template_part(
				'template-parts/components/field',
				null,
				array(
					'label'   => $intera_sf_label,
					'for'     => $intera_sf_id,
					'content' => $intera_sf_control,
				)
			);
		} else {
			?>
			<label class="screen-reader-text" for="<?php echo esc_attr( $intera_sf_id ); ?>"><?php echo esc_html( $intera_sf_label ); ?></label>
			<?php
			echo $intera_sf_control; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built by components/input.php, every attribute escaped there.
		}
		?>
	</div>
	<?php
	get_template_part(
		'template-parts/components/button',
		null,
		array(
			'label' => $intera_sf_button,
			'type'  => 'submit',
			'size'  => $intera_sf_size,
		)
	);
	?>
</form>
		<?php
	}
endif;

if ( ! function_exists( 'intera_search_form_get' ) ) :
	/**
	 * The search form as a string.
	 *
	 * @param array $args Arguments, documented in the file header.
	 * @return string
	 */
	function intera_search_form_get( $args = array() ) {
		ob_start();
		intera_search_form_render( $args );

		return (string) ob_get_clean();
	}
endif;

if ( ! function_exists( 'intera_search_form_filter' ) ) :
	/**
	 * Replace the core search form with this one.
	 *
	 * `get_search_form()` passes its own argument array through; only
	 * `aria_label` carries anything the theme can use, and it becomes the
	 * field's label so a second form on a page is still distinguishable.
	 *
	 * @param string $form Core markup, discarded.
	 * @param array  $args Arguments from `get_search_form()`.
	 * @return string
	 */
	function intera_search_form_filter( $form, $args = array() ) {
		unset( $form );

		$args = is_array( $args ) ? $args : array();

		return intera_search_form_get(
			array(
				'label' => isset( $args['aria_label'] ) ? (string) $args['aria_label'] : '',
			)
		);
	}
endif;

if ( ! has_filter( 'get_search_form', 'intera_search_form_filter' ) ) {
	add_filter( 'get_search_form', 'intera_search_form_filter', 10, 2 );
}

/*
 * Render only when this file was reached as a template part. `load_template()`
 * defines `$args` in the template's scope (null when a caller passes none), so
 * requiring the file from PHP — which is how the filter above gets bound on
 * every request — defines the functions and prints nothing.
 */
if ( isset( $args ) || isset( $_template_file ) ) {
	intera_search_form_render( isset( $args ) && is_array( $args ) ? $args : array() );
}
