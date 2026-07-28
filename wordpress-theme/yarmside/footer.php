<?php
/**
 * Site footer: brand column, three link columns, legal line.
 *
 * @package Yarmside
 */
?>
</main>

<footer class="site-footer">
	<div class="wrap">
		<div class="footer-grid">
			<div class="footer-col">
				<?php yarmside_logo( 'footer' ); ?>
				<p class="lede"><?php echo esc_html( get_theme_mod( 'yarmside_footer_blurb', __( 'A specialist lettings and management practice based on Yarm High Street, working with buy to let landlords across the Tees Valley.', 'yarmside' ) ) ); ?></p>
			</div>

			<?php
			$yarmside_footer_columns = array(
				'footer-1' => __( 'Lettings', 'yarmside' ),
				'footer-2' => __( 'Landlords', 'yarmside' ),
				'footer-3' => __( 'Practice', 'yarmside' ),
			);
			foreach ( $yarmside_footer_columns as $yarmside_location => $yarmside_heading ) :
				if ( ! has_nav_menu( $yarmside_location ) ) {
					continue;
				}
				?>
				<div class="footer-col">
					<h3 class="h3"><?php echo esc_html( $yarmside_heading ); ?></h3>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => $yarmside_location,
							'container'      => false,
							'menu_class'     => '',
							'items_wrap'     => '<ul>%3$s</ul>',
							'depth'          => 1,
						)
					);
					?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php yarmside_footer_accreditations(); ?>

		<div class="footer-legal">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			<span><?php echo esc_html( get_theme_mod( 'yarmside_footer_legal', __( 'Registered in England and Wales. Company number to be added.', 'yarmside' ) ) ); ?></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
