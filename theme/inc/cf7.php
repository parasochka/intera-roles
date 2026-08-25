<?php
/**
 * Contact Form 7 — the form the request page actually sends.
 *
 * The contact-request screen was built against `inc/forms.php`, the theme's own
 * handler. The live site now sends through **Contact Form 7**: the form, its
 * fields, its two mails and the SMTP transport behind them are configured in
 * wp-admin, Flamingo keeps a copy of every message and the reCAPTCHA v2
 * checkbox is a real control the visitor has to pass. None of that is the
 * theme's to reimplement, so the theme does not: `page-contact-request.php`
 * renders the plugin's form and this file dresses it in the design system.
 *
 * That is the same arrangement `inc/betterdocs.php` documents, and for the same
 * reason. Rewriting the seven fields as the design's own `Input`/`Select`
 * components would post to the theme's handler instead of the plugin's, and the
 * mail, the Flamingo record, the Akismet check and the captcha would all stop
 * happening quietly. So:
 *
 * 1. **Render the plugin's form.** `intera_cf7_form_html()` is a `do_shortcode()`
 *    of the configured form and nothing else. Every hidden field, nonce,
 *    endpoint and script stays the plugin's.
 * 2. **Dress the plugin's markup.** `wpcf7_form_class_attr` puts `itr-cf7` on the
 *    `<form>` and `wpcf7_form_elements` adds the design system's own control
 *    classes — `.itr-input`, `.itr-input--area`, `.itr-btn` — to the controls the
 *    plugin drew. Nothing is replaced; classes are added, and one wrapper each
 *    for the label text and the select chevron so the two can be styled at all.
 *    The rest is `assets/css/intera.css`, keyed on `form.itr-cf7`.
 * 3. **The theme's own form is the fallback, never the default.** With the plugin
 *    switched off — or the form ID cleared, or the form deleted — the template
 *    falls back to `inc/forms.php`, which still stores and mails. A page that
 *    silently drops a request is the one outcome neither is allowed to have.
 *
 * The reCAPTCHA is deliberately visible. `[recaptcha]` in the form template is
 * the v2 checkbox from the “ReCaptcha v2 for Contact Form 7” plugin, and the CSS
 * gives it its own full-width row directly above the submit button — `order`, so
 * it lands there whatever position the tag has in the form template.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * The configured Contact Form 7 form ID.
 *
 * Either the hash ID from the shortcode wp-admin shows (`f98e0c5`) or the
 * numeric post ID — the plugin accepts both. Empty means "use the theme's own
 * form".
 *
 * @return string
 */
function intera_cf7_form_id() {
	$id = function_exists( 'intera_option' ) ? intera_option( 'contact_form_id' ) : '';

	return trim( (string) $id );
}

/**
 * Whether the plugin is here and a form is configured.
 *
 * @return bool
 */
function intera_cf7_available() {
	return class_exists( 'WPCF7_ContactForm' )
		&& shortcode_exists( 'contact-form-7' )
		&& '' !== intera_cf7_form_id();
}

/**
 * The configured form, rendered.
 *
 * Returns '' — never a half-rendered screen — when the plugin is absent, the ID
 * is empty or the form behind it has been deleted. The caller reads that as
 * "fall back to the theme's own form".
 *
 * @return string Form markup, or '' when there is none.
 */
function intera_cf7_form_html() {
	if ( ! intera_cf7_available() ) {
		return '';
	}

	$html = do_shortcode(
		sprintf( '[contact-form-7 id="%s" html_class="intera-cf7"]', esc_attr( intera_cf7_form_id() ) )
	);

	/*
	 * A deleted or unpublished form does not fail — the shortcode answers with
	 * the plugin's own "Error: Contact form not found" paragraph. That is a
	 * broken screen, so treat it as no form at all: no <form>, no render.
	 */
	if ( false === strpos( (string) $html, '<form' ) ) {
		return '';
	}

	return (string) $html;
}

/**
 * Adds the theme's hook class to every Contact Form 7 form.
 *
 * `form.itr-cf7` is what `assets/css/intera.css` keys on. It has to be on the
 * `<form>` itself: the plugin's stylesheet loads *after* the theme's inlined
 * `<head>` CSS, so a rule that merely ties with `.wpcf7 form .wpcf7-…` loses.
 * The class buys the extra specificity the theme's rules need to land.
 *
 * @param string $class The form's class attribute.
 * @return string
 */
function intera_cf7_form_class_attr( $class ) {
	$class = trim( (string) $class );

	if ( preg_match( '/(^|\s)itr-cf7(\s|$)/', $class ) ) {
		return $class;
	}

	return '' === $class ? 'itr-cf7' : $class . ' itr-cf7';
}
add_filter( 'wpcf7_form_class_attr', 'intera_cf7_form_class_attr' );

