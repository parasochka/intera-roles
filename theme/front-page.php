<?php
/**
 * Front page — the home screen (`_design/01-main.dc.html`).
 *
 * Eleven sections, in the export's order: Hero, Problem, How it works, Champion,
 * In action, Roles, Working with IT, Start small, Pricing, Early Adopter, Partners.
 *
 * The handoff is preserved 1:1 — every inline `style` below is the mockup's own,
 * with the `var(--token)` names verbatim. Only three kinds of change were made:
 *
 *  - `<x-import …DesignSystem…>` became `get_template_part()` on the matching
 *    component (Button, Badge, Card, SignalBadge, SignalChain) or `intera_icon()`.
 *  - `<dc-import name="site-nav">` and the 76px spacer are gone: header.php owns
 *    the chrome, opens `<main>` and emits the spacer; footer.php closes it.
 *  - The repeated records became WordPress content: the five role cards are the
 *    `role` post type, the three plan cards are `plan`, and the three product
 *    screenshots are media-library images read through `intera_option()`.
 *
 * PORT.md §1: `background`, `border`, `border-color`, `box-shadow` and
 * `transition` are never inline on an element carrying `.itr-lift`, `.itr-row`,
 * `.itr-tile`, `.itr-panel`, `.itr-hl-panel`, `.itr-frame` or `.itr-float` — an
 * inline declaration outranks the hover rules in assets/css/intera.css and the
 * element would sit there dead. Per-instance values arrive as `--itr-bg`,
 * `--itr-edge`, `--itr-shadow` and `--itr-indent`; padding, radius, grid tracks
 * and the `border-top` accent stripes stay inline, exactly as the mockup writes
 * them. The export's preview-only `style-hover` attributes are dropped.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/*
 * The four link targets on this page. `intera_page_url()` resolves each one by
 * the page template assigned in the editor, so no `*.dc.html` href survives.
 * An unresolved key returns '' and the Button component renders an inert
 * <button> rather than a link to nowhere.
 */
$intera_request_url  = intera_page_url( 'contact-request' );
$intera_pricing_url  = intera_page_url( 'pricing' );
$intera_contacts_url = intera_page_url( 'contacts' );
$intera_product_url  = intera_page_url( 'product' );
$intera_roles_url    = $intera_product_url ? $intera_product_url . '#roles' : '';

get_header();
?>

