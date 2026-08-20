<?php
/**
 * Site header.
 *
 * Placeholder markup until the Claude Design handoff screens land — then this
 * becomes the design's masthead, verbatim, with only the dynamic slots swapped.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;
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

<header class="intera-masthead">
	<a class="intera-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<?php bloginfo( 'name' ); ?>
	</a>
	<?php
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => 'nav',
				'menu_class'     => 'intera-nav',
			)
		);
	}
	?>
</header>

<main id="intera-main" class="intera-main">
