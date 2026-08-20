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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: rgba(255,255,255,.42); margin-top: 30px"><?php esc_html_e( 'Your business, clearly', 'intera' ); ?></div>
			<h1 style="font-size: clamp(34px, 4vw, 52px); font-weight: 600; line-height: 1.08; letter-spacing: -0.028em; color: var(--white); margin-top: 14px; max-width: 520px; text-wrap: balance"><?php esc_html_e( 'See what needs attention. Before someone has to ask.', 'intera' ); ?></h1>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: rgba(255,255,255,.66); margin-top: 22px; max-width: 460px"><?php esc_html_e( 'INTERA connects the systems your teams already use and gives each role a clear view of what matters — changes, risks, inconsistencies and trends.', 'intera' ); ?></p>
			<div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 32px">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'   => __( 'Get Early Access', 'intera' ),
						'href'    => $intera_request_url,
						'variant' => 'inverse',
						'size'    => 'lg',
					)
				);

				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'      => __( 'See how INTERA works', 'intera' ),
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
					'route-off'   => __( 'No migration', 'intera' ),
					'lock'        => __( 'Read-only access', 'intera' ),
					'circle-dot'  => __( 'Start with one role', 'intera' ),
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
				<span style="font-size: var(--text-2xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: rgba(255,255,255,.4)"><?php esc_html_e( 'Reads from', 'intera' ); ?></span>
				<span style="display: flex; gap: 14px; flex-wrap: wrap; font-family: var(--font-mono); font-size: var(--text-xs); color: rgba(255,255,255,.58)">
					<span>ERP</span><span>CRM</span><span>Billing</span><span>Excel</span><span><?php esc_html_e( 'Internal tools', 'intera' ); ?></span>
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
			$intera_hero_shot  = (int) intera_option( 'shot_hero' );
			$intera_hero_float = ( $intera_hero_shot > 0 && wp_attachment_is_image( $intera_hero_shot ) )
				? 'itr-float itr-lift'
				: 'itr-lift';

			get_template_part(
				'template-parts/partials/screenshot-frame',
				null,
				array(
					'attachment' => $intera_hero_shot,
					'caption'    => __( 'Fleet Health Overview · Shipmanagement', 'intera' ),
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
			<div style="font-size: var(--text-md); font-weight: 600; color: var(--ink-900); margin-top: 10px; line-height: 1.4"><?php esc_html_e( 'Critical maintenance task overdue', 'intera' ); ?></div>
			<div style="display: flex; align-items: baseline; gap: 8px; margin-top: 10px">
				<span style="font-family: var(--font-mono); font-size: var(--text-2xl); font-weight: 500; color: var(--ink-900); letter-spacing: -0.01em"><?php esc_html_e( '5 days', 'intera' ); ?></span>
				<span style="font-size: var(--text-xs); color: var(--ink-500)"><?php esc_html_e( 'before impact', 'intera' ); ?></span>
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
				esc_html_e( '4th occurrence — always after a delayed spare', 'intera' );
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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'The problem', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'This will feel familiar', 'intera' ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px; max-width: 520px"><?php esc_html_e( 'Your business runs across several systems. Finance sees one part. Operations sees another.', 'intera' ); ?></p>
			<div style="display: flex; flex-direction: column; gap: 14px; max-width: 520px; margin-top: 24px">
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-700)"><?php esc_html_e( 'CRM, billing, ERP, spreadsheets and internal tools each contain pieces of the picture. Problems often become visible only when someone connects those pieces manually.', 'intera' ); ?></p>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-700)"><?php esc_html_e( 'Teams spend time checking, reconciling, explaining and preparing information that already exists somewhere in the business.', 'intera' ); ?></p>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-900); font-weight: 500"><?php esc_html_e( 'INTERA makes that operating picture continuously visible.', 'intera' ); ?></p>
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
					'label'  => __( 'ERP', 'intera' ),
					'ref'    => 'erp.orders',
					'dot'    => 'var(--ink-200)',
					'indent' => 0,
				),
				array(
					'icon'   => 'contact',
					'label'  => __( 'CRM', 'intera' ),
					'ref'    => 'crm.accounts',
					'dot'    => 'var(--ink-200)',
					'indent' => 26,
				),
				array(
					'icon'   => 'receipt',
					'label'  => __( 'Billing', 'intera' ),
					'ref'    => 'billing.invoices',
					'dot'    => 'var(--status-warning)',
					'indent' => 52,
				),
				array(
					'icon'   => 'table-2',
					'label'  => __( 'Spreadsheets', 'intera' ),
					'ref'    => 'ops_checks.xlsx',
					'dot'    => 'var(--ink-200)',
					'indent' => 26,
				),
				array(
					'icon'   => 'terminal',
					'label'  => __( 'Internal tools', 'intera' ),
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
				esc_html_e( 'Pieces of the same picture, checked by hand.', 'intera' );
				?>
			</div>
		</div>
	</div>
</section>

<section id="how" data-screen-label="How it works" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(53px, 7vw, 92px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 40px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'How it works', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Get full visibility without changing how your company operates', 'intera' ); ?></h2>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(290px, 100%), 1fr)); gap: 20px">
			<?php
			$intera_steps = array(
				array(
					'icon'  => 'plug',
					'title' => __( 'Connect your existing systems', 'intera' ),
					'body'  => __( 'INTERA connects to finance, operations, CRM, billing, ERP, Excel and internal systems without replacing them.', 'intera' ),
				),
				array(
					'icon'  => 'scale',
					'title' => __( 'INTERA understands what matters', 'intera' ),
					'body'  => __( 'It applies your business logic and watches changes, risks and inconsistencies across the systems it reads.', 'intera' ),
				),
				array(
					'icon'  => 'eye',
					'title' => __( 'See what needs attention', 'intera' ),
					'body'  => __( 'Managers immediately see what changed, what requires action and where to investigate.', 'intera' ),
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
		<p style="font-size: var(--text-lg); color: var(--ink-700); margin-top: 34px; max-width: 760px; line-height: 1.6"><?php esc_html_e( 'INTERA doesn\'t replace your team — it removes unnecessary manual checking and reporting between systems and people.', 'intera' ); ?></p>
	</div>
</section>

<section data-screen-label="Champion" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 82%; top: 78%; width: 880px; height: 880px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-teal) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 40px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'For the manager who owns the area', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Make your area easier to run', 'intera' ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php esc_html_e( 'INTERA doesn\'t just give management more visibility. It helps you stay on top of the part of the business you\'re responsible for.', 'intera' ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px">
			<?php
			$intera_benefits = array(
				array(
					'icon'  => 'bell',
					'title' => __( 'Know before you\'re asked', 'intera' ),
					'body'  => __( 'See problems and unusual changes before they become questions or escalations.', 'intera' ),
				),
				array(
					'icon'  => 'clock',
					'title' => __( 'Spend less time proving what\'s happening', 'intera' ),
					'body'  => __( 'Reduce repetitive reporting, manual checks and status updates.', 'intera' ),
				),
				array(
					'icon'  => 'clipboard-check',
					'title' => __( 'Bring problems with answers', 'intera' ),
					'body'  => __( 'See the supporting data and understand what requires action.', 'intera' ),
				),
				array(
					'icon'  => 'shield-check',
					'title' => __( 'Show that your area is under control', 'intera' ),
					'body'  => __( 'Give management clear and consistent visibility without preparing another spreadsheet.', 'intera' ),
				),
				array(
					'icon'  => 'repeat',
					'title' => __( 'Make improvements that last', 'intera' ),
					'body'  => __( 'Turn the checks, knowledge and working practices your team already uses into something repeatable and visible across the organization.', 'intera' ),
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
				<p style="font-size: var(--text-xl); font-weight: 600; line-height: 1.35; letter-spacing: -0.01em; color: var(--ink-900)"><?php esc_html_e( 'Less chasing.', 'intera' ); ?><br><?php esc_html_e( 'Fewer surprises.', 'intera' ); ?><br><?php esc_html_e( 'More confidence in the part of the business you own.', 'intera' ); ?></p>
			</div>
		</div>
	</div>
</section>

<section id="action" data-screen-label="In action" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(53px, 7vw, 92px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 40px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'INTERA in action', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Don\'t just watch the business. Catch what matters.', 'intera' ); ?></h2>
		</div>
		<?php
		get_template_part(
			'template-parts/components/signal-chain',
			null,
			array(
				'captions' => array(
					'event'          => __( 'Something important changed.', 'intera' ),
					'reconciliation' => __( 'Things that should agree — don\'t.', 'intera' ),
					'incident'       => __( 'Something requires attention and action.', 'intera' ),
					'pattern'        => __( 'Understand what keeps happening, and under which conditions.', 'intera' ),
				),
			)
		);
		?>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 40px; align-items: center; margin-top: 52px">
			<div>
				<div style="border-left: 3px solid var(--blue-600); padding-left: 24px; max-width: 520px">
					<p style="font-size: var(--text-2xl); line-height: 1.4; letter-spacing: -0.01em; color: var(--ink-900)"><?php esc_html_e( 'From "something looks wrong" to "we know what is happening, why it matters, and what to watch next."', 'intera' ); ?></p>
				</div>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 24px; max-width: 520px"><?php esc_html_e( 'Every item carries the reason it is on the list: what changed, who owns it, when it becomes a problem, and what keeps happening around it.', 'intera' ); ?></p>
			</div>
			<?php
			get_template_part(
				'template-parts/partials/screenshot-frame',
				null,
				array(
					'attachment' => intera_option( 'shot_signals' ),
					'caption'    => __( 'Attention Queue · what to work on first', 'intera' ),
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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'INTERA Roles', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Pre-built visibility for the parts of your business that matter most', 'intera' ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php esc_html_e( 'Roles are ready-made business modules designed around real responsibilities. Each one comes with predefined metrics, logic and automatic issue detection — so you see what\'s happening without building anything from scratch.', 'intera' ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px">
			<?php
			// The five cards are the `role` post type, ordered by the Order field.
			$intera_role_posts = get_posts(
				array(
					'post_type'        => 'role',
					'post_status'      => 'publish',
					'numberposts'      => -1,
					'orderby'          => 'menu_order title',
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
				<p style="font-size: var(--text-xl); font-weight: 600; line-height: 1.35; letter-spacing: -0.01em; color: var(--ink-900)"><?php esc_html_e( 'Different responsibilities. One operating picture.', 'intera' ); ?></p>
				<div>
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'      => __( 'See all Roles', 'intera' ),
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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'Working with existing IT', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Your systems stay. INTERA makes them more useful.', 'intera' ); ?></h2>
			<div style="display: flex; flex-direction: column; gap: 0; margin-top: 28px; border-top: 1px solid var(--border-hairline)">
				<?php
				$intera_it_facts = array(
					array( 'database', __( 'ERP, CRM, billing and others remain your systems of record.', 'intera' ) ),
					array( 'plug', __( 'INTERA connects to them, never replacing anything.', 'intera' ) ),
					array( 'lock', __( 'IT is responsible for access to systems and data.', 'intera' ) ),
					array( 'sliders-horizontal', __( 'Business decides which Metrics, Events, Incidents, Reconciliations and Patterns are important.', 'intera' ) ),
					array( 'route-off', __( 'No company-wide transformation project.', 'intera' ) ),
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
			<p style="font-size: var(--text-lg); line-height: 1.55; color: var(--ink-900); font-weight: 500; margin-top: 26px; max-width: 520px"><?php esc_html_e( 'Business teams know what they need to control. IT knows how the systems work. INTERA gives them a practical place to meet.', 'intera' ); ?></p>
		</div>
		<?php
		get_template_part(
			'template-parts/partials/screenshot-frame',
			null,
			array(
				'attachment' => intera_option( 'shot_it' ),
				'caption'    => __( 'Dependencies · vendors, parts, external commitments', 'intera' ),
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
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'Start small', 'intera' ); ?></div>
				<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Start with one real problem', 'intera' ); ?></h2>
				<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px; max-width: 480px"><?php esc_html_e( 'Do not start by implementing INTERA in your whole company. One role. One operational problem. One working result.', 'intera' ); ?></p>
				<div style="margin-top: 28px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label' => __( 'Bring us a real problem', 'intera' ),
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
					__( 'Billing and usage do not correspond.', 'intera' ),
					__( 'The problem is detected too late.', 'intera' ),
					__( 'The same exceptions are constantly checked by hand.', 'intera' ),
					__( 'A manager gathers the same data from several different systems.', 'intera' ),
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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'Pricing', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Start free. Pay when INTERA is doing real work.', 'intera' ); ?></h2>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 20px; align-items: stretch">
			<?php
			// The three cards are the `plan` post type, ordered by the Order field.
			$intera_plan_posts = get_posts(
				array(
					'post_type'        => 'plan',
					'post_status'      => 'publish',
					'numberposts'      => -1,
					'orderby'          => 'menu_order title',
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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-200); margin-bottom: 14px"><?php esc_html_e( 'Early Adopter offer', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--white)"><?php esc_html_e( 'Help shape INTERA around a real operation', 'intera' ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: rgba(255,255,255,.72); margin-top: 16px"><?php esc_html_e( 'We are looking for a small number of companies and managers ready to use INTERA on their real operational tasks during beta.', 'intera' ); ?></p>
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
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: rgba(255,255,255,.45); margin-bottom: 16px"><?php esc_html_e( 'Early Adopters receive', 'intera' ); ?></div>
				<div style="display: flex; flex-direction: column; gap: 10px; font-size: var(--text-md); color: rgba(255,255,255,.82); line-height: 1.5">
					<span><?php esc_html_e( 'INTERA free for the first 12 months', 'intera' ); ?></span>
					<span><?php esc_html_e( 'Custom onboarding', 'intera' ); ?></span>
					<span><?php esc_html_e( 'Direct contact with the INTERA team', 'intera' ); ?></span>
					<span><?php esc_html_e( 'Priority support', 'intera' ); ?></span>
					<span><?php esc_html_e( 'Influence over product development', 'intera' ); ?></span>
					<span><?php esc_html_e( 'Help setting up your first real use case', 'intera' ); ?></span>
				</div>
			</div>
			<div class="itr-hl-panel" style="border-radius: var(--radius-card); padding: 24px">
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: rgba(255,255,255,.45); margin-bottom: 16px"><?php esc_html_e( 'We expect in return', 'intera' ); ?></div>
				<div style="display: flex; flex-direction: column; gap: 10px; font-size: var(--text-md); color: rgba(255,255,255,.82); line-height: 1.5">
					<span><?php esc_html_e( 'A real business case', 'intera' ); ?></span>
					<span><?php esc_html_e( 'Feedback', 'intera' ); ?></span>
					<span><?php esc_html_e( 'Readiness to work together and verify our solutions', 'intera' ); ?></span>
				</div>
				<div style="margin-top: 26px; padding-top: 20px; border-top: 1px solid var(--border-inverse)">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'   => __( 'I have a problem INTERA could solve', 'intera' ),
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
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php esc_html_e( 'Partners and resellers', 'intera' ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php esc_html_e( 'Turn your industry knowledge into repeatable solutions', 'intera' ); ?></h2>
			<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 18px; max-width: 520px"><?php esc_html_e( 'For systems integrators and consultants who already know their customers\' real problems. INTERA turns that expertise into something you can deploy again.', 'intera' ); ?></p>
			<p style="font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900); margin-top: 22px"><?php esc_html_e( 'Solve once. Adapt. Deploy again.', 'intera' ); ?></p>
			<div style="margin-top: 26px">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'      => __( 'Become an INTERA partner', 'intera' ),
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
			$intera_tiles = array(
				array( 'layers', 'var(--blue-600)', __( 'Roles', 'intera' ) ),
				array( 'scale', 'var(--signal-reconciliation)', __( 'Reconciliations', 'intera' ) ),
				array( 'sliders-horizontal', 'var(--ink-500)', __( 'Business logic', 'intera' ) ),
				array( 'git-branch', 'var(--signal-pattern)', __( 'Patterns', 'intera' ) ),
				array( 'plug', 'var(--ink-500)', __( 'Integrations', 'intera' ) ),
				array( 'package', 'var(--ink-500)', __( 'Market packages', 'intera' ) ),
			);

			foreach ( $intera_tiles as $intera_tile ) :
				?>
				<div class="itr-tile" style="display: flex; align-items: center; gap: 10px; white-space: nowrap; border-radius: var(--radius-md); padding: 16px; font-size: var(--text-md); color: var(--ink-800)">
					<?php
					intera_icon(
						$intera_tile[0],
						array(
							'size'  => 16,
							'color' => $intera_tile[1],
						)
					);
					echo esc_html( $intera_tile[2] );
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
