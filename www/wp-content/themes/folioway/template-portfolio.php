<?php
/*
Template Name: Portfolio
*/
?>

<?php get_header(); echo '</div>'; // close #background 

    $posts_per_page = -1;//$iwak->o['portfolio']['posts_per_page'];
    $orderby = $iwak->o['portfolio']['orderby'];
    $order = $iwak->o['portfolio']['order'];
    $thumbnail_size = array(220,205);
    $group_size = 0; // no group
    //$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $posts = new WP_Query(array('post_type'=>'portfolio', 'paged'=>$paged, 'posts_per_page' =>$posts_per_page, 'orderby'=>$orderby, 'order'=>$order));
?>

<div id="container">
    <div class="inner">
        <div id="portfolio" class="index works">
        
            <ul id="portfolio-filter" class="horiz-list">
                <li><a ref="all" title="" href="#all"><?php _e('All', THEME_NAME); ?></a></li>
                
                <?php
                $args = array(
                  //'orderby'      => $orderby,
                  'number'   => $number,
                  //'pad_counts'   => $pad_counts,
                  //'hierarchical' => 0,
                  'parent'=>0,
                );
                $terms = get_terms('portfolio_category', $args ); 
                
                foreach( (array)$terms as $term ): ?>
                    <li><a ref="<?php echo $term->slug; ?>" title="" href="#<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></li>
                <?php endforeach; ?>
                
            </ul>
            
            <ul id="portfolio-list" class="horiz-list">
            <?php $iwak->list_posts($posts, array('itemtag'=>'li', 'num_of_posts'=>-1, 'more_text'=>'', 'thumbnail_size'=>$thumbnail_size, 'show_excerpt'=>0, 'group_size'=>$group_size, 'show_morelink'=>1)); ?>
            </ul>
            
            <!--<div class="pagination"><?php $iwak->get_pagination(4); ?></div>-->
            <div class="clear"></div>
        </div>
    </div><!-- .inner -->
</div><!-- #container -->
        
<?php get_footer() ?>