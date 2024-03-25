<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Blink
 */

$footer_text        = get_theme_mod( 'footer-text', blink_get_default( 'footer-text' ) );
$hide_footer_credit = (bool) get_theme_mod( 'footer-credits', blink_get_default( 'footer-credits' ) );
$social_links       = blink_get_social_links();

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="inner-block">
			<?php if ( blink_has_footer_widgets() ) : ?>
			<div class="widgets-container">
				<?php get_sidebar( 'footer-1' ); ?>
				<?php get_sidebar( 'footer-2' ); ?>
			</div>
			<?php endif; ?>

			<div class="footer-text-container">

				<span class="cprt">
					©<a href="http://isabelsaldanha.com" target="_blank"> Isabel Saldanha</a>. Todos os direitos reservados. Web by <a href="http://digitalclap.com" target="_blank">Digitalclap</a>
				</span>

				<div class="footer-text">
					<?php // Footer text
					if ( $footer_text ) : ?>
					<p><?php echo blink_sanitize_text( $footer_text ); ?></p>
					<?php endif; ?>

					<?php // Theme credit text
					if ( false === $hide_footer_credit ) : ?>
					<p class="site-info">
						<?php if ( blink_is_wpcom() ) : ?>
							<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'blink' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'blink' ), 'WordPress' ); ?></a>
							<span class="sep"> | </span>
							<?php printf( __( 'Theme: %1$s by %2$s.', 'blink' ), 'Blink', '<a href="https://codestag.com/" rel="designer">Codestag</a>' ); ?>
						<?php else : ?>
							
						<?php endif; ?>
					</p><!-- .site-info -->
					<?php endif; ?>
				</div>

				<?php // Social profile links
				if ( ! empty( $social_links ) ) : ?>
				<!-- <ul class="footer-social-links"> -->
					<?php foreach ( $social_links as $service => $details ) : ?>
<!-- 					<li class="<?php echo esc_attr( $service ); ?>">
						<a href="<?php echo esc_url( $details['url'] ); ?>" title="<?php echo esc_attr( $details['title'] ); ?>">
							<span class="genericon genericon-<?php echo esc_attr( $details['class'] ); ?>"></span>
						</a>
					</li> -->
					<?php endforeach; ?>
				<!-- </ul> -->
				<?php endif; ?>
			</div>

		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
