<?php
/**
 * @package Blink
 */

$hide_meta = (bool) get_theme_mod( 'layout-page-meta', blink_get_default( 'layout-page-meta' ) );
?>

<section class="page-cover">
	<div class="overlay"></div>

	<div class="page-cover-inside inner-block">
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<?php if ( ! $hide_meta ) : ?>
		<footer class="entry-footer">
			<?php //blink_posted_on(); ?>

			<?php //blink_entry_footer(); ?>
		</footer><!-- .entry-footer -->
		<?php endif; ?>
	</div><!-- .page-cover-inside -->
</section><!-- .page-cover -->
