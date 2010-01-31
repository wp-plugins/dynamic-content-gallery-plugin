<?php
/**
* Functions for displaying Contextual Help in Settings page
*
* @copyright Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
* @package dynamic_content_gallery
* @version 3.2
*
* @info These are the functions which produce the Contextual Help
* @info in the Settings page pull-down.
*
* @since 3.2
*/


/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}


/**
* Add help to Admin Contextual Help pull-down
*
* Hooked to 'contextual_help'
*
* @uses dfcg_admin_help_content()
*
* @param string $text	Default help text
* @param string $screen Current Page hook
* @return string $text	DCG help text
* @since 3.0
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


/**
* Admin Contextual Help content
*
* Used by dfcg_admin_help()
*
* Contains actual content displayed in Contextual Help pull-down
*
* @since 3.2
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
		<p><?php _e('The gallery is populated with images which you assign to your posts or pages using a custom field called <strong>_dfcg-image</strong> in the relevant posts or pages. The description/text, displayed in the Slide Pane below the post/page title, can be set as an automatically generated excerpt from the relevant posts or pages, or entered manually using a custom field called <strong>_dfcg-desc</strong> in the relevant posts or pages.', DFCG_DOMAIN); ?></p>
		<p><?php _e('Since version 3.2 these custom fields are handled via a Dynamic Content Gallery Metabox in the Write Posts/Pages screens.', DFCG_DOMAIN); ?></p>
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
			<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/#external-link"><?php _e('How to link gallery images to external URLs', DFCG_DOMAIN); ?></a></li>
			<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/#default-images"><?php _e('How to organise your default images', DFCG_DOMAIN); ?></a></li>
		</ul>
		<p><?php _e('Note for WPMU users: Image File Management and Default images are not available in WPMU.', DFCG_DOMAIN); ?></p>
	</div>
<?php
}
?>