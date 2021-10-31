<?php
// don't load it if you can't comment
if ( post_password_required() ) {
  return;
}
?>
  <?php if ( have_comments() ) : ?>
    <h3 id="comments-title" class="h2"><?php comments_number( '<span>No</span> Comments', '<span>One</span> Comment', '<span>%</span> Comments' );?></h3>
    <section class="commentlist">
      <?php
        wp_list_comments( array(
          'style'             => 'div',
          'short_ping'        => true,
          'avatar_size'       => 40,
          'type'              => 'all',
          'reply_text'        => 'Reply',
          'page'              => '',
          'per_page'          => '',
          'reverse_top_level' => null,
          'reverse_children'  => ''
        ) );
      ?>
    </section>
    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
    	<nav class="navigation comment-navigation" role="navigation">
      	<div class="comment-nav-prev"><?php previous_comments_link( '&larr; Previous Comments' ); ?></div>
      	<div class="comment-nav-next"><?php next_comments_link( 'More Comments &rarr;' ); ?></div>
    	</nav>
    <?php endif; ?>
    <?php if ( ! comments_open() ) : ?>
    	<p class="no-comments">Comments are closed.</p>
    <?php endif; ?>
  <?php endif; ?>
  <?php comment_form(); ?>