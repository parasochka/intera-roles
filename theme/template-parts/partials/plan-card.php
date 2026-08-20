<?php
/**
 * Partial — one pricing plan card.
 *
 * The three plan cards on 01-main ("#pricing") and 03-pricing ("Plans") are the
 * same card: an uppercase plan label, the period line, the headline price, an
 * optional tier ladder, the feature list above a hairline rule, an optional
 * footnote and a full-width CTA pinned to the bottom of the card.
 *
 *     get_template_part( 'template-parts/partials/plan-card', null, array(
 *         'post'      => $post,   // WP_Post|int, defaults to the current post
 *         'cta_label' => '',      // overrides _intera_plan_cta_label
 *         'cta_url'   => '',      // overrides _intera_plan_cta_url
 *         'badge'     => null,    // featured badge text; null -> intera_option( 'header_badge' )
 *         'heading'   => 'div',   // div|h2|h3|h4 — the plan-name element
 *         'class'     => '', 'style' => '', 'attrs' => array(),
 *     ) );
 *
 * `cta_label` / `cta_url` exist because the same plan points at two different
 * places: on the home page the Free card sends the reader to the pricing page,
 * on the pricing page it sends them to the request form. The meta stays the
 * plan's own default; the calling template overrides it per placement.
 *
 * Where the copy comes from:
 *
 * | slot         | source                                                      |
 * | ------------ | ----------------------------------------------------------- |
 * | plan label   | `the_title()`                                               |
 * | period       | `_intera_plan_period`                                       |
 * | price        | `_intera_plan_price` — mono and `--text-4xl` when it is one |
 * |              | token ("€0"), a `--text-2xl` sentence when it has spaces     |
 * |              | ("Free for the first 12 months"), omitted when empty        |
 * | tier ladder  | content list items written `Basic — €750/year`: the part     |
 * |              | before an em dash, en dash or pipe is the label, the part    |
 * |              | after it is the mono figure                                 |
 * | feature list | every other content list item                                |
 * | footnote     | the content's paragraphs                                     |
 * | accent       | `_intera_plan_featured` — elevated card, blue top rule,      |
 * |              | blue label, the Beta badge and a primary CTA                |
 *
 * PORT.md §1: the card's surface belongs to the Card component, which routes
 * the resting border and shadow through `--itr-edge` / `--itr-shadow`. The only
 * inline declaration added here is the export's `fillCard` style, which is
 * layout, not surface: `height:100%;display:flex;flex-direction:column`, so the
 * CTA can sit on `margin-top: auto` and every card in the row ends level.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'post'      => null,
		'cta_label' => '',
		'cta_url'   => '',
		'badge'     => null,
		'heading'   => 'div',
		'class'     => '',
		'style'     => '',
		'attrs'     => array(),
	)
);

$intera_plan = get_post( $args['post'] );

if ( ! $intera_plan instanceof WP_Post ) {
	return;
}

$intera_plan_heading  = in_array( $args['heading'], array( 'div', 'h2', 'h3', 'h4' ), true ) ? $args['heading'] : 'div';
$intera_plan_price    = (string) get_post_meta( $intera_plan->ID, '_intera_plan_price', true );
$intera_plan_period   = (string) get_post_meta( $intera_plan->ID, '_intera_plan_period', true );
$intera_plan_featured = (bool) get_post_meta( $intera_plan->ID, '_intera_plan_featured', true );

$intera_plan_cta_label = '' !== $args['cta_label']
	? $args['cta_label']
	: (string) get_post_meta( $intera_plan->ID, '_intera_plan_cta_label', true );

$intera_plan_cta_url = '' !== $args['cta_url']
	? $args['cta_url']
	: (string) get_post_meta( $intera_plan->ID, '_intera_plan_cta_url', true );

$intera_plan_badge = null === $args['badge'] ? intera_option( 'header_badge' ) : (string) $args['badge'];

/*
 * The content carries the ladder, the features and the footnote. List items
 * first — that is how an editor writes them — falling back to paragraphs when
 * the plan has no list at all, in which case there is no footnote to separate.
 */
$intera_plan_html  = apply_filters( 'the_content', $intera_plan->post_content );
$intera_plan_items = array();
$intera_plan_notes = array();

if ( preg_match_all( '#<li\b[^>]*>(.*?)</li>#is', $intera_plan_html, $intera_plan_matches ) ) {
	$intera_plan_items = $intera_plan_matches[1];

	if ( preg_match_all( '#<p\b[^>]*>(.*?)</p>#is', $intera_plan_html, $intera_plan_matches ) ) {
		$intera_plan_notes = $intera_plan_matches[1];
	}
} elseif ( preg_match_all( '#<p\b[^>]*>(.*?)</p>#is', $intera_plan_html, $intera_plan_matches ) ) {
	$intera_plan_items = $intera_plan_matches[1];
}

$intera_plan_clean = static function ( $intera_plan_list ) {
	return array_values(
		array_filter(
			array_map( 'trim', (array) $intera_plan_list ),
			static function ( $intera_plan_entry ) {
				return '' !== wp_strip_all_tags( $intera_plan_entry );
			}
		)
	);
};

$intera_plan_items = $intera_plan_clean( $intera_plan_items );
$intera_plan_notes = $intera_plan_clean( $intera_plan_notes );

// "Basic — €750/year" becomes a ladder row; everything else is a feature line.
$intera_plan_tiers    = array();
$intera_plan_features = array();

