<?php
/**
 * Template Name: Legal
 *
 * Ported from `_design/07-policy.dc.html` (recon §9). Two sections, in the
 * export's order: `Policy header` and `Policy body` (sticky "Contents" rail
 * beside the article).
 *
 * Three pages share this template — privacy policy, cookie policy, software
 * licence — so **none** of the export's prose is baked in here. The mockup's ten
 * numbered sections are placeholder text: an editor writes them as an `h2` per
 * section plus paragraphs and lists, and the rail is generated from those
 * headings, so adding a section in the editor adds a rail row.
 *
 * What comes from WordPress:
 *
 * | slot                     | source                                              |
 * | ------------------------ | --------------------------------------------------- |
 * | breadcrumb               | `intera_breadcrumbs()` (auto: Home / <title>)        |
 * | heading                  | `the_title()`                                        |
 * | standfirst               | the page excerpt                                     |
 * | version stamp            | `_intera_doc_version` post meta, hidden when empty   |
 * | updated stamp            | `the_modified_date()`                                |
 * | every numbered section   | the page content                                     |
 * | "Contents" rows          | `intera_headings_get()` over the rendered content    |
 * | sibling legal links      | the other pages assigned to this template            |
 * | the article's © stamp    | site name + the page's own modified year + version   |
 *
 * Fixed template copy, as the recon allows: the "Contents" and "Print this page"
 * labels. Everything else is editor-owned.
 *
 * "Print this page" is progressive: it ships with `hidden` and `assets/js/intera.js`
 * reveals it, so a visitor without JavaScript never sees a control that cannot work.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
	the_post();
}

$intera_legal_id = (int) get_the_ID();

// PORT.md: give the headings ids first, list them second, print that same HTML third.
$intera_legal_html = intera_content_with_heading_ids( apply_filters( 'the_content', get_the_content() ) );
$intera_legal_toc  = intera_headings_get( $intera_legal_html );

// The rail hides itself below two entries; the sibling links must survive that.
$intera_legal_rail_rows = 0;

foreach ( $intera_legal_toc as $intera_legal_heading ) {
	if ( 2 === (int) $intera_legal_heading['level'] && '' !== $intera_legal_heading['id'] && '' !== trim( (string) $intera_legal_heading['text'] ) ) {
		++$intera_legal_rail_rows;
	}
}

// The mono stamps. The version is optional; the updated date always exists.
$intera_legal_version = trim( (string) get_post_meta( $intera_legal_id, '_intera_doc_version', true ) );
$intera_legal_updated = get_the_modified_date( 'Y-m-d' );

/*
 * The other legal pages: whatever else the editor assigned to this template.
 * Resolved the same way intera_page_url() resolves a destination — by template,
 * never by slug — so renaming "Cookie policy" keeps the link.
 */
$intera_legal_siblings = get_pages(
	array(
		'meta_key'    => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'meta_value'  => 'page-legal.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		'exclude'     => array( $intera_legal_id ),
		'sort_column' => 'menu_order,post_title',
	)
);

$intera_legal_siblings = is_array( $intera_legal_siblings ) ? $intera_legal_siblings : array();
$intera_legal_aside    = '';

if ( $intera_legal_siblings ) {
	ob_start();

	foreach ( $intera_legal_siblings as $intera_legal_sibling ) {
		?>
		<a class="itr-term-link" href="<?php echo esc_url( (string) get_permalink( $intera_legal_sibling ) ); ?>" style="font-size: var(--text-sm)"><?php echo esc_html( get_the_title( $intera_legal_sibling ) ); ?></a>
		<?php
	}

	$intera_legal_aside = trim( (string) ob_get_clean() );
}

// "© 2026 INTERA · version 1.0" — the site's name, this document's own year, its version.
$intera_legal_stamp = array( sprintf( '© %1$s %2$s', get_the_modified_date( 'Y' ), get_bloginfo( 'name' ) ) );

if ( '' !== $intera_legal_version ) {
	/* translators: %s: document version, e.g. 1.0. */
	$intera_legal_stamp[] = sprintf( __( 'version %s', 'intera' ), $intera_legal_version );
}
?>

<section data-screen-label="Policy header" style="background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(30px, 7vw, 52px) clamp(20px, 5vw, 24px) clamp(23px, 7vw, 40px)">
		<?php intera_breadcrumbs(); ?>
		<div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-end; justify-content: space-between; margin-top: 20px">
			<div style="max-width: 640px">
				<h1 style="font-size: clamp(28px, 3vw, 36px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900)"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p style="font-size: var(--text-base); line-height: 1.6; color: var(--ink-600); margin-top: 14px"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>
			<div style="display: flex; gap: 24px; font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-500)">
				<?php if ( '' !== $intera_legal_version ) : ?>
					<span>
						<?php
						/* translators: %s: document version, e.g. 1.0. */
						printf( esc_html__( 'Version %s', 'intera' ), esc_html( $intera_legal_version ) );
						?>
					</span>
				<?php endif; ?>
				<?php if ( '' !== $intera_legal_updated ) : ?>
					<span>
						<?php
						/* translators: %s: the date this page was last changed. */
						printf( esc_html__( 'Updated %s', 'intera' ), esc_html( $intera_legal_updated ) );
						?>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<section data-screen-label="Policy body" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(28px, 7vw, 48px) clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px); display: flex; flex-wrap: wrap; gap: 48px; align-items: flex-start">
		<?php
		if ( $intera_legal_rail_rows > 1 ) {
			get_template_part(
				'template-parts/partials/toc-rail',
				null,
				array(
					'headings'     => $intera_legal_toc,
					'title'        => __( 'Contents', 'intera' ),
					'variant'      => 'jump',
					'size'         => 'sm',
					'width'        => '240px',
					'min_width'    => '220px',
					'top'          => '100px',
					'footer'       => $intera_legal_aside,
					'footer_style' => 'margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-hairline); display: flex; flex-direction: column; gap: 8px',
				)
			);
		} elseif ( '' !== $intera_legal_aside ) {
			// One section or none: no rail to draw, but the sibling policies still belong here.
			?>
			<aside style="position: sticky; top: 100px; flex: 0 1 240px; min-width: 220px; display: flex; flex-direction: column; gap: 8px">
				<?php echo $intera_legal_aside; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted, template-composed markup, escaped above. ?>
			</aside>
			<?php
		}
		?>

		<article style="flex: 1 1 480px; min-width: 0; max-width: 640px; display: flex; flex-direction: column; gap: 32px">
			<div class="intera-prose" style="--itr-prose-max: 640px">
				<?php echo $intera_legal_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, filtered by WordPress. ?>
			</div>
			<?php
			// A legal page split across several editor pages still needs a pager.
			wp_link_pages(
				array(
					'before' => '<div class="intera-pagination" style="margin-top: 0">',
					'after'  => '</div>',
				)
			);
			?>
			<div style="border-top: 1px solid var(--border-hairline); padding-top: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between">
				<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-400)"><?php echo esc_html( implode( ' · ', $intera_legal_stamp ) ); ?></span>
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'     => __( 'Print this page', 'intera' ),
						'variant'   => 'secondary',
						'size'      => 'sm',
						'icon_left' => 'printer',
						'attrs'     => array(
							'data-intera-print' => true,
							'hidden'            => true,
						),
					)
				);
				?>
			</div>
		</article>
	</div>
</section>

<?php
get_footer();
