<?php
/**
 * First-run setup: create the design's pages, menus and example content.
 *
 * Runs once on theme activation. Never overwrites existing content and
 * never runs twice (guarded by an option).
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Seed everything on first activation.
 */
function yarmside_seed_site() {
	if ( get_option( 'yarmside_seeded' ) ) {
		return;
	}
	update_option( 'yarmside_seeded', 1 );

	// Make sure the CPT + patterns registered on this request.
	yarmside_register_post_types();
	yarmside_default_property_types();

	$pages = yarmside_seed_pages();
	yarmside_seed_front( $pages );
	yarmside_seed_menus( $pages );
	yarmside_seed_properties();
	yarmside_seed_journal();

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'yarmside_seed_site', 20 );

/**
 * Create the static pages from the design.
 *
 * @return array slug => post ID.
 */
function yarmside_seed_pages() {
	$definitions = array(
		'home'      => array(
			'title'   => __( 'Home', 'yarmside' ),
			'excerpt' => '',
			'content' => '',
		),
		'landlords' => array(
			'title'   => __( 'Landlords', 'yarmside' ),
			'excerpt' => __( 'We\'re buy to let specialists. Most of our landlords own one or two properties and want them let well, kept compliant and paid on time, without having to think about it every week. That\'s the job, and we do all of it from the office on the High Street.', 'yarmside' ),
			'content' => yarmside_pattern_service_tiers()
				. yarmside_pattern_testimonial()
				. yarmside_pattern_process()
				. '<!-- wp:group {"className":"section-head","layout":{"type":"default"}} -->
<div class="wp-block-group section-head"><!-- wp:group {"className":"section-head__titles","layout":{"type":"default"}} -->
<div class="wp-block-group section-head__titles"><!-- wp:heading {"className":"h2"} --><h2 class="wp-block-heading h2">' . __( 'The rules keep changing. Keeping up is our job, not yours.', 'yarmside' ) . '</h2><!-- /wp:heading --></div>
<!-- /wp:group -->
<!-- wp:paragraph {"className":"lede"} --><p class="lede">' . __( 'Private renting is one of the most regulated things an ordinary person can do with their money. For managed properties we track every requirement and renew it before it lapses.', 'yarmside' ) . '</p><!-- /wp:paragraph --></div>
<!-- /wp:group -->'
				. yarmside_pattern_spec_list()
				. yarmside_pattern_cta(),
		),
		'about'     => array(
			'title'   => __( 'About', 'yarmside' ),
			'excerpt' => __( 'Yarmside is a small, independent practice on Yarm High Street. We let and manage homes across the Tees Valley for landlords who want their property treated like the serious investment it is.', 'yarmside' ),
			'content' => yarmside_pattern_prose_image()
				. yarmside_pattern_about_rules()
				. yarmside_pattern_team()
				. yarmside_pattern_cta(),
		),
		'contact'   => array(
			'title'   => __( 'Contact', 'yarmside' ),
			'excerpt' => __( 'Whether you\'re a landlord weighing up a valuation, a tenant looking for a home, or you just want a straight answer to a lettings question, get in touch. We answer the phone ourselves.', 'yarmside' ),
			'content' => yarmside_pattern_contact(),
		),
		'journal'   => array(
			'title'   => __( 'Journal', 'yarmside' ),
			'excerpt' => __( 'Plain-English writing on changing regulation, market conditions and the practical business of letting a home in the North East. No jargon, no scaremongering, and we only publish when there\'s something worth saying.', 'yarmside' ),
			'content' => '',
		),
	);

	$ids = array();
	foreach ( $definitions as $slug => $page ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}
		$ids[ $slug ] = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $page['title'],
				'post_excerpt' => $page['excerpt'],
				'post_content' => $page['content'],
			)
		);
	}

	return $ids;
}

/**
 * The about page's "Four rules" band (orange, like the process band).
 */
