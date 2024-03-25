<?php
/**
 * Tweak Customizer's Title Tagline settings.
 *
 * @package Blink
 */

if ( ! function_exists( 'blink_customizer_title_tagline' ) ) :
	/**
	 * Configure settings and controls for the Title Tagline section.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	function blink_customizer_title_tagline() {
		global $wp_customize;

		$section        = 'title_tagline';
		$control_prefix = 'blink_';
		$setting_prefix = str_replace( $control_prefix, '', $section );

		// Light Logo.
		$setting_id = $setting_prefix . '-logo-light';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => blink_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$control_prefix . $setting_id,
				array(
					'settings'    => $setting_id,
					'section'     => $section,
					'label'       => __( 'Logo (Light)', 'blink' ),
					'description' => __( 'Used when &lsquo;General Settings &rarr; Overlay Header&rsquo; is checked', 'blink' ),
				)
			)
		);

	}
endif;

add_action( 'customize_register', 'blink_customizer_title_tagline', 20 );
