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
</div><!-- #page -->

<?php wp_footer(); ?>

<a href="https://open.spotify.com/user/isaldanha/playlist/4A7W2OtDA9VD9vO6PqzJpv" target="_blank"><img src="http://gangdopepreto.com/wp-content/uploads/2018/02/spotify-playlist.png" id="fixedbutton" height="40" width="40" alt="Gang do Pé Preto Playlist"></a>
</body>
</html>


