<?php
/**
 * The contact-request form: post type, handler, validation and success state.
 *
 * `06-contact-request.dc.html` is the only screen in the set with a real state
 * machine — a form branch and a success branch driven by one flag. The mockup
 * never wires it to a backend, so the whole round trip lives here, in the theme,
 * with no plugin:
 *
 *   1. the page template renders the form and calls `intera_form_hidden_fields()`
 *      for the nonce, the honeypot and the time trap;
 *   2. the browser posts to `admin-post.php`;
 *   3. `intera_handle_contact_request()` sanitises, validates, stores the
 *      submission as a private `intera_request` entry, mails it, and redirects
 *      (POST/redirect/GET, so a refresh can never resubmit);
 *   4. the template reads the outcome back through `intera_form_errors_get()`,
 *      `intera_form_old_get()` and `intera_form_succeeded()`.
 *
 * The entry is written *before* the mail is sent. If SMTP is broken on the host
 * the request is still on the site, which is the whole reason for the post type.
 *
 * Copy an editor would plausibly change — the recipient, the subject line, the
 * success card, the two option lists, the consent sentence — is registered with
 * the Customizer below and read through `intera_option()`. Labels, hints and
 * placeholders belong to the screen and stay with the template.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * The post type one submission is stored as.
 *
 * Private, not public, not queryable, never in search: it is an inbox, not
 * content. It belongs to the form rather than to the public content model in
 * inc/post-types.php, so it is registered here.
 *
 * @var string
 */
define( 'INTERA_REQUEST_POST_TYPE', 'intera_request' );

/**
 * The `admin-post.php` action name, for both the logged-in and the nopriv hook.
 *
 * @var string
 */
define( 'INTERA_FORM_ACTION', 'intera_contact_request' );

/**
 * The nonce field name. The nonce action is `INTERA_FORM_ACTION`.
 *
 * @var string
 */
define( 'INTERA_FORM_NONCE', 'intera_contact_request_nonce' );

/**
 * Seconds a human needs, at the very least, to fill seven fields.
 *
 * Anything faster is a script. Deliberately generous downwards: a real person
 * pasting a prepared answer still takes longer than this.
 *
 * @var int
 */
define( 'INTERA_FORM_MIN_SECONDS', 3 );

/**
 * How long a rejected submission is held for redisplay, in seconds.
 *
 * Long enough to survive the redirect, short enough that a stale tab reopened
 * tomorrow gets a clean form.
 *
 * @var int
 */
define( 'INTERA_FORM_TRANSIENT_TTL', 300 );


/* -------------------------------------------------------------------------
 * The field list
 * ---------------------------------------------------------------------- */

/**
 * Every field the form posts, in the order `06-contact-request` renders them.
 *
 * `label` is here for the mail body and the admin screen only — the template
 * takes its own labels, hints and placeholders from the screen. `required`
 * mirrors the mockup: fields 1, 2 and 7 carry the required marker.
 *
 * @return array<string,array<string,mixed>> Field key => definition.
 */
function intera_request_fields() {
	$fields = array(
		'name'     => array(
			'label'    => __( 'Name', 'intera' ),
			'type'     => 'text',
			'required' => true,
			'max'      => 120,
		),
		'email'    => array(
			'label'    => __( 'Work email', 'intera' ),
			'type'     => 'email',
			'required' => true,
			'max'      => 200,
		),
		'company'  => array(
			'label'    => __( 'Company', 'intera' ),
			'type'     => 'text',
			'required' => false,
			'max'      => 160,
		),
		'role'     => array(
			'label'    => __( 'Your role', 'intera' ),
			'type'     => 'text',
			'required' => false,
			'max'      => 160,
		),
		'industry' => array(
			'label'    => __( 'Industry', 'intera' ),
			'type'     => 'select',
			'required' => false,
			'choices'  => 'intera_form_industries_get',
		),
		'interest' => array(
			'label'    => __( 'What brings you here', 'intera' ),
			'type'     => 'select',
			'required' => false,
			'choices'  => 'intera_form_interests_get',
		),
		'problem'  => array(
			'label'    => __( 'The problem, in your words', 'intera' ),
			'type'     => 'textarea',
			'required' => true,
			'max'      => 8000,
		),
	);

	/**
	 * Filters the contact-request field list.
	 *
	 * @param array<string,array<string,mixed>> $fields Field key => definition.
	 */
	return (array) apply_filters( 'intera_request_fields', $fields );
}

