<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.0 RC4
*
*	These are the functions which produce the Contextual Help
*	in the Settings page pull-down.
*
*	Called by add_filter('contextual_help', )
*
*	@since	3.0
*
*/


/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}


/** Add help to Admin Contextual Help pull-down
*
*	Hooked to contextual_help
*
*	@uses	dfcg_admin_help_content()
*
*	@since	3.0
*/
function dfcg_admin_help($text, $screen) {
	
	// Check we're only on the DCG Settings page
	if (strcmp($screen, DFCG_PAGEHOOK) == 0 ) {
		
		$text = dfcg_admin_help_content();
		return $text;
	}
	// Let the default WP Dashboard help stuff through on other Admin pages
	return $text;
}


/** Admin Contextual Help content
*
*	Used by dfcg_admin_help()
*
*	Contains actual content displayed in Contextual Help pull-down
*
*	@since	3.0
*/
function dfcg_admin_help_content() {
?>

	<div class="help-outer"><h3><?php _e('Dynamic Content Gallery - Quick Help', DFCG_DOMAIN); ?></h3>
		<p><?php _e('This Quick Help guide highlights some basic points only. Detailed guides to Documentation and Configuration can be found here:', DFCG_DOMAIN); ?></p>
		<p>
			<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/"><?php _e('Quick Start', DFCG_DOMAIN); ?></a> | 
			<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/"><?php _e('Configuration Guide', DFCG_DOMAIN); ?></a> | 
			<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/documentation/"><?php _e('Documentation', DFCG_DOMAIN); ?></a> | 
			<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/faq/"><?php _e('FAQ', DFCG_DOMAIN); ?></a> | 
			<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/error-messages/"><?php _e('Error Messages', DFCG_DOMAIN); ?></a>
		</p>
		
		<h4><?php _e('Understanding the basics', DFCG_DOMAIN); ?></h4>
		<p><?php _e('The gallery is populated with images which you assign to your posts or pages using a custom field called <strong>dfcg-image</strong> in the relevant posts or pages. You can also assign an image description, using a custom field called <strong>dfcg-desc</strong>, in the relevant posts or pages. This description is what you see in the Slide Pane below the post/page title.', DFCG_DOMAIN); ?></p>
		<p><?php _e('The Settings page <a href="#image-file">Image File Management</a> option allows you to choose the form of the URL that you enter in the dfcg-image custom field. You also select the <a href="#gallery-method">Gallery method</a> which determines how your gallery is populated, either by posts from a <a href="#one-category">single category</a>, <a href="#multi-option">a mix of categories</a>, or from <a href="#pages-method">pages</a>.', DFCG_DOMAIN); ?></p>
		<p><?php _e('You can also <a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/#default-images">create default images</a> and specify their location on your server so that, in the event a custom field image is missing from a post or page, a default image will be shown in its place.', DFCG_DOMAIN); ?></p>
		<p><?php _e('There are lots of options for the <a href="#gallery-css">Gallery CSS</a>, as well as various <a href="#gallery-js">Javascript options</a> which determine the behaviour of the gallery. There are also options for <a href="#restrict-scripts">restricting</a> the loading of the plugin\'s javascript files to reduce the impact of the plugin on page loading times. Finally, you have two choices of <a href="#gallery-js-scripts">javascript framework</a>, mootools or jquery, which should eliminate javascript conflicts with other plugins.', DFCG_DOMAIN); ?></p>
		<p><?php _e('<strong>Still a bit lost?</strong> Find out more in the Configuration Guide =>', DFCG_DOMAIN); ?></p>
		<ul>
			<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/#gallery-method-options"><?php _e('How to choose the correct options for the Gallery Method', DFCG_DOMAIN); ?></a></li>
			<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/#image-file-man-options"><?php _e('How to select appropriate Image File management preferences', DFCG_DOMAIN); ?></a></li>
			<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/#media-uploader"><?php _e('How to use the Media Uploader to get the custom field image URLs', DFCG_DOMAIN); ?></a></li>
			<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/#template-code"><?php _e('How to choose the correct theme template when adding the plugin code', DFCG_DOMAIN); ?></a></li>
			<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/#restrict-script"><?php _e('How to configure the Restrict Script loading options', DFCG_DOMAIN); ?></a></li>
			<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/#default-images"><?php _e('How to organise your default images', DFCG_DOMAIN); ?></a></li>
		</ul>
		<p><?php _e('Note for WPMU users: Image File Management and Default images are not available in WPMU.', DFCG_DOMAIN); ?></p>
	</div>
<?php
}


