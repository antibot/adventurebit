    <div class="widget widget_categories widget-1">
        <h2 class="widget-title"><span class="widget-icon"></span><?php _e('Categories', THEME_NAME) ?></h2>
        <ul>
        <?php wp_list_categories('show_count=0&title_li=&depth=1'); ?>
        </ul>
    </div>
    
    <div class="widget widget_pages widget-2">
        <h2 class="widget-title"><span class="widget-icon"></span><?php _e('Pages', THEME_NAME) ?></h2>
        <ul>
        <?php wp_list_pages('title_li=&depth=1' ); ?>
        </ul>
    </div>    
    
    <div class="widget widget_archive widget-3">
        <h2 class="widget-title"><span class="widget-icon"></span><?php _e('Archives', THEME_NAME) ?></h2>
        <ul>
            <?php wp_get_archives('type=monthly'); ?>
        </ul>
    </div>
    
    <!--<div class="widget widget_meta widget-4">
        <h2 class="widget-title"><?php _e('Meta', THEME_NAME) ?></h2>
        <ul>
            <?php wp_register(); ?>
            <li><?php wp_loginout(); ?></li>
			<li><a href="<?php global $iwak; echo $iwak->o['general']['rss']; ?>" title="<?php echo esc_attr(__('Syndicate this site using RSS 2.0')); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
			<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo esc_attr(__('The latest comments to all posts in RSS')); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
        </ul>
    </div>-->