/**
 * Splits a one-per-line Customizer option into a clean list.
 *
 * @param string $raw Raw textarea value.
 * @return string[] Trimmed, non-empty, de-duplicated lines.
 */
function intera_form_lines_to_list( $raw ) {
	$lines = preg_split( '/\R/', (string) $raw );

	if ( ! is_array( $lines ) ) {
		return array();
	}

	$lines = array_map( 'trim', $lines );
	$lines = array_filter( $lines, 'strlen' );

	return array_values( array_unique( $lines ) );
}

/**
 * The `Industry` option list.
 *
 * Doubles as the validation whitelist: a value the list does not contain is
 * rejected rather than stored, so a crafted POST cannot smuggle text through a
 * `<select>`.
 *
 * @return string[]
 */
function intera_form_industries_get() {
	return intera_form_lines_to_list( intera_option( 'contact_industries', intera_form_industries_default() ) );
}

/**
 * The `What brings you here` option list. Also the validation whitelist.
 *
 * @return string[]
 */
function intera_form_interests_get() {
	return intera_form_lines_to_list( intera_option( 'contact_interests', intera_form_interests_default() ) );
}

/**
 * The seven industries from the mockup's `renderVals()`, verbatim.
 *
 * @return string One per line.
 */
function intera_form_industries_default() {
	return implode(
		"\n",
		array(
			__( 'Telecommunications', 'intera' ),
			__( 'Shipmanagement / maritime', 'intera' ),
			__( 'Logistics and transport', 'intera' ),
			__( 'Manufacturing', 'intera' ),
			__( 'Food production', 'intera' ),
			__( 'Financial services', 'intera' ),
			__( 'Other', 'intera' ),
		)
	);
}

/**
 * The five interests from the mockup's `renderVals()`, verbatim.
 *
 * @return string One per line.
 */
function intera_form_interests_default() {
	return implode(
		"\n",
		array(
			__( 'Early Adopter programme', 'intera' ),
			__( 'Evaluation on the free plan', 'intera' ),
			__( 'Commercial deployment', 'intera' ),
			__( 'INTERA Method engagement', 'intera' ),
			__( 'Partner or reseller', 'intera' ),
		)
	);
}


/* -------------------------------------------------------------------------
 * What the template reads back
 * ---------------------------------------------------------------------- */

/**
 * The rejected submission held for this request, or an empty payload.
 *
 * Read once and cached: the transient is deleted on first read so a refresh
 * gets a clean form instead of yesterday's complaints, but every helper in this
 * file can still call this as often as it likes.
 *
 * @return array{errors:array<string,string>,old:array<string,string>}
 */
