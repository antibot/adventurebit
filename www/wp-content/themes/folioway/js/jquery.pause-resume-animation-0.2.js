/*
jQuery plugin : pause resume animation
Created by Joe Weitzel
BOX Creative LLC
http://plugins.jquery.com/project/Pause-Resume-animation
*/
jQuery.fn.startAnimation = function(  params, duration, easing, callback ) {
	jQuery(this).animate( params, duration, easing, callback );
	var data = { target:this.get(0), params: params, duration: duration, easing: easing, callback: callback,
				startTime: new Date().getTime(), timePlayed: 0, timeRemaining: 0 };
	if( !jQuery.pauseableAnimations ) {
		jQuery.extend({ pauseableAnimations: new Array( data ) });
	} else {
		for( var i in jQuery.pauseableAnimations ) {
			if( jQuery.pauseableAnimations[i].target == this.get(0) ) {
				jQuery.pauseableAnimations[i] = data;
			} else {
				jQuery.pauseableAnimations.push( data );
			};
		};
	};
};
jQuery.fn.pauseAnimation = function() {
	if( jQuery.pauseableAnimations ) {
		for(var i in jQuery.pauseableAnimations ) {
			if( jQuery.pauseableAnimations[i].target == this.get(0) ) {
				jQuery(this).stop();
				var now = new Date().getTime();
				var data = jQuery.pauseableAnimations[i];
				data.timePlayed += ( now - data.startTime );
				data.timeRemaining = data.duration - data.timePlayed;
				if( data.timePlayed > data.duration ) {
					var newArray = new Array();
					for( var p in jQuery.pauseableAnimations ) {
						if( jQuery.pauseableAnimations[p] != data ) newArray.push( jQuery.pauseableAnimations[p] );
					};
					jQuery.pauseableAnimations = newArray.length > 0 ? newArray : null;
					delete newArray;
					return this;
				};
				break;
			};
		};
	};
	return this;
};
jQuery.fn.resumeAnimation = function() {
	if( jQuery.pauseableAnimations ) {
		for(var i in jQuery.pauseableAnimations ) {
			var data = jQuery.pauseableAnimations[i];
			if( data.target == this.get(0) ) {
				this.animate( data.params, data.timeRemaining, data.easing, data.callback );
				data.startTime = new Date().getTime();
				return this;
			};
		};
	};
};