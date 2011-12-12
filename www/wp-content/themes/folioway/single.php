<?php get_header(); echo '</div>'; // close #background ?>
    
<div id="container">
        <div class="inner">
            <div id="content">
            
                <?php the_post(); ?>
                    <div id="post-<?php the_ID() ?>" class="post single">
                        <h2 class="post-title"><span class="day"><?php the_time('d');?></span><span class="month"><?php the_time('M'); ?></span><?php the_title(); ?></h2>
                        <?php if($src = $iwak->get_post_image_url(array(640, 240))): ?>
                                <img class="thumbnail" src="<?php echo $src; ?>" />
                        <?php endif; ?>
                        <div class="post-content"><?php $iwak->the_content() ?></div>
                        
                        <?php if($iwak->o['blog']['display_shareto']): ?>
                        <div class="sharebox box">
                            <h3 class="boxtitle"><?php _e('Share to', THEME_NAME); ?></h3>
                            <?php echo $iwak->sociable_bookmarks(); ?>
                            <div class="clear"></div>
                        </div>
                        <?php endif; ?>

                        <?php if($iwak->o['blog']['display_tags']): ?>
                        <div class="tagbox box"><h3 class="boxtitle"><?php _e('Tags', THEME_NAME); ?></h3><?php the_tags(''); ?></div>                
                        <?php endif; ?>
                
                        <?php if($iwak->o['blog']['display_author']): ?>
                        <div class="authorbox box">
                            <h3 class="boxtitle"><?php _e('About the Author', THEME_NAME); ?></h3>
                            <div class="fl"><?php echo get_avatar( get_the_author_meta('email'), '80' ); ?></div>
                            <div class="fr">
                                <div class="author_name"><?php the_author_link(); ?></div>
                                <p class="author_desc"><?php the_author_meta('description'); ?></p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($iwak->o['blog']['display_extra_posts']): ?>
                         <div class="related-posts postbox box">
                            <h3 class="boxtitle"><?php _e('Related Posts', THEME_NAME); ?></h3>
                            <?php $iwak->related_posts(); ?>
                            <div class="clear"></div>
                        </div>
                        
                         <div class="popular-posts postbox box">
                            <h3 class="boxtitle"><?php _e('Popular Posts', THEME_NAME); ?></h3>
                            <?php $iwak->popular_posts(); ?>
                            <div class="clear"></div>
                        </div>
                        <?php endif; ?>

                    </div><!-- .post -->

                <?php comments_template() ?>
                
            </div><!-- #content -->
                
            <div id="post-sidebar" class="sidebar">
                <?php iwak_get_sidebar('Post Sidebar'); ?>
            </div>
                    
            <div class="clear"></div>
        </div><!-- .inner -->
</div><!-- #container -->
        
<?php get_footer() ?>