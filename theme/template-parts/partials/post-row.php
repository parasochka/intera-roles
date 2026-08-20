<?php
/**
 * Partial — one blog list row (08-blog, "Post list").
 *
 * The whole row is a single link: mono date in a 96px column, then the title
 * and the standfirst, then the category chip pushed to the end of the flex
 * line. Category, date and excerpt all come from WordPress.
 *
 * The export writes the hover as `style-hover="background: var(--surface-hover)"`,
 * which is a preview-only attribute. In the theme that is `.itr-list-row` in
 * assets/css/intera.css — a background-only hover, so the row keeps its inline
 * padding and hairline (PORT.md, "Background-only hovers"). Nothing that the
 * hover touches is written inline.
 *
 *     get_template_part( 'template-parts/partials/post-row', null, array(
 *         'post_id'      => 0,     // 0 = the post in the loop ('post' => WP_Post also works)
 *         'date'         => true,
 *         'reading_time' => false, // 08-blog rows show the date only
 *         'category'     => true,  // the trailing Tag
 *         'excerpt'      => true,
 *         'divider'      => true,  // the hairline under the row
 *         'class'        => '',
 *         'style'        => '',    // appended last
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
		'reading_time' => false,
		'category'     => true,
		'excerpt'      => true,
		'divider'      => true,
		'class'        => '',
		'style'        => '',
		'attrs'        => array(),
	)
);

$intera_row_post = null !== $args['post'] ? get_post( $args['post'] ) : get_post( (int) $args['post_id'] > 0 ? (int) $args['post_id'] : null );

if ( ! $intera_row_post ) {
	return;
}

$intera_row_id = $intera_row_post->ID;

$intera_row_style = 'display:flex;flex-wrap:wrap;gap:20px;align-items:baseline;padding:24px 0';

if ( $args['divider'] ) {
	$intera_row_style .= ';border-bottom:1px solid var(--border-hairline)';
}

if ( $args['style'] ) {
	$intera_row_style .= ';' . $args['style'];
}

$intera_row_class = 'itr-list-row';

if ( $args['class'] ) {
	$intera_row_class .= ' ' . $args['class'];
}

$intera_row_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_row_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_row_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

$intera_row_excerpt = $args['excerpt'] ? get_the_excerpt( $intera_row_post ) : '';
$intera_row_terms   = $args['category'] ? get_the_terms( $intera_row_id, 'category' ) : array();
$intera_row_term    = ( $intera_row_terms && ! is_wp_error( $intera_row_terms ) ) ? reset( $intera_row_terms ) : null;
?>
<a class="<?php echo esc_attr( $intera_row_class ); ?>" href="<?php echo esc_url( get_permalink( $intera_row_id ) ); ?>" style="<?php echo esc_attr( $intera_row_style ); ?>"<?php echo $intera_row_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<?php
	if ( $args['date'] || $args['reading_time'] ) {
		get_template_part(
			'template-parts/partials/entry-meta',
			null,
			array(
				'post_id'      => $intera_row_id,
				'variant'      => 'inline',
				'date'         => (bool) $args['date'],
				'reading_time' => (bool) $args['reading_time'],
				'read_label'   => 'short',
				'style'        => 'min-width:96px',
			)
		);
	}
	?>
	<span style="flex:1;min-width:220px">
		<span style="display:block;font-size:var(--text-lg);font-weight:500;color:var(--ink-900);line-height:1.35"><?php echo wp_kses_post( get_the_title( $intera_row_id ) ); ?></span>
		<?php if ( $intera_row_excerpt ) : ?>
			<span style="display:block;font-size:var(--text-sm);color:var(--ink-600);line-height:1.6;margin-top:6px"><?php echo wp_kses_post( $intera_row_excerpt ); ?></span>
		<?php endif; ?>
	</span>
	<?php
	if ( $intera_row_term ) {
		get_template_part(
			'template-parts/components/tag',
			null,
			array( 'text' => $intera_row_term->name )
		);
	}
	?>
</a>
