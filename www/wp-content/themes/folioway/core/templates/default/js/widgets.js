jQuery(document).ready(function () {
    var new_accordion, tr, expand, i = 0;

    // Expand selected advertise
    var accordion_click = function() {
        tr = jQuery(this).next('textarea');
        if ( tr.is(':hidden') ) {
            if(expand && expand != tr) {
                expand.slideUp();
                expand.parent().find('h4').removeClass('accordion-active');
            }
            jQuery(this).addClass('accordion-active');
            tr.slideDown();
            expand = tr;
        } else  {
            expand = null;
            tr.slideUp();
            jQuery(this).removeClass('accordion-active');
        }
    }
    
    // Delete selected advertise
    var accordion_delete = function() {
        jQuery(this).parent().remove();
    }
    
    jQuery("div.accordion h4").live('click', accordion_click);
    jQuery("div.accordion span.delete").live('click', accordion_delete);
    
    // Add an advertise into an <Advertise Widget>
    jQuery('a.add-ads').live('click', function() {
            input = jQuery(this).parent().prev().find('textarea');
            prev_accordion = jQuery(this).parent().prev().prev();
            new_accordion = prev_accordion.after('<div class="accordion"><span class="widget-icon delete"></span><h4>New Advertise</h4><textarea class="hidden" id="'+input.attr('id').replace(/ad_code/ig, 'ad_code'+i)+'" name="'+input.attr('name').replace(/ad_code/ig, 'ad_code'+i)+'">'+input.val()+'</textarea></div>').next();
            new_accordion.find('h4').click(accordion_click);
            new_accordion.find('span.delete').click(accordion_delete);
            input.val('');
            i++;
        }
    );
    
})
