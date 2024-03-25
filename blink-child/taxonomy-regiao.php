<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Blink
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main posts-list" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="archive-title">
					<span>
					<?php
							_e( 'Região', 'blink' );
					?>
					</span>
				</h1>
				<p><?php single_term_title(); ?></p>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;

					// Show author bio description
					$author_bio = get_the_author_meta( 'description' );
					if ( is_author() && ! empty( $author_bio ) ) {
						printf( '<div class="author-description">%s</div>', apply_filters( 'the_content', $author_bio ) );
					}
				?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'partials/content', get_post_format() );
				?>

			<?php endwhile; ?>

			<?php blink_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'partials/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>
