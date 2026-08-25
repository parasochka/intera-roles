<?php
/**
 * Template Name: Product
 *
 * Ported from `_design/02-product.dc.html` (recon §4). Eight sections in the
 * export's order: product header, "What INTERA watches", Pattern Studio,
 * Integrations, Roles, Market packages, Method, CTA.
 *
 * What comes from WordPress:
 *
 * | slot                   | source                                          |
 * | ---------------------- | ----------------------------------------------- |
 * | breadcrumb             | `intera_breadcrumbs()` (auto: Home / <title>)    |
 * | header heading         | `the_title()`                                    |
 * | header lede            | `the_content()` — one paragraph, "Large" preset  |
 * | primary CTA            | `header_cta_label` / `header_cta_url`            |
 * | Pattern Studio frame   | the page's featured image                        |
 * | the five role cards    | the `role` post type, `variant => product`       |
 * | every internal link    | `intera_page_url()`                              |
 *
 * The rest of the copy is the handoff's own fixed marketing text, per recon §4
 * ("fully static marketing page"): the section headings and ledes, the signal
 * vocabulary, the eight DataSource rows, the two market packages and the five
 * Method steps.
 *
 * PORT.md §1: no `background`, `border`, `border-color`, `box-shadow` or
 * `transition` is written inline on an element carrying `.itr-hl`, `.itr-lift`,
 * `.itr-row`, `.itr-frame` or `.itr-card` — those arrive as `--itr-edge` /
 * `--itr-shadow` / `--itr-bg`, or come from the Card component's own surface.
 * Everything else stays inline exactly as the export writes it.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
	the_post();
}

// The early-access call to action: the Customizer wording and target, as in header.php.
$intera_cta_label = trim( (string) intera_option( 'header_cta_label' ) );
$intera_cta_url   = trim( (string) intera_option( 'header_cta_url' ) );

if ( '' === $intera_cta_url ) {
	$intera_cta_url = (string) intera_page_url( 'contact-request' );
}

if ( '' === $intera_cta_label ) {
	$intera_cta_label = intera_copy( 'product_page__get_early_access' );
}

$intera_has_cta  = ( '' !== $intera_cta_url );
$intera_docs_url = (string) intera_page_url( 'docs' );

// The Pattern Studio screenshot — `ship-3.webp` in the export, the page's featured image here.
$intera_product_shot = (int) get_post_thumbnail_id();

// The chain captions, `renderVals().chainCaptions` in the export.
$intera_chain_captions = array(
	'event'          => intera_copy( 'product_page__something_important_changed' ),
	'reconciliation' => intera_copy( 'product_page__things_that_should_agree_don_t' ),
	'incident'       => intera_copy( 'product_page__something_requires_attention_and_action' ),
	'pattern'        => intera_copy( 'product_page__understand_what_keeps_happening_and_under' ),
);
?>

<section data-screen-label="Product header" style="position: relative; overflow: hidden; background: var(--ink-950)">
	<?php
	/*
	 * The home page's hero ground, verbatim: the 1160px column rules, the two
	 * ambient washes and the corner lock-up mark. One band opens the site and
	 * this one opens the product, so they are the same band — see the Hero
	 * section of front-page.php, which this is quoted from.
	 */
	?>
	<div aria-hidden="true" style="position: absolute; inset: 0; pointer-events: none; overflow: hidden">
		<div style="position: absolute; inset: 0; max-width: 1160px; margin: 0 auto">
			<div style="position: absolute; top: 0; bottom: 0; left: 0; width: 1px; background: rgba(255,255,255,.07)"></div>
			<div style="position: absolute; top: 0; bottom: 0; left: 25%; width: 1px; background: rgba(255,255,255,.07)"></div>
			<div style="position: absolute; top: 0; bottom: 0; left: 50%; width: 1px; background: rgba(255,255,255,.07)"></div>
			<div style="position: absolute; top: 0; bottom: 0; left: 75%; width: 1px; background: rgba(255,255,255,.07)"></div>
			<div style="position: absolute; top: 0; bottom: 0; left: 100%; width: 1px; background: rgba(255,255,255,.07)"></div>
		</div>
		<div style="position: absolute; right: -260px; top: 50%; transform: translateY(-50%); width: 1180px; height: 1180px; background: radial-gradient(circle, var(--wash-blue-dark) 0%, transparent 62%)"></div>
		<div style="position: absolute; right: -60px; bottom: -360px; width: 820px; height: 820px; background: radial-gradient(circle, var(--wash-teal-dark) 0%, transparent 62%)"></div>
		<svg viewBox="0 0 40 40" width="720" height="720" fill="none" style="position: absolute; right: -180px; top: -160px">
			<rect x="5" y="5" width="22" height="22" rx="3.5" stroke="rgba(255,255,255,.075)" stroke-width="0.5"></rect>
			<rect x="13" y="13" width="14" height="14" fill="rgba(255,255,255,.028)"></rect>
			<rect x="13" y="13" width="22" height="22" rx="3.5" stroke="rgba(255,255,255,.075)" stroke-width="0.5"></rect>
		</svg>
	</div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(37px, 7vw, 64px) clamp(20px, 5vw, 24px) clamp(32px, 7vw, 56px)">
		<?php intera_breadcrumbs( array(), array( 'inverse' => true ) ); ?>
		<div class="itr-1col" style="--itr-cols: minmax(0, 1fr) minmax(0, 420px); margin-top: 24px; gap: 56px; align-items: start">
			<div>
				<h1 style="font-size: clamp(30px, 3.2vw, 38px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.14; color: var(--white); max-width: 560px; text-wrap: pretty"><?php
					/*
					 * The design's headline, not the page title. The two are
					 * different jobs: the title is what the breadcrumb and the
					 * menu say, and it has to stay short, while this is a full
					 * sentence. Falls back to the title when the field is empty.
					 */
					$intera_headline = trim( (string) intera_copy( 'product_headline' ) );
					echo esc_html( '' !== $intera_headline ? $intera_headline : get_the_title() );
					?></h1>
				<?php if ( '' !== trim( (string) get_the_content() ) ) : ?>
					<div class="intera-prose intera-prose--inverse" style="--itr-prose-max: 520px; margin-top: 20px"><?php the_content(); ?></div>
				<?php endif; ?>
				<div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 28px">
					<?php
					if ( $intera_has_cta ) {
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label'   => $intera_cta_label,
								'href'    => $intera_cta_url,
								'size'    => 'lg',
								'variant' => 'inverse',
							)
						);
					}

					if ( '' !== $intera_docs_url ) {
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label'      => intera_copy( 'product_product_header__read_the_docs' ),
								'href'       => $intera_docs_url,
								'size'       => 'lg',
								'variant'    => 'outlineInverse',
								'icon_right' => 'arrow-right',
							)
						);
					}
					?>
				</div>
			</div>
			<?php
			// The "Operations Oversight" preview panel — the header's visual, and the
			// one card on the page a pointer meets first. `.itr-card` paints the white
			// surface, the --border-card edge, the 8px radius and --shadow-xs, which is
			// exactly what the export writes inline; only overflow stays inline.
			// `.itr-lift` gives it the 5px rise every other card on the site answers a
			// pointer with, and `.itr-lift-inverse` swaps the hover cast for one that
			// reads against the --ink-950 band instead of disappearing into it.
			ob_start();
			?>
			<div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--border-hairline); background: var(--surface-sunken)">
				<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-500)"><?php echo esc_html( intera_copy( 'product_product_header__operations_oversight' ) ); ?></span>
				<span style="display: flex; align-items: center; gap: 6px; font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted)">
					<span style="position: relative; display: inline-flex; width: 8px; height: 8px; flex: none"><span class="itr-live-halo" aria-hidden="true" style="position: absolute; inset: 0; border-radius: 999px; background: var(--green-500)"></span><span class="itr-live-dot" aria-hidden="true" style="position: relative; width: 8px; height: 8px; border-radius: 999px; background: var(--green-500)"></span></span>
					<?php echo esc_html( intera_copy( 'product_product_header__live' ) ); ?>
				</span>
			</div>
			<div style="padding: 18px 16px 20px">
				<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px">
					<?php
					get_template_part(
						'template-parts/components/metric-tile',
						null,
						array(
							'label'     => intera_copy( 'product_product_header__open_incidents' ),
							'value'     => '7',
							'delta'     => '+2',
							'direction' => 'up',
							'tone'      => 'warning',
						)
					);

					get_template_part(
						'template-parts/components/metric-tile',
						null,
						array(
							'label'     => intera_copy( 'product_product_header__unreconciled' ),
							'value'     => '4,812',
							'delta'     => '-311',
							'direction' => 'down',
							'tone'      => 'ok',
						)
					);
					?>
				</div>
				<div style="margin-top: 18px; display: flex; flex-direction: column; gap: 10px">
					<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php echo esc_html( intera_copy( 'product_product_header__latest' ) ); ?></div>
					<?php
					$intera_latest = array(
						array(
							'type' => 'reconciliation',
							'text' => intera_copy( 'product_product_header__billing_vs_erp_118_invoices_differ' ),
							'rule' => true,
						),
						array(
							'type' => 'incident',
							'text' => intera_copy( 'product_product_header__no_data_received_from_wms_since' ),
							'rule' => true,
						),
						array(
							'type' => 'pattern',
							'text' => intera_copy( 'product_product_header__readiness_drops_every_monday_after_the' ),
							'rule' => false,
						),
					);

					foreach ( $intera_latest as $intera_signal ) :
						?>
						<div style="display: flex; align-items: flex-start; gap: 10px<?php echo $intera_signal['rule'] ? '; padding-bottom: 10px; border-bottom: 1px solid var(--border-hairline)' : ''; ?>">
							<?php
							get_template_part(
								'template-parts/components/signal-badge',
								null,
								array( 'type' => $intera_signal['type'] )
							);
							?>
							<span style="font-size: var(--text-md); color: var(--ink-700); line-height: 1.45"><?php echo esc_html( $intera_signal['text'] ); ?></span>
						</div>
						<?php
					endforeach;
					?>
				</div>
			</div>
			<?php
			$intera_panel = ob_get_clean();

			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'content' => $intera_panel,
					'padding' => 'none',
					'class'   => 'itr-lift itr-lift-inverse',
					'style'   => 'overflow: hidden',
				)
			);
			?>
		</div>
	</div>
