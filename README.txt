=== Dynamic Content Gallery ===

Version: 2.1
Author: Ade Walker
Author page: http://www.studiograsshopper.ch
Plugin page: http://www.studiograsshopper.ch/wordpress-plugins/dynamic-content-gallery-v2/
Tags: gallery
Requires at least: 2.5
Tested up to: 2.7 beta 2 (WP) and 2.6.3 (WPMU)
Stable tag: 2.1

Creates a dynamic gallery of images for latest and/or featured posts.


== Description==

This plugin creates a dynamic gallery of images for latest and/or featured posts using JonDesign/’s excellent SmoothGallery script.  By associating your gallery images with individual posts, using Post Custom Fields, the plugin dynamically creates the gallery from your latest and/or featured posts. Additionally, default images can be assigned to categories in the event that the necessary Post Custom Fields have not been set up. An Admin Settings page enables you to select which categories and posts are linked to the gallery images. 


== Features ==

* SmoothGallery javascript driven image gallery using mootools framework.
* Displays 5 custom images, titles and descriptions for the 5 latest posts from your choice of categories. For example, the last 5 posts from one category or the latest post from 5 categories – or any other combination in-between.
* Highly configurable.
* Valid xhtml ouput.
* Tested to be compatible with Wordpress 2.5 to 2.7 beta 1
* Compatible with Worpress Mu to 2.6.3 (note that some settings are not available when used with Wordpress Mu) 


So, what does it do?
--------------------
The Dynamic Content Gallery plugin uses post custom fields to pull in images and titles from the latest posts in your chosen categories, and displays them on your web page using the SmoothGallery rotating gallery script.  Once you have set up a few basic options you can sit back and let the plugin automatically display your dynamic gallery with up to date content.

How does it work?
-----------------
Very simply. For each of the gallery’s 5 image “slots” the plugin checks to see if, for the latest posts in your specified categories, post custom fields exist for an image filename and its description.  If so, these are displayed in the gallery.  If an image or its description has not been specified in the post custom fields, the plugin displays a default image or default description for the these posts.

To get the best out of this plugin, it is necessary to create a default image for each of the categories that will be displayed in the gallery.  These are used as "fall-backs" in the event that a post does not have the necessary custom field set up, and thereby ensures that the gallery will always display images.  (Note that this functionality is not available when used in Wordpress Mu).

There are a number of configuration options for the plugin, readily available via a Settings Page in the Dashboard.  Normally you will set these options once, then forget about them. The underlying javascript file also gives a number of further configuration options relating to how the gallery displays images, arrows, an inbuilt menu carousel and other options - for those who are not afraid to make minor edits to a javascript file.  


== Download ==

Latest version is version 2.1 (131k, ZIP file) 
 

== Installation ==

If you are installing this plugin in a Wordpress installation, follow the instructions marked WORDPRESS.
If you are installing this plugin in a Wordpress Mu installation, follow the instructions marked WPMU.

WORDPRESS: Installing for the FIRST TIME
----------------------------------------

1.Download the latest version of the plugin to your computer.
2.Extract and upload the folder "dynamic-content-gallery-plugin" and its contents to your /wp-content/plugins/ directory.  Please ensure that you do not rename any folder or filenames in the process.
3.Activate the plugin in your Dashboard via the “Plugins” menu item.

WORDPRESS: Ugrading from an older version
-----------------------------------------

If upgrading from version 1.0.0 it is recommended that you deactivate the old version, then delete the dynamic-gallery folder and its files from your /wp-content/plugins/ folder. Then follow the above instructions "WORDPRESS: Installing for the FIRST TIME". If you use the Wordpress Automatic Plugin upgrade this will be done automatically.

For those who have made extensive changes to the CSS contained in the file jd.gallery.css, it is recommended that you backup this file before deleting the old version of the plugin, as you may wish to refer to this when configuring the settings for the upgraded version of the plugin.

For those who have customised the jd.gallery.js file in their existing installation, backup this file before following the above instructions. You can then upload your backed up copy of jd.gallery.js to the /dynamic-content-gallery-plugin/js/ folder, overwriting the newly installed version of this file. This will prevent you from losing any gallery script configurations that you made previously. 

