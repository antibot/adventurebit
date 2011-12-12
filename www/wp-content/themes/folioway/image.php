<?php get_header(); echo '</div>'; // close #background ?>
    
<?php the_post(); 
?>
<div id="container">
        <div class="inner">
            
            <div id="post-<?php the_ID() ?>" class="single media">
                <h1 class="post-title super-heading"><span class="post-cat"><?php _e('Media', THEME_NAME); ?></span><?php the_title() ?></h1>
                <div class="post-content">
                    <?php $iwak->the_thumbnail( $iwak->get_post_image_url(), array(640, 0) ); ?>
                    
                    <?php if($iwak->o['media']['display_shareto']): ?>
                    <div class="sharebox box">
                        <div class="boxtitle"><?php _e('Share to', THEME_NAME); ?></div>
                        <?php echo $iwak->sociable_bookmarks(); ?>
                        <div class="clear"></div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="post-excerpt"><img class="icon-info" src="<?php echo THEME_URL. '/images/icons/info47.png'; ?>" /><?php the_content() ?></div>
                
                <div class="sidebar">
                    <?php iwak_get_sidebar(null, false); ?>
                </div>
                
            </div><!-- .post -->

            <?php get_template_part('latest-works'); ?>
                                    
            <div class="clear"></div>
        </div><!-- .inner -->
</div><!-- #container -->
        
<?php get_footer() ?>