<div id="comments">
<?php if ( post_password_required() ) : ?>
  <p class="nopassword"><?php __('Для просмотра комментариев введите пароль.', 'adventurebit'); ?></p>
</div><!-- comments -->
<?php return; endif;?>

<?php if ( have_comments() ) : ?>
<h3 id="comments-title">
<?php
  printf( __( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'adventurebit' ),
  number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
?>
</h3>

<ol class="commentlist">
	<?php
    wp_list_comments( array( 'callback' => 'twentyten_comment' ) );
	?>
</ol>

<?php else : 
	if (!comments_open()) :
?>
<?php endif; ?>

<?php endif; ?>

<?php comment_form(); ?>

</div>
