<?php
/**
 * Jetpack Compatibility File.
 *
 * @see http://jetpack.me/
 *
 * @package Blink
 */

/**
 * Add theme support for Infinite Scroll.
 *
 * @see http://jetpack.me/support/infinite-scroll/
 */
function blink_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'type'           => 'click',
		'container'      => 'main',
		'footer'         => 'colophon',
		'wrapper'        => false,
		'render'         => 'blink_infinite_scroll_render',
		'posts_per_page' => get_option( 'posts_per_page' )
	) );

	/**
	 * Add theme support for content options.
	 *
	 * @since 1.0.9
	 */
	add_theme_support( 'jetpack-content-options', array(
		'post-details' => array(
			'stylesheet' => 'blink-style',
			'date'       => '.posted-on',
			'categories' => '.cat-links',
			'tags'       => '.tags-links',
			'author'     => '.byline',
		),
	) );
}
add_action( 'after_setup_theme', 'blink_jetpack_setup' );

if ( ! function_exists( 'blink_infinite_scroll_render' ) ) :
/**
 * Rendering function for Infinite Scroll
 *
 * @since  1.0.
 *
 * @return void
 */
function blink_infinite_scroll_render() {
	// Add flag to prevent widget area from loading again
	add_filter( 'blink_loading_more_posts', '__return_true' );

	while ( have_posts() ) : the_post();
		get_template_part( 'partials/content' );
		get_template_part( 'partials/jetpack', 'background' );
	endwhile;

	// Remove flag
	remove_all_filters( 'blink_loading_more_posts' );
}
endif;

if ( ! function_exists( 'blink_infinite_scroll_js_settings' ) ) :
/**
 * Adjust the JS settings for infinite scroll
 *
 * @since 1.0.
 *
 * @param  array $settings The JS settings
 * @return array           The adjusted settings
 */
function blink_infinite_scroll_js_settings( $settings ) {
	$settings['text'] = esc_js( __( '&larr; Older Posts', 'blink' ) );

	return $settings;
}
endif;

add_filter( 'infinite_scroll_js_settings', 'blink_infinite_scroll_js_settings' );