<section data-screen-label="Hero" style="position: relative; overflow: hidden; background: var(--ink-950)">
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
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(49px, 7vw, 84px) clamp(20px, 5vw, 24px) clamp(53px, 7vw, 92px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(380px, 100%), 1fr)); gap: 52px; align-items: center">
		<div>
			<?php
			/*
			 * The live-status pill. Its wording is the same site-wide state that
			 * ends the footer legal line, so it is read as an option rather than
			 * typed here; the mockup's copy is the registered fallback.
			 */
			$intera_hero_status = trim( (string) intera_option( 'hero_status', __( 'In beta — Early Adopter programme open', 'intera' ) ) );
			?>
			<?php if ( '' !== $intera_hero_status ) : ?>
				<div class="itr-panel" style="--itr-edge: rgba(255,255,255,.22); --itr-bg: rgba(255,255,255,.06); display: inline-flex; align-items: center; gap: 9px; border-radius: var(--radius-round); padding: 5px 14px 5px 10px">
					<span style="position: relative; display: inline-flex; width: 8px; height: 8px; flex: none">
						<span class="itr-live-halo" aria-hidden="true" style="position: absolute; inset: 0; border-radius: 999px; background: var(--green-500)"></span>
						<span class="itr-live-dot" style="position: relative; width: 8px; height: 8px; border-radius: 999px; background: var(--green-500)"></span>
					</span>
					<span style="font-size: var(--text-xs); color: rgba(255,255,255,.82); font-weight: 500"><?php echo esc_html( $intera_hero_status ); ?></span>
				</div>
			<?php endif; ?>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: rgba(255,255,255,.42); margin-top: 30px"><?php echo esc_html( intera_copy( 'home_hero__your_business_clearly' ) ); ?></div>
			<h1 style="font-size: clamp(34px, 4vw, 52px); font-weight: 600; line-height: 1.08; letter-spacing: -0.028em; color: var(--white); margin-top: 14px; max-width: 520px; text-wrap: balance"><?php echo esc_html( intera_copy( 'home_hero__see_what_needs_attention_before_someone' ) ); ?></h1>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: rgba(255,255,255,.66); margin-top: 22px; max-width: 460px"><?php echo esc_html( intera_copy( 'home_hero__intera_connects_the_systems_your_teams' ) ); ?></p>
			<div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 32px">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'   => intera_copy( 'home_hero__get_early_access' ),
						'href'    => $intera_request_url,
						'variant' => 'inverse',
						'size'    => 'lg',
					)
				);

				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'      => intera_copy( 'home_hero__see_how_intera_works' ),
						'href'       => '#how',
						'variant'    => 'outlineInverse',
						'size'       => 'lg',
						'icon_right' => 'arrow-right',
					)
				);
				?>
			</div>
			<div style="display: flex; gap: 26px; flex-wrap: wrap; margin-top: 38px; padding-top: 22px; border-top: 1px solid rgba(255,255,255,.14)">
				<?php
				$intera_hero_facts = array(
					'route-off'   => intera_copy( 'home_hero__no_migration' ),
					'lock'        => intera_copy( 'home_hero__read_only_access' ),
					'circle-dot'  => intera_copy( 'home_hero__start_with_one_role' ),
				);

				foreach ( $intera_hero_facts as $intera_fact_icon => $intera_fact_label ) :
					?>
					<span style="display: inline-flex; align-items: center; gap: 8px; font-size: var(--text-sm); color: rgba(255,255,255,.78)">
						<?php
						intera_icon(
							$intera_fact_icon,
							array(
								'size'  => 15,
								'color' => 'rgba(255,255,255,.45)',
							)
						);
						echo esc_html( $intera_fact_label );
						?>
					</span>
					<?php
				endforeach;
				?>
			</div>
			<div style="display: flex; align-items: center; gap: 14px; margin-top: 18px; flex-wrap: wrap">
				<span style="font-size: var(--text-2xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: rgba(255,255,255,.4)"><?php echo esc_html( intera_copy( 'home_hero__reads_from' ) ); ?></span>
				<span style="display: flex; gap: 14px; flex-wrap: wrap; font-family: var(--font-mono); font-size: var(--text-xs); color: rgba(255,255,255,.58)">
					<span>ERP</span><span>CRM</span><span>Billing</span><span>Excel</span><span><?php echo esc_html( intera_copy( 'home_hero__internal_tools' ) ); ?></span>
				</span>
			</div>
		</div>
		<div style="position: relative; min-width: 0">
			<?php
			/*
			 * With no hero screenshot set the frame renders nothing, and an
			 * absolutely positioned card would then hang off a zero-height
			 * wrapper. Without a frame the card simply becomes the block it
			 * already is below 900px.
			 */
			$intera_hero_shot  = intera_shot_id( 'shot_hero' );
			$intera_hero_float = ( $intera_hero_shot > 0 && wp_attachment_is_image( $intera_hero_shot ) )
				? 'itr-float itr-lift'
				: 'itr-lift';

			get_template_part(
				'template-parts/partials/screenshot-frame',
				null,
				array(
					'attachment' => $intera_hero_shot,
					'caption'    => intera_copy( 'home_hero__fleet_health_overview_shipmanagement' ),
					'height'     => '420px',
					'shadow'     => 'var(--shadow-overlay)',
				)
			);

			/*
			 * The card that floats over the frame. It is a Card: the component
			 * already paints --surface-card, --radius-card and routes the resting
			 * border through --itr-edge (accent_line), which is what PORT.md asks
			 * `.itr-float` to be given. `.itr-float` itself supplies the position,
			 * the width and --shadow-overlay.
			 */
			ob_start();
			?>
			<div style="display: flex; align-items: center; gap: 8px">
				<?php
				get_template_part(
					'template-parts/components/signal-badge',
					null,
					array(
						'type' => 'incident',
						'size' => 'sm',
					)
				);
				?>
				<span style="font-family: var(--font-mono); font-size: var(--text-2xs); color: var(--ink-400)">09:14 UTC</span>
			</div>
			<div style="font-size: var(--text-md); font-weight: 600; color: var(--ink-900); margin-top: 10px; line-height: 1.4"><?php echo esc_html( intera_copy( 'home_hero__critical_maintenance_task_overdue' ) ); ?></div>
			<div style="display: flex; align-items: baseline; gap: 8px; margin-top: 10px">
				<span style="font-family: var(--font-mono); font-size: var(--text-2xl); font-weight: 500; color: var(--ink-900); letter-spacing: -0.01em"><?php echo esc_html( intera_copy( 'home_hero__5_days' ) ); ?></span>
				<span style="font-size: var(--text-xs); color: var(--ink-500)"><?php echo esc_html( intera_copy( 'home_hero__before_impact' ) ); ?></span>
			</div>
			<div style="display: flex; align-items: center; gap: 7px; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border-subtle); font-size: var(--text-xs); color: var(--ink-500)">
				<?php
				intera_icon(
					'git-branch',
					array(
						'size'  => 13,
						'color' => 'var(--signal-pattern)',
					)
				);
				echo esc_html( intera_copy( 'home_hero__4th_occurrence_always_after_a_delayed' ) );
				?>
			</div>
			<?php
			$intera_float_body = ob_get_clean();

			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'content'     => $intera_float_body,
					'padding'     => 'compact',
					'accent'      => 'var(--signal-incident)',
					'accent_line' => 'var(--border-default)',
					'class'       => $intera_hero_float,
				)
			);
			?>
		</div>
	</div>
