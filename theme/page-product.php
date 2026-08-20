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
	$intera_cta_label = __( 'Get Early Access', 'intera' );
}

$intera_has_cta  = ( '' !== $intera_cta_url );
$intera_docs_url = (string) intera_page_url( 'docs' );

// The Pattern Studio screenshot — `ship-3.webp` in the export, the page's featured image here.
$intera_product_shot = (int) get_post_thumbnail_id();

// The chain captions, `renderVals().chainCaptions` in the export.
$intera_chain_captions = array(
	'event'          => __( 'Something important changed.', 'intera' ),
	'reconciliation' => __( "Things that should agree — don't.", 'intera' ),
	'incident'       => __( 'Something requires attention and action.', 'intera' ),
	'pattern'        => __( 'Understand what keeps happening, and under which conditions.', 'intera' ),
);
?>

<section data-screen-label="Product header" style="background: var(--surface-page); border-bottom: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(37px, 7vw, 64px) clamp(20px, 5vw, 24px) clamp(32px, 7vw, 56px)">
		<?php intera_breadcrumbs(); ?>
		<div class="itr-1col" style="--itr-cols: minmax(0, 1fr) minmax(0, 420px); margin-top: 24px; gap: 56px; align-items: start">
			<div>
				<h1 style="font-size: clamp(30px, 3.2vw, 38px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.14; color: var(--ink-900); max-width: 560px; text-wrap: pretty"><?php the_title(); ?></h1>
				<?php if ( '' !== trim( (string) get_the_content() ) ) : ?>
					<div class="intera-prose" style="--itr-prose-max: 520px; margin-top: 20px"><?php the_content(); ?></div>
				<?php endif; ?>
				<div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 28px">
					<?php
					if ( $intera_has_cta ) {
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label' => $intera_cta_label,
								'href'  => $intera_cta_url,
								'size'  => 'lg',
							)
						);
					}

					if ( '' !== $intera_docs_url ) {
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label'      => __( 'Read the docs', 'intera' ),
								'href'       => $intera_docs_url,
								'size'       => 'lg',
								'variant'    => 'secondary',
								'icon_right' => 'arrow-right',
							)
						);
					}
					?>
				</div>
			</div>
			<?php
			// The "Operations Oversight" preview panel. `.itr-card` paints the white
			// surface, the --border-card edge, the 8px radius and --shadow-xs, which is
			// exactly what the export writes inline; only overflow stays inline.
			ob_start();
			?>
			<div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--border-hairline); background: var(--surface-sunken)">
				<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-500)"><?php esc_html_e( 'Operations Oversight', 'intera' ); ?></span>
				<span style="display: flex; align-items: center; gap: 6px; font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-400)">
					<span style="position: relative; display: inline-flex; width: 8px; height: 8px; flex: none"><span class="itr-live-halo" aria-hidden="true" style="position: absolute; inset: 0; border-radius: 999px; background: var(--green-500)"></span><span class="itr-live-dot" aria-hidden="true" style="position: relative; width: 8px; height: 8px; border-radius: 999px; background: var(--green-500)"></span></span>
					<?php esc_html_e( 'live', 'intera' ); ?>
				</span>
			</div>
			<div style="padding: 18px 16px 20px">
				<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px">
					<?php
					get_template_part(
						'template-parts/components/metric-tile',
						null,
						array(
							'label'     => __( 'Open incidents', 'intera' ),
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
							'label'     => __( 'Unreconciled', 'intera' ),
							'value'     => '4,812',
							'delta'     => '-311',
							'direction' => 'down',
							'tone'      => 'ok',
						)
					);
					?>
				</div>
				<div style="margin-top: 18px; display: flex; flex-direction: column; gap: 10px">
					<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php esc_html_e( 'Latest', 'intera' ); ?></div>
					<?php
					$intera_latest = array(
						array(
							'type' => 'reconciliation',
							'text' => __( 'Billing vs ERP: 118 invoices differ by more than 0.5%', 'intera' ),
							'rule' => true,
						),
						array(
							'type' => 'incident',
							'text' => __( 'No data received from WMS since 03:20 UTC', 'intera' ),
							'rule' => true,
						),
						array(
							'type' => 'pattern',
							'text' => __( 'Readiness drops every Monday after the weekend import', 'intera' ),
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
					'class'   => 'itr-hl',
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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'The chain', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Event, Reconciliation, Incident, Pattern', 'intera' ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php esc_html_e( 'Four object types, in a fixed order. Everything a role sees is one of them.', 'intera' ); ?></p>
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
					'title' => __( 'A watched Metric moved', 'intera' ),
					'body'  => __( 'Thresholds, trends and data status on the numbers a role owns. The event carries the value, the source and the time.', 'intera' ),
					'mono'  => __( 'metrics.trend · metrics.threshold', 'intera' ),
				),
				array(
					'type'  => 'reconciliation',
					'title' => __( 'Two systems disagree', 'intera' ),
					'body'  => __( 'Continuous comparison between systems, periods and business conditions — with the differing records listed, not summarised away.', 'intera' ),
					'mono'  => __( 'usage ↔ billing · orders ↔ invoices', 'intera' ),
				),
				array(
					'type'  => 'incident',
					'title' => __( 'Someone has to act', 'intera' ),
					'body'  => __( 'A tracked item with an owner, a priority, an expected time to impact and the evidence behind it.', 'intera' ),
					'mono'  => __( 'P0 · impact in 2 days · owner set', 'intera' ),
				),
				array(
					'type'  => 'pattern',
					'title' => __( 'It keeps happening', 'intera' ),
					'body'  => __( 'Recurring combinations of conditions behind incidents and changes — and the option to keep watching them.', 'intera' ),
					'mono'  => __( '4th occurrence · same precondition', 'intera' ),
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
				<div style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-400); margin-top: 12px"><?php echo esc_html( $intera_object['mono'] ); ?></div>
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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'Pattern Studio', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Understand what keeps happening, and under which conditions', 'intera' ); ?></h2>
			<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 18px; max-width: 520px"><?php esc_html_e( 'Look back at what preceded an incident, find the combination that repeats, then turn it into something INTERA keeps watching. What one manager learns becomes a check the whole company keeps.', 'intera' ); ?></p>
			<div style="display: flex; flex-direction: column; gap: 8px; margin-top: 24px; max-width: 520px">
				<div style="display: flex; gap: 12px; align-items: center; border: 1px solid var(--border-hairline); border-radius: var(--radius-md); padding: 12px 14px; background: var(--surface-sunken)">
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--violet-600)"><?php esc_html_e( 'if', 'intera' ); ?></span>
					<span style="font-size: var(--text-sm); color: var(--ink-800)"><?php esc_html_e( 'a spare part delivery slips more than 5 days', 'intera' ); ?></span>
				</div>
				<div style="display: flex; gap: 12px; align-items: center; border: 1px solid var(--border-hairline); border-radius: var(--radius-md); padding: 12px 14px; background: var(--surface-sunken)">
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--violet-600)"><?php esc_html_e( 'and', 'intera' ); ?></span>
					<span style="font-size: var(--text-sm); color: var(--ink-800)"><?php esc_html_e( 'the vessel already carries overdue critical maintenance', 'intera' ); ?></span>
				</div>
				<div class="itr-row" style="--itr-edge: var(--border-default); display: flex; gap: 12px; align-items: center; border-radius: var(--radius-md); padding: 12px 14px">
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--violet-600)"><?php esc_html_e( 'then', 'intera' ); ?></span>
					<span style="font-size: var(--text-sm); color: var(--ink-900); font-weight: 500"><?php esc_html_e( 'readiness drops below plan within 3 weeks — 4 of the last 5 times', 'intera' ); ?></span>
				</div>
			</div>
			<?php if ( '' !== $intera_docs_url ) : ?>
				<div style="margin-top: 26px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'      => __( 'How Patterns are defined', 'intera' ),
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
				'caption'    => __( 'Role view · readiness and upcoming dates', 'intera' ),
				'height'     => '420px',
				'alt'        => __( 'INTERA role view with readiness metrics and upcoming dates', 'intera' ),
			)
		);
		?>
	</div>
