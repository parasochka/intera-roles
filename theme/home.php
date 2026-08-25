<?php
/**
 * The posts page — ported from `_design/08-blog.dc.html` (recon §10).
 *
 * Three sections, in the export's order: `Blog header`, `Featured` and
 * `Post list` (the two-column grid with the sidebar). `header.php` owns the
 * masthead, the 76px spacer and `<main>`; `footer.php` closes it.
 *
 * What comes from WordPress:
 *
 * | slot                     | source                                              |
 * | ------------------------ | --------------------------------------------------- |
 * | breadcrumb `Home / Blog` | `intera_breadcrumbs()` (auto for `is_home()`)        |
 * | H1                       | the posts page's title                               |
 * | standfirst               | the posts page's excerpt (auto from its content)     |
 * | filter chips             | `get_categories()` + `get_category_link()`           |
 * | featured card            | the first post of the main query on page 1 — which   |
 * |                          | is the sticky post when one is stuck                 |
 * | five list rows           | the rest of the main query, via `partials/post-row`  |
 * | counter + Previous/Next  | `partials/pagination` over the main query            |
 * | sidebar `Categories`     | `get_categories()` with counts                       |
 * | sidebar CTA card         | `partials/sidebar-cta`, option prefix `blog_cta`     |
 * | the documentation note   | `intera_option( 'blog_docs_note' )`                  |
 *
 * The export hardcodes six posts, a `6 of 6 posts` counter and a disabled
 * pager. Here every row is a real post: the query decides how many there are,
 * `partials/post-row` draws each one, and `partials/pagination` draws the
 * counter and the button pair (the design's own row, so its `.intera-pagination`
 * chrome is trimmed back inline to the export's `padding-top: 28px` and no top
 * rule — the last row already ends on a hairline).
 *
 * PORT.md §1: the featured card carries `.itr-lift .itr-feature-card`, so its
 * border, box-shadow and transition are never inline — the classes hold the
 * resting values (`--border-card`, `--shadow-xs`) the export writes. Its
 * `background` is the documented exception: no hover or class touches it.
 * The category rows in the sidebar drop their inline colour for `.itr-rail-row`,
 * which carries both states.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

$intera_blog_query = $GLOBALS['wp_query'];
$intera_blog_paged = max( 1, (int) get_query_var( 'paged' ) );

// The main query, split: one featured card on page 1, the rest as list rows.
$intera_blog_posts    = array_values( (array) $intera_blog_query->posts );
$intera_blog_featured = ( 1 === $intera_blog_paged && $intera_blog_posts ) ? array_shift( $intera_blog_posts ) : null;

// Title and standfirst belong to the page assigned as the posts page.
$intera_blog_page_id = (int) get_option( 'page_for_posts' );
$intera_blog_title   = $intera_blog_page_id ? get_the_title( $intera_blog_page_id ) : single_post_title( '', false );
$intera_blog_lede    = $intera_blog_page_id ? trim( (string) get_the_excerpt( $intera_blog_page_id ) ) : '';
$intera_blog_url     = intera_page_url( 'blog' );

$intera_blog_cats = get_categories( array( 'hide_empty' => true ) );
$intera_blog_note = trim( (string) intera_option( 'blog_docs_note' ) );
?>

<section data-screen-label="Blog header" style="background: var(--surface-page); border-bottom: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(32px, 7vw, 56px) clamp(20px, 5vw, 24px) 36px">
		<?php intera_breadcrumbs(); ?>
		<div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-end; justify-content: space-between; margin-top: 20px">
			<div style="max-width: 620px">
				<h1 style="font-size: clamp(28px, 3vw, 36px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900)"><?php echo esc_html( $intera_blog_title ); ?></h1>
				<?php if ( '' !== $intera_blog_lede ) : ?>
					<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 14px"><?php echo wp_kses_post( $intera_blog_lede ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $intera_blog_cats ) : ?>
				<div style="display: flex; gap: 8px; flex-wrap: wrap">
					<?php
					get_template_part(
						'template-parts/components/tag',
						null,
						array(
							'text'     => __( 'All', 'intera' ),
							'href'     => $intera_blog_url,
							'selected' => true,
						)
					);

					foreach ( $intera_blog_cats as $intera_blog_cat ) {
						get_template_part(
							'template-parts/components/tag',
							null,
							array(
								'text' => $intera_blog_cat->name,
								'href' => (string) get_category_link( $intera_blog_cat ),
							)
						);
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
if ( $intera_blog_featured instanceof WP_Post ) :
	$intera_blog_feat_id    = $intera_blog_featured->ID;
	$intera_blog_feat_terms = get_the_category( $intera_blog_feat_id );
	$intera_blog_feat_term  = $intera_blog_feat_terms ? $intera_blog_feat_terms[0] : null;
	$intera_blog_feat_text  = trim( (string) get_the_excerpt( $intera_blog_featured ) );
	?>
<section data-screen-label="Featured" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(26px, 7vw, 44px) clamp(20px, 5vw, 24px) 0">
		<a href="<?php echo esc_url( get_permalink( $intera_blog_feat_id ) ); ?>" class="itr-lift itr-feature-card" style="display: block; border-radius: var(--radius-xl); overflow: hidden; background: var(--white)">
			<div style="padding: 36px 40px">
				<div style="display: flex; align-items: center; gap: 12px">
					<?php
					if ( $intera_blog_feat_term ) {
						get_template_part(
							'template-parts/components/badge',
							null,
							array(
								'text' => $intera_blog_feat_term->name,
								'tone' => 'accent',
							)
						);
					}

					get_template_part(
						'template-parts/partials/entry-meta',
						null,
						array(
							'post_id'      => $intera_blog_feat_id,
							'variant'      => 'inline',
							'date'         => true,
							'reading_time' => true,
							'read_label'   => 'short',
						)
					);
					?>
				</div>
				<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.28; color: var(--ink-900); margin-top: 16px; max-width: 640px"><?php echo wp_kses_post( get_the_title( $intera_blog_feat_id ) ); ?></h2>
				<?php if ( '' !== $intera_blog_feat_text ) : ?>
					<p style="font-size: var(--text-base); line-height: 1.65; color: var(--ink-600); margin-top: 14px; max-width: 640px"><?php echo wp_kses_post( $intera_blog_feat_text ); ?></p>
				<?php endif; ?>
				<div style="display: flex; align-items: center; gap: 8px; margin-top: 22px; font-size: var(--text-md); color: var(--blue-600); font-weight: 500"><?php esc_html_e( 'Read the story', 'intera' ); ?>
					<?php intera_icon( 'arrow-right', array( 'size' => 16 ) ); ?>
				</div>
			</div>
		</a>
	</div>
</section>
<?php endif; ?>

<section data-screen-label="Post list" style="background: var(--surface-page)">
	<div class="itr-1col" style="--itr-cols: minmax(0, 1fr) minmax(0, 340px); max-width: 1160px; margin: 0 auto; padding: clamp(26px, 7vw, 44px) clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px); gap: 44px; align-items: start">
		<div style="min-width: 0; display: flex; flex-direction: column; gap: 0; border-top: 1px solid var(--border-hairline)">
			<?php
			if ( $intera_blog_posts ) {
				foreach ( $intera_blog_posts as $intera_blog_post ) {
					get_template_part(
						'template-parts/partials/post-row',
						null,
						array(
							'post'         => $intera_blog_post,
							'date'         => true,
							'reading_time' => false,
							'category'     => true,
							'excerpt'      => true,
						)
					);
				}
			} elseif ( ! $intera_blog_featured instanceof WP_Post ) {
				?>
				<p style="padding: 24px 0; font-size: var(--text-md); color: var(--ink-600); line-height: 1.6"><?php esc_html_e( 'Nothing has been published here yet.', 'intera' ); ?></p>
				<?php
			}

			// The export's counter row: mono metadata left, the button pair right.
			get_template_part(
				'template-parts/partials/pagination',
				null,
				array(
					'query' => $intera_blog_query,
					'style' => 'margin-top: 0; padding-top: 28px; border-top: 0',
				)
			);
			?>
		</div>

		<aside style="display: flex; flex-direction: column; gap: 20px; min-width: 0">
			<?php
			if ( $intera_blog_cats ) {
				ob_start();
				?>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php esc_html_e( 'Categories', 'intera' ); ?></div>
				<div style="display: flex; flex-direction: column; gap: 0; margin-top: 14px">
					<?php
					$intera_blog_last = count( $intera_blog_cats ) - 1;

					foreach ( $intera_blog_cats as $intera_blog_index => $intera_blog_cat ) :
						$intera_blog_row = 'display: flex; justify-content: space-between; align-items: center; padding: 11px 0; font-size: var(--text-md)';

						if ( $intera_blog_index !== $intera_blog_last ) {
							$intera_blog_row .= '; border-bottom: 1px solid var(--border-hairline)';
						}
						?>
						<a class="itr-rail-row" href="<?php echo esc_url( (string) get_category_link( $intera_blog_cat ) ); ?>" style="<?php echo esc_attr( $intera_blog_row ); ?>"><?php echo esc_html( $intera_blog_cat->name ); ?> <span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted)"><?php echo esc_html( number_format_i18n( (int) $intera_blog_cat->count ) ); ?></span></a>
					<?php endforeach; ?>
				</div>
				<?php
				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content' => ob_get_clean(),
						'class'   => 'itr-hl',
					)
				);
			}

			get_template_part(
				'template-parts/partials/sidebar-cta',
				null,
				array(
					'option_prefix' => 'blog_cta',
					'accent'        => 'var(--blue-600)',
					'accent_line'   => 'var(--blue-200)',
				)
			);

			if ( '' !== $intera_blog_note ) :
				?>
				<div style="font-size: var(--text-sm); color: var(--ink-600); line-height: 1.6"><?php echo wp_kses_post( $intera_blog_note ); ?></div>
				<?php
			endif;
			?>
		</aside>
	</div>
</section>

<?php
get_footer();
