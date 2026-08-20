<?php
/**
 * Partial — product screenshot frame (`.itr-frame`).
 *
 * The browser-chrome frame the mockups wrap every product screenshot in: a mono
 * caption bar over a fixed-height crop. Five instances in the export —
 * `01-main` ×3 (hero 420px/`--shadow-overlay`, "in action" 420px, "working with
 * IT" 440px), `02-product` ×1 (420px) and `09-blog-post` ×1 (360px, no traffic
 * lights, no frame fill).
 *
 *     get_template_part( 'template-parts/partials/screenshot-frame', null, array(
 *         'attachment' => intera_option( 'shot_hero' ),
 *         'caption'    => 'Fleet Health Overview · Shipmanagement',
 *         'height'     => '420px',            // desktop crop height -> --itr-shot-h
 *         'size'       => 'intera-shot',      // intera-shot | intera-shot-inline
 *         'alt'        => '',                 // overrides the attachment's own alt text
 *         'dots'       => true,               // the three window dots
 *         'background' => 'var(--white)',     // '' on the in-article frame
 *         'shadow'     => '',                 // '' -> the .itr-frame default, var(--shadow-lg)
 *         'edge'       => '',                 // '' -> the .itr-frame default, var(--border-subtle)
 *         'radius'     => 'var(--radius-xl)',
 *         'class'      => '', 'style' => '', 'attrs' => array(),
 *     ) );
 *
 * No attachment (or an attachment that is not an image) renders **nothing** — an
 * empty chrome bar over a 420px hole is worse than an absent figure.
 *
 * PORT.md §1: `.itr-frame` carries a hover state, so `border`, `box-shadow`,
 * `background` and `transition` are never inline here — they arrive as
 * `--itr-edge` / `--itr-shadow` / `--itr-bg`, which `assets/css/intera.css`
 * reads. Likewise `.itr-shot` takes its crop height as `--itr-shot-h` (the
 * 760px breakpoint overrides it), and the `<img>` gets no inline style at all.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'attachment' => 0,
		'caption'    => '',
		'height'     => '420px',
		'size'       => 'intera-shot',
		'alt'        => '',
		'dots'       => true,
		'background' => 'var(--white)',
		'shadow'     => '',
		'edge'       => '',
		'radius'     => 'var(--radius-xl)',
		'class'      => '',
		'style'      => '',
		'attrs'      => array(),
	)
);

$intera_shot_id = (int) $args['attachment'];

// Degrade to nothing rather than to a broken frame.
if ( $intera_shot_id < 1 || ! wp_attachment_is_image( $intera_shot_id ) ) {
	return;
}

$intera_shot_size = in_array( $args['size'], array( 'intera-shot', 'intera-shot-inline' ), true )
	? $args['size']
	: 'intera-shot';

$intera_shot_img_attr = array();

if ( '' !== $args['alt'] ) {
	$intera_shot_img_attr['alt'] = $args['alt'];
}

$intera_shot_img = wp_get_attachment_image( $intera_shot_id, $intera_shot_size, false, $intera_shot_img_attr );

if ( '' === $intera_shot_img ) {
	return;
}

/*
 * Custom properties first, then the declarations the hover state never touches.
 * --itr-bg is also what the crop reads for its own fill: .itr-frame in
 * intera.css paints border and shadow only, so the frame's white ground is
 * applied on .itr-shot, which sits under the image and has no hover rule.
 */
$intera_shot_style = '';

if ( '' !== $args['edge'] ) {
	$intera_shot_style .= '--itr-edge:' . $args['edge'] . ';';
}

if ( '' !== $args['shadow'] ) {
	$intera_shot_style .= '--itr-shadow:' . $args['shadow'] . ';';
}

if ( '' !== $args['background'] ) {
	$intera_shot_style .= '--itr-bg:' . $args['background'] . ';';
}

$intera_shot_style .= 'border-radius:' . $args['radius'] . ';overflow:hidden';

if ( '' !== $args['style'] ) {
	$intera_shot_style .= ';' . $args['style'];
}

$intera_shot_class = 'itr-frame';

if ( '' !== $args['class'] ) {
	$intera_shot_class .= ' ' . $args['class'];
}

$intera_shot_attrs = '';

foreach ( (array) $args['attrs'] as $intera_shot_key => $intera_shot_value ) {
	if ( false === $intera_shot_value || null === $intera_shot_value ) {
		continue;
	}
	if ( true === $intera_shot_value ) {
		$intera_shot_attrs .= ' ' . esc_attr( $intera_shot_key );
		continue;
	}
	$intera_shot_attrs .= ' ' . esc_attr( $intera_shot_key ) . '="' . esc_attr( $intera_shot_value ) . '"';
}

$intera_shot_has_bar = $args['dots'] || '' !== $args['caption'];
?>
<div class="<?php echo esc_attr( $intera_shot_class ); ?>" style="<?php echo esc_attr( $intera_shot_style ); ?>"<?php echo $intera_shot_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<?php if ( $intera_shot_has_bar ) : ?>
		<div style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-bottom: 1px solid var(--border-hairline); background: var(--surface-sunken)">
			<?php if ( $args['dots'] ) : ?>
				<span aria-hidden="true" style="display: flex; gap: 5px">
					<span style="width: 8px; height: 8px; border-radius: 999px; background: var(--ink-200)"></span>
					<span style="width: 8px; height: 8px; border-radius: 999px; background: var(--ink-200)"></span>
					<span style="width: 8px; height: 8px; border-radius: 999px; background: var(--ink-200)"></span>
				</span>
			<?php endif; ?>
			<?php if ( '' !== $args['caption'] ) : ?>
				<span style="font-family: var(--font-mono); font-size: var(--text-2xs); color: var(--ink-500)"><?php echo esc_html( $args['caption'] ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="itr-shot" style="--itr-shot-h: <?php echo esc_attr( $args['height'] ); ?>">
		<?php echo $intera_shot_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() escapes its own attributes. ?>
	</div>
</div>
