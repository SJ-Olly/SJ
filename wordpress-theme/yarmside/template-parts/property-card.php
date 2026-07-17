<?php
/**
 * A property card, used on the archive and the homepage grid.
 *
 * @package Yarmside
 */

$yarmside_id     = get_the_ID();
$yarmside_size   = isset( $args['size'] ) ? $args['size'] : 'yarmside-card';
$yarmside_status = yarmside_property_status( $yarmside_id );
$yarmside_price  = get_post_meta( $yarmside_id, '_yarmside_price', true );
?>
<a class="property" href="<?php the_permalink(); ?>"<?php yarmside_property_filter_atts( $yarmside_id ); ?>>
	<div class="frame property__art">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( $yarmside_size ); ?>
		<?php endif; ?>
	</div>
	<div class="property__body">
		<p class="property__tag"><?php echo esc_html( yarmside_property_tag( $yarmside_id ) ); ?></p>
		<h3 class="h3"><?php the_title(); ?></h3>
		<?php yarmside_property_dimline( $yarmside_id ); ?>
		<?php if ( $yarmside_price ) : ?>
			<p class="property__price">
				<strong class="tabular"><?php echo esc_html( yarmside_format_price( $yarmside_price ) ); ?></strong>
				<span class="status <?php echo esc_attr( $yarmside_status[1] ); ?>"><?php echo esc_html( $yarmside_status[0] ); ?></span>
			</p>
		<?php endif; ?>
	</div>
</a>
