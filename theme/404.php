<?php
/**
 * Not found.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="intera-404">
	<h1><?php esc_html_e( 'Page not found', 'intera' ); ?></h1>
	<p><?php esc_html_e( 'The page you are looking for does not exist.', 'intera' ); ?></p>
	<?php get_search_form(); ?>
</section>
<?php
get_footer();
