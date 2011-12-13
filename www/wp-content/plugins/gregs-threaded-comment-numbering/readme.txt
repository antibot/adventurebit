=== Greg's Threaded Comment Numbering ===
Contributors: GregMulhauser
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2799661
Tags: comments, count, numbering, threading, paging, paged comments, threaded comments, pingback, trackback, display, callback function, comments.php, greg mulhauser, comment number, comment counter, listing comments
Requires at least: 2.7
Tested up to: 3.2.1
Stable tag: 1.4.8

Numbers comments sequentially and hierarchically; handles comments which are threaded, paged and/or reversed. Coders can call the function directly.

== Description ==

This plugin numbers your comments sequentially and hierarchically, with full support for the new comment features available in WordPress 2.7 and later -- including threading, paging, and your choice of ascending or descending date order.

= New in This Version =

* Removed PluginSponsors.com code following threats that the plugin would be expelled from the plugin repository for using the code to display sponsorship messages

For more details on the threats which have removed financial support for this plugin, see [GregsPlugins.com](http://gregsplugins.com/lib/2011/11/26/automattic-bullies/). Following the initial threats, we have now been told that NO type of passive loading of an external resource for advertising purposes or sponsorship purposes will be permitted without explicit prior opt-in by the user -- i.e., no JavaScript, no iframes, no plain image loading of any kind. If you see any type of advertising of any kind in any plugin which loads any resource from any external server, you will know that this policy is not yet being fairly, uniformly and impartially applied. When it is fairly and uniformly and impartially applied, you will no longer see any type of advertising loading any external resource unless you have explicitly opted into seeing it.

For more details on what's new in the latest main release, version 1.4, please see the update announcement: [WordPress Plugins Get Performance Boost](http://gregsplugins.com/lib/2010/06/01/wordpress-plugins-performance-boost/)

= Background =

The introduction of WordPress 2.7 brought with it significant new capabilities for threading and paging comments, but these same changes in WordPress mean that well established methods for numbering comments -- like including a basic incrementing counter within your template code -- no longer do the trick. Fortunately, taking advantage of modern comment handling features like paging and threading doesn't have to mean giving up comment numbering altogether.

Coupled with a new template function for displaying comments which debuted in WordPress 2.7, Greg's Threaded Comment Numbering plugin provides accurate sequential numbering for each comment, including hierarchical numbering up to the full 10 levels supported by WordPress.

The plugin numbers comments accurately whether you choose to display them in ascending or descending date order, on multiple pages or on one long page, and with or without threading enabled. It also handles pingback and trackback numbering.

For coders, the plugin provides additional configuration options via direct calls to the function that handles the numbering.

For more information, please see the plugin's 'Instructions' tab in the WordPress admin interface.

== Installation ==

1. Unzip the plugin archive
2. Upload the entire folder `gregs-threaded-comment-numbering` to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to Settings -> Threaded Comment Numbering to configure your preferences
5. Update your template's `comments.php` or `functions.php` to incorporate numbering, as described below

= Usage =

Please see the Instructions page of the plugin settings for usage details.

= Deactivating and Uninstalling =

You can deactivate Greg's Threaded Comment Numbering plugin via the plugins administration page, and your preferences will be saved for the next time you enable it.

However, if you would like to remove the plugin completely, just disable it via the plugins administration page, then select it from the list of recently deactivated plugins and choose "Delete" from the admin menu. This will not only delete the plugin, but it will also run a special routine included with the plugin which will completely remove its preferences from the database.

= Known Issues =

The version of the Intense Debate plugin which is current as of this writing overrides themes' existing comment code, rendering it incompatible with any theme or plugin designed to use the full capabilities of the new `wp_list_comments` introduced in WordPress 2.7. This means that regardless of what changes you make to your theme's comment code (such as calling this plugin), those changes won't show up while your comments are being controlled by Intense Debate.

== Frequently Asked Questions ==

Please see the [Greg's Plugins FAQ](http://gregsplugins.com/lib/faq/).

== Screenshots ==

1. Basic threaded comment numbering configuration options
2. Hierarchical comment numbering using the default theme and the provided styling

== Upgrade Notice ==

= 1.4.8, 26 November 2011 =
* Removed PluginSponsors.com code following threats that the plugin would be expelled from the plugin repository for using the code to display sponsorship messages

== Changelog ==

= 1.4.8, 26 November 2011 =
* Removed PluginSponsors.com code following threats that the plugin would be expelled from the plugin repository for using the code to display sponsorship messages

= 1.4.7, 27 October 2011 =
* Documentation updates

= 1.4.6, 3 October 2011 =
* Minor code cleanups

= 1.4.5, 28 March 2011 =
* Bugfix: the comment number can now be returned quietly (without echo) for further use by theme code

= 1.4.4, 29 January 2011 =
* Minor code cleanup
* Testing with WP 3.1 Release Candidate 3

= 1.4.3, 20 January 2011 =
* Minor code cleanup
* Testing with WP 3.1 Release Candidate 2

= 1.4.2, 24 June 2010 =
* Better workaround for WordPress 3.0's problems initialising plugins properly under multisite

= 1.4.1, 24 June 2010 =
* Workaround for rare problem where WordPress interferes with a newly activated plugin's ability to add options when using multisite/network mode

= 1.4, 1 June 2010 =
* Major reduction in database footprint in preparation for WordPress 3.0

= 1.3.4, 20 April 2010 =
* Minor code cleanups

= 1.3.3, 6 April 2010 =
* Enhanced admin pages now support user-configurable section boxes which can be re-ordered or closed

= 1.3.2, 31 March 2010 =
* Documentation updates

= 1.3.1, 3 December 2009 =
* Whoops, previous version accidentally used method chaining and broke support for PHP4 users; that's fixed now

= 1.3, 2 December 2009 =
* Support for 'trash' comment status introduced in WordPress 2.9
* Tested with WordPress 2.9 beta 2

= 1.2.7, 10 November 2009 =
* Minor update to configuration pages
* Fully tested with 2.8.5 (no changes)

= 1.2.6, 17 August 2009 =
* Options page bugfix for users on old PHP4 installations

= 1.2.5, 12 August 2009 =
* Documentation corrected for advanced usage -- thanks to Mark
* Fully tested with 2.8.4 (no changes)

= 1.2.4, 3 August 2009 =
* Option to display comment number without `div` wrapper -- thanks to Gabriel
* Documentation tweaks
* Added support for [Plugin Sponsorship](http://pluginsponsors.com/)

= 1.2.3, 11 June 2009 =
* Fully tested with final release of WordPress 2.8

= 1.2.2, 4 June 2009 =
* Updated documentation
* Support for WordPress 2.8

= 1.2.1, 15 April 2009 =
* Fixed a minor typo which would have interfered with translations for this plugin -- thanks to Nikolay

= 1.2, 31 March 2009 =
* This version brings higher performance, several minor enhancements, and a revamped administrative interface; it is recommended for all users.

= 1.1.2, 13 February 2009 =
* In return for a slight performance hit, Greg's Threaded Comment Numbering plugin can now check explicitly whether a threaded comment has been orphaned by having its parent comment deleted -- in which case, WordPress may display the comment in the wrong order. Due to the slight decrease in performance, this feature should only be enabled if you are experiencing problems with orphaned comment ordering.

= 1.1.1, 3 February 2009 =
* Folks who didn't notice the README note to update their preferred level of hierarchical numbering will find that the code now does it for them

= 1.1, 2 February 2009 =
* New feature: increased hierarchical numbering from 2 levels to 10 levels
* New feature: 'jumble count' mode for time-ordered numbering
* Enhancement: rewritten core numbering routines for significantly improved efficiency
* Fixed: cleaned up 'path to url' text (left over from WordPress Codex) in the provided basic callback function, restoring default avatar features for users calling the basic function

= 1.0.3, 30 January 2009 =
* Fixed another bug with deep nesting -- thanks to Marina

= 1.0.2, 29 January 2009 =
* Fixed a nested comment counter bug -- thanks to Philip S

= 1.0.1, 28 January 2009 =
* Fixed directory references to accommodate the WordPress Plugins Repository's automatic choice of name for the download archive

= 1.0, 27 January 2009 =
* Initial public release

== Fine Print ==

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License version 3 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.