</section>

<section data-screen-label="Problem" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 88%; top: 18%; width: 820px; height: 820px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-amber) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 56px; align-items: start">
		<div>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_problem__the_problem' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_problem__this_will_feel_familiar' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px; max-width: 520px"><?php echo esc_html( intera_copy( 'home_problem__your_business_runs_across_several_systems' ) ); ?></p>
			<div style="display: flex; flex-direction: column; gap: 14px; max-width: 520px; margin-top: 24px">
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-700)"><?php echo esc_html( intera_copy( 'home_problem__crm_billing_erp_spreadsheets_and_internal' ) ); ?></p>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-700)"><?php echo esc_html( intera_copy( 'home_problem__teams_spend_time_checking_reconciling_explaining' ) ); ?></p>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-900); font-weight: 500"><?php echo esc_html( intera_copy( 'home_problem__intera_makes_that_operating_picture_continuously' ) ); ?></p>
			</div>
		</div>
		<div style="display: flex; flex-direction: column; gap: 10px">
			<?php
			/*
			 * The five source rows. `.itr-stagger` carries the desktop indent as
			 * --itr-indent so the 760px breakpoint can flatten it without
			 * `!important`, and the resting --shadow-xs is a custom property
			 * because `.itr-row:hover` replaces the shadow.
			 */
			$intera_sources = array(
				array(
					'icon'   => 'boxes',
					'label'  => intera_copy( 'home_problem__erp' ),
					'ref'    => 'erp.orders',
					'dot'    => 'var(--ink-200)',
					'indent' => 0,
				),
				array(
					'icon'   => 'contact',
					'label'  => intera_copy( 'home_problem__crm' ),
					'ref'    => 'crm.accounts',
					'dot'    => 'var(--ink-200)',
					'indent' => 26,
				),
				array(
					'icon'   => 'receipt',
					'label'  => intera_copy( 'home_problem__billing' ),
					'ref'    => 'billing.invoices',
					'dot'    => 'var(--status-warning)',
					'indent' => 52,
				),
				array(
					'icon'   => 'table-2',
					'label'  => intera_copy( 'home_problem__spreadsheets' ),
					'ref'    => 'ops_checks.xlsx',
					'dot'    => 'var(--ink-200)',
					'indent' => 26,
				),
				array(
					'icon'   => 'terminal',
					'label'  => intera_copy( 'home_problem__internal_tools' ),
					'ref'    => 'provisioning.api',
					'dot'    => 'var(--ink-200)',
					'indent' => 0,
				),
			);

			foreach ( $intera_sources as $intera_source ) :
				$intera_source_class = $intera_source['indent'] ? 'itr-row itr-stagger' : 'itr-row';
				$intera_source_style = '--itr-shadow: var(--shadow-xs)';

				if ( $intera_source['indent'] ) {
					$intera_source_style .= '; --itr-indent: ' . (int) $intera_source['indent'] . 'px';
				}

				$intera_source_style .= '; display: flex; align-items: center; gap: 14px; border-radius: var(--radius-md); padding: 13px 16px';
				?>
				<div class="<?php echo esc_attr( $intera_source_class ); ?>" style="<?php echo esc_attr( $intera_source_style ); ?>">
					<?php
					intera_icon(
						$intera_source['icon'],
						array(
							'size'  => 16,
							'color' => 'var(--ink-500)',
						)
					);
					?>
					<span style="font-size: var(--text-md); font-weight: 500; color: var(--ink-800)"><?php echo esc_html( $intera_source['label'] ); ?></span>
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-400); margin-left: auto"><?php echo esc_html( $intera_source['ref'] ); ?></span>
					<span aria-hidden="true" style="width: 8px; height: 8px; border-radius: 999px; background: <?php echo esc_attr( $intera_source['dot'] ); ?>"></span>
				</div>
				<?php
			endforeach;
			?>
			<div style="display: flex; align-items: center; gap: 10px; margin-top: 8px; color: var(--ink-400); font-size: var(--text-xs)">
				<?php
				intera_icon( 'corner-down-right', array( 'size' => 15 ) );
				echo esc_html( intera_copy( 'home_problem__pieces_of_the_same_picture_checked' ) );
				?>
			</div>
		</div>
	</div>
