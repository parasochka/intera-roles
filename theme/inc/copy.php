<?php
/**
 * Page copy — every run of text on the four designed pages, editable in wp-admin.
 *
 * The home, product, pricing and contacts screens are the parts of the site
 * whose *layout* is the deliverable: eleven bands of grid, wash and hairline
 * that no block editor is going to reproduce, so the markup stays in the
 * template exactly as the handoff drew it. Their *words* are a different thing
 * entirely — they are marketing copy, they change often, and CLAUDE.md is blunt
 * about it: "the template owns the layout, WordPress owns the words."
 *
 * So the words moved out. Each string became a key; `inc/copy-defaults.php`
 * holds the design's own wording as the registered default for that key; the
 * template asks for the key. An editor opens the page, sees the copy grouped
 * under the same section names the design uses, and edits it. Nothing about the
 * layout is reachable from there, which is the point — the design cannot be
 * broken by editing text, and text no longer needs a deployment.
 *
 * One meta key holds it all (`_intera_copy`, an object of key => string) rather
 * than 273 separate rows: it keeps the postmeta table sane, it makes the whole
 * page's copy one REST field, and it means a key retired from the design stops
 * costing anything the moment it stops being read.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/** The single meta key every page's copy lives under. */
const INTERA_COPY_KEY = '_intera_copy';

/** Defaults longer than this get a textarea rather than a one-line input. */
const INTERA_COPY_TEXTAREA_AT = 70;

if ( ! function_exists( 'intera_copy' ) ) :
	/**
	 * One run of page copy.
	 *
	 * Reads the current page's saved value and falls back to the design's own
	 * wording. An empty saved value is *not* a value: blanking a field in the
	 * editor restores the handoff's text rather than leaving a hole in the
	 * layout, which is the behaviour a designed page needs.
	 *
	 * @param string   $key     Copy key, e.g. `home_hero__your_business_clearly`.
	 * @param int|null $post_id Page to read from. Defaults to the current post.
	 * @return string
	 */
	function intera_copy( $key, $post_id = null ) {
		$key      = (string) $key;
		$defaults = intera_copy_defaults();
		$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		$post_id = null === $post_id ? get_the_ID() : (int) $post_id;

		if ( ! $post_id ) {
			return $default;
		}

		$saved = get_post_meta( $post_id, INTERA_COPY_KEY, true );

		if ( ! is_array( $saved ) || ! isset( $saved[ $key ] ) ) {
			return $default;
		}

		$value = trim( (string) $saved[ $key ] );

		return '' === $value ? $default : $value;
	}
endif;

/**
 * One run of page copy that carries placeholders, formatted safely.
 *
 * A few strings are sprintf formats: "Prefer to read first? %1$s and %2$s
 * answer most first questions." Making them editable is right — they are
 * sentences — but it hands an editor a loaded gun, because PHP 8 throws on a
 * format with fewer placeholders than arguments. A page that fatals because
 * someone tidied a sentence is not an acceptable way to edit copy.
 *
 * So the edited string is tried first and the design's own wording is the net:
 * remove a placeholder and the sentence reverts to the handoff's version rather
 * than taking the page down. The format is escaped, the arguments are not —
 * they are the caller's already-escaped links.
 *
 * @param string $key     Copy key.
 * @param mixed  ...$args Values for the placeholders.
 * @return string Ready to echo.
 */
function intera_copy_format( $key, ...$args ) {
	$formatted = intera_copy_vsprintf( esc_html( intera_copy( $key ) ), $args );

	if ( null === $formatted ) {
		$defaults  = intera_copy_defaults();
		$fallback  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
		$formatted = intera_copy_vsprintf( esc_html( $fallback ), $args );
	}

	return null === $formatted ? '' : $formatted;
}

/**
 * `vsprintf()` that reports a bad format instead of raising it.
 *
 * @param string $format Format string.
 * @param array  $args   Arguments.
 * @return string|null Formatted string, or null when the format cannot take them.
 */
function intera_copy_vsprintf( $format, array $args ) {
	if ( ! $args ) {
		return $format;
	}

	try {
		return vsprintf( $format, $args );
	} catch ( \Throwable $intera_copy_error ) {
		unset( $intera_copy_error );

		return null;
	}
}

if ( ! function_exists( 'intera_copy_field' ) ) :
	/**
	 * One schema entry, normalised.
	 *
	 * A field is normally just its default string — the design's own wording is
	 * also the name an editor recognises it by, which is what
	 * `intera_copy_field_label()` relies on. That breaks down for the handful of
	 * fields whose default is a bare figure: a control labelled "+2" tells an
	 * editor nothing about which tile it sits in. Those entries are written as
	 * `array( 'default' => '+2', 'label' => … )` instead, and this is where the
	 * two shapes become one.
	 *
	 * @param string|array{default:string,label:string} $field Schema entry.
	 * @return array{default:string,label:string} Label is '' when the default is its own name.
	 */
	function intera_copy_field( $field ) {
		if ( is_array( $field ) ) {
			return array(
				'default' => isset( $field['default'] ) ? (string) $field['default'] : '',
				'label'   => isset( $field['label'] ) ? (string) $field['label'] : '',
			);
		}

		return array(
			'default' => (string) $field,
			'label'   => '',
		);
	}