</section>

<section data-screen-label="What INTERA watches" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 78%; top: 22%; width: 900px; height: 900px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-blue) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(49px, 7vw, 84px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 36px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'product_what_intera_watches__the_chain' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'product_what_intera_watches__event_reconciliation_incident_pattern' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php echo esc_html( intera_copy( 'product_what_intera_watches__four_object_types_in_a_fixed' ) ); ?></p>
		</div>
		<?php
		get_template_part(
			'template-parts/components/signal-chain',
			null,
			array( 'captions' => $intera_chain_captions )
		);
		?>
		<div class="itr-1col" style="--itr-cols: repeat(2, minmax(0, 1fr)); gap: 20px; margin-top: 20px">
			<?php
			$intera_objects = array(
				array(
					'type'  => 'event',
					'title' => intera_copy( 'product_what_intera_watches__a_watched_metric_moved' ),
					'body'  => intera_copy( 'product_what_intera_watches__thresholds_trends_and_data_status_on' ),
					'mono'  => intera_copy( 'product_what_intera_watches__metrics_trend_metrics_threshold' ),
				),
				array(
					'type'  => 'reconciliation',
					'title' => intera_copy( 'product_what_intera_watches__two_systems_disagree' ),
					'body'  => intera_copy( 'product_what_intera_watches__continuous_comparison_between_systems_periods_an' ),
					'mono'  => intera_copy( 'product_what_intera_watches__usage_billing_orders_invoices' ),
				),
				array(
					'type'  => 'incident',
					'title' => intera_copy( 'product_what_intera_watches__someone_has_to_act' ),
					'body'  => intera_copy( 'product_what_intera_watches__a_tracked_item_with_an_owner' ),
					'mono'  => intera_copy( 'product_what_intera_watches__p0_impact_in_2_days_owner' ),
				),
				array(
					'type'  => 'pattern',
					'title' => intera_copy( 'product_what_intera_watches__it_keeps_happening' ),
					'body'  => intera_copy( 'product_what_intera_watches__recurring_combinations_of_conditions_behind_inci' ),
					'mono'  => intera_copy( 'product_what_intera_watches__4th_occurrence_same_precondition' ),
				),
			);

			foreach ( $intera_objects as $intera_object ) :
				ob_start();

				get_template_part(
					'template-parts/components/signal-badge',
					null,
					array( 'type' => $intera_object['type'] )
				);
				?>
				<div style="font-size: var(--text-md); font-weight: 600; margin-top: 12px"><?php echo esc_html( $intera_object['title'] ); ?></div>
				<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 8px"><?php echo esc_html( $intera_object['body'] ); ?></p>
				<div style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted); margin-top: 12px"><?php echo esc_html( $intera_object['mono'] ); ?></div>
				<?php
				$intera_object_body = ob_get_clean();

				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content'     => $intera_object_body,
						'accent'      => 'var(--signal-' . $intera_object['type'] . ')',
						'accent_line' => 'var(--signal-' . $intera_object['type'] . '-line)',
						'class'       => 'itr-lift',
					)
				);
			endforeach;
			?>
		</div>
	</div>
