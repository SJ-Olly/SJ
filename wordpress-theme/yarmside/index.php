<?php
/**
 * Fallback template: a branded list of whatever the query returns.
 *
 * @package Yarmside
 */

get_header();
?>

<div class="page-head">
	<div class="wrap page-head__inner">
		<?php if ( is_archive() ) : ?>
			<h1 class="h1"><?php echo wp_kses_post( get_the_archive_title() ); ?></h1>
			<?php if ( get_the_archive_description() ) : ?>
				<p class="lede"><?php echo wp_kses_post( get_the_archive_description() ); ?></p>
			<?php endif; ?>
		<?php else : ?>
			<h1 class="h1"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
		<?php endif; ?>
	</div>
</div>

<section class="section--tight">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="<?php echo 'property' === get_post_type() ? 'card-grid' : 'journal-grid'; ?>">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( 'property' === get_post_type() ) {
						get_template_part( 'template-parts/property-card' );
					} else {
						get_template_part( 'template-parts/article-card', null, array( 'show_date' => true ) );
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
			<p class="lede"><?php esc_html_e( 'Nothing found here.', 'yarmside' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
