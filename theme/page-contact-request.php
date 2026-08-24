<?php
/**
 * Template Name: Contact request
 *
 * Ported from `_design/06-contact-request.dc.html` (recon §8). Two sections, in
 * the export's order: `Request header` and `Request form` (the form beside the
 * "What happens next" aside).
 *
 * This is the only screen in the set with a real state machine. The export
 * drives it from `state = { sent: false }` and two `<sc-if>` branches; here the
 * flag comes back from the server after a POST/redirect/GET round trip, so a
 * refresh can never resubmit. `inc/forms.php` owns everything behind the
 * `<form>`; this file only renders it and reads the outcome back.
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
 * | every internal link     | `intera_page_url()`; the email is `contact_email`   |
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
$intera_email       = trim( (string) intera_option( 'contact_email' ) );

// What the handler left behind for us: the branch, the messages, the reference.
$intera_sent      = intera_form_succeeded();
$intera_errors    = intera_form_errors_get();
$intera_reference = intera_form_reference_get();

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
		if ( ! $intera_sent ) :

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
			<p style="font-size: var(--text-xs); color: var(--ink-500); margin-top: 14px; line-height: 1.5">
				<?php
				if ( '' !== $intera_legal_url ) {
					/* translators: %s: link to the privacy policy, reading "privacy policy". */
					echo wp_kses_post(
						intera_copy_format(
							'request_request_form__we_use_what_you_send_only',
							'<a href="' . esc_url( $intera_legal_url ) . '">' . esc_html( intera_copy( 'request_request_form__privacy_policy' ) ) . '</a>'
						)
					);
				} else {
					echo esc_html( intera_copy( 'request_request_form__we_use_what_you_send_only_2' ) );
				}
				?>
			</p>
			<?php
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
							<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-400); padding-top: 3px"><?php echo esc_html( sprintf( '%02d', $intera_step_number ) ); ?></span>
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
			<?php if ( '' !== $intera_email ) : ?>
				<div style="font-size: var(--text-sm); color: var(--ink-600); line-height: 1.6">
					<?php
					/* translators: %s: mailto link with the contact address. */
					echo wp_kses_post(
						intera_copy_format(
							'request_request_form__prefer_email_write_to_s',
							'<a href="' . esc_url( 'mailto:' . $intera_email ) . '" style="font-family: var(--font-mono)">' . esc_html( $intera_email ) . '</a>'
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_footer();
