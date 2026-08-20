<?php
/**
 * Template Name: FAQ
 *
 * Ported from `_design/04-faq.dc.html` (recon §6). Two sections, in the export's
 * order: `FAQ header` and `FAQ content` (sticky rail beside the accordion).
 *
 * What comes from WordPress:
 *
 * | slot               | source                                                   |
 * | ------------------ | -------------------------------------------------------- |
 * | breadcrumb         | `intera_breadcrumbs()` (auto: Home / <title>)             |
 * | header heading     | `the_title()`                                             |
 * | header lede        | the page excerpt                                          |
 * | every Q&A pair     | the page content — a core Details block per question      |
 * | the group headings | the page content — one `h2` per group                     |
 * | "On this page"     | `intera_headings_get()` over the rendered content         |
 * | rail blurb + CTA   | `faq_rail_body` / `faq_rail_cta_label` + `contact-request` |
 *
 * The export hardcodes sixteen questions in three groups. Here an editor writes
 * them — an `h2` per group, a core Details block per question — and the template
 * re-dresses that markup with the handoff's own accordion styling plus the
 * chevron the export draws with `Icon name="chevron-down"`. Nothing in this file
 * is marketing copy, and the rail is generated from the headings, so adding a
 * group in the editor adds a rail row.
 *
 * The accordion stays native `<details>`/`<summary>`: no JavaScript, and the
 * five `summary` rules from the export's `<helmet>` already live in
 * `assets/css/intera.css` (`summary`, `.itr-faq-summary`, the chevron rotation).
 * PORT.md: the summary's resting colour is the class, never inline.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'intera_faq_attrs_merge' ) ) :
	/**
	 * Merges a class and a style into an existing tag's attribute string.
	 *
	 * The tags come from rendered block markup, so they may already carry
	 * `class="wp-block-details"` or an editor's own inline style. Ours is
	 * appended last, which is also how it wins.
	 *
	 * @param string $attrs Raw attribute string captured from the opening tag.
	 * @param string $class Class to add, or ''.
	 * @param string $style Declarations to add, or ''.
	 * @return string Attribute string, ready to re-emit.
	 */
	function intera_faq_attrs_merge( $attrs, $class, $style ) {
		$attrs = (string) $attrs;

		if ( '' !== $class ) {
			if ( preg_match( '/\sclass\s*=\s*(["\'])(.*?)\1/is', $attrs, $intera_faq_found ) ) {
				$attrs = str_replace(
					$intera_faq_found[0],
					' class="' . esc_attr( trim( $intera_faq_found[2] . ' ' . $class ) ) . '"',
					$attrs
				);
			} else {
				$attrs .= ' class="' . esc_attr( $class ) . '"';
			}
		}

		if ( '' !== $style ) {
			if ( preg_match( '/\sstyle\s*=\s*(["\'])(.*?)\1/is', $attrs, $intera_faq_found ) ) {
				$intera_faq_existing = rtrim( trim( $intera_faq_found[2] ), ';' );

				$attrs = str_replace(
					$intera_faq_found[0],
					' style="' . esc_attr( '' !== $intera_faq_existing ? $intera_faq_existing . '; ' . $style : $style ) . '"',
					$attrs
				);
			} else {
				$attrs .= ' style="' . esc_attr( $style ) . '"';
			}
		}

		return $attrs;
	}
endif;

if ( ! function_exists( 'intera_faq_accordion' ) ) :
	/**
	 * Dresses the editor's Details blocks as the export's accordion.
	 *
	 * Per row: the hairline rule under it, flush stacking (the prose rhythm is
	 * cancelled inline), the summary's flex row with the chevron on the right,
	 * and the answer in its own 640px prose column with the export's 20px of
	 * breathing room below it. The first row after a heading also takes the top
	 * rule the export draws on the group's wrapper.
	 *
	 * Content without a Details block passes through untouched.
	 *
	 * @param string $html Rendered post content.
	 * @return string Same content, accordion rows restyled.
	 */
	function intera_faq_accordion( $html ) {
		if ( false === stripos( $html, '<details' ) ) {
			return $html;
		}

		$intera_faq_chevron = function_exists( 'intera_icon_get' )
			? intera_icon_get( 'chevron-down', array( 'size' => 18, 'color' => 'var(--ink-400)' ) )
			: '';

		$intera_faq_opens_group = true;

		$html = preg_replace_callback(
			'#<h[23]\b[^>]*>|<details\b([^>]*)>(.*?)</details>#is',
			static function ( $intera_faq_match ) use ( $intera_faq_chevron, &$intera_faq_opens_group ) {
				// A heading starts a new group: the next row draws the top rule.
				if ( 0 !== stripos( $intera_faq_match[0], '<details' ) ) {
					$intera_faq_opens_group = true;

					return $intera_faq_match[0];
				}

				$intera_faq_style = $intera_faq_opens_group
					? 'margin-top: 18px; border-top: 1px solid var(--border-hairline); border-bottom: 1px solid var(--border-hairline)'
					: 'margin-top: 0; border-bottom: 1px solid var(--border-hairline)';

				$intera_faq_opens_group = false;

				$intera_faq_inner = $intera_faq_match[2];

				// The summary: the export's row, the hover class, the chevron.
				$intera_faq_inner = preg_replace_callback(
					'#<summary\b([^>]*)>(.*?)</summary>#is',
					static function ( $intera_faq_summary ) use ( $intera_faq_chevron ) {
						return '<summary'
							. intera_faq_attrs_merge(
								$intera_faq_summary[1],
								'itr-faq-summary',
								'display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 0; font-size: var(--text-lg); font-weight: 500'
							)
							. '>' . $intera_faq_summary[2] . $intera_faq_chevron . '</summary>';
					},
					$intera_faq_inner,
					1
				);

				// The answer keeps the export's line-height and ink.
				$intera_faq_answer = preg_split( '#(?<=</summary>)#i', $intera_faq_inner, 2 );

				if ( 2 === count( $intera_faq_answer ) && '' !== trim( $intera_faq_answer[1] ) ) {
					$intera_faq_body = preg_replace_callback(
						'#<p\b([^>]*)>#is',
						static function ( $intera_faq_para ) {
							return '<p' . intera_faq_attrs_merge( $intera_faq_para[1], '', 'line-height: 1.65; color: var(--ink-600)' ) . '>';
						},
						$intera_faq_answer[1]
					);

					$intera_faq_inner = $intera_faq_answer[0]
						. '<div class="intera-prose" style="--itr-prose-max: 640px; padding-bottom: 20px">'
						. $intera_faq_body
						. '</div>';
				}

				return '<details' . intera_faq_attrs_merge( $intera_faq_match[1], '', $intera_faq_style ) . '>'
					. $intera_faq_inner . '</details>';
			},
			$html
		);

		return $html;
	}
