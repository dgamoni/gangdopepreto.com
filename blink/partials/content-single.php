<?php
/**
 * @package Blink
 */

$hide_author_info = (bool) get_theme_mod( 'layout-author-info', blink_get_default( 'layout-author-info' ) );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'partials/post', 'cover' ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'blink' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">

		<?php if ( ! $hide_author_info ) : ?>
		<div class="inner-block author-bio">
			<div class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'email' ), '70' ); ?>
			</div>
			<div class="author-info">
				<h3 class="author-title"><?php _x( 'Author:', 'Post author label', 'blink' ); ?> <?php the_author_link(); ?></h3>
				<p class="author-description"><?php the_author_meta( 'description' ); ?></p>
			</div>
		</div>
		<?php endif; ?>

		<?php

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'blink' ) );
		if ( $tags_list ) {
			printf( '<div class="inner-block tags-links">' . __( '<span class="tags-label">Tags:</span> %1$s', 'blink' ) . '</div>', $tags_list );
		}

		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
