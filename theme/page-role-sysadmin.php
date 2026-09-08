<?php
/**
 * Template Name: Role: Sysadmin
 *
 * `/roles/sysadmin` — a campaign landing, not an export screen, and the longest
 * of the three. Traffic arrives from an advert aimed at one job, so everything
 * it has to say is grouped rather than fragmented: the hero, one band for what
 * INTERA shows a system administrator, one band for the free package, and the
 * ask. The two shared bands are the same partials the other two `/roles/*`
 * pages use, so the three cannot drift apart in layout.
 *
 * The middle band carries eight things the brief lists separately — what INTERA
 * is not, the example signals, a health view, an attention queue, the evidence
 * behind a result, the reconciliations, the Zabbix answer and the workflow — as
 * one band with sub-headings rather than eight bands of grid and wash. A reader
 * scrolling a landing page reads a band; eight of them read as a site.
 *
 * The health figures are copy, not data. Nothing on this site is connected to a
 * live installation, so an editor owns them the way they own every other run of
 * text on a designed page: the ones whose default is a bare figure carry an
 * explicit label, because a control named "87%" names nothing.
 *
 * What comes from WordPress:
 *
 * | slot            | source                                                   |
 * | --------------- | -------------------------------------------------------- |
 * | breadcrumb      | `intera_breadcrumbs()` (auto: Home / Roles / <title>)    |
 * | headline        | `role_sysadmin_hero__*` copy, falling back to the title  |
 * | lede            | `the_content()`, falling back to the copy field          |
 * | everything else | `role_sysadmin_*` copy on this page                      |
 * | every CTA link  | `intera_page_url()`                                      |
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
	the_post();
}

$intera_request_url = (string) intera_page_url( 'contact-request' );
$intera_pricing_url = (string) intera_page_url( 'pricing' );

get_template_part(
	'template-parts/partials/role-landing-hero',
	null,
	array(
		'eyebrow'         => intera_copy( 'role_sysadmin_hero__sysadmin_role' ),
		'headline'        => intera_copy( 'role_sysadmin_hero__see_what_needs_attention_before_someone' ),
		'lede'            => intera_copy( 'role_sysadmin_hero__intera_brings_together_selected_signals_from' ),
		/*
		 * The free package first, the programme second — the brief's own order,
		 * and the right one: the package is the thing a reader can have today
		 * without talking to anybody, which is what a cold landing needs to
		 * offer before it asks for an application.
		 */
		'primary_label'   => intera_copy( 'role_sysadmin_hero__try_the_free_sysadmin_package' ),
		'primary_url'     => $intera_pricing_url,
		'secondary_label' => intera_copy( 'role_sysadmin_hero__apply_for_private_beta' ),
		'secondary_url'   => $intera_request_url,
		'promise'         => array(
			array(
				'icon'  => 'alert-triangle',
				'label' => intera_copy( 'role_sysadmin_hero__know_what_is_failing' ),
			),
			array(
				'icon'  => 'search',
				'label' => intera_copy( 'role_sysadmin_hero__know_where_to_investigate' ),
			),
			array(
				'icon'  => 'clipboard-check',
				'label' => intera_copy( 'role_sysadmin_hero__have_evidence_when_someone_asks' ),
			),
			array(
				'icon'  => 'shield-check',
				'label' => intera_copy( 'role_sysadmin_hero__stop_carrying_the_blame_for_problems' ),
			),
		),
	)
);
?>

