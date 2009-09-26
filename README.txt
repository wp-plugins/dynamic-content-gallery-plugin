=== Dynamic Content Gallery ===

Version: 3.0 beta
Author: Ade Walker
Author page: http://www.studiograsshopper.ch
Plugin page: http://www.studiograsshopper.ch/dynamic-content-gallery/
Tags: gallery,images,posts
Requires at least: 2.5
Tested up to: 2.8.4 (WP) and 2.8.4 (WPMU)
Stable tag:

Creates a dynamic gallery of images for latest and/or featured Posts or Pages. Set up the plugin options in Settings > Dynamic Content Gallery.


== Description==

This plugin creates a dynamic gallery of images for latest and/or featured Posts or Pages using the JonDesign SmoothGallery script.  By associating your gallery images with individual Posts or Pages, using custom fields, the plugin dynamically creates the gallery from your latest and/or featured content. Additionally, default images can be used in the event that the necessary custom fields have not been created. A Dashboard Settings page gives access to a comprehensive range of options for populating the gallery and configuring its look and behaviour. 

Compatible with Wordpress Mu but with some differences in features compared with the Wordpress version.

**Key Features**
----------------
Version 3.0 introduces many new features: streamlined code, expanded Settings page to handle javascript options, and new options for image file management and populating the gallery.
* SmoothGallery javascript image gallery using mootools framework.
* A choice of 3 different methods for populating the gallery -  Multi Option, One Category or Pages.
* Up to 15 gallery images (One Category method), 9 gallery images (Multi Option), or unlimited for Pages.
* Provides for a system of default images which will be displayed in the event a custom field image has not been defined.
* Displays the Post/Page title and a user-definable description in the Slide Pane.
* Images can be linked to external URLs
* User settings for image file management, CSS and javascript options.
* Built-in configuration validation checks and error message reporting. 
* Valid xhtml output.
* WPMU compatible (with some differences in the Settings available to the user).

**Further information**
-----------------------
Comprehensive information on installing, configuring and using the plugin can be found at http://www.studiograsshopper.ch/dynamic-content-gallery/

Configuration Guide - http://www.studiograsshopper.ch/dynamic-content-gallery-configuration-guide/
Documentation - http://www.studiograsshopper.ch/dynamic-content-gallery-v3-documentation/
FAQ - http://www.studiograsshopper.ch/dynamic-content-gallery-v3-faq/
Error messages info - http://www.studiograsshopper.ch/dynamic-content-gallery-error-messages/

All support is handled at http://studiograsshopper.ch/forum. I do not have time to monitor the wordpress.org forums, therefore please post any questions on my site's forum.


== Installation ==


**Installing for the FIRST TIME**
--------------------------------------------

