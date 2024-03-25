<?php
/**
 * @package Blink
 */
?>

<section class="post-cover post-grid">
	<div class="overlay"></div>

	<div class="post-cover-inside">
		<div class="text-container post-cover-content">
			<header class="entry-header">
				<?php get_template_part( 'partials/entry', 'sticky' ); ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<footer class="entry-footer">
				<?php blink_posted_on(); ?>

				<?php blink_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		</div><!-- .text-container -->
	</div><!-- .post-cover-inside -->
</section><!-- .post-cover -->
