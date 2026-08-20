<?php
/**
 * The generic page — anything an editor creates without one of the six named
 * templates (Product, Pricing, FAQ, Contacts, Contact request, Legal).
 *
 * Not in the export. It is the plain version of the designed inner page:
 * `page-legal.php`'s two sections with everything policy-specific taken out —
 * the sunken header band (breadcrumb, title, optional lede) and the white body
 * band carrying `the_content()` in `.intera-prose` at the designed 640px
 * measure. `header.php` owns the masthead, the 76px spacer and `<main>`;
 * `footer.php` closes it.
 *
 * What comes from WordPress — this file names nothing:
 *
 * | slot            | source                                                    |
 * | --------------- | --------------------------------------------------------- |
 * | breadcrumb      | `intera_breadcrumbs()` (auto: Home / <ancestors> / <title>)|
 * | H1              | `the_title()`                                              |
 * | lede            | the page excerpt, when the editor wrote one                |
 * | body            | the page content                                           |
 * | "Contents" rail | `intera_headings_get()` over the rendered content          |
 * | pager           | `wp_link_pages()` on a page split with a nextpage block    |
 * | comments        | `comments_template()`, only when they are open or exist    |
 *
 * The rail is the one thing `page-legal.php` has that this file only sometimes
 * grows: `partials/toc-rail` renders nothing below two `h2` headings, so an
 * "About" page of three paragraphs is a single column and a long structured
 * page gets the same sticky contents list a policy gets. Nothing else is added
 * — no featured image, no meta strip, no related list: the designed inner page
 * has no slot for them, and an editor who wants an image puts it in the body.
 *
 * The only fixed string is the rail's "Contents" label, which `page-legal.php`
 * fixes too.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	/*
	 * PORT.md: give the headings ids first, list them second, print that same
	 * HTML third — the rail links to ids that are actually in the output.
	 */
	$intera_page_html = intera_content_with_heading_ids( apply_filters( 'the_content', get_the_content() ) );
	$intera_page_toc  = intera_headings_get( $intera_page_html );

	// `has_excerpt()` is deliberate: an auto-trimmed body is not a lede.
	$intera_page_lede = has_excerpt() ? trim( (string) get_the_excerpt() ) : '';
	?>

<section data-screen-label="Page header" style="background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(30px, 7vw, 52px) clamp(20px, 5vw, 24px) clamp(23px, 7vw, 40px)">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 640px; margin-top: 20px">
			<h1 style="font-size: clamp(28px, 3vw, 36px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900)"><?php the_title(); ?></h1>
			<?php if ( '' !== $intera_page_lede ) : ?>
				<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 14px"><?php echo wp_kses_post( $intera_page_lede ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<section data-screen-label="Page body" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(28px, 7vw, 48px) clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px); display: flex; flex-wrap: wrap; gap: 48px; align-items: flex-start">
		<?php
		// Renders nothing below two h2 headings, so most pages stay one column.
		get_template_part(
			'template-parts/partials/toc-rail',
			null,
			array(
				'headings'  => $intera_page_toc,
				'title'     => __( 'Contents', 'intera' ),
				'variant'   => 'jump',
				'size'      => 'sm',
				'width'     => '240px',
				'min_width' => '220px',
				'top'       => '100px',
			)
		);
		?>

		<article <?php post_class( 'intera-page' ); ?> style="flex: 1 1 480px; min-width: 0; max-width: 640px; display: flex; flex-direction: column; gap: 32px">
			<?php if ( '' !== trim( (string) $intera_page_html ) ) : ?>
				<div class="intera-prose" style="--itr-prose-max: 640px">
					<?php echo $intera_page_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, filtered by WordPress. ?>
				</div>
			<?php endif; ?>
			<?php
			// A page split across several editor pages still needs a pager.
			wp_link_pages(
				array(
					'before' => '<div class="intera-pagination" style="margin-top: 0">',
					'after'  => '</div>',
				)
			);

			// Pages can carry a discussion; nothing is printed when they do not.
			if ( ( comments_open() || get_comments_number() ) && locate_template( 'comments.php' ) ) {
				echo '<div class="intera-comments">';
				comments_template();
				echo '</div>';
			}
			?>
		</article>
	</div>
</section>

	<?php
endwhile;

get_footer();
