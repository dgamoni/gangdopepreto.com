<?php
/**
 * @package Blink
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'blink' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Enter keyword and hit enter', 'search placeholder', 'blink' ); ?>" value="<?php the_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'blink' ); ?>">
	</label>
	<button type="submit" class="search-submit"><?php _e( 'Search', 'blink' ); ?></button>
</form>