WPMU: Installing for the FIRST TIME
-----------------------------------

1.Download the latest version of the plugin to your computer.
2.Extract and upload the folder “dynamic-gallery” and its contents to your /plugins/ directory (do not install in the mu-plugins directory).  Please ensure that you do not rename any folder or filenames in the process.
3.Activate the plugin in your Dashboard via the “Plugins” menu item.


== Instructions for use ==


== Using the plugin == 

To display the dynamic gallery in your theme, add this code to your theme file wherever you want to display the gallery:

&lt;?php include (ABSPATH . ‘/wp-content/plugins/dynamic-content-gallery-plugin/dynamic-gallery.php’); ?&gt;

NOTE: The plugin folder name has changed to dynamic-content-gallery-plugin from dynamic-gallery in prior versions.

WORDPRESS ONLY:
---------------
In order to display a unique image and description for each post, create two Post Custom Fields when writing a post:

    * Key = dfcg-image with a Value = Image filename including extension eg. myImage.jpg
    * Key = dfcg-desc with a Value = Description text eg. Here's our latest news!

You must upload all such custom field images to the folder you specify in the plugin's Settings page. 

WPMU ONLY:
----------
In order to display a unique image and description for each post, create two Post Custom Fields when writing a post:

    * Key = dfcg-image with a Value = Full URL to the image including filename and extension eg. http://myblog.blogs.com/files/2008/11/myImage.jpg
    * Key = dfcg-desc with a Value = Description text eg. Here's our latest news!

Use the Media Uploader (accessed via the Add Media button in Admin > Write Post) to upload your images and to find the full URL to be used in the Post Custom field. See the Settings page for further information on how to do this. 


== Configuration and set-up ==


Further information can be found at http://www.studiograsshopper.ch/dynamic-content-gallery-configuration/

The plugin is now loaded and activated, but needs to have its configuration and set-up completed before it is fully ready to go. This takes a little work, but once done you can forget about it and get on with more important things!

A.Configuring the Settings page
B.Create and name the default images
C.Create default descriptions


A. Configuring the Options page
----------------------------
 
1. In the Dashboard, go to Settings and open the Dynamic Content Gallery Settings page.  This contains a number of options, some of which are required and some of which are optional and may be left blank.

2. WORDPRESS and WPMU: Assign Categories to each of the 5 image “slots” that will be shown in the gallery.  By using a combination of the Category ID and The Post Select field, you specify which post will be assigned to each of the 5 image “slots”. Post Select works like this: enter 1 to display the latest post, 2 to display the previous post, 3 to display the post before that, and so on.  For example, two possible schemes are:
2a. Display latest post from 5 categories: Enter a different ID in each "Category ID" field and enter “1” in each Post Select box.
2b. Display latest 5 posts from one category: Enter the same ID in each "Category ID" field and enter “1”, "2", "3", "4", "5" in the "Post Select" boxes.
2c. Or you can specify any combination of Category ID and Post Select depending on your requirements and imagination.

3. WORDPRESS ONLY: Enter the relative path to the folder which contains the images that are referenced in the post custom field Key "dfcg-image". This path should be relative to the root of your Wordpress blog. For example, if your images are stored in your Uploads folder, the relative path will be: /wp-content/uploads/. This is a required field.

4. WORDPRESS ONLY: Enter the relative path to the folder which contains the default images which will be pulled into the gallery. These default images are only used by the plugin in the event that the post does not have an image specified in the post custom field Key "dfcg-image". This path should be relative to the root of your Wordpress blog. For example, if your default images are stored in your Uploads folder, the relative path will be: /wp-content/uploads/. This is a required field.

5. WORDPRESS and WPMU: Default description.  By default the Dynamic Content Gallery plugin displays a description for each image displayed. The plugin looks for the image description in this sequence:
   1. Checks the post for a custom field with the Key of "dfcg-desc" and if this doesn't exist =>
   2. Pulls in the Category Description set up in WP Admin>Manage>Categories and if this doesn't exist =>
   3. Shows the Default Description entered in this field.
Be aware that the gallery has relatively little space in which to display this text and therefore it is recommended to keep this description short, probably less than 20 words.  This field is optional and may be left blank.