endif;

get_header();

if ( have_posts() ) {
	the_post();
}

// PORT.md: parse the ids first, list them second, print that same HTML third.
$intera_faq_html = intera_content_with_heading_ids( apply_filters( 'the_content', get_the_content() ) );
$intera_faq_toc  = intera_headings_get( $intera_faq_html );
$intera_faq_html = intera_faq_accordion( $intera_faq_html );

// The rail hides itself below two entries, so the aside falls back to the blurb alone.
$intera_faq_rail_rows = 0;

foreach ( $intera_faq_toc as $intera_faq_heading ) {
	if ( 2 === (int) $intera_faq_heading['level'] && '' !== $intera_faq_heading['id'] && '' !== trim( (string) $intera_faq_heading['text'] ) ) {
		++$intera_faq_rail_rows;
	}
}

// The block under the rail's hairline: the Customizer blurb and the ask-us button.
$intera_faq_blurb    = trim( (string) intera_option( 'faq_rail_body', '' ) );
$intera_faq_ask      = trim( (string) intera_option( 'faq_rail_cta_label', __( 'Ask a question', 'intera' ) ) );
$intera_faq_ask_url  = trim( (string) intera_page_url( 'contact-request' ) );
$intera_faq_aside    = '';

if ( '' !== $intera_faq_blurb || ( '' !== $intera_faq_ask && '' !== $intera_faq_ask_url ) ) {
	ob_start();

	if ( '' !== $intera_faq_blurb ) {
		?>
		<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600)"><?php echo wp_kses_post( $intera_faq_blurb ); ?></p>
		<?php
	}

	if ( '' !== $intera_faq_ask && '' !== $intera_faq_ask_url ) {
		?>
		<div style="margin-top: 14px">
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label'   => $intera_faq_ask,
					'href'    => $intera_faq_ask_url,
					'variant' => 'secondary',
					'size'    => 'sm',
				)
			);
			?>
		</div>
		<?php
	}

	$intera_faq_aside = trim( (string) ob_get_clean() );
}
?>

<section data-screen-label="FAQ header" style="background: var(--surface-page); border-bottom: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(35px, 7vw, 60px) clamp(20px, 5vw, 24px) clamp(26px, 7vw, 44px)">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 640px; margin-top: 22px">
			<h1 style="font-size: clamp(30px, 3.2vw, 38px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.14; color: var(--ink-900)"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 18px"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<section data-screen-label="FAQ content" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(28px, 7vw, 48px) clamp(20px, 5vw, 24px) clamp(46px, 7vw, 80px); display: flex; flex-wrap: wrap; gap: 48px; align-items: flex-start">
		<?php
		if ( $intera_faq_rail_rows > 1 ) {
			get_template_part(
				'template-parts/partials/toc-rail',
				null,
				array(
					'headings'     => $intera_faq_toc,
					'title'        => __( 'On this page', 'intera' ),
					'variant'      => 'jump',
					'size'         => 'md',
					'width'        => '260px',
					'min_width'    => '220px',
					'top'          => '100px',
					'footer'       => $intera_faq_aside,
					'footer_style' => 'margin-top: 28px; padding-top: 24px; border-top: 1px solid var(--border-hairline)',
				)
			);
		} elseif ( '' !== $intera_faq_aside ) {
			// Too few headings for a rail, but the ask-us block still belongs here.
			?>
			<aside style="position: sticky; top: 100px; flex: 0 1 260px; min-width: 220px">
				<?php echo $intera_faq_aside; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted, template-composed markup, escaped above. ?>
			</aside>
			<?php
		}
		?>

		<div class="intera-prose" style="--itr-prose-max: 760px; flex: 1 1 520px">
			<?php echo $intera_faq_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, filtered by WordPress. ?>
		</div>
	</div>
</section>

<?php
get_footer();
