<?php
/**
 * Homepage: hero, featured property, latest homes, process,
 * testimonial, journal and CTA — all driven by real content
 * and the Customizer.
 *
 * @package Yarmside
 */

get_header();

$yarmside_hero_id = absint( get_theme_mod( 'yarmside_hero_image' ) );
?>

<!-- Hero -->
<section class="hero">
	<div class="hero__media">
		<?php
		if ( $yarmside_hero_id ) {
			echo wp_get_attachment_image(
				$yarmside_hero_id,
				'yarmside-hero',
				false,
				array(
					'fetchpriority' => 'high',
					'alt'           => get_post_meta( $yarmside_hero_id, '_wp_attachment_image_alt', true ),
				)
			);
		}
		?>
	</div>

	<div class="wrap hero__inner">
		<h1 class="h1"><?php echo esc_html( get_theme_mod( 'yarmside_hero_heading', __( 'We manage homes the way we\'d want our own managed.', 'yarmside' ) ) ); ?></h1>
		<p class="hero__lede"><?php echo esc_html( get_theme_mod( 'yarmside_hero_lede', __( 'A small, unhurried lettings practice based on Yarm High Street, working with landlords and renters across the Tees Valley and the North East.', 'yarmside' ) ) ); ?></p>
		<div class="btn-row">
			<a class="btn btn--primary" href="<?php echo esc_url( yarmside_contact_url() ); ?>"><?php esc_html_e( 'Request a valuation', 'yarmside' ); ?></a>
			<a class="btn btn--ghost-light" href="<?php echo esc_url( yarmside_properties_url() ); ?>"><?php esc_html_e( 'Browse homes to let', 'yarmside' ); ?></a>
		</div>
		<p class="dim-line dim-line--light hero__foot">
			<span class="dim-line__label"><?php esc_html_e( 'Where we work', 'yarmside' ); ?></span>
			<span class="dim-line__rule"></span>
			<span class="dim-line__value"><?php echo esc_html( get_theme_mod( 'yarmside_hero_areas', __( 'Yarm · Tees Valley · North East', 'yarmside' ) ) ); ?></span>
		</p>
	</div>
</section>

