<?php
/**
 * Template Name: Contact request
 *
 * Ported from `_design/06-contact-request.dc.html` (recon §8). Two sections, in
 * the export's order: `Request header` and `Request form` (the form beside the
 * "What happens next" aside), and a third under them that the export does not
 * have: `What happens if you say yes`, the six steps of the Early Adopter
 * Program spelled out at full width. It is built from the same band, card and
 * mono numeral the rest of the site uses, and every word of it is a copy key.
 *
 * This is the only screen in the set with a real state machine. The export
 * drives it from `state = { sent: false }` and two `<sc-if>` branches; here the
 * flag comes back from the server after a POST/redirect/GET round trip, so a
 * refresh can never resubmit. `inc/forms.php` owns everything behind the
 * `<form>`; this file only renders it and reads the outcome back.
 *
 * **What the live site sends through is Contact Form 7.** The form, its fields,
 * its two mails, the SMTP transport, the Flamingo record and the reCAPTCHA v2
 * checkbox are all configured in wp-admin, so the first branch below renders
 * the plugin's own form inside the export's Card and `inc/cf7.php` dresses it
 * in the design system — the plugin's controls, at the design's size, colour
 * and grid. The two branches under it are what draws the screen when there is
 * no plugin form to render: the theme's own form, and its success card. A
 * request page that has quietly stopped sending is the one outcome none of the
 * three may have.
 *
 * What comes from WordPress:
 *
 * | slot                    | source                                            |
 * | ----------------------- | ------------------------------------------------- |
 * | breadcrumb              | `intera_breadcrumbs()` (Home / Contacts / <title>) |
 * | header heading          | `the_title()`                                      |
 * | header lede             | `the_content()` — one paragraph, "Large" preset     |
 * | form action + plumbing  | `intera_form_action_url_get()`, `intera_form_hidden_fields()` |
 * | the two option lists    | `intera_form_industries_get()` / `..._interests_get()` |
 * | consent sentence        | `contact_consent_label`                            |
 * | validation messages     | `intera_form_errors_get()`, into the DS Field error branch |
 * | refilled values         | `intera_form_old_get()`                            |
 * | success branch          | `intera_form_succeeded()` + `contact_success_title/body/answer` |
 * | the reference number    | `intera_form_reference_get()` — the export's `REQ-2026-0148` |
 * | every internal link     | `intera_page_url()`; the direct route is `contact_person` |
 *
 * The rest is the handoff's fixed copy, per recon §8 ("Dynamic slots: the whole
 * form"): the field labels, hints and placeholders, the three "What happens
 * next" steps and the Early Adopter card. Field labels, hints and placeholders
 * belong to the screen, which is why `inc/forms.php` deliberately does not own
 * them.
 *
 * `#intera-request-form` is the fragment every redirect in `inc/forms.php`
 * targets, so it sits on whichever branch is rendering: the `<form>` itself
 * while the form shows, the success Card once it has been sent. The two are
 * mutually exclusive, so the id is never duplicated.
 *
 * PORT.md §1: nothing carrying `.itr-hl` gets `background`, `border`,
 * `border-color`, `box-shadow` or `transition` inline — an inline declaration
 * outranks the hover rules in `assets/css/intera.css` and the card would sit
 * there dead. The Card component emits the resting values as `--itr-edge` /
 * `--itr-shadow`. The export's preview-only `style-hover` attributes are
 * dropped; `a.itr-crumb` carries the breadcrumb hover.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
	the_post();
}

/*
 * The five link targets on this screen. `intera_page_url()` resolves each one
 * by the page template assigned in the editor, so no `*.dc.html` href survives.
 * An unresolved key returns '' and the Button component renders an inert
 * <button> rather than a link to nowhere.
 */
