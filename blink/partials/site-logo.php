<?php
/**
 * @package Blink
 */

$light_logo                 = get_theme_mod( 'title_tagline-logo-light' );
$has_post_thumbnail         = has_post_thumbnail();
$overlay_header             = absint( get_theme_mod( 'layout-header-overlay', blink_get_default( 'layout-header-overlay' ) ) );
$is_overlay_header_disabled = absint( get_theme_mod( 'layout-header-overlay-disabled', blink_get_default( 'layout-header-overlay-disabled' ) ) );
?>

<?php if ( ( ( is_home() && 1 === $is_overlay_header_disabled ) ) || is_archive() || is_search() ) : ?>
	<?php if ( blink_is_wpcom() ) : ?>
		<?php the_site_logo(); ?>
	<?php elseif ( function_exists( 'jetpack_has_site_logo' ) && jetpack_has_site_logo() ) : ?>
		<?php jetpack_the_site_logo(); ?>
	<?php endif; ?>
<?php elseif ( true === $has_post_thumbnail && '' !== $light_logo && 1 === $overlay_header ) : ?>
	<a href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home' class="site-logo-link" itemprop="url">
		<img src="<?php echo esc_url( $light_logo ); ?>" itemprop="logo" class="site-logo" />
	</a>
<?php else : ?>
	<?php if ( blink_is_wpcom() ) : ?>
		<?php the_site_logo(); ?>
	<?php elseif ( function_exists( 'jetpack_has_site_logo' ) && jetpack_has_site_logo() ) : ?>
		<?php jetpack_the_site_logo(); ?>
	<?php endif; ?>
<?php endif; ?>
