<p style="font-style:italic;"><?php echo $in_reply_to; ?>:</p>

<blockquote style="color:grey;"><?php echo $quoted_text; ?></blockquote>

<?php comment_text( $comment->ID ); ?>

<p style="color:grey;"><?php comment_author( $comment->ID ); ?> | <a href="<?php echo esc_url( get_comment_link( $comment->ID ) ); ?>"><?php echo sprintf( '%s at %s', get_comment_time( get_option( 'date_format' ) ), get_comment_time( get_option( 'time_format' ) ) ); ?></a></p>