<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Blink
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'partials/page', 'cover' ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links header-font"><span class="wrap">',
				'after'       => '</span></div>',
				'link_before' => '<span class="page-numbers">',
				'link_after'  => '</span>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
