<?php
/**
 * Post meta and the admin UI that edits it.
 *
 * One table, `intera_meta_fields()`, drives three things: `register_post_meta()`
 * (so the block editor and the REST API see the fields), the meta boxes an
 * editor actually types into, and the save handler. Add a field once and all
 * three follow.
 *
 * Every field below is consumed by markup in the design export — nothing here
 * is speculative. Anything an editor might reword lives in a field; layout,
 * spacing and colour stay in the templates.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * The post meta this theme registers, grouped by post type.
 *
 * Each field:
 *   label       — admin label.
 *   type        — text | icon | url | checkbox | rows (how the meta box renders it).
 *   rest_type   — the `register_post_meta()` type.
 *   rest_schema — optional. The REST schema of a field that stores rows rather
 *                 than a scalar; `show_in_rest` becomes that schema.
 *   sanitize    — sanitize_callback, also used by the save handler.
 *   default     — registered default.
 *   description — help text under the control, and the REST description.
 *
 * @return array<string,array<string,array<string,mixed>>> post type => key => field.
 */
function intera_meta_fields() {
	return array(
		'role' => array(
			'_intera_role_icon'    => array(
				'label'       => __( 'Icon', 'intera' ),
				'type'        => 'icon',
				'rest_type'   => 'string',
				'sanitize'    => 'intera_sanitize_icon_name',
				'default'     => '',
				'description' => __( 'Lucide icon name shown beside the role title — for example circle-dollar-sign, gauge, scale, heart-pulse or shield-check.', 'intera' ),
			),
			'_intera_role_summary' => array(
				'label'       => __( 'One-line summary', 'intera' ),
				'type'        => 'text',
				'rest_type'   => 'string',
				'sanitize'    => 'sanitize_text_field',
				'default'     => '',
				'description' => __( 'The single line under the role title on the home page, for example “See financial risks before they become problems.”', 'intera' ),
			),
			'_intera_role_tags'    => array(
				'label'       => __( 'Tags', 'intera' ),
				'type'        => 'text',
				'rest_type'   => 'string',
				'sanitize'    => 'sanitize_text_field',
				'default'     => '',
				'description' => __( 'Comma-separated chips shown under the role on the product page, for example “12 metrics, 4 reconciliations”.', 'intera' ),
			),
		),
		'plan' => array(
			'_intera_plan_price'        => array(
				'label'       => __( 'Price', 'intera' ),
				'type'        => 'text',
				'rest_type'   => 'string',
				'sanitize'    => 'sanitize_text_field',
				'default'     => '',
				'description' => __( 'The headline figure on the card — “€0”, or a sentence such as “Free for the first 12 months”. Leave empty for a plan that lists its tiers in the content instead.', 'intera' ),
			),
			'_intera_plan_period'       => array(
				'label'       => __( 'Period', 'intera' ),
				'type'        => 'text',
				'rest_type'   => 'string',
				'sanitize'    => 'sanitize_text_field',
				'default'     => '',
				'description' => __( 'The small line between the plan name and the price — “For evaluation”, “For beta partners”, “For rollout across teams”.', 'intera' ),
			),
			'_intera_plan_featured'     => array(
				'label'       => __( 'Featured plan', 'intera' ),
				'type'        => 'checkbox',
				'rest_type'   => 'boolean',
				'sanitize'    => 'rest_sanitize_boolean',
				'default'     => false,
				'description' => __( 'Raises the card, adds the accent rule and shows the Beta badge. Only one plan should be featured.', 'intera' ),
			),
			'_intera_plan_cta_label'    => array(
				'label'       => __( 'CTA label', 'intera' ),
				'type'        => 'text',
				'rest_type'   => 'string',
				'sanitize'    => 'sanitize_text_field',
				'default'     => '',
				'description' => __( 'Text on the button at the foot of the card, for example “Become an Early Adopter”.', 'intera' ),
			),
			'_intera_plan_cta_url'      => array(
				'label'       => __( 'CTA target', 'intera' ),
				'type'        => 'url',
				'rest_type'   => 'string',
				'sanitize'    => 'esc_url_raw',
				'default'     => '',
				'description' => __( 'Where the button goes.', 'intera' ),
			),
			'_intera_plan_capabilities' => array(
				'label'       => __( 'Capabilities', 'intera' ),
				'type'        => 'rows',
				'rest_type'   => 'array',
				'rest_schema' => array(
					'type'  => 'array',
					'items' => array(
						'type'                 => 'object',
						'properties'           => array(
							'label' => array( 'type' => 'string' ),
							'value' => array( 'type' => 'string' ),
						),
						'additionalProperties' => false,
					),
				),
				'sanitize'    => 'intera_sanitize_plan_capabilities',
				'default'     => array(),
				'description' => __( 'One row per limit in the comparison table on the pricing page: the capability — “Users” — and this plan’s figure — “25”. The table compares the plans row by row, so a capability has to be written the same way on every plan that offers it; a plan that leaves one out gets a dash in that cell. The first plan sets the order of the table.', 'intera' ),
			),
		),
		'docs' => array(
			'_intera_doc_version' => array(
				'label'       => __( 'Version stamp', 'intera' ),
				'type'        => 'text',
				'rest_type'   => 'string',
				'sanitize'    => 'sanitize_text_field',
				'default'     => '',
				'description' => __( 'Optional. Shown in the meta strip beside the updated date and the reading time — for example “v0.003”. Leave empty to hide it.', 'intera' ),
			),
		),
	);
}

