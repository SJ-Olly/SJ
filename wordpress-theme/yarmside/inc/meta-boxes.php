<?php
/**
 * Property meta boxes: particulars, status and photo gallery.
 *
 * No plugin dependencies — plain WordPress meta boxes.
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The text fields shown under "The particulars" on a property page.
 *
 * @return array key => [label, hint].
 */
function yarmside_property_fields() {
	return array(
		'_yarmside_subtype'        => array( __( 'Property description', 'yarmside' ), __( 'e.g. Detached house, Ground floor flat', 'yarmside' ) ),
		'_yarmside_price'          => array( __( 'Rent (pcm, numbers only)', 'yarmside' ), __( 'e.g. 1395', 'yarmside' ) ),
		'_yarmside_beds'           => array( __( 'Bedrooms', 'yarmside' ), '' ),
		'_yarmside_baths'          => array( __( 'Bathrooms', 'yarmside' ), '' ),
		'_yarmside_sqft'           => array( __( 'Floor area (sq ft)', 'yarmside' ), __( 'e.g. 1450', 'yarmside' ) ),
		'_yarmside_deposit'        => array( __( 'Deposit', 'yarmside' ), __( 'e.g. £1,610 (five weeks\' rent)', 'yarmside' ) ),
		'_yarmside_available_from' => array( __( 'Available from', 'yarmside' ), __( 'e.g. 1 September 2026', 'yarmside' ) ),
		'_yarmside_furnishing'     => array( __( 'Furnishing', 'yarmside' ), __( 'e.g. Unfurnished', 'yarmside' ) ),
		'_yarmside_council_tax'    => array( __( 'Council tax', 'yarmside' ), __( 'e.g. Band D, Stockton-on-Tees', 'yarmside' ) ),
		'_yarmside_epc'            => array( __( 'EPC', 'yarmside' ), __( 'e.g. Rating C', 'yarmside' ) ),
		'_yarmside_heating'        => array( __( 'Heating', 'yarmside' ), __( 'e.g. Gas central heating', 'yarmside' ) ),
		'_yarmside_parking'        => array( __( 'Parking', 'yarmside' ), __( 'e.g. Drive for two cars', 'yarmside' ) ),
		'_yarmside_pets'           => array( __( 'Pets', 'yarmside' ), __( 'e.g. Considered, by arrangement', 'yarmside' ) ),
	);
}

/**
 * Register the meta boxes.
 */
