<?php
/**
 * Site header: fixed bar with logo, primary nav and mobile toggle.
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

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'yarmside' ); ?></a>

<header class="site-header" id="siteHeader">
	<div class="wrap site-header__bar">
		<?php yarmside_logo( 'header' ); ?>

		<nav class="nav" aria-label="<?php esc_attr_e( 'Primary', 'yarmside' ); ?>">
			<?php yarmside_primary_nav_links( 'primary' ); ?>
		</nav>

		<button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="navMobile">
			<?php esc_html_e( 'Menu', 'yarmside' ); ?>
		</button>
	</div>

	<nav class="nav-mobile wrap" id="navMobile" aria-label="<?php esc_attr_e( 'Primary, mobile', 'yarmside' ); ?>">
		<?php yarmside_primary_nav_links( 'mobile' ); ?>
	</nav>
</header>

<main id="main">
