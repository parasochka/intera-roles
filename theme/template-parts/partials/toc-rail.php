<?php
/**
 * Partial — the sticky "Contents" rail (04-faq, 07-policy, 09-blog-post, 12-docs-article).
 *
 * One rail, two skins, because the export draws two:
 *
 * - `jump`  — 04-faq L81-L92 ("On this page"), 07-policy L83-L96 ("Contents") and
 *   09-blog-post L144-L150 ("In this story"): padded rows with a radius that
 *   tint on hover. That hover is background-only, so the row keeps its inline
 *   padding, radius and colour and just adds `.itr-jump` (PORT.md, "Links").
 *   The inline `color` is deliberate here: nothing in the hover touches colour,
 *   and without it `a:not(.itr-btn)` would paint every row link blue.
 * - `rail`  — 12-docs-article L174-L186: a hairline left rule, tighter rows, and
 *   a colour hover. That one *is* a colour hover, so the inline colour is dropped
 *   and `a.itr-link-blue` carries both states.
 *
 * The heading list is `intera_headings_get()` output — ids are read exactly as
 * `intera_content_with_heading_ids()` wrote them and are never re-slugged here.
 * Fewer than two entries and the partial renders **nothing at all**: a rail with
 * one row is noise. Templates that must show the block below the rule regardless
 * render it themselves.
 *
 * 09-blog-post's rail anchors are literally `href="#"` in the export — an
 * unfinished handoff, not a design. Real ids are generated.
 *
 *     get_template_part( 'template-parts/partials/toc-rail', null, array(
 *         'headings'     => intera_headings_get( $html ), // or null + 'content'
 *         'content'      => '',        // rendered HTML, parsed when 'headings' is null
 *         'title'        => '',        // default: "On this page"
 *         'variant'      => 'jump',    // jump | rail
 *         'size'         => 'sm',      // sm | md — 04-faq is the only md rail
 *         'levels'       => array( 2 ),// heading levels listed; add 3 to nest h3
 *         'footer'       => '',        // trusted HTML under the hairline
 *         'footer_style' => '',        // override the hairline block's spacing
 *         'width'        => '240px',   // flex basis — 260px on 04-faq/09, 200px on 12
 *         'min_width'    => '220px',
 *         'sticky'       => true,
 *         'top'          => '100px',
 *         'tag'          => 'aside',
 *         'class'        => '',
 *         'style'        => '',        // appended last — 09 passes "order: 2"
 *         'attrs'        => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'headings'     => null,
		'content'      => '',
		'title'        => '',
		'variant'      => 'jump',
		'size'         => 'sm',
		'levels'       => array( 2 ),
		'footer'       => '',
		'footer_style' => '',
		'width'        => '240px',
		'min_width'    => '220px',
		'sticky'       => true,
		'top'          => '100px',
		'tag'          => 'aside',
		'class'        => '',
		'style'        => '',
		'attrs'        => array(),
	)
);

$intera_toc_source = $args['headings'];

if ( ! is_array( $intera_toc_source ) ) {
	$intera_toc_source = ( '' !== $args['content'] && function_exists( 'intera_headings_get' ) )
		? intera_headings_get( $args['content'] )
		: array();
}

$intera_toc_levels = array_map( 'absint', (array) $args['levels'] );

if ( ! $intera_toc_levels ) {
	$intera_toc_levels = array( 2 );
}

// Keep only rows that can actually be linked: an id and something to read.
$intera_toc_rows = array();

foreach ( $intera_toc_source as $intera_toc_heading ) {
	if ( ! is_array( $intera_toc_heading ) ) {
		continue;
	}

	$intera_toc_id_attr = isset( $intera_toc_heading['id'] ) ? (string) $intera_toc_heading['id'] : '';
	$intera_toc_text    = isset( $intera_toc_heading['text'] ) ? trim( (string) $intera_toc_heading['text'] ) : '';
	$intera_toc_level   = isset( $intera_toc_heading['level'] ) ? (int) $intera_toc_heading['level'] : 2;

	if ( '' === $intera_toc_id_attr || '' === $intera_toc_text ) {
		continue;
	}

	if ( ! in_array( $intera_toc_level, $intera_toc_levels, true ) ) {
		continue;
	}

	$intera_toc_rows[] = array(
		'id'    => $intera_toc_id_attr,
		'text'  => $intera_toc_text,
		'level' => $intera_toc_level,
	);
}

// One entry is noise; nothing is rendered, footer included.
if ( count( $intera_toc_rows ) < 2 ) {
	return;
}

$intera_toc_variant = 'rail' === $args['variant'] ? 'rail' : 'jump';
$intera_toc_size    = 'md' === $args['size'] ? 'md' : 'sm';

$intera_toc_tag = in_array( $args['tag'], array( 'aside', 'div', 'nav' ), true ) ? $args['tag'] : 'aside';

$intera_toc_title = '' !== $args['title'] ? $args['title'] : __( 'On this page', 'intera' );

// A distinct id per rail, so two rails on one page keep separate labels.
$GLOBALS['intera_toc_seq'] = isset( $GLOBALS['intera_toc_seq'] ) ? (int) $GLOBALS['intera_toc_seq'] + 1 : 1;
$intera_toc_label_id       = 'intera-toc-' . (int) $GLOBALS['intera_toc_seq'];

$intera_toc_style = '';

if ( $args['sticky'] ) {
	$intera_toc_style .= 'position: sticky; top: ' . $args['top'] . '; ';
}

$intera_toc_style .= 'flex: 0 1 ' . $args['width'] . '; min-width: ' . $args['min_width'];

if ( '' !== $args['style'] ) {
	$intera_toc_style .= '; ' . $args['style'];
}

$intera_toc_class = 'intera-toc intera-toc--' . $intera_toc_variant;

if ( '' !== $args['class'] ) {
	$intera_toc_class .= ' ' . $args['class'];
}

$intera_toc_attrs = '';

foreach ( (array) $args['attrs'] as $intera_toc_key => $intera_toc_value ) {
	if ( false === $intera_toc_value || null === $intera_toc_value ) {
		continue;
	}
	if ( true === $intera_toc_value ) {
		$intera_toc_attrs .= ' ' . esc_attr( $intera_toc_key );
		continue;
	}
	$intera_toc_attrs .= ' ' . esc_attr( $intera_toc_key ) . '="' . esc_attr( $intera_toc_value ) . '"';
}

// The eyebrow above the list — identical on all four screens bar its margin.
$intera_toc_eyebrow_style = 'font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500); margin-bottom: '
	. ( 'rail' === $intera_toc_variant ? '10px' : '12px' );

$intera_toc_list_style = 'rail' === $intera_toc_variant
	? 'display: flex; flex-direction: column; gap: 1px; border-left: 1px solid var(--border-hairline)'
	: 'display: flex; flex-direction: column; gap: 2px';

$intera_toc_footer_style = '' !== $args['footer_style']
	? $args['footer_style']
	: 'margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-hairline)';
?>
<<?php echo esc_html( $intera_toc_tag ); ?> class="<?php echo esc_attr( $intera_toc_class ); ?>" style="<?php echo esc_attr( $intera_toc_style ); ?>"<?php echo $intera_toc_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above. ?>>
	<nav aria-labelledby="<?php echo esc_attr( $intera_toc_label_id ); ?>">
		<div id="<?php echo esc_attr( $intera_toc_label_id ); ?>" style="<?php echo esc_attr( $intera_toc_eyebrow_style ); ?>"><?php echo esc_html( $intera_toc_title ); ?></div>
		<div style="<?php echo esc_attr( $intera_toc_list_style ); ?>">
			<?php
			foreach ( $intera_toc_rows as $intera_toc_row ) {
				$intera_toc_nested = $intera_toc_row['level'] > 2;

				if ( 'rail' === $intera_toc_variant ) {
					$intera_toc_row_class = 'itr-link-blue';
					$intera_toc_row_style = 'font-size: var(--text-sm); padding: 5px 12px';

					if ( $intera_toc_nested ) {
						$intera_toc_row_style .= '; padding-left: 26px';
					}
				} else {
					$intera_toc_row_class = 'itr-jump';
					$intera_toc_row_style = 'md' === $intera_toc_size
						? 'font-size: var(--text-md); color: var(--ink-700); padding: 8px 12px; border-radius: var(--radius-md)'
						: 'font-size: var(--text-sm); color: var(--ink-700); padding: 7px 12px; border-radius: var(--radius-md)';

					if ( $intera_toc_nested ) {
						$intera_toc_row_style .= '; padding-left: 24px';
					}
				}
				?>
				<a class="<?php echo esc_attr( $intera_toc_row_class ); ?>" href="<?php echo esc_url( '#' . $intera_toc_row['id'] ); ?>" style="<?php echo esc_attr( $intera_toc_row_style ); ?>"><?php echo esc_html( $intera_toc_row['text'] ); ?></a>
				<?php
			}
			?>
		</div>
	</nav>
	<?php if ( '' !== $args['footer'] ) : ?>
		<div style="<?php echo esc_attr( $intera_toc_footer_style ); ?>">
			<?php echo $args['footer']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted, template-composed markup. ?>
		</div>
	<?php endif; ?>
</<?php echo esc_html( $intera_toc_tag ); ?>>
