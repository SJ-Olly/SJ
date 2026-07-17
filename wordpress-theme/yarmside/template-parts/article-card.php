<?php
/**
 * A journal article card.
 *
 * @param bool $args['show_date'] Append the month to the category tag.
 *
 * @package Yarmside
 */

$yarmside_show_date  = ! empty( $args['show_date'] );
$yarmside_categories = get_the_category();
$yarmside_tag_parts  = array();

if ( $yarmside_categories ) {
	$yarmside_tag_parts[] = $yarmside_categories[0]->name;
}
if ( $yarmside_show_date ) {
	$yarmside_tag_parts[] = get_the_date( 'F Y' );
}
?>
<a class="article" href="<?php the_permalink(); ?>">
	<div class="frame article__art">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'yarmside-article' ); ?>
		<?php endif; ?>
	</div>
	<?php if ( $yarmside_tag_parts ) : ?>
		<p class="property__tag"><?php echo esc_html( implode( ' · ', $yarmside_tag_parts ) ); ?></p>
	<?php endif; ?>
	<h3 class="article__title"><?php the_title(); ?></h3>
	<p class="article__meta"><?php echo esc_html( yarmside_read_time( get_the_ID() ) ); ?></p>
</a>
