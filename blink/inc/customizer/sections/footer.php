<?php
/**
 * @package Blink
 */

if ( ! function_exists( 'blink_customizer_footer' ) ) :
/**
 * Configure settings and controls for the Footer section
 *
 * @since  1.0.0.
 *
 * @param  object    $wp_customize    The global customizer object.
 * @param  string    $section         The section name.
 * @return void
 */
function blink_customizer_footer( $wp_customize, $section ) {
	$priority       = new Blink_Prioritizer( 500, 5 );
	$control_prefix = 'blink_';
	$setting_prefix = str_replace( $control_prefix, '', $section );
	$section = ( blink_is_wpcom() ) ? 'blink_theme' : $section;

	// Footer text
	$setting_id = $setting_prefix . '-text';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'blink_sanitize_text',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Footer Text', 'blink' ),
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// Footer Credit text
	$setting_id = $setting_prefix . '-credits';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
			'transport' => 'refresh',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Hide theme credit', 'blink' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);
}
endif;
