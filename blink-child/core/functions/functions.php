<?php


function get_list_cat_icons($id) {
	
	$args = array(
		'taxonomy' => array( 'category' )
		);

	$myterms = get_terms( $args );

	foreach( $myterms as $term ){
		$active = '';
		if($term->term_id == $id ){
			$active = 'term_active';
		}
		//print_r($term);
		$term_link = get_term_link($term->term_id, 'category');
		$icon = get_field('gangdopepreto_category_icons', 'category_' . $term->term_id);
		echo "<a href='". $term_link ."'><img class='".$active."' src='" .$icon['url']. "'></a>";
	}
}

function misha_loadmore_ajax_handler(){
 
	// prepare our arguments for the query
	$args = json_decode( stripslashes( $_POST['query'] ), true );
	$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
	$args['post_status'] = 'publish';
 
	// it is always better to use WP_Query but not here
	query_posts( $args );
 	$full=1;
	if( have_posts() ) :
			global $counts;
			$count = $GLOBALS['wp_query']->post_count; 
			$paged = get_query_var( 'paged', 1 );
			$counts = 1;
			


		// run the loop
		while( have_posts() ): the_post();
 


			// look into your theme code how the posts are inserted, but you can use your own HTML of course
			// do you remember? - my example is adapted for Twenty Seventeen theme
			get_template_part( 'partials/content', 'more' );
			// for the test purposes comment the line above and uncomment the below one
			// the_title();
			
			// var_dump($count);
			// var_dump($counts);
			// var_dump($GLOBALS['wp_query']->found_posts);


			if ($count == $counts && $paged > 0 && $count % 2 == 0) {
				get_template_part( 'partials/content_add');
			} else {

			}
			$counts++; 
 
		endwhile;
 
	endif;
	die; // here we exit the script and even no wp_reset_query() required!
}
 
 
 
add_action('wp_ajax_loadmore', 'misha_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'misha_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}