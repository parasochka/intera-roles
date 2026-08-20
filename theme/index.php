<?php
/**
 * Fallback template — the post list.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<?php if ( have_posts() ) : ?>
	<div class="intera-grid">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'intera-card' ); ?>>
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'medium_large' ); ?>
					<h2><?php the_title(); ?></h2>
				</a>
				<?php the_excerpt(); ?>
			</article>
			<?php
		endwhile;
		?>
	</div>
	<?php the_posts_pagination(); ?>
<?php else : ?>
	<p><?php esc_html_e( 'Nothing here yet.', 'intera' ); ?></p>
<?php endif; ?>

<?php
get_footer();
