<?php

if( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die("Access denied.");

if( !class_exists( 'BasicGoogleMapsPlacemarks' ) )
{
	/**
	 * A Wordpress plugin that adds a custom post type for placemarks and builds a Google Map with them
	 * Requires PHP 5.2 because of filter_var()
	 * Requires Wordpress 3.1 because of WP_Query[ 'tax_query' ] support
	 *
	 * @package BasicGoogleMapsPlacemarks
	 * @author Ian Dunn <ian@iandunn.name>
	 * @link http://wordpress.org/extend/plugins/basic-google-maps-placemarks/
	 */
	class BasicGoogleMapsPlacemarks
	{
		// Declare variables and constants
		protected $settings, $options, $updatedOptions, $userMessageCount, $mapShortcodeCalled, $mapShortcodeCategories;
		const VERSION		= '1.6';
		const PREFIX		= 'bgmp_';
		const POST_TYPE		= 'bgmp';
		const TAXONOMY		= 'bgmp-category';
		const DEBUG_MODE	= false;
		
		/**
		 * Constructor
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function __construct()
		{
			require_once( dirname(__FILE__) . '/settings.php' );
			
			// Initialize variables
			$defaultOptions						= array( 'updates' => array(), 'errors' => array(), 'dbVersion' => '0' );
			$this->options						= array_merge( $defaultOptions, get_option( self::PREFIX . 'options', array() ) );
			$this->userMessageCount				= array( 'updates' => count( $this->options['updates'] ), 'errors' => count( $this->options['errors'] )	);
			$this->updatedOptions				= false;
			$this->mapShortcodeCalled			= false;
			$this->mapShortcodeCategories		= null;
			$this->settings						= new BGMPSettings( $this );
			
			// Register actions, filters and shortcodes
			add_action( 'plugins_loaded',		array( $this, 'upgrade' ) );
			add_action( 'init',					array( $this, 'createPostType' ) );
			add_action( 'init',					array( $this, 'createCategoryTaxonomy' ) );
			add_action( 'after_setup_theme',	array( $this, 'addFeaturedImageSupport' ), 11 );
			add_action( 'admin_init',			array( $this, 'addMetaBoxes' ) );
			add_action( 'wp',					array( $this, 'loadResources' ), 11 );
			add_action( 'wp_head',				array( $this, 'outputHead' ) );
			add_action( 'admin_notices',		array( $this, 'printMessages' ) );
			add_action( 'save_post',			array( $this, 'saveCustomFields' ) );
			add_action( 'wpmu_new_blog', 		array( $this, 'activateNewSite' ) );
			add_action( 'shutdown',				array( $this, 'shutdown' ) );
			
			add_filter( 'parse_query',			array( $this, 'sortAdminView' ) );
			
			add_shortcode( 'bgmp-map',			array( $this, 'mapShortcode') );
			add_shortcode( 'bgmp-list',			array( $this, 'listShortcode') );
			
			register_activation_hook( dirname(__FILE__) . '/basic-google-maps-placemarks.php', array( $this, 'networkActivate') );
		}
		
		/**
		 * Handles extra activation tasks for MultiSite installations
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function networkActivate()
		{
			global $wpdb;
			
			if( function_exists('is_multisite') && is_multisite() )
			{
				// Enable image uploads so the 'Set Featured Image' meta box will be available
				$mediaButtons = get_site_option( 'mu_media_buttons' );
				
				if( !array_key_exists( 'image', $mediaButtons ) || !$mediaButtons['image'] )
				{
					$mediaButtons['image'] = 1;
					update_site_option( 'mu_media_buttons', $mediaButtons );
				}
				
				// Activate the plugin across the network if requested
				if( array_key_exists( 'networkwide', $_GET ) && ( $_GET['networkwide'] == 1) )
				{
					$blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
					
					foreach( $blogs as $b ) 
					{
						switch_to_blog( $b );
						$this->singleActivate();
					}
					
					restore_current_blog();
				}
				else
					$this->singleActivate();
			}
			else
				$this->singleActivate();
		}
		
		/**
		 * Prepares a single blog to use the plugin
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		protected function singleActivate()
		{
			// Save default settings
			if( !get_option( self::PREFIX . 'map-width' ) )
				add_option( self::PREFIX . 'map-width', 600 );
			if( !get_option( self::PREFIX . 'map-height' ) )
				add_option( self::PREFIX . 'map-height', 400 );
			if( !get_option( self::PREFIX . 'map-address' ) )
				add_option( self::PREFIX . 'map-address', 'Seattle' );
			if( !get_option( self::PREFIX . 'map-latitude' ) )
				add_option( self::PREFIX . 'map-latitude', 47.6062095 );
			if( !get_option( self::PREFIX . 'map-longitude' ) )
				add_option( self::PREFIX . 'map-longitude', -122.3320708 );
			if( !get_option( self::PREFIX . 'map-zoom' ) )
				add_option( self::PREFIX . 'map-zoom', 7 );
			if( !get_option( self::PREFIX . 'map-type' ) )
				add_option( self::PREFIX . 'map-type', 'ROADMAP' );
			if( !get_option( self::PREFIX . 'map-type-control' ) )
				add_option( self::PREFIX . 'map-type-control', 'off' );
			if( !get_option( self::PREFIX . 'map-navigation-control' ) )
				add_option( self::PREFIX . 'map-navigation-control', 'DEFAULT' );
			if( !get_option( self::PREFIX . 'map-info-window-width' ) )
				add_option( self::PREFIX . 'map-info-window-width', 500 );	// @todo - this isn't DRY, same values in BGMPSettings::__construct() and upgrade()
		}
		
		/**
		 * Runs activation code on a new WPMS site when it's created
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param int $blogID
		 */
		public function activateNewSite( $blogID )
		{
			switch_to_blog( $blogID );
			$this->singleActivate();
			restore_current_blog();
		}
		
		/**
		 * Checks if the plugin was recently upgraded and upgrades if necessary
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function upgrade()
		{
			if( version_compare( $this->options[ 'dbVersion' ], self::VERSION, '==' ) )
				return;
			
			if( version_compare( $this->options[ 'dbVersion' ], '1.1', '<' ) )
			{
				// Populate new Address field from existing coordinate fields
				$posts = get_posts( array( 'numberposts' => -1, 'post_type' => self::POST_TYPE ) );
				if( $posts )
				{
					foreach( $posts as $p )
					{
						$address	= get_post_meta( $p->ID, self::PREFIX . 'address', true );
						$latitude	= get_post_meta( $p->ID, self::PREFIX . 'latitude', true );
						$longitude	= get_post_meta( $p->ID, self::PREFIX . 'longitude', true );
						
						if( empty($address) && !empty($latitude) && !empty($longitude) )
						{
							$address = $this->reverseGeocode( $latitude, $longitude );
							if( $address )
								update_post_meta( $p->ID, self::PREFIX . 'address', $address );
						}
					}
				}
			}
			
			if( version_compare( $this->options[ 'dbVersion' ], '1.6', '<' ) )
			{
				// Add new options
				add_option( self::PREFIX . 'map-type',					'ROADMAP' );
				add_option( self::PREFIX . 'map-type-control',			'off' );
				add_option( self::PREFIX . 'map-navigation-control',	'DEFAULT' );
				
				// @todo - this isn't DRY, those default values appear in activate and settings->construct. should have single array to hold them all
			}
			
			$this->options[ 'dbVersion'] = self::VERSION;
			$this->updatedOptions = true;
			
			// Clear WP Super Cache and W3 Total Cache
			if( function_exists( 'wp_cache_clear_cache' ) )
				wp_cache_clear_cache();
				
			if( class_exists( 'W3_Plugin_TotalCacheAdmin' ) )
			{
				$w3TotalCache =& w3_instance('W3_Plugin_TotalCacheAdmin');
				
				if( method_exists( $w3TotalCache, 'flush_all' ) )
					$w3TotalCache->flush_all();
			}
		}
		
		/**
		 * Adds featured image support
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function addFeaturedImageSupport()
		{
			// We enabled image media buttons for MultiSite on activation, but the admin may have turned it back off
			if( is_admin() && function_exists('is_multisite') && is_multisite() )
			{
				$mediaButtons = get_site_option( 'mu_media_buttons' );
				
				if( !array_key_exists( 'image', $mediaButtons ) || !$mediaButtons['image'] )
				{
					$this->enqueueMessage( sprintf(
						"%s requires the Images media button setting to be enabled in order to use custom icons on markers, but it's currently turned off. If you'd like to use custom icons you can enable it on the <a href=\"%ssettings.php\">Network Settings</a> page, in the Upload Settings section.",
						BGMP_NAME,
						network_admin_url()
					), 'error' );
				}
			}
			
			$supportedTypes = get_theme_support( 'post-thumbnails' );
			
			if( $supportedTypes === false )
				add_theme_support( 'post-thumbnails', array( self::POST_TYPE ) );				
			elseif( is_array( $supportedTypes ) )
			{
				$supportedTypes[0][] = self::POST_TYPE;
				add_theme_support( 'post-thumbnails', $supportedTypes[0] );
			}
		}
		
		/**
		 * Checks the current posts to see if they contain the map shortcode
		 * @author Ian Dunn <ian@iandunn.name>
		 * @return bool
		 */
		protected function mapShortcodeCalled()
		{
			global $post;
			$matches = array();
			$found = false;
			
			$this->mapShortcodeCalled = apply_filters( self::PREFIX .'mapShortcodeCalled', $this->mapShortcodeCalled );		// filter should be named map-shortcode-called, but people already using the camelcase version
			if( $this->mapShortcodeCalled )
				return true;
			
			if( !$post )
				return false;	// 404 or other context where $post doesn't exist
				
			// Check for shortcodes in the post's content
			preg_match_all( '/'. get_shortcode_regex() .'/s', $post->post_content, $matches );			
			if( !is_array( $matches ) || !array_key_exists( 2, $matches ) )
				return false;
			
			for( $i = 0; $i < count( $matches[2] ); $i++ )
			{
				if( $matches[2][$i] == 'bgmp-map' )
				{
					$found = $i;
					break;
				}
			}
			
			if( $found !== false )
			{
				// Parse out any arguments
				if( !empty( $matches[ 3 ][ $found ] ) )
				{
					$this->mapShortcodeCategories = str_replace( '"', '', $matches[ 3 ][ $found ] );
					$this->mapShortcodeCategories = explode( '=', $this->mapShortcodeCategories );
					$this->mapShortcodeCategories = explode( ',', $this->mapShortcodeCategories[1] );
				}
				
				$this->mapShortcodeCategories = apply_filters( self::PREFIX .'mapShortcodeCategories', $this->mapShortcodeCategories );		// filter should be named map-shortcode-categories, but people already using camelcase version
				
				return true;
			}
			
			else
				return false;
		}
		
		/**
		 * Load CSS and JavaScript files
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function loadResources()
		{
			wp_register_script(
				'googleMapsAPI',
				'http'. ( is_ssl() ? 's' : '' ) .'://maps.google.com/maps/api/js?sensor=false',
				false,
				false,
				true
			);
			
			wp_register_script(
				'bgmp',
				plugins_url( 'functions.js', __FILE__ ),
				array( 'googleMapsAPI', 'jquery' ),
				self::VERSION,
				true
			);
			
			wp_register_style(
				self::PREFIX .'style',
				plugins_url( 'style.css', __FILE__ ),
				false,
				self::VERSION
			);
			
			$this->mapShortcodeCalled = $this->mapShortcodeCalled();
			
			if( !is_admin() && $this->mapShortcodeCalled )
			{
				wp_enqueue_script( 'googleMapsAPI' );
				wp_enqueue_script( 'bgmp' );
				
				$bgmpData = sprintf(
					"bgmpData.options = %s;\r\nbgmpData.markers = %s",
					json_encode( $this->getMapOptions() ),
					json_encode( $this->getPlacemarks( $this->mapShortcodeCategories ) )
				);
				
				wp_localize_script( 'bgmp', 'bgmpData', array( 'l10n_print_after' => $bgmpData ) );
			}
			
			if( is_admin() || $this->mapShortcodeCalled )
				wp_enqueue_style( self::PREFIX . 'style' );
		}
		
		/**
		 * Outputs elements in the <head> section of the front-end
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function outputHead()
		{
			if( $this->mapShortcodeCalled )
				require_once( dirname(__FILE__) . '/views/front-end-head.php' );
		}
		
		/**
		 * Registers the custom post type
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function createPostType()
		{
			if( !post_type_exists( self::POST_TYPE ) )
			{
				$labels = array
				(
					'name'					=> __( 'Placemarks' ),
					'singular_name'			=> __( 'Placemark' ),
					'add_new'				=> __( 'Add New' ),
					'add_new_item'			=> __( 'Add New Placemark' ),
					'edit'					=> __( 'Edit' ),
					'edit_item'				=> __( 'Edit Placemark' ),
					'new_item'				=> __( 'New Placemark' ),
					'view'					=> __( 'View Placemark' ),
					'view_item'				=> __( 'View Placemark' ),
					'search_items'			=> __( 'Search Placemarks' ),
					'not_found'				=> __( 'No Placemarks found' ),
					'not_found_in_trash'	=> __( 'No Placemarks found in Trash' ),
					'parent'				=> __( 'Parent Placemark' )
				);
				
				$postTypeParams = array(
					'labels'			=> $labels,
					'singular_label'	=> 'Placemarks',
					'public'			=> true,
					'menu_position'		=> 20,
					'hierarchical'		=> false,
					'capability_type'	=> 'post',
					'rewrite'			=> array( 'slug' => 'placemarks', 'with_front' => false ),
					'query_var'			=> true,
					'supports'			=> array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'revisions' )
				);
				
				register_post_type(
					self::POST_TYPE,
					apply_filters( self::PREFIX . 'post-type-params', $postTypeParams )
				);
			}
		}
		
		/**
		 * Registers the category taxonomy
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function createCategoryTaxonomy()
		{
			if( !taxonomy_exists( self::TAXONOMY ) )
			{
				register_taxonomy(
					self::TAXONOMY,
					self::POST_TYPE,
					array(
						'label'					=> 'Category',
						'labels'				=> array( 'name' => 'Categories', 'singular_name' => 'Category' ),
						'hierarchical'			=> true,
						'rewrite'				=> array( 'slug' => self::TAXONOMY ),
						'update_count_callback'	=> '_update_post_term_count'
					)
				);	// @todo - add filter on the array
			}
		}
		
		/**
		 * Sorts the posts by the title in the admin view posts screen
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		function sortAdminView( $query )
		{
			global $pagenow;
			
			if( is_admin() && $pagenow == 'edit.php' && array_key_exists('post_type', $_GET) && $_GET['post_type'] == self::POST_TYPE )
			{
				$query->query_vars['order'] = apply_filters( self::PREFIX . 'admin-sort-order', 'ASC' );
				$query->query_vars['orderby'] = apply_filters( self::PREFIX . 'admin-sort-orderby', 'title' );
			}
		}
		
		/**
		 * Adds meta boxes for the custom post type
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function addMetaBoxes()
		{
			add_meta_box( self::PREFIX . 'placemark-address', 'Placemark Address', array($this, 'markupAddressFields'), self::POST_TYPE, 'normal', 'high' );
			add_meta_box( self::PREFIX . 'placemark-zIndex', 'Stacking Order', array($this, 'markupZIndexField'), self::POST_TYPE, 'side', 'default' );
		}
		
		/**
		 * Outputs the markup for the address fields
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function markupAddressFields()
		{
			global $post;
		
			$address	= get_post_meta( $post->ID, self::PREFIX . 'address', true );
			$latitude	= get_post_meta( $post->ID, self::PREFIX . 'latitude', true );
			$longitude	= get_post_meta( $post->ID, self::PREFIX . 'longitude', true );
			
			require_once( dirname(__FILE__) . '/views/meta-address.php' );
		}
		
		/**
		 * Outputs the markup for the stacking order field
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function markupZIndexField()
		{
			global $post;
		
			$zIndex = get_post_meta( $post->ID, self::PREFIX . 'zIndex', true );
			if( filter_var( $zIndex, FILTER_VALIDATE_INT ) === FALSE )
				$zIndex = 0;
				
			require_once( dirname(__FILE__) . '/views/meta-z-index.php' );
		}
		
		/**
		 * Saves values of the the custom post type's extra fields
		 * @param
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function saveCustomFields( $postID )
		{
			global $post;
			$coordinates = false;
			
			// Check preconditions
			if( isset( $_GET[ 'action' ] ) && ( $_GET[ 'action' ] == 'trash' || $_GET[ 'action' ] == 'untrash' ) )
				return;
			
			if(	!$post || $post->post_type != self::POST_TYPE || !current_user_can( 'edit_posts' ) )
				return;
				
			if( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' )
				return;
				
			
			// Save address
			update_post_meta( $post->ID, self::PREFIX . 'address', $_POST[ self::PREFIX . 'address'] );

			if( $_POST[ self::PREFIX . 'address'] )
				$coordinates = $this->geocode( $_POST[ self::PREFIX . 'address'] );
				
			if( $coordinates )
			{
				update_post_meta( $post->ID, self::PREFIX . 'latitude', $coordinates['latitude'] );
				update_post_meta( $post->ID, self::PREFIX . 'longitude', $coordinates['longitude'] );
			}
			else
			{	
				update_post_meta( $post->ID, self::PREFIX . 'latitude', '' );
				update_post_meta( $post->ID, self::PREFIX . 'longitude', '' );
				
				if( !empty( $_POST[ self::PREFIX . 'address'] ) )
					$this->enqueueMessage('That address couldn\'t be geocoded, please make sure that it\'s correct.', 'error' );
			}
			
			
			// Save z-index
			if( filter_var( $_POST[ self::PREFIX . 'zIndex'], FILTER_VALIDATE_INT ) === FALSE )
			{
				update_post_meta( $post->ID, self::PREFIX . 'zIndex', 0 );
				$this->enqueueMessage( 'The stacking order has to be an integer', 'error' );
			}	
			else
				update_post_meta( $post->ID, self::PREFIX . 'zIndex', $_POST[ self::PREFIX . 'zIndex'] );
		}
		
		/**
		 * Geocodes an address
		 * Google's API has a daily request limit, but this is only called when a post is published, so shouldn't ever be a problem.
		 * @param
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function geocode( $address )
		{
			$geocodeResponse = wp_remote_get( 'http://maps.googleapis.com/maps/api/geocode/json?address='. str_replace( ' ', '+', $address ) .'&sensor=false' );
			if( is_wp_error( $geocodeResponse ) )
			{
				$this->enqueueMessage( BGMP_NAME . ' geocode error: '. implode( '<br />', $geocodeResponse->get_error_messages() ), 'error' );
				return false;
			}
				
			$coordinates = json_decode( $geocodeResponse['body'] );
			
			if( empty( $coordinates->results ) )
				return false;
			else
				return array( 'latitude' => $coordinates->results[0]->geometry->location->lat, 'longitude' => $coordinates->results[0]->geometry->location->lng );
		}
		
		/**
		 * Reverse-geocodes a set of coordinates
		 * Google's API has a daily request limit, but this is only called when a post is published, so shouldn't ever be a problem.
		 * @param string $latitude
		 * @param string $longitude
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		protected function reverseGeocode( $latitude, $longitude )
		{
			$geocodeResponse = wp_remote_get( 'http://maps.googleapis.com/maps/api/geocode/json?latlng='. $latitude .','. $longitude .'&sensor=false' );
			$address = json_decode( $geocodeResponse['body'] );
			
			if( is_wp_error( $geocodeResponse ) || empty( $address->results ) )
				return false;
			else
				return $address->results[0]->formatted_address;
		}
			
		/**
		 * Defines the [bgmp-map] shortcode
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $attributes Array of parameters automatically passed in by Wordpress
		 * return string The output of the shortcode
		 */
		public function mapShortcode( $attributes ) 
		{
			if( !wp_script_is( 'googleMapsAPI', 'queue' ) || !wp_script_is( 'bgmp', 'queue' ) || !wp_style_is( self::PREFIX .'style', 'queue' ) )
				return '<p class="error">'. BGMP_NAME .' error: JavaScript and/or CSS files aren\'t loaded. If you\'re using do_shortcode() you need to add a filter to your theme first. See <a href="http://wordpress.org/extend/plugins/basic-google-maps-placemarks/faq/">the FAQ</a> for details.</p>';
			
			$output = sprintf('
				<div id="%smap-canvas">
					<p>Loading map...</p>
					<p><img src="%s" alt="Loading" /></p>
				</div>',
				self::PREFIX,
				plugins_url( 'images/loading.gif', __FILE__ )
			);
			
			return $output;
		}		
		
		/**
		 * Defines the [bgmp-list] shortcode
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $attributes Array of parameters automatically passed in by Wordpress
		 * return string The output of the shortcode
		 */
		public function listShortcode( $attributes ) 
		{
			$params = array(
				'numberposts'	=> -1,
				'post_type'		=> self::POST_TYPE,
				'post_status'	=> 'publish',
				'orderby'		=> 'title',
				'order'			=> 'ASC'
			);
			
			$posts = get_posts( apply_filters( self::PREFIX . 'list-shortcode-params', $params ) );
			
			if( $posts )
			{
				$output = '<ul id="'. self::PREFIX .'list">';
				
				foreach( $posts as $p )
				{
					// @todo - redo this w/ setup_postdata() and template tags instead of accessing properties directly
					
					$address = get_post_meta( $p->ID, self::PREFIX . 'address', true );
						
					$markerHTML = sprintf('
						<li>
							<h3>%s</h3>
							<div>%s</div>
							<p><a href="%s">%s</a></p>
						</li>',
						$p->post_title,
						wpautop( $p->post_content ),
						'http://google.com/maps?q='. $address,
						$address
					);
					
					$output .= apply_filters( self::PREFIX . 'list-marker-output', $markerHTML, $p->ID );
				}
				
				$output .= '</ul>';
				
				return $output;
			}
			else
				return "There aren't any published placemarks right now";
		}
		
		/**
		 * Gets map options
		 * @author Ian Dunn <ian@iandunn.name>
		 * @return string JSON-encoded array
		 */
		public function getMapOptions()
		{
			$options = array(
				'mapWidth'				=> $this->settings->mapWidth,
				'mapHeight'				=> $this->settings->mapHeight,
				'latitude'				=> $this->settings->mapLatitude,
				'longitude'				=> $this->settings->mapLongitude,
				'zoom'					=> $this->settings->mapZoom,
				'type'					=> $this->settings->mapType,
				'typeControl'			=> $this->settings->mapTypeControl,
				'navigationControl'		=> $this->settings->mapNavigationControl,
				'infoWindowMaxWidth'	=> $this->settings->mapInfoWindowMaxWidth
			);
			
			return apply_filters( self::PREFIX . 'map-options', $options );
		}
		
		/**
		 * Gets the published placemarks from the database, formats and outputs them.
		 * json_encode() requires PHP 5.
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param mixed $categories null for all, or an array with category slugs to include only those categories
		 * @return string JSON-encoded array
		 */
		public function getPlacemarks( $categories = null )
		{
			$placemarks = array();
			
			$query = array( 
				'numberposts'	=> -1,
				'post_type'		=> self::POST_TYPE,
				'post_status'	=> 'publish'
			);
			
			if( $categories )
			{
				$query['tax_query'] = array(
					array(
						'taxonomy'	=> self::TAXONOMY,
						'field'		=> 'slug',
						'terms'		=> $categories
					)
				);
			}
			
			$publishedPlacemarks = get_posts( apply_filters( self::PREFIX . 'get-placemarks-query', $query ) );
			
			if( $publishedPlacemarks )
			{
				foreach( $publishedPlacemarks as $pp )
				{
					$icon = wp_get_attachment_image_src( get_post_thumbnail_id( $pp->ID ) );
					$defaultIcon = apply_filters( self::PREFIX .'default-icon', plugins_url( 'images/default-marker.png', __FILE__ ), $pp->ID );
 
					$placemarks[] = array(
						'title'		=> $pp->post_title,
						'latitude'	=> get_post_meta( $pp->ID, self::PREFIX . 'latitude', true ),
						'longitude'	=> get_post_meta( $pp->ID, self::PREFIX . 'longitude', true ),
						'details'	=> wpautop( $pp->post_content ),
						'icon'		=> is_array( $icon ) ? $icon[0] : $defaultIcon,
						'zIndex'	=> get_post_meta( $pp->ID, self::PREFIX . 'zIndex', true )
					);
				}
			}
			
			return apply_filters( self::PREFIX . 'get-placemarks-return', $placemarks );
		}
		
		/**
		 * Displays updates and errors
		 * NOTE: In order to allow HTML in the output, any unsafe variables passed to enqueueMessage() need to be escaped before they're passed in, instead of escaping here.
		 *
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function printMessages()
		{
			foreach( array( 'updates', 'errors' ) as $type )
			{
				if( $this->options[ $type ] && ( self::DEBUG_MODE || $this->userMessageCount[ $type ] ) )
				{
					echo '<div id="message" class="'. ( $type == 'updates' ? 'updated' : 'error' ) .'">';
					foreach( $this->options[ $type ] as $message )
						if( $message[ 'mode' ] == 'user' || self::DEBUG_MODE )
							echo '<p>'. $message[ 'message' ] .'</p>';
					echo '</div>';
					
					$this->options[ $type ] = array();
					$this->updatedOptions = true;
					$this->userMessageCount[ $type ] = 0;
				}
			}
		}
		
		/**
		 * Queues up a message to be displayed to the user
		 * NOTE: In order to allow HTML in the output, any unsafe variables in $message need to be escaped before they're passed in, instead of escaping here.
		 *		 
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $message The text to show the user
		 * @param string $type 'update' for a success or notification message, or 'error' for an error message
		 * @param string $mode 'user' if it's intended for the user, or 'debug' if it's intended for the developer
		 */
		protected function enqueueMessage( $message, $type = 'update', $mode = 'user' )
		{
			if( !is_string( $message ) )
				return false;
			
			array_push( $this->options[$type .'s'], array(
				'message' => $message,
				'type' => $type,
				'mode' => $mode
			) );
			
			if( $mode == 'user' )
				$this->userMessageCount[$type . 's']++;
			
			$this->updatedOptions = true;
			
			return true;
		}
		
		/**
		 * Prints the output in various ways for debugging.
		 * @author Ian Dunn <ian.dunn@mpangodev.com>
		 * @param mixed $data
		 * @param string $output 'message' will be sent to an admin notice; 'die' will be output inside wp_die(); 'transient' will create a transient in the database; 'return' will be returned;
		 * @param string $message Optionally message to output before description
		 */
		protected function describe( $data, $output = 'die', $message = '' )
		{
			$type = gettype( $data );

			// Build description
			switch( $type )
			{
				case 'array':
				case 'object':
					$length = count( $data );
					$data = print_r( $data, true );
				break;
				
				case 'string';
					$length = strlen( $data );
				break;
				
				default:
					$length = count( $data );
					
					ob_start();
					var_dump( $data );
					$data = ob_get_contents();
					ob_end_clean();
					
					$data = print_r( $data, true );
				break;
			}
			
			$description = sprintf('
				<p>
					%s
					Type: %s<br />
					Length: %s<br />
					Content: <br /><blockquote><pre>%s</pre></blockquote>
				</p>',
				( $message ? 'Message: '. $message .'<br />' : '' ),
				$type,
				$length,
				$data
			);
			
			// Output description
			switch( $output )
			{
				case 'notice':
					$this->enqueueMessage( $description, 'error' );
				break;
				
				case 'die':
					wp_die( $description );
				break;
				
				case 'output':
					return $description;
				break;
				
				case 'transient':
					$uniqueKey = $message ? str_replace( array( ' ', '-', '/', '\\', '.' ), '_', $message ) : mt_rand();	// removes characters that are invalid in MySQL column names
					set_transient( self::PREFIX . 'describe_' . $uniqueKey, $description, 60 * 5 );
				break;
				
				case 'echo':
				default:
					echo $description;		// @todo - want to esc_html on message, but not entire description. can't do to $message above because don't want to escape for other switch cases
				break;
			}
		}
		
		/**
		 * Writes options to the database
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function shutdown()
		{
			if( $this->updatedOptions )
				update_option( self::PREFIX . 'options', $this->options );
		}
	} // end BasicGoogleMapsPlacemarks
}

?>