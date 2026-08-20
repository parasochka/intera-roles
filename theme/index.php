<?php
/**
 * The last-resort template — whatever WordPress could not match anywhere else.
 *
 * Not in the export, and deliberately the plainest file in the theme: the
 * header band every inner page opens with, then either one article or one list
 * of rows. `home.php`, `archive.php`, `category.php`, `search.php`, `single.php`
 * and `page.php` catch every request the site actually serves, so this file
 * exists to be correct rather than designed — and to keep a site with nothing
 * published from rendering an empty container.
 *
 * What comes from WordPress:
 *
 * | slot                    | source                                            |
 * | ----------------------- | ------------------------------------------------- |
 * | breadcrumb              | `intera_breadcrumbs()` (auto for every request)    |
 * | H1                      | the queried object: post title, posts-page title,  |
 * |                         | `get_the_archive_title()`, the search term, or the |
 * |                         | site name when the request names nothing           |
 * | `N posts`               | `$wp_query->found_posts`, hidden at zero           |
 * | a single post           | `the_content()` inside `.intera-prose`             |
 * | a list                  | the main query, via `partials/post-row`            |
 * | counter + Previous/Next | `partials/pagination` over the main query          |
 *
 * The empty state is the case this file is most likely to reach, so it is
 * written rather than defaulted (ds-rules §2: state the fact, then the next
 * step). Its way out is the home page, dropped when this already is it.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

$intera_index_query  = $GLOBALS['wp_query'];
$intera_index_found  = (int) $intera_index_query->found_posts;
$intera_index_single = is_singular();

// One post: open the loop here, so the band and the body read the same post.
if ( $intera_index_single && have_posts() ) {
	the_post();
}

if ( $intera_index_single ) {
	$intera_index_title = get_the_title();
} elseif ( is_home() ) {
	$intera_index_posts_page = (int) get_option( 'page_for_posts' );
	$intera_index_title      = $intera_index_posts_page > 0 ? get_the_title( $intera_index_posts_page ) : single_post_title( '', false );
} elseif ( is_search() ) {
	$intera_index_title = trim( (string) get_search_query() );
} elseif ( is_archive() ) {
	$intera_index_title = trim( wp_strip_all_tags( (string) get_the_archive_title() ) );
} else {
	$intera_index_title = '';
}

if ( '' === trim( (string) $intera_index_title ) ) {
	$intera_index_title = get_bloginfo( 'name', 'display' );
}
?>

<section data-screen-label="Header" style="background: var(--surface-page); border-bottom: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(32px, 7vw, 56px) clamp(20px, 5vw, 24px) 36px">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 640px; margin-top: 20px">
			<h1 style="font-size: clamp(28px, 3vw, 36px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900)"><?php echo esc_html( $intera_index_title ); ?></h1>
			<?php if ( ! $intera_index_single && $intera_index_found > 0 ) : ?>
				<div style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-500); margin-top: 14px">
					<?php
					printf(
						/* translators: %s: number of posts listed. */
						esc_html( _n( '%s post', '%s posts', $intera_index_found, 'intera' ) ),
						esc_html( number_format_i18n( $intera_index_found ) )
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section data-screen-label="Body" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(26px, 7vw, 44px) clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px)">
		<?php if ( $intera_index_single ) : ?>
			<article <?php post_class( 'intera-article' ); ?> style="min-width: 0; max-width: 680px; display: flex; flex-direction: column; gap: 32px">
				<div class="intera-prose">
					<?php the_content(); ?>
				</div>
				<?php
				wp_link_pages(
					array(
						'before' => '<div class="intera-pagination" style="margin-top: 0">',
						'after'  => '</div>',
					)
				);
				?>
			</article>
		<?php elseif ( have_posts() ) : ?>
			<div style="max-width: 820px; display: flex; flex-direction: column; gap: 0; border-top: 1px solid var(--border-hairline)">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part(
						'template-parts/partials/post-row',
						null,
						array(
							'post_id'      => get_the_ID(),
							'date'         => true,
							'reading_time' => false,
							'category'     => true,
							'excerpt'      => true,
						)
					);
				endwhile;

				// The design's counter row: mono metadata left, the button pair right.
				get_template_part(
					'template-parts/partials/pagination',
					null,
					array(
						'query' => $intera_index_query,
						'style' => 'margin-top: 0; padding-top: 28px; border-top: 0',
					)
				);
				?>
			</div>
		<?php else : ?>
			<div style="max-width: 620px">
				<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)"><?php esc_html_e( 'Nothing published yet', 'intera' ); ?></h2>
				<p style="font-size: var(--text-md); line-height: 1.6; color: var(--ink-600); margin-top: 14px"><?php esc_html_e( 'This list is empty. Pages and stories appear here as soon as they are published.', 'intera' ); ?></p>
				<?php
				if ( ! is_front_page() ) {
					?>
					<div style="margin-top: 22px">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label'   => __( 'Go to the home page', 'intera' ),
								'href'    => home_url( '/' ),
								'variant' => 'secondary',
								'size'    => 'sm',
							)
						);
						?>
					</div>
					<?php
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
