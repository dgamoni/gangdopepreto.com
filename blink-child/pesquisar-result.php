<?php
/**
 * The template for displaying search results pages.
 *
 * @package Blink
 */

get_header(); ?>

	<section id="primary" class="content-area pesquisar-results">
		<main id="main" class="site-main posts-list page-cover-inside" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="entry-title"><?php echo __( 'Pesquisar resultados:', 'blink' ); ?></h1>
			</header><!-- .page-header -->
			
			

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'partials/content', 'result' );
				?>

			<?php endwhile; ?>

			<?php blink_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'partials/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>
