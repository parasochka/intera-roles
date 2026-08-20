<?php
/**
 * Partial — one INTERA Role card.
 *
 * The five role cards appear twice with the same shell and different bodies:
 *
 * - `variant => 'main'` (01-main, "INTERA Roles"): icon + title, the
 *   `_intera_role_summary` line, then the role's checks as a stacked list above
 *   a hairline rule.
 * - `variant => 'product'` (02-product, "#roles"): icon + title, one prose
 *   paragraph, then `_intera_role_tags` as Tag chips.
 *
 *     get_template_part( 'template-parts/partials/role-card', null, array(
 *         'post'    => $post,       // WP_Post|int, defaults to the current post
 *         'variant' => 'main',      // main|product
 *         'heading' => 'span',      // span|h2|h3|h4 — the title element
 *         'class'   => '', 'style' => '', 'attrs' => array(),
 *     ) );
 *
 * Where the copy comes from:
 *
 * | slot                | source                                              |
 * | ------------------- | --------------------------------------------------- |
 * | icon                | `_intera_role_icon` -> `intera_icon()`, 18px, blue  |
 * | title               | `the_title()`                                       |
 * | summary line (main) | `_intera_role_summary`                              |
 * | checks list (main)  | the post content — `<li>`s, else `<p>`s             |
 * | paragraph (product) | the excerpt; with none, the checks joined into one  |
 * |                     | sentence, so a role still reads on the product page |
 * | chips (product)     | `_intera_role_tags`, comma-separated                |
 *
 * `role` is `public => false`, so a card never links anywhere — the export's
 * `interactive="{{ true }}"` on the 02-product cards is dropped rather than
 * reproduced, because a `cursor: pointer` on something that cannot be clicked
 * is a promise the page cannot keep (CONTRACT.md: gaps are fixed, not copied).
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'post'    => null,
		'variant' => 'main',
		'heading' => 'span',
		'class'   => '',
		'style'   => '',
		'attrs'   => array(),
	)
);

$intera_role = get_post( $args['post'] );

if ( ! $intera_role instanceof WP_Post ) {
	return;
}

$intera_role_variant = 'product' === $args['variant'] ? 'product' : 'main';
$intera_role_heading = in_array( $args['heading'], array( 'span', 'h2', 'h3', 'h4' ), true ) ? $args['heading'] : 'span';

$intera_role_icon    = (string) get_post_meta( $intera_role->ID, '_intera_role_icon', true );
$intera_role_summary = (string) get_post_meta( $intera_role->ID, '_intera_role_summary', true );
$intera_role_tags    = (string) get_post_meta( $intera_role->ID, '_intera_role_tags', true );

/*
 * The body. `<li>`s first — the natural way an editor writes a list of checks —
 * and paragraphs when the content has no list at all. Inline markup inside an
 * item is kept for the stacked list and stripped for the joined sentence.
 */
$intera_role_html  = apply_filters( 'the_content', $intera_role->post_content );
$intera_role_items = array();

if ( preg_match_all( '#<li\b[^>]*>(.*?)</li>#is', $intera_role_html, $intera_role_matches ) ) {
	$intera_role_items = $intera_role_matches[1];
} elseif ( preg_match_all( '#<p\b[^>]*>(.*?)</p>#is', $intera_role_html, $intera_role_matches ) ) {
	$intera_role_items = $intera_role_matches[1];
}

$intera_role_items = array_values(
	array_filter(
		array_map( 'trim', $intera_role_items ),
		static function ( $intera_role_item ) {
			return '' !== wp_strip_all_tags( $intera_role_item );
		}
	)
);

// ---------------------------------------------------------------- card body.
ob_start();
?>
<div style="display: flex; align-items: center; gap: 10px<?php echo 'main' === $intera_role_variant ? '; color: var(--ink-900)' : ''; ?>">
	<?php
	if ( '' !== $intera_role_icon ) {
		intera_icon(
			$intera_role_icon,
			array(
				'size'  => 18,
				'color' => 'var(--blue-600)',
			)
		);
	}
	?>
	<<?php echo esc_html( $intera_role_heading ); ?> style="font-size: var(--text-lg); font-weight: 600; letter-spacing: -0.01em<?php echo 'span' === $intera_role_heading ? '' : '; margin: 0'; ?>"><?php echo esc_html( get_the_title( $intera_role ) ); ?></<?php echo esc_html( $intera_role_heading ); ?>>
</div>
<?php
if ( 'main' === $intera_role_variant ) {

	if ( '' !== $intera_role_summary ) {
		echo '<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 10px">' . esc_html( $intera_role_summary ) . '</p>';
	}

	if ( $intera_role_items ) {
		echo '<div style="display: flex; flex-direction: column; gap: 6px; margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border-hairline); font-size: var(--text-sm); color: var(--ink-700)">';
		foreach ( $intera_role_items as $intera_role_item ) {
			echo '<span>' . wp_kses_post( $intera_role_item ) . '</span>';
		}
		echo '</div>';
	}
} else {

	/*
	 * The product page shows one sentence, not a list. The excerpt is the
	 * editor's own wording; without one the checks are joined so the card is
	 * never blank.
	 */
	$intera_role_lede = trim( (string) $intera_role->post_excerpt );

	if ( '' === $intera_role_lede && $intera_role_items ) {
		$intera_role_lede = implode(
			', ',
			array_map(
				static function ( $intera_role_item ) {
					return rtrim( wp_strip_all_tags( $intera_role_item ), " \t\n\r\0\x0B." );
				},
				$intera_role_items
			)
		) . '.';
	}

	if ( '' === $intera_role_lede ) {
		$intera_role_lede = $intera_role_summary;
	}

	if ( '' !== $intera_role_lede ) {
		echo '<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 10px">' . esc_html( $intera_role_lede ) . '</p>';
	}

	$intera_role_chips = array_values(
		array_filter(
			array_map( 'trim', explode( ',', $intera_role_tags ) ),
			static function ( $intera_role_chip ) {
				return '' !== $intera_role_chip;
			}
		)
	);

	if ( $intera_role_chips ) {
		echo '<div style="display: flex; gap: 8px; margin-top: 14px; flex-wrap: wrap">';
		foreach ( $intera_role_chips as $intera_role_chip ) {
			get_template_part(
				'template-parts/components/tag',
				null,
				array( 'text' => $intera_role_chip )
			);
		}
		echo '</div>';
	}
}

$intera_role_body = ob_get_clean();

get_template_part(
	'template-parts/components/card',
	null,
	array(
		'content' => $intera_role_body,
		'class'   => trim( 'itr-lift ' . $args['class'] ),
		'style'   => $args['style'],
		'attrs'   => $args['attrs'],
	)
);
