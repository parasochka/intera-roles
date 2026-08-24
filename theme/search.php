<?php
/**
 * The search results archive.
 *
 * Not in the export — designed from the system. It is `home.php`'s list page
 * with the query in place of the standfirst: a `Blog header`-shaped band
 * (breadcrumb, H1, hairline bottom rule) carrying the term in mono type and the
 * count beside it, then the same hairline-topped column of
 * `partials/post-row` rows with `partials/pagination` closing it.
 * `header.php` owns the masthead, the spacer and `<main>`.
 *
 * What comes from WordPress:
 *
 * | slot                     | source                                              |
 * | ------------------------ | --------------------------------------------------- |
 * | breadcrumb               | `intera_breadcrumbs()` (auto: Home / Search: <term>) |
 * | the term                 | `get_search_query()`, printed in mono                |
 * | the count                | `$wp_query->found_posts`, printed in mono            |
 * | every row                | the main query, via `partials/post-row`              |
 * | counter + Previous/Next  | `partials/pagination` over the main query            |
 * | the search field         | `partials/search-form`                               |
 * | the destination list     | the `primary` menu, falling back to `intera_page_url()` |
 *
 * The empty state is the case people actually reach, so it is written rather
 * than defaulted (ds-rules §2: state the fact, then the next step). It repeats
 * the field with the term still in it and offers the same destinations
 * `404.php` offers — resolved the same way, from the `primary` menu, so neither
 * page carries a hardcoded list of links.
 *
 * PORT.md §1: the destination card carries `.itr-hl` and its rows
 * `.itr-rail-row`, so no `background`, `border`, `box-shadow`, `transition` —
 * and no inline link colour — is written on either.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

$intera_search_query = $GLOBALS['wp_query'];
$intera_search_term  = get_search_query();
$intera_search_found = (int) $intera_search_query->found_posts;
$intera_search_has   = (int) $intera_search_query->post_count > 0;

/*
 * Destinations for the empty state — the same list `404.php` offers, resolved
 * once in inc/template-tags.php so the two pages cannot drift apart. Only the
 * empty state prints them, so nothing is resolved on a page with results.
 */
$intera_search_dest = $intera_search_has ? array() : intera_destinations_get( 6 );

// The count line, mono: "12 results" — and "0 results" rather than a silence.
$intera_search_count = sprintf(
	/* translators: %s: number of results, already formatted. */
	_n( '%s result', '%s results', $intera_search_found, 'intera' ),
	number_format_i18n( $intera_search_found )
);
?>

<section data-screen-label="Search header" style="background: var(--surface-page); border-bottom: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(32px, 7vw, 56px) clamp(20px, 5vw, 24px) 36px">
		<?php intera_breadcrumbs(); ?>
		<div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-end; justify-content: space-between; margin-top: 20px">
			<div style="max-width: 620px">
				<h1 style="font-size: clamp(28px, 3vw, 36px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900)">
					<?php if ( '' !== $intera_search_term ) : ?>
						<?php esc_html_e( 'Results for', 'intera' ); ?>
						<span style="font-family: var(--font-mono); font-size: 0.86em; font-weight: 500; letter-spacing: 0; word-break: break-word"><?php echo esc_html( $intera_search_term ); ?></span>
					<?php else : ?>
						<?php esc_html_e( 'Search', 'intera' ); ?>
					<?php endif; ?>
				</h1>
				<div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: baseline; margin-top: 14px; font-size: var(--text-xs)">
					<span style="font-family: var(--font-mono); color: var(--ink-600)"><?php echo esc_html( $intera_search_count ); ?></span>
					<span style="color: var(--text-muted)"><?php esc_html_e( 'across pages, stories and documentation', 'intera' ); ?></span>
				</div>
			</div>
			<div style="width: 100%; max-width: 380px">
				<?php
				get_template_part(
					'template-parts/partials/search-form',
					null,
					array(
						'id'    => 'intera-search-header',
						'label' => __( 'Search again', 'intera' ),
					)
				);
				?>
			</div>
		</div>
	</div>
</section>

<section data-screen-label="Results" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(26px, 7vw, 44px) clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px)">
		<?php if ( $intera_search_has ) : ?>
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
						'query' => $intera_search_query,
						'style' => 'margin-top: 0; padding-top: 28px; border-top: 0',
					)
				);
				?>
			</div>
		<?php else : ?>
			<div style="max-width: 660px">
				<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)">
					<?php if ( '' !== $intera_search_term ) : ?>
						<?php esc_html_e( 'Nothing matched', 'intera' ); ?>
						<span style="font-family: var(--font-mono); font-size: 0.86em; font-weight: 500; letter-spacing: 0; word-break: break-word"><?php echo esc_html( $intera_search_term ); ?></span>
					<?php else : ?>
						<?php esc_html_e( 'No search term', 'intera' ); ?>
					<?php endif; ?>
				</h2>
				<p style="font-size: var(--text-md); line-height: 1.6; color: var(--ink-600); margin-top: 14px">
					<?php
					if ( '' !== $intera_search_term ) {
						esc_html_e( 'Search reads titles and body text only. Check the spelling, drop the sentence down to one term, or use the word the product uses — role, metric, reconciliation, incident.', 'intera' );
					} else {
						esc_html_e( 'Search reads titles and body text only. One term matches more than a full sentence.', 'intera' );
					}
					?>
				</p>
				<div style="margin-top: 22px">
					<?php
					get_template_part(
						'template-parts/partials/search-form',
						null,
						array(
							'id'   => 'intera-search-empty',
							'size' => 'lg',
						)
					);
					?>
				</div>

				<?php
				if ( $intera_search_dest ) {
					ob_start();
					?>
					<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php esc_html_e( 'Go to', 'intera' ); ?></div>
					<nav aria-label="<?php esc_attr_e( 'Main destinations', 'intera' ); ?>" style="display: flex; flex-direction: column; gap: 0; margin-top: 14px">
						<?php
						$intera_search_last = count( $intera_search_dest ) - 1;

						foreach ( $intera_search_dest as $intera_search_index => $intera_search_row ) :
							$intera_search_row_style = 'display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 13px 0; font-size: var(--text-md)';

							if ( $intera_search_index !== $intera_search_last ) {
								$intera_search_row_style .= '; border-bottom: 1px solid var(--border-hairline)';
							}
							?>
							<a class="itr-rail-row" href="<?php echo esc_url( $intera_search_row['url'] ); ?>" style="<?php echo esc_attr( $intera_search_row_style ); ?>">
								<span><?php echo esc_html( $intera_search_row['label'] ); ?></span>
								<?php intera_icon( 'arrow-right', array( 'size' => 16 ) ); ?>
							</a>
						<?php endforeach; ?>
					</nav>
					<?php
					get_template_part(
						'template-parts/components/card',
						null,
						array(
							'content' => ob_get_clean(),
							'padding' => 'loose',
							'class'   => 'itr-hl',
							'style'   => 'margin-top: 30px',
						)
					);
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
