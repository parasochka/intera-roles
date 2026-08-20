<?php
/**
 * Partial — archive pagination.
 *
 * No mockup paginates a real archive: the export's only pager is
 * `10-blog-category` L121-L126, a single page with the pair disabled — a mono
 * `Page 1 of 1` on the left of a hairline-topped row, two `secondary sm`
 * Buttons with `chevron-left` / `chevron-right` on the right. Numbered pages
 * are written inside that same vocabulary rather than invented: the DS Button
 * pair keeps its place, and the numbers between them are mono numerals in
 * `.page-numbers` pills, which `assets/css/intera.css` already styles to match
 * a secondary control (`--surface-card`, `--action-secondary-border`,
 * `--control-height-sm`) and fills with `--action-primary` when current.
 *
 * Accessibility is the part the export could not show, so it is built rather
 * than copied: a `<nav>` with an accessible name, real `<a>` elements for every
 * reachable page, `aria-current="page"` on the one you are on (WordPress writes
 * it into `paginate_links()` output), a screen-reader "Page" prefix before each
 * numeral, and a genuinely disabled `<button>` — never a dead link — where
 * there is no previous or next page.
 *
 *     get_template_part( 'template-parts/partials/pagination', null, array(
 *         'query'       => null,   // WP_Query; defaults to the main query
 *         'total'       => 0,      // overrides $query->max_num_pages
 *         'current'     => 0,      // overrides the paged query var
 *         'label'       => '',     // nav accessible name, default "Pagination"
 *         'count'       => true,   // the mono "Page 1 of 3"
 *         'numbers'     => true,   // the numeral pills
 *         'prev_label'  => '',     // default "Previous"
 *         'next_label'  => '',     // default "Next"
 *         'hide_single' => false,  // the design shows the disabled pair, so: no
 *         'mid_size'    => 1,
 *         'end_size'    => 1,
 *         'base'        => '',     // paginate_links() base / format overrides
 *         'format'      => '',
 *         'prev_url'    => '',     // override when get_pagenum_link() cannot know
 *         'next_url'    => '',
 *         'class'       => '', 'style' => '', 'attrs' => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'query'       => null,
		'total'       => 0,
		'current'     => 0,
		'label'       => '',
		'count'       => true,
		'numbers'     => true,
		'prev_label'  => '',
		'next_label'  => '',
		'hide_single' => false,
		'mid_size'    => 1,
		'end_size'    => 1,
		'base'        => '',
		'format'      => '',
		'prev_url'    => '',
		'next_url'    => '',
		'class'       => '',
		'style'       => '',
		'attrs'       => array(),
	)
);

$intera_pg_query = $args['query'] instanceof WP_Query ? $args['query'] : $GLOBALS['wp_query'];

$intera_pg_total = (int) $args['total'];

if ( $intera_pg_total < 1 ) {
	$intera_pg_total = (int) $intera_pg_query->max_num_pages;
}

if ( $intera_pg_total < 1 ) {
	$intera_pg_total = 1;
}

$intera_pg_current = (int) $args['current'];

if ( $intera_pg_current < 1 ) {
	$intera_pg_current = (int) get_query_var( 'paged' );
}

if ( $intera_pg_current < 1 ) {
	$intera_pg_current = (int) get_query_var( 'page' );
}

$intera_pg_current = max( 1, min( $intera_pg_current, $intera_pg_total ) );

if ( $args['hide_single'] && $intera_pg_total < 2 ) {
	return;
}

$intera_pg_label = '' !== $args['label'] ? $args['label'] : __( 'Pagination', 'intera' );

$intera_pg_class = 'intera-pagination';

if ( '' !== $args['class'] ) {
	$intera_pg_class .= ' ' . $args['class'];
}

$intera_pg_attrs = '';

foreach ( (array) $args['attrs'] as $intera_pg_key => $intera_pg_value ) {
	if ( false === $intera_pg_value || null === $intera_pg_value ) {
		continue;
	}
	if ( true === $intera_pg_value ) {
		$intera_pg_attrs .= ' ' . esc_attr( $intera_pg_key );
		continue;
	}
	$intera_pg_attrs .= ' ' . esc_attr( $intera_pg_key ) . '="' . esc_attr( $intera_pg_value ) . '"';
}

$intera_pg_prev_url = '';
$intera_pg_next_url = '';

if ( $intera_pg_current > 1 ) {
	$intera_pg_prev_url = '' !== $args['prev_url'] ? $args['prev_url'] : (string) get_pagenum_link( $intera_pg_current - 1 );
}

if ( $intera_pg_current < $intera_pg_total ) {
	$intera_pg_next_url = '' !== $args['next_url'] ? $args['next_url'] : (string) get_pagenum_link( $intera_pg_current + 1 );
}

// The numeral pills. WordPress writes .page-numbers/.current/.dots and the
// aria-current the CSS and screen readers expect, so its output is used as-is.
$intera_pg_numbers = array();

if ( $args['numbers'] && $intera_pg_total > 1 ) {
	$intera_pg_big  = 999999999;
	$intera_pg_base = '' !== $args['base']
		? $args['base']
		: str_replace( $intera_pg_big, '%#%', esc_url( get_pagenum_link( $intera_pg_big ) ) );

	$intera_pg_links = paginate_links(
		array(
			'base'               => $intera_pg_base,
			'format'             => $args['format'],
			'current'            => $intera_pg_current,
			'total'              => $intera_pg_total,
			'mid_size'           => (int) $args['mid_size'],
			'end_size'           => (int) $args['end_size'],
			'prev_next'          => false,
			'type'               => 'array',
			'aria_current'       => 'page',
			'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'intera' ) . ' </span>',
		)
	);

	if ( is_array( $intera_pg_links ) ) {
		$intera_pg_numbers = $intera_pg_links;
	}

	/*
	 * Current WordPress marks the current page itself. Older cores did not, and
	 * the one thing this pager must not ship without is aria-current, so it is
	 * added when it is missing rather than assumed.
	 */
	foreach ( $intera_pg_numbers as $intera_pg_index => $intera_pg_link ) {
		if ( false !== strpos( $intera_pg_link, 'page-numbers current' ) && false === strpos( $intera_pg_link, 'aria-current' ) ) {
			$intera_pg_numbers[ $intera_pg_index ] = preg_replace( '/^<span /', '<span aria-current="page" ', $intera_pg_link, 1 );
		}
	}
}

