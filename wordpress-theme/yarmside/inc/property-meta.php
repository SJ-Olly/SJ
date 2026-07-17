<?php
/**
 * Property particulars meta box.
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fields shown on the property edit screen.
 */
function yarmside_property_fields() {
	return array(
		'type'   => array( 'label' => __( 'Property type', 'yarmside' ), 'placeholder' => __( 'Detached house', 'yarmside' ) ),
		'price'  => array( 'label' => __( 'Rent', 'yarmside' ), 'placeholder' => __( '£1,395 pcm', 'yarmside' ) ),
		'beds'   => array( 'label' => __( 'Bedrooms', 'yarmside' ), 'placeholder' => '4' ),
		'baths'  => array( 'label' => __( 'Bathrooms', 'yarmside' ), 'placeholder' => '2' ),
		'sqft'   => array( 'label' => __( 'Floor area (sq ft)', 'yarmside' ), 'placeholder' => '1,450' ),
	);
}

/**
 * Register the meta box.
 */
function yarmside_add_property_meta_box() {
	add_meta_box(
		'yarmside_property_details',
		__( 'Property particulars', 'yarmside' ),
		'yarmside_render_property_meta_box',
		'property',
		'side'
	);
}
add_action( 'add_meta_boxes', 'yarmside_add_property_meta_box' );

/**
 * Render fields.
 *
 * @param WP_Post $post Current post.
 */
function yarmside_render_property_meta_box( $post ) {
	wp_nonce_field( 'yarmside_property_meta', 'yarmside_property_meta_nonce' );

	foreach ( yarmside_property_fields() as $key => $field ) {
		$value = get_post_meta( $post->ID, '_yarmside_' . $key, true );
		printf(
			'<p><label for="yarmside_%1$s"><strong>%2$s</strong></label><br><input type="text" class="widefat" id="yarmside_%1$s" name="yarmside_%1$s" value="%3$s" placeholder="%4$s"></p>',
			esc_attr( $key ),
			esc_html( $field['label'] ),
			esc_attr( $value ),
			esc_attr( $field['placeholder'] )
		);
	}

	$status = get_post_meta( $post->ID, '_yarmside_status', true );
	?>
	<p>
		<label for="yarmside_status"><strong><?php esc_html_e( 'Status', 'yarmside' ); ?></strong></label><br>
		<select class="widefat" id="yarmside_status" name="yarmside_status">
			<option value="let" <?php selected( $status, 'let' ); ?>><?php esc_html_e( 'To let', 'yarmside' ); ?></option>
			<option value="agreed" <?php selected( $status, 'agreed' ); ?>><?php esc_html_e( 'Let agreed', 'yarmside' ); ?></option>
		</select>
	</p>
	<?php
}

/**
 * Save fields.
 *
 * @param int $post_id Post being saved.
 */
function yarmside_save_property_meta( $post_id ) {
	if ( ! isset( $_POST['yarmside_property_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['yarmside_property_meta_nonce'] ), 'yarmside_property_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( array_keys( yarmside_property_fields() ) as $key ) {
		if ( isset( $_POST[ 'yarmside_' . $key ] ) ) {
			update_post_meta( $post_id, '_yarmside_' . $key, sanitize_text_field( wp_unslash( $_POST[ 'yarmside_' . $key ] ) ) );
		}
	}

	if ( isset( $_POST['yarmside_status'] ) ) {
		$status = 'agreed' === $_POST['yarmside_status'] ? 'agreed' : 'let';
		update_post_meta( $post_id, '_yarmside_status', $status );
	}
}
add_action( 'save_post_property', 'yarmside_save_property_meta' );

/**
 * Render the bed/bath/sqft dimension line for a property.
 */
function yarmside_property_dim_line( $light = false ) {
	$beds  = yarmside_property_meta( 'beds' );
	$baths = yarmside_property_meta( 'baths' );
	$sqft  = yarmside_property_meta( 'sqft' );

	if ( ! $beds && ! $baths && ! $sqft ) {
		return;
	}
	?>
	<p class="dim-line<?php echo $light ? ' dim-line--light' : ''; ?>">
		<?php if ( $beds ) : ?>
			<span class="dim-line__label"><?php echo esc_html( sprintf( __( '%s bed', 'yarmside' ), $beds ) ); ?></span>
			<span class="dim-line__rule"></span>
		<?php endif; ?>
		<?php if ( $baths ) : ?>
			<span class="dim-line__label"><?php echo esc_html( sprintf( __( '%s bath', 'yarmside' ), $baths ) ); ?></span>
			<span class="dim-line__rule"></span>
		<?php endif; ?>
		<?php if ( $sqft ) : ?>
			<span class="dim-line__value"><?php echo esc_html( sprintf( __( '%s sq ft', 'yarmside' ), $sqft ) ); ?></span>
		<?php endif; ?>
	</p>
	<?php
}

/**
 * Render the status pill.
 */
function yarmside_property_status() {
	$status = yarmside_property_meta( 'status' );
	if ( 'agreed' === $status ) {
		printf( '<span class="status status--agreed">%s</span>', esc_html__( 'Let agreed', 'yarmside' ) );
	} else {
		printf( '<span class="status status--let">%s</span>', esc_html__( 'To let', 'yarmside' ) );
	}
}
