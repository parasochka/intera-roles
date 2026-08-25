<?php
/**
 * Single documentation article — ported from `_design/12-docs-article.dc.html` (recon §14).
 *
 * One screen, three columns, exactly as the export draws it: a sticky docs tree
 * (`flex: 0 1 240px`), the article (`flex: 1 1 520px; max-width: 680px`) and the
 * sticky "On this page" rail (`flex: 0 1 200px`). `header.php` owns the masthead,
 * the 76px spacer and `<main>`; `footer.php` closes it. The wrapper is the
 * export's own `max-width: 1280px` — wider than the site's 1160px, deliberately.
 *
 * What comes from WordPress — the export names 12 tree links, a title, a version
 * and a prev/next pair; this file names none of them:
 *
 * | slot                       | source                                             |
 * | -------------------------- | -------------------------------------------------- |
 * | docs search                | a real `GET` form scoped with `intera_docs=1`       |
 * | tree groups                | `get_terms( 'doc_category' )`, top level only      |
 * | tree links                 | the per-term query, ordered by `menu_order`         |
 * | the current item           | `get_the_ID()` — a `<span>`, never a link to itself |
 * | breadcrumb `Docs / …`      | `intera_breadcrumbs()` (no Home crumb on a doc)     |
 * | H1                         | `the_title()`                                        |
 * | standfirst                 | the excerpt, when the editor wrote one              |
 * | meta strip                 | `entry-meta` `rule` — modified date, version, time  |
 * | body                       | `the_content()` inside `.intera-prose`               |
 * | "On this page"             | `intera_headings_get()` over the rendered content   |
 * | the version notice         | `intera_option( 'docs_notice_title' / '…_body' )`   |
 * | previous / next            | `prev-next`, the `menu_order` neighbours in the category |
 *
 * The meta strip prints `Updated <date> · <version> · <n> min read`, and the
 * version stamp disappears by itself when `_intera_doc_version` is empty — that
 * decision lives in `partials/entry-meta`, so nothing here has to test for it.
 *
 * **Two feedback controls, and only one of them is drawn here.** The export
 * ships `Yes` / `No` with no handler at all, so the theme's strip is a real
 * `GET` form aimed at the contact-request page: either button navigates there
 * carrying the answer and the article slug. It works without JavaScript and it
 * records nothing — which is the right trade for the only control on the page
 * and the wrong one next to a control that keeps a number. BetterDocs' reaction
 * vote is that control: it is what writes to `/betterdocs/v1/feedback` and what
 * fills BetterDocs → Analytics → Reactions. So `inc/betterdocs.php` draws the
 * plugin's own article footer below the article — vote, share row, tags,
 * feedback modal, each present only if the operator has it switched on — and
 * the theme's strip renders only when that footer offers no vote at all
 * (BetterDocs off, or both of its feedback features disabled). With no
 * contact-request page assigned the strip is not rendered either way.
 *
 * The print button and the AI summary sit above the body for the same reason,
 * and the H1 and the prose wrapper carry `betterdocs-entry-title` /
 * `betterdocs-single-content` because the plugin's print handler reads those
 * two ids by name.
 *
 * PORT.md §1: nothing here writes `background`, `border`, `box-shadow` or
 * `transition` inline on an element that carries a hover class — the tree links
 * take the export's `background: var(--surface-hover)` hover from `.itr-jump`,
 * and both prev/next cards get their resting border from the partial as
 * `--itr-edge`. The current tree item keeps its inline `background` and
 * `border-left` because it is a `<span>` that never hovers.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$intera_doc_id = (int) get_the_ID();

	// PORT.md's TOC recipe: ids first, list second, print that same HTML third.
	$intera_doc_html = intera_content_with_heading_ids( apply_filters( 'the_content', get_the_content() ) );
	$intera_doc_toc  = intera_headings_get( $intera_doc_html );

	// The rail hides itself below two rows; the help block must survive that.
	$intera_doc_rail_rows = 0;

	foreach ( $intera_doc_toc as $intera_doc_heading ) {
		if ( 2 === (int) $intera_doc_heading['level'] && '' !== $intera_doc_heading['id'] && '' !== trim( (string) $intera_doc_heading['text'] ) ) {
			++$intera_doc_rail_rows;
		}
	}

	/**
	 * Every published doc in one category, in the order an editor set.
	 *
	 * `menu_order` is the Order field, the same one the category page prints as
	 * an ordinal — so the tree, the ordinals and the prev/next pair all follow a
	 * single control. Children are included: a sub-group is part of its parent.
	 *
	 * @param int $intera_doc_term_id doc_category term id.
	 * @return int[] Post ids.
	 */
	$intera_doc_cache = array();

	$intera_doc_ids_in = static function ( $intera_doc_term_id ) use ( &$intera_doc_cache ) {
		$intera_doc_term_id = (int) $intera_doc_term_id;

		// The article's own group is asked for twice — once for the tree, once
		// for the neighbours — and answers from memory the second time.
		if ( isset( $intera_doc_cache[ $intera_doc_term_id ] ) ) {
			return $intera_doc_cache[ $intera_doc_term_id ];
		}

		$intera_doc_rows = get_posts(
			array(
				'post_type'   => 'docs',
				'post_status' => 'publish',
				'numberposts' => -1,
				'fields'      => 'ids',
				'orderby'     => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'tax_query'   => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- one group per query, which is what a docs tree is.
					array(
						'taxonomy'         => 'doc_category',
						'field'            => 'term_id',
						'terms'            => $intera_doc_term_id,
						'include_children' => true,
					),
				),
			)
		);

		$intera_doc_cache[ $intera_doc_term_id ] = array_map( 'intval', (array) $intera_doc_rows );

		return $intera_doc_cache[ $intera_doc_term_id ];
	};

	/*
	 * Neighbours: WordPress cannot guess this ordering, so PARTIALS.md asks for
	 * both sides explicitly — the `menu_order` position inside the article's own
	 * category. `false` tells the partial that side is deliberately empty.
	 */
	$intera_doc_term = function_exists( 'intera_breadcrumbs_primary_term' )
		? intera_breadcrumbs_primary_term( get_post(), 'doc_category' )
		: null;

	// `null` leaves the partial to resolve neighbours itself — the fallback for
	// an article an editor has not filed under any category yet.
	$intera_doc_prev = null;
	$intera_doc_next = null;

	if ( $intera_doc_term instanceof WP_Term ) {
		$intera_doc_siblings = $intera_doc_ids_in( $intera_doc_term->term_id );
		$intera_doc_at       = array_search( $intera_doc_id, $intera_doc_siblings, true );

		if ( false !== $intera_doc_at ) {
			$intera_doc_prev = $intera_doc_at > 0 ? $intera_doc_siblings[ $intera_doc_at - 1 ] : false;
			$intera_doc_next = isset( $intera_doc_siblings[ $intera_doc_at + 1 ] ) ? $intera_doc_siblings[ $intera_doc_at + 1 ] : false;
		}
	}

	/*
	 * The tree: top-level categories only, because a child term is a sub-group
	 * inside its parent, never a group of its own. `hide_empty` stays false —
	 * a parent whose articles all sit in children counts zero, and the per-term
	 * query below is what decides whether the group is printed.
	 */
	$intera_doc_terms = get_terms(
		array(
			'taxonomy'   => 'doc_category',
			'parent'     => 0,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	$intera_doc_terms = is_wp_error( $intera_doc_terms ) ? array() : $intera_doc_terms;

	// Where "Report an issue" and both feedback answers go.
	$intera_doc_ask_url = trim( (string) intera_page_url( 'contact-request' ) );

	/*
	 * What BetterDocs draws around an article, drawn by BetterDocs.
	 *
	 * The plugin owns the content, so it also owns the controls that write
	 * about the content: the reaction vote is the only thing on this page that
	 * reaches `/betterdocs/v1/feedback`, and therefore the only thing that ever
	 * puts a number in BetterDocs → Analytics → Reactions. `inc/betterdocs.php`
	 * renders the plugin's own views, so each part is present exactly when the
	 * operator has it switched on in BetterDocs and absent otherwise — nothing
	 * here has to test a setting.
	 */
	$intera_doc_print   = function_exists( 'intera_betterdocs_doc_print_icon' ) ? intera_betterdocs_doc_print_icon() : '';
	$intera_doc_summary = function_exists( 'intera_betterdocs_doc_summary' ) ? intera_betterdocs_doc_summary() : '';
	$intera_doc_footer  = function_exists( 'intera_betterdocs_doc_footer' ) ? intera_betterdocs_doc_footer() : '';

	/*
	 * The theme's own "Was this page useful?" strip is the FALLBACK, not the
	 * default. It is a `GET` form to the contact page — it answers the visitor's
	 * half of the question and records nothing, which is fine as the only
	 * control on the page and misleading as the second one. So it renders when
	 * the plugin drew no vote of its own: BetterDocs switched off, uninstalled,
	 * or its reactions and feedback form both disabled.
	 */
	$intera_doc_own_vote = '' !== $intera_doc_ask_url
		&& ! ( function_exists( 'intera_betterdocs_footer_has_vote' ) && intera_betterdocs_footer_has_vote( $intera_doc_footer ) );

	/*
	 * A GET form replaces the action's whole query string, so on a plain-permalink
	 * install the `?page_id=…` that identifies the page would be thrown away. Any
	 * argument already in the destination is re-emitted as a hidden field.
	 */
	$intera_doc_ask_args  = array();
	$intera_doc_ask_query = (string) wp_parse_url( $intera_doc_ask_url, PHP_URL_QUERY );

	if ( '' !== $intera_doc_ask_query ) {
		wp_parse_str( $intera_doc_ask_query, $intera_doc_ask_args );
	}

	/*
	 * The version notice. It is a standing docs announcement rather than one
	 * article's paragraph, so it is a theme option with the export's wording as
	 * the default: an editor rewrites it once, or empties both fields and the
	 * Alert stops rendering everywhere.
	 */
	$intera_doc_notice_title = trim( (string) intera_option( 'docs_notice_title' ) );
	$intera_doc_notice_body  = trim( (string) intera_option( 'docs_notice_body' ) );

	// The block under the rail's hairline — passed to the partial as trusted HTML.
	$intera_doc_rail_footer = '';

	if ( '' !== $intera_doc_ask_url ) {
		ob_start();
		?>
		<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600)"><?php esc_html_e( 'Something unclear or wrong here? Tell us — docs are part of the product.', 'intera' ); ?></p>
		<div style="margin-top: 12px"><a href="<?php echo esc_url( $intera_doc_ask_url ); ?>" style="font-size: var(--text-sm)"><?php esc_html_e( 'Report an issue', 'intera' ); ?></a></div>
		<?php
		$intera_doc_rail_footer = trim( (string) ob_get_clean() );
	}

	// The tree rows, quoted from the export and identical in every group.
	$intera_doc_link_style    = 'font-size: var(--text-sm); color: var(--ink-600); padding: 6px 10px; border-radius: var(--radius-md)';
	$intera_doc_current_style = 'font-size: var(--text-sm); color: var(--ink-900); font-weight: 500; padding: 6px 10px; border-radius: var(--radius-md); background: var(--blue-50); border-left: 2px solid var(--blue-600)';
	$intera_doc_group_style   = 'font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500); margin-bottom: 10px';
	?>

<div data-screen-label="Docs article" style="max-width: 1280px; margin: 0 auto; padding: 32px clamp(20px, 5vw, 24px) clamp(46px, 7vw, 80px); display: flex; flex-wrap: wrap; gap: 40px; align-items: flex-start">
	<aside class="intera-rail intera-rail--sticky intera-doc-tree" style="--itr-rail: 240px; --itr-rail-min: 220px; --itr-rail-top: 100px">
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="intera-doc-search"><?php esc_html_e( 'Search docs', 'intera' ); ?></label>
			<?php
			get_template_part(
				'template-parts/components/input',
				null,
				array(
					'type'        => 'search',
					'id'          => 'intera-doc-search',
					'name'        => 's',
					'value'       => get_search_query(),
					'placeholder' => __( 'Search docs', 'intera' ),
					'size'        => 'sm',
					'icon_left'   => 'search',
				)
			);
			?>
			<input type="hidden" name="intera_docs" value="1">
		</form>
		<nav aria-label="<?php esc_attr_e( 'Documentation', 'intera' ); ?>">
			<?php
			foreach ( $intera_doc_terms as $intera_doc_group ) {
				$intera_doc_group_ids = $intera_doc_ids_in( $intera_doc_group->term_id );

				// A category with nothing published in it, or in its sub-groups.
				if ( ! $intera_doc_group_ids ) {
					continue;
				}
				?>
				<div style="margin-top: 22px">
					<div style="<?php echo esc_attr( $intera_doc_group_style ); ?>"><?php echo esc_html( $intera_doc_group->name ); ?></div>
					<div style="display: flex; flex-direction: column; gap: 1px">
						<?php
						foreach ( $intera_doc_group_ids as $intera_doc_row_id ) {
							if ( $intera_doc_row_id === $intera_doc_id ) {
								?>
								<span aria-current="page" style="<?php echo esc_attr( $intera_doc_current_style ); ?>"><?php echo esc_html( get_the_title( $intera_doc_row_id ) ); ?></span>
								<?php
								continue;
							}
							?>
							<a class="itr-jump" href="<?php echo esc_url( (string) get_permalink( $intera_doc_row_id ) ); ?>" style="<?php echo esc_attr( $intera_doc_link_style ); ?>"><?php echo esc_html( get_the_title( $intera_doc_row_id ) ); ?></a>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
			?>
		</nav>
	</aside>

	<article <?php post_class( 'intera-article' ); ?> style="flex: 1 1 520px; min-width: 0; max-width: 680px">
		<?php intera_breadcrumbs(); ?>
		<h1 id="betterdocs-entry-title" style="font-size: clamp(26px, 2.8vw, 34px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.18; color: var(--ink-900); margin-top: 16px; text-wrap: pretty"><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) : ?>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 14px"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
		<?php endif; ?>

		<?php
		get_template_part(
			'template-parts/partials/entry-meta',
			null,
			array(
				'variant'      => 'rule',
				'date'         => false,
				'modified'     => true,
				'version'      => true,
				'reading_time' => true,
				'read_label'   => 'long',
				'style'        => 'margin-top:18px',
			)
		);
		?>

		<?php if ( '' !== $intera_doc_print || '' !== $intera_doc_summary ) : ?>
			<div class="intera-doc-plugin intera-doc-plugin--head">
				<?php
				echo $intera_doc_print;   // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin-composed markup, escaped at its source.
				echo $intera_doc_summary; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin-composed markup, escaped at its source.
				?>
			</div>
		<?php endif; ?>

		<div id="betterdocs-single-content" class="intera-prose" style="margin-top: 28px">
			<?php echo $intera_doc_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, filtered by WordPress. ?>
		</div>

		<?php
		// A doc split across several editor pages still needs a pager — the
		// same one `page.php` prints, so the two cannot drift apart.
		wp_link_pages(
			array(
				'before' => '<div class="intera-pagination" style="margin-top: 24px">',
				'after'  => '</div>',
			)
		);
		?>

		<?php if ( '' !== $intera_doc_notice_title || '' !== $intera_doc_notice_body ) : ?>
			<div style="margin-top: 36px">
				<?php
				get_template_part(
					'template-parts/components/alert',
					null,
					array(
						'tone'  => 'info',
						'title' => $intera_doc_notice_title,
						'text'  => $intera_doc_notice_body,
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( $intera_doc_own_vote ) : ?>
			<form class="intera-doc-feedback" method="get" action="<?php echo esc_url( $intera_doc_ask_url ); ?>" aria-labelledby="intera-doc-useful" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between; margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--border-hairline)">
				<span id="intera-doc-useful" style="font-size: var(--text-sm); color: var(--ink-600)"><?php esc_html_e( 'Was this page useful?', 'intera' ); ?></span>
				<?php foreach ( $intera_doc_ask_args as $intera_doc_ask_key => $intera_doc_ask_value ) : ?>
					<input type="hidden" name="<?php echo esc_attr( (string) $intera_doc_ask_key ); ?>" value="<?php echo esc_attr( is_scalar( $intera_doc_ask_value ) ? (string) $intera_doc_ask_value : '' ); ?>">
				<?php endforeach; ?>
				<input type="hidden" name="intera_doc" value="<?php echo esc_attr( (string) get_post_field( 'post_name', $intera_doc_id ) ); ?>">
				<div style="display: flex; gap: 8px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'     => __( 'Yes', 'intera' ),
							'variant'   => 'secondary',
							'size'      => 'sm',
							'icon_left' => 'thumbs-up',
							'attrs'     => array(
								'type'  => 'submit',
								'name'  => 'intera_doc_useful',
								'value' => 'yes',
							),
						)
					);

					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'     => __( 'No', 'intera' ),
							'variant'   => 'secondary',
							'size'      => 'sm',
							'icon_left' => 'thumbs-down',
							'attrs'     => array(
								'type'  => 'submit',
								'name'  => 'intera_doc_useful',
								'value' => 'no',
							),
						)
					);
					?>
				</div>
			</form>
		<?php endif; ?>

		<?php if ( '' !== $intera_doc_footer ) : ?>
			<div class="intera-doc-plugin intera-doc-plugin--footer">
				<?php echo $intera_doc_footer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin-composed markup, escaped at its source. ?>
			</div>
		<?php endif; ?>

		<?php
		get_template_part(
			'template-parts/partials/prev-next',
			null,
			array(
				'previous' => $intera_doc_prev,
				'next'     => $intera_doc_next,
				'padding'  => '16px',
				'min'      => '220px',
				'style'    => 'margin-top: 20px',
			)
		);
		?>
		<?php
		/*
		 * Comments, when BetterDocs' own switch and the post's own setting both
		 * allow them. The part hands over to `comments_template()`, so a thread
		 * on a doc is the theme's `comments.php`, same as anywhere else.
		 */
		if ( function_exists( 'intera_betterdocs_doc_comments' ) ) {
			intera_betterdocs_doc_comments();
		}
		?>
	</article>

	<?php
	if ( $intera_doc_rail_rows > 1 ) {
		get_template_part(
			'template-parts/partials/toc-rail',
			null,
			array(
				'headings'  => $intera_doc_toc,
				'title'     => __( 'On this page', 'intera' ),
				'variant'   => 'rail',
				'size'      => 'sm',
				'width'     => '200px',
				'min_width' => '180px',
				'top'       => '100px',
				'footer'    => $intera_doc_rail_footer,
			)
		);
	} elseif ( '' !== $intera_doc_rail_footer ) {
		// Too few headings for a rail; the help block still belongs in the column.
		?>
		<aside class="intera-rail intera-rail--sticky" style="--itr-rail: 200px; --itr-rail-min: 180px; --itr-rail-top: 100px">
			<?php echo $intera_doc_rail_footer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted, template-composed markup, escaped above. ?>
		</aside>
		<?php
	}
	?>
</div>

	<?php
endwhile;

get_footer();
