<?php
/**
 * Template Name: Roles
 *
 * `/roles` — the page the three campaign landings sit under, and the one the
 * top bar's "Roles" points at. Not an export screen: it is the hub that answers
 * "which of these is me?" and then gets out of the way.
 *
 * Four bands, in the site's own vocabulary: the standard inner-page header, a
 * row of tiles for the three job pages, the Roles themselves as the cards the
 * product page draws them with, and the Method band every page closes on.
 *
 * **Nothing about the three tiles is written here.** A tile's name is its
 * page's title and its line is that page's own hero headline, both read against
 * the page through `intera_page_id()`, so the hub cannot promise a landing
 * something the landing does not say. A fourth landing is one more entry in
 * `$intera_hub_landings` — its title, its line and its address all follow. A
 * template not yet assigned to any page resolves to 0 and its tile is skipped,
 * which is also what happens while a landing is still a draft.
 *
 * The six Role cards are the `role` post type, in the Order the editor set —
 * the same query and the same partial the home and product pages use, so a role
 * added in wp-admin appears on all three without touching a template.
 *
 * What comes from WordPress:
 *
 * | slot            | source                                              |
 * | --------------- | --------------------------------------------------- |
 * | breadcrumb      | `intera_breadcrumbs()` (auto: Home / Roles)         |
 * | heading         | `the_title()`                                       |
 * | lede            | `the_content()`                                     |
 * | the three tiles | the landing pages themselves, via `intera_page_id()` |
 * | the Role cards  | the `role` post type                                |
 * | band headings   | `roles_*` copy on this page                         |
 * | every link      | `intera_page_url()` / `intera_page_id()`            |
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
	the_post();
}

$intera_request_url = (string) intera_page_url( 'contact-request' );
$intera_product_url = (string) intera_page_url( 'product' );
$intera_roles_url   = $intera_product_url ? $intera_product_url . '#roles' : '';

/*
 * The three landings, in the order the site offers them: the audience the
 * campaign is aimed at first, then the two the product already described.
 *
 * The icon is the template's, not the page's — it is vocabulary rather than
 * words, the same way a Role card's icon is meta and its title is content.
 */
$intera_hub_landings = array(
	array(
		'key'      => 'role-sysadmin',
		'icon'     => 'shield-check',
		'headline' => 'role_sysadmin_hero__see_what_needs_attention_before_someone',
	),
	array(
		'key'      => 'role-finance',
		'icon'     => 'circle-dollar-sign',
		'headline' => 'role_finance_hero__know_where_the_numbers_do_not',
	),
	array(
		'key'      => 'role-account-management',
		'icon'     => 'contact',
		'headline' => 'role_account_hero__know_what_was_promised_and_what',
	),
);

$intera_hub_tiles = array();

foreach ( $intera_hub_landings as $intera_hub_landing ) {
	$intera_hub_id = (int) intera_page_id( $intera_hub_landing['key'] );

	if ( ! $intera_hub_id ) {
		continue;
	}

	$intera_hub_tiles[] = array(
		'icon'  => $intera_hub_landing['icon'],
		'title' => (string) get_the_title( $intera_hub_id ),
		'line'  => (string) intera_copy( $intera_hub_landing['headline'], $intera_hub_id ),
		'url'   => (string) get_permalink( $intera_hub_id ),
	);
}
?>

