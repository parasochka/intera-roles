<?php
/**
 * The 404 template.
 *
 * Not in the export — designed from the system. Two sections, built out of the
 * shapes the ported inner pages already use: the `Contacts header` band
 * (breadcrumb, H1, lede, hairline bottom rule) from `page-contacts.php`, then a
 * `--surface-sunken` band in the two-column `repeat(auto-fit, minmax(min(300px,
 * 100%), 1fr))` grid that page's `Contact routes` section uses. `header.php`
 * owns the masthead, the spacer and `<main>`; `footer.php` closes it.
 *
 * What comes from WordPress:
 *
 * | slot                     | source                                              |
 * | ------------------------ | --------------------------------------------------- |
 * | breadcrumb               | `intera_breadcrumbs()` (auto: Home / Page not found) |
 * | requested path           | the request itself, printed in mono                  |
 * | search field             | `partials/search-form`                               |
 * | the destination list     | the `primary` menu, falling back to `intera_page_url()` |
 * | the "broken link" line   | `intera_option( 'contact_email' )` / contact-request  |
 *
 * The destinations are never a hardcoded list of links: they are the top level
 * of whatever menu the site has assigned to `primary`, labels included, so the
 * page follows the navigation instead of drifting from it. With no menu
 * assigned yet, the theme resolves the same destinations itself through
 * `intera_page_url()` and takes each label from the page that answers to it —
 * a destination whose page does not exist simply does not appear.
 *
 * ds-rules §2: the copy states the fact, then the next step. No apology, no
 * joke, no exclamation mark.
 *
 * PORT.md §1: the destination card carries `.itr-hl` and its rows `.itr-rail-row`,
 * so no `background`, `border`, `box-shadow`, `transition` — and no inline link
 * colour — is written on either.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

$intera_404_email   = trim( (string) intera_option( 'contact_email' ) );
$intera_404_request = (string) intera_page_url( 'contact-request' );
$intera_404_mailto  = '' !== $intera_404_email ? 'mailto:' . $intera_404_email : '';

// The address that was asked for, path only, printed as an identifier.
$intera_404_path = '';

if ( isset( $_SERVER['REQUEST_URI'] ) ) {
	$intera_404_path = (string) wp_parse_url( esc_url_raw( wp_unslash( (string) $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH );

	if ( strlen( $intera_404_path ) > 96 ) {
		$intera_404_path = substr( $intera_404_path, 0, 96 ) . '…';
	}
}

/*
 * Destinations: the top level of the primary menu, or what the theme can
 * resolve on its own. `search.php`'s empty state offers the same list, so the
 * resolver lives in inc/template-tags.php and neither page can drift.
 */
$intera_404_dest = intera_destinations_get( 6 );
?>

<section data-screen-label="Not found header" style="background: var(--surface-page); border-bottom: 1px solid var(--border-hairline)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(35px, 7vw, 60px) clamp(20px, 5vw, 24px) clamp(26px, 7vw, 44px)">
		<?php intera_breadcrumbs(); ?>
		<div style="max-width: 660px; margin-top: 22px">
			<h1 style="font-size: clamp(30px, 3.2vw, 38px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.14; color: var(--ink-900)"><?php esc_html_e( 'Page not found', 'intera' ); ?></h1>
			<p style="font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600); margin-top: 18px"><?php esc_html_e( 'This address does not match a page on this site. It may have moved, or the link may be incomplete.', 'intera' ); ?></p>
			<?php if ( '' !== $intera_404_path ) : ?>
				<div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: baseline; margin-top: 18px; font-size: var(--text-xs)">
					<span style="font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php esc_html_e( 'Requested', 'intera' ); ?></span>
					<code style="font-family: var(--font-mono); color: var(--ink-600); word-break: break-all"><?php echo esc_html( $intera_404_path ); ?></code>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section data-screen-label="Where to go" style="background: var(--surface-sunken); border-top: 1px solid var(--border-subtle)">
	<div style="max-width: 1160px; margin: 0 auto; padding: clamp(42px, 7vw, 72px) clamp(20px, 5vw, 24px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 44px; align-items: start">
		<div>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500); margin-bottom: 18px"><?php esc_html_e( 'Search', 'intera' ); ?></div>
			<p style="font-size: var(--text-md); line-height: 1.6; color: var(--ink-600); max-width: 420px"><?php esc_html_e( 'If you know what you were reading, search for it. One term matches more than a full sentence.', 'intera' ); ?></p>
			<div style="margin-top: 18px; max-width: 460px">
				<?php
				get_template_part(
					'template-parts/partials/search-form',
					null,
					array(
						'id'    => 'intera-search-404',
						'size'  => 'lg',
						'value' => '',
					)
				);
				?>
			</div>

			<?php if ( '' !== $intera_404_mailto || '' !== $intera_404_request ) : ?>
				<?php
				$intera_404_report = '' !== $intera_404_mailto
					? '<a class="itr-link-strong" href="' . esc_url( $intera_404_mailto ) . '" style="font-family: var(--font-mono)">' . esc_html( $intera_404_email ) . '</a>'
					: '<a href="' . esc_url( $intera_404_request ) . '">' . esc_html__( 'tell us', 'intera' ) . '</a>';
				?>
				<p style="font-size: var(--text-sm); line-height: 1.6; color: var(--ink-600); margin-top: 26px; padding-top: 20px; border-top: 1px solid var(--border-hairline); max-width: 460px">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: %s: contact email address, or a link to the contact request page. */
							__( 'If a link on this site brought you here, it is a fault on our side: %s.', 'intera' ),
							$intera_404_report
						)
					);
					?>
				</p>
			<?php endif; ?>
		</div>

		<?php
		if ( $intera_404_dest ) {
			ob_start();
			?>
			<div style="font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500)"><?php esc_html_e( 'Go to', 'intera' ); ?></div>
			<nav aria-label="<?php esc_attr_e( 'Main destinations', 'intera' ); ?>" style="display: flex; flex-direction: column; gap: 0; margin-top: 14px">
				<?php
				$intera_404_last = count( $intera_404_dest ) - 1;

				foreach ( $intera_404_dest as $intera_404_index => $intera_404_row ) :
					$intera_404_row_style = 'display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 13px 0; font-size: var(--text-md)';

					if ( $intera_404_index !== $intera_404_last ) {
						$intera_404_row_style .= '; border-bottom: 1px solid var(--border-hairline)';
					}
					?>
					<a class="itr-rail-row" href="<?php echo esc_url( $intera_404_row['url'] ); ?>" style="<?php echo esc_attr( $intera_404_row_style ); ?>">
						<span>
							<?php echo esc_html( $intera_404_row['label'] ); ?>
							<?php if ( '' !== $intera_404_row['note'] ) : ?>
								<span style="display: block; font-size: var(--text-sm); line-height: 1.5; color: var(--ink-600); margin-top: 3px"><?php echo esc_html( $intera_404_row['note'] ); ?></span>
							<?php endif; ?>
						</span>
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
				)
			);
		}
		?>
	</div>
</section>

<?php
get_footer();
