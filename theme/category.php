<?php
/**
 * A blog category archive — ported from `_design/10-blog-category.dc.html` (recon §12).
 *
 * Two sections, in the export's order: `Category header` (on
 * `var(--surface-sunken)`, closed by a `--border-subtle` rule) and
 * `Category posts` (the two-column grid with the sidebar). `header.php` owns
 * the masthead, the 76px spacer and `<main>`; `footer.php` closes it.
 *
 * What comes from WordPress — the export names one category, this file names
 * none:
 *
 * | slot                          | source                                          |
 * | ----------------------------- | ----------------------------------------------- |
 * | breadcrumb `Home / Blog / …`  | `intera_breadcrumbs()` (auto for `is_category()`)|
 * | H1                            | `single_cat_title()`                             |
 * | standfirst                    | `category_description()`                         |
 * | `3 posts`                     | `$wp_query->found_posts`                         |
 * | filter chips                  | `get_categories()`, the queried term `selected`  |
 * | `All posts`                   | `intera_page_url( 'blog' )`                      |
 * | the post cards                | the main query, via `partials/post-card`         |
 * | counter + Previous/Next       | `partials/pagination` over the main query        |
 * | sidebar `Other categories`    | `get_categories()` minus the queried term, plus  |
 * |                               | the site's published post count                  |
 * | sidebar CTA card              | `partials/sidebar-cta`, prefix `sidebar_cta`     |
 *
 * The only fixed strings left are structural labels (`Category`,
 * `Other categories`, `All posts`, `N posts`) — the CTA card's heading, body
 * and button label are Customizer options read by the partial, and its target
 * resolves through `intera_page_url()`.
 *
 * PORT.md §1: every post card carries `.itr-lift .itr-post-card` and both
 * sidebar cards carry `.itr-hl`, so border, box-shadow and transition are never
 * written inline — `partials/post-card` and `components/card` hand the resting
 * values over as `--itr-edge` / `--itr-shadow`. The two-column grid is
 * `.itr-1col` with its tracks in `--itr-cols`, and every link that turns blue
 * on hover drops its inline colour for `.itr-link-blue` / `.itr-rail-row`.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

$intera_cat_query = $GLOBALS['wp_query'];
$intera_cat_term  = get_queried_object();
$intera_cat_id    = $intera_cat_term instanceof WP_Term ? (int) $intera_cat_term->term_id : 0;
$intera_cat_found = (int) $intera_cat_query->found_posts;
$intera_cat_blog  = intera_page_url( 'blog' );

/*
 * `category_description()` runs through `wpautop`, and the handoff's standfirst
 * is a single paragraph in a styled `<p>`. The block wrappers come off so the
 * mark-up stays valid; an editor's inline formatting and links survive.
 */
$intera_cat_desc = trim( (string) category_description() );

if ( '' !== $intera_cat_desc ) {
	$intera_cat_desc = trim( (string) preg_replace( '#</p>\s*<p>#', ' ', $intera_cat_desc ) );
	$intera_cat_desc = trim( (string) preg_replace( '#^<p>|</p>$#', '', $intera_cat_desc ) );
}

$intera_cat_all = get_categories( array( 'hide_empty' => true ) );

// The sidebar's "All posts" tally: everything published, not just this term.
$intera_cat_counts = wp_count_posts( 'post' );
$intera_cat_total  = $intera_cat_counts && isset( $intera_cat_counts->publish ) ? (int) $intera_cat_counts->publish : 0;

// Every category but the one being read — the sidebar list, plus its own "All posts" row.
$intera_cat_others = array();

foreach ( $intera_cat_all as $intera_cat_item ) {
	if ( (int) $intera_cat_item->term_id !== $intera_cat_id ) {
		$intera_cat_others[] = $intera_cat_item;
	}
}
?>