1. Download the latest version of the plugin to your computer.
2. Extract and upload the folder *dynamic-content-gallery-plugin* to your */wp-content/plugins/* directory. Please ensure that you do not rename any folder or filenames in the process.
3. Activate the plugin in your Dashboard via the “Plugins” menu item.
4. Go to the plugin's Settings page, and configure your settings.

Note for Wordpress Mu users:

* Install the plugin in your */plugins/* directory (do not install in the */mu-plugins/* directory).
* In order for this plugin to be visible to blog owners, the plugin has to be activated for each blog by the Site Administrator. Each blog owner can then configure the plugin's Settings page in their Admin Settings.

**Upgrading from version 2.2**
------------------------------

Either use the AUTOMATIC upgrade option, or upgrade MANUALLY following these instructions:

* Deactivate the existing Dynamic Content Gallery in Dashboard > Plugins > Installed. Do not delete the plugin via the Dashboard > Plugins menu. If you do, you will lose your existing plugin Settings.
* Using your FTP client, delete the existing *dynamic-content-gallery-plugin* folder from your */wp-content/plugins/* folder. Make a note of any manual changes you may have made to the jd.gallery.css and jd.gallery.js files before you do this, so that you can configure the new version 3 options in the Settings page.
* Download the latest version of the plugin to your computer.
* Extract and upload the folder dynamic-content-gallery-plugin to your */wp-content/plugins/* directory. Please ensure that you do not rename any folder or filenames in the process.
* Activate the plugin in your Dashboard via the “Plugins” menu item.
* Go to the plugin's Settings page, check that your existing plugin settings have been preserved, and configure the new settings available with version 3.

**Upgrading from an older version**
-----------------------------------

Pre 2.2 versions of the Dynamic Content Gallery are not compatible with version 3, due to changes to the plugin options introduced with version 2.2. Therefore follow these instructions:

* Before you do anything, go to the existing Settings page and make a note of your settings (these will be lost during the following procedure).
* Deactivate the existing Dynamic Content Gallery in Dashboard > Plugins > Installed.
* If you are using Wordpress 2.7+, delete the plugin via Dashboard > Plugins > Installed.
* If you are using on older version of Wordpress (you really should upgrade, you know), using your FTP client, delete the existing *dynamic-content-gallery-plugin* folder from your */wp-content/plugins/* folder. Make a note of any manual changes you may have made to the jd.gallery.css and jd.gallery.js files before you do this, so that you can configure the new version 3 options in the Settings page.
* Download the latest version of the plugin to your computer.
* Extract and upload the folder *dynamic-content-gallery-plugin* to your */wp-content/plugins/* directory. Please ensure that you do not rename any folder or filenames in the process.
* Activate the plugin in your Dashboard via the “Plugins” menu item.
* Go to the plugin's Settings page, and configure your settings.

Note for Wordpress Mu users:

* Install the plugin in your */plugins/* directory (do not install in the */mu-plugins/* directory).
* In order for this plugin to be visible to blog owners, the plugin has to be activated for each blog by the Site Administrator. Each blog owner can then configure the plugin's Settings page in their Admin Settings.



== Instructions for use ==


== Using the plugin == 

To display the dynamic gallery in your theme, add this code to your theme file wherever you want to display the gallery:

&lt;?php dynamic_content_gallery(); ?&gt;

**Note:** Do not use in the Loop.


== Assigning Images to Posts ==

Images are pulled into the gallery from custom fields created in the relevant Posts/Pages:

* Custom field <strong>dfcg-image</strong> for the image filename, including extension, with EITHER the full, partial URL, or no URL, depending on your Image file management Settings.
* Custom field *dfcg-desc* for the Description which will appear in the gallery Slide Pane. For example: Here's our latest news!

*Note for WPMU users*: Use the Media Uploader (accessed via the Add Media button in Dashboard > Posts > Edit) to upload your images and to find the full URL to be used in the Post Custom field. See the Settings page for further information on how to do this. This tip is good for Wordpress too - especially if using the FULL URL option in your <a href="http://www.studiograsshopper.ch/dynamic-content-gallery-v3-documentation/#faq_32">Image file management</a> Settings.



== Configuration and set-up ==

Comprehensive information on installing, configuring and using the plugin can be found at http://www.studiograsshopper.ch/dynamic-content-gallery/

Configuration Guide - http://www.studiograsshopper.ch/dynamic-content-gallery-configuration-guide/
Documentation - http://www.studiograsshopper.ch/dynamic-content-gallery-v3-documentation/
FAQ - http://www.studiograsshopper.ch/dynamic-content-gallery-v3-faq/
Error messages info - http://www.studiograsshopper.ch/dynamic-content-gallery-error-messages/

All support is handled at http://studiograsshopper.ch/forum. I do not have time to monitor the wordpress.org forums, therefore please post any questions on my site's forum.



== Frequently Asked Questions ==

**What does it do?**
------------------------
The Dynamic Content Gallery plugin uses custom fields to pull in images and titles from user-definable Posts or Pages, and displays them on your web page using the SmoothGallery rotating gallery script.  The Settings page provides comprehensive options for configuring the choice of Posts or Pages, styling the gallery, and configuring the behaviour of the gallery.

**How does it work?**
---------------------
The plugin provides three ways to populate the gallery:
* Multi Option: user-definable combination of categories and Posts to display up to 9 images
* One Category: display up to 15 images from one selected category
* Pages: features Pages rather than Posts in the gallery

Image file management settings provide comprehensive options for how custom field images are referenced, either by Full URL, partial URL or no URL.

Default images can be defined for each category (One Category and Multi Option display methods), which are used as "fall-backs" in the event that a Post does not have the necessary custom field set up, and thereby ensures that the gallery will always display images.  (Note that this functionality is not available when used in Wordpress Mu).

There are a wide range of CSS and javascript Settings for configuring the look and behaviour of the gallery.


**Download**
------------

Latest stable version is version 3.0 available from http://wordpress.org/extend/plugins/dynamic-content-gallery-plugin/ 


**Support**
-----------

This plugin is provided free of charge without warranty.  In the event you experience problems you should visit these resources:

Dynamic Content Gallery home page - http://www.studiograsshopper.ch/dynamic-content-gallery/
Configuration Guide - http://www.studiograsshopper.ch/dynamic-content-gallery-configuration-guide/
Documentation - http://www.studiograsshopper.ch/dynamic-content-gallery-v3-documentation/
FAQ - http://www.studiograsshopper.ch/dynamic-content-gallery-v3-faq/
Error messages info - http://www.studiograsshopper.ch/dynamic-content-gallery-error-messages/

If, having referred to the above resources, you still need assistance, visit the support page at http://www.studiograsshopper.ch/forum/.  Support is provided in my free time but every effort will be made to respond to support queries as quickly as possible. I do not have time to monitor the wordpress.org forums, therefore please post any questions on my site's forum.

Thanks for downloading the plugin.  Enjoy!


**Troubleshooting**
-------------------

In the event of problems with the plugin, refer to the Resources listed above.

Use the in-built Error messages (printed to the page source as XHTML comments) for information about configuration errors and guidance on how to fix them.


**Known Issues**
----------------

There are no known issues as such, but there are some behaviours which you should be aware of.  The tips mentioned below are a good place to start in the event that you experience a problem with the plugin.

1. Javascript conflicts.  The plugin uses SmoothGallery which is built on the Mootools javascript framework.  This framework may conflict with other plugins which use either the same javascript framework or a conflicting one.  In the event of problems with the gallery, try deactivating, one by one, any other plugins which use javascript in order to identify the conflicting plugin.

2. Known conflicts: A list of plugins which are known to conflict with the Dynamic Content gallery can be found at http://www.studiograsshopper.ch/forum/

3. The gallery script will not run properly if it cannot find the first image in the gallery.

4. The gallery script requires a minimum of 2 images.

5. In order to reduce loading time it is recommended to match your image dimensions to the visible dimensions of the gallery and optimise the filesize in your image editor.

If you find any bugs, or have suggestions for future features, please leave a message on the <a href="http://www.studiograsshopper.ch/forum" title="Support forum">Support Forum</a>.




== Technical Notes ==

* The plugin is coded so that it automatically detects whether it has been installed on a Wordpress or Wordpress Mu system.  
* Language Support: This is not yet fully implemented in version 3.0 but is scheduled for a future release.  



== Acknowledgements ==

Grateful acknowledgements and thanks to Jonathan Shemoul of JonDesigns.net, for the versatile and excellent SmoothGallery script which this plugin uses.  The Dynamic Content Gallery is inspired by the Featured Content Gallery plugin by Jason Schuller. Particular kudos to Jason for his plugin, and from whose initial work I have borrowed heavily.


== Changelog ==

= 3.0 =
* Released
* Feature: Added external link capability using dfcg-link custom field
* Feature: Added form validation + reminder messages to Settings page
* Feature: Added Error messages to help users troubleshoot setup problems
* Feature: Re-designed layout of Settings page, added Category selection dropdowns etc
* Feature: New Javascript gallery options added to Settings page and main js file now migrated to PHP in order to allow better interaction with Settings. (jQuery handles this SO much better than Mootools).
* Feature: Added "populate-method" Settings. User can now pick between old way,	one category only, or Pages.
* Feature: Added Settings for limiting loading of scripts into head. New function to handle this.
* Feature: Added Full, Partial, No URL Settings to simplify location of images and be more suitable for "unusual" WP setups.
* Feature: Added Padding Settings for Info Pane Heading and Description
* Bug fix: Complete re-write of dynamic-gallery.php, more efficient coding
* Bug fix: Changed $options variable name to $dfcg_options to avoid conflicts with other plugins.
* Bug fix: Moved galleryStart() js function to HEAD within dfcg_header_scripts()

= 2.2 =
* Released 05/12/2008
* Feature: Added template tag function for theme files
* Feature: Added "disable mootools" checkbox in Settings to avoid js framework	being loaded twice if another plugin uses mootools.
* Bug fix: Changed handling of WP constants - now works as intended
* Bug fix: Removed activation_hook, not needed
* Feature: Changed options page CSS to better match with 2.7 look
* Bug fix: Fixed loading flicker with CSS change => dynamic-gallery.php
* Bug fix: Fixed error if selected post doesn't exist => dynamic-gallery.php
* Bug fix: Fixed XHTML validation error with user-defined styles/CSS moved to head with new file dfcg-user-styles.php for the output of user definable CSS

= 2.1 =
* Released 07/11/2008
* Bug fix: Issue with path to scripts due to WP.org zip file naming convention.

= 2.0 beta =
* Released 05/11/2008			
* Feature: Major code rewrite and reorganisation of functions
* Feature: Added WPMU support
* Feature: Added RESET checkbox to reset options to defaults
* Feature: Added Gallery CSS options in the Settings page

= 1.0.0 =
* Public release 01/09/2008

= 0.9.1 =
* Released 26/08/2008
* Activation and reactivation hooks added to code to setup some default Options on Activation and to remove Options from the WP database on deactivation. 

= 0.9.0 =
* Beta testing release 25/08/2008