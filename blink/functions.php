<?php
/**
 * Blink functions and definitions
 *
 * @package Blink
 */

/**
 * The current version of the theme.
 *
 * @since 1.0.
 */
define( 'BLINK_VERSION', '1.1.5' );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 970; /* pixels */
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/bootstrap.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load auto updater
 *
 * @since 1.0.2.
 */
require get_template_directory() . '/inc/updater/theme-updater.php';

if ( ! function_exists( 'blink_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function blink_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Blink, use a find and replace
	 * to change 'blink' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'blink', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	$custom_header_args = array(
		'width'       => 1170,
		'height'      => 600,
		'uploads'     => true,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => false,
		'video'       => true,
	);
	add_theme_support( 'custom-header', $custom_header_args );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'blink' ),
	) );

	/**
	 * Add theme support for site logos.
	 */
	add_theme_support( 'site-logo', array(
		'header-text' => array(
			'site-title',
			'site-description',
		),
		'size' => 'blink_logo',
	) );

	/**
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', array(
		'default-color'      => blink_get_default( 'background_color' ),
		'default-image'      => blink_get_default( 'background_image' ),
		'default-repeat'     => blink_get_default( 'background_repeat' ),
		'default-position-x' => blink_get_default( 'background_position_x' ),
		'default-attachment' => blink_get_default( 'background_attachment' ),
	) );

	// Editor styles
	add_editor_style( array( 'css/editor-style.css', blink_font_url() ) );
}
endif; // blink_setup
add_action( 'after_setup_theme', 'blink_setup' );

if ( ! function_exists( 'blink_image_sizes' ) ) :
/**
 * Register custom image sizes
 *
 * @since 1.0.0.
 *
 * @return void
 */
function blink_image_sizes() {
	$defaults = array(
		'blink_half' => array( 850, 850, true ),
		'blink_full' => array( 1700, 9999, false ),
		'blink_logo' => array( 500, 9999, false ),
	);

	$image_sizes = apply_filters( 'blink_image_sizes', $defaults );

	foreach ( $image_sizes as $name => $prop ) {
		$crop = ( isset( $prop[2] ) ) ? $prop[2] : false;
		add_image_size( $name, $prop[0], $prop[1], $crop );
	}
}
endif;

add_action( 'after_setup_theme', 'blink_image_sizes' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function blink_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer Widgets 1', 'blink' ),
		'id'            => 'footer-widgets-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Widgets 2', 'blink' ),
		'id'            => 'footer-widgets-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'blink_widgets_init' );

if ( ! function_exists( 'blink_is_wpcom' ) ) :
/**
 * Whether or not the current environment is WordPress.com.
 *
 * @since  1.0.
 *
 * @return bool
 */
function blink_is_wpcom() {
	return ( defined( 'IS_WPCOM' ) && true === IS_WPCOM );
}
endif;

/**
 * The suffix to use for scripts.
 */
if ( ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) || blink_is_wpcom() ) {
	define( 'BLINK_SUFFIX', '' );
} else {
	define( 'BLINK_SUFFIX', '.min' );
}

function blink_font_url() {
	$fonts   = array();
	$subsets = 'latin,latin-ext';

	/**
	 * Translators: If there are characters in your language that are not supported
	 * by Montserrat, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'blink' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/**
	 * Translators: If there are characters in your language that are not supported
	 * by Rufina, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Rufina font: on or off', 'blink' ) ) {
		$fonts[] = 'Rufina:400,700';
	}

	/*
	 * Translators: To add an additional character subset specific to your language,
	 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'blink' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	// Bail if fonts array is empty
	if ( empty( $fonts ) ) {
		return false;
	}

	$font_uri = add_query_arg( array(
		'family' => urlencode( implode( '|', $fonts ) ),
		'subset' => urlencode( $subsets ),
	), '//fonts.googleapis.com/css' );

	return apply_filters( 'blink_font_uri', $font_uri );
}

/**
 * Enqueue scripts and styles.
 */
function blink_scripts() {
	// Suffix for minified script versions
	$sfx = ( blink_is_wpcom() || ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ) ? '' : '.min';
	define( 'BLINK_SCRIPT_SUFFIX', $sfx );

	// Dependencies arrays
	$style_dependencies = array();
	$script_dependencies = array( 'jquery' );

	$fonts = blink_font_url();

	if ( $fonts ) {
		// Enqueue the fonts
		wp_enqueue_style( 'blink-google-fonts', $fonts, array(), BLINK_VERSION );

		$style_dependencies[] = 'blink-google-fonts';
	}

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/css/genericons/genericons.css', $style_dependencies, '3.4.1' );
	$style_dependencies[] = 'genericons';

	// Main stylesheet
	wp_enqueue_style( 'blink-style', get_stylesheet_uri(), $style_dependencies, BLINK_VERSION, 'screen' );

	// Frontend script
	wp_enqueue_script( 'blink-frontend', get_template_directory_uri() . '/js/frontend' . BLINK_SUFFIX . '.js', $script_dependencies, BLINK_VERSION, true );

	// FitVids
	wp_enqueue_script( 'blink-fitvids', get_template_directory_uri() . '/js/lib/fitvids/jquery.fitvids' . BLINK_SUFFIX . '.js', array( 'jquery' ), '1.1', true );
	blink_localize_fitvids( 'blink-fitvids' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'blink_scripts' );

if ( ! function_exists( 'blink_localize_fitvids' ) ) :
/**
 * Pass data for FitVids script to a JS object.
 *
 * @since 1.0.0.
 *
 * @param  string    $handle    The registered ID of the FitVids script.
 * @return void
 */
function blink_localize_fitvids( $handle ) {
	// Default selectors
	$selector_array = array(
		"iframe[src*='www.viddler.com']",
		"iframe[src*='money.cnn.com']",
		"iframe[src*='www.educreations.com']",
		"iframe[src*='//blip.tv']",
		"iframe[src*='//embed.ted.com']",
		"iframe[src*='//www.hulu.com']",
	);

	// Filter selectors
	$selector_array = apply_filters( 'blink_fitvids_custom_selectors', $selector_array );

	// Compile selectors
	$fitvids_custom_selectors = array(
		'selectors' => implode( ',', $selector_array )
	);

	// Send to the script
	wp_localize_script(
		$handle,
		'blinkFitVids',
		$fitvids_custom_selectors
	);
}
endif;

if ( class_exists( 'Subtitles' ) &&  method_exists( 'Subtitles', 'subtitle_styling' ) ) {
	remove_action( 'wp_head', array( Subtitles::getInstance(), 'subtitle_styling' ) );
}
