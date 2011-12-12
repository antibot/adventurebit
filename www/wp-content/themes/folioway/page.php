<?php get_header(); echo '</div>'; // close #background ?>
    
<div id="container">
        <div class="inner">
        
            <div id="content">
                <?php the_post(); ?>
                
                    <div id="post-<?php the_ID() ?>" class="single page">
                        <h1 class="post-title super-heading"><?php the_title() ?></h1>
                        <div class="post-content">
                            <?php $iwak->the_content() ?>
                        </div>
                    </div><!-- .post -->
                <?php comments_template() ?>
                
            </div><!-- #content -->
                
            <div id="archive-sidebar" class="sidebar">
                <?php iwak_get_sidebar('Page Sidebar'); ?>
            </div>
                    
            <div class="clear"></div>
        </div><!-- .inner -->
</div><!-- #container -->
        
<?php get_footer() ?>