</section>

<section data-screen-label="Pattern Studio" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(340px, 100%), 1fr)); gap: 48px; align-items: center">
		<div>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'product_pattern_studio__pattern_studio' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'product_pattern_studio__understand_what_keeps_happening_and_under' ) ); ?></h2>
			<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 18px; max-width: 520px"><?php echo esc_html( intera_copy( 'product_pattern_studio__look_back_at_what_preceded_an' ) ); ?></p>
			<div style="display: flex; flex-direction: column; gap: 8px; margin-top: 24px; max-width: 520px">
				<div style="display: flex; gap: 12px; align-items: center; border: 1px solid var(--border-hairline); border-radius: var(--radius-md); padding: 12px 14px; background: var(--surface-sunken)">
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--violet-600)"><?php echo esc_html( intera_copy( 'product_pattern_studio__if' ) ); ?></span>
					<span style="font-size: var(--text-sm); color: var(--ink-800)"><?php echo esc_html( intera_copy( 'product_pattern_studio__a_spare_part_delivery_slips_more' ) ); ?></span>
				</div>
				<div style="display: flex; gap: 12px; align-items: center; border: 1px solid var(--border-hairline); border-radius: var(--radius-md); padding: 12px 14px; background: var(--surface-sunken)">
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--violet-600)"><?php echo esc_html( intera_copy( 'product_pattern_studio__and' ) ); ?></span>
					<span style="font-size: var(--text-sm); color: var(--ink-800)"><?php echo esc_html( intera_copy( 'product_pattern_studio__the_vessel_already_carries_overdue_critical' ) ); ?></span>
				</div>
				<div class="itr-row" style="--itr-edge: var(--border-default); display: flex; gap: 12px; align-items: center; border-radius: var(--radius-md); padding: 12px 14px">
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--violet-600)"><?php echo esc_html( intera_copy( 'product_pattern_studio__then' ) ); ?></span>
					<span style="font-size: var(--text-sm); color: var(--ink-900); font-weight: 500"><?php echo esc_html( intera_copy( 'product_pattern_studio__readiness_drops_below_plan_within_3' ) ); ?></span>
				</div>
			</div>
			<?php if ( '' !== $intera_docs_url ) : ?>
				<div style="margin-top: 26px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'      => intera_copy( 'product_pattern_studio__how_patterns_are_defined' ),
							'href'       => $intera_docs_url,
							'variant'    => 'secondary',
							'icon_right' => 'arrow-right',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		get_template_part(
			'template-parts/partials/screenshot-frame',
			null,
			array(
				'attachment' => $intera_product_shot,
				'caption'    => intera_copy( 'product_pattern_studio__role_view_readiness_and_upcoming_dates' ),
				'height'     => '420px',
				'alt'        => intera_copy( 'product_pattern_studio__intera_role_view_with_readiness_metrics' ),
			)
		);
		?>
	</div>
