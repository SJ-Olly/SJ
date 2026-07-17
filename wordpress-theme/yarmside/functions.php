<?php
/**
 * Yarmside theme setup.
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'YARMSIDE_VERSION', '1.0.0' );

/**
 * Theme supports, menus and editor integration.
 */
function yarmside_setup() {
	load_theme_textdomain( 'yarmside', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 571,
		'width'       => 1999,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'editor-style.css' );

	// Brand palette only: keeps future content on the guidelines.
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'Yarmside orange', 'yarmside' ),
			'slug'  => 'yarmside-orange',
			'color' => '#d8651e',
		),
		array(
			'name'  => __( 'Dark grey', 'yarmside' ),
			'slug'  => 'dark-grey',
			'color' => '#141414',
		),
		array(
			'name'  => __( 'Orange cream', 'yarmside' ),
			'slug'  => 'orange-cream',
			'color' => '#f5f1ea',
		),
		array(
			'name'  => __( 'Grey orange', 'yarmside' ),
			'slug'  => 'grey-orange',
			'color' => '#8e867a',
		),
	) );
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'disable-custom-gradients' );

	register_nav_menus( array(
		'primary'          => __( 'Primary navigation', 'yarmside' ),
		'footer-lettings'  => __( 'Footer: Lettings column', 'yarmside' ),
		'footer-landlords' => __( 'Footer: Landlords column', 'yarmside' ),
		'footer-practice'  => __( 'Footer: Practice column', 'yarmside' ),
	) );

	add_image_size( 'yarmside-card', 800, 600, true );
	add_image_size( 'yarmside-wide', 1600, 1000, true );
}
add_action( 'after_setup_theme', 'yarmside_setup' );

/**
 * Styles and scripts.
 */
function yarmside_assets() {
	wp_enqueue_style(
		'yarmside-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Raleway:wght@400;500&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'yarmside-style', get_stylesheet_uri(), array( 'yarmside-fonts' ), YARMSIDE_VERSION );
	wp_enqueue_script( 'yarmside-main', get_template_directory_uri() . '/js/main.js', array(), YARMSIDE_VERSION, array( 'strategy' => 'defer' ) );
}
add_action( 'wp_enqueue_scripts', 'yarmside_assets' );

/**
 * Preconnect for Google Fonts.
 */
function yarmside_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'yarmside_resource_hints', 10, 2 );

/**
 * Properties post type. Registered in the theme so the proposal is
 * self-contained; move to a plugin if content must survive theme switches.
 */
function yarmside_register_property_cpt() {
	register_post_type( 'property', array(
		'labels'       => array(
			'name'          => __( 'Properties', 'yarmside' ),
			'singular_name' => __( 'Property', 'yarmside' ),
			'add_new_item'  => __( 'Add new property', 'yarmside' ),
			'edit_item'     => __( 'Edit property', 'yarmside' ),
		),
		'public'       => true,
		'has_archive'  => 'properties',
		'rewrite'      => array( 'slug' => 'property' ),
		'menu_icon'    => 'dashicons-admin-home',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'yarmside_register_property_cpt' );

/**
 * Body class for pages without a photographic hero.
 */
function yarmside_body_classes( $classes ) {
	if ( ! is_front_page() ) {
		$classes[] = 'no-hero';
	}
	return $classes;
}
add_filter( 'body_class', 'yarmside_body_classes' );

/**
 * Fallback primary menu: lists top-level pages until a menu is assigned.
 */
function yarmside_menu_fallback() {
	wp_page_menu( array( 'container' => false, 'before' => '<ul>', 'after' => '</ul>' ) );
}

/**
 * Reading time helper for journal cards.
 */
function yarmside_reading_time( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id ? $post_id : get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) round( $words / 220 ) );
	/* translators: %d: minutes. */
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'yarmside' ), $minutes );
}

/**
 * Property meta helper.
 */
function yarmside_property_meta( $key, $post_id = null ) {
	return get_post_meta( $post_id ? $post_id : get_the_ID(), '_yarmside_' . $key, true );
}

/**
 * Valuation form handler: emails the site admin.
 */
function yarmside_handle_valuation() {
	if ( ! isset( $_POST['yarmside_valuation_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['yarmside_valuation_nonce'] ), 'yarmside_valuation' ) ) {
		wp_safe_redirect( add_query_arg( 'valuation', 'error', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
		exit;
	}

	// Honeypot.
	if ( ! empty( $_POST['website_field'] ) ) {
		wp_safe_redirect( add_query_arg( 'valuation', 'sent', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
		exit;
	}

	$name     = isset( $_POST['your_name'] ) ? sanitize_text_field( wp_unslash( $_POST['your_name'] ) ) : '';
	$email    = isset( $_POST['your_email'] ) ? sanitize_email( wp_unslash( $_POST['your_email'] ) ) : '';
	$phone    = isset( $_POST['your_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['your_phone'] ) ) : '';
	$interest = isset( $_POST['interest'] ) ? sanitize_text_field( wp_unslash( $_POST['interest'] ) ) : '';
	$address  = isset( $_POST['property_address'] ) ? sanitize_text_field( wp_unslash( $_POST['property_address'] ) ) : '';
	$message  = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( empty( $name ) || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'valuation', 'error', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
		exit;
	}

	$to      = get_theme_mod( 'yarmside_contact_email', get_option( 'admin_email' ) );
	$subject = sprintf( __( 'Valuation request from %s', 'yarmside' ), $name );
	$body    = sprintf(
		"%s\n\nName: %s\nEmail: %s\nPhone: %s\nInterested in: %s\nProperty address: %s\n\n%s",
		__( 'New enquiry from the website valuation form.', 'yarmside' ),
		$name,
		$email,
		$phone,
		$interest,
		$address,
		$message
	);

	wp_mail( $to, $subject, $body, array( 'Reply-To: ' . $name . ' <' . $email . '>' ) );

	wp_safe_redirect( add_query_arg( 'valuation', 'sent', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
	exit;
}
add_action( 'admin_post_yarmside_valuation', 'yarmside_handle_valuation' );
add_action( 'admin_post_nopriv_yarmside_valuation', 'yarmside_handle_valuation' );

require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/property-meta.php';
