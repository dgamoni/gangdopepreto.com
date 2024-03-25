<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Blink
 */

if ( ! function_exists( 'blink_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function blink_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'blink' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous nav-link"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'blink' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next nav-link"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'blink' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'blink_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function blink_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'blink' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous nav-link">%link</div>', _x( '<span class="meta-nav">Previous Post</span> %title', 'Previous post link', 'blink' ) );
				next_post_link( '<div class="nav-next nav-link">%link</div>', _x( '<span class="meta-nav">Next Post</span> %title', 'Next post link', 'blink' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'blink_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function blink_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( 'Published %s', 'post date', 'blink' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( 'by %s', 'post author', 'blink' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline">' . $byline . '</span>';

}
endif;

if ( ! function_exists( 'blink_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function blink_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'blink' ) );
		if ( $categories_list && blink_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( 'in %1$s', 'blink' ) . '</span>', $categories_list );
		}
	}

	echo '<div>';

	if ( ! post_password_required() && ( comments_open()  ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'blink' ), __( '1 Comment', 'blink' ), __( '% Comments', 'blink' ) );
		echo '</span>';
	}

	if ( is_single() ) {
		edit_post_link( __( 'Edit', 'blink' ), '<span class="edit-link"> ', '</span>' );
	}

	echo '</div>';
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function blink_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'blink_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'blink_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so blink_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so blink_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in blink_categorized_blog.
 */
function blink_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'blink_categories' );
}
add_action( 'edit_category', 'blink_category_transient_flusher' );
add_action( 'save_post',     'blink_category_transient_flusher' );

if ( ! function_exists( 'blink_has_footer_widgets' ) ) :
/**
 * Check if any of the footer sidebars have active widgets.
 */
function blink_has_footer_widgets() {
	return ( is_active_sidebar( 'footer-widgets-1' ) || is_active_sidebar( 'footer-widgets-2' ) );
}
endif;

if ( ! function_exists( 'blink_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function blink_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li class="post pingback">
		<div class="comment-body">
			<?php comment_author_link(); ?>

			<time class="comment-published" datetime="<?php comment_time( 'c' ); ?>" title="<?php comment_time( 'l, F j, Y, g:i a' ) ?>">
				<?php echo human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) . __( ' ago', 'blink' ); ?>
			</time>
			<?php edit_comment_link( __( 'Edit', 'blink' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php

	else :

	global $post;

	$avatar_visiblity = ( get_option( 'show_avatars' ) ) ? 'show-avatar' : 'hide-avatar';

	?>

	<li <?php comment_class( $avatar_visiblity ); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

			<div class="comment-author-wrap">
				<figure class="comment-avatar">
					<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
				</figure>

				<footer class="comment-meta">
					<?php
						$author = get_comment_author();
						$link   = get_comment_author_url();

						if ( ! empty ( $link ) ) {
							$author = '<a href="' . $link . '" rel="external nofollow" class="url fn n">' . $author . '</a>';
						}

						// If current post author is also comment author, make it known visually.
						if ( $comment->user_id === $post->post_author ) {
							$author .= '<span class="post-author">' . __( '(Author)', 'blink' ) . '</span>';
						}

						printf( __( '<cite class="comment-author">%s</cite>' ), $author );
					?>

					<span class="comment-metadata">
						<a class="time-link" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<time class="comment-published" datetime="<?php comment_time( 'c' ); ?>" title="<?php comment_time( 'l, F j, Y, g:i a' ) ?>">
								<?php comment_time( 'F j, Y' ); ?>
							</time>
						</a>
						<?php
							comment_reply_link( array_merge( $args, array(
								'add_below' => 'div-comment',
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'before'    => '<span class="divider">&#47;</span> <span class="reply">',
								'after'     => '</span>',
							) ) );
						?>
						<?php edit_comment_link( __( 'Edit', 'blink' ), '<span class="divider">&#47;</span> <span class="edit-link">', '</span>' ); ?>
					</span><!-- .comment-metadata -->

					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'blink' ); ?></p>
					<?php endif; ?>
				</footer><!-- .comment-meta -->
			</div>

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->
		</article><!-- .comment-body -->

	<?php
	endif;
}
endif;  // ends check for blink_comment()

if ( ! function_exists( 'blink_has_logo' ) ) :
/**
 * Determine if a site logo is assigned or not.
 *
 * @since 1.0.0.
 *
 * @return bool
 */
function blink_has_logo() {
	if ( blink_is_wpcom() ) {
		return has_site_logo();
	} else if ( function_exists( 'jetpack_has_site_logo' ) ) {
		return jetpack_has_site_logo();
	} else {
		return false;
	}
}
endif;