endif;

/**
 * Every registered default, flattened to key => string.
 *
 * The schema is grouped for the editor's benefit; a lookup wants it flat, and
 * `intera_copy()` runs a few hundred times per request, so it is flattened once
 * per request and kept.
 *
 * @return array<string,string>
 */
function intera_copy_defaults() {
	static $flat = null;

	if ( null !== $flat ) {
		return $flat;
	}

	$flat = array();

	if ( ! function_exists( 'intera_copy_schema' ) ) {
		return $flat;
	}

	foreach ( intera_copy_schema() as $group ) {
		foreach ( $group['sections'] as $section ) {
			foreach ( $section['fields'] as $key => $field ) {
				$resolved     = intera_copy_field( $field );
				$flat[ $key ] = $resolved['default'];
			}
		}
	}

	return $flat;
}

/**
 * Which copy group, if any, a given page owns.
 *
 * The home group belongs to whichever page is set as the front page; the other
 * three belong to whichever page carries the matching page template. Both are
 * settings an editor controls, so the meta box follows the page rather than a
 * hardcoded ID.
 *
 * @param int|WP_Post|null $post Page to test.
 * @return string Group key, or '' when the page carries no designed copy.
 */
function intera_copy_group_for( $post = null ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return '';
	}

	if ( ! function_exists( 'intera_copy_schema' ) ) {
		return '';
	}

	$template = (string) get_page_template_slug( $post );

	foreach ( intera_copy_schema() as $key => $group ) {
		if ( 'front-page.php' === $group['template'] ) {
			if ( (int) get_option( 'page_on_front' ) === $post->ID ) {
				return $key;
			}

			continue;
		}

		if ( $template === $group['template'] ) {
			return $key;
		}
	}

	return '';
}

/**
 * Register the copy field with the REST API.
 *
 * `additionalProperties` rather than a property list: the schema is generated
 * from the templates, so pinning 273 property names into the REST schema would
 * be a second copy of it that drifts the first time a section is reworded.
 *
 * @return void
 */
function intera_register_copy_meta() {
	register_post_meta(
		'page',
		INTERA_COPY_KEY,
		array(
			'type'              => 'object',
			'description'       => __( 'Editable page copy, keyed by the design section it belongs to.', 'intera' ),
			'single'            => true,
			'default'           => array(),
			'show_in_rest'      => array(
				'schema' => array(
					'type'                 => 'object',
					'additionalProperties' => array( 'type' => 'string' ),
				),
			),
			'sanitize_callback' => 'intera_sanitize_copy',
			'auth_callback'     => 'intera_post_meta_auth',
		)
	);
}
add_action( 'init', 'intera_register_copy_meta', 12 );

/**
 * Keep only known keys, and keep them as plain text.
 *
 * Unknown keys are dropped rather than stored: the field is writable over REST,
 * and a bag that accepts anything is a bag that fills up with anything. The
 * values are rendered with `esc_html()`, so markup in them would only ever show
 * up as literal tags — stripping it here says so honestly.
 *
 * @param mixed $value Raw value.
 * @return array<string,string>
 */
function intera_sanitize_copy( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}

	$defaults = intera_copy_defaults();
	$clean    = array();

	foreach ( $value as $key => $text ) {
		$key = (string) $key;

		if ( ! isset( $defaults[ $key ] ) || is_array( $text ) ) {
			continue;
		}

		$text = sanitize_textarea_field( (string) $text );

		/*
		 * A field left at the design's wording stores nothing. That keeps the
		 * page following `inc/copy-defaults.php`: reword a section in the
		 * handoff and every page that never overrode it picks the change up,
		 * instead of being pinned to a copy of the old text nobody edited.
		 */
		if ( '' !== trim( $text ) && $text !== $defaults[ $key ] ) {
			$clean[ $key ] = $text;
		}
	}

	return $clean;
}

/**
 * Add the copy meta box to a page that carries a designed layout.
 *
 * @param string  $post_type Post type of the screen.
 * @param WP_Post $post      Post being edited.
 * @return void
 */
