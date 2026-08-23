<?php
/**
 * The documentation root — ported from `_design/11-docs.dc.html` (recon §13).
 *
 * Three sections, in the export's order: `Docs header` (on
 * `var(--surface-sunken)`, closed by a `--border-subtle` rule) · `Docs
 * categories` (the four category cards) · `Docs help` (the three closing
 * panels). `header.php` owns the masthead, the 76px spacer and `<main>`;
 * `footer.php` closes it.
 *
 * What comes from WordPress — the export names four categories and 21
 * articles, this file names none:
 *
 * | slot                       | source                                          |
 * | -------------------------- | ----------------------------------------------- |
 * | breadcrumb `Home / Docs`   | `intera_breadcrumbs()` (auto for the archive)    |
 * | H1                         | `post_type_archive_title()` (the `archives` label)|
 * | standfirst                 | `intera_option( 'docs_intro' )`                  |
 * | search field               | a real `GET` form scoped to `post_type=docs`     |
 * | quick links                | the three lowest-ordered `docs` posts            |
 * | one card per category      | `get_terms( 'doc_category' )`, top level only   |
 * | chip icon + tone           | `intera_term_icon_get()` / `intera_term_tone_get()`, via `partials/doc-row` |
 * | article count              | the per-term query's `found_posts` (children included) |
 * | five article links         | the per-term query, `partials/doc-row` `compact` |
 * | the card's paragraph       | `term_description()` — only the categories that have one |
 * | `N more articles`          | `found_posts` minus the five shown, through `_n()` |
 * | the three help panels      | `intera_option( 'docs_help_*_…' )` + `intera_page_url()` |
 *
 * The ordinals the design shows on a category page come from each post's
 * `menu_order`, which is also what orders the article lists here — so the Order
 * field an editor sets in the post is the one thing driving both screens.
 *
 * PORT.md §1: every category card carries `.itr-hl`, so background, border,
 * box-shadow and transition are never written inline — `components/card` hands
 * the resting border over as `--itr-edge`. Links drop their inline colour for
 * the class that owns both states: `.itr-link-blue` on the quick links,
 * `.itr-doc-title` / `.itr-doc-row` inside `partials/doc-row`, and
 * `.itr-link-strong` on the blue "N more articles" link.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

/*
 * The standfirst and the three closing panels are marketing copy, so they are
 * theme options with the export's wording as the registered default: an editor
 * rewrites them in the Customizer without touching the template, and a fresh
 * install still reads exactly like the handoff.
 */
$intera_docs_intro = (string) intera_option(
	'docs_intro',
	__( 'Setup, integrations, the object model, and the role packages we ship. Written for the person doing the work.', 'intera' )
);

