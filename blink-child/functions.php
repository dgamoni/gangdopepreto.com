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
}

.gang_field_title {
	    margin-bottom: 10px;
    display: inline-block;
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
    width: 33%;
    text-align: center;
    margin-bottom: 34px;	
}
.num_rating {
    text-indent: -10000px;
    display: inline-block;
    border: 1px solid black;
    border-radius: 50%;
    width: 14px;
    height: 14px;
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