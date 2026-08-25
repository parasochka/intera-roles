<?php
/**
 * The generic archive — author, date, tag, and any taxonomy or post type with
 * no template of its own.
 *
 * Not in the export. It is `category.php`, the designed archive, with the term
 * taken out of it: the sunken header band (breadcrumb, eyebrow, title, the
 * description as a standfirst, the mono count) and the white `Category posts`
 * grid, drawn with `the_archive_title()` and `the_archive_description()` so one
 * file answers for every archive WordPress can build. `header.php` owns the
 * masthead, the 76px spacer and `<main>`; `footer.php` closes it.
 *
 * What comes from WordPress — this file names nothing:
 *
 * | slot                    | source                                            |
 * | ----------------------- | ------------------------------------------------- |
 * | breadcrumb              | `intera_breadcrumbs()` (auto for every archive)    |
 * | eyebrow (`Tag`, `Year`) | the prefix `get_the_archive_title()` builds        |
 * | H1                      | the name that same call builds                     |
 * | standfirst              | `get_the_archive_description()`; author bio on an  |
 * |                         | author archive                                     |
 * | `N posts`               | `$wp_query->found_posts`                           |
 * | the cards               | the main query, via `partials/post-card`           |
 * | counter + Previous/Next | `partials/pagination` over the main query          |
 * | sidebar CTA card        | `partials/sidebar-cta`, prefix `sidebar_cta`       |
 *
 * `get_the_archive_title()` returns the prefix and the name in one string, with
 * the name in a `<span>`. Splitting them there gives the design's eyebrow and
 * H1 without a single archive label being retyped in PHP — and a site that
 * filters the prefix away simply loses the eyebrow. The sidebar is the CTA card
 * alone: a list of sibling terms is meaningless on a date or author archive, so
 * the second column exists only when the card has something to say, and the
 * grid collapses to one when it does not.
 *
 * PORT.md §1: every card carries `.itr-lift .itr-post-card` and the CTA card
 * `.itr-hl`, so border, background, box-shadow and transition are never inline
 * — `partials/post-card` and `components/card` hand the resting values over as
 * `--itr-edge` / `--itr-shadow`. The two-column grid is `.itr-1col` with its
 * tracks in `--itr-cols`, and the empty state's link uses `.itr-link-blue`.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

$intera_archive_query = $GLOBALS['wp_query'];
$intera_archive_found = (int) $intera_archive_query->found_posts;
$intera_archive_blog  = (string) intera_page_url( 'blog' );

/*
 * The title, split into the design's eyebrow and H1. Core writes it as
 * "<prefix> <span><name></span>"; with no span there is no prefix to lift, so
 * the whole string becomes the heading.
 */
$intera_archive_markup  = (string) get_the_archive_title();
$intera_archive_title   = trim( wp_strip_all_tags( $intera_archive_markup ) );
$intera_archive_eyebrow = '';

if ( preg_match( '#^(.*?)<span[^>]*>(.*?)</span>#s', $intera_archive_markup, $intera_archive_parts ) ) {
	$intera_archive_name = trim( wp_strip_all_tags( $intera_archive_parts[2] ) );

	if ( '' !== $intera_archive_name ) {
		$intera_archive_title   = $intera_archive_name;
		$intera_archive_eyebrow = trim( rtrim( trim( wp_strip_all_tags( $intera_archive_parts[1] ) ), ':' ) );
	}
}

/*
 * The standfirst. An author archive reads the user's own description straight
 * from the queried object, because `$authordata` is only reliable inside the
 * loop; everything else takes the term or post-type description.
 */
if ( is_author() ) {
	$intera_archive_user = get_queried_object();
	$intera_archive_desc = $intera_archive_user instanceof WP_User ? trim( (string) $intera_archive_user->description ) : '';
} else {
	$intera_archive_desc = trim( (string) get_the_archive_description() );
}

// The description arrives through `wpautop`; the design's standfirst is one <p>.
if ( '' !== $intera_archive_desc ) {
	$intera_archive_desc = trim( (string) preg_replace( '#</p>\s*<p>#', ' ', $intera_archive_desc ) );
	$intera_archive_desc = trim( (string) preg_replace( '#^<p>|</p>$#', '', $intera_archive_desc ) );
}

