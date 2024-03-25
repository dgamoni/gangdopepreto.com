<?php
/**
 * The sidebar containing the "Footer Widgets 1" widget area.
 *
 * @package Blink
 */

$widget_area_class = ( is_active_sidebar( 'footer-widgets-1' ) ) ? '' : ' inactive';
?>
<div id="footer-widgets-1" class="widget-area<?php echo $widget_area_class; ?>" role="complementary">
	<?php dynamic_sidebar( 'footer-widgets-1' ) ?>
</div>
