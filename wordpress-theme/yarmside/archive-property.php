<?php
/**
 * Homes to let: the property archive with type/availability filters.
 *
 * @package Yarmside
 */

get_header();
?>

<div class="page-head">
	<div class="wrap page-head__inner">
		<h1 class="h1"><?php esc_html_e( 'Every home on this page has been visited in person.', 'yarmside' ); ?></h1>
		<p class="lede"><?php esc_html_e( 'We work with a deliberately small number of properties so we can give each one the attention it deserves. If nothing here suits, register with us and we\'ll tell you when something does.', 'yarmside' ); ?></p>
	</div>
</div>

<section class="section--tight">
	<div class="wrap">

		<?php
		$yarmside_types = get_terms(
			array(
				'taxonomy'   => 'property_type',
				'hide_empty' => true,
			)
		);
		?>
		<div class="filters" role="group" aria-label="<?php esc_attr_e( 'Filter properties', 'yarmside' ); ?>">
			<button class="filter-btn" data-filter="all" aria-pressed="true"><?php esc_html_e( 'All', 'yarmside' ); ?></button>
			<?php if ( $yarmside_types && ! is_wp_error( $yarmside_types ) ) : ?>
				<?php foreach ( $yarmside_types as $yarmside_type ) : ?>
					<button class="filter-btn" data-filter="<?php echo esc_attr( $yarmside_type->slug ); ?>" aria-pressed="false"><?php echo esc_html( $yarmside_type->name ); ?></button>
				<?php endforeach; ?>
			<?php endif; ?>
			<button class="filter-btn" data-filter="available" aria-pressed="false"><?php esc_html_e( 'Available now', 'yarmside' ); ?></button>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="card-grid" id="propertyGrid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/property-card' );
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => __( 'Previous', 'yarmside' ),
					'next_text' => __( 'Next', 'yarmside' ),
				)
			);
			?>
		<?php else : ?>
			<p class="lede"><?php esc_html_e( 'Nothing is listed right now — our list is short by design, and it changes often. Register with us and we\'ll tell you when a suitable home is coming up.', 'yarmside' ); ?></p>
		<?php endif; ?>

		<p class="form__note" style="margin-top: var(--space-4);">
			<?php
			printf(
				/* translators: %s: link to the contact page. */
				wp_kses_post( __( 'Looking for something we don\'t have listed? Tell us what you need through the <a href="%s" style="text-decoration: underline;">contact page</a> and we\'ll let you know when a suitable home is coming up.', 'yarmside' ) ),
				esc_url( yarmside_contact_url() )
			);
			?>
		</p>
	</div>
</section>

<?php
yarmside_cta(
	array(
		'heading' => __( 'Renting through Yarmside', 'yarmside' ),
		'lede'    => __( 'Every applicant is referenced properly and met in person, and every home we let is compliant before you move in. You\'ll always know who to call, and they\'ll know your name.', 'yarmside' ),
		'buttons' => array(
			array( __( 'Register as a tenant', 'yarmside' ), yarmside_contact_url(), 'btn--primary' ),
		),
	)
);

get_footer();