</section>

<section id="integrations" data-screen-label="Integrations" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 20%; top: 80%; width: 860px; height: 860px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-teal) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(49px, 7vw, 84px) clamp(20px, 5vw, 24px)">
		<div id="it" style="max-width: 720px; margin-bottom: 36px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'product_integrations__integrations_and_datasources' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'product_integrations__read_only_connections_to_the_systems' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php echo esc_html( intera_copy( 'product_integrations__a_datasource_states_which_system_holds' ) ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(200px, 100%), 1fr)); gap: 10px">
			<?php
			$intera_sources = array(
				array(
					'icon'  => 'database',
					'label' => intera_copy( 'product_integrations__erp' ),
					'note'  => intera_copy( 'product_integrations__sap_oracle_bc' ),
				),
				array(
					'icon'  => 'contact',
					'label' => intera_copy( 'product_integrations__crm' ),
					'note'  => intera_copy( 'product_integrations__accounts' ),
				),
				array(
					'icon'  => 'receipt',
					'label' => intera_copy( 'product_integrations__billing' ),
					'note'  => intera_copy( 'product_integrations__invoices_rating' ),
				),
				array(
					'icon'  => 'activity',
					'label' => intera_copy( 'product_integrations__mediation' ),
					'note'  => intera_copy( 'product_integrations__usage_cdr' ),
				),
				array(
					'icon'  => 'table-2',
					'label' => intera_copy( 'product_integrations__excel' ),
					'note'  => intera_copy( 'product_integrations__exports_checks' ),
				),
				array(
					'icon'  => 'terminal',
					'label' => intera_copy( 'product_integrations__internal_apis' ),
					'note'  => intera_copy( 'product_integrations__custom' ),
				),
				array(
					'icon'  => 'landmark',
					'label' => intera_copy( 'product_integrations__banking' ),
					'note'  => intera_copy( 'product_integrations__revolut' ),
				),
				array(
					'icon'  => 'mail',
					'label' => intera_copy( 'product_integrations__manual_inputs' ),
					'note'  => intera_copy( 'product_integrations__forms_mail' ),
				),
			);

			foreach ( $intera_sources as $intera_source ) :
				?>
				<div class="itr-row" style="display: flex; align-items: center; gap: 12px; border-radius: var(--radius-md); padding: 14px 16px">
					<?php
					intera_icon(
						$intera_source['icon'],
						array(
							'size'  => 16,
							'color' => 'var(--ink-500)',
						)
					);
					?>
					<span style="font-size: var(--text-md); color: var(--ink-800)"><?php echo esc_html( $intera_source['label'] ); ?></span>
					<span style="font-family: var(--font-mono); font-size: var(--text-2xs); color: var(--text-muted); margin-left: auto"><?php echo esc_html( $intera_source['note'] ); ?></span>
				</div>
				<?php
			endforeach;
			?>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px; margin-top: 28px">
			<?php
			$intera_ownership = array(
				array(
					'title'       => intera_copy( 'product_integrations__it_owns_access' ),
					'description' => intera_copy( 'product_integrations__which_system_which_credentials_which_refresh' ),
					'body'        => intera_copy( 'product_integrations__intera_states_the_requirement_concretely_the' ),
				),
				array(
					'title'       => intera_copy( 'product_integrations__business_owns_logic' ),
					'description' => intera_copy( 'product_integrations__metrics_events_incidents_reconciliations_pattern' ),
					'body'        => intera_copy( 'product_integrations__the_people_who_know_how_the' ),
				),
			);

			foreach ( $intera_ownership as $intera_owner ) :
				ob_start();

				get_template_part(
					'template-parts/components/card-header',
					null,
					array(
						'title'       => $intera_owner['title'],
						'description' => $intera_owner['description'],
					)
				);
				?>
				<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 12px"><?php echo esc_html( $intera_owner['body'] ); ?></p>
				<?php
				$intera_owner_body = ob_get_clean();

				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content' => $intera_owner_body,
						'class'   => 'itr-lift',
					)
				);
			endforeach;
			?>
		</div>
	</div>