function intera_add_copy_meta_box( $post_type, $post ) {
	if ( 'page' !== $post_type || '' === intera_copy_group_for( $post ) ) {
		return;
	}

	add_meta_box(
		'intera_copy',
		__( 'Page copy', 'intera' ),
		'intera_render_copy_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'intera_add_copy_meta_box', 10, 2 );

/**
 * The copy meta box.
 *
 * One fieldset per design section, in the order the page renders them, so the
 * screen can be read top to bottom against the live page. Every control shows
 * the design's own wording as its placeholder, which is also what an emptied
 * field falls back to — so "clear it" and "restore the original" are the same
 * gesture, and nothing an editor does here can leave a blank band on the page.
 *
 * A field that has never been overridden shows the design's wording as its
 * value, not as a placeholder, so an editor can change one word instead of
 * retyping a sentence. Saving it unchanged still stores nothing.
 *
 * @param WP_Post $post Page being edited.
 * @return void
 */
function intera_render_copy_meta_box( $post ) {
	$group_key = intera_copy_group_for( $post );

	if ( '' === $group_key ) {
		return;
	}

	$schema = intera_copy_schema();
	$group  = $schema[ $group_key ];
	$saved  = get_post_meta( $post->ID, INTERA_COPY_KEY, true );
	$saved  = is_array( $saved ) ? $saved : array();

	wp_nonce_field( 'intera_save_copy_' . $post->ID, 'intera_copy_nonce' );
	?>
	<style>
		.intera-copy-section { margin: 20px 0 0; padding: 0; border: 0; border-top: 1px solid var(--wp-admin-theme-color-darker-10, #c3c4c7); }
		.intera-copy-section legend { padding: 12px 0 4px; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
		.intera-copy-field { margin: 0 0 14px; }
		.intera-copy-field label { display: block; margin-bottom: 4px; }
	</style>
	<p class="description">
		<?php
		echo esc_html__( 'Every run of text on this page, grouped by the section it appears in. Layout, spacing and colour stay with the template. Clear a field to go back to the original wording.', 'intera' );
		?>
	</p>

	<?php foreach ( $group['sections'] as $section_key => $section ) : ?>
		<fieldset class="intera-copy-section">
			<legend><?php echo esc_html( $section['label'] ); ?></legend>

			<?php
			foreach ( $section['fields'] as $key => $field ) :
				$resolved = intera_copy_field( $field );
				$default  = $resolved['default'];
				$label    = '' !== $resolved['label'] ? $resolved['label'] : intera_copy_field_label( $default );
				$id       = 'intera-copy-' . sanitize_html_class( $key );
				/*
				 * The control shows the design's own wording when nothing has
				 * been saved, rather than an empty box with a grey placeholder.
				 * Changing one word in a sentence should not mean retyping the
				 * sentence, and there are three hundred of these. Nothing is
				 * stored for a field left at the default (see
				 * `intera_sanitize_copy()`), so the page keeps following the
				 * handoff until someone actually decides otherwise.
				 */
				$value    = isset( $saved[ $key ] ) ? (string) $saved[ $key ] : $default;
				$name     = 'intera_copy[' . $key . ']';
				$textarea = mb_strlen( $default ) > INTERA_COPY_TEXTAREA_AT;
				?>
				<p class="intera-copy-field">
					<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
					<?php if ( $textarea ) : ?>
						<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="2" class="large-text" placeholder="<?php echo esc_attr( $default ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
					<?php else : ?>
						<input type="text" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="large-text" placeholder="<?php echo esc_attr( $default ); ?>" />
					<?php endif; ?>
					<?php if ( preg_match( '/%(?:\d+\$)?s/', $default ) ) : ?>
						<span class="description">
							<?php echo esc_html__( 'Keep the %s markers — each one is replaced by a link when the page renders. Remove one and this sentence falls back to its original wording.', 'intera' ); ?>
						</span>
					<?php endif; ?>
				</p>
				<?php
			endforeach;
			?>
		</fieldset>
		<?php unset( $section_key ); ?>
	<?php endforeach; ?>
	<?php
}

/**
 * A short, recognisable label for one copy field.
 *
 * There is no separate human name for these — the design's own wording *is* the
 * name, and an editor recognises "See what needs attention…" faster than any
 * label we could invent for it. Long defaults are trimmed on a word boundary so
 * the label stays one line.
 *
 * @param string $default The registered default.
 * @return string
 */
function intera_copy_field_label( $default ) {
	$default = trim( wp_strip_all_tags( (string) $default ) );

	if ( mb_strlen( $default ) <= 60 ) {
		return $default;
	}

	return rtrim( mb_substr( $default, 0, 57 ), " \t\n\r\0\x0B.,;:—-" ) . '…';
}

/**
 * Persist the copy meta box.
 *
 * Absent nonce means the save came from somewhere else — a quick edit, an
 * import, the REST API — and those must not blank a page's copy just because
 * they did not post the field.
 *
 * @param int     $post_id Page ID.
 * @param WP_Post $post    Page being saved.
 * @return void
 */
function intera_save_copy_meta( $post_id, $post ) {
	if ( ! isset( $_POST['intera_copy_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['intera_copy_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'intera_save_copy_' . $post_id ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) || 'page' !== $post->post_type ) {
		return;
	}

	$submitted = isset( $_POST['intera_copy'] ) ? wp_unslash( $_POST['intera_copy'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised field by field below.
	$clean     = intera_sanitize_copy( $submitted );

	if ( empty( $clean ) ) {
		delete_post_meta( $post_id, INTERA_COPY_KEY );

		return;
	}

	update_post_meta( $post_id, INTERA_COPY_KEY, $clean );
}
add_action( 'save_post', 'intera_save_copy_meta', 10, 2 );