foreach ( $intera_plan_items as $intera_plan_item ) {
	$intera_plan_parts = preg_split( '/\s+[\x{2014}\x{2013}|]\s+/u', wp_strip_all_tags( $intera_plan_item ), 2 );

	if ( is_array( $intera_plan_parts ) && 2 === count( $intera_plan_parts ) && '' !== trim( $intera_plan_parts[1] ) ) {
		$intera_plan_tiers[] = array( trim( $intera_plan_parts[0] ), trim( $intera_plan_parts[1] ) );
		continue;
	}

	$intera_plan_features[] = $intera_plan_item;
}

// ---------------------------------------------------------------- card body.
ob_start();

$intera_plan_label_style = 'font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: '
	. ( $intera_plan_featured ? 'var(--blue-600)' : 'var(--ink-500)' );

if ( 'div' !== $intera_plan_heading ) {
	$intera_plan_label_style .= '; margin: 0';
}

if ( $intera_plan_featured ) {
	echo '<div style="display: flex; align-items: center; gap: 10px">';
}

printf(
	'<%1$s style="%2$s">%3$s</%1$s>',
	esc_html( $intera_plan_heading ),
	esc_attr( $intera_plan_label_style ),
	esc_html( get_the_title( $intera_plan ) )
);

if ( $intera_plan_featured ) {
	if ( '' !== $intera_plan_badge ) {
		get_template_part(
			'template-parts/components/badge',
			null,
			array(
				'text' => $intera_plan_badge,
				'tone' => 'info',
			)
		);
	}
	echo '</div>';
}

if ( '' !== $intera_plan_period ) {
	echo '<div style="font-size: var(--text-sm); color: var(--ink-500); margin-top: 6px">' . esc_html( $intera_plan_period ) . '</div>';
}

if ( '' !== $intera_plan_price ) {
	// One token is the mono figure; a sentence gets the smaller prose treatment.
	if ( preg_match( '/\s/u', $intera_plan_price ) ) {
		echo '<div style="font-size: var(--text-2xl); font-weight: 600; color: var(--ink-900); margin-top: 18px; letter-spacing: -0.01em; line-height: 1.3">' . esc_html( $intera_plan_price ) . '</div>';
	} else {
		echo '<div style="font-family: var(--font-mono); font-size: var(--text-4xl); font-weight: 500; color: var(--ink-900); margin-top: 18px; letter-spacing: -0.02em">' . esc_html( $intera_plan_price ) . '</div>';
	}
}

if ( $intera_plan_tiers ) {
	echo '<div style="display: flex; flex-direction: column; gap: 10px; margin-top: 18px">';
	foreach ( $intera_plan_tiers as $intera_plan_tier ) {
		echo '<div style="display: flex; justify-content: space-between; align-items: baseline; gap: 12px">';
		echo '<span style="font-size: var(--text-md); color: var(--ink-700)">' . esc_html( $intera_plan_tier[0] ) . '</span>';
		echo '<span style="font-family: var(--font-mono); font-size: var(--text-md); color: var(--ink-900)">' . esc_html( $intera_plan_tier[1] ) . '</span>';
		echo '</div>';
	}
	echo '</div>';
}

if ( $intera_plan_features ) {
	echo '<div style="display: flex; flex-direction: column; gap: 9px; margin-top: 22px; padding-top: 20px; border-top: 1px solid var(--border-hairline); font-size: var(--text-md); color: var(--ink-700)">';
	foreach ( $intera_plan_features as $intera_plan_feature ) {
		echo '<span>' . wp_kses_post( $intera_plan_feature ) . '</span>';
	}
	echo '</div>';
}

foreach ( $intera_plan_notes as $intera_plan_note ) {
	echo '<p style="font-size: var(--text-xs); color: var(--ink-500); margin-top: 18px; line-height: 1.5">' . wp_kses_post( $intera_plan_note ) . '</p>';
}

if ( '' !== $intera_plan_cta_label ) {
	echo '<div style="margin-top: auto; padding-top: 26px">';
	get_template_part(
		'template-parts/components/button',
		null,
		array(
			'label'   => $intera_plan_cta_label,
			'href'    => $intera_plan_cta_url,
			'variant' => $intera_plan_featured ? 'primary' : 'secondary',
			'block'   => true,
		)
	);
	echo '</div>';
}

$intera_plan_body = ob_get_clean();

/*
 * `.itr-hl` rather than `.itr-lift`: these cards stretch to a shared height in
 * a `align-items: stretch` grid, where a 5px rise would break the row. Both
 * pages already carry `class="itr-hl"` on the card; 01-main's `fillCard` also
 * adds `itr-lift`, which 03-pricing's does not — the two placements are the
 * same card here, so the no-transform sibling wins.
 */
$intera_plan_style = 'height: 100%; display: flex; flex-direction: column';

if ( '' !== $args['style'] ) {
	$intera_plan_style .= '; ' . $args['style'];
}

get_template_part(
	'template-parts/components/card',
	null,
	array(
		'content'     => $intera_plan_body,
		'padding'     => 'loose',
		'elevated'    => $intera_plan_featured,
		'accent'      => $intera_plan_featured ? 'var(--blue-600)' : '',
		'accent_line' => $intera_plan_featured ? 'var(--blue-200)' : '',
		'class'       => trim( 'itr-hl ' . $args['class'] ),
		'style'       => $intera_plan_style,
		'attrs'       => $args['attrs'],
	)
);
