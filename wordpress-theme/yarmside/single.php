<?php
/**
 * Single journal post.
 *
 * @package Yarmside
 */

get_header();
?>

<main>
	<?php while ( have_posts() ) : the_post(); ?>

		<div class="page-head">
			<div class="wrap page-head__inner">
				<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'yarmside' ); ?>">
					<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'Journal', 'yarmside' ); ?></a>
					<span aria-hidden="true">/</span>
					<span><?php the_title(); ?></span>
				</nav>
				<h1 class="h1"><?php the_title(); ?></h1>
				<p class="post-meta">
					<?php
					$category = get_the_category();
					if ( $category ) {
						echo esc_html( $category[0]->name ) . ' &middot; ';
					}
					echo esc_html( get_the_date() ) . ' &middot; ' . esc_html( yarmside_reading_time() );
					?>
				</p>
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

	<section class="section cta">
		<div class="wrap cta__grid">
			<h2 class="h2"><?php esc_html_e( "Got a question this article hasn't answered?", 'yarmside' ); ?></h2>
			<div class="cta__panel">
				<p class="lede"><?php esc_html_e( 'Ask us directly. If enough landlords ask the same thing, it usually becomes the next article.', 'yarmside' ); ?></p>
				<div class="btn-row">
					<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Get in touch', 'yarmside' ); ?></a>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
