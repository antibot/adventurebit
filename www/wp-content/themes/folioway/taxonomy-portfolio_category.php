<?php get_header(); echo '</div>'; // close #background

    $category = $wp_query->get_queried_object();
    //$category_template = get_option($category->term_id.'-cat_template');
    $posts_per_page = get_option($category->term_id. '-cat_posts');
    $orderby = get_option($category->term_id. '-cat_orderby');
    $order = get_option($category->term_id. '-cat_order');
    //$visual_effect = get_option($category->term_id. '-cat_visual_effect');
    $thumbnail_size = array(220,205);
    $group_size = 0; // no group
    //$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    query_posts(array('post_type'=>'portfolio', 'paged'=>$paged, 'posts_per_page' =>$posts_per_page, 'pcat'=>$category->slug, 'orderby'=>$orderby, 'order'=>$order));
?>

<div id="container">
    <div class="inner">
        <div id="portfolio" class="index works">
        
            <ul id="portfolio-filter" class="horiz-list">
                <li><a ref="all" title="" href="#all"><?php _e('All', THEME_NAME); ?></a></li>
                
                <?php
                $terms = get_term_children($category->term_id, 'portfolio_category'); 
                
                foreach( (array)$terms as $term ): $term = get_term_by('id', $term, 'portfolio_category'); ?>
                    <li><a ref="<?php echo $term->slug; ?>" title="" href="#<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></li>
                <?php endforeach; ?>
                
            </ul>
            
            <ul id="portfolio-list" class="horiz-list">
            <?php $iwak->list_posts($wp_query, array('itemtag'=>'li', 'num_of_posts'=>-1, 'more_text'=>'', 'thumbnail_size'=>$thumbnail_size, 'show_excerpt'=>0, 'group_size'=>$group_size, 'show_morelink'=>1)); ?>
            </ul>
            
            <div class="pagination"><?php $iwak->get_pagination(4); ?></div>
            <div class="clear"></div>
        </div>
    </div><!-- .inner -->
</div><!-- #container -->
        
<?php get_footer() ?>