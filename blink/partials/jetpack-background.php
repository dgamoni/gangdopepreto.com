<?php
/**
 * @package Blink
 */

$post_id         = get_the_ID();
$thumbnail_id    = get_post_thumbnail_id( $post_id );
$thumbnail_image = wp_get_attachment_image_src( $thumbnail_id, 'blink_half' );

?>

<style type="text/css">
	.post-<?php echo esc_attr( $post_id ); ?> { background-image: url(<?php echo esc_url( $thumbnail_image[0] ) ?>); }
</style>
