<?php

	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))

		die ('Please do not load this page directly. Thanks!');

        if (!empty($post->post_password)) {

            if ($_COOKIE['wp-postpass_'.$cookiehash] != $post->post_password) {

				?>

	<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments."); ?><p>

	<?php

	return;

        }

     }

	$oddcomment = "alt";

?>

<!-- You can start editing here. -->

<h3>There's <?php comments_number('0 Comment','1 Comment','% Comments'); ?></h3>



<a name="comments"></a>

<?php if ($comments) : ?>



<ul class="comment_list">

	<?php foreach ($comments as $comment) : ?>

	<?php $comment_type = get_comment_type(); ?>

	<?php if($comment_type == 'comment') { ?>

		<li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>"><a name="comment-<?php comment_ID() ?>"></a>



	<span class="comment_author"><?php comment_author_link() ?></span><br/>

	<span class="comment_meta"><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></span>

	<div class="comments_entry">

	<?php comment_text() ?>

	</div>

		</li>

	<?php /* Changes every other comment to a different class */	

	if ('alt' == $oddcomment) $oddcomment = '';

	else $oddcomment = 'alt';

	?>

	<?php } else { $trackback = true; } /* End of is_comment statement */ ?>	

	<?php endforeach; /* end for each comment */ ?>

</ul>

<?php if ($trackback == true) { ?>

<h3>Who Linked To This Post?</h3>

<ol>

	<?php foreach ($comments as $comment) : ?>

	<?php $comment_type = get_comment_type(); ?>

	<?php if($comment_type != 'comment') { ?>

	<li><?php comment_author_link() ?></li>

	<?php } ?>

	<?php endforeach; ?>

</ol>

<?php } ?>

<?php else : // this is displayed if there are no comments so far ?>

<?php if ('open' == $post-> comment_status) : ?> 

	<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>

	<!-- If comments are closed. -->

	<p class="nocomments">Comments are closed on this post.</p>

	<?php endif; ?>

<?php endif; ?>

<?php if ('open' == $post-> comment_status) : ?>



<h3>Share your thoughts&#44; leave a comment&#33;</h3>



<form action="<?php echo get_settings('siteurl'); ?>/wp-comments-post.php" method="post" id="comment_form">



<?php if ( $user_ID ) : ?>



	<p>You are currently logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account"">Logout &raquo;</a></p>



		<?php else : ?>



	<p><input type="text" class="text_input" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1" /><label for="author">Name (required)</label></p>



	<p><input type="text" class="text_input" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" /><label for="email">Email (required)</label></p>



	<p><input type="text" class="text_input" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" /><label for="url">Website URL</label></p>



<?php endif; ?>



<p><textarea class="text_area" name="comment" id="comment" rows="8" cols="10" tabindex="4"></textarea></p>	

		<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" /><input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />	</p>

</form>

<?php // if you delete this the sky will fall on your head

endif; ?>