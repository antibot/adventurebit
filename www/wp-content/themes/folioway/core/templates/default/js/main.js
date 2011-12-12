jQuery.noConflict();

jQuery(document).ready(function () {
    var selected_item;
    var context = jQuery('#iwak-admin');
    
    RegExp.quote = function(str) {
        return String(str).replace(/([.?*+^$[\]\\(){}-])/g, "\\$1");
    };

    // Highlight a selected item in <Customized Menubar> and activate relative function buttons
    jQuery('.menubar-editor span.item').click(
        function() {
            var menubar = jQuery(this).parents('.menubar-editor');
            if(!jQuery(this).hasClass('selected')) {
                if(selected_item) selected_item.removeClass('selected');
                jQuery(this).addClass('selected');
                if(jQuery('.item-meta', menubar).is(':hidden')) jQuery('.item-meta', menubar).slideDown();
                selected_item = jQuery(this);
            } else {
                jQuery(this).removeClass('selected');
                jQuery('.item-meta', menubar).slideUp();
                selected_item = null;
            }
        });
        
    // Increase the order of an item in <Customized Menubar>
    jQuery('.move-forward').click(
        function() {
            prev_item = selected_item.prev();
            if(selected_item.parent().hasClass('menu-items') && !prev_item.hasClass("item-home")) {
                var menubar = jQuery(this).parents('.menubar-editor');
                var menu_str = jQuery('input', menubar).val() ? jQuery('input', menubar).val() : '';
                
                // Move forward selected menu item
                prev_item.before(selected_item);

                // Update hidden input value
                prev_str = prev_item.attr('id') + ',' + prev_item.text() + ';';
                curr_str = selected_item.attr('id') + ',' + selected_item.text() + ';';
                menu_str = menu_str.replace(prev_str+curr_str, curr_str+prev_str);
                jQuery('input', menubar).val(menu_str);
            }
        }
    );

    // Decrease the order of an item in <Customized Menubar>
    jQuery('.move-backward').click(
        function() {
            next_item = selected_item.next();
            if(selected_item.parent().hasClass('menu-items')) {
                var menubar = jQuery(this).parents('.menubar-editor');
                var menu_str = jQuery('input', menubar).val() ? jQuery('input', menubar).val() : '';
                
                // Move backward selected menu item
                next_item.after(selected_item);
                    
                // Update hidden input value
                next_str = next_item.attr('id') + ',' + next_item.text() + ';';
                curr_str = selected_item.attr('id') + ',' + selected_item.text() + ';';
                menu_str = menu_str.replace(curr_str+next_str, next_str+curr_str);
                jQuery('input', menubar).val(menu_str);
            }
        }
    );

    // Include/Exclude an item to/from <Customized Menubar>
    jQuery('.toggle-status').click(
        function() {
            var menubar = jQuery(this).parents('.menubar-editor');
            var menu_str = jQuery('input', menubar).val() ? jQuery('input', menubar).val() : '';
            if(selected_item.parent().hasClass('menu-items')) { // Exclude an item
                if(selected_item.hasClass('page-item'))
                    jQuery(".page-items", menubar).append(selected_item);
                else if (selected_item.hasClass('cat-item'))
                    jQuery(".cat-items", menubar).append(selected_item);
                    
                menu_str = menu_str.replace(selected_item.attr('id') + ',' + selected_item.text() + ';', '');
                jQuery('input', menubar).val(menu_str);
            } else { // Include an item
                jQuery(".menu-items", menubar).append(selected_item);
                menu_str += selected_item.attr('id') + ',' + selected_item.text() + ';';
                jQuery('input', menubar).val(menu_str);
            }
        });

        // Expand hidden textarea which usually for code input
        jQuery('label.switch', context).bind('click', function() {
            tr = jQuery(this).next('textarea');
            if ( tr.is(':hidden') ) {
                tr.slideDown();
                if(expand && expand != tr) expand.slideUp();
                expand = tr;
            } else  {
                expand = null;
                tr.slideUp();
            }
        });

        // Switch between internal pages on theme settings page
        jQuery('li.nav-panel', context).click(
            function() {
                if( !jQuery(this).hasClass('nav-panel-active') ) {
                    jQuery('li.nav-panel-active', context).removeClass('nav-panel-active');
                    jQuery(this).addClass('nav-panel-active');
                    
                    index = jQuery('ul.nav li', context).index(this);
                    margin = '-' + index + '00%';
                    // active page
                    jQuery('.groups', context).animate({marginLeft:margin},800);
                    
                }
            }
        );
        
        jQuery('span.icon-expand', context).live('click', function() {
            var main = jQuery(this).parents('.main');
            if(main.next().is(':hidden'))
                main.nextAll().show();
            else
                main.nextAll().hide();
        });
        
        jQuery('span.icon-delete', context).live('click', function() {
            //jQuery(this).parents('.ui-droppable').remove();
            jQuery(this).parents('table').remove();
        });
        
        jQuery('select:not(.hidden)', context).selectbox();
        
        jQuery.fn.dragitem = function() {
            jQuery(this).draggable(
            {
                handle: '> tbody > tr.main',
                cancel: '.item-title .jquery-selectbox-list, .iwak-console, .jquery-selectbox-moreButton, input, textarea',
                axis: 'y',
                opacity: .8,
                addClasses: false,
                helper: 'clone',
                zIndex: 100
            });
            
            jQuery(this).droppable(
            {
                drop: function(e, ui) {
                    jQuery(this).prev('.dock').remove();
                    
                    // Re-replace selectbox
                    jQuery('.jquery-selectbox', this).unselectbox();
                    jQuery('.jquery-selectbox', ui.draggable).unselectbox();
                    jQuery(this).before(ui.draggable);
                    jQuery('select:not(.hidden)', this).selectbox();
                    jQuery('select:not(.hidden)', ui.draggable).selectbox();
                },
                over: function(e, ui) {
                    jQuery(this).before('<div class="dock"><div class="inner"></div></div>');
                },
                out: function(e, ui) {
                    jQuery(this).prev('.dock').remove();
                }
            });
        }
        
        jQuery('table.draggable:visible, div.iwak-console', context).dragitem();
        //jQuery('', context).dragitem();
        
        jQuery.fn.reindex = function() {
            //Reindex items
            var i = 0;
            this.each(function(i) {
                var reg = /^([^\[]+)\[(\d+)\]/;
                var matches = reg.exec(jQuery('form :input:first', this).attr('id'));
                
                if(!matches || matches[2] == i) {
                    return true;
                }

                var pattern = new RegExp(RegExp.quote(matches[0]), 'g');
                var newstr = matches[1] + '[' + i + ']';
                jQuery(this).html(jQuery(this).html().replace(pattern, newstr));
                i++;
            });
        }
        
        jQuery('form', context).submit(function() {
            jQuery('.list-item', context).reindex();
        });
        
        jQuery('div.item-type .jquery-selectbox-item', context).live('click', function() {
            var type = jQuery(this).parents('.jquery-selectbox').find('select option:selected').val().toLowerCase();
            var options = jQuery('.' + type + '-selectbox', context).html();
            var next = jQuery(this).parents('div.column').next();
            next.find('.jquery-selectbox').unselectbox();
            next.find('select').html(options).selectbox();
        }
        );
        
        var preview_toggle = function() {
            if(jQuery(this).val() == 'custom')
                jQuery(this).parents('tr').next().show();
            else
                jQuery(this).parents('tr').next().hide();
        };
        
        jQuery('.menu-type input:checked', context).each(preview_toggle)
        
        jQuery('.menu-type input', context).click(preview_toggle);
        
        jQuery('.filter-item span.iwak-toggle', context).live('click', function() {
            var icon = jQuery(this).prev('span.icon');
            if(icon.hasClass('icon-exclude')) {
                icon.removeClass('icon-exclude');
                jQuery(this).next('select').val(0);
            } else {
                icon.addClass('icon-exclude');
                jQuery(this).next('select').val(1);
            }
        });
        
        jQuery('.iwak-button-status', context).bind('click', function() {
            if(jQuery(this).hasClass('button-status-on')) {
                jQuery(this).removeClass('button-status-on');
                opt = jQuery(this).next('select');
                if(opt.length) {
                    jQuery('option[value=1]', opt).removeAttr('selected');
                    jQuery('option[value=0]', opt).attr('selected', 'selected');
                    //alert(jQuery('option:selected', opt).val());
                }
            } else {
                jQuery(this).addClass('button-status-on');
                opt = jQuery(this).next('select');
                if(opt.length) {
                    jQuery('option[value=0]', opt).removeAttr('selected');
                    jQuery('option[value=1]', opt).attr('selected', 'selected');
                    //alert(jQuery('option:selected', opt).val());
                }
            }
        });
        
    jQuery('form.iwak-contactform').submit(function() {
        jQuery('.loaderIcon', this).css('display', 'inline-block');
        jQuery('input, textarea', this).removeClass('invalid');
        
        var name = jQuery('input#cfname', this).val();
        var email = jQuery('input#cfemail', this).val();
        var website = jQuery('input#cfwebsite', this).val();
        var describe  = jQuery('input#cfdescribe', this).val();
        var message = jQuery('textarea#cfmessage', this).val();
        var submit_url = jQuery('input#submit_url', this).val();
        var submit_category = jQuery('input#submit_category', this).val();
        var contactform = jQuery(this);

        jQuery.ajax({
            type: 'post',
            url: submit_url,
            data: 'action=iwak_ajax_action&type=contact_support&cfname=' + name + '&cfemail=' + email + '&cfmessage=' + message + '&cfwebsite=' + website + '&cfdescribe=' + describe + '&cfcategory=' + submit_category,
            beforeSend: function() {
                jQuery('.iwak-error', contactform).remove();
            },
            success: function(results) {
                jQuery('.loaderIcon', contactform).css('display', 'none');
                if(results) {
                    if(results.charAt(0) != 0 && results.charAt(0) != 1) {
                        jQuery('p.form-submit', contactform).after("<p class='iwak-error'>" + results + "</p>");
                    } else {
                        if(results.charAt(0) == 1) jQuery('input#cfname', contactform).addClass('invalid');
                        if(results.charAt(1) == 1) jQuery('input#cfemail', contactform).addClass('invalid');
                        if(results.charAt(2) == 1) jQuery('textarea#cfmessage', contactform).addClass('invalid');
                    }
                } else {
                    contactform.slideUp();
                    jQuery("<p class='thankyou hidden'>Your message has been sent, thank you!</p>").insertAfter(contactform).fadeIn();
                }
            }
        }); // end ajax
        
        // Prevent form action being triggered
        return false;
    });
        
    jQuery('.iwak-color').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            jQuery(el).val(hex);
            jQuery(el).ColorPickerHide();
        },
        onBeforeShow: function () {
            jQuery(this).ColorPickerSetColor(this.value);
        }
    })
    .bind('keyup', function(){
        jQuery(this).ColorPickerSetColor(this.value);
    });

})