6. WORDPRESS and WPMU: Gallery size and CSS options. You may configure various CSS options here including the width and height of the gallery, the size of the slider, font sizes etc.

That’s it!  The Settings Page is now configured.  Time to create some default images (WORDPRESS only, not WPMU) and the default descriptions (WORDPRESS and WPMU).


B. Create and name the default images (WORDPRESS ONLY, not applicable to WPMU)
--------------------------------------------------------------
1. Find or create a default image for each category.  These should be the same size as the size of the gallery specified in jd.gallery.css in accordance with the layout requirements of your page.
2. Each image should be named as follows: XX.jpg where XX is the ID of the Category that you wish this image to be associated with. The plugin only recognises jpeg format with a filename extension of .jpg.
3. Upload these default images to the folder specified in the Settings page.


C. Create default descriptions (WORDPRESS and WPMU)
---------------------------------------------------
1. Go to the Manage>Categories menu in the Dashboard.
2. Enter a short Category Description for the categories whose posts will be featured in the dynamic gallery.  It is recommended that this be kept to under 20 words or so.
3. If you do not wish to use the Category descriptions, for example, they are too long or are used in a different context elsewhere in your theme, you may create a “catch-all” default description in the Settings Page. If you do this please note that this default description is not Category specific and will be displayed whenever the post custom field does not exist. 


== Frequently Asked Questions ==


There are no known issues as such, but there are some behaviours which you should be aware of.  The tips and tricks mentioned below are a good place to start in the event that you experience a problem with the plugin.

1. Javascript conflicts.  The plugin uses SmoothGallery which is built on the Mootools javascript framework.  This may conflict with other plugins which use either the same javascript framework or a conflicting one.  In the event of problems with the gallery, try deactivating, one by one, any plugins which use javascript in order to identify the conflicting plugin.

2. Known conflicts: Lightbox-2 and its derivatives.  A list of plugins which are known to conflict with teh Dynamic Content gallery can be found at http://www.studiograsshopper.ch/forum/

3. The gallery script will not run properly if it cannot find the first of the 5 images.  The plugin has been designed to prevent this from happening by using default images in the event that a post custom field image has not been specified for a post.  If you experience problems with the gallery displaying a black screen with a loading bar, but no images load, check that you have uploaded correctly named default images to the folder specified in the Settings page.

4. In order to reduce loading time it is recommended to match your image dimensions to the visible dimensions of the gallery and optimise the filesize in your image editor.

5. Javascript Configuration options.  The SmoothGallery javascript file jd.gallery.js contains a number of configuration variables which may be changed according to your needs.  I do not provide a full list here, but users comfortable with editing javascript files will find a list of variables near the top of the file jd.gallery.js.



== Troubleshooting & Support ==

This plugin is provided free of charge without warranty.  In the event you experience problems you should vists the dedicated plugin FAQ at http://www.studiograsshopper.ch/dynamic-content-gallery-faq/.

If you cannot find a solution to a problem in the FAQ visit the support page at http://www.studiograsshopper.ch/forum/.  Support is provided in my free time but every effort will be made to respond to support queries as quickly as possible.

Thanks for downloading the plugin.  Enjoy!


== Release History ==

2.1			07/11/2008	- Bug fix re path to scripts thanks to WP.org zip file naming
						convention.
						
2.0 beta	05/11/2008	Major code overhaul
						- Added WPMU support
						- Renamed and reorganised various functions
						- Added RESET checkbox to reset options to defaults

1.0.0		01/09/2008	Public release

0.9.1		26/08/2008	Activation and reactivation hooks added to code to setup some default Options on Activation and to remove Options from the WP database on deactivation. 

0.9.0		25/08/2008	Beta testing release


== Technical Notes ==

The plugin has been tested for compatibility with Wordpress 2.7 beta 2. 

Language Support: This is not yet fully implemented in version 2.0 beta but will be completed before final release of Version 2.0  


== Acknowledgements ==

I gratefully acknowledge and thank Jonathan Shemoul of JonDesigns.net, for the versatile and excellent SmoothGallery script which this plugin uses.  The Dynamic Content Gallery is inspired by the Featured Content Gallery plugin by Jason Schuller. Particular kudos to Jason for his plugin, and from whose initial work I have borrowed heavily.