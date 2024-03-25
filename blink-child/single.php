<?php
/**
 * The template for displaying all single posts.
 *
 * @package Blink
 */

$hide_navigation = (bool) get_theme_mod( 'layout-post-navigation', blink_get_default( 'layout-post-navigation' ) );

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php get_template_part( 'partials/post', 'cover' ); ?>
			</article><!-- #post-## -->

			<!-- <div class="inner-block"> -->
				<!-- <div class="blog-wrapper percent-blog sidebar-right"> -->
					<?php get_template_part( 'partials/content', 'single' ); ?>
					<?php get_template_part( 'partials/custom', 'single' ); ?>
					<div class="entry-content">CÓDIGO DE ÉTICA:<br>Todos os locais e destinos aqui mencionados foram escolhidos e vividos por nós. O Gang não faz publicidade gratuita. Promove destinos que promovem emoções. Aceitamos de bom agrado sugestões. Estamos abertos a parcerias, mas só falaremos do destino se o destino nos falar à alma. Esse é um compromisso que temos, sujamos os pés, as vezes que for preciso, mas não estamos dispostas a sujar a alma. Este projecto é subsidiado pelo gang (até ver).</div>
					<?php
					if ( ! $hide_navigation  ) :
						blink_post_nav();
					endif;
					?>

					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() ) :
							comments_template();
						endif;
					?>

				<!-- </div> -->
				<!-- <aside class="percent-sidebar"> -->
					<?php //get_sidebar('right'); ?>
				<!-- </aside> -->
				<!-- <div class="clear"></div> -->
			<!-- </div> -->

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
