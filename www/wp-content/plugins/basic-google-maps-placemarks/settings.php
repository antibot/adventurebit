<?php

if( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die("Access denied.");

if( !class_exists( 'BGMPSettings' ) )
{
	/**
	 * Registers and handles the plugin's settings
	 *
	 * @package BasicGoogleMapsPlacemarks
	 * @author Ian Dunn <ian@iandunn.name>
	 * @link http://wordpress.org/extend/plugins/basic-google-maps-placemarks/
	 */
	class BGMPSettings
	{
		protected $bgmp;
		public $mapWidth, $mapHeight, $mapAddress, $mapLatitude, $mapLongitude, $mapZoom, $mapType, $mapTypeControl, $mapNavigationControl, $mapInfoWindowMaxWidth;
		const PREFIX = 'bgmp_';		// @todo - can't you just acceess $bgmp's instead ?
		
		/**
		 * Constructor
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function __construct( $bgmp )
		{
			$this->bgmp						= $bgmp;
			$this->mapWidth					= get_option( self::PREFIX . 'map-width',				600 );
			$this->mapHeight				= get_option( self::PREFIX . 'map-height',				400 );
			$this->mapAddress				= get_option( self::PREFIX . 'map-address',				'Seattle' );
			$this->mapLatitude				= get_option( self::PREFIX . 'map-latitude',			47.6062095 );
			$this->mapLongitude				= get_option( self::PREFIX . 'map-longitude',			-122.3320708 );
			$this->mapZoom					= get_option( self::PREFIX . 'map-zoom',				7 );
			$this->mapType					= get_option( self::PREFIX . 'map-type',				'ROADMAP' );
			$this->mapTypeControl			= get_option( self::PREFIX . 'map-type-control',		'off' );
			$this->mapNavigationControl		= get_option( self::PREFIX . 'map-navigation-control',	'DEFAULT' );
			$this->mapInfoWindowMaxWidth	= get_option( self::PREFIX . 'map-info-window-width',	500 );	// @todo - this isn't DRY, same values in BGMP::singleActivate() and upgrade()
			
			add_action( 'admin_menu',		array( $this, 'addSettingsPage' ) );
			add_action( 'admin_init',		array( $this, 'addSettings') );			// @todo - this may need to fire after admin_menu
			add_filter( 'plugin_action_links_basic-google-maps-placemarks/basic-google-maps-placemarks.php', array($this, 'addSettingsLink') );
			
			$this->updateMapCoordinates();	// @todo - set this to fire on a hook
		}
		
		/**
		 * Get the map center coordinates from the address and update the database values
		 * The latitude/longitude need to be updated when the address changes, but there's no way to do that with the settings API
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		protected function updateMapCoordinates()
		{
			$haveCoordinates = true;
			
			if( isset($_POST) && array_key_exists( self::PREFIX . 'map-address', $_POST ) )
			{
				if( empty( $_POST[ self::PREFIX . 'map-address' ] ) )
					$haveCoordinates = false;
				else
				{
					$coordinates = $this->bgmp->geocode( $_POST[ self::PREFIX . 'map-address'] );
				
					if( !$coordinates )
						$haveCoordinates = false;
				}
				
				if( $haveCoordinates )
				{
					update_option( self::PREFIX . 'map-latitude', $coordinates['latitude'] );
					update_option( self::PREFIX . 'map-longitude', $coordinates['longitude'] );
				}
				else
				{
					// @todo - can't call protected from this class - $this->bgmp->enqueueMessage('That address couldn\'t be geocoded, please make sure that it\'s correct.', 'error' );
					
					update_option( self::PREFIX . 'map-latitude', 'Error' );	// @todo - update these
					update_option( self::PREFIX . 'map-longitude', 'Error' );
				}
			}
		}
		
		/**
		 * Adds a page to Settings menu
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function addSettingsPage()
		{
			add_options_page( BGMP_NAME .' Settings', BGMP_NAME, 'manage_options', self::PREFIX . 'settings', array( $this, 'markupSettingsPage' ) );
		}
		
		/**
		 * Creates the markup for the settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function markupSettingsPage()
		{
			if( current_user_can( 'manage_options' ) )
				require_once( dirname(__FILE__) . '/views/settings.php' );
			else
				wp_die( 'Access denied.' );
		}
		
		/**
		 * Adds a 'Settings' link to the Plugins page
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $links The links currently mapped to the plugin
		 * @return array
		 */
		public function addSettingsLink($links)
		{
			array_unshift( $links, '<a href="http://wordpress.org/extend/plugins/basic-google-maps-placemarks/faq/">Help</a>' );
			array_unshift( $links, '<a href="options-general.php?page='. self::PREFIX . 'settings">Settings</a>' );
			
			return $links; 
		}
		
		/**
		 * Adds our custom settings to the admin Settings pages
		 * We intentionally don't register the map-latitude and map-longitude settings because they're set by updateMapCoordinates()
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function addSettings()
		{
			add_settings_section( self::PREFIX . 'map-settings', '', array($this, 'settingsSectionCallback'), self::PREFIX . 'settings' );
			
			add_settings_field( self::PREFIX . 'map-width',					'Map Width',					array($this, 'mapWidthCallback'),					self::PREFIX . 'settings', self::PREFIX . 'map-settings',	 array( 'label_for' => self::PREFIX . 'map-width' ) );
			add_settings_field( self::PREFIX . 'map-height',				'Map Height',					array($this, 'mapHeightCallback'),					self::PREFIX . 'settings', self::PREFIX . 'map-settings',	 array( 'label_for' => self::PREFIX . 'map-height' ) );
			add_settings_field( self::PREFIX . 'map-address',				'Map Center Address',			array($this, 'mapAddressCallback'),					self::PREFIX . 'settings', self::PREFIX . 'map-settings',	 array( 'label_for' => self::PREFIX . 'map-address' ) );
			add_settings_field( self::PREFIX . 'map-latitude',				'Map Center Latitude',			array($this, 'mapLatitudeCallback'),				self::PREFIX . 'settings', self::PREFIX . 'map-settings',	 array( 'label_for' => self::PREFIX . 'map-latitude' ) );
			add_settings_field( self::PREFIX . 'map-longitude',				'Map Center Longitude',			array($this, 'mapLongitudeCallback'),				self::PREFIX . 'settings', self::PREFIX . 'map-settings',	 array( 'label_for' => self::PREFIX . 'map-longitude' ) );
			add_settings_field( self::PREFIX . 'map-zoom',					'Zoom',							array($this, 'mapZoomCallback'),					self::PREFIX . 'settings', self::PREFIX . 'map-settings',	 array( 'label_for' => self::PREFIX . 'map-zoom' ) );
			add_settings_field( self::PREFIX . 'map-type',					'Map Type',						array($this, 'mapTypeCallback'),					self::PREFIX . 'settings', self::PREFIX . 'map-settings',	 array( 'label_for' => self::PREFIX . 'map-type' ) );			
			add_settings_field( self::PREFIX . 'map-type-control',			'Type Control',					array($this, 'mapTypeControlCallback'),				self::PREFIX . 'settings', self::PREFIX . 'map-settings',	 array( 'label_for' => self::PREFIX . 'map-type-control' ) );
			add_settings_field( self::PREFIX . 'map-navigation-control',	'Navigation Control',			array($this, 'mapNavigationControlCallback'),		self::PREFIX . 'settings', self::PREFIX . 'map-settings',	 array( 'label_for' => self::PREFIX . 'map-navigation-control' ) );
			add_settings_field( self::PREFIX . 'map-info-window-width',		'Info. Window Maximum Width',	array($this, 'mapInfoWindowMaxWidthCallback'),		self::PREFIX . 'settings', self::PREFIX . 'map-settings',	 array( 'label_for' => self::PREFIX . 'map-info-window-width' ) );
			
			register_setting( self::PREFIX . 'settings', self::PREFIX . 'map-width' );
			register_setting( self::PREFIX . 'settings', self::PREFIX . 'map-height' );
			register_setting( self::PREFIX . 'settings', self::PREFIX . 'map-address' );
			register_setting( self::PREFIX . 'settings', self::PREFIX . 'map-zoom' );
			register_setting( self::PREFIX . 'settings', self::PREFIX . 'map-type' );
			register_setting( self::PREFIX . 'settings', self::PREFIX . 'map-type-control' );
			register_setting( self::PREFIX . 'settings', self::PREFIX . 'map-navigation-control' );
			register_setting( self::PREFIX . 'settings', self::PREFIX . 'map-info-window-width' );
			
			// @todo - add input validation  -- http://ottopress.com/2009/wordpress-settings-api-tutorial/
		}
		
		/**
		 * Adds the section introduction text to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function settingsSectionCallback()
		{
			echo '<p>These settings determine the size and center of the map, zoom level and popup window size. For the center address, you can type in anything that you would type into a Google Maps search field, from a full address to an intersection, landmark, city or just a zip code.</p>';
		}
		
		/**
		 * Adds the map-width field to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function mapWidthCallback()
		{
			echo '<input id="'. self::PREFIX .'map-width" name="'. self::PREFIX .'map-width" type="text" value="'. $this->mapWidth .'" class="code" /> pixels';
		}
		
		/**
		 * Adds the map-height field to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function mapHeightCallback()
		{
			echo '<input id="'. self::PREFIX .'map-height" name="'. self::PREFIX .'map-height" type="text" value="'. $this->mapHeight .'" class="code" /> pixels';
		}
		
		/**
		 * Adds the address field to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function mapAddressCallback()
		{
			echo '<input id="'. self::PREFIX .'map-address" name="'. self::PREFIX .'map-address" type="text" value="'. $this->mapAddress .'" class="code" />';
		}
		
		/**
		 * Adds the latitude field to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function mapLatitudeCallback()
		{
			echo '<input id="'. self::PREFIX .'map-latitude" name="'. self::PREFIX .'map-latitude" type="text" value="'. $this->mapLatitude .'" class="code" readonly="readonly" />';
		}
		
		/**
		 * Adds the longitude field to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function mapLongitudeCallback()
		{
			echo '<input id="'. self::PREFIX .'map-longitude" name="'. self::PREFIX .'map-longitude" type="text" value="'. $this->mapLongitude .'" class="code" readonly="readonly" />';
		}
		
		/**
		 * Adds the zoom field to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function mapZoomCallback()
		{
			echo '<input id="'. self::PREFIX .'map-zoom" name="'. self::PREFIX .'map-zoom" type="text" value="'. $this->mapZoom .'" class="code" /> 0 (farthest) to 21 (closest)';
		}
		
		/**
		 * Adds the zoom field to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function mapTypeCallback()
		{
			echo '<select id="'. self::PREFIX .'map-type" name="'. self::PREFIX .'map-type">
					<option value="ROADMAP" '. ( $this->mapType == 'ROADMAP' ? 'selected="selected"' : '' ) .'>Street Map</option>
					<option value="SATELLITE" '. ( $this->mapType == 'SATELLITE' ? 'selected="selected"' : '' ) .'>Satellite Images</option>
					<option value="HYBRID" '. ( $this->mapType == 'HYBRID' ? 'selected="selected"' : '' ) .'>Hybrid</option>
					<option value="TERRAIN" '. ( $this->mapType == 'TERRAIN' ? 'selected="selected"' : '' ) .'>Terrain</option>
				</select>';
		}
		
		/**
		 * Adds the zoom field to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function mapTypeControlCallback()
		{
			echo '<select id="'. self::PREFIX .'map-type-control" name="'. self::PREFIX .'map-type-control">
					<option value="off" '. ( $this->mapTypeControl == 'off' ? 'selected="selected"' : '' ) .'>Off</option>
					<option value="DEFAULT" '. ( $this->mapTypeControl == 'DEFAULT' ? 'selected="selected"' : '' ) .'>Automatic</option>
					<option value="HORIZONTAL_BAR" '. ( $this->mapTypeControl == 'HORIZONTAL_BAR' ? 'selected="selected"' : '' ) .'>Horizontal Bar</option>
					<option value="DROPDOWN_MENU" '. ( $this->mapTypeControl == 'DROPDOWN_MENU' ? 'selected="selected"' : '' ) .'>Dropdown Menu</option>
				</select>';
			echo ' "Automatic" will automatically switch to the appropriate control based on the window size and other factors.';
		}
		
		/**
		 * Adds the zoom field to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function mapNavigationControlCallback()
		{
			echo '<select id="'. self::PREFIX .'map-navigation-control" name="'. self::PREFIX .'map-navigation-control">
					<option value="off" '. ( $this->mapNavigationControl == 'DEFAULT' ? 'selected="selected"' : '' ) .'>Off</option>
					<option value="DEFAULT" '. ( $this->mapNavigationControl == 'DEFAULT' ? 'selected="selected"' : '' ) .'>Automatic</option>
					<option value="SMALL" '. ( $this->mapNavigationControl == 'SMALL' ? 'selected="selected"' : '' ) .'>Small</option>
					<option value="ANDROID" '. ( $this->mapNavigationControl == 'ANDROID' ? 'selected="selected"' : '' ) .'>Android</option>
					<option value="ZOOM_PAN" '. ( $this->mapNavigationControl == 'ZOOM_PAN' ? 'selected="selected"' : '' ) .'>Zoom/Pan</option>
				</select>';
			echo ' "Automatic" will automatically switch to the appropriate control based on the window size and other factors.';
		}
		
		/**
		 * Adds the info-window-width field to the Settings page
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function mapInfoWindowMaxWidthCallback()
		{
			echo '<input id="'. self::PREFIX .'map-info-window-width" name="'. self::PREFIX .'map-info-window-width" type="text" value="'. $this->mapInfoWindowMaxWidth .'" class="code" /> pixels';
		}
	} // end BGMPSettings
}

?>