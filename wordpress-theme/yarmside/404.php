<?php
/**
 * 404 template.
 *
 * @package Yarmside
 */

get_header();
?>

<main>
	<div class="page-head">
		<div class="wrap page-head__inner">
			<h1 class="h1"><?php esc_html_e( 'This page has moved out.', 'yarmside' ); ?></h1>
			<p class="lede"><?php esc_html_e( "The address you followed doesn't exist on this site, or the page has been taken down. The homes we currently have to let are all listed on the properties page.", 'yarmside' ); ?></p>
			<div class="btn-row" style="margin-top: var(--space-2);">
				<a class="btn btn--primary" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'Browse homes to let', 'yarmside' ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the homepage', 'yarmside' ); ?></a>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
