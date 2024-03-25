<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Blink
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<section class="page-cover">
		<div class="overlay"></div>

		<div class="page-cover-inside inner-block">
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<footer class="entry-footer">
			</footer><!-- .entry-footer -->

		</div><!-- .page-cover-inside -->
	</section><!-- .page-cover -->





	<div class="entry-content">

		<?php //the_content(); ?>
		<div class="searchfilter_wrap">
			<!-- <div class="interactive-map"> -->
				<h5 class="map_title">Por distrito:</h5>
				<?php //echo do_shortcode('[show-map id="1"]'); ?>
				<?php build_i_world_map(1); ?>
			<!-- </div> -->
			<div class="search-content">
				<h5>Por critérios cruzados:</h5>
				<?php echo do_shortcode('[searchandfilter id="152"]'); ?>
				
				
			</div>
		</div>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
