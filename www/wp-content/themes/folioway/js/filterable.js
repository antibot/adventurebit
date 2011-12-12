/*
* Copyright (C) 2009 Joel Sutherland.
* Liscenced under the MIT liscense

Mod by iWaKThemes
------------------------------------------------
Add support for easing
Add support for lightbox rel
Include margin in animation
-------------------------------------------------
$easing_list = array('linear'=>'linear', 
                        'swing'=>'swing', 
                        'easeInBounce'=>'easeInBounce',
                        'easeOutBounce'=>'easeOutBounce', 
                        'easeInOutBounce'=>'easeInOutBounce', 
                        'easeInQuad'=>'easeInQuad',
                        'easeOutQuad'=>'easeOutQuad', 
                        'easeInOutQuad'=>'easeInOutQuad', 
                        'easeInCubic'=>'easeInCubic',
                        'easeOutCubic'=>'easeOutCubic', 
                        'easeInOutCubic'=>'easeInOutCubic', 
                        'easeInQuart'=>'easeInQuart',
                        'easeOutQuart'=>'easeOutQuart', 
                        'easeInOutQuart'=>'easeInOutQuart', 
                        'easeInQuint'=>'easeInQuint',
                        'easeOutQuint'=>'easeOutQuint', 
                        'easeInOutQuint'=>'easeInOutQuint', 
                        'easeInSine'=>'easeInSine',
                        'easeOutSine'=>'easeOutSine', 
                        'easeInOutSine'=>'easeInOutSine', 
                        'easeInExpo'=>'easeInExpo',
                        'easeOutExpo'=>'easeOutExpo', 
                        'easeInOutExpo'=>'easeInOutExpo', 
                        'easeInCirc'=>'easeInCirc',
                        'easeOutCirc'=>'easeOutCirc', 
                        'easeInOutCirc'=>'easeInOutCirc', 
                        'easeInElastic'=>'easeInElastic',
                        'easeOutElastic'=>'easeOutElastic', 
                        'easeInOutElastic'=>'easeInOutElastic', 
                        'easeInBack'=>'easeInBack',
                        'easeOutBack'=>'easeOutBack', 
                        'easeInOutBack'=>'easeInOutBack', 
                    );
*/

(function() {
	jQuery.fn.filterable = function(settings) {
		settings = jQuery.extend({
			useHash: true,
			animationSpeed: 1000,
            easingShow: 'easeInCubic',
            easingHide: 'easeOutCubic',
			show: { width: 'show', marginRight: '20px', opacity: 'show' },
			hide: { width: 'hide', marginRight: '0', opacity: 'hide' },
			useTags: true,
			tagSelector: '#portfolio-filter a',
			selectedTagClass: 'current',
			allTag: 'all'
		}, settings);
		
		return jQuery(this).each(function(){
		
			/* FILTER: select a tag and filter */
			jQuery(this).bind("filter", function( e, tagToShow ){
				if(settings.useTags){
					jQuery(settings.tagSelector).removeClass(settings.selectedTagClass);
					jQuery(settings.tagSelector + '[href=' + tagToShow + ']').addClass(settings.selectedTagClass);
				}
				jQuery(this).trigger("filterportfolio", [ tagToShow.substr(1) ]);
			});
		
			/* FILTERPORTFOLIO: pass in a class to show, all others will be hidden */
			jQuery(this).bind("filterportfolio", function( e, classToShow ){
				if(classToShow == settings.allTag){
					jQuery(this).trigger("show");
				}else{
					jQuery(this).trigger("show", [ '.' + classToShow ] );
					jQuery(this).trigger("hide", [ ':not(.' + classToShow + ')' ] );
				}
				if(settings.useHash){
					location.hash = '#' + classToShow;
				}
			});
			
			/* SHOW: show a single class*/
			jQuery(this).bind("show", function( e, selectorToShow ){
				jQuery(this).children(selectorToShow).each(function() {
                    jQuery(this).animate(settings.show, settings.animationSpeed, settings.easingShow);
                    imagelink = jQuery(this).find('.entry-thumb a');
                    imagelink.attr('rel', imagelink.attr('rel').replace(/(\d)\]/, '$1_active]'));
				});
			});
			
			/* SHOW: hide a single class*/
			jQuery(this).bind("hide", function( e, selectorToHide ){
				jQuery(this).children(selectorToHide).each(function() {
                    jQuery(this).animate(settings.hide, settings.animationSpeed, settings.easingHide);	
                    imagelink = jQuery(this).find('.entry-thumb a');
                    imagelink.attr('rel', imagelink.attr('rel').replace('_active', ''));
				});
			});
			
			/* ============ Check URL Hash ====================*/
			if(settings.useHash){
				if(location.hash != '')
					jQuery(this).trigger("filter", [ location.hash ]);
				else
					jQuery(this).trigger("filter", [ '#' + settings.allTag ]);
			}
			
			/* ============ Setup Tags ====================*/
			if(settings.useTags){
				jQuery(settings.tagSelector).click(function(){
					jQuery('#portfolio-list').trigger("filter", [ jQuery(this).attr('href') ]);
					
					jQuery(settings.tagSelector).removeClass('current');
					jQuery(this).addClass('current');
				});
			}
		});
	}
})(jQuery);


jQuery(document).ready(function(){
	
	jQuery('#portfolio-list').filterable();

});