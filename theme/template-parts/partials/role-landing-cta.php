<?php
/**
 * Partial — the closing band of a role landing page.
 *
 * The same ask on all three pages: one problem, one role, one result. It is the
 * only thing on a campaign landing that has to be reached from every scroll
 * depth, so it is the last band on each of them and it is written once here.
 *
 *     get_template_part( 'template-parts/partials/role-landing-cta', null, array(
 *         'eyebrow' => 'Private Beta',
 *         'heading' => 'Bring us one recurring IT problem',
 *         'body'    => 'We will start with one role, one problem and one useful result.',
 *         'label'   => 'Apply for Private Beta',
 *         'url'     => $intera_request_url,
 *     ) );
 *
 * `--ink-900` is the Early Adopter band's own ground on the home page, so the
 * ask closes every page of the site on the same surface. The button drops out
 * rather than rendering an inert `<button>` when nothing carries the request
 * template yet; the heading and the sentence still say what to do.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow' => '',
		'heading' => '',
		'body'    => '',
		'label'   => '',
		'url'     => '',
	)
);

$intera_rlc_eyebrow = trim( (string) $args['eyebrow'] );
$intera_rlc_heading = trim( (string) $args['heading'] );
$intera_rlc_body    = trim( (string) $args['body'] );
$intera_rlc_has_cta = ( '' !== trim( (string) $args['label'] ) && '' !== trim( (string) $args['url'] ) );

if ( '' === $intera_rlc_heading && '' === $intera_rlc_body && ! $intera_rlc_has_cta ) {
	return;
}
?>
<section data-screen-label="Private Beta" style="position: relative; overflow: hidden; background: var(--ink-900)">
	<div aria-hidden="true" style="position: absolute; left: 20%; top: 34%; width: 900px; height: 900px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-blue-dark) 0%, transparent 66%)"></div>
	<div aria-hidden="true" style="position: absolute; left: 86%; top: 92%; width: 720px; height: 720px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-teal-dark) 0%, transparent 66%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(46px, 7vw, 80px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 680px">
			<?php if ( '' !== $intera_rlc_eyebrow ) : ?>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-200); margin-bottom: 14px"><?php echo esc_html( $intera_rlc_eyebrow ); ?></div>
			<?php endif; ?>
			<?php if ( '' !== $intera_rlc_heading ) : ?>
				<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--white)"><?php echo esc_html( $intera_rlc_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( '' !== $intera_rlc_body ) : ?>
				<p style="font-size: var(--text-lg); line-height: 1.6; color: rgba(255,255,255,.72); margin-top: 16px"><?php echo esc_html( $intera_rlc_body ); ?></p>
			<?php endif; ?>
			<?php if ( $intera_rlc_has_cta ) : ?>
				<div style="margin-top: 30px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'   => $args['label'],
							'href'    => $args['url'],
							'variant' => 'inverse',
							'size'    => 'lg',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
