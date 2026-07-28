<?php
/**
 * Customizer: brand colours, contact details, homepage copy, footer.
 *
 * Everything the design left as "placeholder" is editable here.
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function yarmside_customize_register( $wp_customize ) {

	/* ---- Panel ------------------------------------------------------- */

	$wp_customize->add_panel(
		'yarmside',
		array(
			'title'    => __( 'Yarmside settings', 'yarmside' ),
			'priority' => 25,
		)
	);

	/* ---- Brand colours ------------------------------------------------ */

	$wp_customize->add_section(
		'yarmside_brand',
		array(
			'title' => __( 'Brand colours', 'yarmside' ),
			'panel' => 'yarmside',
			'description' => __( 'Every tint on the site is derived from these four colours.', 'yarmside' ),
		)
	);

	$colors = array(
		'yarmside_color_orange'      => array( __( 'Orange (accent)', 'yarmside' ), '#d8651e' ),
		'yarmside_color_dark'        => array( __( 'Dark (ink and panels)', 'yarmside' ), '#141414' ),
		'yarmside_color_cream'       => array( __( 'Cream (background)', 'yarmside' ), '#f5f1ea' ),
		'yarmside_color_grey_orange' => array( __( 'Grey orange (muted)', 'yarmside' ), '#8e867a' ),
	);

	foreach ( $colors as $id => $args ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $args[1],
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$id,
				array(
					'label'   => $args[0],
					'section' => 'yarmside_brand',
				)
			)
		);
	}

	/* ---- Contact details ---------------------------------------------- */

	$wp_customize->add_section(
		'yarmside_contact',
		array(
			'title' => __( 'Contact details', 'yarmside' ),
			'panel' => 'yarmside',
			'description' => __( 'Used in the footer, the contact page, structured data and the enquiry form.', 'yarmside' ),
		)
	);

	$contact_fields = array(
		'yarmside_contact_email'    => array( __( 'Email address', 'yarmside' ), 'enquiries@yarmside.co.uk', 'sanitize_email' ),
		'yarmside_contact_phone'    => array( __( 'Phone number', 'yarmside' ), '', 'sanitize_text_field' ),
		'yarmside_contact_street'   => array( __( 'Street address', 'yarmside' ), 'High Street', 'sanitize_text_field' ),
		'yarmside_contact_locality' => array( __( 'Town', 'yarmside' ), 'Yarm', 'sanitize_text_field' ),
		'yarmside_contact_region'   => array( __( 'Region', 'yarmside' ), 'Stockton-on-Tees', 'sanitize_text_field' ),
		'yarmside_contact_hours'    => array( __( 'Opening hours', 'yarmside' ), '', 'sanitize_text_field' ),
	);

	foreach ( $contact_fields as $id => $args ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $args[1],
				'sanitize_callback' => $args[2],
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $args[0],
				'section' => 'yarmside_contact',
				'type'    => 'text',
			)
		);
	}

	/* ---- Homepage ------------------------------------------------------ */

	$wp_customize->add_section(
		'yarmside_home',
		array(
			'title' => __( 'Homepage', 'yarmside' ),
			'panel' => 'yarmside',
		)
	);

	$wp_customize->add_setting(
		'yarmside_hero_image',
		array(
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'yarmside_hero_image',
			array(
				'label'       => __( 'Hero photograph', 'yarmside' ),
				'description' => __( 'A wide photograph, ideally 1920px or larger. Shown full-bleed behind the headline.', 'yarmside' ),
				'section'     => 'yarmside_home',
				'mime_type'   => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'yarmside_process_image',
		array(
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'yarmside_process_image',
			array(
				'label'       => __( 'Process photograph', 'yarmside' ),
				'description' => __( 'Shown to the left of the "From valuation to move-in" stages. Leave empty for stages only.', 'yarmside' ),
				'section'     => 'yarmside_home',
				'mime_type'   => 'image',
			)
		)
	);

	$home_fields = array(
		'yarmside_hero_heading'      => array( __( 'Hero heading', 'yarmside' ), __( 'We manage homes the way we\'d want our own managed.', 'yarmside' ) ),
		'yarmside_hero_lede'         => array( __( 'Hero introduction', 'yarmside' ), __( 'A small, unhurried lettings practice based on Yarm High Street, working with landlords and renters across the Tees Valley and the North East.', 'yarmside' ) ),
		'yarmside_hero_areas'        => array( __( '"Where we work" line', 'yarmside' ), __( 'Yarm · Tees Valley · North East', 'yarmside' ) ),
		'yarmside_listings_heading'  => array( __( 'Listings section heading', 'yarmside' ), __( 'A small number of homes, each one properly described.', 'yarmside' ) ),
		'yarmside_listings_lede'     => array( __( 'Listings section introduction', 'yarmside' ), __( 'We visit, photograph and describe every home ourselves. We\'d rather manage a modest portfolio well than a large one carelessly, so this list is short by design.', 'yarmside' ) ),
		'yarmside_testimonial_quote' => array( __( 'Testimonial quote', 'yarmside' ), '' ),
		'yarmside_testimonial_cite'  => array( __( 'Testimonial attribution', 'yarmside' ), '' ),
		'yarmside_journal_heading'   => array( __( 'Journal section heading', 'yarmside' ), __( 'Plain-English guidance, updated as things change.', 'yarmside' ) ),
		'yarmside_journal_lede'      => array( __( 'Journal section introduction', 'yarmside' ), __( 'Regulation, market conditions and the practical business of letting a home in the North East, explained without jargon.', 'yarmside' ) ),
		'yarmside_cta_heading'       => array( __( 'Call-to-action heading', 'yarmside' ), __( 'Considering a let? Let\'s begin with a conversation.', 'yarmside' ) ),
		'yarmside_cta_lede'          => array( __( 'Call-to-action text', 'yarmside' ), __( 'Request a free, no obligation valuation. We\'ll visit your property, give you an honest read of the market, and explain exactly how we\'d manage it, with no pressure to instruct.', 'yarmside' ) ),
	);

	foreach ( $home_fields as $id => $args ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $args[1],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $args[0],
				'section' => 'yarmside_home',
				'type'    => in_array( $id, array( 'yarmside_hero_lede', 'yarmside_listings_lede', 'yarmside_testimonial_quote', 'yarmside_journal_lede', 'yarmside_cta_lede' ), true ) ? 'textarea' : 'text',
			)
		);
	}

	/* ---- Footer --------------------------------------------------------- */

	$wp_customize->add_section(
		'yarmside_footer',
		array(
			'title' => __( 'Footer', 'yarmside' ),
			'panel' => 'yarmside',
		)
	);

	$footer_fields = array(
		'yarmside_footer_blurb' => array(
			__( 'Footer description', 'yarmside' ),
			__( 'A specialist lettings and management practice based on Yarm High Street, working with buy to let landlords across the Tees Valley.', 'yarmside' ),
			'textarea',
		),
		'yarmside_footer_legal' => array(
			__( 'Legal line', 'yarmside' ),
			__( 'Registered in England and Wales. Company number to be added.', 'yarmside' ),
			'text',
		),
	);

	foreach ( $footer_fields as $id => $args ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $args[1],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $args[0],
				'section' => 'yarmside_footer',
				'type'    => $args[2],
			)
		);
	}
}
add_action( 'customize_register', 'yarmside_customize_register' );
