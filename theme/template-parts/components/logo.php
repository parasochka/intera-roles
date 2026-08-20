<?php
/**
 * Component — Logo (DS `Logo`, components.md §7).
 *
 * The mark is drawn inline and tokenised, so a token edit re-themes it: `var(--ink-900)` /
 * `var(--action-primary)` on the light lockup, `var(--white)` / `var(--blue-200)` on the
 * inverse one. A WP custom logo, when set, is the caller's business — this partial only
 * draws the SVG lockup.
 *
 *     get_template_part( 'template-parts/components/logo', null, array(
 *         'size'       => 26,           // wordmark font-size in px; everything scales off it
 *         'tone'       => 'ink',        // ink|inverse
 *         'variant'    => 'horizontal', // horizontal|mark|wordmark|square
 *         'suffix'     => '',           // small uppercase word after the wordmark
 *         'aria_label' => 'INTERA',     // accessible name for the mark-only and square variants
 *         'class'      => '',
 *         'style'      => '',
 *         'attrs'      => array(),
 *     ) );
 *
 * Sizes in use: 26 in the header (mark 37px, gap 13px), 18 in the footer (mark 26px, gap 9px).
 * The export's `font-size:{size}` string quirk is fixed here — the px unit is always written.
 * The mark never renders below 16px (design-system minimum).
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'size'       => 20,
		'tone'       => 'ink',
		'variant'    => 'horizontal',
		'suffix'     => '',
		'aria_label' => 'INTERA',
		'class'      => '',
		'style'      => '',
		'attrs'      => array(),
	)
);

$intera_logo_size    = max( 1, (int) $args['size'] );
$intera_logo_inverse = 'inverse' === $args['tone'];
$intera_logo_variant = in_array( $args['variant'], array( 'horizontal', 'mark', 'wordmark', 'square' ), true )
	? $args['variant']
	: 'horizontal';

// markSize = round(size * 1.42); never below the 16px minimum mark size.
$intera_logo_mark = max( 16, (int) round( $intera_logo_size * 1.42 ) );
// box = round(size * 2.4) for the square variant.
$intera_logo_box  = (int) round( $intera_logo_size * 2.4 );
// gap = size * 0.5.
$intera_logo_gap  = $intera_logo_size * 0.5;
$intera_logo_gap  = ( floor( $intera_logo_gap ) === $intera_logo_gap ) ? (string) (int) $intera_logo_gap : (string) $intera_logo_gap;

$intera_logo_color        = $intera_logo_inverse ? 'var(--text-inverse)' : 'var(--text-primary)';
$intera_logo_stroke_back  = $intera_logo_inverse ? 'var(--white)' : 'var(--ink-900)';
$intera_logo_stroke_front = $intera_logo_inverse ? 'var(--blue-200)' : 'var(--action-primary)';
$intera_logo_fill         = $intera_logo_inverse ? 'var(--white)' : 'var(--action-primary)';
$intera_logo_suffix_color = $intera_logo_inverse ? 'rgba(255,255,255,.7)' : 'var(--text-muted)';

$intera_logo_class = 'itr-logo itr-logo--' . $intera_logo_variant . ' itr-logo--' . ( $intera_logo_inverse ? 'inverse' : 'ink' );

if ( $args['class'] ) {
	$intera_logo_class .= ' ' . $args['class'];
}

$intera_logo_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_logo_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_logo_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

if ( 'square' === $intera_logo_variant ) :
	// The square variant ignores tone and suffix entirely.
	$intera_logo_style = 'display:inline-flex' . ( $args['style'] ? ';' . $args['style'] : '' );
	?>
	<span class="<?php echo esc_attr( $intera_logo_class ); ?>" style="<?php echo esc_attr( $intera_logo_style ); ?>"<?php echo $intera_logo_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
		<svg viewBox="0 0 64 64" width="<?php echo esc_attr( $intera_logo_box ); ?>" height="<?php echo esc_attr( $intera_logo_box ); ?>" fill="none" role="img" aria-label="<?php echo esc_attr( $args['aria_label'] ); ?>" style="display:block">
			<rect width="64" height="64" rx="14" fill="var(--ink-900)"/>
			<g transform="translate(12 12)">
				<rect x="0" y="0" width="22" height="22" rx="3.5" stroke="var(--white)" stroke-width="2.8"/>
				<rect x="8" y="8" width="14" height="14" fill="var(--white)"/>
				<rect x="8" y="8" width="22" height="22" rx="3.5" stroke="var(--blue-200)" stroke-width="2.8"/>
			</g>
		</svg>
	</span>
	<?php
	return;
endif;

$intera_logo_style = 'display:inline-flex;align-items:center;gap:' . $intera_logo_gap . 'px';

if ( $args['style'] ) {
	$intera_logo_style .= ';' . $args['style'];
}
?>
<span class="<?php echo esc_attr( $intera_logo_class ); ?>" style="<?php echo esc_attr( $intera_logo_style ); ?>"<?php echo $intera_logo_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<?php if ( 'wordmark' !== $intera_logo_variant ) : ?>
		<svg viewBox="0 0 40 40" width="<?php echo esc_attr( $intera_logo_mark ); ?>" height="<?php echo esc_attr( $intera_logo_mark ); ?>" fill="none"
			<?php
			// The horizontal lockup is named by its own wordmark text; the mark alone needs a name of its own.
			if ( 'mark' === $intera_logo_variant ) :
				?>
				role="img" aria-label="<?php echo esc_attr( $args['aria_label'] ); ?>"
			<?php else : ?>
				aria-hidden="true"
			<?php endif; ?>
			style="display:block;flex:none">
			<rect x="5" y="5" width="22" height="22" rx="3.5" stroke="<?php echo esc_attr( $intera_logo_stroke_back ); ?>" stroke-width="2.6"/>
			<rect x="13" y="13" width="14" height="14" fill="<?php echo esc_attr( $intera_logo_fill ); ?>"/>
			<rect x="13" y="13" width="22" height="22" rx="3.5" stroke="<?php echo esc_attr( $intera_logo_stroke_front ); ?>" stroke-width="2.6"/>
		</svg>
	<?php endif; ?>
	<?php if ( 'mark' !== $intera_logo_variant ) : ?>
		<span style="display:inline-flex;align-items:baseline;gap:0.55em;font-family:var(--font-sans);font-weight:var(--weight-semibold);font-size:<?php echo esc_attr( $intera_logo_size ); ?>px;letter-spacing:var(--tracking-caps);line-height:1;color:<?php echo esc_attr( $intera_logo_color ); ?>;white-space:nowrap">INTERA<?php
		if ( '' !== $args['suffix'] ) :
			?><span style="font-weight:var(--weight-regular);font-size:0.62em;letter-spacing:0.12em;color:<?php echo esc_attr( $intera_logo_suffix_color ); ?>;text-transform:uppercase"><?php echo esc_html( $args['suffix'] ); ?></span><?php
		endif;
		?></span>
	<?php endif; ?>
</span>