</section>

<section id="how" data-screen-label="How it works" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(53px, 7vw, 92px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 40px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_how_it_works__how_it_works' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_how_it_works__get_full_visibility_without_changing_how' ) ); ?></h2>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(290px, 100%), 1fr)); gap: 20px">
			<?php
			$intera_steps = array(
				array(
					'icon'  => 'plug',
					'title' => intera_copy( 'home_how_it_works__connect_your_existing_systems' ),
					'body'  => intera_copy( 'home_how_it_works__intera_connects_to_finance_operations_crm' ),
				),
				array(
					'icon'  => 'scale',
					'title' => intera_copy( 'home_how_it_works__intera_understands_what_matters' ),
					'body'  => intera_copy( 'home_how_it_works__it_applies_your_business_logic_and' ),
				),
				array(
					'icon'  => 'eye',
					'title' => intera_copy( 'home_how_it_works__see_what_needs_attention' ),
					'body'  => intera_copy( 'home_how_it_works__managers_immediately_see_what_changed_what' ),
				),
			);

			$intera_step_number = 0;

			foreach ( $intera_steps as $intera_step ) {
				++$intera_step_number;

				ob_start();
				?>
				<div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px">
					<span style="width: 34px; height: 34px; border-radius: var(--radius-md); background: var(--blue-50); border: 1px solid var(--blue-100); display: grid; place-items: center; color: var(--blue-600)">
						<?php intera_icon( $intera_step['icon'], array( 'size' => 17 ) ); ?>
					</span>
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-400)"><?php echo esc_html( sprintf( '%02d', $intera_step_number ) ); ?></span>
				</div>
				<div style="font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.3"><?php echo esc_html( $intera_step['title'] ); ?></div>
				<p style="font-size: var(--text-md); line-height: 1.6; color: var(--ink-600); margin-top: 10px"><?php echo esc_html( $intera_step['body'] ); ?></p>
				<?php
				$intera_step_body = ob_get_clean();

				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content' => $intera_step_body,
						'padding' => 'loose',
						'class'   => 'itr-lift',
					)
				);
			}
			?>
		</div>
		<p style="font-size: var(--text-lg); color: var(--ink-700); margin-top: 34px; max-width: 760px; line-height: 1.6"><?php echo esc_html( intera_copy( 'home_how_it_works__intera_doesn_t_replace_your_team' ) ); ?></p>
	</div>
</section>

