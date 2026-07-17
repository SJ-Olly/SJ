<?php
/**
 * A single property: gallery, description, particulars, enquiry card.
 *
 * @package Yarmside
 */

get_header();

while ( have_posts() ) :
	the_post();

	$yarmside_id     = get_the_ID();
	$yarmside_status = yarmside_property_status( $yarmside_id );
	$yarmside_price  = get_post_meta( $yarmside_id, '_yarmside_price', true );

	// Gallery: featured image first, then the extra photos.
	$yarmside_gallery = array();
	if ( has_post_thumbnail() ) {
		$yarmside_gallery[] = get_post_thumbnail_id();
	}
	$yarmside_extra = get_post_meta( $yarmside_id, '_yarmside_gallery', true );
	foreach ( array_filter( array_map( 'absint', explode( ',', (string) $yarmside_extra ) ) ) as $yarmside_img ) {
		if ( ! in_array( $yarmside_img, $yarmside_gallery, true ) ) {
			$yarmside_gallery[] = $yarmside_img;
		}
	}
	?>

	<div class="page-head">
		<div class="wrap page-head__inner">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'yarmside' ); ?>">
				<a href="<?php echo esc_url( yarmside_properties_url() ); ?>"><?php esc_html_e( 'Homes to let', 'yarmside' ); ?></a>
				<span aria-hidden="true">/</span>
				<span><?php the_title(); ?></span>
			</nav>
			<h1 class="h1"><?php the_title(); ?></h1>
			<?php yarmside_property_dimline( $yarmside_id, '', true ); ?>
		</div>
	</div>

	<section class="section--tight">
		<div class="wrap">

			<?php if ( $yarmside_gallery ) : ?>
				<div class="gallery <?php echo count( $yarmside_gallery ) > 1 ? 'gallery--multi' : ''; ?>">
					<?php foreach ( $yarmside_gallery as $yarmside_i => $yarmside_img ) : ?>
						<div class="frame">
							<?php
							echo wp_get_attachment_image(
								$yarmside_img,
								0 === $yarmside_i ? 'yarmside-bay' : 'yarmside-card',
								false,
								0 === $yarmside_i ? array( 'fetchpriority' => 'high' ) : array( 'loading' => 'lazy' )
							);
							?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="detail-grid">

				<div class="detail-body">
					<?php the_content(); ?>

					<?php
					// The particulars: rent + every completed meta field.
					$yarmside_particulars = array();
					if ( $yarmside_price ) {
						$yarmside_particulars[] = array( __( 'Rent', 'yarmside' ), yarmside_format_price( $yarmside_price ), true );
					}
					$yarmside_spec_fields = array(
						'_yarmside_deposit'        => __( 'Deposit', 'yarmside' ),
						'_yarmside_available_from' => __( 'Available', 'yarmside' ),
						'_yarmside_furnishing'     => __( 'Furnishing', 'yarmside' ),
						'_yarmside_council_tax'    => __( 'Council tax', 'yarmside' ),
						'_yarmside_epc'            => __( 'EPC', 'yarmside' ),
						'_yarmside_heating'        => __( 'Heating', 'yarmside' ),
						'_yarmside_parking'        => __( 'Parking', 'yarmside' ),
						'_yarmside_pets'           => __( 'Pets', 'yarmside' ),
					);
					foreach ( $yarmside_spec_fields as $yarmside_key => $yarmside_label ) {
						$yarmside_value = get_post_meta( $yarmside_id, $yarmside_key, true );
						if ( '' !== $yarmside_value ) {
							$yarmside_particulars[] = array( $yarmside_label, $yarmside_value, false );
						}
					}

					if ( $yarmside_particulars ) :
						?>
						<h2 class="h3" style="margin-top: var(--space-3);"><?php esc_html_e( 'The particulars', 'yarmside' ); ?></h2>
						<div class="spec-list">
							<?php foreach ( $yarmside_particulars as $yarmside_row ) : ?>
								<p class="dim-line">
									<span class="dim-line__label"><?php echo esc_html( $yarmside_row[0] ); ?></span>
									<span class="dim-line__rule"></span>
									<span class="dim-line__value<?php echo $yarmside_row[2] ? ' tabular' : ''; ?>"><?php echo esc_html( $yarmside_row[1] ); ?></span>
								</p>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<p class="form__note"><?php esc_html_e( 'Measurements are approximate and particulars are prepared in good faith. They don\'t form part of any contract, and we\'d always rather you saw the house in person.', 'yarmside' ); ?></p>
				</div>

				<aside class="aside-card" aria-label="<?php esc_attr_e( 'Enquire about this property', 'yarmside' ); ?>">
					<span class="status <?php echo esc_attr( $yarmside_status[1] ); ?>"><?php echo esc_html( $yarmside_status[0] ); ?></span>
					<?php if ( $yarmside_price ) : ?>
						<p class="aside-card__price tabular"><?php echo esc_html( yarmside_format_price( $yarmside_price ) ); ?></p>
					<?php endif; ?>
					<p class="aside-card__note"><?php esc_html_e( 'Viewings are accompanied, usually within two working days of asking. Evenings and Saturday mornings are fine.', 'yarmside' ); ?></p>
					<div class="btn-row">
						<a class="btn btn--primary" href="<?php echo esc_url( yarmside_contact_url() ); ?>"><?php esc_html_e( 'Arrange a viewing', 'yarmside' ); ?></a>
					</div>
					<p class="aside-card__note"><?php esc_html_e( 'Or call the office on the High Street. We answer the phone ourselves during opening hours.', 'yarmside' ); ?></p>
				</aside>

			</div>
		</div>
	</section>

	<?php
	yarmside_cta(
		array(
			'heading' => __( 'Not quite right?', 'yarmside' ),
			'lede'    => __( 'Our list is short by design, but it changes often. Tell us what you\'re looking for and we\'ll get in touch when a suitable home is coming up, usually before it\'s listed anywhere else.', 'yarmside' ),
			'buttons' => array(
				array( __( 'Register as a tenant', 'yarmside' ), yarmside_contact_url(), 'btn--primary' ),
				array( __( 'Back to all homes', 'yarmside' ), yarmside_properties_url(), 'btn--ghost-light' ),
			),
		)
	);

endwhile;

get_footer();
