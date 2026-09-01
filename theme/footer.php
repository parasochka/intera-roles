<?php
/**
 * Site footer — the dark four-column band from `_design/site-footer.dc.html`.
 *
 * Structure, quoted from the handoff:
 *
 *   footer   background: var(--ink-900); color: rgba(255,255,255,.72)
 *   columns  class="itr-cols-4"; max-width: 1160px; margin: 0 auto;
 *            padding: clamp(32px, 7vw, 56px) clamp(20px, 5vw, 24px) 36px
 *            (the grid tracks — 1.4fr 1fr 1fr 1fr, gap 40px — live in
 *            `.itr-cols-4` so the 900px / 760px collapses need no `!important`)
 *   legal    border-top: 1px solid var(--border-inverse); inner strip
 *            padding: 20px clamp(20px, 5vw, 24px); font-size: var(--text-xs);
 *            color: var(--text-inverse-muted)
 *
 * Column one is the lockup, the brand line and the call to action, all from
 * `intera_option()`. Columns two to four are the `footer_product`,
 * `footer_resources` and `footer_company` menu locations; each column heading is
 * the name of the menu assigned to that location, so an editor renames a column by
 * renaming its menu. A menu item carrying the CSS class `group-break` opens a new
 * group with the handoff's `1px solid rgba(255,255,255,.12)` divider above it —
 * that rule is `.intera-foot-links .group-break` in `assets/css/intera.css`.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$intera_footer_blurb     = trim( (string) intera_option( 'footer_blurb' ) );
$intera_footer_cta_label = trim( (string) intera_option( 'footer_cta_label' ) );
$intera_footer_cta_url   = trim( (string) intera_option( 'footer_cta_url' ) );

/*
 * No option set yet: fall back to whichever page carries the
 * `page-contact-request.php` template. Assigning that template in the editor
 * is enough to light the call to action up — the Customizer field overrides
 * the destination, it is not a prerequisite for having one.
 */
if ( '' === $intera_footer_cta_url && function_exists( 'intera_page_url' ) ) {
	$intera_footer_cta_url = (string) intera_page_url( 'contact-request' );
}
$intera_copyright        = trim( (string) intera_option( 'copyright' ) );
$intera_site_domain      = trim( (string) intera_option( 'site_domain' ) );
$intera_contact_person   = trim( (string) intera_option( 'contact_person' ) );
$intera_contact_url      = trim( (string) intera_option( 'contact_person_url' ) );

// A name with nowhere to go, or a link with nothing to label it, is not a route.
$intera_has_contact_link = ( '' !== $intera_contact_person && '' !== $intera_contact_url );

// A call to action with no target would render as a dead <button>; leave it out instead.
$intera_footer_has_cta = ( '' !== $intera_footer_cta_label && '' !== $intera_footer_cta_url );

// The three link columns, in the handoff's order. The heading is the menu's own name.
$intera_footer_columns = array( 'footer_product', 'footer_resources', 'footer_company' );
$intera_menu_locations = get_nav_menu_locations();

/**
 * Builds a one-shot `nav_menu_link_attributes` callback that appends link classes.
 *
 * The handoff's resting colour lives in `.itr-foot-link` / `.itr-foot-link--dim`
 * (recon shared-css §3), not inline, so the `:hover` to `var(--white)` wins
 * without `!important`.
 *
 * @param string $intera_class Space-separated classes to append.
 * @return callable Filter callback.
 */
$intera_foot_link_classes = static function ( $intera_class ) {
	return static function ( $atts ) use ( $intera_class ) {
		$atts['class'] = isset( $atts['class'] ) && '' !== $atts['class']
			? $atts['class'] . ' ' . $intera_class
			: $intera_class;

		return $atts;
	};
};

$intera_foot_link_filter  = $intera_foot_link_classes( 'itr-foot-link' );
$intera_legal_link_filter = $intera_foot_link_classes( 'itr-foot-link itr-foot-link--dim' );
?>
</main>

