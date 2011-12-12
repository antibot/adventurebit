<?php

if (class_exists("WP_Widget")) {

    /*class  extends WP_Widget {
    
        function () {
            $widget_ops = array('classname' => 'iwak-widget iwak-widget-rc', 'description' => __('The most recent posts', THEME_NAME));
            $this->WP_Widget('iwak_rp', THEME_NAME . ' - Recent Posts', $widget_ops);
        }
        
        function widget($args, $instance) {
        
        }
        
        function update($new_instance, $old_instance) {
        
        }
        
        function form($instance) {
        
        }
        
    }
    */
    
    /*    2011-2-7
     *
     *    1. Now fetch any type posts -  post, page, portfolio or any other custom post types
     *    2. Remove widget "single page" which ever used to fetch 'page'
     *
     *    problem: orderby doesn't work, i will see this issue later
     */
    class iwakWidgetPost extends WP_Widget {
    
        function iwakWidgetPost() {
            $widget_ops = array('classname' => 'iwak-widget iwak-widget-post', 'description' => __('Load content of a specified post', THEME_NAME));
            $this->WP_Widget('iwak_post', THEME_NAME . ' - Single Post', $widget_ops);
        }
        
        function widget($args, $instance) {
            global $iwak;
        
            extract($args);

            $post = $instance['post_id'];
            $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);

            $vars = array('showposts' => 1, 'post_status' => 'publish', 'caller_get_posts' => 1, 'p'=>$post, 'post_type'=>'any');
            $r = new WP_Query($vars);

            echo $before_widget;

            if(!empty($title))
                echo $before_title . $title . $after_title;
            
            $iwak->list_posts($r, $instance);
            
            echo $after_widget;
            
            wp_reset_query();  // Restore global post data stomped by the_post().
        }
        
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['show_thumbnail'] = 0;
            $instance['show_title'] = 1;
            $instance['show_excerpt'] = 1;
            $instance['stuff_to_show'] = isset($new_instance['stuff_to_show']) ? $new_instance['stuff_to_show'] : 'entire';
            $instance['thumbnail_size'] = $new_instance['thumbnail_size'];
            $instance['content_length'] = (int)$new_instance['content_length'];
            $instance['post_id'] = $new_instance['post_id'];

            return $instance;
        }
        
        function form($instance) {
        
            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
            if( !isset($instance['show_thumbnail']) ) $instance['show_thumbnail'] = 0;
            if( !isset($instance['show_title']) ) $instance['show_title'] = 1;
            if( !isset($instance['show_excerpt']) ) $instance['show_excerpt'] = 1;
            if( !isset($instance['thumbnail_size']) ) $instance['thumbnail_size'] = '2.55:1';
            if ( !$number = (int) $instance['num_of_posts'] )
                $number = 1;
            $posts = get_posts('numberposts=-1&orderby=type&post_type=any');
            ?>
            
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEMENAME); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
            </p>
            
            <p>
                <?php $option_name = 'post_id'; _e('Specify a post: ', THEME_NAME); ?>
                <select id="<?php echo $this->get_field_id( $option_name); ?>" name="<?php echo $this->get_field_name($option_name); ?>" class="widefat">
                    <?php
                      foreach ( $posts as $post ) {
                        $selected = ( $post->ID == $instance[$option_name] ) ? 'selected="selected"' : '' ;
                        $option = '<option value="'.$post->ID.'" '.$selected.' >['.$post->post_type. '] '. $post->post_title.'</option>';
                        echo $option;
                      }
                    ?>
                </select>
              
              </p>
                        
            <div class="splitter"></div>

            <?php
        }

        
    }
    
    class iwakWidgetTwitter extends WP_Widget {
    
        function iwakWidgetTwitter() {
            $widget_ops = array('classname' => 'iwak-widget iwak-widget-twitter', 'description' => __('Twitter updates', THEME_NAME));
            $control_ops = array('width' => 251);
            $this->WP_Widget('iwak_twitter', THEME_NAME . ' - Twitter', $widget_ops, $control_ops);
        }
        
        function widget($args, $instance) {
        
            if(empty($instance['username']))
                return;
                
            extract($args);
            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
            
            echo $before_widget;
            if(!empty($title))
                echo $before_title . $title . $after_title;
                ?>
                    <ul class="tweets"><?php _e('Loading...', THEME_NAME); ?></ul>
                    
                    <script type="text/javascript">
                        
                        function distweets(ob)
                        {
                            // This is the callback function
                            var i = 0;
                            var container=jQuery('.iwak-widget-twitter ul');
                            container.html('');
                         
                            jQuery(ob).each(function(el) {
                                // ob contains all the tweets
                                before = i == 0 ? '<li class="first">' : '<li>';
                                str = before + formatTwitString(this.text) + '<a class="time" href="http://twitter.com/' + '<?php echo $instance['username']; ?>' + '/statuses/' + this.id + '">' + relativeTime(this.created_at) + '</a></li>';
                                // Adding the tweet to the container
                                container.append(str);
                                i ++;
                            });
                        }
                        
                        function formatTwitString(str)
                        {
                            // This function formats the tweet body text
                         
                            str=' '+str;
                         
                            str = str.replace(/((ftp|https?):\/\/([-\w\.]+)+(:\d+)?(\/([\w/_\.]*(\?\S+)?)?)?)/gm,'<a href="$1" target="_blank">$1</a>');
                            // The tweets arrive as plain text, so we replace all the textual URLs with hyperlinks
                         
                            str = str.replace(/([^\w])\@([\w\-]+)/gm,'$1@<a href="http://twitter.com/$2" target="_blank">$2</a>');
                            // Replace the mentions
                         
                            str = str.replace(/([^\w])\#([\w\-]+)/gm,'$1<a href="http://twitter.com/search?q=%23$2" target="_blank">#$2</a>');
                            // Replace the hashtags
                         
                            return str;
                        }
                         
                        function relativeTime(pastTime)
                        {
                            // Generate a JavaScript relative time for the tweets
                         
                            var origStamp = Date.parse(pastTime);
                            var curDate = new Date();
                            var currentStamp = curDate.getTime();
                            var difference = parseInt((currentStamp - origStamp)/1000);
                         
                            if(difference < 0) return false;
                         
                            if(difference <= 5)          return "Just now";
                            if(difference <= 20)         return "Seconds ago";
                            if(difference <= 60)         return "A minute ago";
                            if(difference < 3600)        return parseInt(difference/60)+" minutes ago";
                            if(difference <= 1.5*3600)   return "One hour ago";
                            if(difference < 23.5*3600)   return Math.round(difference/3600)+" hours ago";
                            if(difference < 1.5*24*3600) return "One day ago";
                         
                            // If the tweet is older than a day, show an absolute date/time value;
                         
                            var dateArr = pastTime.split(' ');
                         
                            return dateArr[3].replace(/\:\d+$/,'')+' '+dateArr[1]+' '+dateArr[2]+
                            (dateArr[5]!=curDate.getFullYear()?' '+dateArr[5]:'');
                        }        
                        
                    </script>
                    <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
                    <script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $instance['username']; ?>.json?callback=distweets&count=<?php echo $instance['number']; ?>"></script>
                    
                <?php
            echo $after_widget;
            
        }
        
        function update($new_instance, $old_instance) {
        
            $instance['title'] = strip_tags(stripslashes($new_instance['title']));
            $instance['username'] = trim($new_instance['username']);
            $instance['number'] = (int)$new_instance['number'];
            $instance['followtext'] = strip_tags($new_instance['followtext']);
            return $instance;
        }
        
        function form($instance) {
        
            $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Twitter Updates';
            $username = $instance['username'];
            $number = isset($instance['number']) ? abs($instance['number']) : 1;
            $followtext = empty($instance['followtext']) ? 'Follow us' : $instance['followtext'];
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', THEME_NAME ) ?><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e( 'Twitter Username: ', THEME_NAME ) ?>
                <br /><input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" /></label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('followtext'); ?>"><?php _e( 'Follow text: ', THEME_NAME ) ?>
                <br /><input class="widefat" id="<?php echo $this->get_field_id('followtext'); ?>" name="<?php echo $this->get_field_name('followtext'); ?>" type="text" value="<?php echo $followtext; ?>" /></label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of updates to show: ', THEME_NAME); ?><input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></label>
                <br /><small><?php _e('(at most 10)', THEME_NAME); ?></small>
            </p>
            <?php
        }
        
    }
    
    /*    2011-2-6
     *
     *    --- changes below are also made to widget "single post" and "single page"
     *
     *    1. Remove option "display conent" and "content length", instead, display the excerpt and give length control back to global option "excerpt length"
     *        -- reason: it seems there are no such requirements of to display content (keep the structure) in a widget, people are used to display excerpt there
     *    2. Remove the logic that don't display posts or pages which are already loaded another area (say the slider) of the same page
     *        -- reason: as a part of intelligent features, auto-prevent duplicate posts in one page seems not become a respect or apperaciated feature, people used to avoid duplicate posts manually
     *    3. Add an option "All" at top of the categories list
     */
    class iwakWidgetRP extends WP_Widget {
    
        function iwakWidgetRP() {
            $widget_ops = array('classname' => 'iwak-widget iwak-widget-rp', 'description' => __('The most recent posts', THEME_NAME));
            $this->WP_Widget('iwak_rp', THEME_NAME . ' - Recent Posts', $widget_ops);
            
            add_action( 'save_post', array(&$this, 'flush_widget_cache') );
            add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
            add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
        }
        
        function widget($args, $instance) {
            global $iwak;
        
            $cache = wp_cache_get(THEME_NAME . '_recent_posts', THEME_NAME);

            if ( !is_array($cache) )
                $cache = array();

            if ( isset($cache[$args['widget_id']]) )
                return $cache[$args['widget_id']];

            ob_start();
            extract($args);

            $query_vars = array('post_status' => 'publish', 'caller_get_posts' => 1);

            if($instance['category_id'] != '-1')
                $query_vars['cat'] = $instance['category_id'];
                
            if ( !$number = (int) $instance['num_of_posts'] )
                $number = 1;
            else if ( $number < 1 )
                $number = 1;
            else if ( $number > 15 )
                $number = 15;
            $query_vars['posts_per_page'] = $number;
            
            /*if(is_home())
                $vars += array('post__not_in'=>$iwak->featured_ids);
                */
                
            $r = new WP_Query($query_vars);

            $instance['num_of_posts'] = $number;

            echo $before_widget;

            $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
            if(!empty($title))
                echo $before_title . $title . $after_title;
            
            $iwak->list_posts($r, $instance);
            
            echo $after_widget;
            
            wp_reset_query();  // Restore global post data stomped by the_post().
            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_add(THEME_NAME . '_recent_posts', $cache, THEME_NAME);
        }
        
        function update( $new_instance, $old_instance ) {
        
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['num_of_posts'] = (int) $new_instance['num_of_posts'];
            $instance['show_thumbnail'] = 0;
            $instance['show_title'] = 1;
            $instance['show_excerpt'] = 1;
            $instance['stuff_to_show'] = isset($new_instance['stuff_to_show']) ? $new_instance['stuff_to_show'] : 'entire';
            $instance['thumbnail_size'] = $new_instance['thumbnail_size'];
            //$instance['content_length'] = (int)$new_instance['content_length'];
            $instance['category_id'] = $new_instance['category_id'];
            $this->flush_widget_cache();

            return $instance;
            
        }

        function flush_widget_cache() {
            wp_cache_delete(THEME_NAME . '_recent_posts', THEME_NAME);
        }
        
        function form($instance) {
        
            $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Recent Posts';
            if( !isset($instance['show_thumbnail']) ) $instance['show_thumbnail'] = 1;
            if( !isset($instance['show_title']) ) $instance['show_title'] = 0;
            //if( !isset($instance['show_content']) ) $instance['show_content'] = 1;
            if( !isset($instance['show_excerpt']) ) $instance['show_excerpt'] = 1;
            if( !isset($instance['thumbnail_size']) ) $instance['thumbnail_size'] = '2.55:1';
            if ( !$number = (int) $instance['num_of_posts'] )
                $number = 1;
            $categories = get_categories('hide_empty=0');
            if(!is_array($categories))
                $categories = array();
            ?>
            
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEMENAME); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
            </p>

            <p>

                <?php _e('Number of posts to show:', THEME_NAME); ?>
                
                    <label for="<?php echo $this->get_field_id('num_of_posts'); ?>">
                    <input id="<?php echo $this->get_field_id('num_of_posts'); ?>" name="<?php echo $this->get_field_name('num_of_posts'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></label>
                    <br /><small><?php _e('(at most 15)', THEME_NAME); ?></small>
            </p>
            
            <p>
                <?php $option_name = 'category_id'; _e('Specify a category: ', THEME_NAME); ?>
                <select id="<?php echo $this->get_field_id( $option_name); ?>" name="<?php echo $this->get_field_name($option_name); ?>" class="widefat">
                    <option value="-1" <?php if($instance[$option_name] == '-1') echo 'selected="selected"'; ?>><?php _e('All', THEME_NAME); ?></option>
                    <?php
                      foreach ( $categories as $cat ) {
                        $selected = ( $cat->cat_ID == $instance[$option_name] ) ? 'selected="selected"' : '' ;
                        $option = '<option value="'.$cat->cat_ID.'" '.$selected.' >'.$cat->cat_name.'</option>';
                        echo $option;
                      }
                    ?>
                </select>
              
            </p>
                        
            <div class="splitter"></div>

            <?php
        }
        
    }
    
    class iwakWidgetPP extends WP_Widget {
    
        function iwakWidgetPP() {
            $widget_ops = array('classname' => 'iwak-widget iwak-widget-rp', 'description' => __('The most recent portfolio posts', THEME_NAME));
            $this->WP_Widget('iwak_pp', THEME_NAME . ' - Recent Portfolio Posts', $widget_ops);
            
            add_action( 'save_post', array(&$this, 'flush_widget_cache') );
            add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
            add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
        }
        
        function widget($args, $instance) {
            global $iwak;
        
            $cache = wp_cache_get(THEME_NAME . '_recent_portfolio_posts', THEME_NAME);

            if ( !is_array($cache) )
                $cache = array();

            if ( isset($cache[$args['widget_id']]) )
                return $cache[$args['widget_id']];

            ob_start();
            extract($args);

            $query_vars = array('post_status' => 'publish', 'caller_get_posts' => 1, 'post_type'=>'portfolio');

            if($instance['category_id'] != '-1')
                $query_vars['portfolio_categories'] = $instance['category_id'];
                
            if ( !$number = (int) $instance['num_of_posts'] )
                $number = 1;
            else if ( $number < 1 )
                $number = 1;
            else if ( $number > 15 )
                $number = 15;
            $query_vars['posts_per_page'] = $number;
            
            /*if(is_home())
                $vars += array('post__not_in'=>$iwak->featured_ids);
                */
                
            $r = new WP_Query($query_vars);

            $instance['num_of_posts'] = $number;

            echo $before_widget;

            $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
            if(!empty($title))
                echo $before_title . $title . $after_title;
            
            $iwak->list_posts($r, $instance);
            
            echo $after_widget;
            
            wp_reset_query();  // Restore global post data stomped by the_post().
            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_add(THEME_NAME . '_recent_portfolio_posts', $cache, THEME_NAME);
        }
        
        function update( $new_instance, $old_instance ) {
        
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['num_of_posts'] = (int) $new_instance['num_of_posts'];
            $instance['show_thumbnail'] = isset($new_instance['show_thumbnail']) ? 1 : 0;
            $instance['show_title'] = isset($new_instance['show_title']) ? 1 : 0;
            //$instance['show_content'] = isset($new_instance['show_content']) ? 1 : 0;
            $instance['show_excerpt'] = isset($new_instance['show_excerpt']) ? 1 : 0;
            $instance['stuff_to_show'] = isset($new_instance['stuff_to_show']) ? $new_instance['stuff_to_show'] : 'entire';
            $instance['thumbnail_size'] = $new_instance['thumbnail_size'];
            //$instance['content_length'] = (int)$new_instance['content_length'];
            $instance['category_id'] = $new_instance['category_id'];
            $this->flush_widget_cache();

            return $instance;
            
        }

        function flush_widget_cache() {
            wp_cache_delete(THEME_NAME . '_recent_posts', THEME_NAME);
        }
        
        function form($instance) {
        
            $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Recent Posts';
            if( !isset($instance['show_thumbnail']) ) $instance['show_thumbnail'] = 1;
            if( !isset($instance['show_title']) ) $instance['show_title'] = 0;
            //if( !isset($instance['show_content']) ) $instance['show_content'] = 1;
            if( !isset($instance['show_excerpt']) ) $instance['show_excerpt'] = 1;
            if( !isset($instance['thumbnail_size']) ) $instance['thumbnail_size'] = '2.55:1';
            if ( !$number = (int) $instance['num_of_posts'] )
                $number = 1;
            $categories = get_terms('portfolio_categories', 'hide_empty=0');
            if(!is_array($categories))
                $categories = array();
            ?>
            
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEMENAME); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
            </p>

            <p>
                <?php $option_name = 'show_thumbnail'; ?>
                
                    <label for="<?php echo $this->get_field_id($option_name); ?>"><input name="<?php echo $this->get_field_name($option_name); ?>" id="<?php echo $this->get_field_id($option_name); ?>" type="checkbox" <?php if ($instance[$option_name]) echo ' checked=""'; ?> />
                    <?php _e('Show thumbnail ', THEME_NAME) ?></label><br />

                <?php $option_name = 'show_title'; ?>
                
                    <label for="<?php echo $this->get_field_id($option_name); ?>"><input name="<?php echo $this->get_field_name($option_name); ?>" id="<?php echo $this->get_field_id($option_name); ?>" type="checkbox" <?php if ($instance[$option_name]) echo ' checked=""'; ?> />
                    <?php _e('Show title ', THEME_NAME) ?></label><br />

                <?php $option_name = 'show_excerpt'; ?>
                
                    <label for="<?php echo $this->get_field_id($option_name); ?>"><input name="<?php echo $this->get_field_name($option_name); ?>" id="<?php echo $this->get_field_id($option_name); ?>" type="checkbox" <?php if ($instance[$option_name]) echo ' checked=""'; ?> />
                    <?php _e('Show excerpt ', THEME_NAME) ?></label><br />

                <?php _e('Number of posts to show:', THEME_NAME); ?>
                
                    <label for="<?php echo $this->get_field_id('num_of_posts'); ?>">
                    <input id="<?php echo $this->get_field_id('num_of_posts'); ?>" name="<?php echo $this->get_field_name('num_of_posts'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></label>
                    <br /><small><?php _e('(at most 15)', THEME_NAME); ?></small>
            </p>
            
            <p>
                <?php $option_name = 'category_id'; _e('Specify a category: ', THEME_NAME); ?>
                <select id="<?php echo $this->get_field_id( $option_name); ?>" name="<?php echo $this->get_field_name($option_name); ?>" class="widefat">
                    <option value="-1" <?php if($instance[$option_name] == '-1') echo 'selected="selected"'; ?>><?php _e('All', THEME_NAME); ?></option>
                    <?php
                      foreach ( $categories as $cat ) {
                        $selected = ( $cat->term_id == $instance[$option_name] ) ? 'selected="selected"' : '' ;
                        $option = '<option value="'.$cat->term_id.'" '.$selected.' >'.$cat->name.'</option>';
                        echo $option;
                      }
                    ?>
                </select><br /><br />
              
                <?php //get_template_part('/includes/settings.format'); ?>
            </p>
                        
            <div class="splitter"></div>

            <?php
        }
        
    }
    
    class iwakWidgetFlickr extends WP_Widget {
        
        function iwakWidgetFlickr() {
            $widget_ops = array('classname' => 'iwak-widget iwak-widget-flickr', 'description' => __('Flickr photos', THEME_NAME));
            $this->WP_Widget('iwak_flickr', THEME_NAME.' - Flickr', $widget_ops);
        }
        
        function widget($args, $instance) {
        
            if(empty($instance['id']))
                return;
                
            extract($args);
            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
            
            echo $before_widget;
                if(!empty($title)) {
                    $title = str_ireplace('flickr', '<span class="flickr_blue">flick</span><span class="flickr_purple">r</span>', $title);
                    echo $before_title . $title . $after_title;
                }
                ?>
                <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $instance['number']; ?>&amp;display=random&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $instance['id']; ?>"></script>        
                <?php
            echo $after_widget;
        }
        
        function update($new_instance, $old_instance) {
        
            $instance = $old_instance;
            $instance['title'] = strip_tags(stripslashes($new_instance['title']));
            $instance['id'] = trim($new_instance['id']);
            $instance['number'] = (int)$new_instance['number'];
            return $instance;
            
        }
        
        function form($instance) {
        
            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
            $id = $instance['id'];
            $number = isset($instance['number']) ? abs($instance['number']) : 6;
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', THEME_NAME ) ?><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e( 'Flickr ID: ', THEME_NAME ) ?>
                <br /><input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $id; ?>" /></label>
                <br /><small><?php _e('Don\'t know your id? Find it <a target="_blank" href="http://idgettr.com/">here</a>', THEME_NAME); ?></small>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of photos to show: '); ?><input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></label>
                <br /><small><?php _e('(at most 10)', THEME_NAME); ?></small>
            </p>
            <?php
        }
    }

    class iwakWidgetAds extends WP_Widget {
    
        function iwakWidgetAds() {
        
            $widget_ops = array('classname' => 'iwak-widget iwak-widget-ads', 'description' => __("Advertises"));
            $control_ops = array('width' => '355');
            $this->WP_Widget('iwak_ads', THEME_NAME.' - Advertises', $widget_ops, $control_ops);
            
        }
        
        function widget($args, $instance) {

            extract($args);
            $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);

            echo $before_widget;
                if(!empty($title))
                    echo $before_title . $title . $after_title;
                foreach( $instance['ads'] as $ad_code ) {
                    $c = $c == 'odd' ? 'even' : 'odd';
                    echo "<div class='ads $c'>";
                    echo $ad_code;
                    echo '</div>';
                }
            echo $after_widget;
            
        }
        
        function update($new_instance, $old_instance) {
        
            $instance = array();
            $instance['title'] = strip_tags(stripslashes($new_instance['title']));
            $instance['ads'] = array();
                
            foreach( $new_instance as $key => $value ) {
                $value = trim($value);
                if( strpos($key, 'ad_code') !== false && !empty($value) )
                    $instance['ads'][] = stripslashes($value);
            }
            return $instance;
            
        }
        
        function form($instance) {
            $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Sponsors';
            if(!isset($instance['ads']))
                $instance['ads'] = array();
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', THEME_NAME ) ?><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
            </p>
            
            
            <?php foreach( $instance['ads'] as $ad_index => $ad_code ) { ?>
                
            <div class="accordion">
                    <span class="widget-icon delete"></span>
                    <h4><?php echo ' Advertise No.'.$ad_index; ?></h4>
                    <textarea class="hidden" id="<?php echo $this->get_field_id($ad_index.'ad_code'); ?>" name="<?php echo $this->get_field_name($ad_index.'ad_code'); ?>"><?php echo $ad_code; ?></textarea>
            </div>
                
            <?php $i++; } ?>
                
            <p><br />
                <label for="<?php echo $this->get_field_id('new'.'ad_code'); ?>"><?php echo 'Advertise Code Here:'; ?><br />
                <textarea class="newad" id="<?php echo $this->get_field_id('new'.'ad_code'); ?>" name="<?php echo $this->get_field_name('new'.'ad_code'); ?>" rows="8" cols="45"></textarea></label>
            </p>
            <p>
                <a class="button add-ads" href="#add"><?php _e('Add Advertise', THEME_NAME); ?></a>
            </p>
            <div class="splitter"></div>
        <?php
        }
    }
    
    class iwakWidgetRC extends WP_Widget {
    
        function iwakWidgetRC() {
        
            $widget_ops = array('classname' => 'iwak-widget iwak-widget-rc', 'description' => __('Neat recent comments', THEME_NAME));
            $control_ops = array('width' => 251);
            $this->WP_Widget('iwak_rc', THEME_NAME.' - Recent Comments', $widget_ops, $control_ops);
            
            add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
            add_action( 'wp_set_comment_status', array(&$this, 'flush_widget_cache') );
        
        }
        
        function widget($args, $instance) {
        
            global $iwak, $wpdb, $comment;
            extract($args);
            
            $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
            if( $instance['hide_author_reply'] ) {
                $author_filter = "AND comment_author <> '". get_the_author()."'";
            }
            
            if( !$number = (int)$instance['number'] )
                $number = 10;
            else if( $number < 1 )
                $number = 1;
            else if( $number > 10 )
                $number = 10;
            
            if ( !$comments = wp_cache_get( THEME_NAME.'_recent_comments', THEME_NAME ) ) {
                $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = ''".$author_filter." ORDER BY comment_date_gmt DESC LIMIT ".$number); 
                wp_cache_add( THEME_NAME.'_recent_comments', $comments, THEME_NAME );
            }
            
            echo $before_widget;
                if(!empty($title))
                    echo $before_title . $title . $after_title;
                    
                if($comments) {
                    $i = 0;
                    echo '<ul class="recent-comments">';
                    foreach( $comments as $comment ) {
                        $i ++;
                        if( $instance['show_commenter_name'] )
                            $commenter = get_comment_author_link();
                        if( $instance['show_commenter_avatar'] )
                            $avatar = get_avatar($comment, 40);
                        $class = ($i==1) ? ' class="first"' : '';
                        $content = '<a class="recent-comment" href="'. get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '" title="' . $comment->comment_author . ' on ' . get_the_title($comment->comment_post_ID) . '">' . i_substr(get_comment_excerpt(), 0, 68). '</a>';
                        echo '<li'. $class. '>'. $avatar . '<span class="fn">'. $commenter. ' <span>says:</span></span>'. $content. '</li>';
                    }
                    echo '</ul>';
                }
            echo $after_widget;
        }
        
        function update($new_instance, $old_instance) {
        
            $instance['title'] = strip_tags(stripslashes($new_instance['title']));
            $instance['desc'] = strip_tags(stripslashes($new_instance['desc']));
            $instance['number'] = $new_instance['number'];
            $instance['hide_author_reply'] = isset($new_instance['hide_author_reply']) ? 1 : 0 ;
            $instance['show_commenter_name'] = isset($new_instance['show_commenter_name']) ? 1 : 0 ;
            $instance['show_commenter_avatar'] = isset($new_instance['show_commenter_avatar']) ? 1 : 0 ;
            $this->flush_widget_cache();
            return $instance;
            
        }
        
        function flush_widget_cache() {
            wp_cache_delete(THEME_NAME . '_recent_comments', THEME_NAME);
        }
        
        function form($instance) {
        
            $title = isset($instance['title']) ? esc_attr($instance['title']) : __('Recent Comments', THEME_NAME);
            $show_commenter_name = isset($instance['show_commenter_name']) ? $instance['show_commenter_name'] : 1;
            $show_commenter_avatar = isset($instance['show_commenter_avatar']) ? $instance['show_commenter_avatar'] : 1;
            $hide_author_reply = isset($instance['hide_author_reply']) ? $instance['hide_author_reply'] : 1;
            $number = isset($instance['number']) ? $instance['number'] : 2;
            $desc = isset($instance['desc']) ? $instance['desc'] : '';

            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', THEME_NAME ) ?><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
            </p>
            <p>
                <input name="<?php echo $this->get_field_name('show_commenter_name'); ?>" id="<?php echo $this->get_field_id('show_commenter_name'); ?>" type="checkbox" <?php if ($show_commenter_name) echo ' checked=""'; ?> />
                <label for="<?php echo $this->get_field_id('show_commenter_name'); ?>"><?php _e('Show commenter name ', THEME_NAME) ?></label><br />
                <input name="<?php echo $this->get_field_name('hide_author_reply'); ?>" id="<?php echo $this->get_field_id('hide_author_reply'); ?>" type="checkbox" <?php if ($hide_author_reply) echo ' checked=""'; ?> />
                <label for="<?php echo $this->get_field_id('hide_author_reply'); ?>"><?php _e('Do not show my replies ', THEME_NAME) ?></label><br />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:', THEME_NAME); ?><input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></label>
                <br /><small><?php _e('(at most 10)', THEME_NAME); ?></small>
            </p>
            <?php
        }
    }
    
}
?>