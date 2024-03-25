<?php
/**
 * Tweak Customizer's Background settings.
 *
 * @package Blink
 */

if ( ! function_exists( 'blink_customizer_background' ) ) :
/**
 * Configure settings and controls for the Background section.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function blink_customizer_background() {
	global $wp_customize;

	$priority       = new Blink_Prioritizer( 10, 5 );
	$section        = 'background_image';

	// Rename Background Image section to Background.
	$wp_customize->get_section( $section )->title = __( 'Background', 'blink' );

	// Move Background Color to Background section.
	$wp_customize->get_control( 'background_color' )->section = $section;
}
endif;

add_action( 'customize_register', 'blink_customizer_background', 20 );
