<?php
/**
 * Search form.
 *
 * @package Yarmside
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="yarmside-search"><?php esc_html_e( 'Search this site', 'yarmside' ); ?></label>
	<input type="search" id="yarmside-search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'yarmside' ); ?>">
	<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Search', 'yarmside' ); ?></button>
</form>