</section>

<section id="integrations" data-screen-label="Integrations" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 20%; top: 80%; width: 860px; height: 860px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-teal) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(49px, 7vw, 84px) clamp(20px, 5vw, 24px)">
		<div id="it" style="max-width: 720px; margin-bottom: 36px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'Integrations and DataSources', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Read-only connections to the systems of record', 'intera' ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php esc_html_e( 'A DataSource states which system holds the data, what is mapped and how often it is read. Nothing is written back.', 'intera' ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(200px, 100%), 1fr)); gap: 10px">
			<?php
			$intera_sources = array(
				array(
					'icon'  => 'database',
					'label' => __( 'ERP', 'intera' ),
					'note'  => __( 'SAP · Oracle · BC', 'intera' ),
				),
				array(
					'icon'  => 'contact',
					'label' => __( 'CRM', 'intera' ),
					'note'  => __( 'accounts', 'intera' ),
				),
				array(
					'icon'  => 'receipt',
					'label' => __( 'Billing', 'intera' ),
					'note'  => __( 'invoices · rating', 'intera' ),
				),
				array(
					'icon'  => 'activity',
					'label' => __( 'Mediation', 'intera' ),
					'note'  => __( 'usage · CDR', 'intera' ),
				),
				array(
					'icon'  => 'table-2',
					'label' => __( 'Excel', 'intera' ),
					'note'  => __( 'exports · checks', 'intera' ),
				),
				array(
					'icon'  => 'terminal',
					'label' => __( 'Internal APIs', 'intera' ),
					'note'  => __( 'custom', 'intera' ),
				),
				array(
					'icon'  => 'landmark',
					'label' => __( 'Banking', 'intera' ),
					'note'  => __( 'Revolut', 'intera' ),
				),
				array(
					'icon'  => 'mail',
					'label' => __( 'Manual inputs', 'intera' ),
					'note'  => __( 'forms · mail', 'intera' ),
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
					<span style="font-family: var(--font-mono); font-size: var(--text-2xs); color: var(--ink-400); margin-left: auto"><?php echo esc_html( $intera_source['note'] ); ?></span>
				</div>
				<?php
			endforeach;
			?>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px; margin-top: 28px">
			<?php
			$intera_ownership = array(
				array(
					'title'       => __( 'IT owns access', 'intera' ),
					'description' => __( 'Which system, which credentials, which refresh window.', 'intera' ),
					'body'        => __( 'INTERA states the requirement concretely: the system, the DataSource, the fields to map. No open-ended data project.', 'intera' ),
				),
				array(
					'title'       => __( 'Business owns logic', 'intera' ),
					'description' => __( 'Metrics, Events, Incidents, Reconciliations, Patterns.', 'intera' ),
					'body'        => __( 'The people who know how the operation runs decide what counts as a problem — and can change it without a release.', 'intera' ),
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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'INTERA Roles', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'A module built around a responsibility, not a data source', 'intera' ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php esc_html_e( 'Each role arrives with its metrics, its checks and its detection logic. Adjust them; you are not locked in.', 'intera' ); ?></p>
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
						'title'      => 'ASC',
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
				<p style="font-size: var(--text-xl); font-weight: 600; line-height: 1.35; letter-spacing: -0.01em; color: var(--ink-900)"><?php esc_html_e( 'Different responsibilities. One operating picture.', 'intera' ); ?></p>
				<p style="font-size: var(--text-sm); color: var(--ink-600); line-height: 1.6"><?php esc_html_e( 'Roles combine several sources and apply business logic, so nobody connects the dots by hand.', 'intera' ); ?></p>
			</div>
		</div>
	</div>
</section>

<section id="packages" data-screen-label="Market packages" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 82%; top: 70%; width: 880px; height: 880px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-violet) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(49px, 7vw, 84px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 36px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'Market packages', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Industry bundles, already shaped around real jobs', 'intera' ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php esc_html_e( 'A market package is a reusable set of roles, checks and integrations for one industry. Two are in progress with beta partners.', 'intera' ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 20px">
			<?php
			$intera_packages = array(
				array(
					'icon'  => 'radio-tower',
					'title' => __( 'Telecommunications', 'intera' ),
					'body'  => __( 'Where usage, rating, billing and partner settlement have to agree — and rarely do without checking.', 'intera' ),
					'tags'  => array(
						__( 'Revenue Assurance Manager', 'intera' ),
						__( 'Billing Operations Manager', 'intera' ),
						__( 'Network Operations Manager', 'intera' ),
						__( 'Partner / Wholesale Manager', 'intera' ),
						__( 'Commercial Director', 'intera' ),
						__( 'CFO / Finance Controller', 'intera' ),
						__( 'COO / Head of Operations', 'intera' ),
					),
					'link'  => __( 'Telecommunications package', 'intera' ),
				),
				array(
					'icon'  => 'ship',
					'title' => __( 'Shipmanagement', 'intera' ),
					'body'  => __( 'Maintenance backlog, defects, class and certificate dates, and the vendor dependencies that quietly delay all of it.', 'intera' ),
					'tags'  => array(
						__( 'Technical Superintendent', 'intera' ),
						__( 'Fleet Manager', 'intera' ),
						__( 'Procurement and parts', 'intera' ),
						__( 'Compliance and audit', 'intera' ),
					),
					'link'  => __( 'Shipmanagement package', 'intera' ),
				),
			);

			foreach ( $intera_packages as $intera_package ) :
				ob_start();
				?>
				<div style="display: flex; align-items: center; justify-content: space-between; gap: 12px">
					<div style="display: flex; align-items: center; gap: 10px">
						<?php
						intera_icon(
							$intera_package['icon'],
							array(
								'size'  => 18,
								'color' => 'var(--ink-700)',
							)
						);
						?>
						<span style="font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em"><?php echo esc_html( $intera_package['title'] ); ?></span>
					</div>
					<?php
					get_template_part(
						'template-parts/components/badge',
						null,
						array(
							'text' => __( 'Beta', 'intera' ),
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
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'INTERA Method', 'intera' ); ?></div>
				<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'A working system, not a set of recommendations', 'intera' ); ?></h2>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 18px; max-width: 520px"><?php esc_html_e( 'The Method is a hands-on engagement: we work on site with your team, map how the operation actually runs, and leave a configured environment behind. Traditional consulting ends with a document. This ends with dashboards that keep working.', 'intera' ); ?></p>
				<div style="display: flex; flex-direction: column; gap: 0; margin-top: 26px; border-top: 1px solid var(--border-hairline); max-width: 520px">
					<?php
					$intera_method_steps = array(
						__( 'Map the real data flows, not the documented ones', 'intera' ),
						__( 'Identify blind spots and the checks nobody owns', 'intera' ),
						__( 'Define Metrics that reflect the operation', 'intera' ),
						__( 'Connect the data sources with IT', 'intera' ),
						__( 'Build the first Roles and dashboards together', 'intera' ),
					);

					foreach ( $intera_method_steps as $intera_step_index => $intera_method_step ) :
						?>
						<div style="display: flex; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--border-hairline)"><span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-400)"><?php echo esc_html( sprintf( '%02d', $intera_step_index + 1 ) ); ?></span><span style="font-size: var(--text-md); color: var(--ink-700)"><?php echo esc_html( $intera_method_step ); ?></span></div>
						<?php
					endforeach;
					?>
				</div>
			</div>
			<?php
			ob_start();
			?>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php esc_html_e( 'What you leave with', 'intera' ); ?></div>
			<div style="display: flex; flex-direction: column; gap: 10px; margin-top: 16px; font-size: var(--text-md); color: var(--ink-800); line-height: 1.5">
				<?php
				$intera_method_outcomes = array(
					__( 'A working INTERA environment', 'intera' ),
					__( 'Connected data sources', 'intera' ),
					__( 'Defined Metrics and business logic', 'intera' ),
					__( 'Operational dashboards in use', 'intera' ),
					__( 'Visibility into issues you could not see before', 'intera' ),
				);

				foreach ( $intera_method_outcomes as $intera_method_outcome ) {
					echo '<span>' . esc_html( $intera_method_outcome ) . '</span>';
				}
				?>
			</div>
			<div style="margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--border-hairline)">
				<p style="font-size: var(--text-sm); color: var(--ink-600); line-height: 1.6"><?php esc_html_e( 'Delivered over several intensive on-site days. Scope depends on how many systems and roles are involved.', 'intera' ); ?></p>
				<?php if ( $intera_has_cta ) : ?>
					<div style="margin-top: 16px">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label' => __( 'Talk to us about the Method', 'intera' ),
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
			<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900); line-height: 1.3"><?php esc_html_e( 'Start with one real problem.', 'intera' ); ?></h2>
			<p style="font-size: var(--text-md); color: var(--ink-600); margin-top: 8px"><?php esc_html_e( 'One role. One operational problem. One working result.', 'intera' ); ?></p>
		</div>
		<?php
		if ( $intera_has_cta ) {
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => __( 'Bring us a real problem', 'intera' ),
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
