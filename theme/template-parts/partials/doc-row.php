<?php
/**
 * Partial — one docs list row.
 *
 * Three shapes, all taken from the export:
 *
 *   ordinal   13-docs-category — mono ordinal, title, one-line summary, mono
 *             reading time. The ordinal is the post's `menu_order`, read per
 *             post and never a loop index: the numbering runs 01…10 straight
 *             through the sub-groups ("First steps", "Data and modelling",
 *             "Good to know"), so a counter restarted per group would be wrong.
 *   compact   11-docs — the 38px ellipsised article link inside a category card.
 *   heading   11-docs — the line above such a list: the category's icon chip in
 *             its tone, the category link, and the mono article count. Icon and
 *             tone are term meta, via intera_term_icon_get() /
 *             intera_term_tone_get(), so an editor owns both.
 *
 * Hovers, per PORT.md: the ordinal row's `style-hover="background:
 * var(--surface-hover)"` is `.itr-list-row`; the compact link is `a.itr-doc-row`
 * (--ink-700 → --blue-600) and the heading link `a.itr-doc-title` (--ink-900 →
 * --blue-600), both of which own their resting colour, so no inline `color`
 * survives on either link.
 *
 *     get_template_part( 'template-parts/partials/doc-row', null, array(
 *         'post_id'      => 0,         // ordinal|compact — 0 = the post in the loop
 *         'post'         => null,      // or hand over a WP_Post / id directly
 *         'variant'      => 'ordinal', // ordinal | compact | heading
 *         'ordinal'      => null,      // null = menu_order; 0 or '' hides it
 *         'summary'      => true,
 *         'reading_time' => true,
 *         'divider'      => true,      // the hairline under the row
 *         'term'         => null,      // heading — WP_Term or term id
 *         'href'         => '',        // heading — defaults to the term link
 *         'count'        => null,      // heading — defaults to the term count
 *         'icon'         => '',        // heading — overrides the term's icon
 *         'class'        => '',
 *         'style'        => '',        // appended last
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
		'variant'      => 'ordinal',
		'ordinal'      => null,
		'summary'      => true,
		'reading_time' => true,
		'divider'      => true,
		'term'         => null,
		'href'         => '',
		'count'        => null,
		'icon'         => '',
		'class'        => '',
		'style'        => '',
		'attrs'        => array(),
	)
);

$intera_doc_variant = in_array( $args['variant'], array( 'ordinal', 'compact', 'heading' ), true ) ? $args['variant'] : 'ordinal';

$intera_doc_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_doc_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_doc_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

/*
 * ---------------------------------------------------------------------------
 * heading — the docs category line on 11-docs
 * ---------------------------------------------------------------------------
 */
if ( 'heading' === $intera_doc_variant ) {
	$intera_doc_term = $args['term'];

	if ( is_numeric( $intera_doc_term ) ) {
		$intera_doc_term = get_term( (int) $intera_doc_term, 'doc_category' );
	}

	if ( ! $intera_doc_term || is_wp_error( $intera_doc_term ) ) {
		return;
	}

	$intera_doc_tone = function_exists( 'intera_term_tone_get' ) ? intera_term_tone_get( $intera_doc_term->term_id ) : 'blue';
	$intera_doc_icon = $args['icon'];

	if ( '' === $intera_doc_icon && function_exists( 'intera_term_icon_get' ) ) {
		$intera_doc_icon = intera_term_icon_get( $intera_doc_term->term_id );
	}

	if ( '' === $intera_doc_icon ) {
		$intera_doc_icon = 'book-open';
	}

	$intera_doc_href  = $args['href'] ? $args['href'] : get_term_link( $intera_doc_term );
	$intera_doc_href  = is_wp_error( $intera_doc_href ) ? '' : $intera_doc_href;
	$intera_doc_count = null === $args['count'] ? (int) $intera_doc_term->count : (int) $args['count'];

	$intera_doc_style = 'display:flex;align-items:flex-start;justify-content:space-between;gap:12px;min-height:58px';

	if ( $args['style'] ) {
		$intera_doc_style .= ';' . $args['style'];
	}

	$intera_doc_chip = 'flex:none;width:34px;height:34px;border-radius:var(--radius-md)'
		. ';background:var(--' . $intera_doc_tone . '-50)'
		. ';border:1px solid var(--' . $intera_doc_tone . '-100)'
		. ';display:grid;place-items:center'
		. ';color:var(--' . $intera_doc_tone . '-600)';
	?>
	<div<?php echo $args['class'] ? ' class="' . esc_attr( $args['class'] ) . '"' : ''; ?> style="<?php echo esc_attr( $intera_doc_style ); ?>"<?php echo $intera_doc_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
		<div style="display:flex;align-items:flex-start;gap:12px">
			<span style="<?php echo esc_attr( $intera_doc_chip ); ?>">
				<?php
				if ( function_exists( 'intera_icon' ) ) {
					intera_icon( $intera_doc_icon, array( 'size' => 17 ) );
				}
				?>
			</span>
			<a class="itr-doc-title" href="<?php echo esc_url( $intera_doc_href ); ?>" style="font-size:var(--text-xl);font-weight:600;letter-spacing:-0.01em;line-height:34px"><?php echo esc_html( $intera_doc_term->name ); ?></a>
		</div>
		<span style="font-family:var(--font-mono);font-size:var(--text-xs);color:var(--ink-400);line-height:34px"><?php echo esc_html( (string) $intera_doc_count ); ?></span>
	</div>
	<?php
	return;
}

