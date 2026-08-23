<?php
/**
 * Custom post types, taxonomies and term meta.
 *
 * The mockups treat roles, plans and documentation as *records*, not copy, so
 * every one of them is editable in WordPress. Layout, spacing and colour stay
 * in the templates; the words and the ordering live here.
 *
 * Term meta registered by this file is read through the contract helpers
 * `intera_term_icon_get()` / `intera_term_tone_get()` in inc/template-tags.php.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta key holding a `doc_category` term's Lucide icon name.
 *
 * @var string
 */
define( 'INTERA_TERM_ICON_KEY', '_intera_term_icon' );

/**
 * Meta key holding a `doc_category` term's colour family.
 *
 * @var string
 */
define( 'INTERA_TERM_TONE_KEY', '_intera_term_tone' );

/**
 * The colour families a docs category may use.
 *
 * Each one is the `--{tone}-50 / --{tone}-100 / --{tone}-600` token triple the
 * `11-docs` and `13-docs-category` screens paint the category chip with. The
 * values are token *names*, not colours — nothing here duplicates a token.
 *
 * @return array<string,string> tone slug => admin label.
 */
function intera_term_tones() {
	return array(
		'blue'   => __( 'Blue', 'intera' ),
		'teal'   => __( 'Teal', 'intera' ),
		'violet' => __( 'Violet', 'intera' ),
		'amber'  => __( 'Amber', 'intera' ),
	);
}

if ( ! function_exists( 'intera_sanitize_tone' ) ) :
	/**
	 * Clamp a colour family to the four the design system defines.
	 *
	 * @param mixed $value Raw value.
	 * @return string One of blue|teal|violet|amber.
	 */
	function intera_sanitize_tone( $value ) {
		$value = is_scalar( $value ) ? strtolower( trim( (string) $value ) ) : '';

		return array_key_exists( $value, intera_term_tones() ) ? $value : 'blue';
	}
endif;

if ( ! function_exists( 'intera_sanitize_icon_name' ) ) :
	/**
	 * Sanitise a Lucide icon name.
	 *
	 * Icons are inlined by `intera_icon()`; an unknown name simply renders
	 * nothing, so the sanitiser only has to guarantee a safe slug shape.
	 *
	 * @param mixed $value Raw value.
	 * @return string Lowercase `a-z0-9-` slug, or '' when unset.
	 */
	function intera_sanitize_icon_name( $value ) {
		$value = is_scalar( $value ) ? strtolower( trim( (string) $value ) ) : '';

		return (string) preg_replace( '/[^a-z0-9-]/', '', $value );
	}
endif;

if ( ! function_exists( 'intera_admin_icon_suggestions' ) ) :
	/**
	 * Icon names offered as a `<datalist>` on every icon field in the admin.
	 *
	 * This is the complete set the export uses — suggestions only, so an editor
	 * can still type any name `intera_icon()` knows.
	 *
	 * @return string[]
	 */
	function intera_admin_icon_suggestions() {
		return array(
			'activity',
			'alert-octagon',
			'alert-triangle',
			'arrow-right',
			'bell',
			'book-open',
			'boxes',
			'check',
			'check-circle',
			'chevron-down',
			'chevron-left',
			'chevron-right',
			'circle-dollar-sign',
			'circle-dot',
			'clipboard-check',
			'clock',
			'contact',
			'corner-down-right',
			'database',
			'eye',
			'gauge',
			'git-branch',
			'globe',
			'heart-pulse',
			'info',
			'landmark',
			'layers',
			'link',
			'lock',
			'mail',
			'menu',
			'minus',
			'package',
			'plug',
			'printer',
			'radio-tower',
			'receipt',
			'repeat',
			'rocket',
			'route-off',
			'scale',
			'search',
			'settings',
			'shield-check',
			'ship',
			'sliders-horizontal',
			'table-2',
			'terminal',
			'thumbs-down',
			'thumbs-up',
			'trending-down',
			'trending-up',
			'x',
		);
	}
endif;

if ( ! function_exists( 'intera_render_icon_datalist' ) ) :
	/**
	 * Print the shared `<datalist>` icon names are suggested from.
	 *
	 * Safe to call more than once per screen — it only prints the first time.
	 *
	 * @return void
	 */
	function intera_render_icon_datalist() {
		static $printed = false;

		if ( $printed ) {
			return;
		}

		$printed = true;

		echo '<datalist id="intera-icon-names">';
		foreach ( intera_admin_icon_suggestions() as $icon_name ) {
			echo '<option value="' . esc_attr( $icon_name ) . '"></option>';
		}
		echo '</datalist>';
	}
endif;

/**
 * Register the three custom post types.
 *
 * `docs` is public and archived (`archive-docs.php`, `single-docs.php`).
 * `role` and `plan` are records rendered inside other templates — they have no
 * single view of their own in the design, so they stay out of the front end
 * routing while remaining fully editable and REST-visible.
 *
 * @return void
 */
