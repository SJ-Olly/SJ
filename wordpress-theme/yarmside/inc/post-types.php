<?php
/**
 * Property custom post type and taxonomy.
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Property post type and the Property type taxonomy.
 */
function yarmside_register_post_types() {
	register_post_type(
		'property',
		array(
			'labels'       => array(
				'name'               => __( 'Properties', 'yarmside' ),
				'singular_name'      => __( 'Property', 'yarmside' ),
				'add_new'            => __( 'Add property', 'yarmside' ),
				'add_new_item'       => __( 'Add new property', 'yarmside' ),
				'edit_item'          => __( 'Edit property', 'yarmside' ),
				'new_item'           => __( 'New property', 'yarmside' ),
				'view_item'          => __( 'View property', 'yarmside' ),
				'view_items'         => __( 'View properties', 'yarmside' ),
				'search_items'       => __( 'Search properties', 'yarmside' ),
				'not_found'          => __( 'No properties found', 'yarmside' ),
				'not_found_in_trash' => __( 'No properties found in the bin', 'yarmside' ),
				'all_items'          => __( 'All properties', 'yarmside' ),
				'archives'           => __( 'Homes to let', 'yarmside' ),
			),
			'description'  => __( 'Homes to let, each one visited and described in person.', 'yarmside' ),
			'public'       => true,
			'has_archive'  => 'properties',
			'rewrite'      => array(
				'slug'       => 'property',
				'with_front' => false,
			),
			'menu_position' => 5,
			'menu_icon'    => 'dashicons-admin-home',
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'show_in_rest' => true,
		)
	);

	register_taxonomy(
		'property_type',
		'property',
		array(
			'labels'            => array(
				'name'          => __( 'Property types', 'yarmside' ),
				'singular_name' => __( 'Property type', 'yarmside' ),
				'search_items'  => __( 'Search property types', 'yarmside' ),
				'all_items'     => __( 'All property types', 'yarmside' ),
				'edit_item'     => __( 'Edit property type', 'yarmside' ),
				'update_item'   => __( 'Update property type', 'yarmside' ),
				'add_new_item'  => __( 'Add new property type', 'yarmside' ),
				'new_item_name' => __( 'New property type', 'yarmside' ),
				'menu_name'     => __( 'Property types', 'yarmside' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'properties/type',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'yarmside_register_post_types' );

/**
 * Default property types so the archive filters work out of the box.
 */
function yarmside_default_property_types() {
	foreach ( array(
		'house' => __( 'Houses', 'yarmside' ),
		'flat'  => __( 'Flats', 'yarmside' ),
	) as $slug => $name ) {
		if ( ! term_exists( $slug, 'property_type' ) ) {
			wp_insert_term( $name, 'property_type', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'after_switch_theme', 'yarmside_default_property_types' );

/**
 * The three lettings statuses used across the design.
 *
 * @return array slug => label.
 */
function yarmside_property_statuses() {
	return array(
		'available' => __( 'To let', 'yarmside' ),
		'agreed'    => __( 'Let agreed', 'yarmside' ),
		'let'       => __( 'Let', 'yarmside' ),
	);
}

/**
 * Useful columns on the property list screen.
 */
function yarmside_property_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['yarmside_price']  = __( 'Rent', 'yarmside' );
			$new['yarmside_beds']   = __( 'Beds', 'yarmside' );
			$new['yarmside_status'] = __( 'Status', 'yarmside' );
		}
	}
	return $new;
}
add_filter( 'manage_property_posts_columns', 'yarmside_property_columns' );

/**
 * Render the custom columns.
 */
function yarmside_property_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'yarmside_price':
			$price = get_post_meta( $post_id, '_yarmside_price', true );
			echo $price ? esc_html( yarmside_format_price( $price ) ) : '&mdash;';
			break;
		case 'yarmside_beds':
			$beds = get_post_meta( $post_id, '_yarmside_beds', true );
			echo $beds ? esc_html( $beds ) : '&mdash;';
			break;
		case 'yarmside_status':
			$statuses = yarmside_property_statuses();
			$status   = get_post_meta( $post_id, '_yarmside_status', true );
			echo isset( $statuses[ $status ] ) ? esc_html( $statuses[ $status ] ) : '&mdash;';
			break;
	}
}
add_action( 'manage_property_posts_custom_column', 'yarmside_property_column_content', 10, 2 );