$intera_pg_prev_text = '' !== $args['prev_label'] ? $args['prev_label'] : __( 'Previous', 'intera' );
$intera_pg_next_text = '' !== $args['next_label'] ? $args['next_label'] : __( 'Next', 'intera' );
?>
<nav class="<?php echo esc_attr( $intera_pg_class ); ?>" aria-label="<?php echo esc_attr( $intera_pg_label ); ?>"<?php echo '' !== $args['style'] ? ' style="' . esc_attr( $args['style'] ) . '"' : ''; ?><?php echo $intera_pg_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<?php if ( $args['count'] ) : ?>
		<span>
			<?php
			printf(
				/* translators: 1: current page number, 2: total number of pages. */
				esc_html__( 'Page %1$s of %2$s', 'intera' ),
				esc_html( number_format_i18n( $intera_pg_current ) ),
				esc_html( number_format_i18n( $intera_pg_total ) )
			);
			?>
		</span>
	<?php endif; ?>
	<div class="nav-links">
		<?php
		get_template_part(
			'template-parts/components/button',
			null,
			array(
				'label'     => $intera_pg_prev_text,
				'href'      => $intera_pg_prev_url,
				'variant'   => 'secondary',
				'size'      => 'sm',
				'icon_left' => 'chevron-left',
				'disabled'  => ( '' === $intera_pg_prev_url ),
				'attrs'     => '' !== $intera_pg_prev_url ? array( 'rel' => 'prev' ) : array(),
			)
		);

		foreach ( $intera_pg_numbers as $intera_pg_number ) {
			echo $intera_pg_number; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- paginate_links() output.
		}

		get_template_part(
			'template-parts/components/button',
			null,
			array(
				'label'      => $intera_pg_next_text,
				'href'       => $intera_pg_next_url,
				'variant'    => 'secondary',
				'size'       => 'sm',
				'icon_right' => 'chevron-right',
				'disabled'   => ( '' === $intera_pg_next_url ),
				'attrs'      => '' !== $intera_pg_next_url ? array( 'rel' => 'next' ) : array(),
			)
		);
		?>
	</div>
</nav>
