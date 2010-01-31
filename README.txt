=== Dynamic Content Gallery ===

Version: 3.2
Author: Ade Walker
Author page: http://www.studiograsshopper.ch
Plugin page: http://www.studiograsshopper.ch/dynamic-content-gallery/
Tags: gallery,images,posts,rotator,content-slider
Requires at least: 2.8
Tested up to: 2.9.1 (WP) and 2.9.1.1 (WPMU)
Stable tag: 3.2

Creates a dynamic gallery of images for latest or featured content selected from one category, a mix of categories, or pages. Highly configurable options for customising the look and behaviour of the gallery, and choice of using mootools or jquery to display the gallery. Compatible with Wordpress Mu. Requires WP/WPMU version 2.8+.


== Description==

This plugin creates a dynamic gallery of images for latest and/or featured Posts or Pages using either the JonDesign SmoothGallery script for mootools, or the Galleryview script for jQuery.  By associating your gallery images with individual Posts or Pages, using custom fields, the plugin dynamically creates the gallery from your latest and/or featured content. Additionally, default images can be displayed in the event that the necessary custom fields have not been created. A Dashboard Settings page gives access to a comprehensive range of options for populating the gallery and configuring its look and behaviour. 

Compatible with Wordpress Mu but with some differences in features compared with the Wordpress version. Requires WP/WPMU version 2.8+.

**Key Features**
----------------
Version 3.0+ introduces many new features: streamlined code, expanded Settings page to handle javascript options, and new options for image file management and populating the gallery.

* NEW in 3.2 - New metabox panel in Write Post/Page screens for easy editing of custom fields used by the plugin.
* NEW in 3.2 - Added _dfcg-exclude postmeta to allow specific exclusion of a post from multi-option or one-category output.
* NEW in 3.2 - Added ability to apply a custom Sort Order to gallery images when using Pages Method
* Auto custom excerpt option for the Slide Pane Description - automatically pulls text from your Post/Page content
* SmoothGallery javascript image gallery using mootools framework, or an alternative jQuery script.
* A choice of 3 different methods for populating the gallery -  Multi Option, One Category or Pages.
* Up to 15 gallery images (One Category method), 9 gallery images (Multi Option), or unlimited for Pages.
* Provides for a system of default images which will be displayed in the event a custom field image has not been defined.
* Displays the Post/Page title and a user-definable description in the Slide Pane.
* Images can be linked to external URLs.
* User settings for image file management, CSS and javascript options.
* Built-in configuration validation checks and error message reporting. 
* Valid xhtml output.
* WPMU compatible (with some differences in the Settings available to the user).

