<?php
/**
 * Journal article card.
 *
 * @package Yarmside
 */

$category = get_the_category();
?>
<a class="article" href="<?php the_permalink(); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="frame article__art">
			<?php the_post_thumbnail( 'yarmside-card' ); ?>
		</div>
	<?php endif; ?>
	<p class="property__tag">
		<?php if ( $category ) : ?>
			<?php echo esc_html( $category[0]->name ); ?> &middot;
		<?php endif; ?>
		<?php echo esc_html( get_the_date( 'F Y' ) ); ?>
	</p>
	<h2 class="article__title"><?php the_title(); ?></h2>
	<p class="article__meta"><?php echo esc_html( yarmside_reading_time() ); ?></p>
</a>
