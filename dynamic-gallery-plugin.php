<?php
/*
Plugin Name: Dynamic Content Gallery
Plugin URI: http://www.studiograsshopper.ch/wordpress-plugins/dynamic-content-gallery-plugin-v2/
Version: 2.0 beta
Author: Ade Walker
Author URI: http://www.studiograsshopper.ch
Description: Creates a dynamic content gallery anywhere within your wordpress theme using <a href="http://smoothgallery.jondesign.net/">Smooth Gallery</a>. Inspired by the Featured Content Gallery developed by Jason Schuller. Set up the plugin options in Settings>Dynamic Content Gallery.
*/

/*  Copyright 2008  Ade WALKER  (email : info@studiograsshopper.ch)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License 2 as published by
    the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can be found here: 
    http://www.gnu.org/licenses/gpl-2.0.html
	
*/

/* Version History

	2.0 beta	- Major code rewrite and reorganisation of functions
				- Added WPMU support
				- Added RESET checkbox to reset options to defaults
				- Added Gallery CSS options in the Settings page
			
	1.0			Public Release
	
*/

/* ******************** DO NOT edit below this line! ******************** */

/* Prevent direct access to the plugin */
if (!defined('ABSPATH')) {
	exit("Sorry, you are not allowed to access this page directly.");
}


/* Internationalization functionality */
define('DFCG_DOMAIN','Dynamic_Content_Gallery');
$dfcg_text_loaded = false;

function dfcg_load_textdomain() {
	global $dfcg_text_loaded;
   	if($dfcg_text_loaded) return;

   	load_plugin_textdomain(DFCG_DOMAIN, WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)), dirname(plugin_basename(__FILE__)));
   	$dfcg_text_loaded = true;
}


/* Activate and do Registration hook */
function dfcg_activate() {
	/* Pre-2.6 compatibility to find directories */
	if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
}
register_activation_hook(__FILE__, 'dfcg_activate');


/* This is where the plugin does its stuff */
function dfcg_gallery_styles() {
    /* Set the URL to plugin's directory: */
    $dfcg_gallery_path =  WP_PLUGIN_URL."/dynamic-gallery/";

    /* The XHTML code needed in the header for gallery to work: */
	$dfcg_galleryscript = "
	<!-- Dynamic Content Gallery plugin version 1.1 beta 7  www.studiograsshopper.ch  Begin scripts -->
    <link rel=\"stylesheet\" href=\"".$dfcg_gallery_path."css/jd.gallery.css\" type=\"text/css\" media=\"screen\" charset=\"utf-8\"/>
	<script type=\"text/javascript\" src=\"".$dfcg_gallery_path."scripts/mootools.v1.11.js\"></script>
	<script type=\"text/javascript\" src=\"".$dfcg_gallery_path."scripts/jd.gallery.js\"></script>
	<!-- end dynamic content gallery scripts -->\n";
	/* Output the XHTML code for the header: */
	echo($dfcg_galleryscript);
}

/* Add the above XHTML to the header of web pages */
add_action('wp_head', 'dfcg_gallery_styles');


/* Setup the plugin and create Admin settings page */
function dfcg_setup() {
	dfcg_load_textdomain();
	if ( current_user_can('manage_options') && function_exists('add_options_page') ) {
		add_options_page('Dynamic Content Gallery Options', 'Dynamic Content Gallery', 'manage_options', 'dynamic-content-gallery.php', 'dfcg_options_page');
		add_filter( 'plugin_action_links', 'dfcg_filter_plugin_actions', 10, 2 );
		dfcg_set_gallery_options();
	}
}
add_action('admin_menu', 'dfcg_setup');


/* dfcg_filter_plugin_actions() - Adds a "Settings" action link to the plugins page */
function dfcg_filter_plugin_actions($links, $file){
	static $this_plugin;

	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if( $file == $this_plugin ){
		$settings_link = '<a href="admin.php?page=dynamic-content-gallery.php">' . __('Settings') . '</a>';
		$links = array_merge( array($settings_link), $links); // before other links
	}
	return $links;
}


/* Create the options and provide some defaults */
function dfcg_set_gallery_options() {
	// Are we in WPMU?
	if ( function_exists('wpmu_create_blog') ) {
		// Add WPMU options
		$dfcg_new_options = array(
			'cat01' => '1',
			'cat02' => '1',
			'cat03' => '1',
			'cat04' => '1',
			'cat05' => '1',
			'off01' => '1',
			'off02' => '1',
			'off03' => '1',
			'off04' => '1',
			'off05' => '1',
			'homeurl' => '',
			'imagepath' => '',
			'defimagepath' => '',
			'defimagedesc' => '',
			'gallery-width' => '460',
			'gallery-height' => '250',
			'slide-height' => '50',
			'gallery-border-thick' => '1',
			'gallery-border-colour' => '#000000',
			'slide-h2-size' => '12',
			'slide-h2-marglr' => '5',
			'slide-h2-margtb' => '2',
			'slide-h2-colour' => '#FFFFFF',
			'slide-p-size' => '11',
			'slide-p-marglr' => '5',
			'slide-p-margtb' => '2',
			'slide-p-colour' => '#FFFFFF',
			'reset' => 'false',
		);
	} else {
		// Add WP options
		$dfcg_new_options = array(
			'cat01' => '1',
			'cat02' => '1',
			'cat03' => '1',
			'cat04' => '1',
			'cat05' => '1',
			'off01' => '1',
			'off02' => '1',
			'off03' => '1',
			'off04' => '1',
			'off05' => '1',
			'homeurl' => get_option('siteurl'),
			'imagepath' => '/wp-content/uploads/custom/',
			'defimagepath' => '/wp-content/uploads/dfcgimages/',
			'defimagedesc' => '',
			'gallery-width' => '460',
			'gallery-height' => '250',
			'slide-height' => '50',
			'gallery-border-thick' => '1',
			'gallery-border-colour' => '#000000',
			'slide-h2-size' => '12',
			'slide-h2-marglr' => '5',
			'slide-h2-margtb' => '2',
			'slide-h2-colour' => '#FFFFFF',
			'slide-p-size' => '11',
			'slide-p-marglr' => '5',
			'slide-p-margtb' => '2',
			'slide-p-colour' => '#FFFFFF',
			'reset' => 'false',
		);
	
		// if old Version 1.0 options exist, which are prefixed "dfcg-", update to new system
		foreach( $dfcg_new_options as $key => $value ) {
			if( $existing = get_option( 'dfcg-' . $key ) ) {
				$dfcg_new_options[$key] = $existing;
				delete_option( 'dfcg-' . $key );
			}
		}
	}
	add_option('dfcg_plugin_settings', $dfcg_new_options );
}


/* Only for WP versions less than 2.7
Delete the options when plugin is deactivated */
function dfcg_unset_gallery_options() {
	delete_option('dfcg_plugin_settings');
}

/* Determine whether to register deactivation hook
if installed on pre 2.7 WP. */
// Are we in WP 2.7+ ?
if ( function_exists('register_uninstall_hook') ) {
     // We are in 2.7+, so do nothing
} else {
	// we're in < 2.7 so register the deactivation hook
     register_deactivation_hook(__FILE__, 'dfcg_unset_gallery_options');
}	


/* Display and handle the options page */
function dfcg_options_page(){
	// Are we in WPMU?
	if ( function_exists('wpmu_create_blog') ) {
		// Yes, load the WPMU options page
		include_once('dfcg-wpmu-ui.php');
		// No, load the WP options page
		} else { include_once('dfcg-wp-ui.php');
	}
}