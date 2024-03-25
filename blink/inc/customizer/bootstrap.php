<?php
/**
 * Blink Theme Customizer.
 *
 * @package Blink
 */

if ( ! function_exists( 'blink_customizer_init' ) ) :
/**
 * Load the customizer files and enqueue scripts.
 *
 * @return void
 */
function blink_customizer_init() {
	$path = get_template_directory() . '/inc/customizer/';

	// Always load required files
	require_once( $path . 'helper-css.php' );
	require_once( $path . 'helper-defaults.php' );
	require_once( $path . 'helper-display.php' );

	if ( ! blink_is_wpcom() ) {
		require_once( $path . 'helper-fonts.php' );
	}

	// Hook up functions
	add_action( 'customize_register', 'blink_customizer_add_sections' );
}
endif;

add_action( 'after_setup_theme', 'blink_customizer_init' );

if ( ! function_exists( 'blink_customizer_add_sections' ) ) :
/**
 * Add sections and controls to the customizer.
 *
 * Hooked to 'customize_register' via blink_customizer_init().
 *
 * @since  1.0.0.
 *
 * @param  WP_Customize_Manager    $wp_customize    Theme Customizer object.
 * @return void
 */
function blink_customizer_add_sections( $wp_customize ) {
	$path         = get_template_directory() . '/inc/customizer/';
	$section_path = $path . 'sections/';

	// Get the custom controls
	require_once( $path . 'helper-controls.php' );

	// Modifications for existing sections
	require_once( $section_path . 'background.php' );
	require_once( $section_path . 'navigation.php' );
	require_once( $section_path . 'title-tagline.php' );
	require_once( $section_path . 'header-image.php' );

	// List of new sections to add
	$sections = array(
		'color'  => array( 'title' => __( 'Colors', 'blink' ), 'path' => $section_path ),
		'fonts'  => array( 'title' => __( 'Fonts', 'blink' ), 'path' => $section_path ),
		'layout' => array( 'title' => __( 'General Settings', 'blink' ), 'path' => $section_path ),
		'footer' => array( 'title' => __( 'Footer', 'blink' ), 'path' => $section_path ),
		'social' => array( 'title' => __( 'Social Profiles &amp; RSS', 'blink' ), 'description' => __( 'Enter the complete URL to your profile for each service below that you would like to share.', 'blink' ), 'path' => $section_path ),
	);
	$sections = apply_filters( 'blink_customizer_sections', $sections );

	// Remove sections for WP.com
	if ( blink_is_wpcom() ) {
		unset( $sections['logo'] );
		unset( $sections['color'] );
	}

	// Priority for first section
	$priority = new Blink_Prioritizer( 200, 10 );

	// Add the "Theme" section for WP.com
	if ( blink_is_wpcom() ) {
		$wp_customize->add_section(
			'blink_theme',
			array(
				'title'    => __( 'Theme Options', 'blink' ),
				'priority' => $priority->add(),
			)
		);
	}

	// Add and populate each section, if it exists
	foreach ( $sections as $section => $data ) {
		$file = trailingslashit( $data[ 'path' ] ) . $section . '.php';
		if ( file_exists( $file ) ) {
			// First load the file
			require_once( $file );

			// Then add the section
			$section_callback = 'blink_customizer_';
			$section_callback .= ( strpos( $section, '-' ) ) ? str_replace( '-', '_', $section ) : $section;

			if ( function_exists( $section_callback ) ) {
				$section_id = 'blink_' . esc_attr( $section );

				// Only add separate sections for self-hosted sites
				if ( ! blink_is_wpcom() ) {
					// Sanitize the section title
					if ( ! isset( $data[ 'title' ] ) || ! $data[ 'title' ] ) {
						$data[ 'title' ] = ucfirst( esc_attr( $section ) );
					}

					// Add section
					$wp_customize->add_section(
						$section_id,
						array(
							'title'       => $data[ 'title' ],
							'priority'    => $priority->add(),
							'description' => ( isset( $data['description'] ) ) ? $data['description'] : false,
						)
					);
				}

				// Callback to populate the section
				call_user_func_array(
					$section_callback,
					array(
						$wp_customize,
						$section_id,
					)
				);
			}
		}
	}
}
endif;

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function blink_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
}
add_action( 'customize_register', 'blink_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function blink_customize_preview_js() {
	wp_enqueue_script( 'blink_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'blink_customize_preview_js' );

if ( ! function_exists( 'blink_add_customizations' ) ) :
/**
 * Make sure the 'blink_css' action only runs once.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function blink_add_customizations() {
	do_action( 'blink_css' );
}
endif;

add_action( 'admin_init', 'blink_add_customizations' );

if ( ! function_exists( 'blink_display_customizations' ) ) :
/**
 * Generates the style tag and CSS needed for the theme options.
 *
 * By using the "blink_css" filter, different components can print CSS in the header. It is organized this way to
 * ensure that there is only one "style" tag and not a proliferation of them.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function blink_display_customizations() {
	do_action( 'blink_css' );

	// Echo the rules
	$css = blink_get_css()->build();
	// if ( ! empty( $css ) ) {
		echo "\n<!-- Begin Blink Custom CSS -->\n<style type=\"text/css\" id=\"blink-custom-css\">\n";
		echo $css;
		echo "\n</style>\n<!-- End Blink Custom CSS -->\n";
	// }
}
endif;

add_action( 'wp_head', 'blink_display_customizations', 11 );

if ( ! function_exists( 'blink_ajax_display_customizations' ) ) :
/**
 * Generates the theme option CSS as an Ajax response
 *
 * @since  1.0.0.
 *
 * @return void
 */
function blink_ajax_display_customizations() {
	// Make sure this is an Ajax request
	if ( ! defined( 'DOING_AJAX' ) || true !== DOING_AJAX ) {
		return;
	}

	// Set the content type
	header( 'Content-Type: text/css' );

	// Echo the rules
	echo blink_get_css()->build();

	// End the Ajax response
	die();
}
endif;

add_action( 'wp_ajax_blink-css', 'blink_ajax_display_customizations' );

if ( ! function_exists( 'blink_mce_css' ) ) :
/**
 * Make sure theme option CSS is added to TinyMCE last, to override other styles.
 *
 * @since  1.0.0.
 *
 * @param  string    $stylesheets    List of stylesheets added to TinyMCE.
 * @return string                    Modified list of stylesheets.
 */
function blink_mce_css( $stylesheets ) {
	global $post_id;
	if ( ! isset( $post_id ) ) {
		$post_id = 0;
	}

	$ajax = admin_url( 'admin-ajax.php' );
	$ajax = add_query_arg( 'action', 'blink-css', $ajax );
	$ajax = add_query_arg( 'post_id', $post_id, $ajax );
	$stylesheets .= ',' . $ajax;

	return $stylesheets;
}
endif;

add_filter( 'mce_css', 'blink_mce_css', 99 );


if ( ! function_exists( 'blink_customizer_layout_control' ) ) :
/**
 * Layout Picker Control
 *
 * Attach the custom layout picker control to the `customize_register` action
 * so the WP_Customize_Control class is initiated.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function blink_customizer_layout_control( $wp_customize ) {
	class WP_Customize_Layout_Control extends WP_Customize_Control {
		public $type = 'layout';

		public function render_content() {
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<ul>
			<?php

			foreach ( $this->choices as $key => $value ) {
				?>
				<li class="customizer-control-row">
					<input type="radio" value="<?php echo esc_attr( $key ) ?>" name="<?php echo $this->id; ?>" <?php echo $this->link(); ?> <?php if ( $this->value() === $key ) echo 'checked="checked"'; ?>>
					<label for="<?php echo $this->id;  ?>[key]"><?php echo $value; ?></label>
				</li>
				<?php
			}

			?> </ul> <?php
		}
	}
}
endif;

add_action( 'customize_register', 'blink_customizer_layout_control', 1, 1 );
