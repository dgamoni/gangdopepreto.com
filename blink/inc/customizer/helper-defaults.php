<?php
/**
 * @package Blink
 */

if ( ! function_exists( 'blink_option_defaults' ) ) :
/**
 * List of array containing default global options.
 *
 * @return array The default values for all theme options.
 */
function blink_option_defaults() {
	$defaults = array(
		// Background
		'background_color'               => 'ffffff',
		'background_image'               => '',
		'background_repeat'              => 'repeat',
		'background_position_x'          => 'left',
		'background_attachment'          => 'scroll',

		// Navigation
		'navigation-label'               => __( 'Menu', 'blink' ),

		// Logo
		'logo-regular'                   => '',
		'logo-retina'                    => '',
		'logo-favicon'                   => '',
		'logo-apple-touch'               => '',
		'logo-apple-touch'               => '',
		'logo-apple-touch'               => '',
		'title_tagline-logo-light'       => '',

		// Custom Header.
		'header_image-text'              => '',
		'header_image-color'             => '#000000',
		'header_image-opacity'           => 50,

		// Colors
		'color-accent'                   => '#6db0a3',
		'color-main'                     => '#000000',

		// Fonts
		'fonts-header'                   => 'Montserrat',
		'fonts-body'                     => 'Rufina',
		'fonts-subset'                   => 'latin',

		'layout-site'                    => 'hero',
		'layout-author-info'             => 0,
		'layout-post-navigation'         => 0,
		'layout-page-meta'               => 0,
		'layout-uppercase-title'         => 1,
		'layout-post-border'             => 0,
		'layout-header-overlay'          => 0,
		'layout-header-overlay-disabled' => 0,

		// Footer
		'footer-text'                    => '',
		'footer-credits'                 => 0,

		// Social
		'social-facebook'                => '',
		'social-twitter'                 => '',
		'social-google-plus'             => '',
		'social-linkedin'                => '',
		'social-instagram'               => '',
		'social-flickr'                  => '',
		'social-youtube'                 => '',
		'social-vimeo'                   => '',
		'social-pinterest'               => '',
		'social-dribbble'                => '',
		'social-email'                   => '',
		'social-hide-rss'                => ( blink_is_wpcom() ) ? 1 : 0,
		'social-custom-rss'              => '',

		'display-sticky-label'           => __( 'Featured', 'blink' ),
	);

	return apply_filters( 'blink_option_defaults', $defaults );
}
endif;

if ( ! function_exists( 'blink_get_default' ) ) :
/**
 * Return a particular global option default.
 *
 * @since  1.0.0.
 *
 * @param  string    $option    The key of the option to return.
 * @return mixed                Default value if found; false if not found.
 */
function blink_get_default( $option ) {
	$defaults = blink_option_defaults();
	return ( isset( $defaults[ $option ] ) ) ? $defaults[ $option ] : false;
}
endif;
