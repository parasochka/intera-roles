<?php
/**
 * A documentation category — ported from `_design/13-docs-category.dc.html` (recon §15).
 *
 * Two sections, in the export's order: `Docs category header` (on
 * `var(--surface-sunken)`, closed by a `--border-subtle` rule) · `Docs category
 * list` (the grouped article list beside the sidebar). `header.php` owns the
 * masthead, the 76px spacer and `<main>`; `footer.php` closes it.
 *
 * What comes from WordPress — the export names one category, three sub-groups
 * and ten articles, this file names none:
 *
 * | slot                          | source                                          |
 * | ----------------------------- | ----------------------------------------------- |
 * | breadcrumb `Home / Docs / …`  | `intera_breadcrumbs()` (auto for a docs term)    |
 * | chip icon + tone              | `intera_term_icon_get()` / `intera_term_tone_get()` |
 * | H1                            | `single_term_title()`                           |
 * | `10 articles`                 | the list query's `found_posts`, through `_n()`  |
 * | standfirst                    | `term_description()`                            |
 * | sub-group headings            | the term's child terms, in the admin's own order |
 * | each row                      | `partials/doc-row` `ordinal`: position, title, excerpt, reading time |
 * | sidebar `Other categories`    | top-level `doc_category` terms in the admin's own order, current one excluded, counted with children |
 * | sidebar CTA                   | `partials/sidebar-cta`, prefix `docs_cta`       |
 *
 * The ordinals run 01…10 straight through the sub-groups. The order they run
 * in is the editor's own — the `_docs_order` list BetterDocs keeps on the
 * category term, read by `intera_docs_query_args()`, with the post's Order
 * field as the fallback behind it — which is what makes the groups fall into
 * the export's shape: a group appears where its first article does, not
 * alphabetically. Articles filed on the category itself rather than in a
 * sub-group are listed first, without a heading, so nothing goes missing when
 * an editor never creates children.
 *
 * PORT.md §1: the two sidebar cards carry `.itr-hl`, so background, border,
 * box-shadow and transition are never inline — `components/card` hands the
 * resting border over as `--itr-edge`. The article rows are `.itr-list-row`
 * inside `partials/doc-row`, and the sidebar's category links drop their inline
 * colour for `.itr-term-link`, which owns both states.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

$intera_docs_term = get_queried_object();
$intera_docs_term = $intera_docs_term instanceof WP_Term ? $intera_docs_term : null;
$intera_docs_id   = $intera_docs_term ? (int) $intera_docs_term->term_id : 0;

/**
 * Every published doc in a category, its sub-groups included.
 *
 * @param int $term_id       Category term id.
 * @param int $per_page      How many posts to fetch.
 * @return WP_Query
 */
$intera_docs_query = static function ( $term_id, $per_page ) {
	return new WP_Query( intera_docs_query_args( (int) $term_id, (int) $per_page ) );
};

/*
 * One query for the whole page: the design lists a category end to end, with no
 * pager, so the rows are grouped in PHP rather than fetched group by group.
 * `found_posts` still gives the true tally if a category ever outgrows the cap.
 */
$intera_docs_list  = $intera_docs_id ? $intera_docs_query( $intera_docs_id, 100 ) : null;
$intera_docs_total = $intera_docs_list ? (int) $intera_docs_list->found_posts : 0;

/*
 * The chip's icon and colour family are term meta. A sub-group usually has
 * neither, so the nearest ancestor that does lends both — a child page then
 * looks like the category it belongs to instead of falling back to blue.
 */
$intera_docs_source = $intera_docs_id;
$intera_docs_icon   = $intera_docs_id ? intera_term_icon_get( $intera_docs_id ) : '';

if ( '' === $intera_docs_icon && $intera_docs_id ) {
	foreach ( (array) get_ancestors( $intera_docs_id, 'doc_category', 'taxonomy' ) as $intera_docs_ancestor ) {
		$intera_docs_candidate = intera_term_icon_get( (int) $intera_docs_ancestor );

		if ( '' !== $intera_docs_candidate ) {
			$intera_docs_icon   = $intera_docs_candidate;
			$intera_docs_source = (int) $intera_docs_ancestor;
			break;
		}
	}
}

$intera_docs_tone = intera_term_tone_get( $intera_docs_source );
$intera_docs_icon = '' !== $intera_docs_icon ? $intera_docs_icon : 'book-open';

/*
 * `term_description()` runs through `wpautop`; the handoff's standfirst is a
 * single styled `<p>`, so the block wrappers come off and an editor's inline
 * formatting survives.
 */
$intera_docs_desc = $intera_docs_id ? trim( (string) term_description( $intera_docs_id ) ) : '';

if ( '' !== $intera_docs_desc ) {
	$intera_docs_desc = trim( (string) preg_replace( '#</p>\s*<p>#', ' ', $intera_docs_desc ) );
	$intera_docs_desc = trim( (string) preg_replace( '#^<p>|</p>$#', '', $intera_docs_desc ) );
}

