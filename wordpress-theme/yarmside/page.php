<?php
/**
 * Default page template: every new page inherits the site's look.
 *
 * @package Yarmside
 */

get_header();
?>

<main>
	<?php while ( have_posts() ) : the_post(); ?>

		<div class="page-head">
			<div class="wrap page-head__inner">
				<h1 class="h1"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="wrap">
				<div class="frame single-hero">
					<?php the_post_thumbnail( 'yarmside-wide' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<article class="section--tight">
			<div class="wrap">
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		</article>

	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