function intera_register_post_types() {

	/*
	 * `docs` is only ours when nobody else has claimed it.
	 *
	 * This site runs BetterDocs, which registers `docs` itself and already
	 * holds twenty-six published articles on /docs/. Re-registering the type
	 * would rewrite its rewrite rules and labels out from under the plugin and
	 * put the existing URLs at risk, so the theme steps aside and renders that
	 * content through its own templates instead (see
	 * `intera_docs_template_include()` below). The registration stays here for
	 * the case where the plugin is switched off: the theme then owns the type
	 * again and the docs screens keep working.
	 */
	if ( post_type_exists( 'docs' ) ) {
		return intera_register_record_post_types();
	}

	register_post_type(
		'docs',
		array(
			'labels'          => array(
				'name'               => __( 'Documentation', 'intera' ),
				'singular_name'      => __( 'Doc', 'intera' ),
				'menu_name'          => __( 'Documentation', 'intera' ),
				'add_new'            => __( 'Add doc', 'intera' ),
				'add_new_item'       => __( 'Add doc', 'intera' ),
				'edit_item'          => __( 'Edit doc', 'intera' ),
				'new_item'           => __( 'New doc', 'intera' ),
				'view_item'          => __( 'View doc', 'intera' ),
				'view_items'         => __( 'View documentation', 'intera' ),
				'search_items'       => __( 'Search documentation', 'intera' ),
				'not_found'          => __( 'No docs found.', 'intera' ),
				'not_found_in_trash' => __( 'No docs found in Trash.', 'intera' ),
				'all_items'          => __( 'All docs', 'intera' ),
				'archives'           => __( 'Documentation', 'intera' ),
				'attributes'         => __( 'Order and parent', 'intera' ),
			),
			'description'     => __( 'Documentation articles. The Order field carries the 01…10 ordinals shown on a category page.', 'intera' ),
			'public'          => true,
			'show_in_rest'    => true,
			'menu_position'   => 21,
			'menu_icon'       => 'dashicons-media-document',
			'supports'        => array( 'title', 'editor', 'excerpt', 'page-attributes', 'revisions', 'custom-fields' ),
			'taxonomies'      => array( 'doc_category' ),
			'has_archive'     => 'docs',
			'rewrite'         => array(
				'slug'       => 'docs',
				'with_front' => false,
			),
			'hierarchical'    => false,
			'capability_type' => 'post',
		)
	);

	intera_register_record_post_types();
}

/**
 * Register the two record post types.
 *
 * `role` and `plan` are records rendered inside other templates — they have no
 * single view of their own in the design, so they stay out of the front end
 * routing while remaining fully editable and REST-visible. They are split out
 * of `intera_register_post_types()` so that the early return taken when a
 * plugin already owns `docs` never costs us these two.
 *
 * @return void
 */