<section data-screen-label="Champion" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 82%; top: 78%; width: 880px; height: 880px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-teal) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 40px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_champion__for_the_manager_who_owns_the' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_champion__make_your_area_easier_to_run' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php echo esc_html( intera_copy( 'home_champion__intera_doesn_t_just_give_management' ) ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px">
			<?php
			$intera_benefits = array(
				array(
					'icon'  => 'bell',
					'title' => intera_copy( 'home_champion__know_before_you_re_asked' ),
					'body'  => intera_copy( 'home_champion__see_problems_and_unusual_changes_before' ),
				),
				array(
					'icon'  => 'clock',
					'title' => intera_copy( 'home_champion__spend_less_time_proving_what_s' ),
					'body'  => intera_copy( 'home_champion__reduce_repetitive_reporting_manual_checks_and' ),
				),
				array(
					'icon'  => 'clipboard-check',
					'title' => intera_copy( 'home_champion__bring_problems_with_answers' ),
					'body'  => intera_copy( 'home_champion__see_the_supporting_data_and_understand' ),
				),
				array(
					'icon'  => 'shield-check',
					'title' => intera_copy( 'home_champion__show_that_your_area_is_under' ),
					'body'  => intera_copy( 'home_champion__give_management_clear_and_consistent_visibility' ),
				),
				array(
					'icon'  => 'repeat',
					'title' => intera_copy( 'home_champion__make_improvements_that_last' ),
					'body'  => intera_copy( 'home_champion__turn_the_checks_knowledge_and_working' ),
				),
			);

			foreach ( $intera_benefits as $intera_benefit ) {
				ob_start();
				?>
				<div style="color: var(--teal-600); margin-bottom: 12px"><?php intera_icon( $intera_benefit['icon'], array( 'size' => 20 ) ); ?></div>
				<div style="font-size: var(--text-md); font-weight: 600; line-height: 1.4"><?php echo esc_html( $intera_benefit['title'] ); ?></div>
				<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 8px"><?php echo esc_html( $intera_benefit['body'] ); ?></p>
				<?php
				$intera_benefit_body = ob_get_clean();

				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content' => $intera_benefit_body,
						'class'   => 'itr-lift',
					)
				);
			}
			?>
			<div style="display: flex; align-items: center; padding: 0 8px">
				<p style="font-size: var(--text-xl); font-weight: 600; line-height: 1.35; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_champion__less_chasing' ) ); ?><br><?php echo esc_html( intera_copy( 'home_champion__fewer_surprises' ) ); ?><br><?php echo esc_html( intera_copy( 'home_champion__more_confidence_in_the_part_of' ) ); ?></p>
			</div>
		</div>
	</div>
</section>

<section id="action" data-screen-label="In action" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(53px, 7vw, 92px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 40px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_in_action__intera_in_action' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_in_action__don_t_just_watch_the_business' ) ); ?></h2>
		</div>
		<?php
		get_template_part(
			'template-parts/components/signal-chain',
			null,
			array(
				'captions' => array(
					'event'          => intera_copy( 'home_in_action__something_important_changed' ),
					'reconciliation' => intera_copy( 'home_in_action__things_that_should_agree_don_t' ),
					'incident'       => intera_copy( 'home_in_action__something_requires_attention_and_action' ),
					'pattern'        => intera_copy( 'home_in_action__understand_what_keeps_happening_and_under' ),
				),
			)
		);
		?>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 40px; align-items: center; margin-top: 52px">
			<div>
				<div style="border-left: 3px solid var(--blue-600); padding-left: 24px; max-width: 520px">
					<p style="font-size: var(--text-2xl); line-height: 1.4; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_in_action__from_something_looks_wrong_to_we' ) ); ?></p>
				</div>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 24px; max-width: 520px"><?php echo esc_html( intera_copy( 'home_in_action__every_item_carries_the_reason_it' ) ); ?></p>
			</div>
			<?php
			get_template_part(
				'template-parts/partials/screenshot-frame',
				null,
				array(
					'attachment' => intera_shot_id( 'shot_signals' ),
					'caption'    => intera_copy( 'home_in_action__attention_queue_what_to_work_on' ),
					'height'     => '420px',
				)
			);
			?>
		</div>
	</div>
</section>

