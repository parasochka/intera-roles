<?php
/**
 * Template Name: Contacts
 *
 * Ported from `_design/05-contacts.dc.html` (recon §7). Three sections, in the
 * export's order: `Contacts header`, `Contact routes`, `Who to talk to`.
 *
 * What comes from WordPress:
 *
 * | slot                     | source                                          |
 * | ------------------------ | ----------------------------------------------- |
 * | breadcrumb               | `intera_breadcrumbs()` (auto: Home / <title>)    |
 * | header heading           | `the_title()`                                    |
 * | header lede              | `the_content()` — one paragraph, "Large" preset  |
 * | email address + mailto   | `intera_option( 'contact_email' )`               |
 * | response time            | `intera_option( 'contact_response' )`            |
 * | working language         | `intera_option( 'contact_languages' )`           |
 * | every internal link      | `intera_page_url()`                              |
 *
 * The three contact facts are site identity, not page content, so they live in
 * the Customizer and the footer, `page-contact-request.php` and `page-legal.php`
 * read the same three values. Each `Direct` row and each CTA disappears when its
 * option is cleared, so an empty value never ships an empty row or a `mailto:`
 * that goes nowhere.
 *
 * The rest is the handoff's fixed copy, per recon §7 ("Everything else is fixed
 * copy"): the `Direct` labels, the "What to send us" card, the three reasons and
 * the read-first line under them.
 *
 * PORT.md §1: the four Cards carry `.itr-hl`, so `background`, `border`,
 * `border-color`, `box-shadow` and `transition` are never inline here — the
 * component hands its resting values over as `--itr-edge` / `--itr-shadow` and
 * the hover rule in assets/css/intera.css wins. The export's `style-hover`
 * attributes are dropped for the same reason: the breadcrumb is `.itr-crumb`
 * and the email link `.itr-link-strong`, both with their resting colour in the
 * stylesheet. Everything else is the export's own inline style, verbatim.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
	the_post();
}

$intera_email     = trim( (string) intera_option( 'contact_email' ) );
$intera_response  = trim( (string) intera_option( 'contact_response' ) );
$intera_languages = trim( (string) intera_option( 'contact_languages' ) );

$intera_mailto = '' !== $intera_email ? 'mailto:' . $intera_email : '';

$intera_request_url = (string) intera_page_url( 'contact-request' );
$intera_pricing_url = (string) intera_page_url( 'pricing' );
$intera_docs_url    = (string) intera_page_url( 'docs' );
$intera_faq_url     = (string) intera_page_url( 'faq' );

// The export's "What to send us" list: three numbered items, ordinals printed 01–03.
$intera_send_items = array(
	array(
		'title' => __( 'The check nobody enjoys doing', 'intera' ),
		'body'  => __( 'The report, reconciliation or status update someone repeats every week.', 'intera' ),
	),
	array(
		'title' => __( 'The systems involved', 'intera' ),
		'body'  => __( 'ERP, CRM, billing, spreadsheets — names are enough, no diagrams needed.', 'intera' ),
	),
	array(
		'title' => __( 'What goes wrong when it is found too late', 'intera' ),
		'body'  => __( 'Lost revenue, a missed date, an escalation, a surprised customer.', 'intera' ),
	),
);

// The three routes into a conversation. Only the last one points somewhere else.
$intera_reasons = array(
	array(
		'icon'      => 'circle-dot',
		'title'     => __( 'Early Adopter', 'intera' ),
		'body'      => __( 'You have a real operational task and want it solved during beta. Twelve months free, custom onboarding, direct line to the team.', 'intera' ),
		'cta_label' => __( 'Apply as Early Adopter', 'intera' ),
		'cta_url'   => $intera_request_url,
	),
	array(
		'icon'      => 'git-branch',
		'title'     => __( 'Partner or reseller', 'intera' ),
		'body'      => __( 'You already solve operational problems for customers in one industry and want to package that as roles, checks and integrations.', 'intera' ),
		'cta_label' => __( 'Talk about partnership', 'intera' ),
		'cta_url'   => $intera_request_url,
	),
	array(
		'icon'      => 'settings',
		'title'     => __( 'Deployment and pricing', 'intera' ),
		'body'      => __( 'You know what you need and want the commercial detail: installation, integrations, implementation scope and cost.', 'intera' ),
		'cta_label' => __( 'See pricing first', 'intera' ),
		'cta_url'   => $intera_pricing_url,
	),
);

/*
 * The "What to send us" card body. Composed here so the Card component receives
 * one trusted string; every editorial value inside it is escaped as it is written.
 */
