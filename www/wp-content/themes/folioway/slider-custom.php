<div id="featured">
    <!-- <div class="background"></div> -->
    <div class="inner">
        
    <?php $featured_posts = iwak_get_list('featured_posts'); if( !empty($featured_posts) ): global $post; $class=' active';
        
        foreach( $featured_posts as $featured_post )
            $id_list[] = $featured_post['id'];
            
        $q = query_posts(array('posts_per_page'=>-1,
                                           'caller_get_posts'=>1,
                                           'post_type'=>'any',
                                           'post_status' =>'inherit,publish',
                                           'post__in'=>$id_list)
                                );
        foreach($q as $featured_post) {
            $posts[$featured_post->ID] = $featured_post;
        }
            
        foreach( $featured_posts as $index=>$featured_post ): 
            $post = $posts[$featured_post['id']];
            setup_postdata($post); $image = $iwak->get_post_image_url(); $thumbs[] = $image; 
            $href = $featured_post['image_link'] ? $featured_post['image_link']: get_permalink(); ?>
                <div id="featured-<?php echo $index;?>" class="featured-entry<?php echo $class; unset($class); ?>" >
                    <?php if($href == 'none'): $iwak->the_thumbnail($image, array(940, 390)); else: ?>
                        <a title="" href="<?php echo $href; ?>"><?php $iwak->the_thumbnail($image, array(940, 390)); ?><div class="indicator"></div></a>
                    <?php endif; ?>
                </div>
            <?php
        endforeach; ?>
        
    <?php endif; ?>
        
    <?php if($thumbs): $sections = count($thumbs); $section_width = floor(940/$sections); ?>
    <div class="console-wrapper">
        <div class="progressbar"><span></span></div>
        <ul class="console">
            <?php foreach($thumbs as $index=>$thumb)
                    echo '<li style="width:'. $section_width. 'px" class="thumb thumb-'. $index. '"><span class="number">0'. ++$index. '</span></li>'; ?>
            <div class="clear"></div>
        </ul>
        <span class="slider-btn slider-btn-play"></span>
    </div>
    <?php endif; wp_reset_query(); ?>
    
    </div><!-- .inner -->
        
</div><!-- #featured -->
        
<script type="text/javascript">
    /*jQuery(document).ready(function() {
        //jQuery("#featured-0 .fl, #featured-0 .fr").css('display', 'none');
        //jQuery('#featured .inner').css('background', 'transparent url(' + '<?php echo THEME_URL; ?>' + '/images/slider-loader.gif) no-repeat 50% 50%');        
    });*/

    jQuery(window).load(function() {
        
        var progress = jQuery('div.progressbar span');
        var current = 0;
        
        var autoplay = <?php echo $iwak->o['featured']['autoplay']; ?>;
        var duration = <?php echo $iwak->o['featured']['autoplay_duration'] * 1000; ?>;
        var sections = <?php echo $sections; ?>;
        var section_width = <?php echo $section_width; ?>;
        var interval = (duration*sections)/940;
        var pause_on_hover = <?php echo $iwak->o['featured']['pause_on_hover']; ?>;
        var settings = <?php echo json_encode($featured_posts) ?>;
        var featured_posts = jQuery('#featured .featured-entry');
        var numbers =  jQuery('ul.console .number');
        var timer;
                                
        if(sections < 2)
            return;

        jQuery('.console-wrapper').fadeIn();
        
        // Navigation Actions
        jQuery.fn.highlight = function() {
            if(!jQuery(this).hasClass('active')) {
                selected = jQuery(this);
                jQuery("ul.console .active").removeClass('active');
                selected.addClass('active');
            }
        }

        var activate = function(number) {
            called = number;

                // Hide current featured item
                out_duration = parseInt(settings[current]['animation']['out_duration']);
                jQuery(featured_posts[current]).fadeOut(out_duration);
                
                // Light up the navigation button corresponding to the featured item passed in
                 jQuery(numbers[called]).highlight();
            
            // Activate the featured item passed in
            in_duration = parseInt(settings[called]['animation']['in_duration']);
            jQuery(featured_posts[called]).fadeIn(in_duration);
                            
        }
        
        var start = function() {
          timer = setInterval(function() {
            w = progress.width();
            if(w == 940) {
                progress.width(0);
                activate(0);
                current = 0;
            } else {
                progress.width(w + 1);
                next = Math.floor(w/section_width);
                if( next == current + 1 ) {
               // alert('next: ' + next + ' current: ' + current);
                    activate(next);
                    current = next;
                }
            }
          }, interval);
        }
        
        if(autoplay) {
            jQuery('.console-wrapper .slider-btn').toggleClass('slider-btn-play');
            start();
        }
        
        if(pause_on_hover) {
            jQuery("#featured .featured-entry").mouseenter(function() {
                if(timer) {
                    clearInterval(timer);
                    timer = false;
                }
            });
            
            jQuery("#featured .featured-entry").mouseleave(function() {
                if(!timer) start();
            });
        }
        
        jQuery("ul.console .number").click(function() {
            if(jQuery(this).hasClass('active'))
                return;
                
            if(timer) {
                clearInterval(timer);
                timer = false;
            }
            index = jQuery("ul.console .number").index(this);
            activate(index);
            current = index;
            progress.animate({width: index*section_width}, function() { if(!timer) start();});
            jQuery(this).highlight();
        });
        
        jQuery("ul.console .number:first").addClass('active');
        
        jQuery('.console-wrapper .slider-btn').click(function() {
            if(jQuery(this).hasClass('slider-btn-play') && !timer) {
                start();
            }
            else if(!jQuery(this).hasClass('slider-btn-play') && timer) {
                clearInterval(timer);
                timer = false;
            }
                
            jQuery(this).toggleClass('slider-btn-play');
        });
        
        if(!(jQuery.browser.msie && jQuery.browser.version < 9)) { // exclude ie8- because of their poor support for png
            jQuery('#featured a').hover(function() {
                jQuery('.indicator', this).fadeIn('slow');
            }, function() {
                jQuery('.indicator', this).fadeOut();
            });
        }
        
    });
</script>
