            <div id="latest-works" class="panel works panel-works">
                <?php global $iwak; $posts_per_belt = $iwak->o['home']['posts_per_belt'] > 24 ? 24 : $iwak->o['home']['posts_per_belt']; 
                    //$latest_works = new WP_Query(array('post_type'=>'portfolio', 'posts_per_page' =>$posts_per_belt, 'pcat'=>$iwak->o['home']['belt_works_category']));
                    if(!empty($iwak->o['home']['belt_works_category']) && $iwak->o['home']['belt_works_category'] != -1) {
                        $inside = "AND $wpdb->term_taxonomy.taxonomy = 'portfolio_category' AND $wpdb->terms.term_id = {$iwak->o['home']['belt_works_category']}";
                    }
                    if(!empty($iwak->o['home']['belt_posts_category'])) {
                        $or = "OR ($wpdb->posts.post_type = 'post' AND $wpdb->posts.post_status = 'publish'";
                        if($iwak->o['home']['belt_posts_category'] != '-1')
                            $or .= " AND $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->terms.term_id = {$iwak->o['home']['belt_posts_category']}";
                        $or .= ")";
                    }
                    $querystr = "
                        SELECT * 
                        FROM $wpdb->posts
                        LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
                        LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
                        LEFT JOIN $wpdb->terms ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
                        WHERE ($wpdb->posts.post_type = 'portfolio' AND $wpdb->posts.post_status = 'publish' $inside)
                        $or
                        GROUP BY $wpdb->posts.ID
                        ORDER BY $wpdb->posts.post_date DESC
                        LIMIT 0, $posts_per_belt
                    ";
                    $latest_works = $wpdb->get_results($querystr, OBJECT);//var_dump($similar_works);//new WP_Query(array('post_type'=>'portfolio', 'posts_per_page' =>$iwak->o['home']['posts_per_belt'], 'pcat'=>array_keys($pcats), 'post__not_in'=>(array)get_the_id())); ?>

                <h3><?php _e('Latest Works', THEME_NAME); ?></h3>
                <ul class="belt">
                    <?php $iwak->list_posts($latest_works, array('itemtag'=>'li', 'num_of_posts'=>-1, 'more_text'=>'', 'thumbnail_size'=>array(220, 205), 'show_excerpt'=>0, 'group_size'=>0, 'show_morelink'=>1)); ?>
                </ul>
                <span class="more"><a></a></span>
            </div>