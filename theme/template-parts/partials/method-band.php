<?php
/**
 * Partial — the Method band and the "start with one real problem" box under it.
 *
 * Two bands that always travel together and always say the same thing: how an
 * engagement actually runs, what a customer is left holding afterwards, and the
 * one-line ask that follows from both. The product page has carried them since
 * the export; the three `/roles/*` landings now close on them too, so a reader
 * who arrives on one job's page from an advert leaves the site knowing how the
 * work is done rather than only what it watches.
 *
 *     get_template_part( 'template-parts/partials/method-band', null, array(
 *         'source_id' => intera_page_id( 'product' ),  // 0 = this page's own copy
 *         'cta_url'   => $intera_request_url,
 *     ) );
 *
 * **The words belong to the product page, wherever the band is drawn.** These
 * copy keys are in the product group of `inc/copy-defaults.php`, which means
 * the product page is the one place an editor can edit them; a role landing
 * reading them from its own post would silently answer with the registered
 * default and drift the day somebody rewrote the Method. So `source_id` names
 * the page the words come from and every string here is read against it. With
 * no product page assigned yet it falls back to the current post, which for the
 * product page itself is the same thing.
 *
 * The band is light on both grounds it is used on, and both pages put a dark
 * band directly above it — the export's own alternation, and the reason it
 * lands last rather than in the middle.
 *
 * PORT.md §1: the two Cards own their surface; only padding, radius and the
 * flex/grid tracks are inline here.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'source_id' => 0,
		'cta_url'   => '',
	)
);

$intera_mb_source = (int) $args['source_id'];
$intera_mb_source = $intera_mb_source > 0 ? $intera_mb_source : (int) get_the_ID();
$intera_mb_cta    = trim( (string) $args['cta_url'] );

/** One run of the Method's copy, always read from the page that owns it. */
$intera_mb_copy = static function ( $key ) use ( $intera_mb_source ) {
	return intera_copy( $key, $intera_mb_source );
};
?>
<section id="method" data-screen-label="Method" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 48px; align-items: start">
			<div>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( $intera_mb_copy( 'product_method__intera_method' ) ); ?></div>
				<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( $intera_mb_copy( 'product_method__a_working_system_not_a_set' ) ); ?></h2>
				<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 18px; max-width: 520px"><?php echo esc_html( $intera_mb_copy( 'product_method__the_method_is_a_hands_on' ) ); ?></p>
				<div style="display: flex; flex-direction: column; gap: 0; margin-top: 26px; border-top: 1px solid var(--border-hairline); max-width: 520px">
					<?php
					$intera_method_steps = array(
						$intera_mb_copy( 'product_method__map_the_real_data_flows_not' ),
						$intera_mb_copy( 'product_method__identify_blind_spots_and_the_checks' ),
						$intera_mb_copy( 'product_method__define_metrics_that_reflect_the_operation' ),
						$intera_mb_copy( 'product_method__connect_the_data_sources_with_it' ),
						$intera_mb_copy( 'product_method__build_the_first_roles_and_dashboards' ),
					);

					foreach ( $intera_method_steps as $intera_step_index => $intera_method_step ) :
						?>
						<div style="display: flex; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--border-hairline)"><span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted)"><?php echo esc_html( sprintf( '%02d', $intera_step_index + 1 ) ); ?></span><span style="min-width: 0; font-size: var(--text-md); color: var(--ink-700)"><?php echo esc_html( $intera_method_step ); ?></span></div>
						<?php
					endforeach;
					?>
				</div>
			</div>
			<?php
			ob_start();
			?>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php echo esc_html( $intera_mb_copy( 'product_method__what_you_leave_with' ) ); ?></div>
			<div style="display: flex; flex-direction: column; gap: 10px; margin-top: 16px; font-size: var(--text-md); color: var(--ink-800); line-height: 1.5">
				<?php
				$intera_method_outcomes = array(
					$intera_mb_copy( 'product_method__a_working_intera_environment' ),
					$intera_mb_copy( 'product_method__connected_data_sources' ),
					$intera_mb_copy( 'product_method__defined_metrics_and_business_logic' ),
					$intera_mb_copy( 'product_method__operational_dashboards_in_use' ),
					$intera_mb_copy( 'product_method__visibility_into_issues_you_could_not' ),
				);

				foreach ( $intera_method_outcomes as $intera_method_outcome ) {
					echo '<span>' . esc_html( $intera_method_outcome ) . '</span>';
				}
				?>
			</div>
			<div style="margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--border-hairline)">
				<p style="font-size: var(--text-sm); color: var(--ink-600); line-height: 1.6"><?php echo esc_html( $intera_mb_copy( 'product_method__delivered_over_several_intensive_on_site' ) ); ?></p>
				<?php if ( '' !== $intera_mb_cta ) : ?>
					<div style="margin-top: 16px">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label' => $intera_mb_copy( 'product_method__talk_to_us_about_the_method' ),
								'href'  => $intera_mb_cta,
								'block' => true,
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>
			<?php
			get_template_part(
				'template-parts/components/card',
				null,
				array(
					'content'     => ob_get_clean(),
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
	<div style="max-width: 1160px; margin: 0 auto; padding: 0 clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px)">
		<?php
		// The export writes this box as a bare div with the same surface the Card
		// component draws, plus a 3px --blue-600 top rule; `accent_line` keeps the
		// remaining three edges on --border-card, as the handoff has them.
		ob_start();
		?>
		<div style="min-width: 0">
			<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900); line-height: 1.3"><?php echo esc_html( $intera_mb_copy( 'product_cta__start_with_one_real_problem' ) ); ?></h2>
			<p style="font-size: var(--text-md); color: var(--ink-600); margin-top: 8px"><?php echo esc_html( $intera_mb_copy( 'product_cta__one_role_one_operational_problem_one' ) ); ?></p>
		</div>
		<?php
		if ( '' !== $intera_mb_cta ) {
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label' => $intera_mb_copy( 'product_cta__bring_us_a_real_problem' ),
					'href'  => $intera_mb_cta,
					'size'  => 'lg',
				)
			);
		}

		get_template_part(
			'template-parts/components/card',
			null,
			array(
				'content'     => ob_get_clean(),
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
