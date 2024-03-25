<?php
/**
 * Custom header
 *
 * @package Blink
 */

if ( '' === get_header_image() ) {
	return;
}

$header_image_text    = get_theme_mod( 'header_image-text', blink_get_default( 'header_image-text' ) );
$header_image_color   = get_theme_mod( 'header_image-color', blink_get_default( 'header_image-color' ) );
$header_image_opacity = get_theme_mod( 'header_image-opacity', blink_get_default( 'header_image-opacity' ) );

?>

<section class="custom-header">
	<style type="text/css">
		.custom-header {
			background-image: url(<?php echo esc_url( get_header_image() ); ?>);
		}
		.custom-header__overlay {
			background-color: <?php echo esc_attr( $header_image_color ); ?>;
			opacity: <?php echo floatval( $header_image_opacity / 100 ); ?>;
		}
	</style>

	<?php if ( function_exists( 'has_header_video' ) && has_header_video() ) : ?>
		<div class="custom-header-media">
			<?php the_custom_header_markup(); ?>
		</div>
	<?php endif; ?>

	<?php if ( '' !== $header_image_text || is_customize_preview() ) : ?>
	<div class="custom-header__overlay"></div>

	<div class="custom-header__inner">
		<div class="custom-header__text">
			<?php echo wp_kses_post( $header_image_text ); ?>
		</div>
	</div>
	<?php endif; ?>
</section>