function intera_form_payload_get() {
	static $payload = null;

	if ( null !== $payload ) {
		return $payload;
	}

	$payload = array(
		'errors' => array(),
		'old'    => array(),
	);

	$token = isset( $_GET['intera_form'] ) ? sanitize_key( wp_unslash( $_GET['intera_form'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only redisplay of the user's own rejected input, keyed by a single-use token.

	if ( '' === $token ) {
		return $payload;
	}

	$stored = get_transient( 'intera_form_' . $token );
	delete_transient( 'intera_form_' . $token );

	if ( ! is_array( $stored ) ) {
		return $payload;
	}

	if ( isset( $stored['errors'] ) && is_array( $stored['errors'] ) ) {
		$payload['errors'] = array_map( 'strval', $stored['errors'] );
	}

	if ( isset( $stored['old'] ) && is_array( $stored['old'] ) ) {
		$payload['old'] = array_map( 'strval', $stored['old'] );
	}

	return $payload;
}

/**
 * Per-field validation messages for the submission that was just rejected.
 *
 * Feed each one to the DS `Field` component's `error` prop — it takes
 * precedence over `hint`, and the two never render together.
 *
 * Values are raw text: escape on output, like everything else.
 *
 * @return array<string,string> Field key => message. Empty when nothing failed.
 */
function intera_form_errors_get() {
	$payload = intera_form_payload_get();

	return $payload['errors'];
}

/**
 * The value the visitor typed, so a rejected form comes back filled in.
 *
 * Raw text: escape on output (`esc_attr()` for an input, `esc_textarea()` for
 * the textarea, `selected()` for the two selects).
 *
 * @param string $field   Field key, e.g. `email`.
 * @param string $default Returned when the field was not submitted.
 * @return string
 */
function intera_form_old_get( $field, $default = '' ) {
	$payload = intera_form_payload_get();
	$field   = (string) $field;

	return isset( $payload['old'][ $field ] ) ? $payload['old'][ $field ] : (string) $default;
}

/**
 * Whether the success branch should render instead of the form.
 *
 * True only after a successful POST/redirect/GET round trip. Because the flag
 * lives in the URL and not in a one-shot transient, a refresh of the success
 * page re-renders the success card rather than resubmitting or falling back to
 * an empty form.
 *
 * @return bool
 */
function intera_form_succeeded() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display flag only; it grants nothing and changes no state.
	return isset( $_GET['intera_request'] ) && 'sent' === sanitize_key( wp_unslash( $_GET['intera_request'] ) );
}

/**
 * The reference number to print in the success card, e.g. `REQ-2026-0148`.
 *
 * Generated by the handler from the stored entry's ID — the mockup's
 * `REQ-2026-0148` is a placeholder for exactly this. Empty when the success
 * branch is not showing.
 *
 * @return string
 */
function intera_form_reference_get() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display value only, validated against a fixed shape below.
	$raw = isset( $_GET['intera_ref'] ) ? wp_unslash( $_GET['intera_ref'] ) : '';

	if ( ! is_string( $raw ) || ! preg_match( '/^REQ-\d{4}-\d{4,}$/', $raw ) ) {
		return '';
	}

	return $raw;
}

/**
 * The URL the form posts to.
 *
 * @return string
 */
function intera_form_action_url_get() {
	return admin_url( 'admin-post.php' );
}


/* -------------------------------------------------------------------------
 * The hidden plumbing the template has to emit
 * ---------------------------------------------------------------------- */

/**
 * Echoes the routing, nonce, honeypot and time-trap fields.
 *
 * Call this once inside the `<form>`. Everything a submission needs that is not
 * a visible control lives here, so the template never has to know how the trap
 * is built.
 *
 * The honeypot is positioned off-canvas with an inline style rather than a
 * class: a spam bot reads the stylesheet too, and a field that is invisible
 * only because `intera.css` loaded is a field that is visible when it does not.
 *
 * @return void
 */
function intera_form_hidden_fields() {
	$stamp = time();

	printf( '<input type="hidden" name="action" value="%s" />', esc_attr( INTERA_FORM_ACTION ) );
	wp_nonce_field( INTERA_FORM_ACTION, INTERA_FORM_NONCE );

	printf( '<input type="hidden" name="intera_ts" value="%d" />', (int) $stamp );
	printf(
		'<input type="hidden" name="intera_tsk" value="%s" />',
		esc_attr( intera_form_stamp_key( $stamp ) )
	);

	printf(
		'<div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden" aria-hidden="true">'
			. '<label for="intera-website">%1$s</label>'
			. '<input type="text" id="intera-website" name="intera_website" value="" tabindex="-1" autocomplete="off" />'
			. '</div>',
		esc_html__( 'Leave this field empty.', 'intera' )
	);
}

/**
 * Signs a timestamp so the time trap cannot simply be back-dated.
 *
 * @param int $stamp Unix timestamp rendered into the form.
 * @return string
 */
function intera_form_stamp_key( $stamp ) {
	return wp_hash( INTERA_FORM_ACTION . '|' . (int) $stamp );
}


/* -------------------------------------------------------------------------
 * The handler
 * ---------------------------------------------------------------------- */

/**
 * Receives the POST, validates it, stores it, mails it, redirects.
 *
 * Runs for logged-in visitors and logged-out visitors alike.
 *
 * @return void
 */
function intera_handle_contact_request() {
	check_admin_referer( INTERA_FORM_ACTION, INTERA_FORM_NONCE );

	$back = wp_get_referer();
	$back = $back ? $back : home_url( '/' );
	$back = remove_query_arg( array( 'intera_form', 'intera_request', 'intera_ref' ), $back );

	/*
	 * Spam first, and silently. A bot that is told why it failed learns how to
	 * pass; one that is shown the success card learns nothing. Nothing is
	 * stored and nothing is mailed on this path.
	 */
	if ( intera_form_looks_automated() ) {
		wp_safe_redirect( add_query_arg( 'intera_request', 'sent', $back ) );
		exit;
	}

	$values = intera_form_collect();
	$errors = intera_form_validate( $values );

	if ( ! empty( $errors ) ) {
		$token = wp_generate_password( 20, false, false );

		set_transient(
			'intera_form_' . $token,
			array(
				'errors' => $errors,
				'old'    => $values,
			),
			INTERA_FORM_TRANSIENT_TTL
		);

		$url = add_query_arg(
			array(
				'intera_request' => 'error',
				'intera_form'    => $token,
			),
			$back
		);

		wp_safe_redirect( $url . '#intera-request-form' );
		exit;
	}

	$post_id = intera_form_store( $values );

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		$token = wp_generate_password( 20, false, false );

		set_transient(
			'intera_form_' . $token,
			array(
				'errors' => array( 'form' => __( 'Something went wrong on our side and the request was not saved. Please try again, or write to us directly.', 'intera' ) ),
				'old'    => $values,
			),
			INTERA_FORM_TRANSIENT_TTL
		);

		wp_safe_redirect(
			add_query_arg(
				array(
					'intera_request' => 'error',
					'intera_form'    => $token,
				),
				$back
			) . '#intera-request-form'
		);
		exit;
	}

	$reference = intera_form_reference_for( $post_id );

	update_post_meta( $post_id, '_intera_request_reference', $reference );
	wp_update_post(
		array(
			'ID'         => $post_id,
			'post_title' => sprintf(
				/* translators: 1: request reference, e.g. REQ-2026-0148. 2: sender name. */
				__( '%1$s — %2$s', 'intera' ),
				$reference,
				'' !== $values['name'] ? $values['name'] : __( 'Anonymous', 'intera' )
			),
		)
	);

	$mailed = intera_form_notify( $values, $reference );
	update_post_meta( $post_id, '_intera_request_mailed', $mailed ? 1 : 0 );

	/**
	 * Fires once a contact request has been stored and the mail attempted.
	 *
	 * @param int    $post_id   The stored `intera_request` entry.
	 * @param array  $values    Sanitised field values.
	 * @param string $reference The generated reference number.
	 * @param bool   $mailed    Whether `wp_mail()` accepted the message.
	 */
	do_action( 'intera_request_received', $post_id, $values, $reference, $mailed );

	wp_safe_redirect(
		add_query_arg(
			array(
				'intera_request' => 'sent',
				'intera_ref'     => rawurlencode( $reference ),
			),
			$back
		) . '#intera-request-form'
	);
	exit;
}
add_action( 'admin_post_' . INTERA_FORM_ACTION, 'intera_handle_contact_request' );
add_action( 'admin_post_nopriv_' . INTERA_FORM_ACTION, 'intera_handle_contact_request' );

