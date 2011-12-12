<?php 

class iWaK_Cache {

    function __construct() {
        add_action('save_post', array(&$this, 'flush_cache'));
        add_action('deleted_post', array(&$this, 'flush_cache'));
        add_action('switch_theme', array(&$this, 'flush_cache'));
    }
    
    function flush_cache() {
        wp_cache_delete('iwak_category_list', THEME_NAME);
        wp_cache_delete('iwak_tag_list', THEME_NAME);
        wp_cache_delete('iwak_post_list', THEME_NAME);
        wp_cache_delete('iwak_page_list', THEME_NAME);
    }
        
    function get_term_list($taxonomy) {
        $cache_name = 'iwak_'. $taxonomy. '_list';
        $list = wp_cache_get($cache_name, THEME_NAME);
        if(!is_array($list)) {
            $list = array();
            $terms = get_terms($taxonomy, 'hide_empty=0');
            $list[-1] = __('&nbsp;', THEME_NAME); // this should be changed later
            foreach ( $terms as $term ) {
                $list[$term->term_id] = $term->name;
            }
            wp_cache_add($cache_name, $list, THEME_NAME);
        }
        
        return $list;
    }
    
    function get_ptypes_list() {
        $excludes = array('mediapage', 'revision', 'nav_menu_item');
        
        $cache_name = 'iwak_ptypes_list';
        $ptypes_list = wp_cache_get($cache_name, THEME_NAME);
        if(!is_array($ptypes_list)) {
            $ptypes_list = array();
            $post_types = get_post_types(null, 'objects');
            foreach ( $post_types as $post_type ) {
                if(in_array($post_type->name, $excludes))
                    continue;
                $ptypes_list[$post_type->name] = $post_type->labels->singular_name;
            }
            wp_cache_add($cache_name, $ptypes_list, THEME_NAME);
        }
        
        return $ptypes_list;
    }
    
    function get_post_list($post_type = 'post') {
        $cache_name = 'iwak_'. $post_type. '_list';
        $post_list = wp_cache_get($cache_name, THEME_NAME);
        if(!is_array($post_list)) {
            $post_list = array();
            $posts = get_posts('numberposts=-1&post_type='. $post_type);
            foreach ( $posts as $post ) {
                $post_list[$post->ID] = $post->post_title;
            }
            wp_cache_add($cache_name, $post_list, THEME_NAME);
        }
        
        return $post_list;
    }
    
    function get_page_list() {
        return $this->get_post_list('page');
    }
        
    function get_category_list() {
        $cache = wp_cache_get('iwak_category_list', THEME_NAME);
        if(!is_array($cache)) {
            $cache = array();
            $categories = get_categories('hide_empty=0');
            foreach ( $categories as $category ) {
                $cache[$category->term_id] = $category->name;
            }
            wp_cache_add('iwak_category_list', $cache, THEME_NAME);
        }
        
        return $cache;
    }
        
    function get_link_categories() {
        $cache = wp_cache_get('iwak_link_categories', THEME_NAME);
        if(!is_array($cache)) {
            $cache = array();
            $categories = get_categories('type=link');
            foreach ( $categories as $category ) {
                $cache[$category->term_id] = $category->name;
            }
            wp_cache_add('iwak_link_categories', $cache, THEME_NAME);
        }
        
        return $cache;
    }
    
    function get_tag_list() {
        $cache = wp_cache_get('iwak_tag_list', THEME_NAME);
        if(!is_array($cache)) {
            $cache = array();
            $tags = get_tags('hide_empty=0');
            foreach ( $tags as $tag ) {
                $cache[$tag->term_id] = $tag->name;
            }
            wp_cache_add('iwak_tag_list', $cache, THEME_NAME);
        }
        
        return $cache;
    }
    
    function get_sticky_posts() {
        $cache = wp_cache_get('iwak_sticky_posts', THEME_NAME);
        if(!is_array($cache)) {
            $cache = array();
            $tags = get_tags('hide_empty=0');
            foreach ( $tags as $tag ) {
                $cache[$tag->term_id] = $tag->name;
            }
            wp_cache_add('iwak_sticky_posts', $cache, THEME_NAME);
        }
        
        return $cache;
    }
    
    function get_widget_areas() {
        $cache = wp_cache_get('iwak_widget_areas', THEME_NAME);
        if(!is_array($cache)) {
            $options = iwak_get_options();
            // The reason why not do an is_array verification here is if it is not array, then it must be a bug,
            // we can get an error message from here thus know something is wrong
            $cache = empty($options['widget_areas']) ? array() : $options['widget_areas'];
            wp_cache_add('iwak_widget_areas', $cache, THEME_NAME);
        }
        
        return $cache;
    }
    
