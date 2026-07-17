<?php
/**
 * Yarmside theme bootstrap.
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'YARMSIDE_VERSION', '1.0.0' );

require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/block-patterns.php';
require get_template_directory() . '/inc/contact-form.php';
require get_template_directory() . '/inc/demo-content.php';

/**
 * Theme supports, menus, image sizes.
 */
function yarmside_setup() {
	load_theme_textdomain( 'yarmside', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
	add_editor_style( yarmside_fonts_url() );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 420,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Pages use their excerpt as the branded lede beneath the page title.
	add_post_type_support( 'page', 'excerpt' );

	register_nav_menus(
		array(
			'primary'  => __( 'Primary navigation', 'yarmside' ),
			'footer-1' => __( 'Footer — Lettings', 'yarmside' ),
			'footer-2' => __( 'Footer — Landlords', 'yarmside' ),
			'footer-3' => __( 'Footer — Practice', 'yarmside' ),
		)
	);

	// Card and bay crops used throughout the design.
	add_image_size( 'yarmside-card', 800, 600, true );      // 4:3 property cards.
	add_image_size( 'yarmside-article', 800, 500, true );   // 16:10 journal cards.
	add_image_size( 'yarmside-bay', 1600, 1000, true );     // Featured bays / gallery lead.
	add_image_size( 'yarmside-hero', 1920, 1280, false );   // Full-bleed hero.

	$GLOBALS['content_width'] = 1312;
}
add_action( 'after_setup_theme', 'yarmside_setup' );

/**
 * Google Fonts URL for the two brand families.
 */
function yarmside_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Raleway:wght@400;500&display=swap';
}

/**
 * Front-end assets.
 */
function yarmside_enqueue_assets() {
	wp_enqueue_style( 'yarmside-fonts', yarmside_fonts_url(), array(), null );
	wp_enqueue_style( 'yarmside-style', get_stylesheet_uri(), array( 'yarmside-fonts' ), YARMSIDE_VERSION );
	wp_add_inline_style( 'yarmside-style', yarmside_brand_css() );

	wp_enqueue_script(
		'yarmside-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		YARMSIDE_VERSION,
		array( 'strategy' => 'defer' )
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'yarmside_enqueue_assets' );

/**
 * Customizer brand colours become CSS variables so every template,
 * pattern, post and page picks them up automatically.
 */
function yarmside_brand_css() {
	$colors = array(
		'orange'      => get_theme_mod( 'yarmside_color_orange', '#d8651e' ),
		'dark'        => get_theme_mod( 'yarmside_color_dark', '#141414' ),
		'cream'       => get_theme_mod( 'yarmside_color_cream', '#f5f1ea' ),
		'grey-orange' => get_theme_mod( 'yarmside_color_grey_orange', '#8e867a' ),
	);

	$css = ':root{';
	foreach ( $colors as $name => $value ) {
		$value = sanitize_hex_color( $value );
		if ( $value ) {
			$css .= '--' . $name . ':' . $value . ';';
		}
	}
	$css .= '}';

	return $css;
}

/**
 * Preconnect for Google Fonts.
 */
function yarmside_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'yarmside_resource_hints', 10, 2 );

/**
 * Admin assets for the property gallery picker.
 */
function yarmside_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'property' !== $screen->post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'yarmside-admin',
		get_template_directory_uri() . '/assets/js/admin.js',
		array( 'jquery' ),
		YARMSIDE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'yarmside_admin_assets' );

/**
 * Button styles that mirror the design's ghost buttons.
 */
function yarmside_register_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'  => 'ghost',
			'label' => __( 'Ghost (dark outline)', 'yarmside' ),
		)
	);
	register_block_style(
		'core/button',
		array(
			'name'  => 'ghost-light',
			'label' => __( 'Ghost (light outline)', 'yarmside' ),
		)
	);
}
add_action( 'init', 'yarmside_register_block_styles' );

/**
 * Sensible archive tuning: the property archive shows a full page of cards,
 * the journal shows nine articles per page.
 */
function yarmside_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'property' ) || $query->is_tax( 'property_type' ) ) {
		$query->set( 'posts_per_page', 12 );
	}
	if ( $query->is_home() ) {
		$query->set( 'posts_per_page', 9 );
	}
}
add_action( 'pre_get_posts', 'yarmside_pre_get_posts' );

/**
 * Trim head output the theme doesn't need. Keeps pages lean.
 */
function yarmside_clean_head() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'yarmside_clean_head' );

/**
 * Excerpts read like the design's ledes: short, no ellipsis chatter.
 */
function yarmside_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'yarmside_excerpt_more' );

function yarmside_excerpt_length() {
	return 28;
}
add_filter( 'excerpt_length', 'yarmside_excerpt_length' );

/**
 * RealEstateAgent structured data, populated from the Customizer.
 */
function yarmside_structured_data() {
	if ( ! is_front_page() && ! is_page( 'contact' ) ) {
		return;
	}

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'RealEstateAgent',
		'name'        => get_bloginfo( 'name' ),
		'url'         => home_url( '/' ),
		'description' => get_bloginfo( 'description' ),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => get_theme_mod( 'yarmside_contact_street', 'High Street' ),
			'addressLocality' => get_theme_mod( 'yarmside_contact_locality', 'Yarm' ),
			'addressRegion'   => get_theme_mod( 'yarmside_contact_region', 'Stockton-on-Tees' ),
			'addressCountry'  => 'GB',
		),
	);

	$email = get_theme_mod( 'yarmside_contact_email' );
	if ( $email ) {
		$schema['email'] = sanitize_email( $email );
	}
	$phone = get_theme_mod( 'yarmside_contact_phone' );
	if ( $phone ) {
		$schema['telephone'] = sanitize_text_field( $phone );
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'yarmside_structured_data' );

/**
 * Plain-anchor nav walker: the design renders links, not list items.
 */
class Yarmside_Nav_Walker extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$atts = array(
			'href' => ! empty( $item->url ) ? $item->url : '',
		);
		if ( in_array( 'current-menu-item', (array) $item->classes, true ) ) {
			$atts['aria-current'] = 'page';
		}

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( '' !== $value ) {
				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
			}
		}

		$output .= '<a' . $attributes . '>' . esc_html( $item->title ) . '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}
