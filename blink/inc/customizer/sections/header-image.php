<?php
/**
 * Tweak Customizer's Header Image settings.
 *
 * @package Blink
 */

if ( ! function_exists( 'blink_customizer_header_image' ) ) :
	/**
	 * Configure settings and controls for the Header Image section.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	function blink_customizer_header_image() {
		global $wp_customize;

		$section        = 'header_image';
		$control_prefix = 'blink_';
		$setting_prefix = str_replace( $control_prefix, '', $section );

		// Header Image Text Logo.
		$setting_id = $setting_prefix . '-text';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => blink_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Header Image Text', 'blink' ),
				'type'     => 'textarea',
			)
		);

		// Overlay Color.
		$setting_id = $setting_prefix . '-color';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => blink_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$control_prefix . $setting_id,
				array(
					'settings' => $setting_id,
					'section'  => $section,
					'label'    => __( 'Overlay Color', 'blink' ),
				)
			)
		);

		// Overlay Opacity.
		$setting_id = $setting_prefix . '-opacity';
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
				'settings'    => $setting_id,
				'section'     => $section,
				'label'       => __( 'Overlay Opacity', 'blink' ),
				'type'        => 'range',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 5,
				),
			)
		);

	}
endif;

add_action( 'customize_register', 'blink_customizer_header_image', 20 );