<section id="roles" data-screen-label="Roles" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 14%; top: 22%; width: 900px; height: 900px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-violet) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 760px; margin-bottom: 40px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_roles__intera_roles' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_roles__pre_built_visibility_for_the_parts' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php echo esc_html( intera_copy( 'home_roles__roles_are_ready_made_business_modules' ) ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px">
			<?php
			/*
			 * The five cards are the `role` post type. The Order field decides,
			 * and where it has not been set the cards fall back to the order the
			 * roles were added in rather than to the alphabet — a designed row
			 * reads in the sequence someone chose, and alphabetical is not one.
			 */
			$intera_role_posts = get_posts(
				array(
					'post_type'        => 'role',
					'post_status'      => 'publish',
					'numberposts'      => -1,
					'orderby'          => 'menu_order date',
					'order'            => 'ASC',
					'suppress_filters' => false,
				)
			);

			foreach ( $intera_role_posts as $intera_role_post ) {
				get_template_part(
					'template-parts/partials/role-card',
					null,
					array(
						'post'    => $intera_role_post,
						'variant' => 'main',
					)
				);
			}
			?>
			<div style="display: flex; flex-direction: column; justify-content: center; gap: 18px; padding: 0 8px">
				<p style="font-size: var(--text-xl); font-weight: 600; line-height: 1.35; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_roles__different_responsibilities_one_operating_picture' ) ); ?></p>
				<div>
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'      => intera_copy( 'home_roles__see_all_roles' ),
							'href'       => $intera_roles_url,
							'variant'    => 'secondary',
							'icon_right' => 'arrow-right',
						)
					);
					?>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="it" data-screen-label="Working with IT" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(53px, 7vw, 92px) clamp(20px, 5vw, 24px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(340px, 100%), 1fr)); gap: 52px; align-items: start">
		<div>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_working_with_it__working_with_existing_it' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_working_with_it__your_systems_stay_intera_makes_them' ) ); ?></h2>
			<div style="display: flex; flex-direction: column; gap: 0; margin-top: 28px; border-top: 1px solid var(--border-hairline)">
				<?php
				$intera_it_facts = array(
					array( 'database', intera_copy( 'home_working_with_it__erp_crm_billing_and_others_remain' ) ),
					array( 'plug', intera_copy( 'home_working_with_it__intera_connects_to_them_never_replacing' ) ),
					array( 'lock', intera_copy( 'home_working_with_it__it_is_responsible_for_access_to' ) ),
					array( 'sliders-horizontal', intera_copy( 'home_working_with_it__business_decides_which_metrics_events_incidents' ) ),
					array( 'route-off', intera_copy( 'home_working_with_it__no_company_wide_transformation_project' ) ),
				);

				foreach ( $intera_it_facts as $intera_it_fact ) :
					?>
					<div style="display: flex; gap: 14px; padding: 16px 0; border-bottom: 1px solid var(--border-hairline)">
						<?php
						intera_icon(
							$intera_it_fact[0],
							array(
								'size'  => 16,
								'color' => 'var(--ink-400)',
							)
						);
						?>
						<span style="font-size: var(--text-base); line-height: 1.55; color: var(--ink-700)"><?php echo esc_html( $intera_it_fact[1] ); ?></span>
					</div>
					<?php
				endforeach;
				?>
			</div>
			<p style="font-size: var(--text-lg); line-height: 1.55; color: var(--ink-900); font-weight: 500; margin-top: 26px; max-width: 520px"><?php echo esc_html( intera_copy( 'home_working_with_it__business_teams_know_what_they_need' ) ); ?></p>
		</div>
		<?php
		get_template_part(
			'template-parts/partials/screenshot-frame',
			null,
			array(
				'attachment' => intera_shot_id( 'shot_it' ),
				'caption'    => intera_copy( 'home_working_with_it__dependencies_vendors_parts_external_commitments' ),
				'height'     => '440px',
			)
		);
		?>
	</div>
</section>