// The header's quick links: the docs an editor ordered first, whatever they are.
$intera_docs_quick = new WP_Query(
	array(
		'post_type'           => 'docs',
		'posts_per_page'      => 3,
		'orderby'             => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

/*
 * Top-level categories only — a child term is a sub-group inside its parent's
 * page, never a card of its own. `hide_empty` stays false because a parent whose
 * articles all sit in children counts zero; the per-term query below decides.
 */
$intera_docs_terms = get_terms(
	array(
		'taxonomy'   => 'doc_category',
		'parent'     => 0,
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

$intera_docs_terms = is_wp_error( $intera_docs_terms ) ? array() : $intera_docs_terms;

// The closing panels: option prefix, then the export's copy as the default.
$intera_docs_help = array(
	array(
		'prefix'  => 'docs_help_limits',
		'heading' => __( 'Alpha-stage limitations', 'intera' ),
		'body'    => __( 'What INTERA cannot do yet is written down, not hidden. Read it before planning a rollout.', 'intera' ),
		'label'   => __( 'Current system limitations', 'intera' ),
		'url'     => intera_page_url( 'docs' ),
	),
	array(
		'prefix'  => 'docs_help_releases',
		'heading' => __( 'Release information', 'intera' ),
		'body'    => __( 'Every version notes what changed in the object model and what it means for existing roles.', 'intera' ),
		'label'   => __( 'Release notes', 'intera' ),
		'url'     => intera_page_url( 'blog' ),
	),
	array(
		'prefix'  => 'docs_help_ask',
		'heading' => __( 'Question not answered here?', 'intera' ),
		'body'    => __( 'Send the situation in two sentences. We answer with what INTERA would watch.', 'intera' ),
		'label'   => __( 'Ask the team', 'intera' ),
		'url'     => intera_page_url( 'contact-request' ),
	),
);
?>

<section data-screen-label="Docs header" style="background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(30px, 7vw, 52px) clamp(20px, 5vw, 24px) clamp(26px, 7vw, 44px)">
		<?php intera_breadcrumbs(); ?>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 32px; align-items: end; margin-top: 20px">
			<div style="max-width: 560px">
				<h1 style="font-size: clamp(28px, 3vw, 36px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900)"><?php post_type_archive_title(); ?></h1>
				<?php if ( '' !== $intera_docs_intro ) : ?>
					<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 14px"><?php echo wp_kses_post( $intera_docs_intro ); ?></p>
				<?php endif; ?>
			</div>
			<div style="max-width: 420px; width: 100%">
				<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="screen-reader-text" for="intera-docs-search"><?php esc_html_e( 'Search the documentation', 'intera' ); ?></label>
					<?php
					get_template_part(
						'template-parts/components/input',
						null,
						array(
							'type'        => 'search',
							'id'          => 'intera-docs-search',
							'name'        => 's',
							'value'       => get_search_query(),
							'placeholder' => __( 'Search the documentation', 'intera' ),
							'size'        => 'lg',
							'icon_left'   => 'search',
						)
					);
					?>
					<input type="hidden" name="post_type" value="docs">
				</form>
				<?php if ( $intera_docs_quick->have_posts() ) : ?>
					<div style="display: flex; gap: 14px; margin-top: 12px; flex-wrap: wrap; font-size: var(--text-sm)">
						<?php
						while ( $intera_docs_quick->have_posts() ) :
							$intera_docs_quick->the_post();
							?>
							<a class="itr-link-blue" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							<?php
						endwhile;

						wp_reset_postdata();
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<section data-screen-label="Docs categories" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(30px, 7vw, 52px) clamp(20px, 5vw, 24px) 32px">
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); grid-auto-rows: 1fr; gap: 20px">
			<?php
			$intera_docs_printed = 0;

			foreach ( $intera_docs_terms as $intera_docs_term ) {

				$intera_docs_query = new WP_Query(
					array(
						'post_type'           => 'docs',
						'posts_per_page'      => 5,
						'orderby'             => array(
							'menu_order' => 'ASC',
							'title'      => 'ASC',
						),
						'post_status'         => 'publish',
						'ignore_sticky_posts' => true,
						'tax_query'           => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- one term per card, the archive's whole purpose.
							array(
								'taxonomy'         => 'doc_category',
								'field'            => 'term_id',
								'terms'            => (int) $intera_docs_term->term_id,
								'include_children' => true,
							),
						),
					)
				);

				$intera_docs_total = (int) $intera_docs_query->found_posts;

				// A category with nothing published in it — or in its sub-groups — gets no card.
				if ( 0 === $intera_docs_total ) {
					wp_reset_postdata();
					continue;
				}

				++$intera_docs_printed;

				$intera_docs_link = get_term_link( $intera_docs_term );
				$intera_docs_link = is_wp_error( $intera_docs_link ) ? '' : (string) $intera_docs_link;
				$intera_docs_rest = $intera_docs_total - (int) $intera_docs_query->post_count;

				/*
				 * `term_description()` runs through `wpautop`; the handoff's card
				 * paragraph is a single styled `<p>`, so the block wrappers come
				 * off and an editor's inline formatting survives.
				 */
				$intera_docs_desc = trim( (string) term_description( $intera_docs_term ) );

				if ( '' !== $intera_docs_desc ) {
					$intera_docs_desc = trim( (string) preg_replace( '#</p>\s*<p>#', ' ', $intera_docs_desc ) );
					$intera_docs_desc = trim( (string) preg_replace( '#^<p>|</p>$#', '', $intera_docs_desc ) );
				}

				ob_start();

				get_template_part(
					'template-parts/partials/doc-row',
					null,
					array(
						'variant' => 'heading',
						'term'    => $intera_docs_term,
						'href'    => $intera_docs_link,
						'count'   => $intera_docs_total,
					)
				);
				?>
				<div style="display: flex; flex-direction: column; gap: 0; margin-top: 12px">
					<?php
					$intera_docs_index = 0;

					while ( $intera_docs_query->have_posts() ) {
						$intera_docs_query->the_post();
						++$intera_docs_index;

						get_template_part(
							'template-parts/partials/doc-row',
							null,
							array(
								'post_id' => get_the_ID(),
								'variant' => 'compact',
								'divider' => ( $intera_docs_index < (int) $intera_docs_query->post_count ),
							)
						);
					}

					wp_reset_postdata();
					?>
				</div>
				<?php if ( '' !== $intera_docs_desc ) : ?>
					<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 18px"><?php echo wp_kses_post( $intera_docs_desc ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $intera_docs_link ) : ?>
					<a class="itr-link-strong" href="<?php echo esc_url( $intera_docs_link ); ?>" style="display: inline-flex; align-items: center; gap: 8px; font-size: var(--text-md); font-weight: 500; margin-top: 16px">
						<?php
						if ( $intera_docs_rest > 0 ) {
							printf(
								/* translators: %s: number of further articles in this docs category. */
								esc_html( _n( '%s more article', '%s more articles', $intera_docs_rest, 'intera' ) ),
								esc_html( number_format_i18n( $intera_docs_rest ) )
							);
						} else {
							esc_html_e( 'All articles', 'intera' );
						}

						intera_icon( 'arrow-right', array( 'size' => 15 ) );
						?>
					</a>
					<?php
				endif;

				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content' => ob_get_clean(),
						'padding' => 'loose',
						'class'   => 'itr-hl',
					)
				);
			}

			if ( 0 === $intera_docs_printed ) :
				?>
				<p style="font-size: var(--text-md); color: var(--ink-600); line-height: 1.6"><?php esc_html_e( 'No documentation has been published yet.', 'intera' ); ?></p>
				<?php
			endif;
			?>
		</div>
	</div>
