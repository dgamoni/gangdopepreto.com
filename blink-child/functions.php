<?php
/**
 * Blink Child Theme
 *
 * Place any custom functionality/code snippets here.
 *
 * @since Blink Child 1.0
 */

 function blink_widgets_init_child() {
	register_sidebar( array(
		'name'          => __( 'Post sidebar', 'blink' ),
		'id'            => 'sidebar-right',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'blink_widgets_init_child' );
 
function blink_child_styles() {
    wp_enqueue_style( 'blink-parent-theme', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'blink_child_styles' );

add_filter('acf/settings/google_api_key', function () {
    return 'AIzaSyCKKuAw6gdLDepGFUTJmdWuM6XMkWAAm_o';
});

require_once 'core/load.php'; 


add_action('wp_footer', 'add_custom_css');
function add_custom_css() {
	global $current_user;
	?>
	<script>

	jQuery(window).load(function() {

function getNatural($mainImage) {
    var mainImage = $mainImage[0],
        d = {};

    if (mainImage.naturalWidth === undefined) {
        var i = new Image();
        i.src = mainImage.src;
        d.oWidth = i.width;
        d.oHeight = i.height;
    } else {
        d.oWidth = mainImage.naturalWidth;
        d.oHeight = mainImage.naturalHeight;
    }
    return d;
}

	    jQuery('.slideshow-slide img').each(function() {
	    	var href = jQuery(this).attr('src');
	    	//console.log(href);
			var img = jQuery(this);
			var naturalDimension = getNatural(img);
			//console.log(naturalDimension.oWidth);
			//console.log(naturalDimension.oHeight);
			var width = naturalDimension.oWidth;
			var height = naturalDimension.oHeight;

	        if(width < height){
	        	//console.log('yes');
	        	jQuery(this).addClass('vertical_image');
	        } else {
	        	//console.log('no');
	        }
	     });		
	});


		jQuery(document).ready(function($) {



// $("#ex4").slider({
//     ticks: [1, 2, 3, 4, 5],
//     ticks_labels: ['1', '2', '3', '4', '5'],
//     tooltip: 'hide',
//     value: 3,
// 	//range: false,

// });
// $("#ex4").on('change', function(event) {
// 	console.log($("#ex4").slider("getValue"));
// });
		});
	</script>
	<style>
.slideshow-slide img {
    height: auto;
    width: 100%;
    object-fit: cover;
}
.slideshow-slide img.vertical_image {
    object-fit: contain;
}
.post-add .text-container {
	display: none;
}
.post-add.post-grid {
	background-color: white;
}
.post-add  .post-grid-content {
	    text-align: center;
    top: 18%;
}
.post-add.post-grid:hover .post-grid-content {
    background: inherit;
}
.post-cover-inside .edit-link {
	display: none;
}

.has-post-thumbnail .page-cover-inside  {
    position: absolute;
    z-index: 3;
    right: 50px;
    bottom: 50px;
    left: 50px;
    max-width: 970px;
    margin-right: auto;
    margin-left: auto;
    padding-bottom: inherit;	
}
.entry-content.search-template-654 .searchandfilter ul {
	margin: 0;
}
.search-template-654 input[type="text"] {
    width: 100%;
}
.search-template-654 .page-cover-inside {
    padding-top: 20px;
    padding-top: 7rem;
}
.search-template-654 .page-header, .search-template-654 .entry-content {
	padding-right: 0;
    padding-left: 0;
}
.search-filter-results .no-results .page-content {
	display: none;
}
.archive.category .archive-title {
    margin-top: 0 !important;
}
.category-icons-wrap {
    position: relative;
    max-width: 1020px;
    margin-right: auto;
    margin-left: auto;
    padding-right: 25px;
    padding-left: 25px;
    text-align: center;
}
.category-icons-wrap a {

}
.category-icons-wrap img {
	
}
.category-icons-wrap img.term_active {
	
}
.has-post-thumbnail .page-cover {
    width: 100%;
    height: 100vh;
}
.gangdopepreto_avaliacao_img img {
	margin-right: 5px;
}
.group_first {
	margin-left: -19px;
    margin-right: -100px;
}
.gangdopepreto_avaliacao {
	    text-align: center;
	    border-top: 1px solid black;
    	padding-top: 20px;
}
.gangdopepreto_fields_wrap .gangdopepreto_avaliacao p {
    /*font-size: 26px;*/
    font-size: 29px;
    padding-bottom: 30px;
/*    display: flex; */
    justify-content: center;
	font-family: "Lato","Helvetica Neue",Helvetica,Arial,sans-serif;
}
.gangdopepreto_avaliacao_img {
	margin-left: 7px;
}
.gang_field_title {
	margin-bottom: 10px;
    display: inline-block;
	font-family: "Lato","Helvetica Neue",Helvetica,Arial,sans-serif;
}
.gangdopepreto_fields_wrap_2col {
    display: flex;
    flex-wrap: wrap;
}
.gangdopepreto_fields_wrap_2col .gangdopepreto_field_group {
	flex-grow: 1;
    width: 33%;
    text-align: center;
    margin-bottom: 40px;	
}
.gangdopepreto_fields_wrap_3col {
    display: flex;
    flex-wrap: wrap;
    border-bottom: 1px solid black;
    margin-bottom: 30px;
}
.gangdopepreto_fields_wrap_3col .gangdopepreto_field {
	flex-grow: 1;
    /*width: 20%;*/
    text-align: center;
    margin-bottom: 34px;
    padding-right: 5px;	
}
.gangdopepreto_fields_wrap p, .gangdopepreto_fields_wrap_left p {
	font-size: 13px;
}
.num_rating {
    text-indent: -10000px;
    display: inline-block;
    border: 1px solid black;
    border-radius: 50%;
    /*width: 14px;*/
    /*height: 14px;*/
    width: 9px;
    height: 9px;    
    margin-right: 5px;
}
.num_rating.num_rating_black {
    background-color: black;
}

/*template*/
.page-template-template-pesquisar .page-cover {
    margin-bottom: 0px;
}


/*map*/

.iwm_map_canvas {
    left: -402px;
    float: left;
 }

.search-content {
	position: absolute;
    right: 0;
    top: 0;
    width: 650px;
    height: 600px;
    padding-left: 50px;
    border-left: 1px solid black;
	}

/*search filter*/

.searchandfilter .post_meta ul li {
	   display:inline-block;
    text-align:left;
    position: relative;
}
.searchandfilter .post_meta ul li:last-child .radio_separate {
	display: none;
}
.default_radio {
	display: none;
}
.searchandfilter .post_meta .default_radio ~ .radio_separate {
	display: none;
}
.searchandfilter .post_meta .default_radio ~ label {
	display: none;
}
.searchandfilter .post_meta .radio_separate {
	width: 80px;
    border: 1px solid black;
    display: inline-block;
    margin-bottom: 6px;
 }
.searchandfilter .post_meta	label {
		display:block;
	    padding-left: 3px;
	    position: absolute;
    	top: -8px;
	}
.searchandfilter h4 {
    display: block;
    float: left;
    margin-right: 15px;
    min-width: 140px;
}
.searchandfilter select {
    border: 1px solid #ccc;
}
.searchandfilter .submit {
	text-align: right;
    margin-right: 58px;
}

/*result*/

.result-tumb {
	float: left;
    width: 30%;
}
.result-content {
	float: left;
    width: 60%;
    margin-left: 50px;
    margin-top: 10px;
}

.pesquisar-results .entry-title:after {
	display: block;
    width: 80px;
    height: 2px;
    margin: 40px 0;
    content: "";
    background: #000;
}
.result-content strong {
	font-family: "Montserrat", "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-weight: bold;
}
.pesquisar-results .entry-content {
	min-height: 300px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
}

@media only screen and (max-width: 1024px) {
	.iwm_map_canvas {
	    left: -341px;
	}
	.gangdopepreto_fields_wrap_2col .gangdopepreto_field_group {
	    width: 50%;
	}
	.group_first {
	     margin-left: 0px; 
	     margin-right: 0px; 
	}
	.group_second {
		display: none;
	}
}

@media only screen and (max-width: 892px) {
	.iwm_map_canvas {
	    left: 0;
    	float: none;
	}
	.search-content {
	    position: relative;
	    border-left: none;
	}
	.map_title {
		padding-left: 50px;
	}
}
@media only screen and (max-width: 766px) {
	/*single*/
	.sidebar-right {
	    margin-right: 0;
	}
	.percent-blog, .percent-page {
	    width: 100%;
	    float: none;
	    position: relative;
	}
	.percent-sidebar {
	    width: 100%;
	    float: none;
	    position: relative;
	        padding-right: 25px;
    padding-left: 25px;
    margin-bottom: 40px;
	}
}
@media only screen and (max-width: 669px) {
	.pesquisar-results .entry-content {
	    flex-direction: column;
	}
	.result-tumb {
		float: none;
	    width: 100%;
	}
	.result-content {
		float: none;
	    width: 100%;
	    margin-left: 0px;
	}
	.searchandfilter h4 {
	    float: none;
	}
	.search-content {
    	height: auto;
    	width: 100%;
    }
    .searchandfilter .submit {
	    text-align: left;
	     margin-right: 0px; 
	}
}
@media only screen and (max-width: 494px) {
	.searchandfilter .post_meta .radio_separate {
	    width: 50px;
	}
	.gangdopepreto_fields_wrap_3col {
	    display: block;
	}
	.gangdopepreto_fields_wrap_3col .gangdopepreto_field {
	    width: 100%;
	}
	.gangdopepreto_fields_wrap_2col {
	    display: block;
	}
	.gangdopepreto_fields_wrap_2col .gangdopepreto_field_group {
	    width: 100%;
	}
}
	</style>
	<?php
}

if ( ! function_exists( 'blink_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function blink_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( '%s', 'post date', 'blink' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( 'by %s', 'post author', 'blink' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"></span>';

}
endif;

if ( ! function_exists( 'blink_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function blink_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'blink' ) );
		if ( $categories_list && blink_categorized_blog() ) {
/* original	printf( '<span class="cat-links">' . __( 'in %1$s', 'blink' ) . '</span>', $categories_list ); */
			printf( '<span class="cat-links"></span>', $categories_list );
		}
	}

	echo '<div>';

	if ( ! post_password_required() && ( comments_open()  ) ) {
		echo '<span class="comments-link">';
		//comments_popup_link( __( 'Leave a comment', 'blink' ), __( '1 Comment', 'blink' ), __( '% Comments', 'blink' ) );
		echo '</span>';
	}

	if ( is_single() ) {
		edit_post_link( __( 'Edit', 'blink' ), '<span class="edit-link"> ', '</span>' );
	}

	echo '</div>';
}
endif;


//Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output ) {
        return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
    }
add_filter('language_attributes', 'add_opengraph_doctype');
 
//Lets add Open Graph Meta Info
 
function insert_fb_in_head() {
    global $post;
    if ( !is_singular()) //if it is not a post or a page
        return;
        echo '<meta property="og:title" content="' . get_the_title() . '"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo '<meta property="og:site_name" content="Gang do Pé Preto"/>';
    if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
        $default_image="http://gangdopepreto.com/wp-content/uploads/2018/02/gang-do-pe-preto.jpg"; //replace this with a default image on your server or an image in your media library
        echo '<meta property="og:image" content="' . $default_image . '"/>';
    }
    else{
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
        echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
    }
    echo "
";
}
add_action( 'wp_head', 'insert_fb_in_head', 5 );


/* disable jetpack og */
add_filter( 'jetpack_enable_open_graph', '__return_false' );