function yarmside_pattern_about_rules() {
	$stages = yarmside_pattern_stage( __( 'Rule one', 'yarmside' ), __( 'See it before you say it', 'yarmside' ), __( 'No home is described, valued or listed by someone who hasn\'t stood in it. Templates don\'t visit properties, we do.', 'yarmside' ) )
		. yarmside_pattern_stage( __( 'Rule two', 'yarmside' ), __( 'Bad news travels fast', 'yarmside' ), __( 'If rent is late, a boiler has failed or a tenancy is wobbling, you hear it from us the day we know, with a plan attached.', 'yarmside' ) )
		. yarmside_pattern_stage( __( 'Rule three', 'yarmside' ), __( 'Tenants are clients too', 'yarmside' ), __( 'A well-treated tenant stays longer, pays reliably and looks after the house. Respect for tenants isn\'t a cost, it\'s the strategy.', 'yarmside' ) )
		. yarmside_pattern_stage( __( 'Rule four', 'yarmside' ), __( 'The statement is sacred', 'yarmside' ), __( 'Same day, every month, one page you can actually read. If you\'ve ever chased an agent for your own money, you know why this matters.', 'yarmside' ) );

	return '<!-- wp:group {"className":"alignfull section process-section","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull section process-section"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:group {"className":"section-head","layout":{"type":"default"}} -->
<div class="wp-block-group section-head"><!-- wp:group {"className":"section-head__titles","layout":{"type":"default"}} -->
<div class="wp-block-group section-head__titles"><!-- wp:heading {"className":"h2"} --><h2 class="wp-block-heading h2">' . __( 'Four rules we don\'t bend.', 'yarmside' ) . '</h2><!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- wp:group {"className":"stages","layout":{"type":"default"}} -->
<div class="wp-block-group stages">' . $stages . '</div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->';
}

/**
 * Point WordPress at the right front and posts pages.
 *
 * @param array $pages slug => ID map.
 */
function yarmside_seed_front( $pages ) {
	if ( ! empty( $pages['home'] ) && ! empty( $pages['journal'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pages['home'] );
		update_option( 'page_for_posts', $pages['journal'] );
	}
}

/**
 * Build the primary and footer menus from the design.
 *
 * @param array $pages slug => ID map.
 */
function yarmside_seed_menus( $pages ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );

	// Primary: Properties, Landlords, Journal, About, Contact.
	if ( empty( $locations['primary'] ) && ! wp_get_nav_menu_object( 'Primary' ) ) {
		$menu_id = wp_create_nav_menu( 'Primary' );
		if ( ! is_wp_error( $menu_id ) ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => __( 'Properties', 'yarmside' ),
					'menu-item-url'    => get_post_type_archive_link( 'property' ),
					'menu-item-type'   => 'custom',
					'menu-item-status' => 'publish',
				)
			);
			foreach ( array( 'landlords', 'journal', 'about', 'contact' ) as $slug ) {
				if ( ! empty( $pages[ $slug ] ) ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-object-id' => $pages[ $slug ],
							'menu-item-object'    => 'page',
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}
			$locations['primary'] = $menu_id;
		}
	}

	// Footer columns.
	$footer_menus = array(
		'footer-1' => array(
			'name'  => 'Footer — Lettings',
			'items' => array(
				array( __( 'Homes to let', 'yarmside' ), get_post_type_archive_link( 'property' ) ),
				array( __( 'Houses', 'yarmside' ), get_term_link( 'house', 'property_type' ) ),
				array( __( 'Flats', 'yarmside' ), get_term_link( 'flat', 'property_type' ) ),
			),
		),
		'footer-2' => array(
			'name'  => 'Footer — Landlords',
			'items' => array(
				array( __( 'Services', 'yarmside' ), ! empty( $pages['landlords'] ) ? get_permalink( $pages['landlords'] ) : '' ),
				array( __( 'Request a valuation', 'yarmside' ), ! empty( $pages['contact'] ) ? get_permalink( $pages['contact'] ) : '' ),
			),
		),
		'footer-3' => array(
			'name'  => 'Footer — Practice',
			'items' => array(
				array( __( 'About', 'yarmside' ), ! empty( $pages['about'] ) ? get_permalink( $pages['about'] ) : '' ),
				array( __( 'Journal', 'yarmside' ), ! empty( $pages['journal'] ) ? get_permalink( $pages['journal'] ) : '' ),
				array( __( 'Contact', 'yarmside' ), ! empty( $pages['contact'] ) ? get_permalink( $pages['contact'] ) : '' ),
			),
		),
	);

	foreach ( $footer_menus as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) || wp_get_nav_menu_object( $menu['name'] ) ) {
			continue;
		}
		$menu_id = wp_create_nav_menu( $menu['name'] );
		if ( is_wp_error( $menu_id ) ) {
			continue;
		}
		foreach ( $menu['items'] as $item ) {
			if ( empty( $item[1] ) || is_wp_error( $item[1] ) ) {
				continue;
			}
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => $item[0],
					'menu-item-url'    => $item[1],
					'menu-item-type'   => 'custom',
					'menu-item-status' => 'publish',
				)
			);
		}
		$locations[ $location ] = $menu_id;
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Example properties from the design, clearly placeholders.
 * Photography is intentionally left empty — the tinted frame shows instead.
 */
