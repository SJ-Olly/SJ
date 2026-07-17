<?php
/**
 * Comments — kept quiet; the journal is mostly one-way.
 *
 * @package Yarmside
 */

if ( post_password_required() ) {
	return;
}
?>

<div class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="h3">
			<?php
			$yarmside_count = get_comments_number();
			/* translators: %s: number of comments. */
			printf( esc_html( _n( '%s comment', '%s comments', $yarmside_count, 'yarmside' ) ), esc_html( number_format_i18n( $yarmside_count ) ) );
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'avatar_size' => 0,
				)
			);
			?>
		</ol>

		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="form__note"><?php esc_html_e( 'Comments are closed.', 'yarmside' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>
