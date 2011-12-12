<div id="featured">
    <!-- <div class="background"></div> -->
    <div id="roundabout" class="inner">
        
    <?php $featured_posts = iwak_get_list('featured_posts'); if( !empty($featured_posts) ): global $post; $class=' active';
        
        foreach( $featured_posts as $featured_post )
            $id_list[] = $featured_post['id'];
            
        $q = get_posts(array('posts_per_page'=>-1,
                                           'caller_get_posts'=>1,
                                           'post_type'=> 'any',
                                           'post_status' => array(null, 'publish'),
                                           'post__in'=>$id_list)
                                );
        foreach($q as $featured_post) {
            $posts[$featured_post->ID] = $featured_post;
        }
                        
        // produce html entity ?>
        <ul class="roundabout-holder"><?php
        foreach( $featured_posts as $index=>$featured_post ): $i ++;
            $post = $posts[$featured_post['id']];
            setup_postdata($post); $image = $iwak->get_post_image_url(); ?>
            <li class="<?php echo 'item-'. $i; ?>">
                <a href="<?php echo $featured_post['image_link'] ? $featured_post['image_link']: get_permalink(); ?>"><?php $iwak->the_thumbnail($image, array(475, 323)); ?></a>
            </li><?php
        endforeach; ?>
        </ul>
        
    <?php endif; ?>
    
    <?php wp_reset_query(); ?>
    
    </div><!-- .inner -->
        
</div><!-- #featured -->

<script type="text/javascript" src="<?php echo THEME_URL. '/js/jquery.roundabout.min.js'; ?>"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        var interval;
        var autoplay = <?php echo $iwak->o['featured']['autoplay']; ?>;
        
        var number = <?php echo count($featured_posts); ?>;
        if(!number)
            return;
            
        var min_scale = 16/19;
        var max_scale = 1;
        switch(number) {
            case 4:
                min_scale = 14.5/19;
                jQuery('#roundabout ul').css('width', '44em');
                break;
            case 5:
                min_scale = 12/19;
                jQuery('#roundabout ul').css('width', '46em');
                break;
            case 6:
                min_scale = 10/19;
                jQuery('#roundabout ul').css('width', '50em');
                break;
            case 7:
                min_scale = 8/19;
                jQuery('#roundabout ul').css('width', '51em');
                break;
            case 8:
                min_scale = 4/19;
                jQuery('#roundabout ul').css('width', '55em');
                break;
            case 9:
                min_scale = 1/19;
                jQuery('#roundabout ul').css('width', '55.8em');
                break;
        }
        
        if(number > 9) {
            min_scale = 1/19;
            jQuery('#roundabout ul').css('width', '55.8em');
        }
        
        jQuery('#roundabout ul').roundabout({
            minScale: min_scale,
            maxScale: max_scale,
            minOpacity: 0.9
        });
        
        if(autoplay) {
            jQuery('#roundabout ul').hover(
                function() {
                    // oh no, it's the cops!
                    clearInterval(interval);
                },
                function() {
                    // false alarm: PARTY!
                    interval = startAutoPlay();
                }
            );
            
             // let's get this party started
             interval = startAutoPlay();
        }
        
    });
    
    function startAutoPlay() {
        var duration = <?php echo $iwak->o['featured']['autoplay_duration'] * 1000; ?>;
        return setInterval(function() {
            jQuery('#roundabout ul').roundabout_animateToNextChild();
        }, duration);
    } 
</script>
