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





	<div class="entry-content search-template-654">

		<?php //the_content(); ?>


		<?php echo do_shortcode('[searchandfilter id="654"]'); ?>
		
		<?php 
		//if (isset($_GET['sfid'])) :
    		echo do_shortcode('[searchandfilter id="654" show="results"]');
    	//endif; ?>

	</div><!-- .entry-content -->
</article><!-- #post-## -->