</section>

<section id="roles" data-screen-label="Roles" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 36px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'product_roles__intera_roles' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'product_roles__a_module_built_around_a_responsibility' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php echo esc_html( intera_copy( 'product_roles__each_role_arrives_with_its_metrics' ) ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(270px, 100%), 1fr)); gap: 20px">
			<?php
			$intera_roles = new WP_Query(
				array(
					'post_type'              => 'role',
					'post_status'            => 'publish',
					'posts_per_page'         => -1,
					'orderby'                => array(
						'menu_order' => 'ASC',
						'date'       => 'ASC',
					),
					'no_found_rows'          => true,
					'ignore_sticky_posts'    => true,
					'update_post_term_cache' => false,
				)
			);

			while ( $intera_roles->have_posts() ) :
				$intera_roles->the_post();

				get_template_part(
					'template-parts/partials/role-card',
					null,
					array(
						'post'    => get_post(),
						'variant' => 'product',
					)
				);

			endwhile;

			wp_reset_postdata();
			?>
			<div style="display: flex; flex-direction: column; justify-content: center; gap: 16px; padding: 0 8px">
				<p style="font-size: var(--text-xl); font-weight: 600; line-height: 1.35; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'product_roles__different_responsibilities_one_operating_picture' ) ); ?></p>
				<p style="font-size: var(--text-sm); color: var(--ink-600); line-height: 1.6"><?php echo esc_html( intera_copy( 'product_roles__roles_combine_several_sources_and_apply' ) ); ?></p>
			</div>
		</div>
	</div>
