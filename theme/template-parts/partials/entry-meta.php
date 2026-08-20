<?php
/**
 * Partial — the mono metadata strip.
 *
 * One source for every `2026-08-04 · 6 min read` line in the export: the blog
 * post header (09-blog-post), the featured card and list rows on 08-blog, the
 * archive cards on 10-blog-category, the doc rows on 13-docs-category, and the
 * hairline-ruled strip above a docs article on 12-docs-article.
 *
 * Everything is read from WordPress: the date and the category from core, the
 * reading time from `intera_reading_time()` (word count at render, never a
 * field), the version stamp from the `_intera_doc_version` meta box — and when
 * that meta is empty the stamp is omitted entirely, as the field's own help
 * text promises.
 *
 * Three shapes, all mono, all from the mockups:
 *
 *   inline  one span, parts joined by ` · `      08-blog, 09-blog-post, doc rows
 *   pair    a flex row, one span per part        10-blog-category cards
 *   rule    `.intera-meta-rule`, hairlines       12-docs-article
 *
 *     get_template_part( 'template-parts/partials/entry-meta', null, array(
 *         'post_id'      => 0,        // 0 = the post in the loop ('post' => WP_Post works too)
 *         'variant'      => 'inline', // inline | pair | rule
 *         'date'         => true,     // published date, Y-m-d
 *         'modified'     => false,    // "Updated Y-m-d" — the docs strip
 *         'category'     => false,    // primary term name, plain mono text
 *         'version'      => false,    // _intera_doc_version, hidden when empty
 *         'reading_time' => true,
 *         'read_label'   => 'long',   // long => "6 min read"; short => "6 min"
 *         'separator'    => '·',      // inline variant only
 *         'class'        => '',
 *         'style'        => '',       // appended last, so it overrides
 *         'attrs'        => array(),
 *     ) );
 *
 * Order is fixed — modified, date, category, version, reading time — because
 * that is the order the mockups print them in.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'post_id'      => 0,
		'post'         => null,
		'variant'      => 'inline',
		'date'         => true,
		'modified'     => false,
		'category'     => false,
		'version'      => false,
		'reading_time' => true,
		'read_label'   => 'long',
		'separator'    => '·',
		'class'        => '',
		'style'        => '',
		'attrs'        => array(),
	)
);

$intera_meta_post = null !== $args['post'] ? get_post( $args['post'] ) : get_post( (int) $args['post_id'] > 0 ? (int) $args['post_id'] : null );

if ( ! $intera_meta_post ) {
	return;
}

$intera_meta_id    = $intera_meta_post->ID;
$intera_meta_parts = array();

if ( $args['modified'] ) {
	$intera_meta_updated = get_the_modified_date( 'Y-m-d', $intera_meta_id );

	if ( $intera_meta_updated ) {
		/* translators: %s: date the article was last changed, Y-m-d. */
		$intera_meta_parts[] = sprintf( __( 'Updated %s', 'intera' ), $intera_meta_updated );
	}
}

if ( $args['date'] ) {
	$intera_meta_published = get_the_date( 'Y-m-d', $intera_meta_id );

	if ( $intera_meta_published ) {
		$intera_meta_parts[] = $intera_meta_published;
	}
}

if ( $args['category'] ) {
	$intera_meta_tax   = ( 'docs' === get_post_type( $intera_meta_post ) ) ? 'docs_category' : 'category';
	$intera_meta_terms = get_the_terms( $intera_meta_id, $intera_meta_tax );

	if ( $intera_meta_terms && ! is_wp_error( $intera_meta_terms ) ) {
		$intera_meta_term    = reset( $intera_meta_terms );
		$intera_meta_parts[] = $intera_meta_term->name;
	}
}

if ( $args['version'] ) {
	$intera_meta_version = get_post_meta( $intera_meta_id, '_intera_doc_version', true );
	$intera_meta_version = is_scalar( $intera_meta_version ) ? trim( (string) $intera_meta_version ) : '';

	// Empty means the article carries no version stamp — print nothing at all.
	if ( '' !== $intera_meta_version ) {
		$intera_meta_parts[] = $intera_meta_version;
	}
}

if ( $args['reading_time'] && function_exists( 'intera_reading_time' ) ) {
	$intera_meta_minutes = intera_reading_time( $intera_meta_id );

	if ( 'short' === $args['read_label'] ) {
		/* translators: %s: whole minutes. */
		$intera_meta_parts[] = sprintf( _n( '%s min', '%s min', $intera_meta_minutes, 'intera' ), number_format_i18n( $intera_meta_minutes ) );
	} else {
		/* translators: %s: whole minutes. */
		$intera_meta_parts[] = sprintf( _n( '%s min read', '%s min read', $intera_meta_minutes, 'intera' ), number_format_i18n( $intera_meta_minutes ) );
	}
}

if ( ! $intera_meta_parts ) {
	return;
}

$intera_meta_variant = in_array( $args['variant'], array( 'inline', 'pair', 'rule' ), true ) ? $args['variant'] : 'inline';

// The mono chrome, verbatim from the export. `rule` takes it from
// `.intera-meta-rule` in assets/css/intera.css instead, hairlines included.
$intera_meta_mono = 'font-family:var(--font-mono);font-size:var(--text-xs);color:var(--ink-400)';

$intera_meta_class = 'rule' === $intera_meta_variant ? 'intera-meta-rule' : '';

if ( $args['class'] ) {
	$intera_meta_class = trim( $intera_meta_class . ' ' . $args['class'] );
}

$intera_meta_style = '';

if ( 'inline' === $intera_meta_variant ) {
	$intera_meta_style = $intera_meta_mono;
} elseif ( 'pair' === $intera_meta_variant ) {
	$intera_meta_style = 'display:flex;align-items:center;gap:12px';
}

if ( $args['style'] ) {
	$intera_meta_style = $intera_meta_style ? $intera_meta_style . ';' . $args['style'] : $args['style'];
}

$intera_meta_attrs = '';

foreach ( (array) $args['attrs'] as $intera_attr_key => $intera_attr_value ) {
	if ( false === $intera_attr_value || null === $intera_attr_value ) {
		continue;
	}
	if ( true === $intera_attr_value ) {
		$intera_meta_attrs .= ' ' . esc_attr( $intera_attr_key );
		continue;
	}
	$intera_meta_attrs .= ' ' . esc_attr( $intera_attr_key ) . '="' . esc_attr( $intera_attr_value ) . '"';
}

$intera_meta_tag = 'inline' === $intera_meta_variant ? 'span' : 'div';

echo '<' . esc_html( $intera_meta_tag )
	. ( $intera_meta_class ? ' class="' . esc_attr( $intera_meta_class ) . '"' : '' )
	. ( $intera_meta_style ? ' style="' . esc_attr( $intera_meta_style ) . '"' : '' )
	. $intera_meta_attrs // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each attribute escaped above.
	. '>';

if ( 'inline' === $intera_meta_variant ) {
	$intera_meta_separator = ' ' . trim( (string) $args['separator'] ) . ' ';

	echo esc_html( implode( $intera_meta_separator, $intera_meta_parts ) );
} else {
	foreach ( $intera_meta_parts as $intera_meta_part ) {
		if ( 'pair' === $intera_meta_variant ) {
			echo '<span style="' . esc_attr( $intera_meta_mono ) . '">' . esc_html( $intera_meta_part ) . '</span>';
		} else {
			echo '<span>' . esc_html( $intera_meta_part ) . '</span>';
		}
	}
}

echo '</' . esc_html( $intera_meta_tag ) . '>';
