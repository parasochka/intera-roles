<?php
/**
 * Front page — the home screen (`_design/01-main.dc.html`).
 *
 * Thirteen sections. Eleven of them are the export's, in the export's order:
 * Hero, Problem, How it works, Champion, In action, Roles, Working with IT,
 * Start small, Pricing, Early Adopter, Partners. Two are not in the export and
 * say so where they stand — "Sales and account management" under the hero, and
 * "Your data" under Working with IT.
 *
 * Two bands have moved on from the handoff as well. The Sysadmin Package now
 * closes the Roles band rather than the Working with IT one, and "In action"
 * renders one of two versions: the attention reading by default, the export's
 * own Event → Pattern chain behind a Customizer switch. Both are commented at
 * the point they happen.
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
			$intera_hero_status = trim( (string) intera_option( 'hero_status' ) );
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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--text-inverse-muted); margin-top: 30px"><?php echo esc_html( intera_copy( 'home_hero__your_business_clearly' ) ); ?></div>
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
								'color' => 'var(--text-inverse-muted)',
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
				<span style="font-size: var(--text-2xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--text-inverse-muted)"><?php echo esc_html( intera_copy( 'home_hero__reads_from' ) ); ?></span>
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
				<span style="font-family: var(--font-mono); font-size: var(--text-2xs); color: var(--text-muted)">09:14 UTC</span>
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

<?php
/*
 * Sales and account management.
 *
 * Not in the export. It sits directly under the hero because the people it
 * names are the first audience the site is aimed at, and the band before this
 * one — "This will feel familiar" — states the problem in the abstract: several
 * systems, pieces of one picture. This states it as the four questions one
 * person actually has to answer before a customer call, which is the same
 * problem with a name on it.
 *
 * The questions are the right column for the same reason the source rows are
 * the right column in the band below: the left says what the section is, the
 * right shows the thing itself.
 */
?>
<section id="sales" data-screen-label="Sales and account management" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 52px; align-items: start">
		<div>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_sales__sales_and_account_management' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_sales__know_what_was_promised_and_whether' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px; max-width: 520px"><?php echo esc_html( intera_copy( 'home_sales__sales_and_account_managers_often_need' ) ); ?></p>
			<div style="display: flex; flex-direction: column; gap: 14px; max-width: 520px; margin-top: 24px">
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-700)"><?php echo esc_html( intera_copy( 'home_sales__the_agreement_may_be_in_crm' ) ); ?></p>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-900); font-weight: 500"><?php echo esc_html( intera_copy( 'home_sales__intera_brings_those_signals_together_and' ) ); ?></p>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600)"><?php echo esc_html( intera_copy( 'home_sales__it_does_not_replace_your_crm' ) ); ?></p>
			</div>
		</div>
		<div style="display: flex; flex-direction: column; gap: 10px">
			<?php
			/*
			 * Four questions, each one from a different system. The icon names
			 * where the answer lives rather than decorating the question, which
			 * is why the fourth is a checklist and not a question mark.
			 */
			$intera_sales_questions = array(
				array( 'package', intera_copy( 'home_sales__was_the_equipment_delivered' ) ),
				array( 'plug', intera_copy( 'home_sales__was_the_service_activated' ) ),
				array( 'receipt', intera_copy( 'home_sales__was_the_agreed_price_applied' ) ),
				array( 'clipboard-check', intera_copy( 'home_sales__is_anything_still_outstanding_before_the' ) ),
			);

			foreach ( $intera_sales_questions as $intera_sales_question ) :
				?>
				<div class="itr-row" style="--itr-shadow: var(--shadow-xs); display: flex; gap: 14px; align-items: flex-start; border-radius: var(--radius-md); padding: 16px 18px">
					<span style="flex: none; display: inline-flex; padding-top: 2px">
						<?php
						intera_icon(
							$intera_sales_question[0],
							array(
								'size'  => 16,
								'color' => 'var(--blue-600)',
							)
						);
						?>
					</span>
					<span style="font-size: var(--text-base); line-height: 1.55; color: var(--ink-800); min-width: 0"><?php echo esc_html( $intera_sales_question[1] ); ?></span>
				</div>
				<?php
			endforeach;
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
					'ref'    => intera_copy( 'home_problem__erp_orders' ),
					'dot'    => 'var(--ink-200)',
					'indent' => 0,
				),
				array(
					'icon'   => 'contact',
					'label'  => intera_copy( 'home_problem__crm' ),
					'ref'    => intera_copy( 'home_problem__crm_accounts' ),
					'dot'    => 'var(--ink-200)',
					'indent' => 26,
				),
				array(
					'icon'   => 'receipt',
					'label'  => intera_copy( 'home_problem__billing' ),
					'ref'    => intera_copy( 'home_problem__billing_invoices' ),
					'dot'    => 'var(--status-warning)',
					'indent' => 52,
				),
				array(
					'icon'   => 'table-2',
					'label'  => intera_copy( 'home_problem__spreadsheets' ),
					'ref'    => intera_copy( 'home_problem__ops_checks_xlsx' ),
					'dot'    => 'var(--ink-200)',
					'indent' => 26,
				),
				array(
					'icon'   => 'terminal',
					'label'  => intera_copy( 'home_problem__internal_tools' ),
					'ref'    => intera_copy( 'home_problem__provisioning_api' ),
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
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted); margin-left: auto"><?php echo esc_html( $intera_source['ref'] ); ?></span>
					<span aria-hidden="true" style="width: 8px; height: 8px; border-radius: 999px; background: <?php echo esc_attr( $intera_source['dot'] ); ?>"></span>
				</div>
				<?php
			endforeach;
			?>
			<div style="display: flex; align-items: center; gap: 10px; margin-top: 8px; color: var(--text-muted); font-size: var(--text-xs)">
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
					<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted)"><?php echo esc_html( sprintf( '%02d', $intera_step_number ) ); ?></span>
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