// TODO: NOT CURRENTLY USED
// How to assign posts/pages: box and content
function dfcg_ui_assign() {
?>
	<div id="assign" class="postbox">
		<h3><?php _e("How to assign an image and a description to each Post/Page:", DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e("Images are pulled into the gallery from custom fields created in the relevant Posts/Pages:", DFCG_DOMAIN); ?></p> 
			<ul>
				<li>Custom field <strong>dfcg-desc</strong> <?php _e("for the Description which will appear in the gallery Slide Pane. For example: ", DFCG_DOMAIN); ?> <em><?php _e("Here's our latest news!", DFCG_DOMAIN); ?></em></li>
				<li>Custom field <strong>dfcg-image</strong> <?php _e("for the image filename, including extension, with EITHER the full, partial URL, or no URL, depending on your ", DFCG_DOMAIN); ?><a href="#image-file">Image file management</a> Settings.</li>
			</ul>
		</div>
	</div>
<?php }

// TODO: NOT CURRENTLY USED
// How To: box and content
function dfcg_ui_howto() {
?>
	<div id="how-to" class="help-outer">
		<h3><?php _e('How to add the Dynamic Content Gallery to your Theme:', DFCG_DOMAIN); ?></h3>
		<div class="help-inner">
			<p><?php _e('Add this code to the relevant theme template file depending on where you want to display the Dynamic Content Gallery:', DFCG_DOMAIN); ?></p>
			<p><code>&lt;?php dynamic_content_gallery(); ?&gt;</code></p>
			<p><em><b><?php _e('If upgrading from a previous version:', DFCG_DOMAIN); ?></b> <?php _e('You may continue to use the code (shown below) in your theme file. However, it is recommended to use the new code (shown above) to ensure compatibility with future versions of the plugin.', DFCG_DOMAIN); ?></em><br /><br />
			<code>&lt;?php include (ABSPATH . '/wp-content/plugins/dynamic-content-gallery-plugin/dynamic-gallery.php'); ?&gt;</code>
			<p><em><b><?php _e('Note: Do not use either of these within the Loop.', DFCG_DOMAIN); ?></b></em></p>
		</div>
	</div>
<?php }

// TODO: NOT CURRENTLY USED
// External link: box and content
function dfcg_ui_link() {
?>
	<div id="external-link" class="help-outer">
		<h3><?php _e("How to assign an external link to a gallery image:", DFCG_DOMAIN); ?></h3>
		<div class="help-inner">
			<p><?php _e("By default, clicking on an image in the gallery takes the user to the image's Post or Page. If you wish to link a gallery image to a different resource, eg another page on your site or an external site, create the following custom field for the relevant Post/Page:", DFCG_DOMAIN); ?></p> 
			<ul>
				<li>Custom field <strong>dfcg-link</strong> <?php _e("with the full URL for the link. For example: ", DFCG_DOMAIN); ?> <em><?php _e("http://www.anothersite.com", DFCG_DOMAIN); ?></em></li>
			</ul>
			<p>This is optional and you only need this custom field if you require images to link somewhere other than the image's Post/Page.</p>
		</div>
	</div>
<?php }

// TODO: NOT CURRENTLY USED
// Create default images: box and content
// WP ONLY
function dfcg_ui_create_wp() {
?>
	<div id="default-images" class="help-outer">
		<h3><?php _e('How to name and organise your default images:', DFCG_DOMAIN); ?></h3>
		<div class="help-inner">
    		<p>A key feature of this plugin is its automatic use of default images in the event a Custom Field <strong>dfcg-image</strong> has not been created for a Post/Page. This is a useful fallback because missing images can prevent the gallery from loading properly. Therefore, it is recommended, though not complulsory, that you set up default images as described below.</p>
			<h4>Gallery Method: Multi Option and One Category</h4>
			<p>These methods use Posts and Categories to populate the gallery. In the event that a Custom Field is missing from a Post, the plugin will display a default image determined by the Category ID for that Post. Create a default image for each of the Categories specified in the <a href="#multi-option">Multi Option</a> Settings, or the Category specified in the <a href="#one-category">One Category</a> Settings, depending on which Gallery Method you have selected.</p>
			<p>In either case, default images must be named as follows: <em>XX.jpg</em>, where XX is the Category ID. For example, the default image that will be displayed for a Post in Category ID=8 must be named <em>8.jpg</em>. Once you have created your default images, and named them as per these instructions, upload them to the folder specified in the <a href="#multi-option">Multi Option</a> or <a href="#one-category">One Category</a> Settings as appropriate.</p>
			<h4>Gallery Method: Pages</h4>
			<p>In this case, only one default image is required, and you have complete freedom to name the image as you wish (any valid image extension is permitted). Upload the image to somewhere on your site and enter the full URL to the image in the <a href="#pages-method">Pages</a> Settings.</p>
		</div>
	</div>
<?php }

// TODO: NOT CURRENTLY USED
// Uploading images: box and content
// WPMU ONLY
function dfcg_ui_create_wpmu() {
?>
	<div id="upload-images" class="postbox">
		<h3><?php _e('1. Uploading your images', DFCG_DOMAIN); ?></h3>
		<div class="inside">
    		<p>Use the Media Uploader in Write Posts / Write Pages to upload your gallery images. With the Media Uploader pop-up open, select "Choose Files to Upload" and browse to your chosen image. Once the Media Uploader screen has uploaded your file and finished "crunching", copy the URL shown in the "File URL" box and paste it in to the <strong>dfcg-image</strong> custom field in the Write Post screen.</p>
		</div>
	</div>
<?php }