function intera_register_record_post_types() {

	/*
	 * `custom-fields` is in the supports list of both record types for one
	 * reason: WordPress only exposes a post type's `meta` over REST when the
	 * type declares it. Without it the icon, summary, tags, price, period and
	 * capability rows are editable in wp-admin but invisible to the REST API,
	 * so nothing can populate a role or a plan except a human typing it in.
	 */
	register_post_type(
		'role',
		array(
			'labels'              => array(
				'name'               => __( 'INTERA Roles', 'intera' ),
				'singular_name'      => __( 'Role', 'intera' ),
				'menu_name'          => __( 'INTERA Roles', 'intera' ),
				'add_new'            => __( 'Add role', 'intera' ),
				'add_new_item'       => __( 'Add role', 'intera' ),
				'edit_item'          => __( 'Edit role', 'intera' ),
				'new_item'           => __( 'New role', 'intera' ),
				'search_items'       => __( 'Search roles', 'intera' ),
				'not_found'          => __( 'No roles found.', 'intera' ),
				'not_found_in_trash' => __( 'No roles found in Trash.', 'intera' ),
				'all_items'          => __( 'All roles', 'intera' ),
				'attributes'         => __( 'Order', 'intera' ),
			),
			'description'         => __( 'The pre-built INTERA Roles shown on the home page and the product page. The Order field sets the order of the cards.', 'intera' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => true,
			'menu_position'       => 22,
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'editor', 'excerpt', 'page-attributes', 'revisions', 'custom-fields' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'hierarchical'        => false,
			'capability_type'     => 'post',
		)
	);

	register_post_type(
		'plan',
		array(
			'labels'              => array(
				'name'               => __( 'Pricing plans', 'intera' ),
				'singular_name'      => __( 'Plan', 'intera' ),
				'menu_name'          => __( 'Pricing plans', 'intera' ),
				'add_new'            => __( 'Add plan', 'intera' ),
				'add_new_item'       => __( 'Add plan', 'intera' ),
				'edit_item'          => __( 'Edit plan', 'intera' ),
				'new_item'           => __( 'New plan', 'intera' ),
				'search_items'       => __( 'Search plans', 'intera' ),
				'not_found'          => __( 'No plans found.', 'intera' ),
				'not_found_in_trash' => __( 'No plans found in Trash.', 'intera' ),
				'all_items'          => __( 'All plans', 'intera' ),
				'attributes'         => __( 'Order', 'intera' ),
			),
			'description'         => __( 'The pricing plans shown on the home page and the pricing page. The Order field sets the order of the cards.', 'intera' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => true,
			'menu_position'       => 23,
			'menu_icon'           => 'dashicons-money-alt',
			'supports'            => array( 'title', 'editor', 'excerpt', 'page-attributes', 'revisions', 'custom-fields' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'hierarchical'        => false,
			'capability_type'     => 'post',
		)
	);
}
add_action( 'init', 'intera_register_post_types', 20 );

/**
 * Register the `doc_category` taxonomy.
 *
 * Hierarchical: the sub-groups on `13-docs-category` (`First steps`,
 * `Data and modelling`, `Good to know`) are child terms of a top-level category.
 *
 * @return void
 */
function intera_register_taxonomies() {

	/*
	 * Same story as the post type: BetterDocs registers `doc_category`, and the
	 * twenty-six existing docs are filed under its terms. We only register the
	 * taxonomy when nothing else has.
	 */
	if ( taxonomy_exists( 'doc_category' ) ) {
		return;
	}

	register_taxonomy(
		'doc_category',
		array( 'docs' ),
		array(
			'labels'            => array(
				'name'              => __( 'Docs categories', 'intera' ),
				'singular_name'     => __( 'Docs category', 'intera' ),
				'menu_name'         => __( 'Categories', 'intera' ),
				'all_items'         => __( 'All docs categories', 'intera' ),
				'edit_item'         => __( 'Edit docs category', 'intera' ),
				'view_item'         => __( 'View docs category', 'intera' ),
				'update_item'       => __( 'Update docs category', 'intera' ),
				'add_new_item'      => __( 'Add docs category', 'intera' ),
				'new_item_name'     => __( 'New docs category name', 'intera' ),
				'parent_item'       => __( 'Parent category', 'intera' ),
				'parent_item_colon' => __( 'Parent category:', 'intera' ),
				'search_items'      => __( 'Search docs categories', 'intera' ),
				'not_found'         => __( 'No docs categories found.', 'intera' ),
				'back_to_items'     => __( '&larr; Go to docs categories', 'intera' ),
			),
			'description'       => __( 'Documentation categories. A child term becomes a sub-group inside its parent category page.', 'intera' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'show_in_nav_menus' => true,
			'rewrite'           => array(
				'slug'         => 'docs/category',
				'with_front'   => false,
				'hierarchical' => true,
			),
		)
	);
}
add_action( 'init', 'intera_register_taxonomies', 19 );

/**
 * Register the term meta the docs templates need.
 *
 * WordPress gives a term a name, a slug and a description; the design also
 * needs an icon and a colour family for the category chip on `11-docs` and
 * `13-docs-category`. Both are editable on the term add/edit screens.
 *
 * @return void
 */
function intera_register_term_meta() {

	register_term_meta(
		'doc_category',
		INTERA_TERM_ICON_KEY,
		array(
			'type'              => 'string',
			'description'       => __( 'Lucide icon name shown in the category chip.', 'intera' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => true,
			'sanitize_callback' => 'intera_sanitize_icon_name',
			'auth_callback'     => 'intera_term_meta_auth',
		)
	);

	register_term_meta(
		'doc_category',
		INTERA_TERM_TONE_KEY,
		array(
			'type'              => 'string',
			'description'       => __( 'Colour family used for the category chip.', 'intera' ),
			'single'            => true,
			'default'           => 'blue',
			'show_in_rest'      => true,
			'sanitize_callback' => 'intera_sanitize_tone',
			'auth_callback'     => 'intera_term_meta_auth',
		)
	);
}
add_action( 'init', 'intera_register_term_meta', 21 );

/**
 * Who may read and write `doc_category` term meta.
 *
 * @param bool   $allowed   Whether the user can add the meta. Unused.
 * @param string $meta_key  Meta key. Unused.
 * @param int    $term_id   Term ID.
 * @return bool
 */
function intera_term_meta_auth( $allowed, $meta_key, $term_id ) {
	unset( $allowed, $meta_key );

	if ( $term_id ) {
		return current_user_can( 'edit_term', (int) $term_id );
	}

	return current_user_can( 'manage_categories' );
}

/**
 * Icon + tone fields on the "Add docs category" form.
 *
 * @return void
 */
function intera_doc_category_add_fields() {
	wp_nonce_field( 'intera_save_term_meta', 'intera_term_meta_nonce' );
	intera_render_icon_datalist();
	?>
	<div class="form-field term-intera-icon-wrap">
		<label for="intera-term-icon"><?php esc_html_e( 'Icon', 'intera' ); ?></label>
		<input type="text" id="intera-term-icon" name="intera_term_icon" value="" list="intera-icon-names" autocomplete="off" />
		<p><?php esc_html_e( 'Lucide icon name for the category chip — for example rocket, plug, book-open or database.', 'intera' ); ?></p>
	</div>
	<div class="form-field term-intera-tone-wrap">
		<label for="intera-term-tone"><?php esc_html_e( 'Colour family', 'intera' ); ?></label>
		<select id="intera-term-tone" name="intera_term_tone">
			<?php foreach ( intera_term_tones() as $tone_slug => $tone_label ) : ?>
				<option value="<?php echo esc_attr( $tone_slug ); ?>"<?php selected( 'blue', $tone_slug ); ?>><?php echo esc_html( $tone_label ); ?></option>
			<?php endforeach; ?>
		</select>
		<p><?php esc_html_e( 'Tints the category chip and its border.', 'intera' ); ?></p>
	</div>
	<?php
}
add_action( 'doc_category_add_form_fields', 'intera_doc_category_add_fields' );

/**
 * Icon + tone fields on the "Edit docs category" form.
 *
 * @param WP_Term $term Term being edited.
 * @return void
 */
function intera_doc_category_edit_fields( $term ) {
	$icon = intera_sanitize_icon_name( get_term_meta( $term->term_id, INTERA_TERM_ICON_KEY, true ) );
	$tone = intera_sanitize_tone( get_term_meta( $term->term_id, INTERA_TERM_TONE_KEY, true ) );

	wp_nonce_field( 'intera_save_term_meta', 'intera_term_meta_nonce' );
	intera_render_icon_datalist();
	?>
	<tr class="form-field term-intera-icon-wrap">
		<th scope="row"><label for="intera-term-icon"><?php esc_html_e( 'Icon', 'intera' ); ?></label></th>
		<td>
			<input type="text" id="intera-term-icon" name="intera_term_icon" value="<?php echo esc_attr( $icon ); ?>" list="intera-icon-names" autocomplete="off" />
			<p class="description"><?php esc_html_e( 'Lucide icon name for the category chip — for example rocket, plug, book-open or database.', 'intera' ); ?></p>
		</td>
	</tr>
	<tr class="form-field term-intera-tone-wrap">
		<th scope="row"><label for="intera-term-tone"><?php esc_html_e( 'Colour family', 'intera' ); ?></label></th>
		<td>
			<select id="intera-term-tone" name="intera_term_tone">
				<?php foreach ( intera_term_tones() as $tone_slug => $tone_label ) : ?>
					<option value="<?php echo esc_attr( $tone_slug ); ?>"<?php selected( $tone, $tone_slug ); ?>><?php echo esc_html( $tone_label ); ?></option>
				<?php endforeach; ?>
			</select>
			<p class="description"><?php esc_html_e( 'Tints the category chip and its border.', 'intera' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'doc_category_edit_form_fields', 'intera_doc_category_edit_fields' );

/**
 * Persist the icon + tone term meta.
 *
 * Only acts when our own nonce is present and valid, so a term created by any
 * other route (quick-add, import, REST) is never silently blanked.
 *
 * @param int $term_id Term ID.
 * @return void
 */
function intera_save_doc_category_meta( $term_id ) {
	if ( ! isset( $_POST['intera_term_meta_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['intera_term_meta_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'intera_save_term_meta' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_term', (int) $term_id ) ) {
		return;
	}

	if ( isset( $_POST['intera_term_icon'] ) ) {
		$icon = intera_sanitize_icon_name( wp_unslash( $_POST['intera_term_icon'] ) );

		if ( '' === $icon ) {
			delete_term_meta( $term_id, INTERA_TERM_ICON_KEY );
		} else {
			update_term_meta( $term_id, INTERA_TERM_ICON_KEY, $icon );
		}
	}

	if ( isset( $_POST['intera_term_tone'] ) ) {
		update_term_meta( $term_id, INTERA_TERM_TONE_KEY, intera_sanitize_tone( wp_unslash( $_POST['intera_term_tone'] ) ) );
	}
}
add_action( 'created_doc_category', 'intera_save_doc_category_meta' );
add_action( 'edited_doc_category', 'intera_save_doc_category_meta' );

/**
 * Flush rewrite rules once, on theme activation, so /docs/ resolves immediately.
 *
 * @return void
 */
function intera_flush_rewrites_on_activation() {
	intera_register_taxonomies();
	intera_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'intera_flush_rewrites_on_activation' );
