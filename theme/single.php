<?php
/**
 * Single post — ported from `_design/09-blog-post.dc.html` (recon §11).
 *
 * Two sections, in the export's order: `Post header` (breadcrumb, category
 * badge, mono meta, title, standfirst) and `Post body` (the article beside a
 * sticky rail). The mockup's inline styles are kept verbatim — that is the
 * handoff — and only the dynamic slots are swapped for WordPress.
 *
 * What comes from WordPress:
 *
 * | slot                 | source                                                  |
 * | -------------------- | ------------------------------------------------------- |
 * | breadcrumb           | `intera_breadcrumbs()` (auto: Home / Blog / <category>)  |
 * | category badge       | the post's primary category                             |
 * | mono meta line       | `entry-meta` — published date + reading time            |
 * | title                | `the_title()`                                            |
 * | standfirst           | the post excerpt, when the editor wrote one             |
 * | article body         | `the_content()` inside `.intera-prose`                   |
 * | in-article frame     | the featured image, in the export's `.itr-frame` chrome  |
 * | "In this story" rail | `intera_headings_get()` over the rendered content        |
 * | "Role in this story" | the `role` post linked by `_intera_post_role`            |
 * | closing note         | `intera_option( 'article_note' )`                         |
 * | second button        | `intera_option( 'article_cta_label' )` + contact-request  |
 * | previous / next      | `prev-next` (adjacent posts, across categories)          |
 *
 * The rail is generated: `intera_content_with_heading_ids()` gives every `h2` a
 * stable id and `intera_headings_get()` lists them, so the export's `href="#"`
 * anchors — an unfinished handoff, not a design — become real jump links. Below
 * two headings the rail hides itself, and the role block moves into a plain
 * aside so the column keeps its second half.
 *
 * `Copy link` is progressive enhancement: it ships `hidden`, carries
 * `data-intera-copy`, and `assets/js/intera.js` unhides and wires it. Without
 * JavaScript the control is never shown.
 *
 * PORT.md §1: nothing here writes `background`, `border`, `box-shadow` or
 * `transition` inline on an element that also carries a hover class — the
 * `.itr-frame` and both prev/next cards take their resting values from their
 * partials as custom properties, and the two aside links use `.itr-jump` /
 * `.itr-rail-row` instead of an inline colour.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	// PORT.md's TOC recipe: ids first, list second, print that same HTML third.
	$intera_post_html = intera_content_with_heading_ids( apply_filters( 'the_content', get_the_content() ) );
	$intera_post_toc  = intera_headings_get( $intera_post_html );

	// The rail hides itself below two rows; the role block must survive that.
	$intera_post_rail_rows = 0;

	foreach ( $intera_post_toc as $intera_post_heading ) {
		if ( 2 === (int) $intera_post_heading['level'] && '' !== $intera_post_heading['id'] && '' !== trim( (string) $intera_post_heading['text'] ) ) {
			++$intera_post_rail_rows;
		}
	}

	// The badge and the breadcrumb agree: a top-level category wins.
	$intera_post_term = function_exists( 'intera_breadcrumbs_primary_term' )
		? intera_breadcrumbs_primary_term( get_post(), 'category' )
		: null;

	/*
	 * "Role in this story": the `role` post an editor attached to this story.
	 * `role` is a private post type, so the link goes to the product page's
	 * roles section, never to a single.
	 */
	$intera_post_role_id = (int) get_post_meta( get_the_ID(), '_intera_post_role', true );
	$intera_post_role    = $intera_post_role_id > 0 ? get_post( $intera_post_role_id ) : null;

	if ( $intera_post_role && ( 'role' !== $intera_post_role->post_type || 'publish' !== $intera_post_role->post_status ) ) {
		$intera_post_role = null;
	}

	$intera_post_aside = '';

	if ( $intera_post_role ) {
		$intera_post_role_icon = (string) get_post_meta( $intera_post_role->ID, '_intera_role_icon', true );
		$intera_post_role_line = (string) get_post_meta( $intera_post_role->ID, '_intera_role_summary', true );
		$intera_post_role_page = (string) intera_page_url( 'product' );
		$intera_post_role_url  = '' !== $intera_post_role_page ? $intera_post_role_page . '#roles' : '';
		$intera_post_role_row  = 'display: flex; align-items: center; gap: 10px; font-size: var(--text-md)';

		ob_start();
		?>
		<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500); margin-bottom: 12px"><?php esc_html_e( 'Role in this story', 'intera' ); ?></div>
		<?php if ( '' !== $intera_post_role_url ) : ?>
			<a class="itr-rail-row" href="<?php echo esc_url( $intera_post_role_url ); ?>" style="<?php echo esc_attr( $intera_post_role_row ); ?>">
				<?php
				if ( '' !== $intera_post_role_icon ) {
					intera_icon(
						$intera_post_role_icon,
						array(
							'size'  => 17,
							'color' => 'var(--blue-600)',
						)
					);
				}
				echo esc_html( get_the_title( $intera_post_role ) );
				?>
			</a>
		<?php else : ?>
			<div style="<?php echo esc_attr( $intera_post_role_row . '; color: var(--ink-800)' ); ?>">
				<?php
				if ( '' !== $intera_post_role_icon ) {
					intera_icon(
						$intera_post_role_icon,
						array(
							'size'  => 17,
							'color' => 'var(--blue-600)',
						)
					);
				}
				echo esc_html( get_the_title( $intera_post_role ) );
				?>
			</div>
		<?php endif; ?>
		<?php if ( '' !== trim( $intera_post_role_line ) ) : ?>
			<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 10px"><?php echo esc_html( $intera_post_role_line ); ?></p>
		<?php endif; ?>
		<?php
		$intera_post_aside = trim( (string) ob_get_clean() );
	}

	// The closing strip: the standing note, the copy control and the ask-us button.
	$intera_post_note      = trim( (string) intera_option( 'article_note' ) );
	$intera_post_cta_label = trim( (string) intera_option( 'article_cta_label' ) );
	$intera_post_cta_url   = trim( (string) intera_page_url( 'contact-request' ) );
	?>

