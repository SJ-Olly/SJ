<?php
/**
 * Single property: the particulars page.
 *
 * @package Yarmside
 */

get_header();
?>

<main>
	<?php while ( have_posts() ) : the_post(); ?>

		<div class="page-head">
			<div class="wrap page-head__inner">
				<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'yarmside' ); ?>">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'Homes to let', 'yarmside' ); ?></a>
					<span aria-hidden="true">/</span>
					<span><?php the_title(); ?></span>
				</nav>
				<h1 class="h1"><?php the_title(); ?></h1>
				<?php yarmside_property_dim_line(); ?>
			</div>
		</div>

		<section class="section--tight">
			<div class="wrap">

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="gallery">
						<div class="frame">
							<?php the_post_thumbnail( 'yarmside-wide', array( 'fetchpriority' => 'high' ) ); ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="detail-grid">
					<div class="detail-body">
						<div class="entry-content">
							<?php the_content(); ?>
						</div>

						<p class="form__note"><?php esc_html_e( "Measurements are approximate and particulars are prepared in good faith. They don't form part of any contract, and we'd always rather you saw the house in person.", 'yarmside' ); ?></p>
					</div>

					<aside class="aside-card" aria-label="<?php esc_attr_e( 'Enquire about this property', 'yarmside' ); ?>">
						<?php yarmside_property_status(); ?>
						<p class="aside-card__price tabular"><?php echo esc_html( yarmside_property_meta( 'price' ) ); ?></p>
						<p class="aside-card__note"><?php esc_html_e( 'Viewings are accompanied, usually within two working days of asking. Evenings and Saturday mornings are fine.', 'yarmside' ); ?></p>
						<div class="btn-row">
							<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Arrange a viewing', 'yarmside' ); ?></a>
						</div>
					</aside>
				</div>

			</div>
		</section>

	<?php endwhile; ?>

	<section class="section cta">
		<div class="wrap cta__grid">
			<h2 class="h2"><?php esc_html_e( 'Not quite right?', 'yarmside' ); ?></h2>
			<div class="cta__panel">
				<p class="lede"><?php esc_html_e( "Our list is short by design, but it changes often. Tell us what you're looking for and we'll get in touch when a suitable home is coming up.", 'yarmside' ); ?></p>
				<div class="btn-row">
					<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Register as a tenant', 'yarmside' ); ?></a>
					<a class="btn btn--ghost-light" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'Back to all homes', 'yarmside' ); ?></a>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
