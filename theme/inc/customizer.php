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
 * Where the line falls between this file and `inc/copy.php`: a page whose words
 * belong to that one page keeps them in its own Page copy meta box. This file
 * holds what has no page behind it — an archive, a category listing, a taxonomy
 * term — plus the standing announcements and the repeated cards that render
 * across several screens at once, where "edit it on the page" has no page to
 * mean.
 *
 * The registered default *is* the export's wording, and never a second copy of
 * it: a template reads `intera_option( 'key' )` with one argument and gets it,
 * so a field an editor never opened still renders the handoff. Clearing a field
 * is therefore also how a block is switched off — every template tests for an
 * empty string before it draws anything.
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
		'header_badge'               => __( 'Beta', 'intera' ),
		'header_cta_label'           => __( 'Get Early Access', 'intera' ),
		'header_cta_url'             => '',

		// site-footer.dc.html.
		'footer_blurb'               => __( 'One operating picture across the systems your teams already use.', 'intera' ),
		'footer_cta_label'           => __( 'Become an Early Adopter', 'intera' ),
		'footer_cta_url'             => '',

		/*
		 * The Contact Form 7 form the request page sends through. Either the
		 * hash ID wp-admin prints in the shortcode or the numeric post ID —
		 * the plugin takes both. Empty falls back to the theme's own handler
		 * in inc/forms.php; see inc/cf7.php.
		 */
		'contact_form_id'            => 'f98e0c5',

		/*
		 * site-footer, 05-contacts, 06-contact-request, 07-policy. The direct
		 * route the site publishes is a person, not an inbox: the name and the
		 * profile behind it. Clearing either one drops every row, line and
		 * button that points there, exactly as clearing an address used to.
		 */
		'contact_person'             => 'Sergey Bogdanov - Founder',
		'contact_person_url'         => 'https://www.linkedin.com/in/sergey-bogdanov-a282a689/',

		/*
		 * Not a published address — nothing renders it. It is only where the
		 * theme's own request form (inc/forms.php, the fallback behind Contact
		 * Form 7) sends its notification; empty means the site's admin email.
		 */
		'contact_notify'             => '',
		'contact_response'           => __( 'Same working day, in most cases', 'intera' ),
		'contact_languages'          => __( 'English', 'intera' ),
		'site_domain'                => 'intera-roles.com',
		'copyright'                  => __( '© 2026 INTERA. In beta — Early Adopter programme open.', 'intera' ),

		// 01-main: the pill above the hero heading.
		'hero_status'                => __( 'In beta — Early Adopter programme open', 'intera' ),

		// 04-faq: the block under the rail's hairline.
		'faq_rail_body'              => __( 'Still unclear? Send us the situation in two sentences — we answer with what INTERA would actually watch.', 'intera' ),
		'faq_rail_cta_label'         => __( 'Ask a question', 'intera' ),

		// 08-blog: the sidebar card and the note under it.
		'blog_cta_heading'           => __( 'Have a story like these?', 'intera' ),
		'blog_cta_body'              => __( 'If one of these situations sounds like your week, describe it to us. We answer with what INTERA would watch.', 'intera' ),
		'blog_cta_label'             => __( 'Bring us a real problem', 'intera' ),
		'blog_cta_url'               => '',
		'blog_docs_note'             => __( 'Release notes are also listed in the documentation.', 'intera' ),

		// 09-blog-post: the strip that closes a story.
		'article_note'               => __( 'Names and figures are anonymised at the customer’s request.', 'intera' ),
		'article_cta_label'          => __( 'Bring us a real problem', 'intera' ),

		// 10-blog-category, and every other archive that carries the card.
		'sidebar_cta_heading'        => __( 'Every story starts the same way', 'intera' ),
		'sidebar_cta_body'           => __( 'Someone checks something by hand, every week, and finds out too late when it goes wrong. Tell us yours.', 'intera' ),
		'sidebar_cta_label'          => __( 'Bring us a real problem', 'intera' ),
		'sidebar_cta_url'            => '',

		// 11-docs: the standfirst and the three panels that close the archive.
		'docs_intro'                 => __( 'Setup, integrations, the object model, and the role packages we ship. Written for the person doing the work.', 'intera' ),
		'docs_help_limits_heading'   => __( 'Alpha-stage limitations', 'intera' ),
		'docs_help_limits_body'      => __( 'What INTERA cannot do yet is written down, not hidden. Read it before planning a rollout.', 'intera' ),
		'docs_help_limits_label'     => __( 'Current system limitations', 'intera' ),
		'docs_help_limits_url'       => '',
		'docs_help_releases_heading' => __( 'Release information', 'intera' ),
		'docs_help_releases_body'    => __( 'Every version notes what changed in the object model and what it means for existing roles.', 'intera' ),
		'docs_help_releases_label'   => __( 'Release notes', 'intera' ),
		'docs_help_releases_url'     => '',
		'docs_help_ask_heading'      => __( 'Question not answered here?', 'intera' ),
		'docs_help_ask_body'         => __( 'Send the situation in two sentences. We answer with what INTERA would watch.', 'intera' ),
		'docs_help_ask_label'        => __( 'Ask the team', 'intera' ),
		'docs_help_ask_url'          => '',

		// 12-docs-article: the standing announcement above every article.
		'docs_notice_title'          => __( 'Naming changed in v0.003', 'intera' ),
		'docs_notice_body'           => __( 'KPI is now Metric across the product and the docs. Existing roles were migrated automatically; saved dashboards keep working.', 'intera' ),

		// 13-docs-category: the sidebar card.
		'docs_cta_heading'           => __( 'Setting up the first role with us', 'intera' ),
		'docs_cta_body'              => __( 'Early Adopters get custom onboarding: we connect the first source and build the first check together.', 'intera' ),
		'docs_cta_label'             => __( 'Apply as Early Adopter', 'intera' ),
		'docs_cta_url'               => '',

		// Product screenshots — media-library attachment IDs.
		'shot_hero'                  => 0,
		'shot_signals'               => 0,
		'shot_it'                    => 0,
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
		'intera_home'     => array(
			'title'       => __( 'Home page', 'intera' ),
			'description' => __( 'The one line on the home page that is not part of its Page copy, because it is a standing announcement rather than one page’s wording.', 'intera' ),
			'priority'    => 45,
		),
		'intera_faq'      => array(
			'title'       => __( 'FAQ', 'intera' ),
			'description' => __( 'The block under the FAQ rail. The questions and answers themselves are the page’s own content.', 'intera' ),
			'priority'    => 50,
		),
		'intera_blog'     => array(
			'title'       => __( 'Blog and stories', 'intera' ),
			'description' => __( 'The sidebar card and the closing strip. Two cards: one for the blog itself, one for every category and archive beside it.', 'intera' ),
			'priority'    => 55,
		),
		'intera_docs'     => array(
			'title'       => __( 'Documentation', 'intera' ),
			'description' => __( 'The standfirst, the three panels that close the archive, the standing announcement above every article and the sidebar card on a category. The articles are BetterDocs’ own.', 'intera' ),
			'priority'    => 60,
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
	 * partial. Anything that renders an attribute (an href, a profile link)
	 * stays on a full refresh, because replacing the element's text would leave
	 * a stale link behind.
	 */
	$fields = array(
		'header_badge'        => array(
			'section'     => 'intera_header',
			'label'       => __( 'Badge text', 'intera' ),
			'description' => __( 'The small pill beside the wordmark. Empty hides it.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
			'partial'     => '.intera-header-badge',
		),
		'header_cta_label'    => array(
			'section'  => 'intera_header',
			'label'    => __( 'Call-to-action label', 'intera' ),
			'sanitize' => 'sanitize_text_field',
		),
		'header_cta_url'      => array(
			'section'     => 'intera_header',
			'label'       => __( 'Call-to-action link', 'intera' ),
			'description' => __( 'Point this at the contact-request page.', 'intera' ),
			'type'        => 'url',
			'sanitize'    => 'esc_url_raw',
		),
		'footer_blurb'        => array(
			'section'     => 'intera_footer',
			'label'       => __( 'Brand line', 'intera' ),
			'description' => __( 'One sentence under the footer wordmark.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'sanitize_textarea_field',
			'partial'     => '.intera-footer-blurb',
		),
		'footer_cta_label'    => array(
			'section'  => 'intera_footer',
			'label'    => __( 'Call-to-action label', 'intera' ),
			'sanitize' => 'sanitize_text_field',
		),
		'footer_cta_url'      => array(
			'section'     => 'intera_footer',
			'label'       => __( 'Call-to-action link', 'intera' ),
			'description' => __( 'Point this at the contact-request page.', 'intera' ),
			'type'        => 'url',
			'sanitize'    => 'esc_url_raw',
		),
		'copyright'           => array(
			'section'     => 'intera_footer',
			'label'       => __( 'Legal line', 'intera' ),
			'description' => __( 'The left half of the strip below the footer columns.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'sanitize_text_field',
			'partial'     => '.intera-copyright',
		),
		'contact_form_id'     => array(
			'section'     => 'intera_contacts',
			'label'       => __( 'Contact Form 7 form ID', 'intera' ),
			'description' => __( 'The id from the form’s shortcode in wp-admin — e.g. f98e0c5. The request page renders that form, mails and captcha included. Leave empty to use the theme’s own built-in form instead.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'contact_person'      => array(
			'section'     => 'intera_contacts',
			'label'       => __( 'Direct contact', 'intera' ),
			'description' => __( 'The person a visitor writes to, as the site prints it — name and role. Empty hides the direct route everywhere it appears.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'contact_person_url'  => array(
			'section'     => 'intera_contacts',
			'label'       => __( 'Direct contact link', 'intera' ),
			'description' => __( 'Where that name points — the LinkedIn profile. Empty hides the direct route everywhere it appears.', 'intera' ),
			'type'        => 'url',
			'sanitize'    => 'esc_url_raw',
		),
		'contact_notify'      => array(
			'section'     => 'intera_contacts',
			'label'       => __( 'Request notifications', 'intera' ),
			'description' => __( 'Where the theme’s own request form mails a submission. Never printed on the site. Empty uses the site’s admin email; with Contact Form 7 in front of it, the plugin’s own recipient applies instead.', 'intera' ),
			'type'        => 'email',
			'sanitize'    => 'sanitize_email',
		),
		'contact_response'    => array(
			'section'  => 'intera_contacts',
			'label'    => __( 'Response time', 'intera' ),
			'sanitize' => 'sanitize_text_field',
			'partial'  => '.intera-contact-response',
		),
		'contact_languages'   => array(
			'section'  => 'intera_contacts',
			'label'    => __( 'Languages', 'intera' ),
			'sanitize' => 'sanitize_text_field',
			'partial'  => '.intera-contact-languages',
		),
		'site_domain'         => array(
			'section'     => 'intera_contacts',
			'label'       => __( 'Public domain', 'intera' ),
			'description' => __( 'Set in mono next to the legal line.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),

		/*
		 * Everything below renders on a screen whose words are not a page's
		 * Page copy: a standing announcement, an archive that has no page
		 * behind it, or a card that repeats across several of them. The
		 * defaults above are the export's own wording, so a field left alone
		 * still reads exactly like the handoff — and emptying one is how a
		 * block is switched off, because every template tests for '' before
		 * it draws anything.
		 */
		'hero_status'         => array(
			'section'     => 'intera_home',
			'label'       => __( 'Status pill', 'intera' ),
			'description' => __( 'The pill above the hero heading. Empty hides it.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'faq_rail_body'       => array(
			'section'     => 'intera_faq',
			'label'       => __( 'Rail note', 'intera' ),
			'description' => __( 'Sits under the “On this page” rail, above the button. Empty hides it.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'wp_kses_post',
		),
		'faq_rail_cta_label'  => array(
			'section'     => 'intera_faq',
			'label'       => __( 'Rail button label', 'intera' ),
			'description' => __( 'Points at the contact-request page. Empty hides the button.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'blog_cta_heading'    => array(
			'section'  => 'intera_blog',
			'label'    => __( 'Blog card — heading', 'intera' ),
			'sanitize' => 'sanitize_text_field',
		),
		'blog_cta_body'       => array(
			'section'     => 'intera_blog',
			'label'       => __( 'Blog card — text', 'intera' ),
			'description' => __( 'The card is hidden when both the heading and the text are empty.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'wp_kses_post',
		),
		'blog_cta_label'      => array(
			'section'  => 'intera_blog',
			'label'    => __( 'Blog card — button label', 'intera' ),
			'sanitize' => 'sanitize_text_field',
		),
		'blog_cta_url'        => array(
			'section'     => 'intera_blog',
			'label'       => __( 'Blog card — button link', 'intera' ),
			'description' => __( 'Empty points at the contact-request page.', 'intera' ),
			'type'        => 'url',
			'sanitize'    => 'esc_url_raw',
		),
		'blog_docs_note'      => array(
			'section'     => 'intera_blog',
			'label'       => __( 'Blog sidebar footnote', 'intera' ),
			'description' => __( 'The line under the card on the blog. A link is allowed here. Empty hides it.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'wp_kses_post',
		),
		'sidebar_cta_heading' => array(
			'section'     => 'intera_blog',
			'label'       => __( 'Archive card — heading', 'intera' ),
			'description' => __( 'The card beside a category or an archive listing.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'sidebar_cta_body'    => array(
			'section'     => 'intera_blog',
			'label'       => __( 'Archive card — text', 'intera' ),
			'description' => __( 'The card is hidden when both the heading and the text are empty.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'wp_kses_post',
		),
		'sidebar_cta_label'   => array(
			'section'  => 'intera_blog',
			'label'    => __( 'Archive card — button label', 'intera' ),
			'sanitize' => 'sanitize_text_field',
		),
		'sidebar_cta_url'     => array(
			'section'     => 'intera_blog',
			'label'       => __( 'Archive card — button link', 'intera' ),
			'description' => __( 'Empty points at the contact-request page.', 'intera' ),
			'type'        => 'url',
			'sanitize'    => 'esc_url_raw',
		),
		'article_note'        => array(
			'section'     => 'intera_blog',
			'label'       => __( 'Story footnote', 'intera' ),
			'description' => __( 'The small print in the strip that closes every story. Empty hides it.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'sanitize_text_field',
		),
		'article_cta_label'   => array(
			'section'     => 'intera_blog',
			'label'       => __( 'Story button label', 'intera' ),
			'description' => __( 'Points at the contact-request page. Empty hides the button.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'docs_intro'          => array(
			'section'     => 'intera_docs',
			'label'       => __( 'Archive standfirst', 'intera' ),
			'description' => __( 'The paragraph under the Documentation heading. Empty hides it.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'wp_kses_post',
		),
		'docs_notice_title'   => array(
			'section'     => 'intera_docs',
			'label'       => __( 'Article announcement — title', 'intera' ),
			'description' => __( 'The blue strip above every documentation article. Empty both fields and the strip stops rendering.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'docs_notice_body'    => array(
			'section'  => 'intera_docs',
			'label'    => __( 'Article announcement — text', 'intera' ),
			'type'     => 'textarea',
			'sanitize' => 'sanitize_text_field',
		),
		'docs_cta_heading'    => array(
			'section'     => 'intera_docs',
			'label'       => __( 'Category card — heading', 'intera' ),
			'description' => __( 'The card beside a documentation category.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'docs_cta_body'       => array(
			'section'     => 'intera_docs',
			'label'       => __( 'Category card — text', 'intera' ),
			'description' => __( 'The card is hidden when both the heading and the text are empty.', 'intera' ),
			'type'        => 'textarea',
			'sanitize'    => 'wp_kses_post',
		),
		'docs_cta_label'      => array(
			'section'  => 'intera_docs',
			'label'    => __( 'Category card — button label', 'intera' ),
			'sanitize' => 'sanitize_text_field',
		),
		'docs_cta_url'        => array(
			'section'     => 'intera_docs',
			'label'       => __( 'Category card — button link', 'intera' ),
			'description' => __( 'Empty points at the contact-request page.', 'intera' ),
			'type'        => 'url',
			'sanitize'    => 'esc_url_raw',
		),
	);

	/*
	 * The three panels that close the docs archive. Same four fields each, so
	 * they are generated rather than typed out — the defaults, and therefore
	 * the export's wording, live in `intera_option_defaults()` with everything
	 * else.
	 */
	$panels = array(
		'docs_help_limits'   => __( 'Panel 1 (limitations)', 'intera' ),
		'docs_help_releases' => __( 'Panel 2 (releases)', 'intera' ),
		'docs_help_ask'      => __( 'Panel 3 (ask us)', 'intera' ),
	);

	foreach ( $panels as $prefix => $panel_label ) {
		$fields[ $prefix . '_heading' ] = array(
			'section'     => 'intera_docs',
			/* translators: %s: panel name, e.g. "Panel 1 (limitations)". */
			'label'       => sprintf( __( '%s — heading', 'intera' ), $panel_label ),
			'description' => __( 'The panel is hidden when both the heading and the text are empty.', 'intera' ),
			'sanitize'    => 'sanitize_text_field',
		);

		$fields[ $prefix . '_body' ] = array(
			'section'  => 'intera_docs',
			/* translators: %s: panel name, e.g. "Panel 1 (limitations)". */
			'label'    => sprintf( __( '%s — text', 'intera' ), $panel_label ),
			'type'     => 'textarea',
			'sanitize' => 'wp_kses_post',
		);

		$fields[ $prefix . '_label' ] = array(
			'section'  => 'intera_docs',
			/* translators: %s: panel name, e.g. "Panel 1 (limitations)". */
			'label'    => sprintf( __( '%s — link label', 'intera' ), $panel_label ),
			'sanitize' => 'sanitize_text_field',
		);

		$fields[ $prefix . '_url' ] = array(
			'section'     => 'intera_docs',
			/* translators: %s: panel name, e.g. "Panel 1 (limitations)". */
			'label'       => sprintf( __( '%s — link target', 'intera' ), $panel_label ),
			'description' => __( 'Empty falls back to the page the panel was drawn against.', 'intera' ),
			'type'        => 'url',
			'sanitize'    => 'esc_url_raw',
		);
	}

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
