jQuery(document).ready(function(){

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
});
