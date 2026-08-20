<?php
/**
 * Site header — the fixed masthead from `_design/site-nav.dc.html`.
 *
 * Structure, quoted from the handoff:
 *
 *   header  position: fixed; top/left/right: 0; z-index: 60;
 *           background: rgba(255,255,255,.92); backdrop-filter: blur(8px);
 *           border-bottom: 1px solid var(--border-subtle)
 *   bar     max-width: 1160px; margin: 0 auto; padding: 0 clamp(20px, 5vw, 24px);
 *           height: 76px; display: flex; align-items: center; gap: 24px
 *   spacer  height: 76px  (every mockup follows the import with this div)
 *
 * Those declarations live in `assets/css/intera.css` as `.intera-masthead`,
 * `.intera-masthead-bar` and `.intera-masthead-spacer` — the export's `!important`
 * and its `style-hover` attributes are rewritten there as real rules, so nothing
 * that a hover has to beat is repeated inline here.
 *
 * The export switches between the wide bar and the burger drawer in React state at
 * `window.innerWidth >= 1040`. This theme must work without JavaScript, so the
 * switch is a CSS breakpoint (1040px) and the drawer is a visually hidden checkbox
 * plus a `<label>` wearing the DS icon-button classes. `assets/js/intera.js` only
 * mirrors the state into `aria-expanded` / `.is-nav-open` and closes the drawer on
 * Escape, on an outside click and when the viewport goes wide.
 *
 * Nothing an editor would want to change is hardcoded: the six links are the
 * `primary` menu, the badge and the call to action are Customizer options, and the
 * lockup is the WordPress custom logo when one is set.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

$intera_has_primary = has_nav_menu( 'primary' );
$intera_badge_text  = trim( (string) intera_option( 'header_badge' ) );
$intera_cta_label   = trim( (string) intera_option( 'header_cta_label' ) );
$intera_cta_url     = trim( (string) intera_option( 'header_cta_url' ) );

/*
 * No option set yet: fall back to whichever page carries the
 * `page-contact-request.php` template. Assigning that template in the editor
 * is enough to light the call to action up — the Customizer field overrides
 * the destination, it is not a prerequisite for having one.
 */
if ( '' === $intera_cta_url && function_exists( 'intera_page_url' ) ) {
	$intera_cta_url = (string) intera_page_url( 'contact-request' );
}

// A call to action with no target would render as a dead <button>; leave it out instead.
$intera_has_cta = ( '' !== $intera_cta_label && '' !== $intera_cta_url );

/**
 * Builds a one-shot `nav_menu_link_attributes` callback that appends link classes.
 *
 * The handoff carries its link colours as inline `style` plus a preview-only
 * `style-hover`; per the recon those collide, so the resting colour moved into
 * `.itr-nav-link` / `.itr-nav-link--drawer` and the hover can win without
 * `!important`. The markup therefore only has to name the class.
 *
 * @param string $intera_class Space-separated classes to append.
 * @return callable Filter callback.
 */
$intera_nav_link_classes = static function ( $intera_class ) {
	return static function ( $atts ) use ( $intera_class ) {
		$atts['class'] = isset( $atts['class'] ) && '' !== $atts['class']
			? $atts['class'] . ' ' . $intera_class
			: $intera_class;

		return $atts;
	};
};
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#intera-main"><?php esc_html_e( 'Skip to content', 'intera' ); ?></a>

