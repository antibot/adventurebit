<?php
/*
Plugin Name: Recent Posts Widget Unlimited
Plugin URI: http://www.geekhelpguide.com/wordpress/bypass-wordpress-recent-posts-widget-15-post-limit/
Description: A Recent Posts widget that bypasses the 15-post limitation.
Author: helpgeek
Author URI: http://www.geekhelpguide.com/
Version: 1.0
*/

/* ======= Custom Extended Recent Posts Widget ======== */
class GHGPostWidget extends WP_Widget {
  function GHGPostWidget()
  {
    parent::WP_Widget(false, 'Recent Posts Widget Unlimited');
  }
  function form($instance)
  {
    /* Set up some default widget settings. */
    $defaults = array( 'title' => 'Recent Posts', 'num_posts' => '15');
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Recent Posts'); ?></label>
      <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" style="width:100%"/>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('num_posts'); ?>">Number of posts to show:</label>
      <input id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" value="<?php echo $instance['num_posts']; ?>" style="width:40px;" />
    </p>
<?php

  }
  function update($new_instance, $old_instance)
  {
    return $new_instance;
  }
  function widget($args, $instance)
  {
    extract( $args );

    /* User-selected settings. */
    $title = apply_filters('widget_title', $instance['title'] );
    $num_posts = $instance['num_posts'];

    /* Before widget (defined by themes). */
    echo $before_widget;

    /* Title of widget (before and after defined by themes). */
    if ( $title )
      echo $before_title . $title . $after_title;

    GHGPostWidget::getRecentPosts($num_posts);

    /* After widget (defined by themes). */
    echo $after_widget;

  }
  function getRecentPosts($num_posts)
  {
    global $wpdb;
    $sql = "select * from ".$wpdb->posts." where post_status='publish' and post_type='post' order by post_date desc limit ".$num_posts;
    $posts = $wpdb->get_results($sql);
    if (count($posts) >= 1 )
    {
      $postArray = array();
      foreach ($posts as $post)
      {
        wp_cache_add($post->ID, $post, 'posts');
        $postArray[] = array('title' => stripslashes($post->post_title), 'url' => get_permalink($post->ID));
      }
      echo '<ul>';
      foreach ($postArray as $post)
      {
        echo '<li>';
        echo '<a href="'.$post['url'].'" title="'.$post['title'].'">'.$post['title'].'</a>';
        echo '</li>';
      }
      echo '</ul>';
    }
  }
}

function ghg_register_widget()
{
    register_widget('GHGPostWidget');
}

function ghg_load_plugin()
{
    add_action( 'widgets_init', 'ghg_register_widget' );
}

add_action( 'plugins_loaded', 'ghg_load_plugin' );
?>
