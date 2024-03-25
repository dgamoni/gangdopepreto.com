<?php
/**
 * @package Blink
 */
//var_dump(get_the_post_thumbnail_url());
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="background-image: url(<?php echo get_the_post_thumbnail_url($post->ID, 'large'); ?>);">
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
