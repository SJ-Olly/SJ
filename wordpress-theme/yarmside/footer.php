<?php
/**
 * Site footer.
 *
 * @package Yarmside
 */
?>

<footer class="site-footer">
	<div class="wrap">
		<div class="footer-grid">
			<div class="footer-col">
				<span class="logo">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/images/yarmside-logo.png' ); ?>" alt="" width="1999" height="571">
				</span>
				<p class="lede"><?php echo esc_html( get_theme_mod( 'yarmside_footer_strap', __( 'A specialist lettings and management practice based on Yarm High Street, working with buy to let landlords across the Tees Valley.', 'yarmside' ) ) ); ?></p>
			</div>

			<div class="footer-col">
				<h3 class="h3"><?php esc_html_e( 'Lettings', 'yarmside' ); ?></h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-lettings',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
				?>
			</div>

			<div class="footer-col">
				<h3 class="h3"><?php esc_html_e( 'Landlords', 'yarmside' ); ?></h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-landlords',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
				?>
			</div>

			<div class="footer-col">
				<h3 class="h3"><?php esc_html_e( 'Practice', 'yarmside' ); ?></h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-practice',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
				?>
			</div>
		</div>

		<div class="footer-legal">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>.</span>
			<span><?php echo esc_html( get_theme_mod( 'yarmside_footer_legal', __( 'Registered in England and Wales. Company number to be added.', 'yarmside' ) ) ); ?></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
