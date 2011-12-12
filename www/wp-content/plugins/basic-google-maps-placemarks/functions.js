/**
 * @package BasicGoogleMapsPlacemarks
 * @author Ian Dunn <ian@iandunn.name>
 * @link http://wordpress.org/extend/plugins/basic-google-maps-placemarks/
 */

 
/**
 * Wrapper function to safely use $
 * @author Ian Dunn <ian@iandunn.name>
 */
function bgmp_wrapper( $ )
{
	// @todo - figure out if wrapper bad for memory consumption (https://developer.mozilla.org/en/JavaScript/Reference/Functions_and_function_scope#Efficiency_considerations)
		// ask on stackoverflow
	
	var bgmp = 
	{
		/**
		 * Main entry point
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		init : function()
		{
			bgmp.name				= 'Basic Google Maps Placemarks';
			bgmp.canvas				= document.getElementById( 'bgmp_map-canvas' );	// We have to use getElementById instead of a jQuery selector here in order to pass it to the Maps API.
			bgmp.previousInfoWindow	= undefined;
			
			if( bgmp.canvas )
				bgmp.buildMap();
			else
				$( bgmp.canvas ).html( bgmp.name + " error: couldn't retrieve DOM elements." );
		},
		
		/**
		 * Pull in the map options from Wordpress' database and create the map
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		buildMap : function()
		{
			var mapOptions;
			
			if( bgmpData.options.mapWidth == '' || bgmpData.options.mapHeight == '' || bgmpData.options.latitude == '' || bgmpData.options.longitude == '' || bgmpData.options.zoom == '' || bgmpData.options.infoWindowMaxWidth == '' )
			{
				$( bgmp.canvas ).html( bgmp.name + " error: map options not set." );
				return;
			}
			
			mapOptions = 
			{
				'zoom'						: parseInt( bgmpData.options.zoom ),
				'center'					: new google.maps.LatLng( parseFloat( bgmpData.options.latitude ), parseFloat( bgmpData.options.longitude ) ),
				'mapTypeId'					: google.maps.MapTypeId[ bgmpData.options.type ],
				'mapTypeControl'			: bgmpData.options.typeControl == 'off' ? false : true,
				'mapTypeControlOptions'		: { style: google.maps.MapTypeControlStyle[ bgmpData.options.typeControl ] },
				'navigationControl'			: bgmpData.options.navigationControl == 'off' ? false : true,
				'navigationControlOptions'	: { style: google.maps.NavigationControlStyle[ bgmpData.options.navigationControl ] }
			};
			
			// Override default width/heights from settings
			$( '#bgmp_map-canvas' ).css( 'width', bgmpData.options.mapWidth );
			$( '#bgmp_map-canvas' ).css( 'height', bgmpData.options.mapHeight );
			
			// Create the map
			try
			{
				map = new google.maps.Map( bgmp.canvas, mapOptions );
				bgmp.addPlacemarks( map );
			}
			catch( e )
			{
				$( bgmp.canvas ).html( bgmp.name + " error: couln't build map." );
				if( window.console )
					console.log( 'bgmp_buildMap: '+ e );
			}
		},
		
		/**
		 * Checks if the value is an integer. Slightly modified version of original.
		 * @author Invent Partners
		 * @link http://www.inventpartners.com/content/javascript_is_int
		 * @param mixed value
		 * @return bool
		 */
		isInt : function( value )
		{
			if( !isNaN( value ) && parseFloat( value ) == parseInt( value ) )
				return true;
			else
				return false;
		},

		/**
		 * Pull the placemark posts from Wordpress' database and add them to the map
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param object map Google Maps map
		 */
		addPlacemarks : function( map )
		{
			// @todo - should probably refactor this since you pulled out the ajax. update phpdoc too
			
			if( bgmpData.markers.length > 0 )
				for( var m in bgmpData.markers )
					bgmp.createMarker( map, bgmpData.markers[m]['title'], bgmpData.markers[m]['latitude'], bgmpData.markers[m]['longitude'], bgmpData.markers[m]['details'], bgmpData.markers[m]['icon'], parseInt( bgmpData.markers[m]['zIndex'] ) );
		},

		/**
		 * Create a marker with an information window
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param object map Google Maps map
		 * @param string title Placemark title
		 * @param float latituded
		 * @param float longitude
		 * @param string details Content of the infowinder
		 * @param string icon URL of the icon
		 * @param int zIndex The desired position in the placemark stacking order
		 * @return bool True on success, false on failure
		 */
		createMarker : function( map, title, latitude, longitude, details, icon, zIndex )
		{
			// @todo - clean up variable names
			
			var infowindowcontent, infowindow, marker;
			
			if( isNaN( latitude ) || isNaN( longitude ) )
			{
				if( window.console )
					console.log( "bgmp_createMarker(): "+ title +" latitude and longitude weren't valid." );
					
				return false;
			}
			
			if( icon == null )
			{
				// @todo - this check may not be needed anymore
				
				if( window.console )
					console.log( "bgmp_createMarker(): "+ title +"  icon wasn't passed in." );
				return false;
			}
			
			if( !bgmp.isInt( zIndex ) )
			{
				//if( window.console )
					//console.log( "bgmp_createMarker():  "+ title +" z-index wasn't valid." );	// this would fire any time it's empty
				
				zIndex = 0;
			}
			
			infowindowcontent = '<div class="bgmp_placemark"> <h3>'+ title +'</h3> <div>'+ details +'</div> </div>';
			
			try
			{
				infowindow = new google.maps.InfoWindow( {
					content		: infowindowcontent,
					maxWidth	: bgmpData.options.infoWindowMaxWidth
				} );
				
				// Replace commas with periods. Some (human) languages use commas to delimit the fraction from the whole number, but Google Maps doesn't accept that.
				latitude = parseFloat( latitude.replace( ',', '.' ) );
				longitude = parseFloat( longitude.replace( ',', '.' ) );
				
				marker = new google.maps.Marker( {
					'position'	: new google.maps.LatLng( latitude, longitude ),
					'map'		: map,
					'icon'		: icon,
					'title'		: title,
					'zIndex'	: zIndex
				} );
				
				google.maps.event.addListener( marker, 'click', function()
				{
					if( bgmp.previousInfoWindow != undefined )
						bgmp.previousInfoWindow.close();
					
					infowindow.open( map, marker );
					bgmp.previousInfoWindow = infowindow;
				} );
				
				
				return true;
			}
			catch( e )
			{
				//$( bgmp.canvas ).append( '<p>' + bgmp.name + " error: couldn't add map placemarks.</p>");		// add class for making red? other places need this too?	// @todo - need to figure out a good way to alert user that placemarks couldn't be added
				if( window.console )
					console.log('bgmp_createMarker: '+ e);
			}
		}
	} // end bgmp
	
	// Kick things off...
	$(document).ready( bgmp.init );
	
} // end bgmp_wrapper()

bgmp_wrapper(jQuery);