$intera_docs_url    = (string) intera_page_url( 'docs' );
$intera_pricing_url = (string) intera_page_url( 'pricing' );
$intera_legal_url   = (string) intera_page_url( 'legal' );
$intera_self_url    = (string) get_permalink();
$intera_person      = trim( (string) intera_option( 'contact_person' ) );
$intera_person_url  = trim( (string) intera_option( 'contact_person_url' ) );

// A name with nowhere to go, or a link with nothing to label it, is not a route.
$intera_has_person  = ( '' !== $intera_person && '' !== $intera_person_url );

// What the handler left behind for us: the branch, the messages, the reference.
$intera_sent      = intera_form_succeeded();
$intera_errors    = intera_form_errors_get();
$intera_reference = intera_form_reference_get();

/*
 * The form the live site actually sends through: Contact Form 7, configured in
 * wp-admin, mailed over SMTP, filed by Flamingo and gated by a reCAPTCHA v2
 * checkbox. `inc/cf7.php` renders the plugin's own form and dresses it in the
 * design system, so this file only chooses between the two.
 *
 * '' means there is no plugin form to show — deactivated, ID cleared, form
 * deleted — and the theme's own handler below takes the screen back. A request
 * page that has stopped sending is the one outcome neither branch may have.
 */
$intera_cf7_form = function_exists( 'intera_cf7_form_html' ) ? intera_cf7_form_html() : '';

/**
 * The line under the form about what happens to what was typed into it.
 *
 * Rendered by both branches, so it is composed once. The legal page is a link
 * when the editor has assigned that template and plain words when they have
 * not — the design never prints a link to nowhere.
 *
 * @param string $legal_url Permalink of the policy page, or ''.
 * @return string
 */
$intera_privacy_note = static function ( $legal_url ) {
	ob_start();
	?>
	<p style="font-size: var(--text-xs); color: var(--ink-500); margin-top: 14px; line-height: 1.5">
		<?php
		if ( '' !== $legal_url ) {
			/* translators: %s: link to the privacy policy, reading "privacy policy". */
			echo wp_kses_post(
				intera_copy_format(
					'request_request_form__we_use_what_you_send_only',
					'<a href="' . esc_url( $legal_url ) . '">' . esc_html( intera_copy( 'request_request_form__privacy_policy' ) ) . '</a>'
				)
			);
		} else {
			echo esc_html( intera_copy( 'request_request_form__we_use_what_you_send_only_2' ) );
		}
		?>
	</p>
	<?php
	return (string) ob_get_clean();
};

/**
 * One validation message, or '' when the field passed.
 *
 * @param array<string,string> $errors Field key => message.
 * @param string               $field  Field key.
 * @return string
 */
$intera_error_for = static function ( $errors, $field ) {
	return isset( $errors[ $field ] ) ? (string) $errors[ $field ] : '';
};
?>