/**
 * Register every field with `register_post_meta()`.
 *
 * @return void
 */
function intera_register_post_meta() {
	foreach ( intera_meta_fields() as $post_type => $fields ) {
		foreach ( $fields as $meta_key => $field ) {
			$show_in_rest = isset( $field['rest_schema'] )
				? array( 'schema' => $field['rest_schema'] )
				: true;

			register_post_meta(
				$post_type,
				$meta_key,
				array(
					'type'              => $field['rest_type'],
					'description'       => $field['description'],
					'single'            => true,
					'default'           => $field['default'],
					'show_in_rest'      => $show_in_rest,
					'sanitize_callback' => $field['sanitize'],
					'auth_callback'     => 'intera_post_meta_auth',
				)
			);
		}
	}
}
add_action( 'init', 'intera_register_post_meta', 12 );

/**
 * Who may read and write this theme's post meta.
 *
 * @param bool   $allowed  Whether the user can add the meta. Unused.
 * @param string $meta_key Meta key. Unused.
 * @param int    $post_id  Post ID.
 * @return bool
 */
function intera_post_meta_auth( $allowed, $meta_key, $post_id ) {
	unset( $allowed, $meta_key );

	return current_user_can( 'edit_post', (int) $post_id );
}

/**
 * Add one meta box per post type that has fields.
 *
 * @return void
 */
