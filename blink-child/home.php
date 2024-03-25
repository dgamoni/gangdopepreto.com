<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Blink
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main posts-list" role="main">

		<?php if ( have_posts() ) : ?>
			<?php 
			$count = $GLOBALS['wp_query']->post_count; 
			//var_dump($GLOBALS['wp_query']->query['paged']);
			$paged = get_query_var( 'paged', 1 );
			//var_dump($paged);
			$counts = 1;
			?>
			<?php /* Start the Loop */   ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'partials/content', get_post_format() );
					//var_dump($count);
					//var_dump($counts);
					if ($count % 2 == 0 && $count == $counts && $paged == 0 || $count == $counts && $paged > 0 && $count % 2 != 0) {
						get_template_part( 'partials/content_add');
					} else {

					}
					$counts++;
				?>

			<?php endwhile; ?>

			<?php //blink_paging_nav(); ?>

			<?php
				global $wp_query; // you can remove this line if everything works for you
			 
				// don't display the button if there are not enough posts
				if (  $wp_query->max_num_pages > 1 ) {
					echo '<div class="misha_loadmore">More posts</div>'; // you can use <a> as well
				}
			?>


		<?php else : ?>

			<?php get_template_part( 'partials/content', 'none' ); ?>

		<?php endif; ?>
		</main><!-- #main -->

	</div><!-- #primary -->

<?php get_footer(); ?>