<section data-screen-label="Post header" style="background: var(--surface-page); border-bottom: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(30px, 7vw, 52px) clamp(20px, 5vw, 24px) 36px">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 720px; margin-top: 22px">
			<div style="display: flex; align-items: center; gap: 12px">
				<?php
				if ( $intera_post_term instanceof WP_Term ) {
					get_template_part(
						'template-parts/components/badge',
						null,
						array(
							'text' => $intera_post_term->name,
							'tone' => 'accent',
						)
					);
				}

				get_template_part(
					'template-parts/partials/entry-meta',
					null,
					array(
						'variant'      => 'inline',
						'date'         => true,
						'reading_time' => true,
						'read_label'   => 'long',
					)
				);
				?>
			</div>
			<h1 style="font-size: clamp(28px, 3.2vw, 38px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900); margin-top: 16px; text-wrap: pretty"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 18px"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<section data-screen-label="Post body" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(26px, 7vw, 44px) clamp(20px, 5vw, 24px) clamp(46px, 7vw, 80px); display: flex; flex-wrap: wrap; gap: 48px; align-items: flex-start">
		<article <?php post_class( 'intera-article' ); ?> style="flex: 1 1 520px; min-width: 0; max-width: 680px; display: flex; flex-direction: column; gap: 24px; order: 1">
			<div class="intera-prose">
				<?php echo $intera_post_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, filtered by WordPress. ?>
			</div>

			<?php
			/*
			 * The export's in-article product frame. The image is the post's
			 * featured image and the mono bar is its caption, so an editor sets
			 * both in the media library; without a featured image the partial
			 * renders nothing at all.
			 */
			if ( has_post_thumbnail() ) {
				get_template_part(
					'template-parts/partials/screenshot-frame',
					null,
					array(
						'attachment' => get_post_thumbnail_id(),
						'caption'    => (string) wp_get_attachment_caption( get_post_thumbnail_id() ),
						'height'     => '360px',
						'size'       => 'intera-shot-inline',
						'dots'       => false,
						'background' => '',
					)
				);
			}
			?>

			<div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; padding-top: 24px; border-top: 1px solid var(--border-hairline)">
				<?php if ( '' !== $intera_post_note ) : ?>
					<span style="font-size: var(--text-sm); color: var(--ink-500)"><?php echo esc_html( $intera_post_note ); ?></span>
				<?php endif; ?>
				<div style="display: flex; gap: 8px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'     => __( 'Copy link', 'intera' ),
							'variant'   => 'secondary',
							'size'      => 'sm',
							'icon_left' => 'link',
							'attrs'     => array(
								'data-intera-copy'      => (string) get_permalink(),
								'data-intera-copy-done' => __( 'Link copied', 'intera' ),
								'hidden'                => true,
							),
						)
					);

					if ( '' !== $intera_post_cta_label && '' !== $intera_post_cta_url ) {
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label' => $intera_post_cta_label,
								'href'  => $intera_post_cta_url,
								'size'  => 'sm',
							)
						);
					}
					?>
				</div>
			</div>

			<?php
			get_template_part(
				'template-parts/partials/prev-next',
				null,
				array(
					'padding' => '18px',
					'min'     => '240px',
					'style'   => 'margin-top: 8px',
				)
			);

			if ( ( comments_open() || get_comments_number() ) && locate_template( 'comments.php' ) ) {
				echo '<div class="intera-comments">';
				comments_template();
				echo '</div>';
			}
			?>
		</article>

		<?php
		if ( $intera_post_rail_rows > 1 ) {
			get_template_part(
				'template-parts/partials/toc-rail',
				null,
				array(
					'headings'     => $intera_post_toc,
					'title'        => __( 'In this story', 'intera' ),
					'variant'      => 'jump',
					'size'         => 'sm',
					'width'        => '260px',
					'min_width'    => '220px',
					'top'          => '100px',
					'style'        => 'order: 2',
					'footer'       => $intera_post_aside,
					'footer_style' => 'margin-top: 26px; padding-top: 22px; border-top: 1px solid var(--border-hairline)',
				)
			);
		} elseif ( '' !== $intera_post_aside ) {
			// Too few headings for a rail, but the role block still belongs here.
			?>
			<aside class="intera-rail intera-rail--sticky" style="--itr-rail: 260px; --itr-rail-min: 220px; --itr-rail-top: 100px; order: 2">
				<?php echo $intera_post_aside; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted, template-composed markup, escaped above. ?>
			</aside>
			<?php
		}
		?>
	</div>
</section>

	<?php
endwhile;

get_footer();
