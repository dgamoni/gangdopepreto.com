<?php
/**
 * The sidebar containing the "sidebarright" widget area.
 *
 * @package Blink
 */

$widget_area_class = ( is_active_sidebar( 'sidebar-right' ) ) ? '' : ' inactive';
?>
<div id="sidebarright" class="widget-area<?php echo $widget_area_class; ?>" role="complementary">
	<?php dynamic_sidebar( 'sidebar-right' ) ?>
</div>
 