/*
 * The sidebar exists only when the CTA card will draw something — the partial
 * returns early with no heading and no body, and a 340px column of nothing is
 * worse than one column.
 */
$intera_archive_aside = '' !== trim( (string) intera_option( 'sidebar_cta_heading' ) )
	|| '' !== trim( (string) intera_option( 'sidebar_cta_body' ) );

$intera_archive_cols = $intera_archive_aside ? 'minmax(0, 1fr) minmax(0, 340px)' : 'minmax(0, 1fr)';
?>

<section data-screen-label="Archive header" style="background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(30px, 7vw, 52px) clamp(20px, 5vw, 24px) clamp(23px, 7vw, 40px)">
		<?php intera_breadcrumbs(); ?>
		<div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-end; justify-content: space-between; margin-top: 20px">
			<div style="max-width: 620px">
				<?php if ( '' !== $intera_archive_eyebrow ) : ?>
					<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 12px"><?php echo esc_html( $intera_archive_eyebrow ); ?></div>
				<?php endif; ?>
				<h1 style="font-size: clamp(28px, 3vw, 36px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900)"><?php echo esc_html( $intera_archive_title ); ?></h1>
				<?php if ( '' !== $intera_archive_desc ) : ?>
					<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 14px"><?php echo wp_kses_post( $intera_archive_desc ); ?></p>
				<?php endif; ?>
			</div>
			<div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center">
				<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-500)">
					<?php
					printf(
						/* translators: %s: number of posts in this archive. */
						esc_html( _n( '%s post', '%s posts', $intera_archive_found, 'intera' ) ),
						esc_html( number_format_i18n( $intera_archive_found ) )
					);
					?>
				</span>
			</div>
		</div>
	</div>
</section>

<section data-screen-label="Archive posts" style="background: var(--surface-page)">
	<div class="itr-1col" style="--itr-cols: <?php echo esc_attr( $intera_archive_cols ); ?>; max-width: 1160px; margin: 0 auto; padding: clamp(28px, 7vw, 48px) clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px); gap: 44px; align-items: start">
		<div style="min-width: 0; display: flex; flex-direction: column; gap: 16px">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();

					get_template_part(
						'template-parts/partials/post-card',
						null,
						array(
							'date'         => true,
							'reading_time' => true,
							'excerpt'      => true,
							'heading'      => 'h2',
							// "Read the story" belongs to a story; the whole card is a link either way.
							'cta'          => ( 'post' === get_post_type() ),
						)
					);
				}
			} else {
				?>
				<div style="max-width: 620px">
					<p style="font-size: var(--text-md); color: var(--ink-600); line-height: 1.6"><?php esc_html_e( 'Nothing has been published here yet.', 'intera' ); ?></p>
					<?php if ( '' !== $intera_archive_blog ) : ?>
						<a class="itr-link-blue" href="<?php echo esc_url( $intera_archive_blog ); ?>" style="display: inline-block; font-size: var(--text-md); margin-top: 12px"><?php esc_html_e( 'All posts', 'intera' ); ?></a>
					<?php else : ?>
						<a class="itr-link-blue" href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: inline-block; font-size: var(--text-md); margin-top: 12px"><?php esc_html_e( 'Go to the home page', 'intera' ); ?></a>
					<?php endif; ?>
				</div>
				<?php
			}

			// The design's pager row: a hairline rule, the mono counter, the button pair.
			get_template_part(
				'template-parts/partials/pagination',
				null,
				array(
					'query' => $intera_archive_query,
					'style' => 'margin-top: 0; padding-top: 20px',
				)
			);
			?>
		</div>

		<?php if ( $intera_archive_aside ) : ?>
			<aside style="display: flex; flex-direction: column; gap: 20px; max-width: 340px">
				<?php
				get_template_part(
					'template-parts/partials/sidebar-cta',
					null,
					array(
						'option_prefix' => 'sidebar_cta',
						'accent'        => 'var(--blue-600)',
						'accent_line'   => 'var(--blue-200)',
					)
				);
				?>
			</aside>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
