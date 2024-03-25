<?php
/**
 * @package Blink
 */

if ( ! function_exists( 'blink_customizer_social' ) ) :
/**
 * Configure settings and controls for the Social section
 *
 * @since 1.0.0
 *
 * @param  object    $wp_customize    The global customizer object.
 * @param  string    $section         The section name.
 * @return void
 */
function blink_customizer_social( $wp_customize, $section ) {
	$priority       = new Blink_Prioritizer( 600, 5 );
	$control_prefix = 'blink_';
	$setting_prefix = str_replace( $control_prefix, '', $section );
	$section = ( blink_is_wpcom() ) ? 'blink_theme' : $section;

	// Facebook
	$setting_id = $setting_prefix . '-facebook';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Facebook', // brand names not translated
			'type'     => 'url',
			'priority' => $priority->add()
		)
	);

	// Twitter
	$setting_id = $setting_prefix . '-twitter';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Twitter', // brand names not translated
			'type'     => 'url',
			'priority' => $priority->add()
		)
	);

	// Google +
	$setting_id = $setting_prefix . '-google-plus';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Google +', // brand names not translated
			'type'     => 'url',
			'priority' => $priority->add()
		)
	);

	// LinkedIn
	$setting_id = $setting_prefix . '-linkedin';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'LinkedIn', // brand names not translated
			'type'     => 'url',
			'priority' => $priority->add()
		)
	);

	// Instagram
	$setting_id = $setting_prefix . '-instagram';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Instagram', // brand names not translated
			'type'     => 'url',
			'priority' => $priority->add()
		)
	);

	// Flickr
	$setting_id = $setting_prefix . '-flickr';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Flickr', // brand names not translated
			'type'     => 'url',
			'priority' => $priority->add()
		)
	);

	// YouTube
	$setting_id = $setting_prefix . '-youtube';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'YouTube', // brand names not translated
			'type'     => 'url',
			'priority' => $priority->add()
		)
	);

	// Vimeo
	$setting_id = $setting_prefix . '-vimeo';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Vimeo', // brand names not translated
			'type'     => 'url',
			'priority' => $priority->add()
		)
	);

	// Pinterest
	$setting_id = $setting_prefix . '-pinterest';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Pinterest', // brand names not translated
			'type'     => 'url',
			'priority' => $priority->add()
		)
	);

	// Dribbble
	$setting_id = $setting_prefix . '-dribbble';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Dribbble', // brand names not translated
			'type'     => 'url',
			'priority' => $priority->add()
		)
	);

	// Email
	$setting_id = $setting_prefix . '-email';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => blink_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Email', 'blink' ),
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// RSS options are not available on WP.com
	if ( ! blink_is_wpcom() ) {
		// Hide RSS
		$setting_id = $setting_prefix . '-hide-rss';
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
				'label'    => __( 'Hide default RSS feed link', 'blink' ),
				'type'     => 'checkbox',
				'priority' => $priority->add(),
			)
		);

		// Custom RSS
		$setting_id = $setting_prefix . '-custom-rss';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => blink_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Custom RSS URL (replaces default)', 'blink' ),
				'type'     => 'url',
				'priority' => $priority->add()
			)
		);
	}
}
endif;