/**
 * The honeypot and the time trap.
 *
 * @return bool True when the submission is almost certainly not a person.
 */
function intera_form_looks_automated() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- check_admin_referer() ran in the caller.
	$pot = isset( $_POST['intera_website'] ) ? trim( (string) wp_unslash( $_POST['intera_website'] ) ) : '';

	if ( '' !== $pot ) {
		return true;
	}

	$stamp = isset( $_POST['intera_ts'] ) ? absint( wp_unslash( $_POST['intera_ts'] ) ) : 0;
	$key   = isset( $_POST['intera_tsk'] ) ? (string) wp_unslash( $_POST['intera_tsk'] ) : '';
	// phpcs:enable WordPress.Security.NonceVerification.Missing

	// A missing or forged stamp means the form was not the one we rendered.
	if ( ! $stamp || ! hash_equals( intera_form_stamp_key( $stamp ), $key ) ) {
		return true;
	}

	return ( time() - $stamp ) < INTERA_FORM_MIN_SECONDS;
}

/**
 * Sanitises every posted field on the way in.
 *
 * The textarea genuinely holds prose, not markup — the mockup's placeholder is
 * two plain sentences — so it gets `sanitize_textarea_field()` and not
 * `wp_kses_post()`. Nothing on this form has any reason to carry HTML.
 *
 * @return array<string,string> Field key => clean value, plus `consent`.
 */
function intera_form_collect() {
	$values = array();

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- check_admin_referer() ran in the caller.
	foreach ( intera_request_fields() as $key => $field ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		$raw = is_scalar( $raw ) ? (string) $raw : '';

		switch ( $field['type'] ) {
			case 'email':
				$value = sanitize_email( $raw );
				break;

			case 'textarea':
				$value = sanitize_textarea_field( $raw );
				break;

			default:
				$value = sanitize_text_field( $raw );
				break;
		}

		if ( isset( $field['max'] ) ) {
			$value = mb_substr( $value, 0, (int) $field['max'] );
		}

		$values[ $key ] = $value;
	}

	$values['consent'] = empty( $_POST['consent'] ) ? '' : '1';
	// phpcs:enable WordPress.Security.NonceVerification.Missing

	return $values;
}