<footer class="intera-footer" style="background: var(--ink-900); color: rgba(255,255,255,.72)">
	<div class="itr-cols-4" style="max-width: 1160px; margin: 0 auto; padding: clamp(32px, 7vw, 56px) clamp(20px, 5vw, 24px) 36px">
		<div style="min-width: 0">
			<?php
			get_template_part(
				'template-parts/components/logo',
				null,
				array(
					'size' => 18,
					'tone' => 'inverse',
				)
			);
			?>

			<?php if ( '' !== $intera_footer_blurb ) : ?>
				<p class="intera-footer-blurb" style="font-size: var(--text-md); line-height: 1.6; margin: 16px 0 0; max-width: 300px"><?php echo esc_html( $intera_footer_blurb ); ?></p>
			<?php endif; ?>

			<?php if ( $intera_footer_has_cta ) : ?>
				<div style="margin-top: 20px">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'   => $intera_footer_cta_label,
							'href'    => $intera_footer_cta_url,
							'variant' => 'inverse',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>

		<?php
		foreach ( $intera_footer_columns as $intera_location ) :
			// The empty <div> keeps the four grid tracks stable when a location has no menu.
			if ( ! has_nav_menu( $intera_location ) ) {
				echo '<div></div>';
				continue;
			}

			$intera_menu    = isset( $intera_menu_locations[ $intera_location ] )
				? wp_get_nav_menu_object( $intera_menu_locations[ $intera_location ] )
				: false;
			$intera_heading = $intera_menu ? $intera_menu->name : '';
			?>
			<div>
				<?php if ( '' !== $intera_heading ) : ?>
					<div class="intera-foot-heading"><?php echo esc_html( $intera_heading ); ?></div>
				<?php endif; ?>
				<?php
				add_filter( 'nav_menu_link_attributes', $intera_foot_link_filter );

				wp_nav_menu(
					array(
						'theme_location' => $intera_location,
						'container'      => false,
						'menu_class'     => 'intera-foot-links',
						'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);

				remove_filter( 'nav_menu_link_attributes', $intera_foot_link_filter );
				?>
			</div>
			<?php
		endforeach;
		?>
	</div>

	<div style="border-top: 1px solid var(--border-inverse)">
		<div style="max-width: 1160px; margin: 0 auto; padding: 20px clamp(20px, 5vw, 24px); display: flex; flex-wrap: wrap; gap: 12px; align-items: baseline; justify-content: space-between; font-size: var(--text-xs); color: var(--text-inverse-muted)">
			<span class="intera-copyright"><?php echo esc_html( $intera_copyright ); ?></span>

			<?php
			if ( has_nav_menu( 'footer_legal' ) ) {
				add_filter( 'nav_menu_link_attributes', $intera_legal_link_filter );

				wp_nav_menu(
					array(
						'theme_location'       => 'footer_legal',
						'container'            => 'nav',
						'container_class'      => 'intera-legal-nav',
						'container_aria_label' => __( 'Legal', 'intera' ),
						'menu_class'           => 'intera-legal-links',
						'items_wrap'           => '<ul class="%2$s" style="display: flex; flex-wrap: wrap; gap: 18px; list-style: none; margin: 0; padding: 0">%3$s</ul>',
						'depth'                => 1,
						'fallback_cb'          => false,
					)
				);

				remove_filter( 'nav_menu_link_attributes', $intera_legal_link_filter );
			}
			?>

			<span style="font-family: var(--font-mono); display: flex; flex-wrap: wrap; gap: 6px 18px; align-items: baseline">
				<?php if ( '' !== $intera_site_domain ) : ?>
					<span class="intera-site-domain"><?php echo esc_html( $intera_site_domain ); ?></span>
				<?php endif; ?>
				<?php if ( $intera_has_contact_link ) : ?>
					<a class="itr-foot-link itr-foot-link--dim" href="<?php echo esc_url( $intera_contact_url ); ?>" target="_blank" rel="noopener noreferrer" style="font-family: var(--font-sans)"><?php echo esc_html( $intera_contact_person ); ?></a>
				<?php endif; ?>
			</span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
