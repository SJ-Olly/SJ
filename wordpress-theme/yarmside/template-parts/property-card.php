<?php
/**
 * Single property card in a grid.
 *
 * @package Yarmside
 */

$type   = yarmside_property_meta( 'type' );
$status = yarmside_property_meta( 'status' );
$slug   = ( false !== stripos( (string) $type, 'flat' ) ) ? 'flat' : 'house';
?>
<a class="property" href="<?php the_permalink(); ?>" data-type="<?php echo esc_attr( $slug ); ?>" data-status="<?php echo 'agreed' === $status ? 'agreed' : 'available'; ?>">
	<div class="frame property__art">
		<?php the_post_thumbnail( 'yarmside-card' ); ?>
	</div>
	<div class="property__body">
		<?php if ( $type ) : ?>
			<p class="property__tag"><?php echo esc_html( $type ); ?></p>
		<?php endif; ?>
		<h3 class="h3"><?php the_title(); ?></h3>
		<?php yarmside_property_dim_line(); ?>
		<p class="property__price">
			<strong class="tabular"><?php echo esc_html( yarmside_property_meta( 'price' ) ); ?></strong>
			<?php yarmside_property_status(); ?>
		</p>
	</div>
</a>
