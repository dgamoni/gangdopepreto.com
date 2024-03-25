<?php
/**
 * Blink Child Theme
 *
 * Place any custom functionality/code snippets here.
 *
 * @since Blink Child 1.0
 */

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
}
	</style>
	<?php
}