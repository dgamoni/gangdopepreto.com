<?php

function gangdopepreto_scripts_child() {

	// wp_register_script( 'bootstrap_js', CORE_URL . '/js/bootstrap.min.js', array( 'jquery' ), '3', true );
	// wp_enqueue_script( 'bootstrap_js');

	// wp_register_script( 'bootstrap_slider_js', CORE_URL . '/js/bootstrap-slider.js', array( 'jquery' ), '8', true );
	// wp_enqueue_script( 'bootstrap_slider_js');

	wp_register_style('custom_css', CORE_URL .'/js/custom.css', array(),rand(), 'all');
	wp_enqueue_style('custom_css');

	wp_register_script( 'custom_js', CORE_URL . '/js/custom.js', array( 'jquery' ), '2', true );
	wp_enqueue_script( 'custom_js');



	
	//wp_localize_script( 'ipopi-function-child-js', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
}
add_action( 'wp_enqueue_scripts', 'gangdopepreto_scripts_child', 101 );

function misha_my_load_more_scripts() {
 
	global $wp_query; 

	if( is_home() ):
 
		// In most cases it is already included on the page and this line can be removed
		wp_enqueue_script('jquery');
	 
		// register our main script but do not enqueue it yet
		wp_register_script( 'my_loadmore', CORE_URL . '/js/myloadmore.js', array('jquery'),rand() );
	 
		// now the most interesting part
		// we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
		// you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
		wp_localize_script( 'my_loadmore', 'misha_loadmore_params', array(
			'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
			'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
			'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
			'max_page' => $wp_query->max_num_pages
		) );
	 
	 	wp_enqueue_script( 'my_loadmore' );

	endif;
}
 
add_action( 'wp_enqueue_scripts', 'misha_my_load_more_scripts' );