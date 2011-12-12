<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="post-top">
  <div class="post-type">
    <?php woo_tumblog_image(array('',40,40)); ?>
    <div class="day"><?php the_time('d') ?></div>
    <div class="month"><?php the_time('M') ?></div>
  </div>
</div>
<div class="post">
<h2 class="post-title"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="nodecor"><?php the_title(); ?></a></h2>
    <div class="post-content">
        <?php the_content();?>
    </div>
</div>
<div class="post-bottom"></div>
<div class="post-footer">
  <div class="post-footer-shadow">
    <a href="<?php the_permalink() ?>" class="post-info date"><?php the_time('F d, Y в G:i') ?></a>
    <?php if('open'==$post->comment_status)comments_popup_link('Ваш отзыв', '1 отзыв', 'Отзывов: %','post-info comments', 'Запрещено комментировать'); ?>
    <span class="post-info tags"><?php the_tags( '', ', ', ''); ?></span>
    <a class="post-info author"><?php the_author(); ?></a>
    <span class="post-info categories"><?php the_category(', ') ?></span>
    <span class="post-info tumblog"><?php echo get_the_term_list($post->ID,'tumblog','',', ',''); ?></span>
  </div>
</div>
<div class="post-footer-bottom"></div>
<?php endwhile; ?>

<?php if(function_exists('wp_paginate')){wp_paginate();}?>

<?php else : ?>

<div class="post search">
  <h2>Поиск не дал результатов</h2>
  Попробуйте сформировать запрос другими словами или воспользуйтесь навигацией!
</div>
<div class="post-bottom"></div>
<div class="post-footer">
  <div class="post-footer-shadow">
    <a href="<?php bloginfo('url');?> " class="post-info thumsup">Домашняя страница</a>
  </div>
</div>
<div class="post-footer-bottom"></div>

<?php endif; ?>