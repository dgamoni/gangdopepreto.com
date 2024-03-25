<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Blink
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'blink' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="inner-block">
			<div class="site-branding">
				<?php if ( blink_has_logo() ) : ?>
					<?php get_template_part( 'partials/site', 'logo' ); ?>
				<?php else : ?>
					<h1 class="site-title">
						<?php // Site title
						if ( get_bloginfo( 'name' ) ) : ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						<?php endif; ?>
					</h1>
					<?php // Tagline
					if ( get_bloginfo( 'description' ) ) : ?>
						<span class="site-description">
							<?php bloginfo( 'description' ); ?>
						</span>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<button id="menu-toggle" class="menu-toggle">
				<span class="menu-toggle__label"><?php echo get_theme_mod( 'navigation-label', blink_get_default( 'navigation-label' ) ) ?></span>
				<span class="genericon genericon-menu"></span>
			</button>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'primary-menu' ) ); ?>
			</nav><!-- #site-navigation -->
		</div>
	</header><!-- #masthead -->

	<?php // Show header image on homepage.
	if ( is_home() ) :
		get_template_part( 'partials/custom', 'header' );
	endif;
	?>

	<div id="content" class="site-content">