ob_start();
?>
<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php esc_html_e( 'What to send us', 'intera' ); ?></div>
<div style="display: flex; flex-direction: column; gap: 14px; margin-top: 18px">
	<?php foreach ( $intera_send_items as $intera_index => $intera_item ) : ?>
	<div style="display: flex; gap: 12px">
		<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-400); padding-top: 3px"><?php echo esc_html( sprintf( '%02d', $intera_index + 1 ) ); ?></span>
		<div>
			<div style="font-size: var(--text-md); font-weight: 500; color: var(--ink-900)"><?php echo esc_html( $intera_item['title'] ); ?></div>
			<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 4px"><?php echo esc_html( $intera_item['body'] ); ?></p>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php
$intera_send_card = (string) ob_get_clean();
?>

<section data-screen-label="Contacts header" style="background: var(--surface-page); border-bottom: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(35px, 7vw, 60px) clamp(20px, 5vw, 24px) clamp(26px, 7vw, 44px)">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 660px; margin-top: 22px">
			<h1 style="font-size: clamp(30px, 3.2vw, 38px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.14; color: var(--ink-900)"><?php the_title(); ?></h1>
			<?php if ( '' !== trim( (string) get_the_content() ) ) : ?>
				<div class="intera-prose" style="--itr-prose-max: 660px; margin-top: 18px"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section data-screen-label="Contact routes" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(32px, 7vw, 56px) clamp(20px, 5vw, 24px) 24px; display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 44px; align-items: start">
		<div>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500); margin-bottom: 18px"><?php esc_html_e( 'Direct', 'intera' ); ?></div>
			<div style="display: flex; flex-direction: column; gap: 0; border-top: 1px solid var(--border-hairline)">
				<?php if ( '' !== $intera_email ) : ?>
				<div style="display: flex; align-items: center; gap: 14px; padding: 18px 0; border-bottom: 1px solid var(--border-hairline)">
					<?php intera_icon( 'mail', array( 'size' => 17, 'color' => 'var(--ink-500)' ) ); ?>
					<div>
						<div style="font-size: var(--text-xs); color: var(--ink-500)"><?php esc_html_e( 'Write to us', 'intera' ); ?></div>
						<a class="itr-link-strong" href="<?php echo esc_url( $intera_mailto ); ?>" style="font-family: var(--font-mono); font-size: var(--text-md)"><?php echo esc_html( $intera_email ); ?></a>
					</div>
				</div>
				<?php endif; ?>

				<?php if ( '' !== $intera_response ) : ?>
				<div style="display: flex; align-items: center; gap: 14px; padding: 18px 0; border-bottom: 1px solid var(--border-hairline)">
					<?php intera_icon( 'clock', array( 'size' => 17, 'color' => 'var(--ink-500)' ) ); ?>
					<div>
						<div style="font-size: var(--text-xs); color: var(--ink-500)"><?php esc_html_e( 'Response time', 'intera' ); ?></div>
						<div style="font-size: var(--text-md); color: var(--ink-800)"><?php echo esc_html( $intera_response ); ?></div>
					</div>
				</div>
				<?php endif; ?>

				<?php if ( '' !== $intera_languages ) : ?>
				<div style="display: flex; align-items: center; gap: 14px; padding: 18px 0; border-bottom: 1px solid var(--border-hairline)">
					<?php intera_icon( 'globe', array( 'size' => 17, 'color' => 'var(--ink-500)' ) ); ?>
					<div>
						<div style="font-size: var(--text-xs); color: var(--ink-500)"><?php esc_html_e( 'Working language', 'intera' ); ?></div>
						<div style="font-size: var(--text-md); color: var(--ink-800)"><?php echo esc_html( $intera_languages ); ?></div>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<?php if ( '' !== $intera_request_url || '' !== $intera_mailto ) : ?>
			<div style="margin-top: 26px; display: flex; flex-wrap: wrap; gap: 12px">
				<?php
				if ( '' !== $intera_request_url ) {
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label' => __( 'Bring us a real problem', 'intera' ),
							'href'  => $intera_request_url,
							'size'  => 'lg',
						)
					);
				}

				if ( '' !== $intera_mailto ) {
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'   => __( 'Send an email', 'intera' ),
							'href'    => $intera_mailto,
							'variant' => 'secondary',
							'size'    => 'lg',
						)
					);
				}
				?>
			</div>
			<?php endif; ?>
		</div>

		<?php
		get_template_part(
			'template-parts/components/card',
			null,
			array(
				'content' => $intera_send_card,
				'padding' => 'loose',
				'class'   => 'itr-hl',
			)
		);
		?>
	</div>
