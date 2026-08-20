<?php
/**
 * Template Name: Pricing
 *
 * Ported from `_design/03-pricing.dc.html` (recon §5). Five sections in the
 * export's order: pricing header, Plans, Comparison, Early Adopter, Pricing
 * questions.
 *
 * What comes from WordPress:
 *
 * | slot                    | source                                          |
 * | ----------------------- | ----------------------------------------------- |
 * | breadcrumb              | `intera_breadcrumbs()` (auto: Home / <title>)    |
 * | header heading          | `the_title()`                                    |
 * | header lede             | `the_content()` — one paragraph, "Large" preset  |
 * | the three plan cards    | the `plan` post type, via `partials/plan-card`   |
 * | each card's price + CTA | `_intera_plan_*` meta and the plan's own content |
 * | the comparison headings | the same three plans' titles                     |
 * | every internal link     | `intera_page_url()`                              |
 *
 * The Free card keeps its own `_intera_plan_cta_url` here — the plan's meta
 * target is the request form, which is why `front-page.php` overrides it with
 * the pricing page and this template does not override anything.
 *
 * The rest is the handoff's fixed copy, per recon §5 ("Dynamic slots: none"):
 * the section headings, the six comparison rows, the Early Adopter band and the
 * four pre-signing questions.
 *
 * PORT.md §1: nothing carrying `.itr-hl`, `.itr-panel` or another hover class
 * gets `background`, `border`, `border-color`, `box-shadow` or `transition`
 * inline — the resting values are the CSS defaults for `--itr-bg` / `--itr-edge`
 * / `--itr-shadow`. The comparison scroller carries `.itr-scroll-x`, so its
 * `overflow` lives in the stylesheet (clipped at desktop, scrolling under
 * 760px) while its surface stays inline. Everything else is the export's own
 * inline style, verbatim.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
	the_post();
}

$intera_request_url = (string) intera_page_url( 'contact-request' );
$intera_faq_url     = (string) intera_page_url( 'faq' );

// The three cards are the `plan` post type, ordered by the Order field.
$intera_plans = get_posts(
	array(
		'post_type'        => 'plan',
		'post_status'      => 'publish',
		'numberposts'      => -1,
		'orderby'          => 'menu_order title',
		'order'            => 'ASC',
		'suppress_filters' => false,
	)
);

$intera_table_title = __( 'What each plan includes', 'intera' );

/*
 * The comparison columns are the export's three plan names, replaced by the
 * plans' own titles whenever the ladder still has exactly three cards — so
 * renaming a plan renames its column, and the accent stays on whichever plan
 * carries `_intera_plan_featured`.
 */
$intera_columns = array(
	array(
		'label'    => __( 'Free', 'intera' ),
		'featured' => false,
	),
	array(
		'label'    => __( 'Early Adopter', 'intera' ),
		'featured' => true,
	),
	array(
		'label'    => __( 'Commercial', 'intera' ),
		'featured' => false,
	),
);

if ( 3 === count( $intera_plans ) ) {
	$intera_columns = array();

	foreach ( $intera_plans as $intera_plan ) {
		$intera_columns[] = array(
			'label'    => get_the_title( $intera_plan ),
			'featured' => (bool) get_post_meta( $intera_plan->ID, '_intera_plan_featured', true ),
		);
	}
}

// Capability, then one figure per column, in the same order as the cards.
$intera_rows = array(
	array( __( 'Roles', 'intera' ), '3', __( 'unlimited', 'intera' ), __( 'unlimited', 'intera' ) ),
	array( __( 'Users', 'intera' ), '10', '25', __( 'by agreement', 'intera' ) ),
	array( __( 'Integrations', 'intera' ), '3', '5', __( 'by agreement', 'intera' ) ),
	array( __( 'History', 'intera' ), __( '30 days', 'intera' ), __( 'unlimited', 'intera' ), __( 'unlimited', 'intera' ) ),
	array( __( 'Onboarding', 'intera' ), __( 'self-serve', 'intera' ), __( 'custom', 'intera' ), __( 'from €4,500', 'intera' ) ),
	array( __( 'Market package', 'intera' ), '—', __( '1 included', 'intera' ), __( 'quoted', 'intera' ) ),
);

$intera_last_row = count( $intera_rows ) - 1;

// The three figures beside the Early Adopter offer.
$intera_stats = array(
	array( '12', __( 'months free', 'intera' ) ),
	array( '1', __( 'market package included', 'intera' ) ),
	array( '25', __( 'users', 'intera' ) ),
);

// The four questions that come up before signing.
$intera_questions = array(
	array(
		__( 'Is the free plan time-limited?', 'intera' ),
		__( 'No. It is limited by roles, users, integrations and 30 days of history — not by a trial clock.', 'intera' ),
	),
	array(
		__( 'What happens after the 12 free months?', 'intera' ),
		__( 'You move to a commercial plan, or you stop. Nothing you configured is held hostage.', 'intera' ),
	),
	array(
		__( 'Do we need the Method to start?', 'intera' ),
		__( 'No. The Method is for teams that want the first roles built together, on site, in a few intensive days.', 'intera' ),
	),
	array(
		__( 'Where does INTERA run?', 'intera' ),
		__( 'Local installation is available from the free plan onwards. Access to source systems stays read-only.', 'intera' ),
	),
);
?>

