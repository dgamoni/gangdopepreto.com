<?php

function gangdopepreto_scripts_child() {

	// wp_register_script( 'bootstrap_js', CORE_URL . '/js/bootstrap.min.js', array( 'jquery' ), '3', true );
	// wp_enqueue_script( 'bootstrap_js');

	// wp_register_script( 'bootstrap_slider_js', CORE_URL . '/js/bootstrap-slider.js', array( 'jquery' ), '8', true );
	// wp_enqueue_script( 'bootstrap_slider_js');

	// wp_register_style('bootstrap_slider_css', CORE_URL .'/js/bootstrap-slider.min.css', array(),null, 'all');
	// wp_enqueue_style('bootstrap_slider_css');

	wp_register_script( 'custom_js', CORE_URL . '/js/custom.js', array( 'jquery' ), '2', true );
	wp_enqueue_script( 'custom_js');



	
	//wp_localize_script( 'ipopi-function-child-js', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
}
add_action( 'wp_enqueue_scripts', 'gangdopepreto_scripts_child', 101 );

