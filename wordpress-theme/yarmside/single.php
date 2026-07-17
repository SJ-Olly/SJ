<?php
/**
 * A journal article.
 *
 * @package Yarmside
 */

get_header();

while ( have_posts() ) :
	the_post();

	$yarmside_categories = get_the_category();
	$yarmside_tag_parts  = array();
	if ( $yarmside_categories ) {
		$yarmside_tag_parts[] = $yarmside_categories[0]->name;
	}
	$yarmside_tag_parts[] = get_the_date( 'F Y' );
	$yarmside_tag_parts[] = yarmside_read_time( get_the_ID() );

	$yarmside_journal = get_option( 'page_for_posts' );
	?>

	<div class="page-head">
		<div class="wrap page-head__inner">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'yarmside' ); ?>">
				<?php if ( $yarmside_journal ) : ?>
					<a href="<?php echo esc_url( get_permalink( $yarmside_journal ) ); ?>"><?php echo esc_html( get_the_title( $yarmside_journal ) ); ?></a>
					<span aria-hidden="true">/</span>
				<?php endif; ?>
				<span><?php the_title(); ?></span>
			</nav>
			<h1 class="h1"><?php the_title(); ?></h1>
			<p class="property__tag"><?php echo esc_html( implode( ' · ', $yarmside_tag_parts ) ); ?></p>
		</div>
	</div>

	<article <?php post_class(); ?>>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="wrap" style="margin-bottom: var(--space-4);">
				<div class="frame article__art">
					<?php the_post_thumbnail( 'yarmside-bay', array( 'fetchpriority' => 'high' ) ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="wrap">
			<div class="prose-flow">
				<?php the_content(); ?>
			</div>

			<?php
			wp_link_pages(
				array(
					'before' => '<nav class="pagination">' . esc_html__( 'Pages:', 'yarmside' ),
					'after'  => '</nav>',
				)
			);
			?>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</article>

	<?php
	yarmside_cta(
		array(
			'heading' => __( 'Got a question this article hasn\'t answered?', 'yarmside' ),
			'lede'    => __( 'Ask us directly. If enough landlords ask the same thing, it usually becomes the next article.', 'yarmside' ),
			'buttons' => array(
				array( __( 'Get in touch', 'yarmside' ), yarmside_contact_url(), 'btn--primary' ),
			),
		)
	);

endwhile;

get_footer();