<section data-screen-label="Pricing header" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(35px, 7vw, 60px) clamp(20px, 5vw, 24px) clamp(23px, 7vw, 40px)">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 640px; margin-top: 22px">
			<h1 style="font-size: clamp(30px, 3.2vw, 38px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.14; color: var(--ink-900); text-wrap: pretty"><?php the_title(); ?></h1>
			<?php if ( '' !== trim( (string) get_the_content() ) ) : ?>
				<div class="intera-prose" style="--itr-prose-max: 640px; margin-top: 18px"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( $intera_plans ) : ?>
<section data-screen-label="Plans" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: 0 24px 72px">
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 20px; align-items: stretch">
			<?php
			foreach ( $intera_plans as $intera_plan ) {
				get_template_part(
					'template-parts/partials/plan-card',
					null,
					array( 'post' => $intera_plan )
				);
			}
			?>
		</div>
	</div>
</section>
<?php endif; ?>

<section data-screen-label="Comparison" style="background: var(--surface-sunken); border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(42px, 7vw, 72px) clamp(20px, 5vw, 24px)">
		<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( $intera_table_title ); ?></h2>
		<div class="itr-scroll-x" tabindex="0" role="region" aria-label="<?php echo esc_attr( $intera_table_title ); ?>" style="margin-top: 24px; background: var(--white); border: 1px solid var(--border-card); border-radius: var(--radius-card)">
			<table style="width: 100%; border-collapse: collapse">
				<thead>
					<tr style="background: var(--surface-sunken)">
						<th scope="col" style="text-align: left; padding: 12px 20px; font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500); border-bottom: 1px solid var(--border-hairline)"><?php esc_html_e( 'Capability', 'intera' ); ?></th>
						<?php foreach ( $intera_columns as $intera_column ) : ?>
							<th scope="col" style="text-align: right; padding: 12px 20px; font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: <?php echo esc_attr( $intera_column['featured'] ? 'var(--blue-600)' : 'var(--ink-500)' ); ?>; border-bottom: 1px solid var(--border-hairline)"><?php echo esc_html( $intera_column['label'] ); ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody style="font-family: var(--font-mono); font-size: var(--text-sm); color: var(--ink-800)">
					<?php
					foreach ( $intera_rows as $intera_index => $intera_row ) :
						// The last row sits on the card's own edge, so it drops the rule.
						$intera_rule = $intera_index === $intera_last_row ? '' : '; border-bottom: 1px solid var(--border-hairline)';
						?>
						<tr>
							<td style="padding: 13px 20px; font-family: var(--font-sans); font-size: var(--text-md); color: var(--ink-800)<?php echo esc_attr( $intera_rule ); ?>"><?php echo esc_html( $intera_row[0] ); ?></td>
							<?php for ( $intera_col = 1; $intera_col <= 3; $intera_col++ ) : ?>
								<td style="padding: 13px 20px; text-align: right<?php echo esc_attr( $intera_rule ); ?>"><?php echo esc_html( isset( $intera_row[ $intera_col ] ) ? $intera_row[ $intera_col ] : '' ); ?></td>
							<?php endfor; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<p style="font-size: var(--text-sm); color: var(--ink-500); margin-top: 14px"><?php esc_html_e( 'Prices exclude VAT. Custom integrations and additional market packages are quoted separately.', 'intera' ); ?></p>
	</div>
</section>

<section data-screen-label="Early Adopter" style="position: relative; overflow: hidden; background: var(--ink-900)">
	<div aria-hidden="true" style="position: absolute; left: 22%; top: 40%; width: 900px; height: 900px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-blue-dark) 0%, transparent 66%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(42px, 7vw, 72px) clamp(20px, 5vw, 24px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 40px; align-items: center">
		<div>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-200); margin-bottom: 14px"><?php esc_html_e( 'Early Adopter offer', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--white)"><?php esc_html_e( 'Help shape INTERA around a real operation', 'intera' ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: rgba(255,255,255,.72); margin-top: 16px; max-width: 520px"><?php esc_html_e( 'Twelve months free, custom onboarding and direct contact with the team. We ask for one real business case and your feedback.', 'intera' ); ?></p>
			<?php if ( '' !== $intera_request_url ) : ?>
				<div style="margin-top: 26px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'   => __( 'I have a problem INTERA could solve', 'intera' ),
							'href'    => $intera_request_url,
							'variant' => 'inverse',
							'size'    => 'lg',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(140px, 100%), 1fr)); gap: 12px">
			<?php foreach ( $intera_stats as $intera_stat ) : ?>
				<div class="itr-panel" style="border-radius: var(--radius-card); padding: 18px">
					<div style="font-family: var(--font-mono); font-size: var(--text-2xl); color: var(--white)"><?php echo esc_html( $intera_stat[0] ); ?></div>
					<div style="font-size: var(--text-xs); color: rgba(255,255,255,.6); margin-top: 6px"><?php echo esc_html( $intera_stat[1] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section data-screen-label="Pricing questions" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(42px, 7vw, 72px) clamp(20px, 5vw, 24px)">
		<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)"><?php esc_html_e( 'Questions that come up before signing', 'intera' ); ?></h2>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px; margin-top: 26px">
			<?php foreach ( $intera_questions as $intera_question ) : ?>
				<div>
					<div style="font-size: var(--text-md); font-weight: 600; color: var(--ink-900)"><?php echo esc_html( $intera_question[0] ); ?></div>
					<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 8px"><?php echo esc_html( $intera_question[1] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( '' !== $intera_faq_url ) : ?>
			<div style="margin-top: 30px">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'      => __( 'Read the full FAQ', 'intera' ),
						'href'       => $intera_faq_url,
						'variant'    => 'secondary',
						'icon_right' => 'arrow-right',
					)
				);
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