/*
 * Sub-groups: the direct children, plus a map from any deeper descendant to the
 * child it sits under, so a grandchild's articles still land in a visible group.
 */
$intera_docs_children = $intera_docs_id
	? get_terms(
		array(
			'taxonomy'   => 'doc_category',
			'parent'     => $intera_docs_id,
			'hide_empty' => false,
		)
	)
	: array();

$intera_docs_children = is_wp_error( $intera_docs_children ) ? array() : $intera_docs_children;
$intera_docs_children = intera_docs_terms_in_order( $intera_docs_children );

$intera_docs_group_of = array();

foreach ( $intera_docs_children as $intera_docs_child ) {
	$intera_docs_child_id                          = (int) $intera_docs_child->term_id;
	$intera_docs_group_of[ $intera_docs_child_id ] = $intera_docs_child_id;

	$intera_docs_deeper = get_term_children( $intera_docs_child_id, 'doc_category' );
	$intera_docs_deeper = is_wp_error( $intera_docs_deeper ) ? array() : $intera_docs_deeper;

	foreach ( $intera_docs_deeper as $intera_docs_deep_id ) {
		$intera_docs_group_of[ (int) $intera_docs_deep_id ] = $intera_docs_child_id;
	}
}

// Group the posts in query order, so the groups keep the ordinals' running order.
$intera_docs_groups = array();

foreach ( ( $intera_docs_list ? $intera_docs_list->posts : array() ) as $intera_docs_post ) {
	$intera_docs_group = 0;
	$intera_docs_terms = get_the_terms( $intera_docs_post, 'doc_category' );
	$intera_docs_terms = ( $intera_docs_terms && ! is_wp_error( $intera_docs_terms ) ) ? $intera_docs_terms : array();

	foreach ( $intera_docs_terms as $intera_docs_post_term ) {
		if ( isset( $intera_docs_group_of[ (int) $intera_docs_post_term->term_id ] ) ) {
			$intera_docs_group = $intera_docs_group_of[ (int) $intera_docs_post_term->term_id ];
			break;
		}
	}

	if ( ! isset( $intera_docs_groups[ $intera_docs_group ] ) ) {
		$intera_docs_groups[ $intera_docs_group ] = array(
			'label' => $intera_docs_group ? (string) get_term_field( 'name', $intera_docs_group, 'doc_category' ) : '',
			'posts' => array(),
		);
	}

	$intera_docs_groups[ $intera_docs_group ]['posts'][] = $intera_docs_post;
}

// The sidebar: every other top-level category, counted with its sub-groups.
$intera_docs_others = get_terms(
	array(
		'taxonomy'   => 'doc_category',
		'parent'     => 0,
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
		'exclude'    => $intera_docs_id ? array( $intera_docs_id ) : array(),
	)
);

$intera_docs_others = is_wp_error( $intera_docs_others ) ? array() : $intera_docs_others;
$intera_docs_others = intera_docs_terms_in_order( $intera_docs_others );
?>

<section data-screen-label="Docs category header" style="background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(28px, 7vw, 48px) clamp(20px, 5vw, 24px) clamp(23px, 7vw, 40px)">
		<?php intera_breadcrumbs(); ?>
		<div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center; margin-top: 20px">
			<span style="<?php echo esc_attr( 'width: 40px; height: 40px; border-radius: var(--radius-md); background: var(--white); border: 1px solid var(--' . $intera_docs_tone . '-100); display: grid; place-items: center; color: var(--' . $intera_docs_tone . '-600)' ); ?>">
				<?php intera_icon( $intera_docs_icon, array( 'size' => 19 ) ); ?>
			</span>
			<div>
				<h1 style="font-size: clamp(26px, 2.8vw, 34px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.18; color: var(--ink-900)"><?php echo esc_html( single_term_title( '', false ) ); ?></h1>
				<span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-500)">
					<?php
					printf(
						/* translators: %s: number of documentation articles in the category. */
						esc_html( _n( '%s article', '%s articles', $intera_docs_total, 'intera' ) ),
						esc_html( number_format_i18n( $intera_docs_total ) )
					);
					?>
				</span>
			</div>
		</div>
		<?php if ( '' !== $intera_docs_desc ) : ?>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px; max-width: 640px"><?php echo wp_kses_post( $intera_docs_desc ); ?></p>
		<?php endif; ?>
	</div>
</section>

