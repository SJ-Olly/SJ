<?php
/**
 * Customizer: the content that changes, without touching the brand.
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function yarmside_customize_register( $wp_customize ) {

	// ---- Hero -----------------------------------------------------------.
	$wp_customize->add_section( 'yarmside_hero', array(
		'title'    => __( 'Homepage hero', 'yarmside' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'yarmside_hero_heading', array(
		'default'           => __( "We manage homes the way we'd want our own managed.", 'yarmside' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'yarmside_hero_heading', array(
		'label'   => __( 'Heading', 'yarmside' ),
		'section' => 'yarmside_hero',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'yarmside_hero_text', array(
		'default'           => __( 'A small, unhurried lettings practice based on Yarm High Street, working with landlords and renters across the Tees Valley and the North East.', 'yarmside' ),
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'yarmside_hero_text', array(
		'label'   => __( 'Supporting line', 'yarmside' ),
		'section' => 'yarmside_hero',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'yarmside_hero_image', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'yarmside_hero_image', array(
		'label'       => __( 'Hero photograph', 'yarmside' ),
		'description' => __( 'A wide photograph of a street or home. The headline sits over the lower left, so keep that area calm.', 'yarmside' ),
		'section'     => 'yarmside_hero',
		'mime_type'   => 'image',
	) ) );

	// ---- Testimonial ------------------------------------------------------.
	$wp_customize->add_section( 'yarmside_testimonial', array(
		'title'    => __( 'Homepage testimonial', 'yarmside' ),
		'priority' => 31,
	) );

	$wp_customize->add_setting( 'yarmside_testimonial_quote', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'yarmside_testimonial_quote', array(
		'label'       => __( 'Quote', 'yarmside' ),
		'description' => __( 'Leave empty to hide the section.', 'yarmside' ),
		'section'     => 'yarmside_testimonial',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'yarmside_testimonial_cite', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'yarmside_testimonial_cite', array(
		'label'   => __( 'Attribution', 'yarmside' ),
		'section' => 'yarmside_testimonial',
		'type'    => 'text',
	) );

	// ---- Contact details ---------------------------------------------------.
	$wp_customize->add_section( 'yarmside_contact', array(
		'title'    => __( 'Contact details', 'yarmside' ),
		'priority' => 32,
	) );

	$contact_fields = array(
		'yarmside_contact_email'   => array( __( 'Enquiries email', 'yarmside' ), 'sanitize_email', 'enquiries@yarmside.co.uk' ),
		'yarmside_contact_phone'   => array( __( 'Phone number', 'yarmside' ), 'sanitize_text_field', '' ),
		'yarmside_contact_address' => array( __( 'Office address', 'yarmside' ), 'sanitize_textarea_field', "High Street, Yarm\nStockton-on-Tees" ),
		'yarmside_contact_hours'   => array( __( 'Opening hours', 'yarmside' ), 'sanitize_text_field', '' ),
	);

	foreach ( $contact_fields as $id => $field ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $field[2],
			'sanitize_callback' => $field[1],
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $field[0],
			'section' => 'yarmside_contact',
			'type'    => 'yarmside_contact_address' === $id ? 'textarea' : 'text',
		) );
	}

	// ---- Footer -------------------------------------------------------------.
	$wp_customize->add_section( 'yarmside_footer', array(
		'title'    => __( 'Footer', 'yarmside' ),
		'priority' => 33,
	) );

	$wp_customize->add_setting( 'yarmside_footer_strap', array(
		'default'           => __( 'A specialist lettings and management practice based on Yarm High Street, working with buy to let landlords across the Tees Valley.', 'yarmside' ),
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'yarmside_footer_strap', array(
		'label'   => __( 'Footer description', 'yarmside' ),
		'section' => 'yarmside_footer',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'yarmside_footer_legal', array(
		'default'           => __( 'Registered in England and Wales. Company number to be added.', 'yarmside' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'yarmside_footer_legal', array(
		'label'   => __( 'Legal line', 'yarmside' ),
		'section' => 'yarmside_footer',
		'type'    => 'text',
	) );
}
add_action( 'customize_register', 'yarmside_customize_register' );
