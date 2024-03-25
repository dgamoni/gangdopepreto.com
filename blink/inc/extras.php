<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Blink
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function blink_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'blink_page_menu_args' );

if ( ! function_exists( '_wp_render_title_tag' ) ) :
/**
 * Let WordPress manage the document title. From WordPress 4.1
 * there is support for add_theme_support( 'title-tag' ); so no need
 * to add <title> tags manually.
 *
 * This function adds fallback for older WordPress versions.
 *
 * @return void
 */
function blink_render_title() {
	echo '<title>' . wp_title( '|', false, 'right' ) . "</title>\n";
}
add_action( 'wp_head', 'blink_render_title' );
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function blink_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	$layout = get_theme_mod( 'layout-site', blink_get_default( 'layout-site' ) );

	if ( ! empty( $layout ) && ! is_singular() ) {
		$classes[] = 'layout-' . $layout;
	}

	$post_border = absint( get_theme_mod( 'layout-post-border', blink_get_default( 'layout-post-border' ) ) );

	if ( 0 !== $post_border ) {
		$classes[] = 'post-with-border';
	}

	$overlay_header = absint( get_theme_mod( 'layout-header-overlay', blink_get_default( 'layout-header-overlay' ) ) );

	$has_post_thumbnail = has_post_thumbnail();
	if ( true === $has_post_thumbnail ) {
		if ( 1 === $overlay_header ) {
			$classes[] = 'overlay-header';
		}
	}

	// Disabled at posts page.
	$is_overlay_header_disabled = absint( get_theme_mod( 'layout-header-overlay-disabled', blink_get_default( 'layout-header-overlay-disabled' ) ) );

	if ( ( is_home() && (1 === $is_overlay_header_disabled ) ) || is_archive() || is_search() ) {
		$index = array_search( 'overlay-header', $classes, true );
		if ( false !== $index ) {
			unset( $classes[ $index ] );
		}
	}

	return $classes;
}
add_filter( 'body_class', 'blink_body_classes' );

if ( ! function_exists( 'blink_post_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function blink_post_classes( $classes ) {

	if ( ! is_singular() ) {
		$classes[] = 'post-grid';
	}

	if ( has_filter( 'blink_loading_more_posts' ) ) {
		$classes[] = 'animate';
	}

	return $classes;
}
endif;

add_filter( 'post_class', 'blink_post_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 *
 * @deprecated 1.0.2.
 */
function blink_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'blink' ), max( $paged, $page ) );
	}

	return $title;
}
// add_filter( 'wp_title', 'blink_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function blink_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'blink_setup_author' );

if ( ! function_exists( 'blink_add_thumbnail_css' ) ) :
/**
 * Add featured image CSS for post grid's cover image.
 *
 * @global $wp_query
 *
 * @param  string $thumbnail_size Post thumbnail size. (default: blink_half)
 * @return void
 */
function blink_add_thumbnail_css() {

	// if ( is_singular() ) return;

	if ( is_single() ) {
		$post_id = get_the_ID();
		$thumbnail_id    = get_post_thumbnail_id( $post_id );
		$thumbnail_image = wp_get_attachment_image_src( $thumbnail_id, 'blink_full' );

		blink_get_css()->add( array(
			'selectors' => sprintf( '.post-%d .post-grid', $post_id ),
			'declarations' => array(
				'background-image' => 'url('. $thumbnail_image[0] .')',
			)
		) );
	} elseif ( is_page() ) {
		$post_id = get_the_ID();
		$thumbnail_id    = get_post_thumbnail_id( $post_id );
		$thumbnail_image = wp_get_attachment_image_src( $thumbnail_id, 'blink_full' );

		blink_get_css()->add( array(
			'selectors' => sprintf( '.post-%d .page-cover', $post_id ),
			'declarations' => array(
				'background-image' => 'url('. $thumbnail_image[0] .')',
			)
		) );
	} else {
		global $wp_query;

		// Posts object.
		$posts = $wp_query->posts;

		$count = 0;

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$count++;

				$thumbnail_size = 'blink_half';
				if ( 1 === $count ) $thumbnail_size = 'blink_full';

				$body_classes = get_body_class();
				if ( in_array( 'layout-odd', $body_classes, true ) ) {
					if ( 1 === $count % 3 ) { // 1, 4, 7, 10...
						$thumbnail_size = 'blink_full';
					}
				} elseif ( in_array( 'layout-even', $body_classes, true ) ) {
					$thumbnail_size = 'blink_full';
				} elseif ( in_array( 'layout-half', $body_classes, true ) ) {
					$thumbnail_size = 'blink_half';
				}

				$post_id = $post->ID;
				$thumbnail_id    = get_post_thumbnail_id( $post_id );
				$thumbnail_image = wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size );

				// continue, if post has no thumbnail
				if ( empty( $thumbnail_image ) ) continue;

				blink_get_css()->add( array(
					'selectors' => sprintf( '.post-%d', $post_id ),
					'declarations' => array(
						'background-image' => 'url('. $thumbnail_image[0] .')',
					)
				) );
			}
		}
	}
}
endif;

add_action( 'blink_css', 'blink_add_thumbnail_css' );

if ( ! function_exists( 'blink_sanitize_text' ) ) :
/**
 * Sanitize a string to allow only tags in the allowedtags array.
 *
 * @since  1.0.0.
 *
 * @param  string    $string    The unsanitized string.
 * @return string               The sanitized string.
 */
function blink_sanitize_text( $string ) {
	global $allowedtags;
	return wp_kses( $string , $allowedtags );
}
endif;

if ( ! function_exists( 'sanitize_hex_color' ) ) :
/**
 * Sanitizes a hex color.
 *
 * This is a copy of the core function for use when the customizer is not being shown.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function sanitize_hex_color( $color ) {
	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return null;
}
endif;

if ( ! function_exists( 'sanitize_hex_color_no_hash' ) ) :
/**
 * Sanitizes a hex color without a hash. Use sanitize_hex_color() when possible.
 *
 * This is a copy of the core function for use when the customizer is not being shown.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function sanitize_hex_color_no_hash( $color ) {
	$color = ltrim( $color, '#' );

	if ( '' === $color ) {
		return '';
	}

	return sanitize_hex_color( '#' . $color ) ? $color : null;
}
endif;

if ( ! function_exists( 'maybe_hash_hex_color' ) ) :
/**
 * Ensures that any hex color is properly hashed.
 *
 * This is a copy of the core function for use when the customizer is not being shown.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function maybe_hash_hex_color( $color ) {
	if ( $unhashed = sanitize_hex_color_no_hash( $color ) ) {
		return '#' . $unhashed;
	}

	return $color;
}
endif;
