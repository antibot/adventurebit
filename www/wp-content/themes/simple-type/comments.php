<?php if('open' == $post->comment_status):?>
  
  <div class="post-comments">
  <?php if($comments):?>
    <h3 class="comments-count">Comments: <?php comments_number('0', '1', '%'); ?></h3>
   
    <ul>
    <?php wp_list_comments('type=comment&callback=adventurebit_comments'); ?>  
    </ul> 
  <?php endif; ?> 
   
  <?php global $commenter; $commenter = wp_get_current_commenter(); ?>
  
  <div id="respond">
    <h3 id="reply-title">Leave a comment</h3>  
    <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="comment-form">  
      <?php do_action( 'comment_form_top' ); ?>  
      
        <div id="comment-fields">
          <table>
            <?php if ( !is_user_logged_in() ) : ?>  
            <tr>
              <td id="author">
                <input name="author" type="text" value="<?php echo $commenter['comment_author']; ?>" />
              </td>
              <td id="email">
                <input name="email" type="text" value="<?php echo $commenter['comment_author_email']; ?>" /> 
              </td>
            </tr>
            <?php endif; ?> 
            <tr>
              <td colspan="2" class="comment">
                <textarea id="comment" name="comment" cols="45" rows="8"></textarea>
              </td>
            </tr>
          </table> 
            
          <table>
            <tr>
              <td width="100%">
                <?='<a class="cancel-comment-reply-link">Click here to cancel reply.</a>'.get_cancel_comment_reply_link(' '); ?>
              </td>
              <td>
              <button name="submit" id="submit-comment">Send</button>
              </td>
            </tr>
          </table>        
        </div>
        
        <?php comment_id_fields( $post_id ); ?>  
      <?php do_action( 'comment_form', $post_id ); ?>  
    </form>
  </div>
    
  </div> 
<?php else:?>
  <p class="nocomments">Comments are closed on this post.</p>
 <?php endif; ?> 