<?php
/**
 * Homepage.
 *
 * @package Yarmside
 */

get_header();

$hero_image_id = (int) get_theme_mod( 'yarmside_hero_image' );
$hero_url      = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'full' ) : '';
?>

<main>

	<section class="hero"<?php echo $hero_url ? '' : ' style="background: var(--dark);"'; ?>>
		<?php if ( $hero_url ) : ?>
			<div class="hero__media">
				<img src="<?php echo esc_url( $hero_url ); ?>" alt="<?php echo esc_attr( get_post_meta( $hero_image_id, '_wp_attachment_image_alt', true ) ); ?>" fetchpriority="high">
			</div>
		<?php endif; ?>

		<div class="wrap hero__inner">
			<h1 class="h1"><?php echo esc_html( get_theme_mod( 'yarmside_hero_heading', __( "We manage homes the way we'd want our own managed.", 'yarmside' ) ) ); ?></h1>
			<p class="hero__lede"><?php echo esc_html( get_theme_mod( 'yarmside_hero_text', __( 'A small, unhurried lettings practice based on Yarm High Street, working with landlords and renters across the Tees Valley and the North East.', 'yarmside' ) ) ); ?></p>
			<div class="btn-row">
				<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Request a valuation', 'yarmside' ); ?></a>
				<a class="btn btn--ghost-light" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'Browse homes to let', 'yarmside' ); ?></a>
			</div>
			<p class="dim-line dim-line--light hero__foot">
				<span class="dim-line__label"><?php esc_html_e( 'Where we work', 'yarmside' ); ?></span>
				<span class="dim-line__rule"></span>
				<span class="dim-line__value"><?php esc_html_e( 'Yarm · Tees Valley · North East', 'yarmside' ); ?></span>
			</p>
		</div>
	</section>

	<?php
	$properties = new WP_Query( array(
		'post_type'      => 'property',
		'posts_per_page' => 4,
		'no_found_rows'  => true,
	) );
	?>
	<section class="section listings">
		<div class="wrap">
			<div class="section-head">
				<div class="section-head__titles">
					<h2 class="h2"><?php esc_html_e( 'A small number of homes, each one properly described.', 'yarmside' ); ?></h2>
				</div>
				<p class="lede"><?php esc_html_e( "We visit, photograph and describe every home ourselves. We'd rather manage a modest portfolio well than a large one carelessly, so this list is short by design.", 'yarmside' ); ?></p>
			</div>

			<?php if ( $properties->have_posts() ) : ?>
				<?php
				$index = 0;
				while ( $properties->have_posts() ) :
					$properties->the_post();
					$index++;

					if ( 1 === $index ) :
						?>
						<a class="featured" href="<?php the_permalink(); ?>">
							<div class="frame featured__art">
								<?php the_post_thumbnail( 'yarmside-wide' ); ?>
							</div>
							<div class="featured__body">
								<p class="featured__tag"><?php esc_html_e( 'Featured', 'yarmside' ); ?> &middot; <?php echo esc_html( yarmside_property_meta( 'type' ) ); ?></p>
								<p class="featured__title"><?php the_title(); ?></p>
								<?php yarmside_property_dim_line( true ); ?>
								<?php if ( has_excerpt() ) : ?>
									<p class="featured__desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
								<?php endif; ?>
								<p class="featured__price">
									<strong class="tabular"><?php echo esc_html( yarmside_property_meta( 'price' ) ); ?></strong>
									<?php yarmside_property_status(); ?>
								</p>
							</div>
						</a>
						<div class="card-grid">
					<?php else : ?>
						<?php get_template_part( 'template-parts/property-card' ); ?>
					<?php endif; ?>
				<?php endwhile; ?>
				</div>
				<?php wp_reset_postdata(); ?>

				<div class="listings__more">
					<a class="btn btn--ghost" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'View all homes to let', 'yarmside' ); ?></a>
				</div>
			<?php else : ?>
				<p class="lede"><?php esc_html_e( 'Homes appear here as soon as properties are added under Properties in the dashboard.', 'yarmside' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="section process-section">
		<div class="wrap">
			<div class="section-head">
				<div class="section-head__titles">
					<h2 class="h2"><?php esc_html_e( 'From valuation to move-in, in four stages, always in this order.', 'yarmside' ); ?></h2>
				</div>
			</div>

			<div class="stages">
				<div class="stage">
					<span class="stage__index"><?php esc_html_e( '01 · Valuation', 'yarmside' ); ?></span>
					<div class="stage__rule"></div>
					<p class="stage__title"><?php esc_html_e( 'An honest read of the market', 'yarmside' ); ?></p>
					<p><?php esc_html_e( 'A face to face visit, a fee-free recommendation on price, backed by real comparables on your street.', 'yarmside' ); ?></p>
				</div>
				<div class="stage">
					<span class="stage__index"><?php esc_html_e( '02 · Preparation', 'yarmside' ); ?></span>
					<div class="stage__rule"></div>
					<p class="stage__title"><?php esc_html_e( 'Everything ready before marketing', 'yarmside' ); ?></p>
					<p><?php esc_html_e( 'Photography, a proper description, and the compliance work, EICR, gas safety, EPC and smoke alarms, done first.', 'yarmside' ); ?></p>
				</div>
				<div class="stage">
					<span class="stage__index"><?php esc_html_e( '03 · Tenant find', 'yarmside' ); ?></span>
					<div class="stage__rule"></div>
					<p class="stage__title"><?php esc_html_e( 'Referenced, and met in person', 'yarmside' ); ?></p>
					<p><?php esc_html_e( 'Listed on Rightmove and OnTheMarket, and matched against our own list of registered tenants.', 'yarmside' ); ?></p>
				</div>
				<div class="stage">
					<span class="stage__index"><?php esc_html_e( '04 · Management', 'yarmside' ); ?></span>
					<div class="stage__rule"></div>
					<p class="stage__title"><?php esc_html_e( 'One statement, same day, every month', 'yarmside' ); ?></p>
					<p><?php esc_html_e( 'Rent collected, repairs coordinated, and a clear statement issued on the same day every month.', 'yarmside' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<?php $quote = get_theme_mod( 'yarmside_testimonial_quote' ); ?>
	<?php if ( $quote ) : ?>
		<section class="section section--tight">
			<div class="wrap">
				<blockquote class="testimonial">
					<p class="testimonial__quote">&ldquo;<?php echo esc_html( $quote ); ?>&rdquo;</p>
					<?php if ( get_theme_mod( 'yarmside_testimonial_cite' ) ) : ?>
						<cite class="testimonial__cite"><?php echo esc_html( get_theme_mod( 'yarmside_testimonial_cite' ) ); ?></cite>
					<?php endif; ?>
				</blockquote>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$journal = new WP_Query( array(
		'posts_per_page'      => 3,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
	) );
	?>
	<?php if ( $journal->have_posts() ) : ?>
		<section class="section journal-section">
			<div class="wrap">
				<div class="section-head">
					<div class="section-head__titles">
						<h2 class="h2"><?php esc_html_e( 'Plain-English guidance, updated as things change.', 'yarmside' ); ?></h2>
					</div>
					<p class="lede"><?php esc_html_e( 'Regulation, market conditions and the practical business of letting a home in the North East, explained without jargon.', 'yarmside' ); ?></p>
				</div>

				<div class="journal-grid">
					<?php
					while ( $journal->have_posts() ) :
						$journal->the_post();
						get_template_part( 'template-parts/article-card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="section cta">
		<div class="wrap cta__grid">
			<h2 class="h2"><?php esc_html_e( "Considering a let? Let's begin with a conversation.", 'yarmside' ); ?></h2>
			<div class="cta__panel">
				<p class="lede"><?php esc_html_e( "Request a free, no obligation valuation. We'll visit your property, give you an honest read of the market, and explain exactly how we'd manage it, with no pressure to instruct.", 'yarmside' ); ?></p>
				<div class="btn-row">
					<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Request a valuation', 'yarmside' ); ?></a>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
