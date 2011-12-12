<?php

/**
 * Unit tests. Uses SimpleTest for WordPress plugin.
 *
 * @package BasicGoogleMapsPlacemarks
 * @author Ian Dunn <ian@iandunn.name>
 * @link http://wordpress.org/extend/plugins/basic-google-maps-placemarks/
 * @link http://wordpress.org/extend/plugins/simpletest-for-wordpress/
 */

require_once( WP_PLUGIN_DIR . '/simpletest-for-wordpress/WpSimpleTest.php' );
require_once( WP_PLUGIN_DIR . '/basic-google-maps-placemarks/core.php' );

// how to do for functhions that don't return anything and just do api stuff? is that where integration tests come in?
// setUp() backs up all postmarks then deletes. tearDown() restores backup? 
	// probably write separate functions for that 'cause won't want to call them each time
// test results, not internals
/*
http://www.ibm.com/developerworks/opensource/library/os-refactoringphp/index.html
	instead of using globals, pass them in
		if nothing passed in, then assign to the global var you originally used
	try to make the function more abstract instead of relying on the current state
	init function separate from constructor, option to not call it
*/


class bgmpCoreUnitTests extends UnitTestCase
{
	/**
	 * Sets a protected or private method to be accessible
	 * @author Joel Uckelman <http://www.nomic.net/~uckelman/>
	 * @link http://stackoverflow.com/questions/249664/best-practices-to-test-protected-methods-with-phpunit
	 * @param string $methodName
	 * @return ?
	 */
	protected static function getHiddenMethod( $methodName )
	{
		$class = new ReflectionClass( 'BasicGoogleMapsPlacemarks' );
		$method = $class->getMethod( $methodName );
		$method->setAccessible( true );
		
		return $method;
	}
	
	// addfeaturedimage support?
	// mapshortcode called
		// method from faq of setting it to true

	public function testGeocodeReturnsFalseWithInvalidAddress()
	{
		$bgmp = new BasicGoogleMapsPlacemarks();
		$this->assertFalse( $bgmp->geocode( 'fjal39802afjl;fsdjfalsdf329jfas;' ) );
	}
	
	public function testGeocodeReturnsCorrectLatitude()
	{
		$bgmp = new BasicGoogleMapsPlacemarks();
		$address = $bgmp->geocode( "Kylie's Chicago Pizza Seattle" );
		$this->assertEqual( $address['latitude'], '47.6062095' );
	}
	
	public function testGeocodeReturnsCorrectLongitude()
	{
		$bgmp = new BasicGoogleMapsPlacemarks();
		$address = $bgmp->geocode( "111 Chelsea Street, Boston, MA 02128" );
		$this->assertEqual( $address['longitude'], '-71.035377' );
	}

	public function testReverseGeocodeReturnsFalseWithInvalidCoordinates()
	{
		$bgmp = new BasicGoogleMapsPlacemarks();
		$reverseGeocode = self::getHiddenMethod( 'reverseGeocode' );
		
		$this->assertFalse( $reverseGeocode->invokeArgs( $bgmp, array( '23432.324', 'tomato' ) ) );
	}
	
	public function testReverseGeocodeReturnsCorrectAddressWithValidCoordinates()
	{
		$bgmp = new BasicGoogleMapsPlacemarks();
		$reverseGeocode = self::getHiddenMethod( 'reverseGeocode' );
		$address = $reverseGeocode->invokeArgs( $bgmp, array( '39.7589478', '-84.1916069' ) );
  
		$this->assertEqual( $address, '2-44 State Highway 48, Dayton, OH 45423, USA' );
	}
		
	// map shortcode
	
	// list shortcode

	/*
	public function testGetPlacemarksReturnsEmptyArrayWhenNoPostsExist()
	{
		$bgmp = new BasicGoogleMapsPlacemarks();
		$markers = $bgmp->getPlacemarks();
		
		// @todo - remove all posts, or set to draft or something
		$this->assertTrue( is_array( $markers ) );
		$this->assertTrue( count( $markers ) === 0 );
		// @todo - restore the posts
	}
	*/
	
	public function testGetPlacemarksReturnsPopulatedArrayWhenPostsExist()
	{
		$bgmp = new BasicGoogleMapsPlacemarks();
		$markers = $bgmp->getPlacemarks();
		
		// @todo - insert a test post to ensure at least 1 exists
		$this->assertTrue( is_array( $markers ) );
		$this->assertTrue( count( $markers ) >= 1 );
		// test that they contain actual posts w/ ids
		// @todo - remove the test post to clean up
	}
	
	// enquue  message
		// returns false when $message isn't a string
		// returns false when $type and $mode are invalid?
		// if message is string, adds it to $bgmp->options, increases usermessagecount if appropriate, sets $updatedoptions to true, returns true 
	
	// describe
		// if $output = 'output', returns the content
		// if echo then doesn't reutrn anything
		// if notice enquese a message and doesnt' return anything
	
	// shutdown?	
	
} // end bgmpCoreUnitTests

// setup another class to test settings.php - maybe should setup separate file and testsuite functions?