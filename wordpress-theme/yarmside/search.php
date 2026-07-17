<?php
/**
 * Search results.
 *
 * @package Yarmside
 */

get_header();
?>

<div class="page-head">
	<div class="wrap page-head__inner">
		<h1 class="h1">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Search results for "%s"', 'yarmside' ), esc_html( get_search_query() ) );
			?>
		</h1>
	</div>
</div>

<section class="section--tight">
	<div class="wrap">
		<?php get_search_form(); ?>

		<?php if ( have_posts() ) : ?>
			<div class="journal-grid" style="margin-top: var(--space-4);">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( 'property' === get_post_type() ) {
						get_template_part( 'template-parts/property-card' );
					} else {
						?>
						<a class="article" href="<?php the_permalink(); ?>">
							<p class="property__tag"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></p>
							<h2 class="article__title"><?php the_title(); ?></h2>
						</a>
						<?php
					}
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
			<p class="lede" style="margin-top: var(--space-4);"><?php esc_html_e( 'Nothing matched that search. Try different words, or browse the homes we have to let.', 'yarmside' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