</section>

<section id="packages" data-screen-label="Market packages" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 82%; top: 70%; width: 880px; height: 880px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-violet) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(49px, 7vw, 84px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 36px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'product_market_packages__market_packages' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'product_market_packages__industry_bundles_already_shaped_around_real' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php echo esc_html( intera_copy( 'product_market_packages__a_market_package_is_a_reusable' ) ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 20px">
			<?php
			$intera_packages = array(
				array(
					'icon'  => 'radio-tower',
					'title' => intera_copy( 'product_market_packages__telecommunications' ),
					'body'  => intera_copy( 'product_market_packages__where_usage_rating_billing_and_partner' ),
					'tags'  => array(
						intera_copy( 'product_market_packages__revenue_assurance_manager' ),
						intera_copy( 'product_market_packages__billing_operations_manager' ),
						intera_copy( 'product_market_packages__network_operations_manager' ),
						intera_copy( 'product_market_packages__partner_wholesale_manager' ),
						intera_copy( 'product_market_packages__commercial_director' ),
						intera_copy( 'product_market_packages__cfo_finance_controller' ),
						intera_copy( 'product_market_packages__coo_head_of_operations' ),
					),
					'link'  => intera_copy( 'product_market_packages__telecommunications_package' ),
				),
				array(
					'icon'  => 'ship',
					'title' => intera_copy( 'product_market_packages__shipmanagement' ),
					'body'  => intera_copy( 'product_market_packages__maintenance_backlog_defects_class_and_certificat' ),
					'tags'  => array(
						intera_copy( 'product_market_packages__technical_superintendent' ),
						intera_copy( 'product_market_packages__fleet_manager' ),
						intera_copy( 'product_market_packages__procurement_and_parts' ),
						intera_copy( 'product_market_packages__compliance_and_audit' ),
					),
					'link'  => intera_copy( 'product_market_packages__shipmanagement_package' ),
				),
			);

			foreach ( $intera_packages as $intera_package ) :
				ob_start();
				?>
				<?php
				/*
				 * The package name and its "Beta" chip. The export writes this
				 * line for a 560px card and never lets it wrap, which on a
				 * phone is 279px of icon, name and chip inside as little as
				 * 214px of card: at 375px the chip hangs over the card's own
				 * padding, at 360px it clears the edge entirely. `flex-wrap`
				 * drops the chip under the name instead, and `min-width: 0`
				 * lets the name itself break — a single unbreakable word
				 * ("Telecommunications" is 198px at --text-xl) is wider than a
				 * 320px card on its own, and a flex item will not go below its
				 * longest word until it is allowed to. It is on the name as
				 * well as on the group: each is a flex item, and the floor has
				 * to come off both before the `overflow-wrap: break-word` the
				 * phone breakpoint puts on <body> has a line short enough to
				 * act on.
				 */
				?>
				<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px">
					<div style="display: flex; align-items: center; gap: 10px; min-width: 0">
						<?php
						intera_icon(
							$intera_package['icon'],
							array(
								'size'  => 18,
								'color' => 'var(--ink-700)',
							)
						);
						?>
						<span style="font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; min-width: 0"><?php echo esc_html( $intera_package['title'] ); ?></span>
					</div>
					<?php
					get_template_part(
						'template-parts/components/badge',
						null,
						array(
							'text' => intera_copy( 'product_market_packages__beta' ),
							'tone' => 'info',
						)
					);
					?>
				</div>
				<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 12px"><?php echo esc_html( $intera_package['body'] ); ?></p>
				<div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-hairline)">
					<?php
					foreach ( $intera_package['tags'] as $intera_package_tag ) {
						get_template_part(
							'template-parts/components/tag',
							null,
							array( 'text' => $intera_package_tag )
						);
					}
					?>
				</div>
				<?php if ( '' !== $intera_docs_url ) : ?>
					<div style="margin-top: 20px">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label'      => $intera_package['link'],
								'href'       => $intera_docs_url,
								'variant'    => 'link',
								'icon_right' => 'arrow-right',
							)
						);
						?>
					</div>
				<?php endif; ?>
				<?php
				$intera_package_body = ob_get_clean();

				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content' => $intera_package_body,
						'padding' => 'loose',
						'class'   => 'itr-hl',
					)
				);
			endforeach;
			?>
		</div>
	</div>
