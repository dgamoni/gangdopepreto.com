<?php
/**
 * @package Blink
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-grid-content">
		<a href="<?php the_permalink(); ?>" class="item-link"></a>

		<div class="text-container">
			<header class="entry-header">
				<?php get_template_part( 'partials/entry', 'sticky' ); ?>
				<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
			</header><!-- .entry-header -->

			<footer class="entry-footer">
				<?php blink_posted_on(); ?>

				<?php blink_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		</div>

		<div class="overlay"></div>
	</div>
</article><!-- #post-## -->