<?php
/*
 * "INTERA in action", in two versions.
 *
 * The export's version is the four-step chain — Event, Reconciliation,
 * Incident, Pattern — and three of those four are capabilities INTERA is
 * being extended towards rather than ones a reader can ask for today. So the
 * band now leads with what the product does now: four kinds of signal, and
 * the person they are brought together around.
 *
 * The chain is not deleted. It belongs to the site rework it was drawn for,
 * and rebuilding it later from the handoff would cost more than keeping it,
 * so it stays behind the Customizer's "Show the Event → Pattern signal
 * chain" (Home page) and comes back untouched with one click.
 *
 * The screenshot is common to both: it is the only thing on this band that
 * shows the product rather than describing it.
 */
$intera_signal_chain = (bool) intera_option( 'home_signal_chain' );
?>
<section id="action" data-screen-label="In action" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(53px, 7vw, 92px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 40px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_in_action__intera_in_action' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( $intera_signal_chain ? 'home_in_action__don_t_just_watch_the_business' : 'home_in_action__intera_turns_data_into_attention' ) ); ?></h2>
		</div>
		<?php
		if ( $intera_signal_chain ) {
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
		}
		?>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 40px; align-items: center; margin-top: <?php echo $intera_signal_chain ? '52px' : '0'; ?>">
			<div>
				<?php if ( $intera_signal_chain ) : ?>
					<div style="border-left: 3px solid var(--blue-600); padding-left: 24px; max-width: 520px">
						<p style="font-size: var(--text-2xl); line-height: 1.4; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_in_action__from_something_looks_wrong_to_we' ) ); ?></p>
					</div>
					<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 24px; max-width: 520px"><?php echo esc_html( intera_copy( 'home_in_action__intera_does_not_try_to_reproduce' ) ); ?></p>
				<?php else : ?>
					<?php
					/*
					 * Four signals, each one a sentence of the same shape: what
					 * it is, and what it tells you. The label is the mono
					 * figure the design system gives a name that has to be read
					 * as a term rather than as prose.
					 */
					$intera_signals = array(
						array( 'gauge', intera_copy( 'home_in_action__metrics' ), intera_copy( 'home_in_action__tell_you_what_changed' ) ),
						array( 'heart-pulse', intera_copy( 'home_in_action__health' ), intera_copy( 'home_in_action__tells_you_what_is_weakening' ) ),
						array( 'scale', intera_copy( 'home_in_action__reconciliations' ), intera_copy( 'home_in_action__tell_you_what_does_not_agree' ) ),
						array( 'search', intera_copy( 'home_in_action__evidence' ), intera_copy( 'home_in_action__tells_you_where_to_investigate' ) ),
					);
					?>
					<div style="display: flex; flex-direction: column; gap: 10px; max-width: 520px">
						<?php foreach ( $intera_signals as $intera_signal ) : ?>
							<div class="itr-row" style="--itr-shadow: var(--shadow-xs); display: flex; gap: 14px; align-items: flex-start; border-radius: var(--radius-md); padding: 14px 18px">
								<span style="flex: none; display: inline-flex; padding-top: 2px">
									<?php
									intera_icon(
										$intera_signal[0],
										array(
											'size'  => 16,
											'color' => 'var(--blue-600)',
										)
									);
									?>
								</span>
								<span style="min-width: 0; font-size: var(--text-base); line-height: 1.55; color: var(--ink-700)">
									<strong style="font-weight: 600; color: var(--ink-900)"><?php echo esc_html( $intera_signal[1] ); ?></strong>
									<?php echo esc_html( $intera_signal[2] ); ?>
								</span>
							</div>
						<?php endforeach; ?>
					</div>
					<p style="font-size: var(--text-lg); line-height: 1.55; color: var(--ink-900); font-weight: 500; margin-top: 26px; max-width: 520px"><?php echo esc_html( intera_copy( 'home_in_action__intera_brings_these_signals_together_around' ) ); ?></p>
				<?php endif; ?>
			</div>
			<?php
			get_template_part(
				'template-parts/partials/screenshot-frame',
				null,
				array(
					'attachment' => intera_shot_id( 'shot_signals' ),
					'caption'    => intera_copy( 'home_in_action__attention_queue_what_to_work_on' ),
					/*
					 * The crop height is the column's own width divided by the
					 * shot's aspect: 534px of column against a 1390x1005 image
					 * is 386px of picture, and a frame taller than that ends
					 * in a white strip the card reads as a mistake. Below the
					 * two-column breakpoint the frame is wider than this, so
					 * `object-fit: cover` crops the bottom instead, which is
					 * what the height is for.
					 */
					'height'     => '386px',
				)
			);
			?>
		</div>
		<?php
		if ( ! $intera_signal_chain ) {
			/*
			 * Where the band is going, stated as such. It names the three
			 * capabilities the export's chain drew as though they were
			 * already here, which is the reason that version is switched off
			 * — a screen the site shows and the product does not offer is a
			 * promise. Said in the future tense it is a roadmap, and the
			 * muted panel is what marks the difference.
			 */
			?>
			<div class="itr-row" style="--itr-bg: var(--surface-sunken); --itr-edge: var(--border-card); display: flex; flex-wrap: wrap; gap: 8px 18px; align-items: baseline; border-radius: var(--radius-md); padding: 18px 20px; margin-top: 40px">
				<span style="flex: none; max-width: 100%; font-family: var(--font-mono); font-size: var(--text-xs); letter-spacing: 0.04em; text-transform: uppercase; color: var(--violet-600)"><?php echo esc_html( intera_copy( 'home_in_action__events_incidents_patterns' ) ); ?></span>
				<span style="flex: 1 1 260px; min-width: 0; font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600)"><?php echo esc_html( intera_copy( 'home_in_action__intera_is_being_extended_beyond_today' ) ); ?></span>
			</div>
			<?php
		}
		?>
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
		</div>
		<?php
		/*
		 * The closing statement is a row under the grid, not a cell inside it.
		 *
		 * The export drew five roles and put this in the sixth cell, which read
		 * as a designed composition only for as long as the count stayed at
		 * five: a sixth role turns the grid into a full 3x2 and leaves this
		 * stranded alone in a third row. Roles are content — an editor adds a
		 * seventh tomorrow — so the section footer is the thing that has to
		 * hold at any count, and a full-width row does. The statement takes the
		 * measure it had; the button sits beside it and drops under it on a
		 * phone.
		 */
		?>
		<div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 18px 32px; margin-top: 32px">
			<p style="flex: 1 1 320px; min-width: 0; font-size: var(--text-xl); font-weight: 600; line-height: 1.35; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_roles__different_responsibilities_one_operating_picture' ) ); ?></p>
			<div style="flex: none">
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
		<?php
		/*
		 * The Sysadmin Package band. It closes the Roles band rather than
		 * standing as one of its cards, because it is an offer and not one
		 * more role: the accent rule and the chip are what carry "free with
		 * every plan" at a glance, and the text below is the same paragraph
		 * an editor can rewrite from the page's own copy box. It sits under
		 * the five roles because it is the one of them a reader can have
		 * today, at no cost, which is the wrong thing to bury further down.
		 */
		ob_start();
		?>
		<div style="display: flex; flex-wrap: wrap; gap: 28px; align-items: start">
			<div style="flex: 0 1 260px; min-width: 0">
				<?php
				get_template_part(
					'template-parts/components/badge',
					null,
					array(
						'text' => intera_copy( 'home_sysadmin_package__included_free_with_every_plan' ),
						'tone' => 'ok',
						'icon' => 'check',
					)
				);
				?>
				<h3 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.25; color: var(--ink-900); margin-top: 14px"><?php echo esc_html( intera_copy( 'home_sysadmin_package__sysadmin_package' ) ); ?></h3>
			</div>
			<div style="flex: 1 1 min(100%, 420px); min-width: 0">
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-700)"><?php echo esc_html( intera_copy( 'home_sysadmin_package__the_free_sysadmin_package_gives_it' ) ); ?></p>
			</div>
		</div>
		<?php
		$intera_sysadmin_panel = ob_get_clean();

		get_template_part(
			'template-parts/components/card',
			null,
			array(
				'content'     => $intera_sysadmin_panel,
				'padding'     => 'loose',
				'elevated'    => true,
				'accent'      => 'var(--blue-600)',
				'accent_line' => 'var(--border-default)',
				'style'       => 'margin-top: 44px',
			)
		);
		?>
	</div>