/**
 * Server-side validation, one message per field.
 *
 * The mockup has no error design, so each message is handed to the DS `Field`
 * component's `error` branch — small `--text-xs` copy in `--status-critical`,
 * replacing the hint.
 *
 * @param array<string,string> $values Sanitised values from `intera_form_collect()`.
 * @return array<string,string> Field key => message. Empty when the form passes.
 */
function intera_form_validate( $values ) {
	$errors = array();

	foreach ( intera_request_fields() as $key => $field ) {
		$value = isset( $values[ $key ] ) ? $values[ $key ] : '';

		if ( ! empty( $field['required'] ) && '' === $value ) {
			switch ( $key ) {
				case 'name':
					$errors[ $key ] = __( 'Please add your name, so we know who we are answering.', 'intera' );
					break;

				case 'email':
					$errors[ $key ] = __( 'Please add a work email — it is the only way we can reply.', 'intera' );
					break;

				case 'problem':
					$errors[ $key ] = __( 'Please describe the problem in your own words. This is the field we read first.', 'intera' );
					break;

				default:
					$errors[ $key ] = __( 'This field is required.', 'intera' );
					break;
			}

			continue;
		}

		if ( '' === $value ) {
			continue;
		}

		if ( 'email' === $field['type'] && ! is_email( $value ) ) {
			$errors[ $key ] = __( 'That does not look like a working email address.', 'intera' );
			continue;
		}

		if ( 'select' === $field['type'] && isset( $field['choices'] ) && is_callable( $field['choices'] ) ) {
			$choices = (array) call_user_func( $field['choices'] );

			if ( ! in_array( $value, $choices, true ) ) {
				$errors[ $key ] = __( 'Please choose one of the listed options.', 'intera' );
			}
		}
	}

	if ( empty( $values['consent'] ) ) {
		$errors['consent'] = __( 'Please confirm that we may contact you about this request.', 'intera' );
	}

	/**
	 * Filters the validation result before the submission is accepted.
	 *
	 * @param array<string,string> $errors Field key => message.
	 * @param array<string,string> $values Sanitised values.
	 */
	return (array) apply_filters( 'intera_request_errors', $errors, $values );
}

/**
 * Stores one submission as a private `intera_request` entry.
 *
 * Written before the mail goes out: if the host's mail is broken the request is
 * still here.
 *
 * @param array<string,string> $values Sanitised values.
 * @return int|WP_Error New post ID, or the insert error.
 */
