<?php
/**
 * Partial — "what INTERA can help you see", and what changes because of it.
 *
 * The middle of the finance and account-management landings is the same band
 * twice over: a list of the things INTERA surfaces for that role, and then one
 * short statement of what the working day looks like once it does. Only the
 * words differ, so the band is written once and each page passes its own.
 *
 *     get_template_part( 'template-parts/partials/role-landing-signals', null, array(
 *         'eyebrow'    => 'What INTERA can help you see',
 *         'heading'    => 'From checking everything to checking exceptions',
 *         'items'      => array( array( 'icon' => 'receipt', 'label' => '…' ) ),
 *         'close_body' => '…',
 *         'close_line' => 'Less manual checking. Faster investigation.',
 *     ) );
 *
 * The items are rows rather than cards: they are one kind of thing, read in
 * sequence, and the design system draws that as `.itr-row` — the same shape the
 * home page's source list and its four sales questions use.
 *
 * The heading is the statement, not a label over one: the band says what
 * changes for that role and the items beside it say what it reads to say it.
 * A second bold line under an h2 competes with it, so there is only the one.
 *
 * An item with no label is dropped, and the closing block is skipped entirely
 * when both of its fields are empty, so clearing a field costs the line and
 * never leaves an empty surface behind.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'    => '',
		'heading'    => '',
		'items'      => array(),
		'close_body' => '',
		'close_line' => '',
	)
);

$intera_rls_items = array();

foreach ( (array) $args['items'] as $intera_rls_item ) {
	$intera_rls_label = isset( $intera_rls_item['label'] ) ? trim( (string) $intera_rls_item['label'] ) : '';

	if ( '' !== $intera_rls_label ) {
		$intera_rls_items[] = array(
			'icon'  => isset( $intera_rls_item['icon'] ) ? (string) $intera_rls_item['icon'] : 'circle-dot',
			'label' => $intera_rls_label,
		);
	}
}

$intera_rls_eyebrow = trim( (string) $args['eyebrow'] );
$intera_rls_heading = trim( (string) $args['heading'] );
$intera_rls_close   = array(
	'body' => trim( (string) $args['close_body'] ),
	'line' => trim( (string) $args['close_line'] ),
);

if ( ! $intera_rls_items && '' === implode( '', $intera_rls_close ) ) {
	return;
}
?>
<section data-screen-label="What INTERA shows" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 52px; align-items: start">
		<div>
			<?php if ( '' !== $intera_rls_eyebrow ) : ?>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( $intera_rls_eyebrow ); ?></div>
			<?php endif; ?>
			<?php if ( '' !== $intera_rls_heading ) : ?>
				<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( $intera_rls_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( '' !== implode( '', $intera_rls_close ) ) : ?>
				<div style="display: flex; flex-direction: column; gap: 14px; max-width: 520px; margin-top: 24px">
					<?php if ( '' !== $intera_rls_close['body'] ) : ?>
						<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-700)"><?php echo esc_html( $intera_rls_close['body'] ); ?></p>
					<?php endif; ?>
					<?php if ( '' !== $intera_rls_close['line'] ) : ?>
						<p style="font-size: var(--text-lg); line-height: 1.55; font-weight: 500; color: var(--ink-900)"><?php echo esc_html( $intera_rls_close['line'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $intera_rls_items ) : ?>
			<div style="display: flex; flex-direction: column; gap: 10px">
				<?php foreach ( $intera_rls_items as $intera_rls_item ) : ?>
					<div class="itr-row" style="--itr-shadow: var(--shadow-xs); display: flex; gap: 14px; align-items: flex-start; border-radius: var(--radius-md); padding: 15px 18px">
						<span style="flex: none; display: inline-flex; padding-top: 2px">
							<?php
							intera_icon(
								$intera_rls_item['icon'],
								array(
									'size'  => 16,
									'color' => 'var(--blue-600)',
								)
							);
							?>
						</span>
						<span style="min-width: 0; font-size: var(--text-base); line-height: 1.55; color: var(--ink-800)"><?php echo esc_html( $intera_rls_item['label'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
