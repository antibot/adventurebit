jQuery.noConflict();
    
jQuery(document).ready(function(){

    jQuery('#header .menu ul li').each(function() {
        if(jQuery(this).find('ul').length != 0)
            jQuery('> a', this).addClass('has_submenu');
    });
    
    jQuery('#header .menu li').hover(function() {	
            submenu = jQuery('> .sub-menu', this);
            submenu.stop().css({overflow:"hidden", height:"auto", display:"none"}).slideDown(300, function()
            {
                jQuery(this).css({overflow:"visible"});
            });	
		},
		function() {	
            submenu = jQuery('> .sub-menu', this);
            top_distance = jQuery(this).parent().is('#header .menu') ? '33px' : '0';
            submenu.stop().css({overflow:"hidden", height:"auto", display:"block", top:top_distance}).slideUp(300, function()
            {	
                jQuery(this).css({display:"none"});
            });
        }
    );
                         
	jQuery(".toggle_content").hide(); 

	jQuery(".toggle").toggle(function(){
		jQuery(this).addClass("active");
		}, function () {
		jQuery(this).removeClass("active");
	});
	
	jQuery(".toggle").click(function(){
		jQuery(this).next(".toggle_content").slideToggle();

	});
    
    jQuery("ul.tabs").tabs("div.panes > div");
    
    jQuery('form#contactform').submit(function() {
        jQuery('.loaderIcon', this).css('display', 'inline-block');
        jQuery('input, textarea', this).removeClass('invalid');
        
        var name = jQuery('input#cfname', this).val();
        var email = jQuery('input#cfemail', this).val();
        //var website = jQuery('input#cfwebsite', this).val();
        //var phone = jQuery('input#cfphone', this).val();
        var message = jQuery('textarea#cfmessage', this).val();
        var submit_url = jQuery('input#submit_url', this).val();
        var contactform = jQuery(this);

        jQuery.ajax({
            type: 'post',
            url: submit_url,
            data: 'action=iwak_ajax_action&type=contact&cfname=' + name + '&cfemail=' + email + '&cfmessage=' + message,
            success: function(results) {
                jQuery('.loaderIcon', contactform).css('display', 'none');
                if(results) {
                    if(results.charAt(0) == 1) jQuery('input#cfname', contactform).addClass('invalid');
                    if(results.charAt(1) == 1) jQuery('input#cfemail', contactform).addClass('invalid');
                    if(results.charAt(2) == 1) jQuery('textarea#cfmessage', contactform).addClass('invalid');
                } else {
                    contactform.slideUp();
                    jQuery("<p class='thanyou hidden'>Your message has been sent, thank you!</p>").insertAfter(contactform).fadeIn();
                }
            }
        }); // end ajax
        
        // Prevent form action being triggered
        return false;
    });
    
    jQuery(".testimonial li:not(.active)").each(function() {
        jQuery(this).css({'position':'absolute','visibility':'hidden','display':'block'});
    });
    
    jQuery(".testimonial-wrapper span.next").click(function() {
        var container_tm = jQuery(this).parent().prev('.testimonial');
        var current_tm = container_tm.find('li.active');
        if(!current_tm)
            return;
            
        var curr_height = current_tm.height();
        var next_tm = current_tm.next('li');
        if(next_tm.length == 0)
            next_tm = jQuery('li:first', container_tm);
            
        var next_height = next_tm.height();
        
        current_tm.fadeOut().removeClass('active');
        next_tm.css('visibility', 'visible').fadeIn().addClass('active');
        container_tm.height(curr_height).animate({height:next_height});
    });
    
    jQuery(".testimonial-wrapper span.prev").click(function() {
        var container_tm = jQuery(this).parent().prev('.testimonial');
        var current_tm = container_tm.find('li.active');
        if(!current_tm)
            return;
            
        var curr_height = current_tm.height();
        var prev_tm = current_tm.prev('li');
        if(prev_tm.length == 0)
            prev_tm = jQuery('li:last', container_tm);
            
        var prev_height = prev_tm.height();
        
        current_tm.fadeOut().removeClass('active')
        prev_tm.css('visibility', 'visible').fadeIn().addClass('active');
        container_tm.height(curr_height).animate({height:prev_height});
    });
    /*
    jQuery('#portfolio .thumblink').hover(function() {
        jQuery('.thumbnail', this).fadeTo(500, 0.4);
        //jQuery('.extra', this).fadeTo(500, 0.8);  // IE7 opacity bug
    },
    function() {
        jQuery('.thumbnail', this).fadeTo(500, 1);
        //jQuery('.extra', this).fadeTo(500, 1);    // IE opacity bug
    }
    );*/
    
    // work hover animation
    jQuery('.works .portfolio-entry, .works .post-entry').each(function(settings) {
        settings = {'durationIn': 180, 'durationOut': 350, 'easingIn': 'easeOutQuad', 'easingOut': 'easeInCirc'};
        jQuery(this).hover(function() {
            h = jQuery('.entry-title', this).outerHeight();
            jQuery('.entry-title', this).stop().css({top: '-' + h + 'px', visibility: 'visible'}).animate({top:'0'}, settings.durationIn, settings.easingIn);
            jQuery('.more-link', this).animate({bottom:'-10px'}, settings.durationIn, settings.easingIn);
            if(!(jQuery.browser.msie && jQuery.browser.version < 9)) // exclude ie8- because of their poor support for png
                jQuery('.extra', this).delay(100).fadeIn('slow');
        }, function() {
            h = jQuery('.entry-title', this).outerHeight();
            jQuery('.entry-title', this).stop().animate({top: '-' + h + 'px'}, settings.durationOut, settings.easingOut);
            jQuery('.more-link', this).animate({bottom:'-40px'}, settings.durationOut, settings.easingOut);
            if(!(jQuery.browser.msie && jQuery.browser.version < 9)) // exclude ie8- because of their poor support for png
                jQuery('.extra', this).fadeOut();
        });
    });
    
    /* height animation doesn't compatible to chrome
    jQuery('#portfolio').height(jQuery('#portfolio').height());
    jQuery('#portfolio-list').resize(function() {
        filter_height = jQuery('#portfolio-filter').outerHeight(true);
        list_height = jQuery('#portfolio-list').outerHeight();
        jQuery('#portfolio').animate({height: filter_height + list_height + 'px'});
    });*/
    
    // menu hover animation
    focusing_icon = jQuery('#menu-wrapper .icon');
    active_menu = jQuery('#main-nav li.current-menu-item');
    if(active_menu.length) {
        p = active_menu.position();
        w = active_menu.width();
        start = p.left + w - 15;
    } else 
        start = -5;
        
    focusing_icon.css({left:start}).show();
    
    jQuery('#main-nav > li').hover(function() {
        p = jQuery(this).position();
        w = jQuery(this).width();
        end = p.left + w - 15;
        focusing_icon.stop().animate({left:end}, 'slow', 'easeOutCirc');
    }, function() {
        focusing_icon.stop().animate({left:start}, 'slow');
    }
    );

    // belt animation (max one belt per page)
    belt = jQuery('.works ul');
    belt_slots = jQuery('li', belt).length;
    left_slots = belt_slots - 4;
    jQuery('.works .more').click(function() {
        duration = 'slow';
        easing = 'easeOutQuad';
        if(left_slots > 0) {
            belt.animate({marginLeft: '-=960px'}, duration, easing);
            left_slots -= 4;
        }
        else if(belt_slots > 4){
            belt.animate({marginLeft: '0px'}, duration, easing);
            left_slots = belt_slots - 4;
        }
    }
    );
    
    jQuery.fn.rotate = function(degree) {
        var target = jQuery(this);
        target.css({ WebkitTransform: 'rotate(' + degree + 'deg)'});  
        target.css({ '-moz-transform': 'rotate(' + degree + 'deg)'});                      
    }
    
    jQuery(".panel .more").mouseenter(function() {
        var target = jQuery('a', this);
        if(jQuery.browser.msie) {
            return;
        }
        degree = 0;
        rolling = setInterval(function() {
            degree -= 5;
            if(degree <= -185)
                clearInterval(rolling);
            else
                target.rotate(degree);
        },10);
    }
    );

    jQuery('.post.entry .thumbnail, .post-entry .thumbnail').hover(function() {
        jQuery(this).fadeTo(180, 0.5, 'easeOutQuad');
    }, function() {
        jQuery(this).fadeTo(350, 1, 'easeInCirc');
    }
    );
    
});
