<?php
/**
 * Partial — previous / next article (09-blog-post L133-L141, 12-docs-article
 * L162-L170).
 *
 * Two cards side by side in an auto-fit grid: a mono `Previous` / `Next` label
 * over the neighbouring title. Identical on both screens bar the padding (18px
 * on the story, 16px in the docs) and the minimum column width.
 *
 * PORT.md §1: the export writes `class="itr-lift"` next to an inline
 * `border: 1px solid var(--border-card)` and a `style-hover` background. Inline
 * those and the card is dead on hover, so the border is handed over as
 * `--itr-edge` (which is `.itr-lift`'s own default anyway) and the hover tint
 * arrives as `.itr-lift-tint`. Padding and radius stay inline — hover never
 * touches them.
 *
 * Neighbours resolve themselves from the loop; `previous` / `next` override
 * that for orderings WordPress cannot guess (docs are ordered by `menu_order`
 * inside a `doc_category`, not by date). Pass `false` to suppress one side,
 * and the grid gives the survivor the full width. Nothing at all is rendered
 * when there is no neighbour on either side.
 *
 *     get_template_part( 'template-parts/partials/prev-next', null, array(
 *         'previous'     => null,   // null = auto | WP_Post | int | false
 *         'next'         => null,
 *         'in_same_term' => false,  // auto-resolution only
 *         'taxonomy'     => 'category',
 *         'prev_label'   => '',     // default: "Previous"
 *         'next_label'   => '',     // default: "Next"
 *         'label'        => '',     // nav accessible name
 *         'padding'      => '18px', // 16px in the docs
 *         'min'          => '240px',// 220px in the docs
 *         'gap'          => '12px',
 *         'edge'         => '',     // --itr-edge override
 *         'class'        => '', 'style' => '', 'attrs' => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'previous'     => null,
		'next'         => null,
		'in_same_term' => false,
		'taxonomy'     => 'category',
		'prev_label'   => '',
		'next_label'   => '',
		'label'        => '',
		'padding'      => '18px',
		'min'          => '240px',
		'gap'          => '12px',
		'edge'         => '',
		'class'        => '',
		'style'        => '',
		'attrs'        => array(),
	)
);

/**
 * Coerce whatever a template passed into a post, or null.
 *
 * `false` means "this side is deliberately empty"; `null` means "work it out".
 *
 * @param mixed    $value    Passed value.
 * @param callable $resolver Auto-resolution callback.
 * @return WP_Post|null
 */
$intera_adjacent = static function ( $value, $resolver ) {
	if ( false === $value ) {
		return null;
	}

	if ( null === $value ) {
		$value = $resolver();
	}

	if ( $value instanceof WP_Post ) {
		return $value;
	}

	if ( is_numeric( $value ) && (int) $value > 0 ) {
		$post_object = get_post( (int) $value );

		return $post_object instanceof WP_Post ? $post_object : null;
	}

	return null;
};

$intera_pn_same = (bool) $args['in_same_term'];
$intera_pn_tax  = (string) $args['taxonomy'];

$intera_pn_prev = $intera_adjacent(
	$args['previous'],
	static function () use ( $intera_pn_same, $intera_pn_tax ) {
		return get_previous_post( $intera_pn_same, '', $intera_pn_tax );
	}
);

$intera_pn_next = $intera_adjacent(
	$args['next'],
	static function () use ( $intera_pn_same, $intera_pn_tax ) {
		return get_next_post( $intera_pn_same, '', $intera_pn_tax );
	}
);

if ( ! $intera_pn_prev && ! $intera_pn_next ) {
	return;
}

$intera_pn_label = '' !== $args['label'] ? $args['label'] : __( 'More articles', 'intera' );

$intera_pn_class = 'intera-prev-next';

if ( '' !== $args['class'] ) {
	$intera_pn_class .= ' ' . $args['class'];
}

$intera_pn_style = 'display: grid; grid-template-columns: repeat(auto-fit, minmax(min(' . $args['min'] . ', 100%), 1fr)); gap: ' . $args['gap'];

if ( '' !== $args['style'] ) {
	$intera_pn_style .= '; ' . $args['style'];
}

$intera_pn_attrs = '';

foreach ( (array) $args['attrs'] as $intera_pn_key => $intera_pn_value ) {
	if ( false === $intera_pn_value || null === $intera_pn_value ) {
		continue;
	}
	if ( true === $intera_pn_value ) {
		$intera_pn_attrs .= ' ' . esc_attr( $intera_pn_key );
		continue;
	}
	$intera_pn_attrs .= ' ' . esc_attr( $intera_pn_key ) . '="' . esc_attr( $intera_pn_value ) . '"';
}

$intera_pn_card_style = '';

if ( '' !== $args['edge'] ) {
	$intera_pn_card_style .= '--itr-edge: ' . $args['edge'] . '; ';
}

$intera_pn_card_style .= 'border-radius: var(--radius-card); padding: ' . $args['padding'];

$intera_pn_cards = array(
	array(
		'post'  => $intera_pn_prev,
		'label' => '' !== $args['prev_label'] ? $args['prev_label'] : __( 'Previous', 'intera' ),
		'rel'   => 'prev',
	),
	array(
		'post'  => $intera_pn_next,
		'label' => '' !== $args['next_label'] ? $args['next_label'] : __( 'Next', 'intera' ),
		'rel'   => 'next',
	),
);
?>
<nav class="<?php echo esc_attr( $intera_pn_class ); ?>" aria-label="<?php echo esc_attr( $intera_pn_label ); ?>" style="<?php echo esc_attr( $intera_pn_style ); ?>"<?php echo $intera_pn_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<?php foreach ( $intera_pn_cards as $intera_pn_card ) : ?>
		<?php if ( $intera_pn_card['post'] ) : ?>
			<a class="itr-lift itr-lift-tint" href="<?php echo esc_url( (string) get_permalink( $intera_pn_card['post'] ) ); ?>" rel="<?php echo esc_attr( $intera_pn_card['rel'] ); ?>" style="<?php echo esc_attr( $intera_pn_card_style ); ?>">
				<div style="font-family: var(--font-mono); font-size: var(--text-2xs); color: var(--ink-400)"><?php echo esc_html( $intera_pn_card['label'] ); ?></div>
				<div style="font-size: var(--text-md); font-weight: 500; color: var(--ink-900); margin-top: 6px; line-height: 1.4"><?php echo wp_kses_post( get_the_title( $intera_pn_card['post'] ) ); ?></div>
			</a>
		<?php endif; ?>
	<?php endforeach; ?>
</nav>
