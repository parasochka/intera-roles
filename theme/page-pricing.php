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
 * | the plan cards          | the `plan` post type, via `partials/plan-card`   |
 * | each card's price + CTA | `_intera_plan_*` meta and the plan's own content |
 * | the comparison columns  | the same plans, in the same order                |
 * | the comparison rows     | `_intera_plan_capabilities` on those plans       |
 * | the Early Adopter tiles | the featured plan's own capability figures       |
 * | every internal link     | `intera_page_url()`                              |
 *
 * The Free card keeps its own `_intera_plan_cta_url` here — the plan's meta
 * target is the request form, which is why `front-page.php` overrides it with
 * the pricing page and this template does not override anything.
 *
 * The comparison table is built from the plans, not from the export: the columns
 * are the published plans in menu order and the rows are the union of their
 * capability labels, in the order the first plan declares them. A plan that does
 * not carry a capability gets the table's em dash in that cell, and a fourth
 * plan adds a fourth column instead of running past a three-column table. With
 * no plans, or no capabilities on any of them, the whole section is skipped —
 * an empty table says less than nothing.
 *
 * The three tiles beside the Early Adopter offer read the featured plan's own
 * figures for the capabilities they name, so the band cannot drift from the
 * plan it describes. The tile captions stay template copy; a tile whose
 * capability the featured plan does not declare is dropped.
 *
 * The rest is the handoff's fixed copy, per recon §5 ("Dynamic slots: none"):
 * the section headings, the Early Adopter band and the four pre-signing
 * questions.
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
		'orderby'          => 'menu_order date',
		'order'            => 'ASC',
		'suppress_filters' => false,
	)
);

$intera_table_title = intera_copy( 'pricing_page__what_each_plan_includes' );

/*
 * One pass over the plans builds both the columns and the rows: the column
 * keeps the plan's title, its accent and its own figures keyed by capability;
 * `$intera_rows` collects the labels in first-seen order, which is the order
 * the first plan declares them in. The featured plan's figures are kept aside
 * for the Early Adopter band.
 */
$intera_columns          = array();
$intera_rows             = array();
$intera_featured_figures = array();

foreach ( $intera_plans as $intera_plan ) {
	$intera_featured = (bool) get_post_meta( $intera_plan->ID, '_intera_plan_featured', true );
	$intera_figures  = array();

	$intera_capabilities = function_exists( 'intera_plan_capabilities_get' )
		? intera_plan_capabilities_get( $intera_plan->ID )
		: array();

	foreach ( $intera_capabilities as $intera_capability ) {
		if ( ! isset( $intera_rows[ $intera_capability['key'] ] ) ) {
			$intera_rows[ $intera_capability['key'] ] = $intera_capability['label'];
		}

		$intera_figures[ $intera_capability['key'] ] = $intera_capability['value'];
	}

	$intera_columns[] = array(
		'label'    => get_the_title( $intera_plan ),
		'featured' => $intera_featured,
		'figures'  => $intera_figures,
	);

	if ( $intera_featured && ! $intera_featured_figures ) {
		$intera_featured_figures = $intera_figures;
	}
}

$intera_row_keys = array_keys( $intera_rows );
$intera_last_row = count( $intera_row_keys ) - 1;

// A plan that does not carry a capability reads as a dash, not as a blank cell.
$intera_no_figure = _x( '—', 'a capability the plan does not include', 'intera' );

/*
 * The three tiles beside the Early Adopter offer: each names the capability it
 * shows and carries its own caption. The figure is the featured plan's, so the
 * band restates the plan instead of repeating it.
 */
$intera_tiles = array();

foreach ( array(
	intera_copy( 'pricing_page__roles' )          => intera_copy( 'pricing_page__roles_2' ),
	intera_copy( 'pricing_page__users' )          => intera_copy( 'pricing_page__users_2' ),
	intera_copy( 'pricing_page__market_package' ) => intera_copy( 'pricing_page__market_package_2' ),
) as $intera_tile_capability => $intera_tile_label ) {
	$intera_tile_key = function_exists( 'intera_plan_capability_key' )
		? intera_plan_capability_key( $intera_tile_capability )
		: '';

	if ( '' === $intera_tile_key || ! isset( $intera_featured_figures[ $intera_tile_key ] ) || '' === $intera_featured_figures[ $intera_tile_key ] ) {
		continue;
	}

	$intera_tiles[] = array( $intera_featured_figures[ $intera_tile_key ], $intera_tile_label );
}

// The four questions that come up before signing.
$intera_questions = array(
	array(
		intera_copy( 'pricing_page__is_the_free_plan_time_limited' ),
		intera_copy( 'pricing_page__no_it_is_limited_by_roles' ),
	),
	array(
		intera_copy( 'pricing_page__what_happens_after_the_12_free' ),
		intera_copy( 'pricing_page__you_move_to_a_commercial_plan' ),
	),
	array(
		intera_copy( 'pricing_page__do_we_need_the_method_to' ),
		intera_copy( 'pricing_page__no_the_method_is_for_teams' ),
	),
	array(
		intera_copy( 'pricing_page__where_does_intera_run' ),
		intera_copy( 'pricing_page__cloud_installation_on_the_free_plan' ),
	),
);
?>