    function get_featured_posts() {
        $cache = wp_cache_get('iwak_featured_posts', THEME_NAME);
        if(!is_array($cache)) {
            $options = iwak_get_options();
            $thelist = $options['featured_posts'];
            $number_of_posts = intval($options['featured']['number_of_posts']);
            if($number_of_posts > 0) {
                $limit = "LIMIT 0, $number_of_posts";
            }
                
            if(empty($thelist))
                $thelist = array();
            
            /*// Return if limit meet
            if(!$options['featured']['loadbefore'] && count($thelist) >= $number_of_posts) {
                got to do something here
            }*/
            
            // Add items match featured rules, remove items not match featured rules
            // Run it no matter if there are rules, as long as there are 'status-auto' items and 'autoload' is enabled
            if( $options['featured']['autoload'] ) {
                $filters = (array)$options['filters'];
                            
                $includes = array();
                $excludes = array();
                global $wpdb;
                foreach($filters as $filter) {
                    if($filter['exclude'])
                        $thearray =& $excludes;
                    else
                        $thearray =& $includes;
                    
                    switch($filter['type']) {
                        case 'sticky':
                            if(!$stickies) {
                                $stickies = implode(',', get_option('sticky_posts'));
                                if(empty($stickies))
                                    break;
                                   
                                $thearray[] = "$wpdb->posts.ID IN ($stickies)";
                            }
                            break;
                        default:
                            $thearray[] = "$wpdb->terms.term_id = '$filter[id]'";
                            break;
                    }
                }

                if(!empty($includes)) {
                    $orderby = implode(' DESC, ', $includes). ' DESC';
                    $includes = implode(' OR ', $includes);

                    $query_str = "SELECT $wpdb->posts.ID, $wpdb->posts.post_date FROM $wpdb->posts
                        LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
                        LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
                        LEFT JOIN $wpdb->terms ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
                        WHERE ($includes)
                        AND $wpdb->posts.post_status = 'publish'
                        GROUP BY $wpdb->posts.ID
                        ORDER BY $wpdb->posts.post_date DESC
                        $limit
                        ";
                    
                    $eligibles = $wpdb->get_col($query_str);

                    if(!empty($excludes)) {
                        $excludes = implode(' OR ', $excludes);
                        
                        $query_str = "SELECT $wpdb->posts.ID FROM $wpdb->posts
                            LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
                            LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
                            LEFT JOIN $wpdb->terms ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
                            WHERE ($excludes)
                            AND $wpdb->posts.post_status = 'publish'
                            GROUP BY $wpdb->posts.ID
                            ";
                        
                        $eligibles = array_diff($eligibles, $wpdb->get_col($query_str));
                    }
                } else
                    $eligibles = array();

                $duplicates = array();
                foreach($thelist as $index=>$post) {
                    if(in_array($post['id'], $eligibles))
                        $duplicates[] = $post['id'];
                    elseif($post['status'] == 'auto' && !in_array($post['id'], $eligibles))
                        // Remove auto and ineligible items from thelist
                        unset($thelist[$index]);
                }

                // Remove duplicate (already exists in thelist) items from eligibles
                $eligibles = array_diff($eligibles, $duplicates);

                // Remove duplicate item from eligible items and produce eligiblelist
                $eligiblelist = array();
                foreach($eligibles as $post_id)
                    $eligiblelist[] = array_merge(
                                            array('type'=>'post',
                                                'id'=>$post_id,
                                                'status'=>'auto'
                                            ),
                                            $options['featured_defaults']
                                        );
                    /*$eligiblelist[] = array('type'=>'post',
                                                'id'=>$post_id,
                                                'status'=>'auto',
                                                'display_content'=>$options['featured']['display_content'],
                                                'display_comments_link'=>$options['general']['display_comments_link'],
                                                'buttons'=>$options['featured']['buttons']
                                        );
*/

                if($options['featured']['loadbefore'])
                    $thelist = array_merge($eligiblelist, $thelist);
                else
                    $thelist = array_merge($thelist, $eligiblelist);                
            }
            $cache = isset($limit) ? array_slice($thelist, 0, $number_of_posts) : $thelist;
            wp_cache_add('iwak_featured_posts', $cache, THEME_NAME);
        }
        
        return $cache; 
    }
}
?>