<section data-screen-label="Category header" style="background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(30px, 7vw, 52px) clamp(20px, 5vw, 24px) clamp(23px, 7vw, 40px)">
		<?php intera_breadcrumbs(); ?>
		<div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-end; justify-content: space-between; margin-top: 20px">
			<div style="max-width: 620px">
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 12px"><?php esc_html_e( 'Category', 'intera' ); ?></div>
				<h1 style="font-size: clamp(28px, 3vw, 36px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900)"><?php single_cat_title(); ?></h1>
				<?php if ( '' !== $intera_cat_desc ) : ?>
					<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 14px"><?php echo wp_kses_post( $intera_cat_desc ); ?></p>
				<?php endif; ?>
			</div>
			<div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center">
				<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-500)">
					<?php
					printf(
						/* translators: %s: number of posts in this category. */
						esc_html( _n( '%s post', '%s posts', $intera_cat_found, 'intera' ) ),
						esc_html( number_format_i18n( $intera_cat_found ) )
					);
					?>
				</span>
			</div>
		</div>
		<?php if ( $intera_cat_all ) : ?>
			<div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 28px">
				<?php
				foreach ( $intera_cat_all as $intera_cat_chip ) {
					get_template_part(
						'template-parts/components/tag',
						null,
						array(
							'text'     => $intera_cat_chip->name,
							'href'     => (string) get_category_link( $intera_cat_chip ),
							'selected' => ( (int) $intera_cat_chip->term_id === $intera_cat_id ),
						)
					);
				}

				if ( '' !== $intera_cat_blog ) :
					?>
					<a class="itr-link-blue" href="<?php echo esc_url( $intera_cat_blog ); ?>" style="font-size: var(--text-sm); align-self: center; margin-left: 8px"><?php esc_html_e( 'All posts', 'intera' ); ?></a>
					<?php
				endif;
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

<section data-screen-label="Category posts" style="background: var(--surface-page)">
	<div class="itr-1col" style="--itr-cols: minmax(0, 1fr) minmax(0, 340px); max-width: 1160px; margin: 0 auto; padding: clamp(28px, 7vw, 48px) clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px); gap: 44px; align-items: start">
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
						)
					);
				}
			} else {
				?>
				<p style="font-size: var(--text-md); color: var(--ink-600); line-height: 1.6"><?php esc_html_e( 'Nothing has been published in this category yet.', 'intera' ); ?></p>
				<?php
			}

			// The export's pager row: a hairline rule, the mono counter, the button pair.
			get_template_part(
				'template-parts/partials/pagination',
				null,
				array(
					'query' => $intera_cat_query,
					'style' => 'margin-top: 0; padding-top: 20px',
				)
			);
			?>
		</div>

		<aside style="display: flex; flex-direction: column; gap: 20px; max-width: 340px">
			<?php
			if ( $intera_cat_others || '' !== $intera_cat_blog ) {
				ob_start();
				?>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php esc_html_e( 'Other categories', 'intera' ); ?></div>
				<div style="display: flex; flex-direction: column; gap: 0; margin-top: 14px">
					<?php
					foreach ( $intera_cat_others as $intera_cat_row ) :
						$intera_cat_row_style = 'display: flex; justify-content: space-between; align-items: center; padding: 11px 0; border-bottom: 1px solid var(--border-hairline); font-size: var(--text-md)';
						?>
						<a class="itr-rail-row" href="<?php echo esc_url( (string) get_category_link( $intera_cat_row ) ); ?>" style="<?php echo esc_attr( $intera_cat_row_style ); ?>"><?php echo esc_html( $intera_cat_row->name ); ?> <span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted)"><?php echo esc_html( number_format_i18n( (int) $intera_cat_row->count ) ); ?></span></a>
					<?php endforeach; ?>
					<?php if ( '' !== $intera_cat_blog ) : ?>
						<a class="itr-rail-row" href="<?php echo esc_url( $intera_cat_blog ); ?>" style="display: flex; justify-content: space-between; align-items: center; padding: 11px 0; font-size: var(--text-md)"><?php esc_html_e( 'All posts', 'intera' ); ?> <span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted)"><?php echo esc_html( number_format_i18n( $intera_cat_total ) ); ?></span></a>
					<?php endif; ?>
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
					'option_prefix' => 'sidebar_cta',
					'accent'        => 'var(--signal-pattern)',
					'accent_line'   => 'var(--signal-pattern-line)',
				)
			);
			?>
		</aside>
	</div>
</section>

<?php
get_footer();