</section>

<section id="it" data-screen-label="Working with IT" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(53px, 7vw, 92px) clamp(20px, 5vw, 24px)">
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(340px, 100%), 1fr)); gap: 52px; align-items: start">
			<div>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_working_with_it__working_with_existing_it' ) ); ?></div>
				<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'home_working_with_it__your_systems_remain_in_control' ) ); ?></h2>
				<div style="display: flex; flex-direction: column; gap: 0; margin-top: 28px; border-top: 1px solid var(--border-hairline)">
					<?php
					$intera_it_facts = array(
						array( 'database', intera_copy( 'home_working_with_it__intera_does_not_replace_your_erp' ) ),
						array( 'eye', intera_copy( 'home_working_with_it__connections_can_be_configured_read_only' ) ),
						array( 'lock', intera_copy( 'home_working_with_it__intera_does_not_need_permission_to' ) ),
						array( 'package', intera_copy( 'home_working_with_it__local_installation_is_available_from' ) ),
						array( 'shield-check', intera_copy( 'home_working_with_it__access_follows_intera_roles_and_permissions' ) ),
					);

					foreach ( $intera_it_facts as $intera_it_fact ) :
						?>
						<div style="display: flex; gap: 14px; padding: 16px 0; border-bottom: 1px solid var(--border-hairline)">
							<?php
							intera_icon(
								$intera_it_fact[0],
								array(
									'size'  => 16,
									'color' => 'var(--text-muted)',
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
					'caption'    => intera_copy( 'home_working_with_it__dependencies_suppliers_parts_external_commitments' ),
					// 528px of column against a 1371x977 image. See the note above.
					'height'     => '376px',
				)
			);
			?>
		</div>
	</div>
