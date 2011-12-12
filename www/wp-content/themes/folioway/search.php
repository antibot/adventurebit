<?php get_header(); echo '</div>'; // close #background ?>

<div id="container">
        <div class="inner">
        
            <div id="content" class="index archive">
                <h1 class="super-heading">
                    <?php _e('Search: ', THEME_NAME); the_search_query(); ?>
                </h1>

                <?php if (have_posts()) : while ( have_posts() ) : the_post(); $i++; ?>
                    <div id="post-<?php the_ID() ?>" class="post entry<?php echo $i == 1 ? ' first' : ''; ?>">
                        <h2 class="post-title"><span class="day"><?php the_time('d');?></span><span class="month"><?php the_time('M'); ?></span><?php $iwak->the_post_link(get_the_title()); ?></h2>
                        
                        <?php if($src = $iwak->get_post_image_url(array(640, 240))): ?>
                            <a title="<?php echo get_the_title(); ?>" href="<?php echo get_permalink(); ?>">
                                <img class="thumbnail" src="<?php echo $src; ?>" />
                            </a>
                        <?php endif; ?>
                        
                        <div class="post-content"><?php $iwak->the_content(array('more_text'=>'Continue Reading')); ?></div>
                        
                        <div class="post-meta">
                            <?php _e('Posted by ', THEME_NAME); the_author_posts_link(); 
                            _e(' in ', THEME_NAME); echo get_the_category_list(', '); 
                            _e(', Followed with ', THEME_NAME); comments_popup_link( __('0 Comment', THEME_NAME), __('1 Comment', THEME_NAME), __('% Comments', THEME_NAME) ); 
                            //edit_post_link( __( 'edit', THEME_NAME ), '(', ')' ); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            
                <div class="pagination"><?php $iwak->get_pagination(10) ?></div>
                
                <?php else: get_template_part('noresults'); ?>

                <?php endif; ?>
            
            </div><!-- #content -->
                
            <div id="archive-sidebar" class="sidebar">
                <?php iwak_get_sidebar('Blog Sidebar'); ?>
            </div>
                    
            <div class="clear"></div>
        </div><!-- .inner -->
</div><!-- #container -->
        
<?php get_footer() ?>