/**
 * Design-system classes for one Contact Form 7 control.
 *
 * The plugin's own class list says what the control is; this maps that onto the
 * classes the design system already defines, so the values behind them stay in
 * `_ds/intera/tokens/*.css` and nothing is copied into a second stylesheet.
 *
 * Controls with markup of their own — the captcha, checkboxes, radios, file
 * inputs — get nothing here and are styled as themselves in CSS.
 *
 * @param string[] $classes The control's existing classes.
 * @return string[] Classes to add. Possibly empty.
 */
function intera_cf7_control_classes( $classes ) {
	if ( ! in_array( 'wpcf7-form-control', $classes, true ) ) {
		return array();
	}

	if ( in_array( 'wpcf7-submit', $classes, true ) ) {
		return array( 'itr-btn', 'itr-btn--primary', 'itr-btn--lg' );
	}

	if ( in_array( 'wpcf7-textarea', $classes, true ) ) {
		return array( 'itr-input', 'itr-input--area' );
	}

	$own_markup = array(
		'wpcf7-recaptcha',
		'wpcf7-acceptance',
		'wpcf7-checkbox',
		'wpcf7-radio',
		'wpcf7-file',
		'wpcf7-range',
	);

	foreach ( $own_markup as $control ) {
		if ( in_array( $control, $classes, true ) ) {
			return array();
		}
	}

	// Everything left is a box the visitor types or picks in: text, email, tel,
	// url, number, date, select, quiz.
	return array( 'itr-input' );
}

/**
 * Dresses the plugin's form markup in the design system.
 *
 * Three passes, each independent and each a no-op when its pattern is not
 * there. A form template an editor rearranges in wp-admin therefore degrades to
 * plainer markup rather than to broken markup.
 *
 * @param string $html The form's inner HTML, as the plugin built it.
 * @return string
 */
function intera_cf7_dress_form_elements( $html ) {
	$html = (string) $html;

	/*
	 * 1. Control classes. `wpcf7-form-control-wrap` contains the string
	 *    `wpcf7-form-control`, so the match is on the class *token*, never on the
	 *    substring — dressing the wrapper as an input would draw a second box
	 *    around every field.
	 */
	$html = preg_replace_callback(
		'/class="([^"]*wpcf7-form-control[^"]*)"/',
		static function ( $matches ) {
			$classes = preg_split( '/\s+/', trim( $matches[1] ), -1, PREG_SPLIT_NO_EMPTY );
			$add     = intera_cf7_control_classes( (array) $classes );

			if ( empty( $add ) ) {
				return $matches[0];
			}

			return 'class="' . esc_attr( implode( ' ', array_merge( $add, (array) $classes ) ) ) . '"';
		},
		$html
	);

	/*
	 * 2. The label text. The plugin writes `<label> Work email<br />` followed by
	 *    the control, which leaves the label a bare text node no selector can
	 *    reach. Wrapping it is what lets the label carry the design's own size,
	 *    weight and colour, and what gives a required field the red asterisk the
	 *    `Field` component draws. `aria-required` already says it to a screen
	 *    reader, so the mark itself is decoration.
	 *
	 *    A label with another label inside it — an `[acceptance]` written into
	 *    one — is left alone: the non-greedy match would close at the inner tag
	 *    and strand the outer one.
	 */
	$html = preg_replace_callback(
		'#<label>\s*([^<]+?)\s*<br\s*/?>\s*(.*?)</label>#s',
		static function ( $matches ) {
			if ( false !== strpos( $matches[2], '<label' ) ) {
				return $matches[0];
			}

			$mark = false !== strpos( $matches[2], 'aria-required="true"' )
				? '<span class="itr-cf7-req" aria-hidden="true">*</span>'
				: '';

			return '<label><span class="itr-cf7-label">' . $matches[1] . $mark . '</span>' . $matches[2] . '</label>';
		},
		$html
	);

	/*
	 * 3. The select chevron. The design's `Select` is a native select with its
	 *    own arrow suppressed and a Lucide chevron drawn over it; the same
	 *    wrapper and the same icon call here mean the two cannot drift.
	 */
	if ( function_exists( 'intera_icon_get' ) ) {
		$html = preg_replace_callback(
			'#<select\b[^>]*>.*?</select>#s',
			static function ( $matches ) {
				return '<span class="itr-cf7-select">' . $matches[0]
					. '<span class="itr-cf7-select__chevron" aria-hidden="true">'
					. intera_icon_get( 'chevron-down', array( 'size' => 16 ) )
					. '</span></span>';
			},
			$html
		);
	}

	return $html;
}
add_filter( 'wpcf7_form_elements', 'intera_cf7_dress_form_elements' );
