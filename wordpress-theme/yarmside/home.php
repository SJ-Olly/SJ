<?php
/**
 * The journal: blog index.
 *
 * @package Yarmside
 */

get_header();

$yarmside_page_id = get_option( 'page_for_posts' );
$yarmside_title   = $yarmside_page_id ? get_the_title( $yarmside_page_id ) : get_bloginfo( 'name' );
$yarmside_lede    = $yarmside_page_id ? get_the_excerpt( $yarmside_page_id ) : '';
?>

<div class="page-head">
	<div class="wrap page-head__inner">
		<h1 class="h1"><?php echo esc_html( $yarmside_title ); ?></h1>
		<?php if ( $yarmside_lede ) : ?>
			<p class="lede"><?php echo esc_html( $yarmside_lede ); ?></p>
		<?php endif; ?>
	</div>
</div>

<section class="section--tight">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="journal-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/article-card', null, array( 'show_date' => true ) );
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
			<p class="lede"><?php esc_html_e( 'Nothing has been published yet. We only publish when there\'s something worth saying.', 'yarmside' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
yarmside_cta(
	array(
		'heading' => __( 'Got a question the journal hasn\'t answered?', 'yarmside' ),
		'lede'    => __( 'Ask us directly. If enough landlords ask the same thing, it usually becomes the next article.', 'yarmside' ),
		'buttons' => array(
			array( __( 'Get in touch', 'yarmside' ), yarmside_contact_url(), 'btn--primary' ),
		),
	)
);

get_footer();
