<?php
/**
 * Partial — the accented sidebar card (08-blog L160-L164, 10-blog-category
 * L138-L142, 13-docs-category L185-L189).
 *
 * A DS `Card` with an accent stripe and `.itr-hl`: a bold line, a short
 * paragraph, and one full-width `sm` button pointing at the contact-request
 * page. The three instances differ only in accent colour and wording, so the
 * accent is a prop and every word is editor-owned: heading, body and button
 * label are read with `intera_option()`, and the target with
 * `intera_page_url( 'contact-request' )` — never a hardcoded slug.
 *
 * `option_prefix` picks which set of options a placement reads, so the three
 * cards stay separately editable without a marketing string ever living in PHP:
 *
 *     option_prefix   options read
 *     sidebar_cta     sidebar_cta_heading / _body / _label / _url   (default)
 *     blog_cta        blog_cta_heading / _body / _label / _url      (08-blog)
 *     docs_cta        docs_cta_heading  / _body / _label / _url     (13-docs-category)
 *
 * All twelve are registered in `inc/customizer.php` with the export's own
 * wording as the default, which is what makes a card appear on a site nobody
 * has been through yet. That registration is load-bearing rather than a
 * convenience: this partial returns early when the heading and the body are
 * both empty, so for as long as the three prefixes had no defaults the card the
 * design draws beside the blog, every category and every archive rendered
 * nowhere at all.
 *
 * An explicitly passed value always wins over the option, so a template that
 * already holds the copy (a term description, say) can hand it straight over.
 * Nothing is rendered when there is no heading and no body: an accent stripe
 * over an empty card is worse than no card — which is also how an editor
 * switches a card off, by clearing both fields.
 *
 * PORT.md §1 is handled by the `card` component itself — it emits `--itr-edge`
 * and keeps border, background and shadow in `.itr-hl`, so nothing here writes
 * them inline.
 *
 *     get_template_part( 'template-parts/partials/sidebar-cta', null, array(
 *         'option_prefix' => 'sidebar_cta',
 *         'heading'       => '',   // '' -> intera_option( '<prefix>_heading' )
 *         'body'          => '',   // '' -> intera_option( '<prefix>_body' )
 *         'cta_label'     => '',   // '' -> intera_option( '<prefix>_label' )
 *         'cta_url'       => '',   // '' -> intera_option( '<prefix>_url' )
 *                                  //       -> intera_page_url( 'contact-request' )
 *         'accent'        => 'var(--blue-600)',      // 10 uses --signal-pattern,
 *         'accent_line'   => 'var(--blue-200)',      // 13 --signal-reconciliation
 *         'variant'       => 'primary',              // button variant
 *         'heading_tag'   => 'div',                  // div|h2|h3|h4
 *         'padding'       => 'default',
 *         'class'         => '', 'style' => '', 'attrs' => array(),
 *     ) );
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'option_prefix' => 'sidebar_cta',
		'heading'       => '',
		'body'          => '',
		'cta_label'     => '',
		'cta_url'       => '',
		'accent'        => 'var(--blue-600)',
		'accent_line'   => 'var(--blue-200)',
		'variant'       => 'primary',
		'heading_tag'   => 'div',
		'padding'       => 'default',
		'class'         => '',
		'style'         => '',
		'attrs'         => array(),
	)
);

$intera_cta_prefix = sanitize_key( (string) $args['option_prefix'] );

if ( '' === $intera_cta_prefix ) {
	$intera_cta_prefix = 'sidebar_cta';
}

/**
 * An explicit value wins; otherwise the option this placement reads.
 *
 * @param string $explicit Value passed by the template.
 * @param string $suffix   Option key suffix, appended to the prefix.
 * @return string
 */
$intera_cta_value = static function ( $explicit, $suffix ) use ( $intera_cta_prefix ) {
	if ( '' !== $explicit ) {
		return $explicit;
	}

	/*
	 * One argument, so the Customizer's registered default is what an untouched
	 * option answers with. Passing '' here instead — which this did until the
	 * defaults existed — meant `blog_cta` and `sidebar_cta` resolved to nothing
	 * and the card the design draws beside the blog, every category and every
	 * archive was never rendered at all. A prefix nobody registered still
	 * resolves to '', which is what keeps that case silent.
	 */
	return function_exists( 'intera_option' )
		? (string) intera_option( $intera_cta_prefix . '_' . $suffix )
		: '';
};

$intera_cta_heading = $intera_cta_value( (string) $args['heading'], 'heading' );
$intera_cta_body    = $intera_cta_value( (string) $args['body'], 'body' );
$intera_cta_label   = $intera_cta_value( (string) $args['cta_label'], 'label' );
$intera_cta_url     = $intera_cta_value( (string) $args['cta_url'], 'url' );

if ( '' === $intera_cta_url && function_exists( 'intera_page_url' ) ) {
	$intera_cta_url = (string) intera_page_url( 'contact-request' );
}

if ( '' === $intera_cta_heading && '' === $intera_cta_body ) {
	return;
}

$intera_cta_heading_tag = in_array( $args['heading_tag'], array( 'div', 'h2', 'h3', 'h4' ), true )
	? $args['heading_tag']
	: 'div';

ob_start();

if ( '' !== $intera_cta_heading ) {
	$intera_cta_heading_style = 'font-size: var(--text-md); font-weight: 600; color: var(--ink-900)';

	if ( 'div' !== $intera_cta_heading_tag ) {
		$intera_cta_heading_style .= '; margin: 0';
	}

	printf(
		'<%1$s style="%2$s">%3$s</%1$s>',
		esc_html( $intera_cta_heading_tag ),
		esc_attr( $intera_cta_heading_style ),
		wp_kses_post( $intera_cta_heading )
	);
}

if ( '' !== $intera_cta_body ) {
	echo '<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 8px">' . wp_kses_post( $intera_cta_body ) . '</p>';
}

if ( '' !== $intera_cta_label && '' !== $intera_cta_url ) {
	echo '<div style="margin-top: 14px">';
	get_template_part(
		'template-parts/components/button',
		null,
		array(
			'label'   => $intera_cta_label,
			'href'    => $intera_cta_url,
			'variant' => $args['variant'],
			'size'    => 'sm',
			'block'   => true,
		)
	);
	echo '</div>';
}

$intera_cta_content = ob_get_clean();

$intera_cta_class = 'itr-hl';

if ( '' !== $args['class'] ) {
	$intera_cta_class .= ' ' . $args['class'];
}

get_template_part(
	'template-parts/components/card',
	null,
	array(
		'content'     => $intera_cta_content,
		'padding'     => $args['padding'],
		'accent'      => $args['accent'],
		'accent_line' => $args['accent_line'],
		'class'       => $intera_cta_class,
		'style'       => $args['style'],
		'attrs'       => $args['attrs'],
	)
);