<section data-screen-label="Docs category list" style="background: var(--surface-page)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(28px, 7vw, 48px) clamp(20px, 5vw, 24px) clamp(51px, 7vw, 88px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 44px; align-items: start">
		<div style="min-width: 0">
			<?php
			if ( empty( $intera_docs_groups ) ) :
				?>
				<p style="font-size: var(--text-md); color: var(--ink-600)"><?php esc_html_e( 'No articles in this category yet.', 'intera' ); ?></p>
				<?php
			else :
				$intera_docs_first = true;

				// Runs straight through the sub-groups, as the export numbers them.
				$intera_docs_seq = 0;

				foreach ( $intera_docs_groups as $intera_docs_group_data ) :
					$intera_docs_label = (string) $intera_docs_group_data['label'];

					if ( '' !== $intera_docs_label ) {
						$intera_docs_heading_style = 'font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500); '
							. ( $intera_docs_first ? 'margin-bottom: 8px' : 'margin: 32px 0 8px' );
						?>
						<div style="<?php echo esc_attr( $intera_docs_heading_style ); ?>"><?php echo esc_html( $intera_docs_label ); ?></div>
						<?php
					}

					$intera_docs_list_style = 'display: flex; flex-direction: column; border-top: 1px solid var(--border-hairline)';

					if ( '' === $intera_docs_label && ! $intera_docs_first ) {
						$intera_docs_list_style .= '; margin-top: 32px';
					}
					?>
					<div style="<?php echo esc_attr( $intera_docs_list_style ); ?>">
						<?php
						foreach ( $intera_docs_group_data['posts'] as $intera_docs_row ) {
							/*
							 * The export numbers these rows 01…10 straight
							 * through the sub-groups. The Order field is the
							 * source wherever an editor set it; where it is
							 * still zero — which is every article while
							 * BetterDocs owns the ordering — the running
							 * position takes over, so the column reads as the
							 * design draws it instead of collapsing to blanks.
							 */
							++$intera_docs_seq;

							get_template_part(
								'template-parts/partials/doc-row',
								null,
								array(
									'post'    => $intera_docs_row,
									'variant' => 'ordinal',
									'ordinal' => (int) $intera_docs_row->menu_order > 0
										? (int) $intera_docs_row->menu_order
										: $intera_docs_seq,
								)
							);
						}
						?>
					</div>
					<?php
					$intera_docs_first = false;
				endforeach;
			endif;
			?>
		</div>

		<?php
		/*
		 * The rail. It is a grid item beside a column of ten articles, so it
		 * runs out of content long before the list does; `intera-rail--sticky`
		 * pins it under the masthead (`--itr-rail-top`) for the rest of that
		 * scroll and lets go of it again below 900px, where the grid has
		 * collapsed to one column and a pinned block would slide over the
		 * list instead of beside it. The grid's own `align-items: start` is
		 * what keeps the box its content's height inside the row.
		 */
		?>
		<aside class="intera-rail--sticky" style="display: flex; flex-direction: column; gap: 20px; max-width: 340px; --itr-rail-top: 100px">
			<?php
			if ( ! empty( $intera_docs_others ) ) {
				ob_start();
				?>
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php esc_html_e( 'Other categories', 'intera' ); ?></div>
				<div style="display: flex; flex-direction: column; margin-top: 14px">
					<?php
					$intera_docs_last = count( $intera_docs_others ) - 1;

					foreach ( array_values( $intera_docs_others ) as $intera_docs_index => $intera_docs_other ) :
						$intera_docs_other_link  = get_term_link( $intera_docs_other );
						$intera_docs_other_link  = is_wp_error( $intera_docs_other_link ) ? '' : (string) $intera_docs_other_link;
						$intera_docs_other_query = $intera_docs_query( (int) $intera_docs_other->term_id, 1 );
						$intera_docs_other_count = (int) $intera_docs_other_query->found_posts;

						$intera_docs_other_style = 'display: flex; justify-content: space-between; align-items: center; padding: 11px 0; font-size: var(--text-md)';

						if ( $intera_docs_index !== $intera_docs_last ) {
							$intera_docs_other_style .= '; border-bottom: 1px solid var(--border-hairline)';
						}
						?>
						<a class="itr-term-link" href="<?php echo esc_url( $intera_docs_other_link ); ?>" style="<?php echo esc_attr( $intera_docs_other_style ); ?>"><?php echo esc_html( $intera_docs_other->name ); ?> <span style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--text-muted)"><?php echo esc_html( number_format_i18n( $intera_docs_other_count ) ); ?></span></a>
						<?php
					endforeach;
					?>
				</div>
				<?php
				$intera_docs_others_card = ob_get_clean();

				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content' => $intera_docs_others_card,
						'class'   => 'itr-hl',
					)
				);
			}

			/*
			 * The onboarding card. Every word is a theme option, with the
			 * handoff's copy as the default, so an editor rewrites it in the
			 * Customizer and a fresh install still reads like the export. The
			 * button's target comes from intera_page_url( 'contact-request' )
			 * inside the partial.
			 */
			get_template_part(
				'template-parts/partials/sidebar-cta',
				null,
				array(
					// The partial reads docs_cta_heading / _body / _label / _url itself.
					'option_prefix' => 'docs_cta',
					'accent'        => 'var(--signal-reconciliation)',
					'accent_line'   => 'var(--signal-reconciliation-line)',
				)
			);
			?>
		</aside>
	</div>
</section>

<?php
get_footer();