</section>

<?php
/*
 * "Your data stays under your control."
 *
 * Not in the export, and dark where the band above it is light. That is the
 * point: the band above says how INTERA connects to what a company already
 * runs, and this one answers the question that follows it — who then holds
 * the data. Three panels rather than a fact list, because each one is a
 * position the company takes and not a bullet, and the line under them is
 * what the whole band is for.
 *
 * The `--ink-900` ground is the same one the Early Adopter band uses, so the
 * inverse tokens are already the design system's: `--text-inverse-muted` for
 * the label, `--white` for the heading, and `.itr-panel` for the three
 * surfaces, whose resting background and edge are the CSS defaults.
 */
?>
<section id="data" data-screen-label="Your data" style="position: relative; overflow: hidden; background: var(--ink-900)">
	<div aria-hidden="true" style="position: absolute; left: 84%; top: 20%; width: 860px; height: 860px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-teal-dark) 0%, transparent 66%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-200); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'home_data_control__your_data' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--white)"><?php echo esc_html( intera_copy( 'home_data_control__your_data_stays_under_your_control' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: rgba(255,255,255,.72); margin-top: 16px"><?php echo esc_html( intera_copy( 'home_data_control__intera_is_designed_to_work_with' ) ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px; margin-top: 40px">
			<?php
			$intera_data_positions = array(
				array(
					'icon'  => 'eye',
					'title' => intera_copy( 'home_data_control__read_only_by_design' ),
					'body'  => intera_copy( 'home_data_control__intera_can_connect_to_existing_erp' ),
				),
				array(
					'icon'  => 'database',
					'title' => intera_copy( 'home_data_control__your_data_stays_where_intera_runs' ),
					'body'  => intera_copy( 'home_data_control__for_customer_deployments_intera_can_run' ),
				),
				array(
					'icon'  => 'lock',
					'title' => intera_copy( 'home_data_control__no_routine_access_for_the_intera' ),
					'body'  => intera_copy( 'home_data_control__intera_support_does_not_require_permanent' ),
				),
			);

			foreach ( $intera_data_positions as $intera_data_position ) :
				?>
				<div class="itr-panel" style="border-radius: var(--radius-card); padding: 24px">
					<div style="color: var(--blue-200); margin-bottom: 12px"><?php intera_icon( $intera_data_position['icon'], array( 'size' => 20 ) ); ?></div>
					<div style="font-size: var(--text-md); font-weight: 600; line-height: 1.4; color: var(--white)"><?php echo esc_html( $intera_data_position['title'] ); ?></div>
					<p style="font-size: var(--text-sm); line-height: 1.6; color: rgba(255,255,255,.72); margin-top: 8px"><?php echo esc_html( $intera_data_position['body'] ); ?></p>
				</div>
				<?php
			endforeach;
			?>
		</div>
		<p style="font-size: var(--text-lg); line-height: 1.55; font-weight: 500; color: var(--white); margin-top: 34px; max-width: 760px"><?php echo esc_html( intera_copy( 'home_data_control__your_systems_remain_yours_your_credentials' ) ); ?></p>
	</div>
</section>

<section data-screen-label="Start small" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 16%; top: 24%; width: 860px; height: 860px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-blue) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(46px, 7vw, 80px) clamp(20px, 5vw, 24px)">
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
						<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted); padding-top: 2px"><?php echo esc_html( sprintf( '%02d', $intera_symptom_number ) ); ?></span>
						<span style="font-size: var(--text-base); color: var(--ink-800); line-height: 1.5"><?php echo esc_html( $intera_symptom ); ?></span>
					</div>
					<?php
				endforeach;
				?>
			</div>
		</div>
	</div>