function intera_form_store( $values ) {
	$post_id = wp_insert_post(
		array(
			'post_type'    => INTERA_REQUEST_POST_TYPE,
			'post_status'  => 'private',
			'post_title'   => '' !== $values['name'] ? $values['name'] : __( 'Contact request', 'intera' ),
			'post_content' => isset( $values['problem'] ) ? $values['problem'] : '',
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	foreach ( intera_request_fields() as $key => $field ) {
		update_post_meta( $post_id, '_intera_request_' . $key, isset( $values[ $key ] ) ? $values[ $key ] : '' );
	}

	update_post_meta( $post_id, '_intera_request_consent', empty( $values['consent'] ) ? 0 : 1 );

	return (int) $post_id;
}

/**
 * Builds the reference number the success card prints.
 *
 * Shaped like the mockup's placeholder `REQ-2026-0148`: the year the request
 * arrived, then the entry's own ID. Unique by construction, and it doubles as
 * the key an admin searches the inbox by.
 *
 * @param int $post_id Stored entry ID.
 * @return string
 */
function intera_form_reference_for( $post_id ) {
	return sprintf( 'REQ-%s-%04d', current_time( 'Y' ), (int) $post_id );
}

/**
 * Mails the submission to the address in the Customizer.
 *
 * Plain text on purpose: this is an internal notification, not a newsletter,
 * and the reply goes straight back to the sender via `Reply-To`.
 *
 * @param array<string,string> $values    Sanitised values.
 * @param string               $reference Generated reference number.
 * @return bool Whether `wp_mail()` accepted the message.
 */
function intera_form_notify( $values, $reference ) {
	$to = sanitize_email( (string) intera_option( 'contact_notify' ) );

	/*
	 * The recipient is an internal address, never one the site prints, so an
	 * empty field is the normal state and must not mean "send nowhere": fall
	 * back to the address WordPress already knows the site by. A request that
	 * is stored but silently unannounced is the outcome this handler exists
	 * to prevent.
	 */
	if ( ! is_email( $to ) ) {
		$to = sanitize_email( (string) get_option( 'admin_email' ) );
	}

	if ( ! is_email( $to ) ) {
		return false;
	}

	$subject = (string) intera_option( 'contact_request_subject', intera_form_subject_default() );
	$subject = strtr(
		$subject,
		array(
			'{name}'      => '' !== $values['name'] ? $values['name'] : __( 'Anonymous', 'intera' ),
			'{company}'   => '' !== $values['company'] ? $values['company'] : __( 'no company given', 'intera' ),
			'{interest}'  => '' !== $values['interest'] ? $values['interest'] : __( 'unspecified', 'intera' ),
			'{reference}' => $reference,
		)
	);
	$subject = wp_specialchars_decode( sanitize_text_field( $subject ), ENT_QUOTES );

	$lines = array(
		sprintf(
			/* translators: %s: request reference, e.g. REQ-2026-0148. */
			__( 'Reference: %s', 'intera' ),
			$reference
		),
		'',
	);

	foreach ( intera_request_fields() as $key => $field ) {
		$value = isset( $values[ $key ] ) && '' !== $values[ $key ] ? $values[ $key ] : __( '—', 'intera' );

		if ( 'textarea' === $field['type'] ) {
			$lines[] = $field['label'] . ':';
			$lines[] = $value;
		} else {
			$lines[] = $field['label'] . ': ' . $value;
		}
	}

	$lines[] = '';
	$lines[] = __( 'The sender agreed to be contacted about this request.', 'intera' );
	$lines[] = sprintf(
		/* translators: %s: site name. */
		__( 'Sent from the request form on %s.', 'intera' ),
		wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES )
	);

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

	if ( is_email( $values['email'] ) ) {
		$headers[] = sprintf( 'Reply-To: %s <%s>', $values['name'], $values['email'] );
	}

	return (bool) wp_mail( $to, $subject, implode( "\n", $lines ), $headers );
}

/**
 * Default notification subject, with the placeholders the control documents.
 *
 * @return string
 */
function intera_form_subject_default() {
	return __( 'New request from {name} ({company})', 'intera' );
}


/* -------------------------------------------------------------------------
 * The inbox: post type, columns, read-only detail
 * ---------------------------------------------------------------------- */

/**
 * Registers the `intera_request` post type.
 *
 * Not public, not queryable, not in search, no archive, no REST: submissions
 * are never a URL on this site. Entries are only ever created by the handler,
 * so `create_posts` is denied outright, and every other capability maps to
 * `manage_options` — an editor writing pages has no business in the inbox.
 *
 * @return void
 */
function intera_register_request_post_type() {
	register_post_type(
		INTERA_REQUEST_POST_TYPE,
		array(
			'labels'              => array(
				'name'               => __( 'Requests', 'intera' ),
				'singular_name'      => __( 'Request', 'intera' ),
				'menu_name'          => __( 'Requests', 'intera' ),
				'all_items'          => __( 'All requests', 'intera' ),
				'edit_item'          => __( 'Request', 'intera' ),
				'view_item'          => __( 'View request', 'intera' ),
				'search_items'       => __( 'Search requests', 'intera' ),
				'not_found'          => __( 'No requests yet.', 'intera' ),
				'not_found_in_trash' => __( 'No requests in the bin.', 'intera' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => false,
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-email-alt',
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			/*
			 * Only the *primitive* capabilities are renamed here. The three meta
			 * capabilities — `edit_post`, `read_post`, `delete_post` — are left
			 * at their defaults on purpose, and it is not a style preference.
			 *
			 * `register_post_type()` copies a renamed meta capability into the
			 * global `$post_type_meta_caps` map:
			 *
			 *     foreach ( $post_type_object->cap as $core => $custom ) {
			 *         if ( in_array( $core, array( 'read_post', 'delete_post', 'edit_post' ), true ) ) {
			 *             $post_type_meta_caps[ $custom ] = $core;
			 *         }
			 *     }
			 *
			 * and `map_meta_cap()` consults that map for *every* capability it
			 * does not recognise. Pointing a meta capability at `manage_options`
			 * therefore turns `manage_options` itself into a meta capability
			 * site-wide: every `current_user_can( 'manage_options' )` is remapped
			 * to a check that needs a post ID, gets none, and returns
			 * `do_not_allow` — for administrators too. Half of wp-admin
			 * disappears, and the pages that do not register answer direct
			 * requests with "Sorry, you are not allowed to access this page."
			 *
			 * The primitive renames below are enough on their own: with
			 * `map_meta_cap => true`, WordPress resolves `edit_post` through
			 * `edit_others_posts`, which is `manage_options` here. Same outcome —
			 * only administrators reach the inbox — without touching the map.
			 */
			'capabilities'        => array(
				'create_posts'        => 'do_not_allow',
				'edit_posts'          => 'manage_options',
				'edit_others_posts'   => 'manage_options',
				'delete_posts'        => 'manage_options',
				'delete_others_posts' => 'manage_options',
				'publish_posts'       => 'manage_options',
				'read_private_posts'  => 'manage_options',
			),
		)
	);

	$keys   = array_keys( intera_request_fields() );
	$keys[] = 'reference';

	foreach ( $keys as $key ) {
		register_post_meta(
			INTERA_REQUEST_POST_TYPE,
			'_intera_request_' . $key,
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => false,
				'sanitize_callback' => 'sanitize_textarea_field',
				'auth_callback'     => 'intera_request_meta_auth',
			)
		);
	}

	foreach ( array( 'consent', 'mailed' ) as $key ) {
		register_post_meta(
			INTERA_REQUEST_POST_TYPE,
			'_intera_request_' . $key,
			array(
				'type'              => 'integer',
				'single'            => true,
				'show_in_rest'      => false,
				'sanitize_callback' => 'absint',
				'auth_callback'     => 'intera_request_meta_auth',
			)
		);
	}
}
add_action( 'init', 'intera_register_request_post_type' );

/**
 * Only an administrator may touch a stored request.
 *
 * @return bool
 */
function intera_request_meta_auth() {
	return current_user_can( 'manage_options' );
}

/**
 * The inbox list columns: name, email, date.
 *
 * @param array<string,string> $columns Default columns.
 * @return array<string,string>
 */
function intera_request_columns( $columns ) {
	$out = array();

	foreach ( $columns as $key => $label ) {
		if ( 'date' === $key ) {
			$out['intera_name']  = __( 'Name', 'intera' );
			$out['intera_email'] = __( 'Email', 'intera' );
		}

		if ( 'title' === $key ) {
			$label = __( 'Reference', 'intera' );
		}

		$out[ $key ] = $label;
	}

	if ( ! isset( $out['intera_name'] ) ) {
		$out['intera_name']  = __( 'Name', 'intera' );
		$out['intera_email'] = __( 'Email', 'intera' );
	}

	return $out;
}
add_filter( 'manage_' . INTERA_REQUEST_POST_TYPE . '_posts_columns', 'intera_request_columns' );

/**
 * Fills the name and email columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Entry ID.
 * @return void
 */
function intera_request_column( $column, $post_id ) {
	if ( 'intera_name' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, '_intera_request_name', true ) );
		return;
	}

	if ( 'intera_email' === $column ) {
		$email = (string) get_post_meta( $post_id, '_intera_request_email', true );

		if ( is_email( $email ) ) {
			printf( '<a href="%1$s">%2$s</a>', esc_url( 'mailto:' . $email ), esc_html( $email ) );
		}
	}
}
add_action( 'manage_' . INTERA_REQUEST_POST_TYPE . '_posts_custom_column', 'intera_request_column', 10, 2 );

