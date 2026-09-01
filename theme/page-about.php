<?php
/**
 * Template Name: About us
 *
 * Not one of the six export screens — there is no `about.dc.html` — so nothing
 * here is a port. It is built from the parts the handoff already draws, in the
 * arrangement the other inner pages use: `page-contacts.php`'s header band
 * (breadcrumb, headline, lede) over a sunken band carrying the radial wash and
 * a grid of Cards, exactly as "Who to talk to" does. Every value is a token and
 * every hover state is a class, so the page re-themes with the rest of the site.
 *
 * What comes from WordPress:
 *
 * | slot                    | source                                          |
 * | ----------------------- | ----------------------------------------------- |
 * | breadcrumb              | `intera_breadcrumbs()` (auto: Home / <title>)    |
 * | header heading          | `about_headline` copy, falling back to the title |
 * | header lede             | `the_content()`                                  |
 * | section heading         | `about_people__the_people` copy                  |
 * | each person's name/role | `about_people__*` copy on this page              |
 * | the profile link        | `about_people__linkedin_url` copy                |
 * | the closing call        | `intera_page_url( 'contacts' )`                  |
 *
 * The people are copy rather than a custom post type on purpose: `role`, `plan`
 * and `docs` are records the site repeats in several places, and a founder line
 * is not — it is a run of text on one page, which is what `inc/copy.php` is for.
 * A second person means a second entry in `$intera_about_people` and its keys in
 * `inc/copy-defaults.php`; the grid already lays out as many as it is given.
 *
 * A person whose name is cleared drops out of the grid, and the profile link
 * disappears on its own when the URL is emptied — an empty value never ships an
 * empty card or a link that goes nowhere.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'intera_about_initials' ) ) :
	/**
	 * The monogram a person's card carries in place of a photograph.
	 *
	 * The site ships no editorial images of its own — `assets/img/` is theme
	 * chrome — and a stock avatar would be a hardcoded image in a template. Two
	 * letters from the name the editor typed need no media library and no
	 * deployment, and they change when the name does.
	 *
	 * Multibyte-safe: a name in Cyrillic gets its own first letters, not the
	 * first two bytes of one of them.
	 *
	 * @param string $name Person's name.
	 * @return string One or two upper-case letters, or '' when there is no name.
	 */
	function intera_about_initials( $name ) {
		$parts    = preg_split( '/\s+/u', trim( (string) $name ), -1, PREG_SPLIT_NO_EMPTY );
		$initials = '';

		foreach ( (array) $parts as $part ) {
			$initials .= function_exists( 'mb_substr' ) ? mb_substr( $part, 0, 1 ) : substr( $part, 0, 1 );

			if ( 2 <= ( function_exists( 'mb_strlen' ) ? mb_strlen( $initials ) : strlen( $initials ) ) ) {
				break;
			}
		}

		return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $initials ) : strtoupper( $initials );
	}
endif;

get_header();

if ( have_posts() ) {
	the_post();
}

$intera_contacts_url = (string) intera_page_url( 'contacts' );

/*
 * The people, in the order they are shown. `url` is run through `esc_url()` at
 * the point of output, so an editor who empties or mistypes it costs the link,
 * never the page.
 */
$intera_about_people = array(
	array(
		'name'      => trim( (string) intera_copy( 'about_people__sergey_bogdanov' ) ),
		'role'      => trim( (string) intera_copy( 'about_people__founder' ) ),
		'link_text' => trim( (string) intera_copy( 'about_people__linkedin' ) ),
		'url'       => trim( (string) intera_copy( 'about_people__linkedin_url' ) ),
	),
);
?>

