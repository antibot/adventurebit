<?php get_header(); echo '</div>'; // close #background ?>
    
<?php the_post(); global $wpdb;
    $terms = get_the_terms(get_the_id(), 'portfolio_category');
    if($terms) {
        foreach($terms as $term) {
            $pcats[$term->slug] = $term->name;
            $inside[] = "$wpdb->terms.slug = '{$term->slug}'";
        }
        $inside = 'AND ('. join(' OR ', $inside). ')';
    }
?>
<div id="container">
        <div class="inner">
            
            <div id="post-<?php the_ID() ?>" class="single portfolio-post">
                <h1 class="post-title super-heading"><span class="post-cat"><?php echo join(', ', (array)$pcats);//the_terms(get_the_id(), 'portfolio_category'); ?></span><?php the_title() ?></h1>
                <div class="post-content">
                    <?php $iwak->the_content() ?>
                    
                    <?php if($iwak->o['portfolio']['display_shareto']): ?>
                    <div class="sharebox box">
                        <h3 class="boxtitle"><?php _e('Share to', THEME_NAME); ?></h3>
                        <?php echo $iwak->sociable_bookmarks(); ?>
                        <div class="clear"></div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="post-excerpt"><img class="icon-info" src="<?php echo THEME_URL. '/images/icons/info47.png'; ?>" />
                    <?php the_excerpt(); if($project_link = get_post_meta(get_the_id(), 'project_link', true)): ?>
                    <a target="_blank" href="<?php echo $project_link; ?>"><?php $project_link_text = get_post_meta(get_the_id(), 'project_link_text', true); echo $project_link_text ? $project_link_text : __('Launch Project', THEME_NAME); ?></a><?php endif; ?>
                </div>
                
                <div class="sidebar">
                    <?php iwak_get_sidebar('Portfolio Post Sidebar', false); ?>
                </div>
                
            </div><!-- .post -->

            <div id="similar-works" class="panel panel-works works">
                <?php
                    $pid = get_the_id();
                    $posts_per_belt = $iwak->o['home']['posts_per_belt'] > 24 ? 24 : $iwak->o['home']['posts_per_belt'];
                    $querystr = "
                        SELECT * 
                        FROM $wpdb->posts
                        LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
                        LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
                        LEFT JOIN $wpdb->terms ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
                        WHERE $wpdb->posts.post_type = 'portfolio' 
                        AND $wpdb->posts.post_status = 'publish'
                        AND $wpdb->posts.id != $pid
                        AND $wpdb->term_taxonomy.taxonomy = 'portfolio_category'
                        $inside
                        GROUP BY $wpdb->posts.ID
                        ORDER BY $wpdb->posts.post_date DESC
                        LIMIT 0, $posts_per_belt
                    ";
                    $similar_works = $wpdb->get_results($querystr, OBJECT);//var_dump($similar_works);//new WP_Query(array('post_type'=>'portfolio', 'posts_per_page' =>$iwak->o['home']['posts_per_belt'], 'pcat'=>array_keys($pcats), 'post__not_in'=>(array)get_the_id())); ?>

                <h3><?php _e('Similar Projects', THEME_NAME); ?></h3>
                <ul class="belt">
                    <?php $iwak->list_posts($similar_works, array('itemtag'=>'li', 'num_of_posts'=>-1, 'more_text'=>'', 'thumbnail_size'=>array(220, 205), 'show_excerpt'=>0, 'group_size'=>0, 'show_morelink'=>1)); ?>
                </ul>
                <span class="more"><a></a></span>
            </div>
                                    
            <div class="clear"></div>
        </div><!-- .inner -->
</div><!-- #container -->
        
<?php get_footer() ?>