<!-- Selected homes -->
<section class="section listings">
	<div class="wrap">
		<div class="section-head">
			<div class="section-head__titles">
				<h2 class="h2"><?php echo esc_html( get_theme_mod( 'yarmside_listings_heading', __( 'A small number of homes, each one properly described.', 'yarmside' ) ) ); ?></h2>
			</div>
			<p class="lede"><?php echo esc_html( get_theme_mod( 'yarmside_listings_lede', __( 'We visit, photograph and describe every home ourselves. We\'d rather manage a modest portfolio well than a large one carelessly, so this list is short by design.', 'yarmside' ) ) ); ?></p>
		</div>

		<?php
		// Featured property: the newest one flagged as featured.
		$yarmside_featured = new WP_Query(
			array(
				'post_type'      => 'property',
				'posts_per_page' => 1,
				'meta_key'       => '_yarmside_featured',
				'meta_value'     => '1',
				'no_found_rows'  => true,
			)
		);

		$yarmside_featured_id = 0;

		if ( $yarmside_featured->have_posts() ) :
			while ( $yarmside_featured->have_posts() ) :
				$yarmside_featured->the_post();
				$yarmside_featured_id = get_the_ID();
				$yarmside_status      = yarmside_property_status( $yarmside_featured_id );
				$yarmside_price       = get_post_meta( $yarmside_featured_id, '_yarmside_price', true );
				?>
				<a class="featured" href="<?php the_permalink(); ?>">
					<div class="frame featured__art">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'yarmside-bay' ); ?>
						<?php endif; ?>
					</div>
					<div class="featured__body">
						<p class="featured__tag"><?php echo esc_html( __( 'Featured', 'yarmside' ) . ' · ' . yarmside_property_tag( $yarmside_featured_id ) ); ?></p>
						<p class="featured__title"><?php the_title(); ?></p>
						<?php yarmside_property_dimline( $yarmside_featured_id, 'light' ); ?>
						<?php if ( has_excerpt() ) : ?>
							<p class="featured__desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
						<?php if ( $yarmside_price ) : ?>
							<p class="featured__price">
								<strong class="tabular"><?php echo esc_html( yarmside_format_price( $yarmside_price ) ); ?></strong>
								<span class="status <?php echo esc_attr( $yarmside_status[1] ); ?>"><?php echo esc_html( $yarmside_status[0] ); ?></span>
							</p>
						<?php endif; ?>
					</div>
				</a>
				<?php
			endwhile;
			wp_reset_postdata();
		endif;

		// Three latest homes, excluding the featured one.
		$yarmside_latest = new WP_Query(
			array(
				'post_type'      => 'property',
				'posts_per_page' => 3,
				'post__not_in'   => $yarmside_featured_id ? array( $yarmside_featured_id ) : array(),
				'no_found_rows'  => true,
			)
		);

		if ( $yarmside_latest->have_posts() ) :
			?>
			<div class="card-grid">
				<?php
				while ( $yarmside_latest->have_posts() ) :
					$yarmside_latest->the_post();
					get_template_part( 'template-parts/property-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php endif; ?>

		<div class="listings__more">
			<a class="btn btn--ghost" href="<?php echo esc_url( yarmside_properties_url() ); ?>"><?php esc_html_e( 'View all homes to let', 'yarmside' ); ?></a>
		</div>
	</div>
</section>

<!-- How a tenancy runs -->
<section class="section process-section">
	<div class="wrap">
		<div class="section-head">
			<div class="section-head__titles">
				<h2 class="h2"><?php esc_html_e( 'From valuation to move-in, in four stages, always in this order.', 'yarmside' ); ?></h2>
			</div>
		</div>

		<div class="stages">
			<?php
			$yarmside_stages = apply_filters(
				'yarmside_process_stages',
				array(
					array( __( '01 · Valuation', 'yarmside' ), __( 'An honest read of the market', 'yarmside' ), __( 'A face to face visit, a fee-free recommendation on price, backed by real comparables on your street.', 'yarmside' ) ),
					array( __( '02 · Preparation', 'yarmside' ), __( 'Everything ready before marketing', 'yarmside' ), __( 'Photography, a proper description, and the compliance work, EICR, gas safety, EPC and smoke alarms, done first.', 'yarmside' ) ),
					array( __( '03 · Tenant find', 'yarmside' ), __( 'Referenced, and met in person', 'yarmside' ), __( 'Listed on the major portals, and matched against our own list of registered tenants.', 'yarmside' ) ),
					array( __( '04 · Management', 'yarmside' ), __( 'One statement, same day, every month', 'yarmside' ), __( 'Rent collected, repairs coordinated, and a clear statement issued on the same day every month.', 'yarmside' ) ),
				)
			);
			foreach ( $yarmside_stages as $yarmside_stage ) :
				?>
				<div class="stage">
					<span class="stage__index"><?php echo esc_html( $yarmside_stage[0] ); ?></span>
					<div class="stage__rule"></div>
					<p class="stage__title"><?php echo esc_html( $yarmside_stage[1] ); ?></p>
					<p><?php echo esc_html( $yarmside_stage[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
// Testimonial — only rendered once a real quote is entered in the Customizer.
$yarmside_quote = get_theme_mod( 'yarmside_testimonial_quote', '' );
if ( $yarmside_quote ) :
	?>
	<section class="section section--tight">
		<div class="wrap">
			<blockquote class="testimonial">
				<p class="testimonial__quote"><?php echo esc_html( $yarmside_quote ); ?></p>
				<?php $yarmside_cite = get_theme_mod( 'yarmside_testimonial_cite', '' ); ?>
				<?php if ( $yarmside_cite ) : ?>
					<cite class="testimonial__cite"><?php echo esc_html( $yarmside_cite ); ?></cite>
				<?php endif; ?>
			</blockquote>
		</div>
	</section>
<?php endif; ?>

<?php
// Journal: three latest articles.
$yarmside_journal = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
	)
);

if ( $yarmside_journal->have_posts() ) :
	?>
	<section class="section journal-section">
		<div class="wrap">
			<div class="section-head">
				<div class="section-head__titles">
					<h2 class="h2"><?php echo esc_html( get_theme_mod( 'yarmside_journal_heading', __( 'Plain-English guidance, updated as things change.', 'yarmside' ) ) ); ?></h2>
				</div>
				<p class="lede"><?php echo esc_html( get_theme_mod( 'yarmside_journal_lede', __( 'Regulation, market conditions and the practical business of letting a home in the North East, explained without jargon.', 'yarmside' ) ) ); ?></p>
			</div>

			<div class="journal-grid">
				<?php
				while ( $yarmside_journal->have_posts() ) :
					$yarmside_journal->the_post();
					get_template_part( 'template-parts/article-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
yarmside_cta(
	array(
		'heading' => get_theme_mod( 'yarmside_cta_heading', __( 'Considering a let? Let\'s begin with a conversation.', 'yarmside' ) ),
		'buttons' => array(
			array( __( 'Request a valuation', 'yarmside' ), yarmside_contact_url(), 'btn--primary' ),
			array( __( 'Compare our services', 'yarmside' ), get_page_by_path( 'landlords' ) ? get_permalink( get_page_by_path( 'landlords' ) ) : yarmside_contact_url(), 'btn--ghost-light' ),
		),
	)
);

get_footer();
