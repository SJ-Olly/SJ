<?php
/**
 * Site header.
 *
 * @package Yarmside
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="siteHeader">
	<div class="wrap site-header__bar">
		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<a class="logo custom-logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<img class="custom-logo" src="<?php echo esc_url( get_template_directory_uri() . '/images/yarmside-logo.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="1999" height="571">
			</a>
		<?php endif; ?>

		<nav class="nav" aria-label="<?php esc_attr_e( 'Primary', 'yarmside' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => 'yarmside_menu_fallback',
			) );
			?>
		</nav>

		<button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="navMobile">
			<?php esc_html_e( 'Menu', 'yarmside' ); ?>
		</button>
	</div>

	<nav class="nav-mobile wrap" id="navMobile" aria-label="<?php esc_attr_e( 'Primary, mobile', 'yarmside' ); ?>">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'depth'          => 1,
			'fallback_cb'    => 'yarmside_menu_fallback',
		) );
		?>
	</nav>
</header>