<header id="intera-masthead" class="intera-masthead">
	<?php if ( $intera_has_primary ) : ?>
		<?php
		/*
		 * The no-JS switch. It stays focusable (opacity, not display), the <label>
		 * below is the visible control, and `:checked ~ .intera-nav-drawer` opens the
		 * drawer. intera.js mirrors `checked` into aria-expanded and .is-nav-open.
		 */
		?>
		<input
			id="intera-nav-toggle"
			class="intera-nav-toggle"
			type="checkbox"
			aria-controls="intera-nav-drawer"
			aria-expanded="false"
			style="position:absolute;top:0;left:0;width:1px;height:1px;margin:0;padding:0;border:0;opacity:0;pointer-events:none">
	<?php endif; ?>

	<div class="intera-masthead-bar">
		<a class="intera-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php
			if ( has_custom_logo() ) {
				// A custom logo, when set, replaces the design-system lockup.
				the_custom_logo();
			} else {
				get_template_part(
					'template-parts/components/logo',
					null,
					array(
						'size' => 26,
						'tone' => 'ink',
					)
				);
			}
			?>
		</a>

		<?php
		if ( $intera_has_primary ) {
			$intera_wide_links = $intera_nav_link_classes( 'itr-nav-link' );
			add_filter( 'nav_menu_link_attributes', $intera_wide_links );

			wp_nav_menu(
				array(
					'theme_location'       => 'primary',
					'container'            => 'nav',
					'container_class'      => 'intera-nav-wide',
					'container_aria_label' => __( 'Primary', 'intera' ),
					'menu_class'           => 'intera-nav-list',
					'items_wrap'           => '<ul class="%2$s">%3$s</ul>',
					'depth'                => 1,
					'fallback_cb'          => false,
				)
			);

			remove_filter( 'nav_menu_link_attributes', $intera_wide_links );
		}
		?>

		<span class="intera-nav-actions">
			<?php
			if ( '' !== $intera_badge_text ) {
				get_template_part(
					'template-parts/components/badge',
					null,
					array(
						'text'  => $intera_badge_text,
						'tone'  => 'info',
						'class' => 'intera-header-badge',
					)
				);
			}

			if ( $intera_has_cta ) {
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => $intera_cta_label,
						'href'  => $intera_cta_url,
						'size'  => 'lg',
					)
				);
			}
			?>
		</span>

		<span class="intera-nav-narrow">
			<?php
			if ( $intera_has_cta ) {
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label' => $intera_cta_label,
						'href'  => $intera_cta_url,
						'size'  => 'md',
					)
				);
			}
			?>

			<?php if ( $intera_has_primary ) : ?>
				<label class="intera-nav-burger itr-iconbtn itr-iconbtn--outline itr-iconbtn--md" for="intera-nav-toggle">
					<span class="intera-nav-burger-icon intera-nav-burger-icon--open" aria-hidden="true">
						<?php
						if ( function_exists( 'intera_icon' ) ) {
							intera_icon( 'menu', array( 'size' => 18 ) );
						}
						?>
					</span>
					<span class="intera-nav-burger-icon intera-nav-burger-icon--close" aria-hidden="true">
						<?php
						if ( function_exists( 'intera_icon' ) ) {
							intera_icon( 'x', array( 'size' => 18 ) );
						}
						?>
					</span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'intera' ); ?></span>
				</label>
			<?php endif; ?>
		</span>
	</div>

	<?php
	if ( $intera_has_primary ) {
		/*
		 * Second render of the same menu. Item ids are suppressed so the two
		 * renders cannot emit duplicate `menu-item-{ID}` ids.
		 */
		$intera_drawer_links = $intera_nav_link_classes( 'itr-nav-link itr-nav-link--drawer' );
		add_filter( 'nav_menu_link_attributes', $intera_drawer_links );
		add_filter( 'nav_menu_item_id', '__return_empty_string' );

		wp_nav_menu(
			array(
				'theme_location'       => 'primary',
				'container'            => 'nav',
				'container_class'      => 'intera-nav-drawer',
				'container_id'         => 'intera-nav-drawer',
				'container_aria_label' => __( 'Primary', 'intera' ),
				'menu_class'           => 'intera-nav-drawer-list',
				'items_wrap'           => '<ul class="%2$s">%3$s</ul>',
				'depth'                => 1,
				'fallback_cb'          => false,
			)
		);

		remove_filter( 'nav_menu_item_id', '__return_empty_string' );
		remove_filter( 'nav_menu_link_attributes', $intera_drawer_links );
	}
	?>
</header>

<div class="intera-masthead-spacer" aria-hidden="true"></div>

<main id="intera-main" class="intera-main" tabindex="-1">