<section data-screen-label="Start small" style="background: var(--surface-page); border-top: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(46px, 7vw, 80px) clamp(20px, 5vw, 24px)">
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 48px; align-items: start">
			<div>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_start_small__start_small' ) ); ?></div>
				<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_start_small__start_with_one_real_problem' ) ); ?></h2>
				<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px; max-width: 480px"><?php echo esc_html( intera_copy( 'home_start_small__do_not_start_by_implementing_intera' ) ); ?></p>
				<div style="margin-top: 28px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label' => intera_copy( 'home_start_small__bring_us_a_real_problem' ),
							'href'  => $intera_request_url,
							'size'  => 'lg',
						)
					);
					?>
				</div>
			</div>
			<div style="display: flex; flex-direction: column; gap: 10px">
				<?php
				$intera_symptoms = array(
					intera_copy( 'home_start_small__billing_and_usage_do_not_correspond' ),
					intera_copy( 'home_start_small__the_problem_is_detected_too_late' ),
					intera_copy( 'home_start_small__the_same_exceptions_are_constantly_checked' ),
					intera_copy( 'home_start_small__a_manager_gathers_the_same_data' ),
				);

				$intera_symptom_number = 0;

				foreach ( $intera_symptoms as $intera_symptom ) :
					++$intera_symptom_number;
					?>
					<div class="itr-row" style="--itr-bg: var(--surface-sunken); --itr-edge: var(--border-card); display: flex; gap: 14px; align-items: flex-start; border-radius: var(--radius-md); padding: 16px 18px">
						<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-400); padding-top: 2px"><?php echo esc_html( sprintf( '%02d', $intera_symptom_number ) ); ?></span>
						<span style="font-size: var(--text-base); color: var(--ink-800); line-height: 1.5"><?php echo esc_html( $intera_symptom ); ?></span>
					</div>
					<?php
				endforeach;
				?>
			</div>
		</div>
	</div>
</section>

<section id="pricing" data-screen-label="Pricing" style="background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 640px; margin: 0 auto 44px; text-align: center">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_pricing__pricing' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_pricing__start_free_pay_when_intera_is' ) ); ?></h2>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 20px; align-items: stretch">
			<?php
			// The three cards are the `plan` post type, ordered by the Order field.
			$intera_plan_posts = get_posts(
				array(
					'post_type'        => 'plan',
					'post_status'      => 'publish',
					'numberposts'      => -1,
					'orderby'          => 'menu_order date',
					'order'            => 'ASC',
					'suppress_filters' => false,
				)
			);

			$intera_plan_index = 0;

			foreach ( $intera_plan_posts as $intera_plan_post ) {
				/*
				 * The evaluation plan is the only card whose target differs
				 * between the two placements: here it sends the reader on to the
				 * pricing page, on the pricing page its own meta sends them to
				 * the request form. It is the first card in the ladder, so the
				 * override is keyed on position rather than on a plan's title.
				 */
				$intera_plan_args = array( 'post' => $intera_plan_post );

				if ( 0 === $intera_plan_index && '' !== $intera_pricing_url ) {
					$intera_plan_args['cta_url'] = $intera_pricing_url;
				}

				get_template_part( 'template-parts/partials/plan-card', null, $intera_plan_args );

				++$intera_plan_index;
			}
			?>
		</div>
	</div>
</section>

