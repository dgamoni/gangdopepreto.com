<?php
/**
 * @package Blink
 */
?>

<article class="entry-content">
	<div class="result-tumb">
		<?php echo get_the_post_thumbnail( $post->ID , 'medium'); ?>
	</div>
	<div class="result-content">
		<a href="<?php echo get_the_permalink( $post->ID ); ?>">
			<strong><?php echo get_the_title( $post->ID ); ?></strong>
			<p><?php echo get_the_excerpt( $post->ID ); ?></p>
		</a>
	</div>
</article><!-- #post-## -->
