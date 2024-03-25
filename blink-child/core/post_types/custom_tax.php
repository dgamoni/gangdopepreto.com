<?php 
if ( ! function_exists( 'custom_tax_regiao' ) ) {

// Register Custom Taxonomy
function custom_tax_regiao() {

	$labels = array(
		'name'                       => _x( 'Regiao', 'Região', 'gangdopepreto' ),
		'singular_name'              => _x( 'Regiao', 'Região', 'gangdopepreto' ),
		'menu_name'                  => __( 'Regiao', 'gangdopepreto' ),
		'all_items'                  => __( 'All Items', 'gangdopepreto' ),
		'parent_item'                => __( 'Parent Item', 'gangdopepreto' ),
		'parent_item_colon'          => __( 'Parent Item:', 'gangdopepreto' ),
		'new_item_name'              => __( 'New Item Name', 'gangdopepreto' ),
		'add_new_item'               => __( 'Add New Item', 'gangdopepreto' ),
		'edit_item'                  => __( 'Edit Item', 'gangdopepreto' ),
		'update_item'                => __( 'Update Item', 'gangdopepreto' ),
		'view_item'                  => __( 'View Item', 'gangdopepreto' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'gangdopepreto' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'gangdopepreto' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'gangdopepreto' ),
		'popular_items'              => __( 'Popular Items', 'gangdopepreto' ),
		'search_items'               => __( 'Search Items', 'gangdopepreto' ),
		'not_found'                  => __( 'Not Found', 'gangdopepreto' ),
		'no_terms'                   => __( 'No items', 'gangdopepreto' ),
		'items_list'                 => __( 'Items list', 'gangdopepreto' ),
		'items_list_navigation'      => __( 'Items list navigation', 'gangdopepreto' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		//'rewrite'          			 => true,
	    'rewrite' => array(
            'slug' => 'regiao', // This controls the base slug that will display before each term (renamed to specialty from specialties)
            'with_front' => false, // Don't display the category base before "/specialties/"
            'hierarchical' => false
        ),
	);
	register_taxonomy( 'regiao', array( 'post' ), $args );

}
add_action( 'init', 'custom_tax_regiao', 0 );

} 

