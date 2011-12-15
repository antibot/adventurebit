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
    <div id="comment-<?php comment_ID(); ?>" class="comment-content">
    <div class="comment-body">
      <div class="comment-meta">  
        <div class="comment-author">
          <span><?= comment_author( $comment->comment_ID ); ?></span> says:
        </div>
        <div class="comment-date">
          on <?= comment_date('F d \a\t H:i:s'); ?>
        </div>
        <div class="comment-number">
           <?= gtcn_comment_numbering($comment->comment_ID,$args); ?> 
        </div>
        
        <div class="comment-text">
           <?= comment_text(); ?> 
        </div>
        
        <div class="comment-buttons">
          <?= edit_comment_link('Edit', '<div class="comment-edit">', '</div>'); ?>
          
          <?= comment_reply_link( array_merge( $args, array (
              'depth' => $depth, 
              'max_depth' => $args['max_depth'],
              'before' => '<div class="comment-reply">', 
              'after' => '</div>',
            )
            )); 
          ?>
        </div>
        
      </div>
      </div>
    </div>
  </div>
  </div>
<?php
}


   
    
?>