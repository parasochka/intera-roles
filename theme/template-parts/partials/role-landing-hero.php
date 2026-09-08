<?php
/**
 * Partial — the opening band of a role landing page.
 *
 * The three `/roles/*` pages are not export screens, and they are campaign
 * landings rather than parts of the site's argument: someone arrives on one of
 * them from an advert, so the first screen has to say whose page it is, what it
 * promises and where to go, before anything else. That is one band, and all
 * three pages want exactly the same one — so it lives here rather than three
 * times over.
 *
 *     get_template_part( 'template-parts/partials/role-landing-hero', null, array(
 *         'eyebrow'          => 'Sysadmin Role',
 *         'headline'         => '',            // '' falls back to the page title
 *         'lede'             => '',            // '' falls back to the page content
 *         'primary_label'    => '', 'primary_url'   => '',
 *         'secondary_label'  => '', 'secondary_url' => '',
 *         'promise'          => array( array( 'icon' => 'check-circle', 'label' => '…' ) ),
 *     ) );
 *
 * The ground is `page-product.php`'s header band, which is `front-page.php`'s
 * hero band: one dark opening for the whole site, quoted rather than redrawn.
 *
 * A button with no destination renders as an inert `<button>`, so each pair is
 * dropped rather than shown when its URL has not resolved — the same rule the
 * header applies to its own call to action.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'         => '',
		'headline'        => '',
		'lede'            => '',
		'primary_label'   => '',
		'primary_url'     => '',
		'secondary_label' => '',
		'secondary_url'   => '',
		'promise'         => array(),
	)
);

$intera_rl_eyebrow  = trim( (string) $args['eyebrow'] );
$intera_rl_headline = trim( (string) $args['headline'] );
$intera_rl_lede     = trim( (string) $args['lede'] );

if ( '' === $intera_rl_headline ) {
	$intera_rl_headline = (string) get_the_title();
}

$intera_rl_primary   = ( '' !== trim( (string) $args['primary_label'] ) && '' !== trim( (string) $args['primary_url'] ) );
$intera_rl_secondary = ( '' !== trim( (string) $args['secondary_label'] ) && '' !== trim( (string) $args['secondary_url'] ) );
?>
<section data-screen-label="Role header" style="position: relative; overflow: hidden; background: var(--ink-950)">
	<?php
	/*
	 * The home page's hero ground, verbatim: the 1160px column rules, the two
	 * ambient washes and the corner lock-up mark. See the Hero section of
	 * front-page.php, which this is quoted from.
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
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(37px, 7vw, 64px) clamp(20px, 5vw, 24px) clamp(40px, 7vw, 68px)">
		<?php intera_breadcrumbs( array(), array( 'inverse' => true ) ); ?>
		<div style="margin-top: 24px; max-width: 660px">
			<?php if ( '' !== $intera_rl_eyebrow ) : ?>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-200); margin-bottom: 14px"><?php echo esc_html( $intera_rl_eyebrow ); ?></div>
			<?php endif; ?>
			<h1 style="font-size: clamp(30px, 3.4vw, 42px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.14; color: var(--white); text-wrap: pretty"><?php echo esc_html( $intera_rl_headline ); ?></h1>
			<?php
			/*
			 * The page's own content is the lede when it has one, so an editor
			 * can rewrite the opening paragraph in the editor rather than in a
			 * copy field. The copy field is the fallback, not the other way
			 * round — a landing page whose body is empty still opens properly.
			 */
			if ( '' !== trim( (string) get_the_content() ) ) :
				?>
				<div class="intera-prose intera-prose--inverse" style="--itr-prose-max: 560px; margin-top: 20px"><?php the_content(); ?></div>
			<?php elseif ( '' !== $intera_rl_lede ) : ?>
				<p style="font-size: var(--text-lg); line-height: 1.6; color: rgba(255,255,255,.72); margin-top: 20px; max-width: 560px"><?php echo esc_html( $intera_rl_lede ); ?></p>
			<?php endif; ?>
			<?php if ( $intera_rl_primary || $intera_rl_secondary ) : ?>
				<div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 30px">
					<?php
					if ( $intera_rl_primary ) {
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label'   => $args['primary_label'],
								'href'    => $args['primary_url'],
								'variant' => 'inverse',
								'size'    => 'lg',
							)
						);
					}

					if ( $intera_rl_secondary ) {
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label'      => $args['secondary_label'],
								'href'       => $args['secondary_url'],
								'variant'    => 'outlineInverse',
								'size'       => 'lg',
								'icon_right' => 'arrow-right',
							)
						);
					}
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		/*
		 * The promise, under a hairline, in the shape the home hero uses for
		 * its three facts. It is addressed to one person rather than to a
		 * management audience, which is the whole reason these pages exist —
		 * so it sits above the fold and not in a band further down.
		 */
		$intera_rl_promise = array();

		foreach ( (array) $args['promise'] as $intera_rl_item ) {
			$intera_rl_label = isset( $intera_rl_item['label'] ) ? trim( (string) $intera_rl_item['label'] ) : '';

			if ( '' !== $intera_rl_label ) {
				$intera_rl_promise[] = array(
					'icon'  => isset( $intera_rl_item['icon'] ) ? (string) $intera_rl_item['icon'] : 'check',
					'label' => $intera_rl_label,
				);
			}
		}

		if ( $intera_rl_promise ) :
			?>
			<div style="display: flex; flex-wrap: wrap; gap: 18px 26px; margin-top: 38px; padding-top: 22px; border-top: 1px solid rgba(255,255,255,.14)">
				<?php foreach ( $intera_rl_promise as $intera_rl_item ) : ?>
					<span style="display: flex; align-items: flex-start; gap: 9px; flex: 1 1 220px; min-width: 0">
						<span style="flex: none; display: inline-flex; padding-top: 1px; color: var(--green-500)">
							<?php intera_icon( $intera_rl_item['icon'], array( 'size' => 15 ) ); ?>
						</span>
						<span style="min-width: 0; font-size: var(--text-sm); line-height: 1.5; color: rgba(255,255,255,.82)"><?php echo esc_html( $intera_rl_item['label'] ); ?></span>
					</span>
				<?php endforeach; ?>
			</div>
			<?php
		endif;
		?>
	</div>
</section>
