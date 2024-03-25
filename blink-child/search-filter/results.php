<?php
/**
 * Search & Filter Pro 
 *
 * Sample Results Template
 * 
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2015 Designs & Code
 * 
 * Note: these templates are not full page templates, rather 
 * just an encaspulation of the your results loop which should
 * be inserted in to other pages by using a shortcode - think 
 * of it as a template part
 * 
 * This template is an absolute base example showing you what
 * you can do, for more customisation see the WordPress docs 
 * and using template tags - 
 * 
 * http://codex.wordpress.org/Template_Tags
 *
 */
?>



	<section id="primary" class="content-area pesquisar-results">
		<main id="main" class="site-main posts-list page-cover-inside" role="main">

		<?php 
		//var_dump($_GET);
		if (isset($_GET['sfid'])) {
			if ( $query->have_posts() ) {
				?>

						<header class="page-header">
							<h1 class="entry-title"><?php echo __( 'Pesquisar resultados:', 'blink' ); ?></h1>
						</header><!-- .page-header -->
						
						

						<?php
						while ($query->have_posts()): 
							$query->the_post(); ?>

							<?php
								get_template_part( 'partials/content', 'result' );
							?>

						<?php endwhile; ?>

						<?php blink_paging_nav(); ?>

				<?php
			}
			else
			{
				 get_template_part( 'partials/content', 'none' ); 
			}
		}
		?>

		</main><!-- #main -->
	</section><!-- #primary -->