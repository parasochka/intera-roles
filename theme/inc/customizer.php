<?php
/**
 * Theme options.
 *
 * The standing rule for this build: the template owns the layout, WordPress owns
 * the words. Every recurring marketing string, contact fact, CTA target and
 * product screenshot in the mockups is registered here so an editor can change
 * it without touching a template.
 *
 * Colour, type, spacing and radii are deliberately **not** here: those come from
 * `_ds/intera/tokens/*.css`, which is the single source of truth. A Customizer
 * colour picker would be a second one.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registered defaults for every theme option, taken from the mockups.
 *
 * `intera_option()` and the Customizer read the same array, so a control's
 * default and a template's fallback can never drift apart.
 *
 * @return array<string,mixed>
 */
function intera_option_defaults() {
	/*
	 * inc/forms.php registers seven more options in this same panel. Their
	 * defaults are folded in here rather than copied, so a template can call
	 * intera_option( 'contact_success_title' ) with no second argument and
	 * still get the designed heading. Guarded: forms.php loads after this file.
	 */
	$form = function_exists( 'intera_form_option_defaults' ) ? intera_form_option_defaults() : array();

	return $form + array(
		// site-nav.dc.html.
		'header_badge'      => __( 'Beta', 'intera' ),
		'header_cta_label'  => __( 'Get Early Access', 'intera' ),
		'header_cta_url'    => '',

		// site-footer.dc.html.
		'footer_blurb'      => __( 'One operating picture across the systems your teams already use.', 'intera' ),
		'footer_cta_label'  => __( 'Become an Early Adopter', 'intera' ),
		'footer_cta_url'    => '',

		/*
		 * The Contact Form 7 form the request page sends through. Either the
		 * hash ID wp-admin prints in the shortcode or the numeric post ID —
		 * the plugin takes both. Empty falls back to the theme's own handler
		 * in inc/forms.php; see inc/cf7.php.
		 */
		'contact_form_id'   => 'f98e0c5',

		// site-footer, 05-contacts, 06-contact-request, 07-policy.
		'contact_email'     => 'sb@by-sky.net',
		'contact_response'  => __( 'Same working day, in most cases', 'intera' ),
		'contact_languages' => __( 'English', 'intera' ),
		'site_domain'       => 'intera-roles.com',
		'copyright'         => __( '© 2026 INTERA. In beta — Early Adopter programme open.', 'intera' ),

		// Product screenshots — media-library attachment IDs.
		'shot_hero'         => 0,
		'shot_signals'      => 0,
		'shot_it'           => 0,
	);
}

if ( ! function_exists( 'intera_option' ) ) :
	/**
	 * One theme option read.
	 *
	 * Falls back to the default registered with the Customizer control, so the
	 * front end and the preview always agree. An explicitly passed `$default`
	 * wins over the registered one.
	 *
	 * @param string $key     Option key, e.g. `header_cta_label`.
	 * @param mixed  $default Optional override for the registered default.
	 * @return mixed
	 */
	function intera_option( $key, $default = '' ) {
		if ( func_num_args() < 2 ) {
			$defaults = intera_option_defaults();
			$default  = array_key_exists( $key, $defaults ) ? $defaults[ $key ] : '';
		}

		return get_theme_mod( $key, $default );
	}
endif;

/**
 * Renders one text-only selective-refresh partial.
 *
 * The partial id is the option key, so a single callback serves them all.
 *
 * @param WP_Customize_Partial $partial The partial being rendered.
 * @return string Escaped text.
 */
function intera_customize_render_text_partial( $partial ) {
	return esc_html( intera_option( $partial->id ) );
}