**Further information**
-----------------------
Comprehensive information on installing, configuring and using the plugin can be found [here](http://www.studiograsshopper.ch/dynamic-content-gallery/)

* [Configuration Guide](http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/)
* [Documentation](http://www.studiograsshopper.ch/dynamic-content-gallery/documentation/)
* [FAQ](http://www.studiograsshopper.ch/dynamic-content-gallery/faq/)
* [Error messages info](http://www.studiograsshopper.ch/dynamic-content-gallery/error-messages/)

All support is handled at the [Studiograsshopper Forum](http://www.studiograsshopper.ch/forum/). I do not have time to monitor the wordpress.org forums, therefore please post any questions on my site's forum.


== Installation ==


**Installing for the FIRST TIME**
--------------------------------------------

1. Download the latest version of the plugin to your computer.
2. Extract and upload the folder *dynamic-content-gallery-plugin* to your */wp-content/plugins/* directory. Please ensure that you do not rename any folder or filenames in the process.
3. Activate the plugin in your Dashboard via the "Plugins" menu item.
4. Go to the plugin's Settings page, and configure your settings.

Note for Wordpress Mu users:

* Install the plugin in your */plugins/* directory (do not install in the */mu-plugins/* directory).
* In order for this plugin to be visible to blog owners, the plugin has to be activated for each blog by the Site Administrator. Each blog owner can then configure the plugin's Settings page in their Admin Settings.


**Upgrading from version 2.2, 3.0, 3.1**
----------------------------------------
Follow the upgrade instructions [here](http://www.studiograsshopper.ch/dynamic-content-gallery/documentation/#faq_43).
Version 3.2 introduces some changes to the handling of Custom Fields dfcg-desc, dfcg-image and dfcg-link, which requires existing Custom Field data to be upgraded. The first time you visit the plugin's Settings page after installing 3.2, you will be prompted to perform this Custom Field upgrade. Follow the on-screen instructions to perform this upgrade. Further information regarding this Custom Field upgrade can be found [here](http://www.studiograsshopper.ch/wordpress-plugins/dynamic-content-gallery-v3-2-released/).



**Instructions for use**
------------------------

**Using the plugin** 

To display the dynamic gallery in your theme, add this code to your theme file wherever you want to display the gallery:

`<?php dynamic_content_gallery(); ?>`

**Note:** Do not use in the Loop.


**Assigning Images to Posts**

Images are pulled into the gallery from custom fields created in the relevant Posts/Pages:

* Custom field *dfcg-image* for the image filename, including extension, with EITHER the full or partial URL depending on your Image file management Settings.

Slide Pane text can be configured in three ways - Manual, Auto or None

**Manual:**
* Use a custom field *dfcg-desc* in your Posts/Pages for the Description which will appear in the gallery Slide Pane. For example: Here's our latest news!

**Auto:**
* Select Auto option to automatically create custom Post/Page excerpts from your Post/Page content.

**None:**
* Select None option to not display any description. (Post/Page title will still display with this option)

*Note for WPMU users*: Use the Media Uploader (accessed via the Add Media button in Dashboard > Posts > Edit) to upload your images and to find the full URL to be used in the Post Custom field. See the Settings page for further information on how to do this. This tip is good for Wordpress too - especially if using the FULL URL option in your [Image file management](http://www.studiograsshopper.ch/dynamic-content-gallery/documentation/#faq_32) Settings.



== Configuration and set-up ==

Comprehensive information on installing, configuring and using the plugin can be found at http://www.studiograsshopper.ch/dynamic-content-gallery/



== Frequently Asked Questions ==

**What does it do?**
------------------------
The Dynamic Content Gallery plugin uses custom fields to pull in images and titles from user-definable Posts or Pages, and displays them on your web page using a javascript-driven rotating image gallery.  The Settings page provides comprehensive options for configuring the choice of Posts, Categories or Pages, styling the gallery, and configuring the behaviour of the gallery.

**How does it work?**
---------------------
The plugin provides three ways to populate the gallery:

* Multi Option: user-definable combination of categories and Posts to display up to 9 images
* One Category: display up to 15 images from one selected category
* Pages: features Pages rather than Posts in the gallery

Image file management settings provide comprehensive options for how custom field images are referenced, either by Full URL or Partial URL.

Default images can be defined for each category (One Category and Multi Option display methods), which are used as "fall-backs" in the event that a Post does not have the necessary custom field set up, and thereby ensures that the gallery will always display images.  (Note that this functionality is not available when used in Wordpress Mu).

There are a wide range of CSS and javascript Settings for configuring the look and behaviour of the gallery.

The plugin is supplied with the original Smoothgallery mootools script and a jQuery alternative, selectable via the plugin's Settings page.


**Download**
------------

Latest stable version is available from http://wordpress.org/extend/plugins/dynamic-content-gallery-plugin/ 


**Support**
-----------

This plugin is provided free of charge without warranty.  In the event you experience problems you should visit these resources:

* [Dynamic Content Gallery home page](http://www.studiograsshopper.ch/dynamic-content-gallery/)
* [Configuration Guide](http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/)
* [Documentation](http://www.studiograsshopper.ch/dynamic-content-gallery/documentation/)
* [FAQ](http://www.studiograsshopper.ch/dynamic-content-gallery/faq/)
* [Error messages info](http://www.studiograsshopper.ch/dynamic-content-gallery/error-messages/)

If, having referred to the above resources, you still need assistance, visit the support page at http://www.studiograsshopper.ch/forum/.  Support is provided in my free time but every effort will be made to respond to support queries as quickly as possible. I do not have time to monitor the wordpress.org forums, therefore please post any questions on my site's forum.

Thanks for downloading the plugin.  Enjoy!

If you have found the plugin useful, please consider a Donation to help me continue to invest the time and effort in maintaining and improving this plugin. Thank you!


**Troubleshooting**
-------------------

In the event of problems with the plugin, refer to the Resources listed above.

Use the in-built Error messages (printed to the page source as HTML comments) for information about configuration errors and guidance on how to fix them.


**Known Issues**
----------------

There are no known issues as such, but there are some behaviours which you should be aware of.  The tips mentioned below are a good place to start in the event that you experience a problem with the plugin.

1. Javascript conflicts.  By default the plugin uses SmoothGallery which is built on the Mootools javascript framework.  This framework may conflict with other plugins which use either the same javascript framework or a conflicting one.  In the event of problems with the gallery, and you are unable to resolve the conflict, try using the supplied jQuery script instead, which you can select in the plugin's Settings page.

2. Known conflicts: A list of plugins which are known to conflict with the mootools gallery script can be found at http://www.studiograsshopper.ch/forum/

3. The mootools gallery script will not run properly if it cannot find the first image in the gallery. It also requires a minimum of 2 images.

4. In order to reduce loading time it is recommended to match your image dimensions to the visible dimensions of the gallery and optimise the filesize in your image editor.

If you find any bugs, or have suggestions for future features, please leave a message on the [Support Forum](http://www.studiograsshopper.ch/forum/).



**Acknowledgements**

The Dynamic Content Gallery plugin uses the mootools SmoothGallery script developed by Jonathan Shemoul of JonDesigns.net, and a modified version of the jQuery Galleryview script developed by Jack Anderson, and was forked from the original Featured Content Gallery v1.0 developed by Jason Schuller. Grateful acknowledgements to Jonathan, Jack and Jason.


== Screenshots ==
1. Dynamic Content Gallery
2. Settings Page (part only)
3. Settings - At a glance settings info
4. Settings - Image File Management
5. Settings - Gallery Method
6. Settings - Gallery CSS
7. Settings - Mootools or jQuery scripts
8. Settings - Gallery javascript options
9. Settings - Restrict scripts loading
10. Settings - dfcg-image and dfcg-desc custom columns


== Changelog ==

= 3.2 =
* Released	31 January 2010
* Feature:	Added custom sort order option for Pages Method using _dfcg-sort custom field
* Feature:	Added "no description" option for the Slide Pane
* Feature:	Manual description now displays Auto description if _dfcg-desc, category description and default description don't exist
* Feature:	Added Metabox to Post/Page Editor screen to handle custom fields
* Feature:	Added _dfcg-exclude postmeta to allow specific exclusion of a post from multi-option or one-category output
* Feature:	Added postmeta upgrade routine to convert dfcg- custom fields to _dfcg-
* Enhance:	Added text-align left to h2 in jd.gallery.css for wider theme compatibility
* Enhance:	Updated inline docs
* Enhance:	$dfcg_load_textdomain() moved to dfcg-admin-core.php
* Enhance:	$dfcg_errorimgurl variable deprecated in favour of DFCG_ERRORIMGURL constant
* Enhance:	New function dfcg_query_list() for handling multi-option cat/off pairs, in dfcg-gallery-core.php
* Enhance:	Function dfcg_admin_notices() renamed to dfcg_admin_notice_reset()
* Enhance:	Tidied up Error Message markup and reorganised dfcg-gallery-errors.php, with new functions
* Enhance:	Renamed function dfcg_add_page() now dfcg_add_to_options_menu()
* Enhance:	jd.gallery.css modified to remove open.gif (looked rubbish in IE and not much better in FF)
* Enhance:	Moved Admin CSS to external stylesheet and added dfcg_loadjs_admin_head() function hooked to admin_print_scripts_$plugin
* Bug fix:	Fixed non-fatal wp_errors in dfcg-gallery-errors.php
* Bug fix:	Corrected path error for .mo files in load_textdomain() in plugin main file
* Bug fix:	Fixed Settings Page Donate broken link
* Bug fix:	Increased sanitisation cat-display limit to 4 characters
* Bug fix:	Increased sanitisation Carousel text limit to 50 characters
* Bug fix:	Removed unneeded call to dfcg_load_textdomain() in dfcg_add_to_options_menu()
* Bug fix:	Mootools jd.gallery.js - increased thumbIdleOpacity to 0.4 for improved carousel visuals in IE

= 3.1 =
* Released	28 December 2009
* Feature:	Added auto Description using custom $content excerpt + 7 options
* Enhance:	dfcg_baseimgurl() moved to dfcg-gallery-core.php, and added conditional check on loading jq or mootools constructors
* Enhance:	Tidied up Settings text for easier gettext translation
* Enhance:	Tidied up Settings page CSS
* Bug fix:	Fixed "Key Settings" display error when Restrict Scripts is set to Home page only ("home" was used incorrectly instead of "homepage").
* Bug fix:	Fixed whitelist option error for WPMU in dfcg-admin-ui-sanitise.php
* Bug fix:	Category default images folder can now be outside wp-content folder

= 3.0 =
* Released	7 December 2009
* Feature:	Added alternative jQuery gallery script and new associated options
* Feature: 	Added WP version check to Plugins screen. DCG now requires WP 2.8+
* Feature: 	Added contextual help to Settings Page
* Feature:	Added plugin meta links to Plugins main admin page
* Feature: 	Added external link capability using dfcg-link custom field
* Feature:	Added form validation + reminder messages to Settings page
* Feature: 	Added Error messages to help users troubleshoot setup problems
* Feature: 	Re-designed layout of Settings page, added Category selection dropdowns etc
* Feature: 	New Javascript gallery options added to Settings page
* Feature: 	Added "populate-method" Settings. User can now pick between 3: old way (called Multi Option), One category, or Pages.
* Feature: 	Added Settings for limiting loading of scripts into head. New functions to handle this.
* Feature: 	Added Full and Partial URL Settings to simplify location of images and be more suitable for "unusual" WP setups.
* Feature: 	Added Padding Settings for Slide Pane Heading and Description
* Bug fix: 	Complete re-write of code and file organisation for more efficient coding
* Bug fix: 	Changed $options variable name to $dfcg_options to avoid conflicts with other plugins.
* Bug fix:	Improved data sanitisation

= 2.2 =
* Released 5 December 2008
* Feature:	Added template tag function for theme files
* Feature:	Added "disable mootools" checkbox in Settings to avoid js framework	being loaded twice if another plugin uses mootools.
* Feature:	Changed options page CSS to better match with 2.7 look
* Bug fix:	Changed handling of WP constants - now works as intended
* Bug fix:	Removed activation_hook, not needed
* Bug fix:	Fixed loading flicker with CSS change => dynamic-gallery.php
* Bug fix:	Fixed error if selected post doesn't exist => dynamic-gallery.php
* Bug fix:	Fixed XHTML validation error with user-defined styles/CSS moved to head with new file dfcg-user-styles.php for the output of user definable CSS

= 2.1 =
* Released 7 November 2008
* Bug fix: Issue with path to scripts due to WP.org zip file naming convention.

= 2.0 beta =
* Released 5 November 2008			
* Feature: Major code rewrite and reorganisation of functions
* Feature: Added WPMU support
* Feature: Added RESET checkbox to reset options to defaults
* Feature: Added Gallery CSS options in the Settings page

= 1.0.0 =
* Public release 1 September 2008

= 0.9.1 =
* Released 26 August 2008
* Activation and reactivation hooks added to code to setup some default Options on Activation and to remove Options from the WP database on deactivation. 

= 0.9.0 =
* Beta testing release 25 August 2008