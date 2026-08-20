<?php
/**
 * Partial — one blog category card (10-blog-category, "Category posts").
 *
 * A whole-card link: mono date and reading time, the title, the standfirst and
 * the blue "Read the story" line with an arrow. Everything but the CTA label
 * comes from WordPress.
 *
 * Hover: the export carries `class="itr-lift"` plus a preview-only
 * `style-hover="box-shadow: var(--shadow-sm); border-color: var(--border-strong)"`.
 * In the theme that pair is `.itr-lift .itr-post-card` in assets/css/intera.css,
 * so the resting border and shadow are handed over as `--itr-edge` /
 * `--itr-shadow` and are never written inline (PORT.md, first section). The
 * background is the one exception: neither hover rule touches it and no class
 * reads `--itr-bg` here, so `background: var(--white)` stays inline exactly as
 * the mockup writes it.
 *
 *     get_template_part( 'template-parts/partials/post-card', null, array(
 *         'post_id'      => 0,      // 0 = the post in the loop ('post' => WP_Post also works)
 *         'date'         => true,
 *         'reading_time' => true,
 *         'excerpt'      => true,
 *         'heading'      => 'h2',   // h2|h3|div — the archive uses h2
 *         'cta_label'    => '',     // default: "Read the story"
 *         'cta'          => true,
 *         'edge'         => '',     // --itr-edge, e.g. 'var(--border-card)'
 *         'shadow'       => '',     // --itr-shadow
 *         'class'        => '',
 *         'style'        => '',     // appended last
 *         'attrs'        => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'post_id'      => 0,
		'post'         => null,
		'date'         => true,
		'reading_time' => true,
		'excerpt'      => true,
		'heading'      => 'h2',
		'cta_label'    => '',
		'cta'          => true,
		'edge'         => '',
		'shadow'       => '',
		'class'        => '',
		'style'        => '',
		'attrs'        => array(),
	)
);

$intera_card_post = null !== $args['post'] ? get_post( $args['post'] ) : get_post( (int) $args['post_id'] > 0 ? (int) $args['post_id'] : null );

if ( ! $intera_card_post ) {
	return;
}

$intera_card_id = $intera_card_post->ID;

$intera_card_heading = in_array( $args['heading'], array( 'h2', 'h3', 'h4', 'div' ), true ) ? $args['heading'] : 'h2';

$intera_card_style = '';

if ( $args['edge'] ) {
	$intera_card_style .= '--itr-edge:' . $args['edge'] . ';';
}

if ( $args['shadow'] ) {
	$intera_card_style .= '--itr-shadow:' . $args['shadow'] . ';';
}

$intera_card_style .= 'display:block;border-radius:var(--radius-card);padding:26px;background:var(--white)';

if ( $args['style'] ) {
	$intera_card_style .= ';' . $args['style'];
}

$intera_card_class = 'itr-lift itr-post-card';

if ( $args['class'] ) {
	$intera_card_class .= ' ' . $args['class'];
}

$intera_card_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_card_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_card_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

$intera_card_excerpt = $args['excerpt'] ? get_the_excerpt( $intera_card_post ) : '';
$intera_card_cta     = $args['cta_label'] ? $args['cta_label'] : __( 'Read the story', 'intera' );
?>
<a class="<?php echo esc_attr( $intera_card_class ); ?>" href="<?php echo esc_url( get_permalink( $intera_card_id ) ); ?>" style="<?php echo esc_attr( $intera_card_style ); ?>"<?php echo $intera_card_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<?php
	if ( $args['date'] || $args['reading_time'] ) {
		get_template_part(
			'template-parts/partials/entry-meta',
			null,
			array(
				'post_id'      => $intera_card_id,
				'variant'      => 'pair',
				'date'         => (bool) $args['date'],
				'reading_time' => (bool) $args['reading_time'],
				'read_label'   => 'short',
			)
		);
	}
	?>
	<<?php echo esc_html( $intera_card_heading ); ?> style="font-size:var(--text-xl);font-weight:600;letter-spacing:-0.01em;line-height:1.32;color:var(--ink-900);margin-top:12px"><?php echo wp_kses_post( get_the_title( $intera_card_id ) ); ?></<?php echo esc_html( $intera_card_heading ); ?>>
	<?php if ( $intera_card_excerpt ) : ?>
		<p style="font-size:var(--text-base);line-height:1.65;color:var(--ink-600);margin-top:10px;max-width:620px"><?php echo wp_kses_post( $intera_card_excerpt ); ?></p>
	<?php endif; ?>
	<?php if ( $args['cta'] ) : ?>
		<div style="display:flex;align-items:center;gap:8px;margin-top:18px;font-size:var(--text-md);color:var(--blue-600);font-weight:500"><?php echo esc_html( $intera_card_cta ); ?>
			<?php
			if ( function_exists( 'intera_icon' ) ) {
				intera_icon( 'arrow-right', array( 'size' => 16 ) );
			}
			?>
		</div>
	<?php endif; ?>
</a>