/**
 * Registers the INTERA panel, its sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function intera_customize_register( $wp_customize ) {
	$defaults = intera_option_defaults();

	$wp_customize->add_panel(
		'intera',
		array(
			'title'       => __( 'INTERA', 'intera' ),
			'description' => __( 'Recurring site copy, contact facts and product screenshots. Colour and type come from the design system and are not editable here.', 'intera' ),
			'priority'    => 20,
		)
	);

	$sections = array(
		'intera_header'   => array(
			'title'       => __( 'Header', 'intera' ),
			'description' => __( 'The badge and call to action in the sticky top bar.', 'intera' ),
			'priority'    => 10,
		),
		'intera_footer'   => array(
			'title'       => __( 'Footer', 'intera' ),
			'description' => __( 'The brand column, its call to action and the legal strip.', 'intera' ),
			'priority'    => 20,
		),
		'intera_contacts' => array(
			'title'       => __( 'Contacts', 'intera' ),
			'description' => __( 'Shown on the contact pages, the policy page and in the footer. One source, so they stay in sync.', 'intera' ),
			'priority'    => 30,
		),
		'intera_shots'    => array(
			'title'       => __( 'Product images', 'intera' ),
			'description' => __( 'Screenshots shown inside the white product frames. Upload to the media library; the frame, caption and size stay with the template.', 'intera' ),
			'priority'    => 40,
		),
	);

	foreach ( $sections as $id => $args ) {
		$wp_customize->add_section( $id, array_merge( $args, array( 'panel' => 'intera' ) ) );
	}

	/*
	 * Text, URL and email options.
	 *
	 * `partial` marks the ones whose markup is a bare run of text: those are
	 * cheap to swap in place, so they get postMessage plus a selective-refresh
	 * partial. Anything that renders an attribute (an href, a mailto) stays on a
	 * full refresh, because replacing the element's text would leave a stale
	 * link behind.
	 */
	$fields = array(
		'header_badge'      => array(
			'section'     => 'intera_header',
			'label'       => __( 'Badge text', 'intera' ),
			'description' => __( 'The small pill beside the wordmark. Empty hides it.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
			'partial'     => '.intera-header-badge',
		),
		'header_cta_label'  => array(
			'section'  => 'intera_header',
			'label'    => __( 'Call-to-action label', 'intera' ),
			'sanitize' => 'sanitize_text_field',
		),
		'header_cta_url'    => array(
			'section'     => 'intera_header',
			'label'       => __( 'Call-to-action link', 'intera' ),
			'description' => __( 'Point this at the contact-request page.', 'intera' ),
			'type'        => 'url',
			'sanitize'    => 'esc_url_raw',
		),
		'footer_blurb'      => array(
			'section'     => 'intera_footer',
			'label'       => __( 'Brand line', 'intera' ),
			'description' => __( 'One sentence under the footer wordmark.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'sanitize_textarea_field',
			'partial'     => '.intera-footer-blurb',
		),
		'footer_cta_label'  => array(
			'section'  => 'intera_footer',
			'label'    => __( 'Call-to-action label', 'intera' ),
			'sanitize' => 'sanitize_text_field',
		),
		'footer_cta_url'    => array(
			'section'     => 'intera_footer',
			'label'       => __( 'Call-to-action link', 'intera' ),
			'description' => __( 'Point this at the contact-request page.', 'intera' ),
			'type'        => 'url',
			'sanitize'    => 'esc_url_raw',
		),
		'copyright'         => array(
			'section'     => 'intera_footer',
			'label'       => __( 'Legal line', 'intera' ),
			'description' => __( 'The left half of the strip below the footer columns.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'sanitize_text_field',
			'partial'     => '.intera-copyright',
		),
		'contact_form_id'   => array(
			'section'     => 'intera_contacts',
			'label'       => __( 'Contact Form 7 form ID', 'intera' ),
			'description' => __( 'The id from the form’s shortcode in wp-admin — e.g. f98e0c5. The request page renders that form, mails and captcha included. Leave empty to use the theme’s own built-in form instead.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'contact_email'     => array(
			'section'     => 'intera_contacts',
			'label'       => __( 'Contact email', 'intera' ),
			'description' => __( 'Used as text and as the mailto target.', 'intera' ),
			'type'        => 'email',
			'sanitize'    => 'sanitize_email',
		),
		'contact_response'  => array(
			'section'  => 'intera_contacts',
			'label'    => __( 'Response time', 'intera' ),
			'sanitize' => 'sanitize_text_field',
			'partial'  => '.intera-contact-response',
		),
		'contact_languages' => array(
			'section'  => 'intera_contacts',
			'label'    => __( 'Languages', 'intera' ),
			'sanitize' => 'sanitize_text_field',
			'partial'  => '.intera-contact-languages',
		),
		'site_domain'       => array(
			'section'     => 'intera_contacts',
			'label'       => __( 'Public domain', 'intera' ),
			'description' => __( 'Set in mono next to the legal line.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),
	);

	$priority = 10;

	foreach ( $fields as $key => $field ) {
		$has_partial = isset( $field['partial'] ) && isset( $wp_customize->selective_refresh );

		$wp_customize->add_setting(
			$key,
			array(
				'type'              => 'theme_mod',
				'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
				'capability'        => 'edit_theme_options',
				'transport'         => $has_partial ? 'postMessage' : 'refresh',
				'sanitize_callback' => $field['sanitize'],
			)
		);

		$wp_customize->add_control(
			$key,
			array(
				'section'     => $field['section'],
				'label'       => $field['label'],
				'description' => isset( $field['description'] ) ? $field['description'] : '',
				'type'        => isset( $field['type'] ) ? $field['type'] : 'text',
				'priority'    => $priority,
			)
		);

		if ( $has_partial ) {
			$wp_customize->selective_refresh->add_partial(
				$key,
				array(
					'selector'            => $field['partial'],
					'settings'            => array( $key ),
					'render_callback'     => 'intera_customize_render_text_partial',
					'container_inclusive' => false,
					'fallback_refresh'    => true,
				)
			);
		}

		$priority += 10;
	}

	// Product screenshots — attachment IDs, never a hardcoded path.
	$shots = array(
		'shot_hero'    => array(
			'label'       => __( 'Hero frame', 'intera' ),
			'description' => __( 'Fleet Health Overview · Shipmanagement — the frame on the dark hero.', 'intera' ),
		),
		'shot_signals' => array(
			'label'       => __( 'Attention queue frame', 'intera' ),
			'description' => __( 'Attention Queue · what to work on first — the “In action” section.', 'intera' ),
		),
		'shot_it'      => array(
			'label'       => __( 'Dependencies frame', 'intera' ),
			'description' => __( 'Dependencies · vendors, parts, external commitments — the “Working with IT” section.', 'intera' ),
		),
	);

	$priority = 10;

	foreach ( $shots as $key => $shot ) {
		$wp_customize->add_setting(
			$key,
			array(
				'type'              => 'theme_mod',
				'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : 0,
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				$key,
				array(
					'section'     => 'intera_shots',
					'label'       => $shot['label'],
					'description' => $shot['description'],
					'mime_type'   => 'image',
					'priority'    => $priority,
				)
			)
		);

		$priority += 10;
	}
}
add_action( 'customize_register', 'intera_customize_register' );

/**
 * One product screenshot, by its Customizer key.
 *
 * The three frames on the home page are Customizer settings — an editor picks
 * the image and the template keeps the frame, the caption and the crop. On a
 * site that has just been populated those settings are still empty, though, and
 * an empty frame is a hole in a designed band.
 *
 * So an unset setting falls back to the attachment whose slug matches the name
 * the screenshot was uploaded under. That is a convention, not a hardcoded
 * image: the moment an editor picks anything in Customizer → INTERA → Product
 * images, their choice wins and the fallback is never consulted again. When
 * neither exists the partial renders nothing, which is what it already does.
 *
 * @param string $key Option key: `shot_hero`, `shot_signals` or `shot_it`.
 * @return int Attachment ID, or 0.
 */
function intera_shot_id( $key ) {
	$chosen = (int) intera_option( $key, 0 );

	if ( $chosen > 0 ) {
		return $chosen;
	}

	$seeded = array(
		'shot_hero'    => 'intera-fleet-health-overview',
		'shot_signals' => 'intera-attention-queue',
		'shot_it'      => 'intera-dependencies',
	);

	if ( ! isset( $seeded[ $key ] ) ) {
		return 0;
	}

	$attachment = get_page_by_path( $seeded[ $key ], OBJECT, 'attachment' );

	return $attachment instanceof WP_Post ? (int) $attachment->ID : 0;
}
