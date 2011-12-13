<?php
if(function_exists('register_sidebar')) {
  register_sidebar();
}
 
if(function_exists('add_theme_support')) {
    add_theme_support('menus');
}

register_nav_menu('main', 'Главное меню'); 
   
//<?php echo get_avatar($comment,48,'http://julia-love.com/wp-content/themes/julia-love/images/avatar.jpg');   
   
function adventurebit_comments($comment,$args,$depth)
{
  $GLOBALS['comment'] = $comment; ?>
  <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
  <div class="comment-body-left">
  <div class="comment-body-right">
    <div class="comment-body">
    <div id="comment-<?php comment_ID(); ?>" class="comment-content">
    <?php comment_text(); ?> 
    </div>
      <div class="comment-info">
      <a class="post-info number"><?php echo gtcn_comment_numbering($comment->comment_ID,$args); ?></a>
      <a href="<?php comment_link( $comment->comment_ID ) ?>" class="post-date"><?php comment_date('F d, Y at G:i'); ?></a>
      <a <?php $url=get_comment_author_url( $comment->comment_ID ); echo !empty($url)?"href=\"$url\"":''; ?> class="post-author"><?php comment_author( $comment->comment_ID ); ?></a>
      <?php edit_comment_link('Edit', '<span class="post-edit">', '</span>'); ?>
      <?php comment_reply_link( array_merge( $args, array
      (
      'depth' => $depth, 
      'max_depth' => $args['max_depth'],
      'before' => '<span class="post-info comments">', 
      'after' => '</span>',
      )
      )); ?>
    </div>
    </div>
  </div>
  </div>
<?php
}   
    
?>