function yarmside_seed_properties() {
	$existing = get_posts(
		array(
			'post_type'   => 'property',
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);
	if ( $existing ) {
		return;
	}

	$properties = array(
		array(
			'title'   => '4 Leven Bank, Yarm',
			'type'    => 'house',
			'excerpt' => __( 'A family house on one of Yarm\'s quieter lanes, a short walk from the High Street and the school run. Recently redecorated throughout, with a south-facing garden and off-street parking for two cars.', 'yarmside' ),
			'content' => '<!-- wp:heading {"className":"h2"} --><h2 class="wp-block-heading h2">' . __( 'A family house on one of Yarm\'s quieter lanes.', 'yarmside' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . __( 'Leven Bank sits a short walk south of the High Street, close to the primary school and the riverside path. This is a house for a family who want to be near the middle of Yarm without living on top of it.', 'yarmside' ) . '</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>' . __( 'Downstairs there\'s a good-sized sitting room to the front, a separate dining room, and a kitchen that opens onto the garden. Upstairs are four bedrooms, three of them doubles, a family bathroom and an en suite to the main bedroom. The whole house was redecorated this spring, and the boiler was replaced in 2024.', 'yarmside' ) . '</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>' . __( 'Outside, the garden faces south and is mostly lawn, with a paved area by the kitchen door. There\'s off-street parking for two cars on the drive.', 'yarmside' ) . '</p><!-- /wp:paragraph -->',
			'meta'    => array(
				'_yarmside_subtype'        => __( 'Detached house', 'yarmside' ),
				'_yarmside_price'          => '1395',
				'_yarmside_beds'           => '4',
				'_yarmside_baths'          => '2',
				'_yarmside_sqft'           => '1450',
				'_yarmside_status'         => 'available',
				'_yarmside_featured'       => '1',
				'_yarmside_deposit'        => __( '£1,610 (five weeks\' rent)', 'yarmside' ),
				'_yarmside_available_from' => __( '1 September 2026', 'yarmside' ),
				'_yarmside_furnishing'     => __( 'Unfurnished', 'yarmside' ),
				'_yarmside_council_tax'    => __( 'Band D, Stockton-on-Tees', 'yarmside' ),
				'_yarmside_epc'            => __( 'Rating C', 'yarmside' ),
				'_yarmside_heating'        => __( 'Gas central heating, boiler fitted 2024', 'yarmside' ),
				'_yarmside_parking'        => __( 'Drive for two cars', 'yarmside' ),
				'_yarmside_pets'           => __( 'Considered, by arrangement', 'yarmside' ),
			),
		),
		array(
			'title'   => '12 Hawthorn Lane, Yarm',
			'type'    => 'house',
			'excerpt' => __( 'A neat two bedroom terrace within walking distance of the High Street.', 'yarmside' ),
			'meta'    => array(
				'_yarmside_subtype' => __( 'Terraced house', 'yarmside' ),
				'_yarmside_price'   => '900',
				'_yarmside_beds'    => '2',
				'_yarmside_baths'   => '1',
				'_yarmside_sqft'    => '680',
				'_yarmside_status'  => 'available',
			),
		),
		array(
			'title'   => '158 Marlow Crescent, Yarm',
			'type'    => 'house',
			'excerpt' => __( 'A three bedroom semi with a garage and a generous rear garden.', 'yarmside' ),
			'meta'    => array(
				'_yarmside_subtype' => __( 'Semi-detached house', 'yarmside' ),
				'_yarmside_price'   => '820',
				'_yarmside_beds'    => '3',
				'_yarmside_baths'   => '2',
				'_yarmside_sqft'    => '920',
				'_yarmside_status'  => 'agreed',
			),
		),
		array(
			'title'   => '6a Old Kiln Road, Yarm',
			'type'    => 'flat',
			'excerpt' => __( 'A bright ground floor flat with its own entrance and a small courtyard.', 'yarmside' ),
			'meta'    => array(
				'_yarmside_subtype' => __( 'Ground floor flat', 'yarmside' ),
				'_yarmside_price'   => '1050',
				'_yarmside_beds'    => '1',
				'_yarmside_baths'   => '1',
				'_yarmside_sqft'    => '400',
				'_yarmside_status'  => 'available',
			),
		),
		array(
			'title'   => 'Flat 3, The Old Granary, Stockton',
			'type'    => 'flat',
			'excerpt' => __( 'A first floor conversion flat with high ceilings and river views.', 'yarmside' ),
			'meta'    => array(
				'_yarmside_subtype' => __( 'First floor flat', 'yarmside' ),
				'_yarmside_price'   => '695',
				'_yarmside_beds'    => '2',
				'_yarmside_baths'   => '1',
				'_yarmside_sqft'    => '610',
				'_yarmside_status'  => 'available',
			),
		),
		array(
			'title'   => '27 Riverbank Terrace, Eaglescliffe',
			'type'    => 'house',
			'excerpt' => __( 'A two bedroom terrace close to the station, ideal for commuters.', 'yarmside' ),
			'meta'    => array(
				'_yarmside_subtype' => __( 'Terraced house', 'yarmside' ),
				'_yarmside_price'   => '750',
				'_yarmside_beds'    => '2',
				'_yarmside_baths'   => '1',
				'_yarmside_sqft'    => '720',
				'_yarmside_status'  => 'agreed',
			),
		),
	);

	foreach ( $properties as $property ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'property',
				'post_status'  => 'publish',
				'post_title'   => $property['title'],
				'post_excerpt' => $property['excerpt'],
				'post_content' => isset( $property['content'] ) ? $property['content'] : '',
			)
		);
		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}
		wp_set_object_terms( $post_id, $property['type'], 'property_type' );
		foreach ( $property['meta'] as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}
}