<section data-screen-label="About header" style="background: var(--surface-page); border-bottom: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(35px, 7vw, 60px) clamp(20px, 5vw, 24px) clamp(26px, 7vw, 44px)">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 660px; margin-top: 22px">
			<h1 style="font-size: clamp(30px, 3.2vw, 38px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.14; color: var(--ink-900)"><?php
					/*
					 * The design's headline, not the page title — the same split
					 * `page-contacts.php` makes. The title stays short enough for
					 * the breadcrumb and the footer menu; this is a full sentence.
					 */
					$intera_headline = trim( (string) intera_copy( 'about_headline' ) );
					echo esc_html( '' !== $intera_headline ? $intera_headline : get_the_title() );
					?></h1>
			<?php if ( '' !== trim( (string) get_the_content() ) ) : ?>
				<div class="intera-prose" style="--itr-prose-max: 660px; margin-top: 18px"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section data-screen-label="The people" style="position: relative; overflow: hidden; background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div aria-hidden="true" style="position: absolute; left: 18%; top: 26%; width: 820px; height: 820px; transform: translate(-50%,-50%); pointer-events: none; background: radial-gradient(circle, var(--wash-amber) 0%, transparent 68%)"></div>
	<div style="position: relative; max-width: 1160px; margin: 0 auto; padding: clamp(42px, 7vw, 72px) clamp(20px, 5vw, 24px)">
		<h2 style="font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)"><?php echo esc_html( intera_copy( 'about_people__the_people' ) ); ?></h2>

		<?php
		/*
		 * `auto-fill`, not `auto-fit`: the tracks the row has room for are laid
		 * out whether or not there is a person to put in them, so the single
		 * founder card keeps a third of the row instead of stretching across it.
		 * The count still comes from the width — three tracks at the 1160px
		 * content width, two below roughly 900px, one on a phone — so a second
		 * person needs nothing here.
		 */
		?>
		<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(min(280px, 100%), 1fr)); gap: 20px; margin-top: 26px">
			<?php
			foreach ( $intera_about_people as $intera_person ) {
				// A person with no name is a card with nothing in it; leave it out.
				if ( '' === $intera_person['name'] ) {
					continue;
				}

				ob_start();
				?>
				<div style="display: flex; align-items: center; gap: 14px; min-width: 0">
					<span aria-hidden="true" style="flex: none; display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 999px; background: var(--wash-blue); color: var(--blue-600); font-family: var(--font-mono); font-size: var(--text-sm); letter-spacing: 0.02em"><?php echo esc_html( intera_about_initials( $intera_person['name'] ) ); ?></span>
					<div style="min-width: 0">
						<div style="font-size: var(--text-lg); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900); overflow-wrap: anywhere"><?php echo esc_html( $intera_person['name'] ); ?></div>
						<?php if ( '' !== $intera_person['role'] ) : ?>
							<div style="font-size: var(--text-sm); color: var(--ink-600); margin-top: 2px"><?php echo esc_html( $intera_person['role'] ); ?></div>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( '' !== $intera_person['url'] && '' !== $intera_person['link_text'] ) : ?>
				<div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-hairline)">
					<?php
					/*
					 * Several people mean several links reading "LinkedIn", which
					 * is the classic ambiguous-link-text problem: the accessible
					 * name says whose profile it is, the visible label stays short.
					 */
					$intera_person_link_name = sprintf(
						/* translators: 1: person's name, 2: the name of the profile site, e.g. LinkedIn. */
						__( '%1$s on %2$s', 'intera' ),
						$intera_person['name'],
						$intera_person['link_text']
					);
					?>
					<a class="itr-link-strong" href="<?php echo esc_url( $intera_person['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $intera_person_link_name ); ?>" style="display: inline-flex; align-items: center; gap: 8px; font-size: var(--text-sm)">
						<?php intera_icon( 'linkedin', array( 'size' => 16 ) ); ?>
						<span><?php echo esc_html( $intera_person['link_text'] ); ?></span>
					</a>
				</div>
				<?php endif; ?>
				<?php
				$intera_person_card = (string) ob_get_clean();

				get_template_part(
					'template-parts/components/card',
					null,
					array(
						'content' => $intera_person_card,
						'class'   => 'itr-hl',
					)
				);
			}
			?>
		</div>

		<?php if ( '' !== $intera_contacts_url ) : ?>
		<div style="margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--border-hairline)">
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label'      => intera_copy( 'about_people__talk_to_us' ),
					'href'       => $intera_contacts_url,
					'variant'    => 'link',
					'icon_right' => 'arrow-right',
				)
			);
			?>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
