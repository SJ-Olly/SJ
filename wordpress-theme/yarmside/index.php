<?php
/**
 * Post listing (the Journal) and general fallback.
 *
 * @package Yarmside
 */

get_header();
?>

<main>
	<div class="page-head">
		<div class="wrap page-head__inner">
			<h1 class="h1">
				<?php
				if ( is_home() ) {
					esc_html_e( 'Reading for landlords.', 'yarmside' );
				} elseif ( is_archive() ) {
					the_archive_title();
				} elseif ( is_search() ) {
					/* translators: %s: search query. */
					printf( esc_html__( 'Search results for %s', 'yarmside' ), '&ldquo;' . esc_html( get_search_query() ) . '&rdquo;' );
				} else {
					esc_html_e( 'Journal', 'yarmside' );
				}
				?>
			</h1>
			<?php if ( is_home() ) : ?>
				<p class="lede"><?php esc_html_e( "Plain-English writing on changing regulation, market conditions and the practical business of letting a home in the North East. No jargon, no scaremongering, and we only publish when there's something worth saying.", 'yarmside' ); ?></p>
			<?php elseif ( is_archive() && get_the_archive_description() ) : ?>
				<p class="lede"><?php echo wp_kses_post( get_the_archive_description() ); ?></p>
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
						get_template_part( 'template-parts/article-card' );
					endwhile;
					?>
				</div>

				<nav class="pagination" aria-label="<?php esc_attr_e( 'Posts navigation', 'yarmside' ); ?>">
					<?php echo wp_kses_post( paginate_links( array( 'mid_size' => 2 ) ) ); ?>
				</nav>
			<?php else : ?>
				<p class="lede"><?php esc_html_e( "Nothing published here yet. New articles appear as soon as they're written.", 'yarmside' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