<section data-screen-label="What INTERA shows" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__what_intera_shows_you' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__above_the_tools_you_already_run' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__intera_sits_above_the_tools_you' ) ); ?></p>
		</div>

		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 20px; margin-top: 40px; align-items: start">
			<div style="display: flex; flex-direction: column; gap: 20px">
			<?php
			/*
			 * What it is not, first. The reader already owns a monitoring
			 * stack and the question in front of every other one is whether
			 * this replaces it — leaving that unanswered until further down
			 * costs the rest of the page its audience.
			 *
			 * `minus` rather than an ✕: these are boundaries, not failures,
			 * and the critical palette would say the wrong thing about them.
			 */
			$intera_sysadmin_nots = array(
				intera_copy( 'role_sysadmin_shows__not_a_replacement_for_zabbix' ),
				intera_copy( 'role_sysadmin_shows__not_a_replacement_for_grafana' ),
				intera_copy( 'role_sysadmin_shows__not_another_nms' ),
				intera_copy( 'role_sysadmin_shows__not_a_ticketing_system' ),
			);

			ob_start();
			?>
			<div style="font-size: var(--text-md); font-weight: 600; line-height: 1.4; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__what_intera_is_not' ) ); ?></div>
			<div style="display: flex; flex-direction: column; gap: 0; margin-top: 14px; border-top: 1px solid var(--border-hairline)">
				<?php foreach ( $intera_sysadmin_nots as $intera_sysadmin_not ) : ?>
					<div style="display: flex; gap: 12px; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--border-hairline)">
						<span style="flex: none; display: inline-flex; color: var(--text-muted)"><?php intera_icon( 'minus', array( 'size' => 15 ) ); ?></span>
						<span style="min-width: 0; font-size: var(--text-base); line-height: 1.5; color: var(--ink-700)"><?php echo esc_html( $intera_sysadmin_not ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'content' => ob_get_clean(),
					'padding' => 'loose',
					'class'   => 'itr-lift',
				)
			);

			/*
			 * The signals, as chips, under the boundaries in the same column.
			 * A chip is what the design system draws for "one of many of the
			 * same kind", and a list would put ten rows of weight behind what
			 * is meant to be read as a range. Two cards stacked here rather
			 * than one band across the page because the column beside them is
			 * a tall one, and a short card next to a tall one leaves a hole
			 * the eye reads as a missing block.
			 *
			 * Every chip is `flex: none` with `max-width: 100%`: it may not be
			 * squeezed below its own label, and a label longer than the column
			 * takes a second line instead of running past it.
			 */
			$intera_sysadmin_signals = array(
				intera_copy( 'role_sysadmin_shows__server_alive_dead' ),
				intera_copy( 'role_sysadmin_shows__disk_space' ),
				intera_copy( 'role_sysadmin_shows__backup_success_failure' ),
				intera_copy( 'role_sysadmin_shows__certificate_expiry' ),
				intera_copy( 'role_sysadmin_shows__api_service_availability' ),
				intera_copy( 'role_sysadmin_shows__database_backup_status' ),
				intera_copy( 'role_sysadmin_shows__external_service_dependency' ),
				intera_copy( 'role_sysadmin_shows__unresolved_infrastructure_tickets' ),
				intera_copy( 'role_sysadmin_shows__licence_subscription_expiry' ),
				intera_copy( 'role_sysadmin_shows__integration_data_feed_failure' ),
			);

			ob_start();
			?>
			<div style="font-size: var(--text-md); font-weight: 600; line-height: 1.4; color: var(--ink-900); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__example_signals' ) ); ?></div>
			<div style="display: flex; flex-wrap: wrap; gap: 8px">
				<?php
				foreach ( $intera_sysadmin_signals as $intera_sysadmin_signal ) {
					if ( '' === trim( (string) $intera_sysadmin_signal ) ) {
						continue;
					}

					get_template_part(
						'template-parts/components/tag',
						null,
						array(
							'text'  => $intera_sysadmin_signal,
							'style' => 'flex: none; max-width: 100%',
						)
					);
				}
				?>
			</div>
			<?php
			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'content' => ob_get_clean(),
					'padding' => 'loose',
					'class'   => 'itr-lift',
				)
			);
			?>
			</div>
			<?php
			/*
			 * The health view and the queue under it, in one card. They are the
			 * same screen in the product — a score, what it is made of, and
			 * what to open first — and splitting them would make the score
			 * look like a figure rather than a way in.
			 */
			ob_start();
			?>
			<div style="display: flex; flex-wrap: wrap; gap: 10px 16px; align-items: baseline">
				<span style="flex: 1 1 auto; min-width: 0; font-size: var(--text-md); font-weight: 600; line-height: 1.4; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__it_operations_health' ) ); ?></span>
				<span style="flex: none; font-family: var(--font-mono); font-size: var(--text-3xl); font-weight: var(--weight-medium); letter-spacing: -0.02em; line-height: 1; color: var(--status-ok)"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__health_score' ) ); ?></span>
			</div>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(120px, 100%), 1fr)); gap: 10px; margin-top: 16px">
				<?php
				$intera_sysadmin_counts = array(
					array( 'ok', intera_copy( 'role_sysadmin_shows__systems_healthy' ), intera_copy( 'role_sysadmin_shows__healthy_count' ) ),
					array( 'warning', intera_copy( 'role_sysadmin_shows__need_attention' ), intera_copy( 'role_sysadmin_shows__attention_count' ) ),
					array( 'critical', intera_copy( 'role_sysadmin_shows__critical' ), intera_copy( 'role_sysadmin_shows__critical_count' ) ),
				);

				foreach ( $intera_sysadmin_counts as $intera_sysadmin_count ) {
					get_template_part(
						'template-parts/components/metric-tile',
						null,
						array(
							'label' => $intera_sysadmin_count[1],
							'value' => $intera_sysadmin_count[2],
							'tone'  => $intera_sysadmin_count[0],
						)
					);
				}
				?>
			</div>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--text-muted); margin-top: 24px; margin-bottom: 10px"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__what_needs_attention_first' ) ); ?></div>
			<div style="display: flex; flex-direction: column; gap: 8px">
				<?php
				/*
				 * The queue, in the order the product would rank it: what has
				 * already failed, then what is about to, then what is only a
				 * number so far. The tone dot is the whole ranking — a row
				 * needs no priority chip when the column it sits in is short.
				 */
				$intera_sysadmin_queue = array(
					array( 'var(--status-critical)', intera_copy( 'role_sysadmin_shows__backup_failed_overnight' ) ),
					array( 'var(--status-warning)', intera_copy( 'role_sysadmin_shows__ssl_certificate_expires_in_9_days' ) ),
					array( 'var(--status-warning)', intera_copy( 'role_sysadmin_shows__server_disk_usage_at_91' ) ),
					array( 'var(--status-critical)', intera_copy( 'role_sysadmin_shows__accounting_api_unavailable' ) ),
					array( 'var(--ink-300)', intera_copy( 'role_sysadmin_shows__monitoring_data_stale_for_3_hours' ) ),
				);

				foreach ( $intera_sysadmin_queue as $intera_sysadmin_item ) :
					?>
					<div class="itr-row" style="--itr-bg: var(--surface-sunken); --itr-edge: var(--border-card); display: flex; gap: 12px; align-items: flex-start; border-radius: var(--radius-md); padding: 12px 14px">
						<span aria-hidden="true" style="flex: none; width: 8px; height: 8px; margin-top: 7px; border-radius: 999px; background: <?php echo esc_attr( $intera_sysadmin_item[0] ); ?>"></span>
						<span style="min-width: 0; font-size: var(--text-sm); line-height: 1.5; color: var(--ink-800)"><?php echo esc_html( $intera_sysadmin_item[1] ); ?></span>
					</div>
					<?php
				endforeach;
				?>
			</div>
			<?php
			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'content'  => ob_get_clean(),
					'padding'  => 'loose',
					'elevated' => true,
					'class'    => 'itr-lift',
				)
			);
			?>
		</div>

		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 20px; margin-top: 44px">
			<?php
			/*
			 * The evidence behind a result, and the reconciliations that
			 * produce one. Side by side because they answer each other: the
			 * left says what a sysadmin gets to see when they open a problem,
			 * the right says which problems only exist once two systems are
			 * read together — which is the whole difference from monitoring.
			 */
			ob_start();
			?>
			<div style="font-size: var(--text-md); font-weight: 600; line-height: 1.4; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__evidence_behind_every_result' ) ); ?></div>
			<div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px">
				<?php
				$intera_sysadmin_evidence = array(
					intera_copy( 'role_sysadmin_shows__source_system' ),
					intera_copy( 'role_sysadmin_shows__current_value' ),
					intera_copy( 'role_sysadmin_shows__previous_value' ),
					intera_copy( 'role_sysadmin_shows__last_successful_update' ),
					intera_copy( 'role_sysadmin_shows__related_systems_and_assets' ),
					intera_copy( 'role_sysadmin_shows__why_intera_marked_it_unhealthy' ),
				);

				foreach ( $intera_sysadmin_evidence as $intera_sysadmin_field ) {
					if ( '' === trim( (string) $intera_sysadmin_field ) ) {
						continue;
					}

					get_template_part(
						'template-parts/components/tag',
						null,
						array(
							'text'  => $intera_sysadmin_field,
							'style' => 'flex: none; max-width: 100%',
						)
					);
				}
				?>
			</div>
			<p style="font-size: var(--text-base); line-height: 1.6; color: var(--ink-900); font-weight: 500; margin-top: 18px"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__intera_helps_you_prove_what_happened' ) ); ?></p>
			<?php
			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'content' => ob_get_clean(),
					'padding' => 'loose',
					'class'   => 'itr-lift',
				)
			);

			ob_start();
			?>
			<div style="font-size: var(--text-md); font-weight: 600; line-height: 1.4; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__where_monitoring_stops' ) ); ?></div>
			<div style="display: flex; flex-direction: column; gap: 0; margin-top: 14px; border-top: 1px solid var(--border-hairline)">
				<?php
				$intera_sysadmin_recons = array(
					intera_copy( 'role_sysadmin_shows__monitoring_says_the_service_is_up' ),
					intera_copy( 'role_sysadmin_shows__the_backup_job_says_completed_but' ),
					intera_copy( 'role_sysadmin_shows__the_server_is_online_but_the' ),
					intera_copy( 'role_sysadmin_shows__an_asset_exists_in_the_cmdb' ),
					intera_copy( 'role_sysadmin_shows__a_licence_is_active_but_the' ),
				);

				foreach ( $intera_sysadmin_recons as $intera_sysadmin_recon ) :
					if ( '' === trim( (string) $intera_sysadmin_recon ) ) {
						continue;
					}
					?>
					<div style="display: flex; gap: 12px; padding: 13px 0; border-bottom: 1px solid var(--border-hairline)">
						<span style="flex: none; display: inline-flex; padding-top: 3px; color: var(--teal-600)"><?php intera_icon( 'scale', array( 'size' => 15 ) ); ?></span>
						<span style="min-width: 0; font-size: var(--text-sm); line-height: 1.55; color: var(--ink-700)"><?php echo esc_html( $intera_sysadmin_recon ); ?></span>
					</div>
					<?php
				endforeach;
				?>
			</div>
			<?php
			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'content' => ob_get_clean(),
					'padding' => 'loose',
					'class'   => 'itr-lift',
				)
			);
			?>
		</div>

		<?php
		/*
		 * The Zabbix question, asked and answered in the reader's own words.
		 * It is the objection this page exists to survive, so it is stated
		 * rather than worked around, and it closes the band.
		 */
		ob_start();
		?>
		<div style="display: flex; flex-wrap: wrap; gap: 20px 32px; align-items: start">
			<div style="flex: 0 1 260px; min-width: 0">
				<div style="color: var(--blue-600); margin-bottom: 10px"><?php intera_icon( 'info', array( 'size' => 20 ) ); ?></div>
				<h3 style="font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.3; color: var(--ink-900); margin: 0"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__why_not_just_use_zabbix' ) ); ?></h3>
			</div>
			<div style="flex: 1 1 min(100%, 420px); min-width: 0">
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-700)"><?php echo esc_html( intera_copy( 'role_sysadmin_shows__zabbix_tells_you_what_is_happening' ) ); ?></p>
			</div>
		</div>
		<?php
		get_template_part(
			'template-parts/components/card',
			null,
			array(
				'content'     => ob_get_clean(),
				'padding'     => 'loose',
				'accent'      => 'var(--blue-600)',
				'accent_line' => 'var(--border-default)',
				'style'       => 'margin-top: 44px',
			)
		);
		?>

		<?php
		/*
		 * The workflow, as three stages on one line. Not a diagram: the three
		 * stages are three short strings, the arrow between them is an icon a
		 * screen reader skips, and the whole thing wraps into a column on a
		 * phone rather than scrolling sideways.
		 *
		 * Rows rather than tiles, and the difference is load-bearing:
		 * `.itr-tile` is `white-space: nowrap`, and the second line of each
		 * stage is a long mono string that has to be allowed to break.
		 */
		$intera_sysadmin_flow = array(
			array(
				'label' => intera_copy( 'role_sysadmin_shows__existing_tools' ),
				'note'  => intera_copy( 'role_sysadmin_shows__zabbix_backup_system_ticketing_sql_api' ),
			),
			array(
				'label' => intera_copy( 'role_sysadmin_shows__intera' ),
				'note'  => intera_copy( 'role_sysadmin_shows__one_role_one_set_of_checks' ),
			),
			array(
				'label' => intera_copy( 'role_sysadmin_shows__attention_health_evidence' ),
				'note'  => intera_copy( 'role_sysadmin_shows__3_things_need_attention' ),
			),
		);

		$intera_sysadmin_stage = 0;
		?>
		<div style="display: flex; flex-wrap: wrap; align-items: stretch; gap: 12px; margin-top: 20px">
			<?php
			foreach ( $intera_sysadmin_flow as $intera_sysadmin_step ) :
				++$intera_sysadmin_stage;

				if ( $intera_sysadmin_stage > 1 ) :
					?>
					<span class="itr-flow-arrow" aria-hidden="true"><?php intera_icon( 'arrow-right', array( 'size' => 18 ) ); ?></span>
					<?php
				endif;
				?>
				<div class="itr-row" style="--itr-shadow: var(--shadow-xs); flex: 1 1 240px; min-width: 0; display: flex; flex-direction: column; gap: 4px; border-radius: var(--radius-md); padding: 16px 18px">
					<span style="font-size: var(--text-md); font-weight: 600; line-height: 1.4; color: var(--ink-900)"><?php echo esc_html( $intera_sysadmin_step['label'] ); ?></span>
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); line-height: 1.5; color: var(--text-muted)"><?php echo esc_html( $intera_sysadmin_step['note'] ); ?></span>
				</div>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<section id="package" data-screen-label="Free Sysadmin Package" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 16%; top: 26%; width: 880px; height: 880px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-blue) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px">
			<?php
			get_template_part(
				'template-parts/components/badge',
				null,
				array(
					'text' => intera_copy( 'role_sysadmin_package__included_free_with_every_plan' ),
					'tone' => 'ok',
					'icon' => 'check',
				)
			);
			?>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900); margin-top: 14px"><?php echo esc_html( intera_copy( 'role_sysadmin_package__free_sysadmin_package' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php echo esc_html( intera_copy( 'role_sysadmin_package__the_package_is_a_starter_set' ) ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 20px; margin-top: 40px">
			<?php
			/*
			 * What is in the package, and what it reads from. Two lists of the
			 * same shape, so they are built by one loop rather than written
			 * twice — the sources list stays deliberately generic, because a
			 * named integration this page cannot deliver is the one promise
			 * that costs a first conversation.
			 */
			$intera_sysadmin_lists = array(
				array(
					'icon'    => 'package',
					'heading' => intera_copy( 'role_sysadmin_package__what_the_package_contains' ),
					'items'   => array(
						intera_copy( 'role_sysadmin_package__server_asset_type' ),
						intera_copy( 'role_sysadmin_package__service_application_asset_type' ),
						intera_copy( 'role_sysadmin_package__backup_health_metrics' ),
						intera_copy( 'role_sysadmin_package__availability_metrics' ),
						intera_copy( 'role_sysadmin_package__storage_metrics' ),
						intera_copy( 'role_sysadmin_package__certificate_expiry' ),
						intera_copy( 'role_sysadmin_package__dependency_health' ),
						intera_copy( 'role_sysadmin_package__it_attention_dashboard' ),
						intera_copy( 'role_sysadmin_package__basic_reconciliations' ),
					),
				),
				array(
					'icon'    => 'plug',
					'heading' => intera_copy( 'role_sysadmin_package__where_the_signals_come_from' ),
					'items'   => array(
						intera_copy( 'role_sysadmin_package__monitoring_systems' ),
						intera_copy( 'role_sysadmin_package__sql_databases' ),
						intera_copy( 'role_sysadmin_package__backup_systems' ),
						intera_copy( 'role_sysadmin_package__helpdesk_and_ticketing' ),
						intera_copy( 'role_sysadmin_package__cloud_and_service_apis' ),
						intera_copy( 'role_sysadmin_package__accounting_or_erp_apis_where_relevant' ),
						intera_copy( 'role_sysadmin_package__generic_http_api_checks_through_your' ),
					),
				),
			);

			foreach ( $intera_sysadmin_lists as $intera_sysadmin_list ) {
				ob_start();
				?>
				<div style="display: flex; align-items: center; gap: 10px; color: var(--blue-600)">
					<?php intera_icon( $intera_sysadmin_list['icon'], array( 'size' => 18 ) ); ?>
					<span style="font-size: var(--text-md); font-weight: 600; line-height: 1.4; color: var(--ink-900)"><?php echo esc_html( $intera_sysadmin_list['heading'] ); ?></span>
				</div>
				<div style="display: flex; flex-direction: column; gap: 0; margin-top: 14px; border-top: 1px solid var(--border-hairline)">
					<?php
					foreach ( $intera_sysadmin_list['items'] as $intera_sysadmin_entry ) :
						if ( '' === trim( (string) $intera_sysadmin_entry ) ) {
							continue;
						}
						?>
						<span style="font-size: var(--text-sm); line-height: 1.5; color: var(--ink-700); padding: 11px 0; border-bottom: 1px solid var(--border-hairline)"><?php echo esc_html( $intera_sysadmin_entry ); ?></span>
						<?php
					endforeach;
					?>
				</div>
				<?php
				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content' => ob_get_clean(),
						'padding' => 'loose',
						'class'   => 'itr-lift',
					)
				);
			}
			?>
		</div>
		<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 26px; max-width: 760px"><?php echo esc_html( intera_copy( 'role_sysadmin_package__it_is_designed_to_work_with' ) ); ?></p>
	</div>
</section>

<?php
get_template_part(
	'template-parts/partials/role-landing-cta',
	null,
	array(
		'eyebrow' => intera_copy( 'role_sysadmin_cta__private_beta' ),
		'heading' => intera_copy( 'role_sysadmin_cta__bring_us_one_recurring_it_problem' ),
		'body'    => intera_copy( 'role_sysadmin_cta__we_will_start_with_one_role' ),
		'label'   => intera_copy( 'role_sysadmin_cta__apply_for_private_beta' ),
		'url'     => $intera_request_url,
	)
);

get_footer();