<section data-screen-label="Page header" style="background: var(--surface-sunken); border-bottom: 1px solid var(--border-subtle)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(30px, 7vw, 52px) clamp(20px, 5vw, 24px) clamp(23px, 7vw, 40px)">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 660px; margin-top: 20px">
			<h1 style="font-size: clamp(28px, 3vw, 36px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.16; color: var(--ink-900)"><?php the_title(); ?></h1>
			<?php if ( '' !== trim( (string) get_the_content() ) ) : ?>
				<div class="intera-prose" style="--itr-prose-max: 620px; margin-top: 14px"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( $intera_hub_tiles ) : ?>
	<section data-screen-label="By role" style="background: var(--surface-page)">
		<div style="max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
			<div style="max-width: 720px; margin-bottom: 36px">
				<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'roles_landings__start_here' ) ); ?></div>
				<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'roles_landings__written_for_the_job_you_actually' ) ); ?></h2>
				<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php echo esc_html( intera_copy( 'roles_landings__each_page_below_answers_for_one' ) ); ?></p>
			</div>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px">
				<?php
				$intera_hub_more = intera_copy( 'roles_landings__see_the_role' );

				foreach ( $intera_hub_tiles as $intera_hub_tile ) {
					ob_start();
					?>
					<div style="display: flex; flex-direction: column; height: 100%">
						<div style="color: var(--blue-600); margin-bottom: 14px"><?php intera_icon( $intera_hub_tile['icon'], array( 'size' => 22 ) ); ?></div>
						<h3 style="font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.3; color: var(--ink-900); margin: 0">
							<?php
							/*
							 * The one anchor in the card, and the one the cover
							 * belongs to: a reader tabbing through the page gets
							 * three stops here rather than six, and each one
							 * announces the page it opens.
							 */
							?>
							<a class="itr-linkcard-target" href="<?php echo esc_url( $intera_hub_tile['url'] ); ?>"><?php echo esc_html( $intera_hub_tile['title'] ); ?></a>
						</h3>
						<p style="font-size: var(--text-base); line-height: 1.6; color: var(--ink-600); margin-top: 10px"><?php echo esc_html( $intera_hub_tile['line'] ); ?></p>
						<span style="display: inline-flex; align-items: center; gap: 8px; margin-top: auto; padding-top: 20px; font-size: var(--text-sm); font-weight: 600; color: var(--blue-600)">
							<?php
							echo esc_html( $intera_hub_more );
							intera_icon( 'arrow-right', array( 'size' => 15 ) );
							?>
						</span>
					</div>
					<?php
					get_template_part(
						'template-parts/components/card',
						null,
						array(
							'content'     => ob_get_clean(),
							'padding'     => 'loose',
							'interactive' => true,
							'class'       => 'itr-lift itr-linkcard',
							'style'       => 'height: 100%',
						)
					);
				}
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<section id="roles" data-screen-label="Roles" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 16%; top: 24%; width: 900px; height: 900px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-violet) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(51px, 7vw, 88px) clamp(20px, 5vw, 24px)">
		<div style="max-width: 720px; margin-bottom: 36px">
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--blue-600); margin-bottom: 14px"><?php echo esc_html( intera_copy( 'roles_all__intera_roles' ) ); ?></div>
			<h2 style="font-size: var(--text-3xl); font-weight: 600; letter-spacing: -0.01em; line-height: 1.22; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'roles_all__a_module_built_around_a_responsibility' ) ); ?></h2>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 16px"><?php echo esc_html( intera_copy( 'roles_all__a_role_arrives_with_its_metrics' ) ); ?></p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(270px, 100%), 1fr)); gap: 20px">
			<?php
			// The same query shape the home and product pages use.
			$intera_role_posts = get_posts(
				array(
					'post_type'        => 'role',
					'post_status'      => 'publish',
					'numberposts'      => -1,
					'orderby'          => 'menu_order date',
					'order'            => 'ASC',
					'suppress_filters' => false,
				)
			);

			foreach ( $intera_role_posts as $intera_role_post ) {
				get_template_part(
					'template-parts/partials/role-card',
					null,
					array(
						'post'    => $intera_role_post,
						'variant' => 'product',
						'heading' => 'h3',
					)
				);
			}
			?>
		</div>
		<?php if ( '' !== $intera_roles_url ) : ?>
			<div style="margin-top: 32px">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'      => intera_copy( 'roles_all__see_how_roles_work' ),
						'href'       => $intera_roles_url,
						'variant'    => 'secondary',
						'icon_right' => 'arrow-right',
					)
				);
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_template_part(
	'template-parts/partials/method-band',
	null,
	array(
		'source_id' => intera_page_id( 'product' ),
		'cta_url'   => $intera_request_url,
	)
);

get_footer();
