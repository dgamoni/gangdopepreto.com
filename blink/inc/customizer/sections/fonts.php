<?php
/**
 * @package Blink
 */

if ( ! function_exists( 'blink_customizer_fonts' ) ) :
/**
 * Configure settings and controls for the Colors section.
 *
 * @since  1.0.0.
 *
 * @param  object    $wp_customize    The global customizer object.
 * @param  string    $section         The section name.
 * @return void
 */
function blink_customizer_fonts( $wp_customize, $section ) {
	$priority       = new Blink_Prioritizer( 200, 5 );
	$control_prefix = 'blink_';
	$setting_prefix = str_replace( $control_prefix, '', $section );
	$section = ( blink_is_wpcom() ) ? 'blink_theme' : $section;

	$google_fonts = blink_all_font_choices();

	// Header Font
	$setting_id = $setting_prefix . '-header';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'blink_sanitize_font_choice',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Header Font', 'blink' ),
			'type'     => 'select',
			'choices'  => $google_fonts,
			'priority' => $priority->add(),
		)
	);

	// Body Font
	$setting_id = $setting_prefix . '-body';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'blink_sanitize_font_choice',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Body Font', 'blink' ),
			'type'     => 'select',
			'choices'  => $google_fonts,
			'priority' => $priority->add(),
		)
	);

	// Body Font
	$setting_id = $setting_prefix . '-subset';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'blink_sanitize_font_choice',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings'    => $setting_id,
			'section'     => $section,
			'label'       => __( 'Character Subset', 'blink' ),
			'type'        => 'select',
			'choices'     => blink_get_google_font_subsets(),
			'priority'    => $priority->add(),
			'description' => __( 'Not all fonts provide each of these subsets.', 'blink' ),
		)
	);
}
endif;
