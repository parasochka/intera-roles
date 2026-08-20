<?php
/**
 * Site footer.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="intera-footer">
	<?php
	if ( has_nav_menu( 'footer_legal' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'footer_legal',
				'container'      => 'nav',
				'menu_class'     => 'intera-footer-nav',
			)
		);
	}
	?>
	<p class="intera-colophon">
		&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
	</p>
</footer>

<?php wp_footer(); ?>
</body>
</html>