function intera_add_meta_boxes() {
	$titles = array(
		'role' => __( 'Role card', 'intera' ),
		'plan' => __( 'Plan card', 'intera' ),
		'docs' => __( 'Doc details', 'intera' ),
	);

	foreach ( array_keys( intera_meta_fields() ) as $post_type ) {
		add_meta_box(
			'intera-meta-' . $post_type,
			isset( $titles[ $post_type ] ) ? $titles[ $post_type ] : __( 'INTERA details', 'intera' ),
			'intera_render_meta_box',
			$post_type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'intera_add_meta_boxes' );

/**
 * Render the meta box for a post.
 *
 * @param WP_Post $post Post being edited.
 * @return void
 */
function intera_render_meta_box( $post ) {
	$all_fields = intera_meta_fields();

	if ( empty( $all_fields[ $post->post_type ] ) ) {
		return;
	}

	$fields = $all_fields[ $post->post_type ];

	wp_nonce_field( 'intera_save_meta_' . $post->ID, 'intera_meta_nonce' );

	$needs_datalist = false;
	foreach ( $fields as $field ) {
		if ( 'icon' === $field['type'] ) {
			$needs_datalist = true;
		}
	}

	if ( $needs_datalist && function_exists( 'intera_render_icon_datalist' ) ) {
		intera_render_icon_datalist();
	}

	echo '<table class="form-table" role="presentation"><tbody>';

	foreach ( $fields as $meta_key => $field ) {
		$field_id = 'intera-field-' . sanitize_html_class( ltrim( $meta_key, '_' ) );
		$value    = get_post_meta( $post->ID, $meta_key, true );

		echo '<tr>';

		// A repeatable field has no single control for a `for` attribute to point at.
		if ( 'rows' === $field['type'] ) {
			echo '<th scope="row">' . esc_html( $field['label'] ) . '</th>';
		} else {
			echo '<th scope="row"><label for="' . esc_attr( $field_id ) . '">' . esc_html( $field['label'] ) . '</label></th>';
		}

		echo '<td>';

		switch ( $field['type'] ) {
			case 'checkbox':
				echo '<label for="' . esc_attr( $field_id ) . '">';
				echo '<input type="checkbox" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $meta_key ) . '" value="1" ' . checked( true, (bool) $value, false ) . ' /> ';
				echo esc_html( $field['label'] );
				echo '</label>';
				break;

			case 'url':
				echo '<input type="url" class="regular-text code" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $meta_key ) . '" value="' . esc_url( (string) $value ) . '" />';
				break;

			case 'icon':
				echo '<input type="text" class="regular-text" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $meta_key ) . '" value="' . esc_attr( (string) $value ) . '" list="intera-icon-names" autocomplete="off" />';
				break;

			case 'rows':
				intera_render_meta_rows( $meta_key, $field_id, $value );
				break;

			case 'text':
			default:
				echo '<input type="text" class="regular-text" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $meta_key ) . '" value="' . esc_attr( (string) $value ) . '" />';
				break;
		}

		echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
		echo '</td></tr>';
	}

	echo '</tbody></table>';
}

/**
 * Render a repeatable label/value field as an editable table.
 *
 * No JavaScript: the rows are plain inputs, three empty rows sit at the bottom
 * to add with, each row carries the position it will be saved at — edit the
 * numbers to reorder — and a Remove box drops a row on the next save. Only core
 * admin classes are used, so the box looks like every other one.
 *
 * @param string $meta_key Meta key the inputs post under.
 * @param string $field_id Base id for the row controls.
 * @param mixed  $value    Stored rows.
 * @return void
 */
function intera_render_meta_rows( $meta_key, $field_id, $value ) {
	$rows  = is_array( $value ) ? array_values( $value ) : array();
	$total = count( $rows ) + 3;

	echo '<table class="widefat striped">';
	echo '<thead><tr>';
	echo '<th scope="col">' . esc_html__( 'Order', 'intera' ) . '</th>';
	echo '<th scope="col">' . esc_html__( 'Capability', 'intera' ) . '</th>';
	echo '<th scope="col">' . esc_html__( 'Value', 'intera' ) . '</th>';
	echo '<th scope="col">' . esc_html__( 'Remove', 'intera' ) . '</th>';
	echo '</tr></thead><tbody>';

	for ( $index = 0; $index < $total; $index++ ) {
		$row      = isset( $rows[ $index ] ) && is_array( $rows[ $index ] ) ? $rows[ $index ] : array();
		$label    = isset( $row['label'] ) ? (string) $row['label'] : '';
		$figure   = isset( $row['value'] ) ? (string) $row['value'] : '';
		$position = $index + 1;
		$name     = $meta_key . '[' . $index . ']';
		$row_id   = $field_id . '-' . $index;

		echo '<tr>';

		echo '<td><label class="screen-reader-text" for="' . esc_attr( $row_id . '-order' ) . '">';
		/* translators: %d: row number. */
		echo esc_html( sprintf( __( 'Order of row %d', 'intera' ), $position ) );
		echo '</label>';
		echo '<input type="number" class="small-text" id="' . esc_attr( $row_id . '-order' ) . '" name="' . esc_attr( $name . '[order]' ) . '" value="' . esc_attr( (string) $position ) . '" min="1" step="1" /></td>';

		echo '<td><label class="screen-reader-text" for="' . esc_attr( $row_id . '-label' ) . '">';
		/* translators: %d: row number. */
		echo esc_html( sprintf( __( 'Capability in row %d', 'intera' ), $position ) );
		echo '</label>';
		echo '<input type="text" class="regular-text" id="' . esc_attr( $row_id . '-label' ) . '" name="' . esc_attr( $name . '[label]' ) . '" value="' . esc_attr( $label ) . '" /></td>';

		echo '<td><label class="screen-reader-text" for="' . esc_attr( $row_id . '-value' ) . '">';
		/* translators: %d: row number. */
		echo esc_html( sprintf( __( 'Value in row %d', 'intera' ), $position ) );
		echo '</label>';
		echo '<input type="text" class="regular-text" id="' . esc_attr( $row_id . '-value' ) . '" name="' . esc_attr( $name . '[value]' ) . '" value="' . esc_attr( $figure ) . '" /></td>';

		echo '<td><label for="' . esc_attr( $row_id . '-remove' ) . '">';
		echo '<input type="checkbox" id="' . esc_attr( $row_id . '-remove' ) . '" name="' . esc_attr( $name . '[remove]' ) . '" value="1" /> ';
		/* translators: %d: row number. */
		echo esc_html( sprintf( __( 'Remove row %d', 'intera' ), $position ) );
		echo '</label></td>';

		echo '</tr>';
	}

	echo '</tbody></table>';
}

/**
 * Sanitize the plan capability rows.
 *
 * Accepts both shapes: what the meta box posts (an order number and a Remove
 * box beside each pair) and what REST sends (label and value only). Rows are
 * sorted by the order number, ties keep the order they arrived in, and a row
 * without a capability is not a row. Only `label` and `value` are stored.
 *
 * @param mixed $value Raw rows.
 * @return array<int,array<string,string>>
 */
function intera_sanitize_plan_capabilities( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}

	$rows     = array();
	$position = 0;

	foreach ( $value as $row ) {
		++$position;

		if ( ! is_array( $row ) || ! empty( $row['remove'] ) ) {
			continue;
		}

		$label = isset( $row['label'] ) ? sanitize_text_field( (string) $row['label'] ) : '';

		if ( '' === $label ) {
			continue;
		}

		$rows[] = array(
			'order'  => isset( $row['order'] ) ? (int) $row['order'] : $position,
			'tie'    => $position,
			'label'  => $label,
			'figure' => isset( $row['value'] ) ? sanitize_text_field( (string) $row['value'] ) : '',
		);
	}

	usort(
		$rows,
		static function ( $a, $b ) {
			if ( $a['order'] === $b['order'] ) {
				return $a['tie'] <=> $b['tie'];
			}

			return $a['order'] <=> $b['order'];
		}
	);

	$clean = array();

	foreach ( $rows as $row ) {
		$clean[] = array(
			'label' => $row['label'],
			'value' => $row['figure'],
		);
	}

	return $clean;
}

/**
 * The capability rows of one plan, ready for output.
 *
 * Each row is `key`, `label` and `value`. The key is the label folded to lower
 * case with its spaces collapsed, which is how the pricing table lines the same
 * capability up across plans.
 *
 * @param int $post_id Plan ID. Defaults to the current post.
 * @return array<int,array<string,string>>
 */
function intera_plan_capabilities_get( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();

	if ( $post_id <= 0 ) {
		return array();
	}

	$stored = get_post_meta( $post_id, '_intera_plan_capabilities', true );

	if ( ! is_array( $stored ) ) {
		return array();
	}

	$rows = array();

	foreach ( $stored as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$label = isset( $row['label'] ) ? trim( (string) $row['label'] ) : '';

		if ( '' === $label ) {
			continue;
		}

		$rows[] = array(
			'key'   => intera_plan_capability_key( $label ),
			'label' => $label,
			'value' => isset( $row['value'] ) ? trim( (string) $row['value'] ) : '',
		);
	}

	return $rows;
}

/**
 * Fold a capability label into the key two plans are compared on.
 *
 * @param string $label Capability label.
 * @return string
 */
function intera_plan_capability_key( $label ) {
	$key = trim( wp_strip_all_tags( (string) $label ) );
	$key = preg_replace( '/\s+/u', ' ', $key );

	return function_exists( 'mb_strtolower' ) ? mb_strtolower( $key, 'UTF-8' ) : strtolower( $key );
}

/**
 * Persist the meta box values.
 *
 * Bails on autosaves and revisions, requires our nonce, and re-checks the
 * capability against this specific post.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @return void
 */
function intera_save_post_meta( $post_id, $post ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	$all_fields = intera_meta_fields();

	if ( ! $post instanceof WP_Post || empty( $all_fields[ $post->post_type ] ) ) {
		return;
	}

	if ( ! isset( $_POST['intera_meta_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['intera_meta_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'intera_save_meta_' . $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( $all_fields[ $post->post_type ] as $meta_key => $field ) {

		if ( 'rows' === $field['type'] ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- the field's own sanitize callback runs on the next line.
			$posted = isset( $_POST[ $meta_key ] ) ? wp_unslash( $_POST[ $meta_key ] ) : array();
			$rows   = call_user_func( $field['sanitize'], $posted );

			if ( empty( $rows ) ) {
				delete_post_meta( $post_id, $meta_key );
			} else {
				update_post_meta( $post_id, $meta_key, $rows );
			}

			continue;
		}

		if ( 'checkbox' === $field['type'] ) {
			if ( empty( $_POST[ $meta_key ] ) ) {
				delete_post_meta( $post_id, $meta_key );
			} else {
				update_post_meta( $post_id, $meta_key, true );
			}
			continue;
		}

		if ( ! isset( $_POST[ $meta_key ] ) ) {
			continue;
		}

		$value = call_user_func( $field['sanitize'], wp_unslash( $_POST[ $meta_key ] ) );

		if ( '' === $value || null === $value ) {
			delete_post_meta( $post_id, $meta_key );
		} else {
			update_post_meta( $post_id, $meta_key, $value );
		}
	}
}
add_action( 'save_post', 'intera_save_post_meta', 10, 2 );