/*
 * ---------------------------------------------------------------------------
 * ordinal / compact — a docs article row
 * ---------------------------------------------------------------------------
 */
$intera_doc_post = null !== $args['post'] ? get_post( $args['post'] ) : get_post( (int) $args['post_id'] > 0 ? (int) $args['post_id'] : null );

if ( ! $intera_doc_post ) {
	return;
}

$intera_doc_id = $intera_doc_post->ID;

if ( 'compact' === $intera_doc_variant ) {
	$intera_doc_style = 'display:block;font-size:var(--text-md);height:38px;line-height:38px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap';

	if ( $args['divider'] ) {
		$intera_doc_style .= ';border-bottom:1px solid var(--border-hairline)';
	}

	if ( $args['style'] ) {
		$intera_doc_style .= ';' . $args['style'];
	}

	$intera_doc_class = 'itr-doc-row';

	if ( $args['class'] ) {
		$intera_doc_class .= ' ' . $args['class'];
	}
	?>
	<a class="<?php echo esc_attr( $intera_doc_class ); ?>" href="<?php echo esc_url( get_permalink( $intera_doc_id ) ); ?>" style="<?php echo esc_attr( $intera_doc_style ); ?>"<?php echo $intera_doc_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>><?php echo wp_kses_post( get_the_title( $intera_doc_id ) ); ?></a>
	<?php
	return;
}

$intera_doc_ordinal = null === $args['ordinal'] ? (int) $intera_doc_post->menu_order : $args['ordinal'];
$intera_doc_ordinal = ( is_numeric( $intera_doc_ordinal ) && (int) $intera_doc_ordinal > 0 )
	? sprintf( '%02d', (int) $intera_doc_ordinal )
	: (string) $intera_doc_ordinal;

$intera_doc_summary = $args['summary'] ? get_the_excerpt( $intera_doc_post ) : '';

$intera_doc_style = 'display:flex;gap:16px;align-items:baseline;padding:16px 4px';

if ( $args['divider'] ) {
	$intera_doc_style .= ';border-bottom:1px solid var(--border-hairline)';
}

if ( $args['style'] ) {
	$intera_doc_style .= ';' . $args['style'];
}

$intera_doc_class = 'itr-list-row';

if ( $args['class'] ) {
	$intera_doc_class .= ' ' . $args['class'];
}
?>
<a class="<?php echo esc_attr( $intera_doc_class ); ?>" href="<?php echo esc_url( get_permalink( $intera_doc_id ) ); ?>" style="<?php echo esc_attr( $intera_doc_style ); ?>"<?php echo $intera_doc_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<?php if ( '' !== $intera_doc_ordinal ) : ?>
		<span style="font-family:var(--font-mono);font-size:var(--text-xs);color:var(--ink-400)"><?php echo esc_html( $intera_doc_ordinal ); ?></span>
	<?php endif; ?>
	<span style="flex:1">
		<span style="display:block;font-size:var(--text-md);font-weight:500;color:var(--ink-900)"><?php echo wp_kses_post( get_the_title( $intera_doc_id ) ); ?></span>
		<?php if ( $intera_doc_summary ) : ?>
			<span style="display:block;font-size:var(--text-sm);color:var(--ink-600);margin-top:4px;line-height:1.55"><?php echo wp_kses_post( $intera_doc_summary ); ?></span>
		<?php endif; ?>
	</span>
	<?php
	if ( $args['reading_time'] ) {
		get_template_part(
			'template-parts/partials/entry-meta',
			null,
			array(
				'post_id'      => $intera_doc_id,
				'variant'      => 'inline',
				'date'         => false,
				'reading_time' => true,
				'read_label'   => 'short',
				'style'        => 'font-size:var(--text-2xs)',
			)
		);
	}
	?>
</a>
