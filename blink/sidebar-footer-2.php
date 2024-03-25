<?php
/**
 * The sidebar containing the "Footer Widgets 2" widget area.
 *
 * @package Blink
 */

$widget_area_class = ( is_active_sidebar( 'footer-widgets-2' ) ) ? '' : ' inactive';
?>
<div id="footer-widgets-2" class="widget-area<?php echo $widget_area_class; ?>" role="complementary">
	<?php dynamic_sidebar( 'footer-widgets-2' ) ?>
</div>