/**
 * Example journal articles from the design (headlines only, drafts of intent).
 */
function yarmside_seed_journal() {
	$existing = get_posts(
		array(
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);
	if ( $existing ) {
		return;
	}

	$body = '<!-- wp:paragraph --><p>' . __( 'This is an example article created by the Yarmside theme so the journal has the right shape from day one. Replace it with the real article, or delete it.', 'yarmside' ) . '</p><!-- /wp:paragraph -->';

	$articles = array(
		array( __( 'The Renters\' Rights Act: what it actually changes for North East landlords', 'yarmside' ), __( 'Legislation', 'yarmside' ) ),
		array( __( 'The Tees Valley rental market in 2026: a quieter, steadier picture', 'yarmside' ), __( 'Market', 'yarmside' ) ),
		array( __( 'Why smaller HMOs are drawing more landlord interest this year', 'yarmside' ), __( 'Investment', 'yarmside' ) ),
		array( __( 'What actually adds rental value in a kitchen, and what doesn\'t', 'yarmside' ), __( 'Practical', 'yarmside' ) ),
		array( __( 'Void periods: what an empty month really costs, and how to shorten it', 'yarmside' ), __( 'Practical', 'yarmside' ) ),
		array( __( 'Getting your lettings paperwork ready for self assessment, without the panic', 'yarmside' ), __( 'Tax', 'yarmside' ) ),
	);

	foreach ( $articles as $article ) {
		$category = wp_create_category( $article[1] );
		wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_title'    => $article[0],
				'post_content'  => $body,
				'post_category' => $category ? array( $category ) : array(),
			)
		);
	}
}