</section>

<section data-screen-label="Docs help" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: 24px clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px)">
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px; padding-top: 32px; border-top: 1px solid var(--border-hairline)">
			<?php
			foreach ( $intera_docs_help as $intera_docs_panel ) :
				$intera_docs_heading = (string) intera_option( $intera_docs_panel['prefix'] . '_heading', $intera_docs_panel['heading'] );
				$intera_docs_body    = (string) intera_option( $intera_docs_panel['prefix'] . '_body', $intera_docs_panel['body'] );
				$intera_docs_label   = (string) intera_option( $intera_docs_panel['prefix'] . '_label', $intera_docs_panel['label'] );
				$intera_docs_url     = (string) intera_option( $intera_docs_panel['prefix'] . '_url', $intera_docs_panel['url'] );

				if ( '' === $intera_docs_heading && '' === $intera_docs_body ) {
					continue;
				}
				?>
				<div>
					<?php if ( '' !== $intera_docs_heading ) : ?>
						<div style="font-size: var(--text-md); font-weight: 600; color: var(--ink-900)"><?php echo esc_html( $intera_docs_heading ); ?></div>
					<?php endif; ?>
					<?php if ( '' !== $intera_docs_body ) : ?>
						<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 8px"><?php echo wp_kses_post( $intera_docs_body ); ?></p>
					<?php endif; ?>
					<?php if ( '' !== $intera_docs_label && '' !== $intera_docs_url ) : ?>
						<div style="margin-top: 12px"><a href="<?php echo esc_url( $intera_docs_url ); ?>" style="font-size: var(--text-md)"><?php echo esc_html( $intera_docs_label ); ?></a></div>
					<?php endif; ?>
				</div>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<?php
get_footer();