</section>

<section id="method" data-screen-label="Method" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 48px; align-items: start">
			<div>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'product_method__intera_method' ) ); ?></div>
				<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'product_method__a_working_system_not_a_set' ) ); ?></h2>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 18px; max-width: 520px"><?php echo esc_html( intera_copy( 'product_method__the_method_is_a_hands_on' ) ); ?></p>
				<div style="display: flex; flex-direction: column; gap: 0; margin-top: 26px; border-top: 1px solid var(--border-hairline); max-width: 520px">
					<?php
					$intera_method_steps = array(
						intera_copy( 'product_method__map_the_real_data_flows_not' ),
						intera_copy( 'product_method__identify_blind_spots_and_the_checks' ),
						intera_copy( 'product_method__define_metrics_that_reflect_the_operation' ),
						intera_copy( 'product_method__connect_the_data_sources_with_it' ),
						intera_copy( 'product_method__build_the_first_roles_and_dashboards' ),
					);

					foreach ( $intera_method_steps as $intera_step_index => $intera_method_step ) :
						?>
						<div style="display: flex; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--border-hairline)"><span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted)"><?php echo esc_html( sprintf( '%02d', $intera_step_index + 1 ) ); ?></span><span style="font-size: var(--text-md); color: var(--ink-700)"><?php echo esc_html( $intera_method_step ); ?></span></div>
						<?php
					endforeach;
					?>
				</div>
			</div>
			<?php
			ob_start();
			?>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php echo esc_html( intera_copy( 'product_method__what_you_leave_with' ) ); ?></div>
			<div style="display: flex; flex-direction: column; gap: 10px; margin-top: 16px; font-size: var(--text-md); color: var(--ink-800); line-height: 1.5">
				<?php
				$intera_method_outcomes = array(
					intera_copy( 'product_method__a_working_intera_environment' ),
					intera_copy( 'product_method__connected_data_sources' ),
					intera_copy( 'product_method__defined_metrics_and_business_logic' ),
					intera_copy( 'product_method__operational_dashboards_in_use' ),
					intera_copy( 'product_method__visibility_into_issues_you_could_not' ),
				);

				foreach ( $intera_method_outcomes as $intera_method_outcome ) {
					echo '<span>' . esc_html( $intera_method_outcome ) . '</span>';
				}
				?>
			</div>
			<div style="margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--border-hairline)">
				<p style="font-size: var(--text-sm); color: var(--ink-600); line-height: 1.6"><?php echo esc_html( intera_copy( 'product_method__delivered_over_several_intensive_on_site' ) ); ?></p>
				<?php if ( $intera_has_cta ) : ?>
					<div style="margin-top: 16px">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label' => intera_copy( 'product_method__talk_to_us_about_the_method' ),
								'href'  => $intera_cta_url,
								'block' => true,
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>
			<?php
			$intera_method_body = ob_get_clean();

			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'content'     => $intera_method_body,
					'padding'     => 'loose',
					'accent'      => 'var(--blue-600)',
					'accent_line' => 'var(--blue-200)',
					'class'       => 'itr-hl',
				)
			);
			?>
		</div>
	</div>