</section>

<section data-screen-label="Who to talk to" style="background: var(--surface-sunken); border-top: 1px solid var(--border-subtle); margin-top: 40px">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(42px, 7vw, 72px) clamp(20px, 5vw, 24px)">
		<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)"><?php esc_html_e( 'Three reasons people write to us', 'intera' ); ?></h2>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px; margin-top: 26px">
			<?php
			foreach ( $intera_reasons as $intera_reason ) {
				ob_start();
				?>
				<div style="display: flex; align-items: center; gap: 10px">
					<?php intera_icon( $intera_reason['icon'], array( 'size' => 18, 'color' => 'var(--blue-600)' ) ); ?>
					<span style="font-size: var(--text-lg); font-weight: 600; letter-spacing: -0.01em"><?php echo esc_html( $intera_reason['title'] ); ?></span>
				</div>
				<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 10px"><?php echo esc_html( $intera_reason['body'] ); ?></p>
				<?php if ( '' !== $intera_reason['cta_url'] ) : ?>
				<div style="margin-top: 16px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'      => $intera_reason['cta_label'],
							'href'       => $intera_reason['cta_url'],
							'variant'    => 'link',
							'icon_right' => 'arrow-right',
						)
					);
					?>
				</div>
				<?php endif; ?>
				<?php
				$intera_reason_card = (string) ob_get_clean();

				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content'     => $intera_reason_card,
						'interactive' => true,
						'class'       => 'itr-hl',
					)
				);
			}
			?>
		</div>

		<?php
		/*
		 * "Prefer to read first?" — the two links are the docs archive and the
		 * FAQ page; each falls back to plain text when that page is not set up.
		 */
		$intera_docs_link = '' !== $intera_docs_url
			? '<a href="' . esc_url( $intera_docs_url ) . '">' . esc_html__( 'Documentation', 'intera' ) . '</a>'
			: esc_html__( 'Documentation', 'intera' );

		$intera_faq_link = '' !== $intera_faq_url
			? '<a href="' . esc_url( $intera_faq_url ) . '">' . esc_html__( 'FAQ', 'intera' ) . '</a>'
			: esc_html__( 'FAQ', 'intera' );
		?>
		<div style="display: flex; flex-wrap: wrap; gap: 24px; margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--border-hairline); font-size: var(--text-sm); color: var(--ink-600)">
			<span>
				<?php
				echo wp_kses_post(
					sprintf(
						/* translators: 1: link to the documentation, 2: link to the FAQ page. */
						__( 'Prefer to read first? %1$s and %2$s answer most first questions.', 'intera' ),
						$intera_docs_link,
						$intera_faq_link
					)
				);
				?>
			</span>
		</div>
	</div>
</section>

<?php
get_footer();
