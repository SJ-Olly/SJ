<?php
/**
 * Properties archive: the homes-to-let grid with filters.
 *
 * @package Yarmside
 */

get_header();
?>

<main>
	<div class="page-head">
		<div class="wrap page-head__inner">
			<h1 class="h1"><?php esc_html_e( 'Every home on this page has been visited in person.', 'yarmside' ); ?></h1>
			<p class="lede"><?php esc_html_e( "We work with a deliberately small number of properties so we can give each one the attention it deserves. If nothing here suits, register with us and we'll tell you when something does.", 'yarmside' ); ?></p>
		</div>
	</div>

	<section class="section--tight">
		<div class="wrap">
			<div class="filters" role="group" aria-label="<?php esc_attr_e( 'Filter properties', 'yarmside' ); ?>">
				<button class="filter-btn" data-filter="all" aria-pressed="true"><?php esc_html_e( 'All', 'yarmside' ); ?></button>
				<button class="filter-btn" data-filter="house" aria-pressed="false"><?php esc_html_e( 'Houses', 'yarmside' ); ?></button>
				<button class="filter-btn" data-filter="flat" aria-pressed="false"><?php esc_html_e( 'Flats', 'yarmside' ); ?></button>
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

				<nav class="pagination" aria-label="<?php esc_attr_e( 'Properties navigation', 'yarmside' ); ?>">
					<?php echo wp_kses_post( paginate_links( array( 'mid_size' => 2 ) ) ); ?>
				</nav>
			<?php else : ?>
				<p class="lede"><?php esc_html_e( 'No homes are listed right now. Register with us and we will let you know the moment something suitable is coming up.', 'yarmside' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="section cta">
		<div class="wrap cta__grid">
			<h2 class="h2"><?php esc_html_e( 'Renting through Yarmside', 'yarmside' ); ?></h2>
			<div class="cta__panel">
				<p class="lede"><?php esc_html_e( "Every applicant is referenced properly and met in person, and every home we let is compliant before you move in. You'll always know who to call, and they'll know your name.", 'yarmside' ); ?></p>
				<div class="btn-row">
					<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Register as a tenant', 'yarmside' ); ?></a>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
