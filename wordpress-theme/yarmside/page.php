<?php
/**
 * Standard page: branded page head (title + excerpt lede), then
 * fully editable block content. Full-width blocks (.alignfull)
 * break out to the viewport edge, so pattern bands work anywhere.
 *
 * @package Yarmside
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<div class="page-head">
		<div class="wrap page-head__inner">
			<h1 class="h1"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<article <?php post_class(); ?>>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</article>

	<?php
endwhile;

get_footer();