/**
 * Adds the read-only detail box — the entry supports only a title, so without
 * this an admin opening a request would see nothing they came for.
 *
 * @return void
 */
function intera_request_meta_box() {
	add_meta_box(
		'intera_request_detail',
		__( 'Request', 'intera' ),
		'intera_request_meta_box_render',
		INTERA_REQUEST_POST_TYPE,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_' . INTERA_REQUEST_POST_TYPE, 'intera_request_meta_box' );

/**
 * Renders the stored submission, read only.
 *
 * @param WP_Post $post The entry.
 * @return void
 */
function intera_request_meta_box_render( $post ) {
	$rows = array(
		__( 'Reference', 'intera' ) => (string) get_post_meta( $post->ID, '_intera_request_reference', true ),
	);

	foreach ( intera_request_fields() as $key => $field ) {
		$rows[ $field['label'] ] = (string) get_post_meta( $post->ID, '_intera_request_' . $key, true );
	}

	$rows[ __( 'Notification sent', 'intera' ) ] = get_post_meta( $post->ID, '_intera_request_mailed', true )
		? __( 'Yes', 'intera' )
		: __( 'No — check the site mail configuration', 'intera' );

	echo '<table class="widefat striped"><tbody>';

	foreach ( $rows as $label => $value ) {
		printf(
			'<tr><th scope="row" style="width:200px">%1$s</th><td>%2$s</td></tr>',
			esc_html( $label ),
			nl2br( esc_html( '' !== $value ? $value : '—' ) )
		);
	}

	echo '</tbody></table>';
}


/* -------------------------------------------------------------------------
 * The words, in the Customizer
 * ---------------------------------------------------------------------- */

/**
 * The seven request-form settings, with their labels and defaults.
 *
 * Split out of the Customizer registration so `intera_option_defaults()` can
 * merge the same defaults in: a caller that reads one of these keys without
 * repeating the default at the call site must still get the designed copy
 * rather than an empty string.
 *
 * @return array<string,array<string,string>> Option key => control definition.
 */
function intera_form_settings() {
return array(
	'contact_request_subject' => array(
		'label'       => __( 'Notification subject', 'intera' ),
		'description' => __( 'Placeholders: {name}, {company}, {interest}, {reference}.', 'intera' ),
		'default'     => intera_form_subject_default(),
		'type'        => 'text',
		'sanitize'    => 'sanitize_text_field',
	),
	'contact_consent_label'   => array(
		'label'    => __( 'Consent checkbox', 'intera' ),
		'default'  => __( 'I agree that INTERA may contact me about this request.', 'intera' ),
		'type'     => 'text',
		'sanitize' => 'sanitize_text_field',
	),
	'contact_success_title'   => array(
		'label'    => __( 'Success heading', 'intera' ),
		'default'  => __( 'Request received', 'intera' ),
		'type'     => 'text',
		'sanitize' => 'sanitize_text_field',
	),
	'contact_success_body'    => array(
		'label'    => __( 'Success paragraph', 'intera' ),
		'default'  => __( 'We read every one of these ourselves. You will get an answer describing what INTERA would watch in your case, which systems it would read and what the first role would contain.', 'intera' ),
		'type'     => 'textarea',
		'sanitize' => 'sanitize_textarea_field',
	),
	'contact_success_answer'  => array(
		'label'       => __( 'Answer expected', 'intera' ),
		'description' => __( 'Printed next to the reference number on the success card.', 'intera' ),
		'default'     => __( 'within 1 working day', 'intera' ),
		'type'        => 'text',
		'sanitize'    => 'sanitize_text_field',
	),
	'contact_industries'      => array(
		'label'       => __( 'Industry options', 'intera' ),
		'description' => __( 'One per line. A submission that does not match this list is rejected.', 'intera' ),
		'default'     => intera_form_industries_default(),
		'type'        => 'textarea',
		'sanitize'    => 'sanitize_textarea_field',
	),
	'contact_interests'       => array(
		'label'       => __( '“What brings you here” options', 'intera' ),
		'description' => __( 'One per line. A submission that does not match this list is rejected.', 'intera' ),
		'default'     => intera_form_interests_default(),
		'type'        => 'textarea',
		'sanitize'    => 'sanitize_textarea_field',
	),
);
}

/**
 * Just those defaults, keyed by option name.
 *
 * @return array<string,string>
 */
function intera_form_option_defaults() {
	$defaults = array();

	foreach ( intera_form_settings() as $intera_key => $intera_field ) {
		$defaults[ $intera_key ] = $intera_field['default'];
	}

	return $defaults;
}

/**
 * Registers the request-form options.
 *
 * Runs after `intera_customize_register()` so the `intera` panel already
 * exists. The recipient itself is not repeated here — it is the
 * `contact_notify` address the Contacts section carries.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function intera_customize_register_form( $wp_customize ) {
	$wp_customize->add_section(
		'intera_request_form',
		array(
			'title'       => __( 'Request form', 'intera' ),
			'description' => __( 'The two option lists, the notification subject and the words on the success card. Requests are mailed to the notification address in Contacts and kept under Requests in the admin menu.', 'intera' ),
			'panel'       => 'intera',
			'priority'    => 35,
		)
	);

	$fields = intera_form_settings();

	foreach ( $fields as $key => $field ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => $field['default'],
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => $field['sanitize'],
			)
		);

		$wp_customize->add_control(
			$key,
			array(
				'label'       => $field['label'],
				'description' => isset( $field['description'] ) ? $field['description'] : '',
				'section'     => 'intera_request_form',
				'type'        => $field['type'],
			)
		);
	}
}
add_action( 'customize_register', 'intera_customize_register_form', 20 );
