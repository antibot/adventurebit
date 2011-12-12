=== Basic Google Maps Placemarks ===
Contributors: iandunn
Donate link: http://www.doctorswithoutborders.org
Tags: google map, map, embed, marker, placemark, icon, geocode
Requires at least: 3.1
Tested up to: 3.3
Stable tag: 1.6
License: GPL2

Embeds a Google Map into your site and lets you add map markers with custom icons and information windows. Each marker can have a different icon.


== Description ==
BGMP creates a [custom post type](http://codex.wordpress.org/Post_Types) for placemarks (markers) on a Google Map. The map is embedded into pages or posts using a shortcode, and there are settings which define it's size, center and zoom level.

Then you can create markers that will show up on the map using the featured image as the map icon. When a marker is clicked on, a box will appear showing its title and description. There's also a shortcode that will output a text listing of all of the markers. Placemarks can be assigned to categories and you can configure maps to only display certain categories.

You can see a live example of the map it creates at [washingtonhousechurches.net](http://washingtonhousechurches.net).


== Installation ==

**Automatic Installation**

1. Login to your blog and go to the Plugins page.
2. Click on the 'Add New' button.
3. Search for 'Basic Google Maps Placemarks'.
4. Click 'Install now'.
5. Enter your FTP or FTPS username and password. If you don't know it, you can ask your web host for it.
6. Click 'Activate plugin'.
8. Follow the Basic Usage instructions below

**Manual Installation**

1. Download the plugin and un-zip it.
2. Upload the *basic-google-maps-placemarks* folder to your *wp-content/plugins/* directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Follow the Basic Usage instructions below

**Upgrading:**

1. Just re-upload the plugin folder to the wp-content/plugins directory to overwrite the old files.
2. If you're upgrading from version 1.0, you'll need to populate the new address field based on existing coordinates. Just deactiveate and re-activate the plugin and it'll do that automatically. This may take a minute or two, depending on the number of placemarks you have.

**Basic Usage:**

1. After activating the plugin, go to the 'Basic Google Maps Placemarks' page under the Settings menu. Enter the address that the map should be centered on.
2. Create a page or post where you'd like to embed the map, and type `[bgmp-map]` in the content area.
3. Go to the Placemarks menu and click 'Add New'. Enter the title, address, etc.
4. Click on 'Set Featured Image' to upload the icon.
5. Click on the 'Publish' or 'Update' button to save the placemark.

**Advanced Usage:**

*Multiple maps with different placemarks:*

1. Go to the Placemarks menu and click on Categories, and add a category for each set of placemarks.
2. Edit your placemarks and click on the category you want to assign them to.
3. Edit the place where the map is embedded and add the category parameter to the shortcode. For example: [bgmp-map categories="restaurants,record-stores"] or [bgmp-map categories="parks"]. Use the category's slug, which is displayed on the Categories page in step 1. Separate each slug with a comma.
4. You can add the [bgmp-map] shortcode to multiple pages, each time using a different set of categories.

*Setting the stacking order of overlapping markers:*

1. Choose which placemark you want to appear on top and edit it.
2. Enter a number in the Stacking Order meta box in the right column that is greater than the other placemark's stacking order.

*Adding a text-based list of placemarks to a page:*

1. Edit the post or page you want the list to appear on.
2. Type `[bgmp-list]` in the context area.
3. Click the 'Publish' or 'Update' button.

Check [the FAQ](http://wordpress.org/extend/plugins/basic-google-maps-placemarks/faq/) and [support forum](http://wordpress.org/tags/basic-google-maps-placemarks?forum_id=10) if you have any questions.


== Frequently Asked Questions ==

= How do I use the plugin? =
Read the Basic Usage section of [the Installation page](http://wordpress.org/extend/plugins/basic-google-maps-placemarks/installation/) for instructions. If you still have questions, read this FAQ or check [the support forum](http://wordpress.org/tags/basic-google-maps-placemarks?forum_id=10).

= The map doesn't look right. =
This is probably because some rules from your theme's stylesheet are being applied to the map. Contact your theme developer for advice on how to override the rules.

= The page says 'Loading map...', but the map never shows up. =
[Check to see if there are any Javascript errors](http://www.cmsmarket.com/resources/dev-corner/92-how-to-check-for-javascript-errors) caused by your theme or other plugins, because an error by any script will prevent all the other scripts from running.

Also, make sure your theme is calling *[wp_footer()](http://codex.wordpress.org/Function_Reference/wp_footer)* right before the closing *body* tag in footer.php. 

= Can I embed more than one map on the same page? =
No, the Google Maps JavaScript API can only support one map on a page.

= How can I force the info. window width and height to always be the same size? =
Add the following styles to your theme's style.css file:

`
.bgmp_placemark
{
	width: 450px;
	height: 350px;
}
`

= Can registered users create their own placemarks? =
Yes. The plugin creates a [custom post type](http://codex.wordpress.org/Post_Types), so it has the same [permission structure](http://codex.wordpress.org/Roles_and_Capabilities) as regular posts/pages.

= Will the plugin work in WordPress MultiSite? =
Yes. Version 1.2 added support for MultiSite installations.

= None of the placemarks are showing up the map =
If your theme is calling `add_theme_support( 'post-thumbnails' )` and passing in a specific list of post types -- rather than enabling support for all post types -- then it should check if some post types are already registered and include those as well. This only applies if it's hooking into `after_theme_setup` with a priority higher than 10. Contact your theme developer and ask them to fix their code.

= How can I override the styles the plugin applies to the map? =
The width/height of the map and marker information windows are always defined in the Settings, but you can override everything else by putting this code in your theme's functions.php file:

`
add_action('init', 'my_theme_name_bgmp_style');
function my_theme_name_bgmp_style()
{
	wp_deregister_style( 'bgmp_style' );
	wp_register_style(
		'bgmp_style',
		get_bloginfo('template_url') . '/bgmp-style.css'
	);
	wp_enqueue_style( 'bgmp_style' );
}
`

Then create a bgmp-style.css file inside your theme directory and put your styles there. If you'd prefer, you could also just make it an empty file and put the styles in your main style.css, but either way you need to register and enqueue a style with the `bgmp_style` handle, because the plugin checks to make sure the CSS and JavaScript files are loaded before embedding the map.

= I upgraded to the latest version and now the map isn't working. =
If you're running a caching plugin like WP Super Cache, make sure you delete the cache contents so that the latest files are loaded, and then refresh your browser.

= I get an error when using do_shortcode() to call the map shortcode =
For efficiency, the plugin only loads the required JavaScript, CSS and markup files on pages where it detects the map shortcode is being called. It's not possible to detect when [do_shortcode()](http://codex.wordpress.org/Function_Reference/do_shortcode) is used, so you need to manually let the plugin know to load the files by adding this code to your theme:

`
function my_theme_name_bgmp_shortcode_check()
{
	global $post;
	
	$shortcodePageSlugs = array(
		'first-page-slug',
		'second-page-slug',
		'hello-world'
	);
	
	if( $post )
		if( in_array( $post->post_name, $shortcodePageSlugs ) )
			add_filter( 'bgmp_mapShortcodeCalled', 'your_theme_name_bgmp_shortcode_called' );
}
add_action( 'wp', 'my_theme_name_bgmp_shortcode_check' );

function your_theme_name_bgmp_shortcode_called( $mapShortcodeCalled )
{
	return true;
}
`

Copy and paste that into your theme's *functions.php* file, update the function names and filter arguments, and then add the slugs of any pages/posts containing the map to $shortcodePageSlugs. If you're using it on the home page, the slug will be 'home'.

This only works if the file that calls do_shortcode() is [registered as a page template](http://codex.wordpress.org/Pages#Creating_Your_Own_Page_Templates) and assigned to a page.

= Can I use coordinates to set the marker, instead of an address? =
Yes. You can type anything into the Address field that you would type into a standard Google Maps search field, which includes coordinates. For example: 48.61322,-123.3465.

However, Google Maps is kind of picky about what coordinates they accept, and the plugin has no control over that. If it won't accept your coordinates, try using <a href="http://www.itouchmap.com/latlong.html" title="Latitude and Longitude of a Point">this page</a> to find the coordinates that Maps will accept. If it still won't accept those coordinates, you're out of luck.

= Can I change the default marker icon? =
Yes, if you want to replace the icon for all markers you can add this to your theme's functions.php:

`
function setBGMPDefaultIcon( $iconURL )
{
	return get_bloginfo( 'stylesheet_directory' ) . '/images/bgmp-default-icon.png';
}
add_filter( 'bgmp_default-icon', 'setBGMPDefaultIcon' );
`

If you only want to replace the default marker under certain conditions (e.g., when the marker is assigned to a specific category), then you can using something like this:

`
function setBGMPDefaultIcon( $iconURL, $placemarkID )
{
	if( $placemarkID == 352 ) // change this to be whatever condition you want
		$iconURL = get_bloginfo( 'stylesheet_directory' ) . '/images/bgmp-default-icon.png';
		
	return $iconURL;
}
add_filter( 'bgmp_default-icon', 'setBGMPDefaultIcon', 10, 2 );
`

The string you return needs to be the full URL to the new icon.

= Are there any hooks I can use to modify or extend the plugin? =
Yes, I've tried to add filters for everything you might reasonably want. If you need a filter or action that isn't there, let me know and I'll add it to the next version.

= How can I help with the plugin's development? =
* Answer questions on [the support forum](http://wordpress.org/tags/basic-google-maps-placemarks?forum_id=10). You can click the 'Subscribe to Emails for this Tag' link to get an e-mail whenever a new post is created.
* If you find a bug, create a post on [the support forum](http://wordpress.org/tags/basic-google-maps-placemarks?forum_id=10) with as much information as possible. If you're a developer, create a patch and include a link to it in the post.
* Check the TODO.txt file for features that need to be added and submit a patch.
* Review the code for security vulnerabilities and best practices.

= Can I make a donation to support the plugin? =
I do this as a way to give back to the WordPress community, so I don't want to take any donations, but if you'd like to give something I'd encourage you to make a donation to [Doctors Without Borders](http://www.doctorswithoutborders.org).

= How can I get help when I'm having a problem? =
1. Read the Basic Usage section of [the Installation page](http://wordpress.org/extend/plugins/basic-google-maps-placemarks/installation/).
2. Read the answers on this page.
3. Check [the support forum](http://wordpress.org/tags/basic-google-maps-placemarks?forum_id=10), because there's half a chance your problem has already been answered there, and if not the answer you get will help others in the future.

If you still need help, then first follow these instructions:

1. Disable all other plugins and switch to the default theme, then check if the problem is still happening. 
2. If it isn't, then the problem may actually be with your theme or other plugins you have installed. 
3. If the problem is still happening, then start a new thread in the forum with a **detailed description** of your problem and **the URL to the map on your site**.
4. Tag the post with `basic-google-maps-placemarks` so that I get an e-mail notification. If you use the link above it'll automatically tag it for you.
5. Check the 'Notify me of follow-up posts via e-mail' box so you won't miss any replies.

I monitor the forums and respond to most requests.

= How can I send feedback that isn't of a support nature? =
If you need help with a problem, see the FAQ answer above, but if instead you'd like to send me feedback/comments/suggestions then you can use the [contact form](http://iandunn.name/contact) on my website, and I'll respond as my schedule permits. *Please **don't** use this if you're having trouble using the plugin;* use [the support forum](http://wordpress.org/tags/basic-google-maps-placemarks?forum_id=10) instead. **I only provide support using the forums, not over e-mail.**

= Can I hire you to make custom modifications to the plugin? =
Yes, please [contact me](http://iandunn.name/contact) and we can discuss the details.


== Screenshots ==
1. This is an example of how the map looks once it's been embedded into a page.
2. The Placemarks page, where you can add/edit/delete map markers.
3. A example placemark. 
4. The Categories screen.
5. The map settings.


== Changelog ==

= 1.6 =
* Added options for changing the map type, type control and navigation control.
* Added [a new filter on the default icon URL](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-categories-feature-requests).
* Changed infomation window titles from H1 to H3 because it's more semantically appropriate
* Made the default information window text black because [it wasn't visible in some themes](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-no-description-in-placemark-balloon).
* Fixed bug where [coordinates with commas instead of periods wouldn't work](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-plugin-error-bad-displays-a-map).
* Added a lot of additional filters
* Placemark descriptions are passed through wpautop() instead of nl2br() to prevent [extra line breaks](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-line-breaks-added-to-description-popup).
* Added option to track plugin version and upgrade routine
* Added labels to fields on the Settings page
* Added error message when wp_remote_get() fails in geocode()

= 1.5.1 =
* Updated readme.txt to reflect that the Wordpress version requirement is 3.1 as of BGMP 1.5.

= 1.5 =
* Added a custom taxonomy to categorize placemarks. Thanks to [Marcel Bootsman](http://nostromo.nl) for contributing code to this.
* Added support for [placemark comments](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-comments-the-placemarks).
* Fixed a [fatal error when geocoding the map center](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-that-address-couldnt-be-geocoded-fatal-error).
* Fixed a warning on 404 pages.

= 1.4 =
* Added meta box for placemark stacking order. Thanks to Jesper Löfgren for contributing code for this.
* Upgraded PHP requirement to version 5.2 in order to use filter_var().
* Moved settings from the Writing page to their own page.
* Fixed bug where [multiple shortcodes on a page would prevent detection of map shortcode when called from do_shortcode()](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-javascript-andor-css-files-arent-loaded#post-2280215).
* Fixed bug where [empty address would sometimes prevent placemarks from appearing](http://wordpress.org/support/topic/basic-google-maps-placemark-firefox-not-rendering-all-placemarks).
* Stopped trying to geocode empty addresses.
* Updated the FAQ to mention that [do_shortcode() has to be called from a registered page template that's been assiged to a page](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-javascript-andor-css-files-arent-loaded?replies=14#post-2287781).

= 1.3.2 =
* The markers are now sorted alphabetically in the [bgmp-list] shortcode
* More theme styles are overriden to prevent the Maps API infowindow scroller bug
* The View screen in the Administration Panels is now sorted alphabetically
* enqueuemessage() is now declared protected instead of public

= 1.3.1 =
* Fixes bug where [standard posts and pages would lose the 'Set Featured Image' meta box](http://wordpress.org/support/topic/featured-image-option-not-showing)

= 1.3 =
* Removed AJAX because unnecessary, slow and causing several bugs
* Removed now-unnecessary front-end-footer.php
* Fixed bug where [placemarks weren't showing up when theme didn't support post-thumbnails](http://wordpress.org/support/topic/no-placemarks-on-theme-raindrops)
* Fixed bug where non-string value passed to enqueueMessage() would cause an error
* Set loadResources() to fire on 'wp' action instead of 'the_posts' filter
* [Added title to markers](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-add-mouseover-title-to-marker)
* Enabled support for BGMP post type revisions

= 1.2.1 = 
* Fixes the [info window height bug](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-info-window-width-height)

= 1.2 =
* Fixes bug from 1.1.3 where the default options weren't set on activation
* MultiSite - Fixed [activation error](http://wordpress.org/support/topic/plugin-basic-google-maps-placemarks-call-to-undefined-function-wp_get_current_user) from relative require paths
* MultiSite - Added support for network activation, new site activation
* MultiSite - Enabled image upload button at activation
* Fixed [bugs](http://wordpress.stackexchange.com/questions/20130/custom-admin-notices-messages-ignored-during-redirects) in message handling functions
* Fixed ['active version' stats bug](http://wordpress.stackexchange.com/questions/21132/repository-reporting-incorrect-plugin-active-version-stat)
* Added notification when geocode couldn't resolve correct coordinates

= 1.1.3 = 
* CSS and JavaScript files are only loaded on pages where the map shortcode is called
* Fixed [fatal error when trying to activate on PHP 4 servers](http://wordpress.org/support/topic/fatal-error-when-activating-basic-google-maps-placemarks)
* Styles updated for twentyeleven based themes
* Switched to wrapper function for $ instead of *$ = jQuery.noConflict();*
* JavaScript functions moved inside an object literal

= 1.1.2 = 
* Settings moved to separate class
* Updated Wordpress requirement to 3.0. Listing it at 2.9 in previous versions was a mistake.

= 1.1.1 =
* JavaScript files only loaded when needed
* Fixed bug where [JavaScript files were loaded over HTTP when they should have been over HTTPS](http://iandunn.name/basic-google-maps-placemarks-plugin/)
* A few minor back-end changes

= 1.1 = 
* Addresses are automatically geocoded
* Default markers used when no featured image set
* Default settings saved to database upon activation

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.6 =
BGMP 1.6 adds options to change the map type and fixes several minor bugs.

= 1.5.1 = 
BGMP 1.5.1 increases the WordPress version requirement to 3.1.

= 1.5 =
BGMP 1.5 adds support for categorizing placemarks and creating maps on different pages that display different categories.

= 1.4 =
BGMP 1.4 adds the ability to set a stacking order for placemarks that overlap, and fixes several minor bugs.

= 1.3.2 =
BGMP 1.3.2 sorts the markers in the [bgmp-list] shortcode alphabetically, and prevents the information window scrollbar bug in more cases.

= 1.3.1 =
BGMP 1.3.1 fixes a bug where standard posts and pages would lose the 'Set Featured Image' meta box.

= 1.3 =
BGMP 1.3 loads the map/placemarks faster and contains several bug fixes.

= 1.2.1 =
BGMP 1.2.1 fixes a bug related to the marker's info window width and height.

= 1.2 = 
BGMP 1.2 adds support for WordPress MultiSite and fixes several minor bugs.

= 1.1.3 = 
BGMP 1.1.3 contains bug fixes, performance improvements and updates for WordPress 3.2 compatibility.

= 1.1.2 = 
BGMP 1.1.2 just has some minor changes on the back end and a bug fix, so if you're not having problems then there's really no reason to upgrade, other than getting rid of the annoying upgrade notice.

= 1.1.1 = 
BGMP 1.1.1 only loads the JavaScript files when needed, making the rest of the pages load faster, and also fixes a minor bugs related to HTTPS pages.

= 1.1 =
BGMP 1.1 will automatically geocode addresses for you, so you no longer have to manually lookup marker coordinates. After uploading the new files, deactivate and reactivate the plugin to populate the new address field on each Placemark based on the existing coordinates.

= 1.0 =
Initial release.