<section data-screen-label="Request header" style="background: var(--surface-page); border-bottom: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(32px, 7vw, 56px) clamp(20px, 5vw, 24px) clamp(23px, 7vw, 40px)">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 660px; margin-top: 22px">
			<h1 style="font-size: clamp(28px, 3vw, 36px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900)"><?php
					/*
					 * The design's headline, not the page title. The two are
					 * different jobs: the title is what the breadcrumb and the
					 * menu say, and it has to stay short, while this is a full
					 * sentence. Falls back to the title when the field is empty.
					 */
					$intera_headline = trim( (string) intera_copy( 'request_headline' ) );
					echo esc_html( '' !== $intera_headline ? $intera_headline : get_the_title() );
					?></h1>
			<?php if ( '' !== trim( (string) get_the_content() ) ) : ?>
				<div class="intera-prose" style="--itr-prose-max: 660px; margin-top: 16px"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section data-screen-label="Request form" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(28px, 7vw, 48px) clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 44px; align-items: start">
		<?php
		if ( '' !== $intera_cf7_form ) :

			/* ---------------------------------------------------------------
			 * The plugin branch. The export's Card, with Contact Form 7's own
			 * form inside it in place of the theme's seven controls: same
			 * frame, same padding, same `itr-hl` hover, and the plugin keeps
			 * its nonce, its endpoint, its captcha, its mail and its spam
			 * check. `inc/cf7.php` is where the plugin's markup is dressed.
			 *
			 * The Card is a <div>, not a <form>: the plugin draws the <form>
			 * itself, and nesting one inside another is markup no browser will
			 * keep. `#intera-request-form` stays on the outer element so every
			 * link on the site that points at the form still lands on it.
			 * ------------------------------------------------------------ */
			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'padding' => 'loose',
					'class'   => 'itr-hl',
					// The plugin's own markup, dressed in inc/cf7.php.
					'content' => $intera_cf7_form . $intera_privacy_note( $intera_legal_url ),
					'attrs'   => array( 'id' => 'intera-request-form' ),
				)
			);

		elseif ( ! $intera_sent ) :

			/* ---------------------------------------------------------------
			 * The form branch — the export's `<sc-if value="{{ showForm }}">`.
			 * ------------------------------------------------------------ */
			ob_start();

			// Routing, nonce, honeypot and time trap. Never visible.
			intera_form_hidden_fields();

			// A failure that belongs to no single field — a failed insert.
			if ( '' !== $intera_error_for( $intera_errors, 'form' ) ) {
				get_template_part(
					'template-parts/components/alert',
					null,
					array(
						'tone'  => 'critical',
						'text'  => $intera_error_for( $intera_errors, 'form' ),
						'style' => 'margin-bottom:18px',
					)
				);
			}
			?>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(220px, 100%), 1fr)); gap: 18px">
				<?php
				ob_start();
				get_template_part(
					'template-parts/components/input',
					null,
					array(
						'id'          => 'name',
						'name'        => 'name',
						'value'       => intera_form_old_get( 'name' ),
						'placeholder' => intera_copy( 'request_request_form__anna_kovalenko' ),
						'required'    => true,
						'invalid'     => '' !== $intera_error_for( $intera_errors, 'name' ),
						'attrs'       => array( 'autocomplete' => 'name' ),
					)
				);
				$intera_control = ob_get_clean();

				get_template_part(
					'template-parts/components/field',
					null,
					array(
						'label'    => intera_copy( 'request_request_form__name' ),
						'for'      => 'name',
						'required' => true,
						'error'    => $intera_error_for( $intera_errors, 'name' ),
						'content'  => $intera_control,
					)
				);

				ob_start();
				get_template_part(
					'template-parts/components/input',
					null,
					array(
						'type'        => 'email',
						'id'          => 'email',
						'name'        => 'email',
						'value'       => intera_form_old_get( 'email' ),
						'placeholder' => intera_copy( 'request_request_form__a_kovalenko_company_com' ),
						'required'    => true,
						'invalid'     => '' !== $intera_error_for( $intera_errors, 'email' ),
						'attrs'       => array( 'autocomplete' => 'email' ),
					)
				);
				$intera_control = ob_get_clean();

				get_template_part(
					'template-parts/components/field',
					null,
					array(
						'label'    => intera_copy( 'request_request_form__work_email' ),
						'for'      => 'email',
						'required' => true,
						'error'    => $intera_error_for( $intera_errors, 'email' ),
						'content'  => $intera_control,
					)
				);

				ob_start();
				get_template_part(
					'template-parts/components/input',
					null,
					array(
						'id'          => 'company',
						'name'        => 'company',
						'value'       => intera_form_old_get( 'company' ),
						'placeholder' => intera_copy( 'request_request_form__company_name' ),
						'invalid'     => '' !== $intera_error_for( $intera_errors, 'company' ),
						'attrs'       => array( 'autocomplete' => 'organization' ),
					)
				);
				$intera_control = ob_get_clean();

				get_template_part(
					'template-parts/components/field',
					null,
					array(
						'label'   => intera_copy( 'request_request_form__company' ),
						'for'     => 'company',
						'error'   => $intera_error_for( $intera_errors, 'company' ),
						'content' => $intera_control,
					)
				);

				ob_start();
				get_template_part(
					'template-parts/components/input',
					null,
					array(
						'id'          => 'role',
						'name'        => 'role',
						'value'       => intera_form_old_get( 'role' ),
						'placeholder' => intera_copy( 'request_request_form__billing_operations_manager' ),
						'invalid'     => '' !== $intera_error_for( $intera_errors, 'role' ),
						'attrs'       => array( 'autocomplete' => 'organization-title' ),
					)
				);
				$intera_control = ob_get_clean();

				get_template_part(
					'template-parts/components/field',
					null,
					array(
						'label'   => intera_copy( 'request_request_form__your_role' ),
						'for'     => 'role',
						'hint'    => intera_copy( 'request_request_form__the_area_you_are_responsible_for' ),
						'error'   => $intera_error_for( $intera_errors, 'role' ),
						'content' => $intera_control,
					)
				);

				ob_start();
				get_template_part(
					'template-parts/components/select',
					null,
					array(
						'id'          => 'industry',
						'name'        => 'industry',
						'options'     => intera_form_industries_get(),
						'value'       => intera_form_old_get( 'industry' ),
						'placeholder' => intera_copy( 'request_request_form__choose_an_industry' ),
						'invalid'     => '' !== $intera_error_for( $intera_errors, 'industry' ),
					)
				);
				$intera_control = ob_get_clean();

				get_template_part(
					'template-parts/components/field',
					null,
					array(
						'label'   => intera_copy( 'request_request_form__industry' ),
						'for'     => 'industry',
						'error'   => $intera_error_for( $intera_errors, 'industry' ),
						'content' => $intera_control,
					)
				);

				ob_start();
				get_template_part(
					'template-parts/components/select',
					null,
					array(
						'id'          => 'interest',
						'name'        => 'interest',
						'options'     => intera_form_interests_get(),
						'value'       => intera_form_old_get( 'interest' ),
						'placeholder' => intera_copy( 'request_request_form__choose_one' ),
						'invalid'     => '' !== $intera_error_for( $intera_errors, 'interest' ),
					)
				);
				$intera_control = ob_get_clean();

				get_template_part(
					'template-parts/components/field',
					null,
					array(
						'label'   => intera_copy( 'request_request_form__what_brings_you_here' ),
						'for'     => 'interest',
						'error'   => $intera_error_for( $intera_errors, 'interest' ),
						'content' => $intera_control,
					)
				);
				?>
			</div>
			<div style="margin-top: 18px">
				<?php
				ob_start();
				get_template_part(
					'template-parts/components/textarea',
					null,
					array(
						'id'          => 'problem',
						'name'        => 'problem',
						'value'       => intera_form_old_get( 'problem' ),
						'rows'        => 6,
						'placeholder' => intera_copy( 'request_request_form__every_month_someone_exports_usage_from' ),
						'required'    => true,
						'invalid'     => '' !== $intera_error_for( $intera_errors, 'problem' ),
					)
				);
				$intera_control = ob_get_clean();

				get_template_part(
					'template-parts/components/field',
					null,
					array(
						'label'    => intera_copy( 'request_request_form__the_problem_in_your_words' ),
						'for'      => 'problem',
						'required' => true,
						'hint'     => intera_copy( 'request_request_form__what_is_checked_manually_today_which' ),
						'error'    => $intera_error_for( $intera_errors, 'problem' ),
						'content'  => $intera_control,
					)
				);
				?>
			</div>
			<div style="margin-top: 18px; padding-top: 18px; border-top: 1px solid var(--border-hairline); display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between">
				<?php
				/*
				 * The consent box sits in a Field so a rejected submission can
				 * show its message in the same --status-critical style as the
				 * seven controls above. Field renders nothing but the control
				 * when it has neither a label nor an error.
				 */
				ob_start();
				get_template_part(
					'template-parts/components/checkbox',
					null,
					array(
						'id'       => 'consent',
						'name'     => 'consent',
						'value'    => '1',
						'label'    => (string) intera_option( 'contact_consent_label' ),
						'checked'  => '1' === intera_form_old_get( 'consent' ),
						'required' => true,
					)
				);
				$intera_control = ob_get_clean();

				get_template_part(
					'template-parts/components/field',
					null,
					array(
						'error'   => $intera_error_for( $intera_errors, 'consent' ),
						'content' => $intera_control,
						'style'   => 'max-width:320px',
					)
				);

				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => intera_copy( 'request_request_form__send_request' ),
						'size'  => 'lg',
						'type'  => 'submit',
					)
				);
				?>
			</div>
			<?php
			echo $intera_privacy_note( $intera_legal_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped where it is composed.

			$intera_form_body = ob_get_clean();

			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'tag'     => 'form',
					'padding' => 'loose',
					'class'   => 'itr-hl',
					'content' => $intera_form_body,
					'attrs'   => array(
						'id'     => 'intera-request-form',
						'method' => 'post',
						'action' => intera_form_action_url_get(),
					),
				)
			);

		else :

			/* ---------------------------------------------------------------
			 * The success branch — `<sc-if value="{{ showSuccess }}">`.
			 * ------------------------------------------------------------ */
			ob_start();
			?>
			<div style="display: flex; align-items: center; gap: 10px; color: var(--status-ok)">
				<?php intera_icon( 'check-circle', array( 'size' => 20 ) ); ?>
				<span style="font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( (string) intera_option( 'contact_success_title' ) ); ?></span>
			</div>
			<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 14px; max-width: 520px"><?php echo esc_html( (string) intera_option( 'contact_success_body' ) ); ?></p>
			<div style="display: flex; flex-direction: column; gap: 10px; margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--border-hairline); font-size: var(--text-md); color: var(--ink-700)">
				<?php if ( '' !== $intera_reference ) : ?>
					<span>
						<?php
						/* translators: %s: request reference number, e.g. REQ-2026-0148. */
						echo wp_kses_post(
							intera_copy_format(
								'request_request_form__reference_s',
								'<span style="font-family: var(--font-mono)">' . esc_html( $intera_reference ) . '</span>'
							)
						);
						?>
					</span>
				<?php endif; ?>
				<span>
					<?php
					/* translators: %s: how soon the request is answered, e.g. "within 1 working day". */
					echo wp_kses_post(
						intera_copy_format(
							'request_request_form__answer_expected_s',
							'<span style="font-family: var(--font-mono)">' . esc_html( (string) intera_option( 'contact_success_answer' ) ) . '</span>'
						)
					);
					?>
				</span>
			</div>
			<div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 24px">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'   => intera_copy( 'request_request_form__read_the_docs' ),
						'href'    => $intera_docs_url,
						'variant' => 'secondary',
					)
				);

				/*
				 * The export resets `sent` in the browser. Here the flag lives
				 * in the URL, so "send another" is simply this page without the
				 * handler's query arguments.
				 */
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'   => intera_copy( 'request_request_form__send_another_request' ),
						'href'    => $intera_self_url,
						'variant' => 'ghost',
					)
				);
				?>
			</div>
			<?php
			$intera_success_body = ob_get_clean();

			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'padding'     => 'loose',
					'accent'      => 'var(--status-ok)',
					'accent_line' => 'var(--status-ok-line)',
					'class'       => 'itr-hl',
					'content'     => $intera_success_body,
					'attrs'       => array( 'id' => 'intera-request-form' ),
				)
			);

		endif;
		?>

		<div style="display: flex; flex-direction: column; gap: 24px; max-width: 400px">
			<div>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500); margin-bottom: 16px"><?php echo esc_html( intera_copy( 'request_request_form__what_happens_next' ) ); ?></div>
				<div style="display: flex; flex-direction: column; gap: 0; border-top: 1px solid var(--border-hairline)">
					<?php
					$intera_steps = array(
						array(
							'title' => intera_copy( 'request_request_form__we_read_it_and_answer' ),
							'body'  => intera_copy( 'request_request_form__usually_the_same_working_day_no' ),
						),
						array(
							'title' => intera_copy( 'request_request_form__one_call_30_minutes' ),
							'body'  => intera_copy( 'request_request_form__we_map_the_problem_to_a' ),
						),
						array(
							'title' => intera_copy( 'request_request_form__one_role_one_result' ),
							'body'  => intera_copy( 'request_request_form__we_set_up_the_first_check' ),
						),
					);

					$intera_step_number = 0;

					foreach ( $intera_steps as $intera_step ) :
						++$intera_step_number;
						?>
						<div style="display: flex; gap: 14px; padding: 16px 0; border-bottom: 1px solid var(--border-hairline)">
							<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted); padding-top: 3px"><?php echo esc_html( sprintf( '%02d', $intera_step_number ) ); ?></span>
							<div>
								<div style="font-size: var(--text-md); font-weight: 500; color: var(--ink-900)"><?php echo esc_html( $intera_step['title'] ); ?></div>
								<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 4px"><?php echo esc_html( $intera_step['body'] ); ?></p>
							</div>
						</div>
						<?php
					endforeach;
					?>
				</div>
			</div>
			<?php
			ob_start();
			?>
			<div style="font-size: var(--text-md); font-weight: 600; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'request_request_form__early_adopter_programme' ) ); ?></div>
			<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 8px"><?php echo esc_html( intera_copy( 'request_request_form__free_for_the_first_12_months' ) ); ?></p>
			<div style="margin-top: 14px">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'      => intera_copy( 'request_request_form__see_what_is_included' ),
						'href'       => $intera_pricing_url,
						'variant'    => 'link',
						'icon_right' => 'arrow-right',
					)
				);
				?>
			</div>
			<?php
			$intera_adopter_body = ob_get_clean();

			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'class'   => 'itr-hl',
					'content' => $intera_adopter_body,
				)
			);
			?>
			<?php if ( $intera_has_person ) : ?>
				<div style="font-size: var(--text-sm); color: var(--ink-600); line-height: 1.6">
					<?php
					/* translators: %s: link to the direct contact's profile, labelled with their name. */
					echo wp_kses_post(
						intera_copy_format(
							'request_request_form__prefer_a_direct_line_message_s',
							'<a class="itr-link-strong" href="' . esc_url( $intera_person_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $intera_person ) . '</a>'
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
/*
 * ---------------------------------------------------------------------------
 * "What happens if I say yes?" — the six steps of the Early Adopter Program.
 *
 * Not from the export: the handoff's answer to "what happens next" is the
 * three-step aside beside the form, which stays a summary. This band is the
 * long version — what an invited company is actually agreeing to, step by
 * step — so it sits below the form rather than beside it, where it has the
 * width for six cards and nothing competes with the controls.
 *
 * The band, the card, the mono step number and the closing line are all the
 * design system's: `surface-sunken` over a `wash-blue` bloom is the same
 * alternating band `page-contacts.php` uses for "Who to talk to", and the
 * numbered card is "How it works" on the front page. Every run of text is a
 * copy key, so the words are the editor's and the layout stays here.
 *
 * It renders under all three form branches — the plugin's form, the theme's
 * fallback and the success card — because what happens after a request is
 * sent is exactly what somebody who has just sent one wants to read.
 * ------------------------------------------------------------------------ */

$intera_yes_steps = array(
	array(
		'title' => intera_copy( 'request_say_yes__apply_for_the_early_adopter_program' ),
		'body'  => array(
			intera_copy( 'request_say_yes__tell_us_briefly_about_your_company' ),
			intera_copy( 'request_say_yes__we_are_currently_inviting_only_a' ),
		),
	),
	array(
		'title' => intera_copy( 'request_say_yes__we_agree_on_one_problem' ),
		'body'  => array(
			intera_copy( 'request_say_yes__no_company_wide_implementation' ),
			intera_copy( 'request_say_yes__we_choose_one_role_one_operational' ),
		),
	),
	array(
		'title' => intera_copy( 'request_say_yes__we_connect_the_required_data' ),
		'body'  => array(
			intera_copy( 'request_say_yes__intera_works_with_your_existing_systems' ),
			intera_copy( 'request_say_yes__no_migration_and_no_replacement_of' ),
		),
	),
	array(
		'title' => intera_copy( 'request_say_yes__we_build_the_first_working_view' ),
		'body'  => array(
			intera_copy( 'request_say_yes__this_may_be_a_health_view' ),
			intera_copy( 'request_say_yes__the_goal_is_simple_produce_something' ),
		),
	),
	array(
		'title' => intera_copy( 'request_say_yes__you_use_it_in_real_work' ),
		'body'  => array(
			intera_copy( 'request_say_yes__your_team_uses_the_result_and' ),
			intera_copy( 'request_say_yes__as_an_invited_early_adopter_you' ),
		),
	),
	array(
		'title' => intera_copy( 'request_say_yes__then_we_decide_whether_to_expand' ),
		'body'  => array(
			intera_copy( 'request_say_yes__if_intera_proves_useful_we_can' ),
			intera_copy( 'request_say_yes__if_it_does_not_there_is' ),
		),
	),
);
?>

<section data-screen-label="What happens if you say yes" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 16%; top: 24%; width: 820px; height: 820px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-blue) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(42px, 7vw, 72px) clamp(20px, 5vw, 24px)">
		<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900); max-width: 720px"><?php echo esc_html( intera_copy( 'request_say_yes__what_happens_if_i_say' ) ); ?></h2>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px; margin-top: 26px">
			<?php
			$intera_yes_number = 0;

			foreach ( $intera_yes_steps as $intera_yes_step ) {
				++$intera_yes_number;

				ob_start();
				?>
				<div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px; min-width: 0">
					<?php
					/*
					 * `flex: none` on the numeral and `min-width: 0` on the row
					 * are the two halves of the same rule (CLAUDE.md): the chip
					 * never squeezes below its own label, and a long title can
					 * still wrap instead of reaching past the card at 320px.
					 */
					?>
					<span style="flex: none; width: 30px; height: 30px; border-radius: var(--radius-md); background: var(--blue-50); border: 1px solid var(--blue-100); display: grid; place-items: center; font-family: var(--font-mono); font-size: var(--text-xs); color: var(--blue-600)"><?php echo esc_html( sprintf( '%02d', $intera_yes_number ) ); ?></span>
				</div>
				<div style="font-size: var(--text-lg); font-weight: 600; letter-spacing: -0.01em; line-height: 1.3; color: var(--ink-900); overflow-wrap: break-word"><?php echo esc_html( $intera_yes_step['title'] ); ?></div>
				<?php foreach ( $intera_yes_step['body'] as $intera_yes_line ) : ?>
					<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 10px"><?php echo esc_html( $intera_yes_line ); ?></p>
				<?php endforeach; ?>
				<?php
				$intera_yes_card = (string) ob_get_clean();

				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content' => $intera_yes_card,
						'padding' => 'loose',
						'class'   => 'itr-hl',
					)
				);
			}
			?>
		</div>
		<div style="margin-top: 30px; padding-top: 24px; border-top: 1px solid var(--border-hairline)">
			<p style="font-size: var(--text-lg); font-weight: 500; line-height: 1.6; color: var(--ink-800); max-width: 760px"><?php echo esc_html( intera_copy( 'request_say_yes__start_with_one_problem_prove_the' ) ); ?></p>
		</div>
	</div>
</section>

<?php
get_footer();