<section id="early" data-screen-label="Early Adopter" style="position: relative; overflow: hidden; background: var(--ink-900)">
	<div aria-hidden="true" style="position: absolute; left: 18%; top: 30%; width: 900px; height: 900px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-blue-dark) 0%, transparent 66%)"></div>
	<div aria-hidden="true" style="position: absolute; left: 88%; top: 90%; width: 720px; height: 720px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-teal-dark) 0%, transparent 66%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-200); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_early_adopter__early_adopter_offer' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--white)"><?php echo esc_html( intera_copy( 'home_early_adopter__help_shape_intera_around_a_real' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: rgba(255,255,255,.72); margin-top: 16px"><?php echo esc_html( intera_copy( 'home_early_adopter__we_are_looking_for_a_small' ) ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 20px; margin-top: 40px">
			<?php
			/*
			 * Both panels resolve to the CSS defaults for --itr-bg
			 * (rgba(255,255,255,.05)) and --itr-edge (var(--border-inverse)), so
			 * only the radius and the padding stay inline.
			 */
			?>
			<div class="itr-panel" style="border-radius: var(--radius-card); padding: 24px">
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: rgba(255,255,255,.45); margin-bottom: 16px"><?php echo esc_html( intera_copy( 'home_early_adopter__early_adopters_receive' ) ); ?></div>
				<div style="display: flex; flex-direction: column; gap: 10px; font-size: var(--text-md); color: rgba(255,255,255,.82); line-height: 1.5">
					<span><?php echo esc_html( intera_copy( 'home_early_adopter__intera_free_for_the_first_12' ) ); ?></span>
					<span><?php echo esc_html( intera_copy( 'home_early_adopter__custom_onboarding' ) ); ?></span>
					<span><?php echo esc_html( intera_copy( 'home_early_adopter__direct_contact_with_the_intera_team' ) ); ?></span>
					<span><?php echo esc_html( intera_copy( 'home_early_adopter__priority_support' ) ); ?></span>
					<span><?php echo esc_html( intera_copy( 'home_early_adopter__influence_over_product_development' ) ); ?></span>
					<span><?php echo esc_html( intera_copy( 'home_early_adopter__help_setting_up_your_first_real' ) ); ?></span>
				</div>
			</div>
			<div class="itr-hl-panel" style="border-radius: var(--radius-card); padding: 24px">
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: rgba(255,255,255,.45); margin-bottom: 16px"><?php echo esc_html( intera_copy( 'home_early_adopter__we_expect_in_return' ) ); ?></div>
				<div style="display: flex; flex-direction: column; gap: 10px; font-size: var(--text-md); color: rgba(255,255,255,.82); line-height: 1.5">
					<span><?php echo esc_html( intera_copy( 'home_early_adopter__a_real_business_case' ) ); ?></span>
					<span><?php echo esc_html( intera_copy( 'home_early_adopter__feedback' ) ); ?></span>
					<span><?php echo esc_html( intera_copy( 'home_early_adopter__readiness_to_work_together_and_verify' ) ); ?></span>
				</div>
				<div style="margin-top: 26px; padding-top: 20px; border-top: 1px solid var(--border-inverse)">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'   => intera_copy( 'home_early_adopter__i_have_a_problem_intera_could' ),
							'href'    => $intera_request_url,
							'variant' => 'inverse',
							'size'    => 'lg',
							'block'   => true,
						)
					);
					?>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="partners" data-screen-label="Partners" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 48px; align-items: start">
		<div>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_partners__partners_and_resellers' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_partners__turn_your_industry_knowledge_into_repeatable' ) ); ?></h2>
			<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 18px; max-width: 520px"><?php echo esc_html( intera_copy( 'home_partners__for_systems_integrators_and_consultants_who' ) ); ?></p>
			<p style="font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900); margin-top: 22px"><?php echo esc_html( intera_copy( 'home_partners__solve_once_adapt_deploy_again' ) ); ?></p>
			<div style="margin-top: 26px">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'      => intera_copy( 'home_partners__become_an_intera_partner' ),
						'href'       => $intera_request_url,
						'variant'    => 'secondary',
						'size'       => 'lg',
						'icon_right' => 'arrow-right',
					)
				);
				?>
			</div>
		</div>
		<div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; align-content: start">
			<?php
			/*
			 * One colour across the row, and not the export's.
			 *
			 * The handoff tints these six individually — two of them borrow the
			 * signal palette, three stay `--ink-500`, one is `--blue-600` — and
			 * on the page that reads as a rendering fault rather than a
			 * distinction, because the six are one kind of thing: what a partner
			 * gets to package. The design system is explicit that colour answers
			 * "what kind of thing is this?", so a signal colour on a tile that is
			 * not a signal is out of spec here, however it looks in isolation.
			 *
			 * Unified upwards rather than down: `--blue-600` was already on the
			 * first tile, and dropping everything to `--ink-500` would take the
			 * last colour out of the band entirely.
			 */
			$intera_tile_icon_color = 'var(--blue-600)';

			$intera_tiles = array(
				array( 'layers', intera_copy( 'home_partners__roles' ) ),
				array( 'scale', intera_copy( 'home_partners__reconciliations' ) ),
				array( 'sliders-horizontal', intera_copy( 'home_partners__business_logic' ) ),
				array( 'git-branch', intera_copy( 'home_partners__patterns' ) ),
				array( 'plug', intera_copy( 'home_partners__integrations' ) ),
				array( 'package', intera_copy( 'home_partners__market_packages' ) ),
			);

			foreach ( $intera_tiles as $intera_tile ) :
				?>
				<div class="itr-tile" style="display: flex; align-items: center; gap: 10px; white-space: nowrap; border-radius: var(--radius-md); padding: 16px; font-size: var(--text-md); color: var(--ink-800)">
					<?php
					intera_icon(
						$intera_tile[0],
						array(
							'size'  => 16,
							'color' => $intera_tile_icon_color,
						)
					);
					echo esc_html( $intera_tile[1] );
					?>
				</div>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<?php
get_footer();
