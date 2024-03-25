<?php
/**
 * @package Blink
 */

if ( ! function_exists( 'blink_customizer_layout' ) ) :
/**
 * Configure settings and controls for the Layout section.
 *
 * @since  1.0.0.
 *
 * @param  object    $wp_customize    The global customizer object.
 * @param  string    $section         The section name.
 * @return void
 */
function blink_customizer_layout( $wp_customize, $section ) {
	$priority       = new Blink_Prioritizer( 200, 5 );
	$control_prefix = 'blink_';
	$setting_prefix = str_replace( $control_prefix, '', $section );
	$section = ( blink_is_wpcom() ) ? 'blink_theme' : $section;

	// Site layut
	$setting_id = $setting_prefix . '-site';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default' => blink_get_default( $setting_id ),
			'type'    => 'theme_mod',
			'transport'  => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Layout_Control(
			$wp_customize,
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Site Layout', 'blink' ),
				'choices' => array(
					'odd'  => '1-2-1-2',
					'even' => '1-1-1-1',
					'hero' => '1-2-2-2',
					'half' => '2-2-2-2',
				),
				'priority' => $priority->add()
			)
		)
	);

	// Hide Post author info
	$setting_id = $setting_prefix . '-author-info';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Hide Author info box on single posts', 'blink' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Hide post navigation
	$setting_id = $setting_prefix . '-post-navigation';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Hide post navigation on single pages', 'blink' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Hide page meta
	$setting_id = $setting_prefix . '-page-meta';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Hide post meta on pages', 'blink' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Hide page meta
	$setting_id = $setting_prefix . '-uppercase-title';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Uppercase Post / Page Title', 'blink' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Transparent header.
	$setting_id = $setting_prefix . '-header-overlay';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings'    => $setting_id,
			'section'     => $section,
			'label'       => __( 'Enable transparent header', 'blink' ),
			'type'        => 'checkbox',
			'priority'    => $priority->add(),
		)
	);

	// Disabled overlay header on posts page.
	$setting_id = $setting_prefix . '-header-overlay-disabled';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Disabled Overlay Header at Posts page', 'blink' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Add border to posts
	$setting_id = $setting_prefix . '-post-border';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings'    => $setting_id,
			'section'     => $section,
			'label'       => __( 'Add Border to Posts Page', 'blink' ),
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 40,
				'step' => 10,
			),
			'priority'    => $priority->add(),
		)
	);
}
endif;

if ( ! function_exists( 'blink_customizer_layout_css' ) ) :
/**
 * Add CSS for customizer layout picker.
 *
 * @return void
 */
function blink_customizer_layout_css() {
	?>

	<style type="text/css">
		#customize-control-blink_layout-post-border input[type="range"] {
			padding: 0;
		}
		.customizer-control-row {
			position: relative;
			display: inline-block;
			vertical-align: top;
			width: 125px;
			height: 75px;
			overflow: hidden;
			margin: 0 0 7px 0;
			font: 0/0 a;
		}
		.customizer-control-row:nth-child(2n+2) {
			margin-left: 4px;
		}

		.customizer-control-row:nth-child(1) label { background-position: 0 0; }
		.customizer-control-row:nth-child(2) label { background-position: -268px 0; }
		.customizer-control-row:nth-child(3) label { background-position: -133px 0; }
		.customizer-control-row:nth-child(4) label { background-position: -399px 0; }

		.customizer-control-row input {
			position: absolute;
			width: 100%;
			height: 100%;
			opacity: 0;
		}
		.customizer-control-row input,
		.customizer-control-row label {
			width: 100%;
		}
		.customizer-control-row label {
			height: 75px;
			background: url("<?php echo get_template_directory_uri(); ?>/images/layout-images.png") no-repeat;
			background-size: 521px 73px;
			display: block;
			box-sizing: border-box;
		}

		.customizer-control-row input[type="radio"]:checked + label {
			background-image: url("<?php echo get_template_directory_uri(); ?>/images/layout-images-gray.png");
		}

		@media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2/1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
			.customizer-control-row label {
				background-image: url('<?php echo get_template_directory_uri(); ?>/images/layout-images-2x.png');
			}
			.customizer-control-row input[type="radio"]:checked + label {
				background-image: url('<?php echo get_template_directory_uri(); ?>/images/layout-images-gray-2x.png');
			}
		}
	</style>

	<?php
}
endif;

add_action( 'customize_controls_print_scripts', 'blink_customizer_layout_css' );