</section>

<section data-screen-label="CTA" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: 0 24px 88px">
		<?php
		// The export writes this box as a bare div with the same surface the Card
		// component draws, plus a 3px --blue-600 top rule; `accent_line` keeps the
		// remaining three edges on --border-card, as the handoff has them.
		ob_start();
		?>
		<div>
			<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900); line-height: 1.3"><?php echo esc_html( intera_copy( 'product_cta__start_with_one_real_problem' ) ); ?></h2>
			<p style="font-size: var(--text-md); color: var(--ink-600); margin-top: 8px"><?php echo esc_html( intera_copy( 'product_cta__one_role_one_operational_problem_one' ) ); ?></p>
		</div>
		<?php
		if ( $intera_has_cta ) {
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => intera_copy( 'product_cta__bring_us_a_real_problem' ),
					'href'  => $intera_cta_url,
					'size'  => 'lg',
				)
			);
		}

		$intera_closing_body = ob_get_clean();

		get_template_part(
			'template-parts/components/card',
			null,
			array(
				'content'     => $intera_closing_body,
				'padding'     => 'none',
				'accent'      => 'var(--blue-600)',
				'accent_line' => 'var(--border-card)',
				'class'       => 'itr-hl',
				'style'       => 'padding: 32px 36px; display: flex; flex-wrap: wrap; gap: 24px; align-items: center; justify-content: space-between',
			)
		);
		?>
	</div>
</section>

<?php
get_footer();
