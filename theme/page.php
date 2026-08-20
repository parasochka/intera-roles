<?php
/**
 * Standard page.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'intera-page' ); ?>>
		<h1><?php the_title(); ?></h1>
		<div class="intera-prose"><?php the_content(); ?></div>
	</article>
	<?php
endwhile;

get_footer();
