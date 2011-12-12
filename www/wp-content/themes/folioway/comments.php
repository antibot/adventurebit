<?php
    if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
        die ( 'Please do not load this page directly. Thanks.' );
?>
<?php if(comments_open()): ?>
<div class="clear"></div>
<div id="comments" class="box">

<?php
    if ( !empty($post->post_password) ) :
        if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) :
?>
                <div class="nopassword"><?php _e( 'This post is protected. Enter the password to view any comments.', THEME_NAME ) ?></div>
            </div><!-- .comments -->
<?php
        return;
    endif;
endif;
?>

<?php if ( have_comments() ) : ?>
    <?php global $comment_ids, $iwak; $comment_ids = array(); $exclude_child_comments = true;
        if ( $iwak->o['general']['display_nested_comments_index'] ) $exclude_child_comments = false;
        if ( $exclude_child_comments == false ) {
            foreach ( $comments as $comment ) {
                if (get_comment_type() == "comment") {
                    ++$comment_count;
                    $comment_ids[get_comment_id()] = ++$comment_i;
                }
            }
        } else {
            foreach ( $comments as $comment ) {
                if (get_comment_type() == "comment") {
                    ++$comment_count;
                    if( $comment->comment_parent == 0 )
                        $comment_ids[get_comment_id()] = ++$comment_i;
                }
            }
        }
    ?>

        <h3 class="boxtitle"><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h3>

        <ol id="comments-list" class="comments">
            <?php global $iwak;wp_list_comments(array('type'=>'comment', 'avatar_size'=>56, 'callback'=>array(&$iwak, 'list_comments'), 'end-callback'=>array(&$iwak, 'end_list_comments'))); ?>
        </ol>
        
        <div class="navigation"><?php paginate_comments_links(); ?></div>
        

<?php endif // REFERENCE: if have_comments() ?>

    <div id="respond" class="box">
<?php if ( 'open' == $post->comment_status ) : ?>
    
        <h3 class="boxtitle"><?php comment_form_title(__('Leave a response',THEME_NAME), __('Leave a response to %s',THEME_NAME), false); ?></h3>
        <p id="cancel-comment-reply"><?php cancel_comment_reply_link('<img src="'. THEME_URL. '/images/icons/close.png"/>') ?></p>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
        <p id="login-req"><?php printf(__('You must be <a href="%s" title="Log in">logged in</a> to post a comment.', THEME_NAME),
        site_url() . '/wp-login.php?redirect_to=' . get_permalink() ) ?></p>
<?php else : ?>

        <form id="commentform" action="<?php echo site_url() ?>/wp-comments-post.php" method="post">

<?php if ( $user_ID ) : ?>
            <div class="login-meta"><?php _e('Logged in as ', THEME_NAME) ?><a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>， <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e('Log out ', THEME_NAME) ?>&raquo;</a></div>
<?php else : $req = 0; ?>
            <div class="form-input">
                <label class="form-label"><?php _e('Name ', THEME_NAME);if ($req) _e('(required)', THEME_NAME); ?></label>
                <input id="author" name="author" class="text" type="text" value="<?php echo $comment_author ?>" size="30" maxlength="50" tabindex="3" />
              </div>

            <div class="form-input">
                <label class="form-label"><?php _e('EMail ', THEME_NAME);if ($req) _e('(required)', THEME_NAME); ?></label>
                <input id="email" name="email" class="text" type="text" value="<?php echo $comment_author_email ?>" size="30" maxlength="50" tabindex="4" />
              </div>

            <div class="form-input">
                <label class="form-label"><?php _e('Website ', THEME_NAME) ?></label>
                <input id="url" name="url" class="text" type="text" value="<?php echo $comment_author_url ?>" size="30" maxlength="50" tabindex="5" />
             </div>
<?php endif // REFERENCE: * if ( $user_ID ) ?>

            <div class="form-textarea">
                <textarea id="comment" name="comment" class="textarea required" cols="45" rows="8" tabindex="6" onkeydown="javascript: return ctrlEnter(event);"></textarea>
            </div>
            <div class="form-submit">
                <input id="submit" name="submit" class="button" type="submit" value="<?php _e( 'Add Comment', THEME_NAME ) ?>" tabindex="7" />
                <span class="desc">* Ctrl + Enter</span>
                <div class="clear"></div>
            </div>
            <?php comment_id_fields(); ?>
            <div class="form-option"><?php do_action( 'comment_form', $post->ID ) ?></div>
            
        </form><!-- #commentform -->

        <script language=javascript>
        /* <![CDATA[ */
            function ctrlEnter(e){ 
                var theEvent = e?e:window.event;
                if(theEvent.ctrlKey && theEvent.keyCode==13) document.getElementById("submit").click(); 
            }
        /* ]]> */
        </script>
 
    
<?php endif // REFERENCE: if ( get_option('comment_registration') && !$user_ID ) ?>
<?php endif // REFERENCE: if ( 'open' == $post->comment_status ) ?>
    </div><!-- .respond -->

</div><!-- #comments -->
<?php endif; ?>