</section>

<section id="pricing" data-screen-label="Pricing" style="background: var(--surface-page)">
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
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--text-inverse-muted); margin-bottom: 16px"><?php echo esc_html( intera_copy( 'home_early_adopter__early_adopters_receive' ) ); ?></div>
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
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--text-inverse-muted); margin-bottom: 16px"><?php echo esc_html( intera_copy( 'home_early_adopter__we_expect_in_return' ) ); ?></div>
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
			<?php
			/*
			 * Three paragraphs where the export drew one. The band used to open an
			 * intake — "INTERA lets you turn your industry expertise into:" reading
			 * across into the six tiles — and during Private Beta there is no intake
			 * to open: who INTERA works with, that participation is invitation-only
			 * for now, and where to write are three separate statements, so they are
			 * three separate fields rather than one paragraph an editor has to keep
			 * whole.
			 */
			foreach ( array(
				'home_partners__intera_is_designed_to_work_with',
				'home_partners__during_private_beta_partner_participation_is',
			) as $intera_partners_line ) :
				?>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 18px; max-width: 520px"><?php echo esc_html( intera_copy( $intera_partners_line ) ); ?></p>
				<?php
			endforeach;

			/*
			 * The third one names the route, so it carries a link rather than
			 * standing as text: "contact us directly" with nowhere to go is the
			 * one thing this band must not say now that the button below it is
			 * held back. The destination is the contacts page — which publishes
			 * the direct contact and carries the "Partner or reseller" card —
			 * and the sentence falls back to plain text when that page is gone,
			 * so a missing page costs the link and never the paragraph.
			 */
			$intera_partners_route = intera_copy( 'home_partners__contact_us_directly' );

			if ( '' !== trim( (string) $intera_contacts_url ) && '' !== trim( (string) $intera_partners_route ) ) {
				$intera_partners_route = '<a class="itr-link-strong" href="' . esc_url( $intera_contacts_url ) . '">' . esc_html( $intera_partners_route ) . '</a>';
			} else {
				$intera_partners_route = esc_html( $intera_partners_route );
			}
			?>
			<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 18px; max-width: 520px">
				<?php
				/* translators: %s: link to the contacts page, labelled "contact us directly". */
				echo wp_kses_post( intera_copy_format( 'home_partners__for_partnership_enquiries_please_s', $intera_partners_route ) );
				?>
			</p>
			<p style="font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900); margin-top: 22px"><?php echo esc_html( intera_copy( 'home_partners__solve_once_adapt_deploy_again' ) ); ?></p>
			<?php
			/*
			 * The partner CTA is held back until the public launch, and the markup
			 * stays because it is coming back with it. A button that invites
			 * applications promises an intake, and the paragraph above it says the
			 * opposite — participation is by invitation only while the beta is
			 * private. Which of the two is true is a position the site takes, not
			 * a fact about the code, so the switch is the Customizer's
			 * (Home page → "Show the partner call to action") and turning the
			 * programme on costs no deploy. The label and destination are left
			 * alone meanwhile, so it comes back exactly as it went.
			 */
			if ( intera_option( 'partners_cta' ) ) :
				?>
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
				<?php
			endif;
			?>
		</div>
		<div>
			<?php
			/*
			 * A figure, where the export drew a list.
			 *
			 * The export's right column was six tiles naming what a partner
			 * packages and reuses — Roles, Reconciliations, Business logic,
			 * Patterns, Integrations, Role packages. During the private beta
			 * there is no partner programme to enumerate, and a specific list
			 * of what a partner would get reads as an offer this band spends
			 * its own left column withdrawing. So the six went, and their copy
			 * keys with them.
			 *
			 * What replaces them says the same thing the band's closing line
			 * says and nothing more: "Solve once. Adapt. Deploy again." One
			 * solved thing at the top, three deployments under it, drawn in
			 * the brand's own nested-square geometry — the hero's mark, at the
			 * scale of a diagram. No words, so nothing here has to be edited,
			 * translated or walked back when the programme does open.
			 *
			 * `aria-hidden` and no accessible name: the figure carries no
			 * information the paragraphs beside it do not already carry, and
			 * announcing it would only interrupt them.
			 */
			?>
			<div class="itr-panel" style="--itr-bg: var(--surface-sunken); --itr-edge: var(--border-card); border-radius: var(--radius-card); padding: clamp(20px, 4vw, 34px); display: grid; place-items: center">
				<svg viewBox="0 0 320 260" width="100%" style="max-width: 380px; height: auto; display: block" fill="none" aria-hidden="true" focusable="false">
					<?php
					/*
					 * Every colour is an inline `style`, not a presentation
					 * attribute. `fill="var(--blue-600)"` is the shorter way to write
					 * it and not a reliable one: a presentation attribute is the
					 * lowest-priority style there is, and engines disagree about
					 * resolving a custom property inside one. A `style` attribute is
					 * ordinary CSS everywhere, which is also how the rest of this
					 * template carries the tokens.
					 */
					?>
					<defs>
						<radialGradient id="itr-partners-wash" cx="50%" cy="30%" r="60%">
							<stop offset="0%" style="stop-color: var(--blue-100); stop-opacity: .9"></stop>
							<stop offset="100%" style="stop-color: var(--blue-100); stop-opacity: 0"></stop>
						</radialGradient>
					</defs>
					<circle cx="160" cy="78" r="118" fill="url(#itr-partners-wash)"></circle>

					<?php // Solved once: the brand mark's two offset squares, filled in. ?>
					<rect x="116" y="26" width="66" height="66" rx="12" style="fill: none; stroke: var(--blue-600); stroke-width: 1.5"></rect>
					<rect x="138" y="48" width="66" height="66" rx="12" style="fill: var(--blue-50); stroke: var(--blue-600); stroke-width: 1.5"></rect>
					<rect x="150" y="60" width="42" height="42" rx="7" style="fill: var(--blue-600)"></rect>

					<?php // Down, then out to each deployment. ?>
					<path d="M160 114 V 150 M 58 150 H 262 M 58 150 V 176 M 160 150 V 176 M 262 150 V 176" style="fill: none; stroke: var(--ink-200); stroke-width: 1.5; stroke-linecap: round"></path>

					<?php // Deployed again: the same shape, adapted — drawn open, three times. ?>
					<rect x="24" y="176" width="68" height="60" rx="12" style="fill: var(--white); stroke: var(--border-default); stroke-width: 1.5"></rect>
					<rect x="42" y="196" width="32" height="20" rx="5" style="fill: var(--blue-100)"></rect>
					<rect x="126" y="176" width="68" height="60" rx="12" style="fill: var(--white); stroke: var(--border-default); stroke-width: 1.5"></rect>
					<rect x="144" y="196" width="32" height="20" rx="5" style="fill: var(--blue-100)"></rect>
					<rect x="228" y="176" width="68" height="60" rx="12" style="fill: var(--white); stroke: var(--border-default); stroke-width: 1.5"></rect>
					<rect x="246" y="196" width="32" height="20" rx="5" style="fill: var(--blue-100)"></rect>
				</svg>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
