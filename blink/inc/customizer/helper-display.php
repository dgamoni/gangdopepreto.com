<?php
/**
 * @package Blink
 */

if ( ! function_exists( 'blink_css_add_rules' ) ) :
/**
 * Process user options to generate CSS needed to implement the choices.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function blink_css_add_rules() {

	$background_color = maybe_hash_hex_color( get_theme_mod( 'background_color', blink_get_default( 'background_color' ) ) );

	if ( $background_color !== blink_get_default( 'background_color' ) ) {
		blink_get_css()->add( array(
			'selectors'    => array(
				'body.mce-content-body',
				'.page-links span:not(.page-numbers)',
				'.block-title span:not(.page-numbers)',
				'.archive-title span:not(.page-numbers)',
				'.comments-area > .comment-respond .comment-reply-title span:not(.page-numbers)'
			),
			'declarations' => array(
				'background-color' => $background_color,
			)
		) );
	}

	$color_accent = maybe_hash_hex_color( get_theme_mod( 'color-accent', blink_get_default( 'color-accent' ) ) );
	$color_main   = maybe_hash_hex_color( get_theme_mod( 'color-main', blink_get_default( 'color-main' ) ) );

	// Accent
	if ( $color_accent !== blink_get_default( 'color-accent' ) ) {
		blink_get_css()->add( array(
			'selectors'    => array( 'a', '.color-accent-text', '#infinite-handle:hover span' ),
			'declarations' => array(
				'color' => $color_accent,
			)
		) );
	}

	// Main
	if ( $color_accent !== blink_get_default( 'color-main' ) ) {
		blink_get_css()->add( array(
			'selectors'    => array(
				'body',
				'input',
				'button',
				'.button',
				'select',
				'textarea'
			),
			'declarations' => array(
				'color' => $color_main,
			)
		) );
	}

	// Uppercase Post / Page Title.
	$uppercase_title = absint( get_theme_mod( 'layout-uppercase-title', blink_get_default( 'layout-uppercase-title' ) ) );

	if ( 1 === $uppercase_title ) {
		blink_get_css()->add( array(
			'selectors'    => array( '.entry-title', '.nav-links [rel]' ),
			'declarations' => array(
				'text-transform' => 'uppercase',
			)
		) );
	}

	// Post Border.
	$post_border = absint( get_theme_mod( 'layout-post-border', blink_get_default( 'layout-post-border' ) ) );

	if ( 0 !== $post_border ) {
		blink_get_css()->add( array(
			'selectors' => array(
				'.post-with-border.blog .post-grid-content',
				'.post-with-border.blog .site-content',
				'.post-with-border.archive .post-grid-content',
				'.post-with-border.archive .site-content',
				'.post-with-border.search .post-grid-content',
				'.post-with-border.search .site-content',
				),
			'declarations' => array(
				'border-width' => $post_border / 10 . 'rem',
			)
		) );
		blink_get_css()->add( array(
			'selectors' => array(
				'.post-with-border .custom-header',
				),
			'declarations' => array(
				'border-width' => $post_border*2 / 10 . 'rem',
			)
		) );
	}
}
endif;

add_action( 'blink_css', 'blink_css_add_rules' );

if ( ! function_exists( 'blink_get_social_links' ) ) :
/**
 * Get the social links from options.
 *
 * @since  1.0.0.
 *
 * @return array    Keys are service names and the values are links.
 */
function blink_get_social_links() {
	// Define default services; note that these are intentional non-translatable
	$default_services = array(
		'facebook' => array(
			'title' => 'Facebook',
			'class' => 'facebook',
		),
		'twitter' => array(
			'title' => 'Twitter',
			'class' => 'twitter',
		),
		'google-plus' => array(
			'title' => 'Google+',
			'class' => 'googleplus',
		),
		'linkedin' => array(
			'title' => 'LinkedIn',
			'class' => 'linkedin',
		),
		'instagram' => array(
			'title' => 'Instagram',
			'class' => 'instagram',
		),
		'flickr' => array(
			'title' => 'Flickr',
			'class' => 'flickr',
		),
		'youtube' => array(
			'title' => 'YouTube',
			'class' => 'youtube',
		),
		'vimeo' => array(
			'title' => 'Vimeo',
			'class' => 'vimeo',
		),
		'pinterest' => array(
			'title' => 'Pinterest',
			'class' => 'pinterest',
		),
		'dribbble' => array(
			'title' => 'Dribbble',
			'class' => 'dribbble',
		),
		'email' => array(
			'title' => __( 'Email', 'blink' ),
			'class' => 'mail',
		),
		'rss' => array(
			'title' => __( 'RSS', 'blink' ),
			'class' => 'feed',
		),
	);

	// Set up the collector array
	$services_with_links = array();

	// Get the links for these services
	foreach ( $default_services as $service => $details ) {
		$url = get_theme_mod( 'social-' . $service, blink_get_default( 'social-' . $service ) );
		if ( '' !== $url ) {
			$services_with_links[ $service ] = array(
				'title' => $details['title'],
				'url'   => $url,
				'class' => $details['class'],
			);
		}
	}

	// Special handling for RSS
	$hide_rss = (int) get_theme_mod( 'social-hide-rss', blink_get_default( 'social-hide-rss' ) );
	if ( 0 === $hide_rss ) {
		$custom_rss = get_theme_mod( 'social-custom-rss', blink_get_default( 'social-custom-rss' ) );
		if ( ! empty( $custom_rss ) ) {
			$services_with_links['rss']['url'] = $custom_rss;
		} else {
			$services_with_links['rss']['url'] = get_feed_link();
		}
	} else {
		unset( $services_with_links['rss'] );
	}

	// Properly set the email
	if ( isset( $services_with_links['email']['url'] ) ) {
		$services_with_links['email']['url'] = esc_url( 'mailto:' . antispambot( $services_with_links['email']['url'] ) );
	}

	return apply_filters( 'blink_social_links', $services_with_links );
}
endif;