<section data-screen-label="Pricing header" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(35px, 7vw, 60px) clamp(20px, 5vw, 24px) clamp(23px, 7vw, 40px)">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 640px; margin-top: 22px">
			<h1 style="font-size: clamp(30px, 3.2vw, 38px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.14; color: var(--ink-900); text-wrap: pretty"><?php
					/*
					 * The design's headline, not the page title. The two are
					 * different jobs: the title is what the breadcrumb and the
					 * menu say, and it has to stay short, while this is a full
					 * sentence. Falls back to the title when the field is empty.
					 */
					$intera_headline = trim( (string) intera_copy( 'pricing_headline' ) );
					echo esc_html( '' !== $intera_headline ? $intera_headline : get_the_title() );
					?></h1>
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

<?php if ( $intera_columns && $intera_row_keys ) : ?>
<section data-screen-label="Comparison" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 84%; top: 22%; width: 860px; height: 860px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-teal) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(42px, 7vw, 72px) clamp(20px, 5vw, 24px)">
		<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( $intera_table_title ); ?></h2>
		<div class="itr-scroll-x" tabindex="0" role="region" aria-label="<?php echo esc_attr( $intera_table_title ); ?>" style="margin-top: 24px; background: var(--white); border: 1px solid var(--border-card); border-radius: var(--radius-card)">
			<table style="width: 100%; border-collapse: collapse">
				<thead>
					<tr style="background: var(--surface-sunken)">
						<th scope="col" style="text-align: left; padding: 12px 20px; font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500); border-bottom: 1px solid var(--border-hairline)"><?php echo esc_html( intera_copy( 'pricing_comparison__capability' ) ); ?></th>
						<?php foreach ( $intera_columns as $intera_column ) : ?>
							<th scope="col" style="text-align: right; padding: 12px 20px; font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: <?php echo esc_attr( $intera_column['featured'] ? 'var(--blue-600)' : 'var(--ink-500)' ); ?>; border-bottom: 1px solid var(--border-hairline)"><?php echo esc_html( $intera_column['label'] ); ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody style="font-family: var(--font-mono); font-size: var(--text-sm); color: var(--ink-800)">
					<?php
					foreach ( $intera_row_keys as $intera_index => $intera_key ) :
						// The last row sits on the card's own edge, so it drops the rule.
						$intera_rule = $intera_index === $intera_last_row ? '' : '; border-bottom: 1px solid var(--border-hairline)';
						?>
						<tr>
							<td style="padding: 13px 20px; font-family: var(--font-sans); font-size: var(--text-md); color: var(--ink-800)<?php echo esc_attr( $intera_rule ); ?>"><?php echo esc_html( $intera_rows[ $intera_key ] ); ?></td>
							<?php
							foreach ( $intera_columns as $intera_column ) :
								$intera_figure = isset( $intera_column['figures'][ $intera_key ] ) && '' !== $intera_column['figures'][ $intera_key ]
									? $intera_column['figures'][ $intera_key ]
									: $intera_no_figure;
								?>
								<td style="padding: 13px 20px; text-align: right<?php echo esc_attr( $intera_rule ); ?>"><?php echo esc_html( $intera_figure ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<p style="font-size: var(--text-sm); color: var(--ink-500); margin-top: 14px"><?php echo esc_html( intera_copy( 'pricing_comparison__prices_exclude_vat_custom_integrations_and' ) ); ?></p>
	</div>
</section>
<?php endif; ?>

<section data-screen-label="Early Adopter" style="position: relative; overflow: hidden; background: var(--ink-900)">
	<div aria-hidden="true" style="position: absolute; left: 22%; top: 40%; width: 900px; height: 900px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-blue-dark) 0%, transparent 66%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(42px, 7vw, 72px) clamp(20px, 5vw, 24px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 40px; align-items: center">
		<div>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-200); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'pricing_early_adopter__early_adopter_offer' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--white)"><?php echo esc_html( intera_copy( 'pricing_early_adopter__help_shape_intera_around_a_real' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: rgba(255,255,255,.72); margin-top: 16px; max-width: 520px"><?php echo esc_html( intera_copy( 'pricing_early_adopter__twelve_months_free_custom_onboarding_and' ) ); ?></p>
			<?php if ( '' !== $intera_request_url ) : ?>
				<div style="margin-top: 26px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'   => intera_copy( 'pricing_early_adopter__i_have_a_problem_intera_could' ),
							'href'    => $intera_request_url,
							'variant' => 'inverse',
							'size'    => 'lg',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $intera_tiles ) : ?>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(140px, 100%), 1fr)); gap: 12px">
				<?php foreach ( $intera_tiles as $intera_tile ) : ?>
					<div class="itr-panel itr-figure-tile" style="border-radius: var(--radius-card); padding: 18px">
						<div class="itr-figure" style="color: var(--white)"><?php echo esc_html( $intera_tile[0] ); ?></div>
						<div style="font-size: var(--text-xs); color: rgba(255,255,255,.6); margin-top: 6px"><?php echo esc_html( $intera_tile[1] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<section data-screen-label="Pricing questions" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(42px, 7vw, 72px) clamp(20px, 5vw, 24px)">
		<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'pricing_pricing_questions__questions_that_come_up_before_signing' ) ); ?></h2>
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
						'label'      => intera_copy( 'pricing_pricing_questions__read_the_full_faq' ),
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