function yarmside_add_meta_boxes() {
	add_meta_box(
		'yarmside-particulars',
		__( 'The particulars', 'yarmside' ),
		'yarmside_particulars_meta_box',
		'property',
		'normal',
		'high'
	);
	add_meta_box(
		'yarmside-gallery',
		__( 'Photo gallery', 'yarmside' ),
		'yarmside_gallery_meta_box',
		'property',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'yarmside_add_meta_boxes' );

/**
 * Particulars meta box UI.
 *
 * @param WP_Post $post Current post.
 */
function yarmside_particulars_meta_box( $post ) {
	wp_nonce_field( 'yarmside_save_property', 'yarmside_property_nonce' );

	$status   = get_post_meta( $post->ID, '_yarmside_status', true );
	$featured = get_post_meta( $post->ID, '_yarmside_featured', true );
	if ( '' === $status ) {
		$status = 'available';
	}
	?>
	<style>
		.yarmside-fields { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px; margin-top: 8px; }
		.yarmside-fields label { display: block; font-weight: 600; margin-bottom: 3px; }
		.yarmside-fields .description { margin: 2px 0 0; }
		.yarmside-fields input, .yarmside-fields select { width: 100%; }
		.yarmside-flags { margin-top: 14px; padding-top: 12px; border-top: 1px solid #dcdcde; }
	</style>
	<div class="yarmside-fields">
		<p>
			<label for="yarmside_status"><?php esc_html_e( 'Lettings status', 'yarmside' ); ?></label>
			<select id="yarmside_status" name="yarmside_meta[_yarmside_status]">
				<?php foreach ( yarmside_property_statuses() as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $status, $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php foreach ( yarmside_property_fields() as $key => $field ) : ?>
			<p>
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[0] ); ?></label>
				<input type="text"
					id="<?php echo esc_attr( $key ); ?>"
					name="yarmside_meta[<?php echo esc_attr( $key ); ?>]"
					value="<?php echo esc_attr( get_post_meta( $post->ID, $key, true ) ); ?>">
				<?php if ( $field[1] ) : ?>
					<span class="description"><?php echo esc_html( $field[1] ); ?></span>
				<?php endif; ?>
			</p>
		<?php endforeach; ?>
	</div>
	<p class="yarmside-flags">
		<label>
			<input type="checkbox" name="yarmside_meta[_yarmside_featured]" value="1" <?php checked( $featured, '1' ); ?>>
			<?php esc_html_e( 'Feature this property on the homepage', 'yarmside' ); ?>
		</label>
	</p>
	<p class="description">
		<?php esc_html_e( 'The main photo is the Featured image (right-hand panel). The description written in the editor above appears on the property page; the excerpt appears on cards.', 'yarmside' ); ?>
	</p>
	<?php
}

/**
 * Gallery meta box UI — extra photos beyond the featured image.
 *
 * @param WP_Post $post Current post.
 */
function yarmside_gallery_meta_box( $post ) {
	$ids = get_post_meta( $post->ID, '_yarmside_gallery', true );
	?>
	<div id="yarmside-gallery-box">
		<input type="hidden" id="yarmside_gallery" name="yarmside_meta[_yarmside_gallery]" value="<?php echo esc_attr( $ids ); ?>">
		<div id="yarmside-gallery-preview" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px;">
			<?php
			foreach ( array_filter( array_map( 'absint', explode( ',', (string) $ids ) ) ) as $id ) {
				echo wp_get_attachment_image( $id, array( 80, 60 ), false, array( 'style' => 'width:80px;height:60px;object-fit:cover;' ) );
			}
			?>
		</div>
		<button type="button" class="button" id="yarmside-gallery-add"><?php esc_html_e( 'Choose photos', 'yarmside' ); ?></button>
		<button type="button" class="button" id="yarmside-gallery-clear"><?php esc_html_e( 'Clear', 'yarmside' ); ?></button>
		<p class="description"><?php esc_html_e( 'Shown in the gallery grid beneath the featured image, in this order.', 'yarmside' ); ?></p>
	</div>
	<?php
}

/**
 * Save property meta.
 *
 * @param int $post_id Post ID.
 */
function yarmside_save_property_meta( $post_id ) {
	if ( ! isset( $_POST['yarmside_property_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( $_POST['yarmside_property_nonce'] ), 'yarmside_save_property' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$input = isset( $_POST['yarmside_meta'] ) ? wp_unslash( (array) $_POST['yarmside_meta'] ) : array();

	// Text particulars.
	foreach ( array_keys( yarmside_property_fields() ) as $key ) {
		$value = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}

	// Status: whitelist.
	$status = isset( $input['_yarmside_status'] ) ? sanitize_key( $input['_yarmside_status'] ) : 'available';
	if ( ! array_key_exists( $status, yarmside_property_statuses() ) ) {
		$status = 'available';
	}
	update_post_meta( $post_id, '_yarmside_status', $status );

	// Featured flag.
	if ( ! empty( $input['_yarmside_featured'] ) ) {
		update_post_meta( $post_id, '_yarmside_featured', '1' );
	} else {
		delete_post_meta( $post_id, '_yarmside_featured' );
	}

	// Gallery: comma-separated attachment IDs.
	$gallery = isset( $input['_yarmside_gallery'] ) ? $input['_yarmside_gallery'] : '';
	$ids     = array_filter( array_map( 'absint', explode( ',', $gallery ) ) );
	if ( $ids ) {
		update_post_meta( $post_id, '_yarmside_gallery', implode( ',', $ids ) );
	} else {
		delete_post_meta( $post_id, '_yarmside_gallery' );
	}
}
add_action( 'save_post_property', 'yarmside_save_property_meta' );
