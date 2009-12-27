<?php
/*
Plugin Name: Dynamic Content Gallery
Plugin URI: http://www.studiograsshopper.ch/dynamic-content-gallery/
Version: 3.1 RC1
Author: Ade Walker, Studiograsshopper
Author URI: http://www.studiograsshopper.ch
Description: Creates a dynamic gallery of images for latest or featured posts selected from one category or a mix of categories, or pages. Highly configurable options for customising the look and behaviour of the gallery, and choice of using mootools or jquery to display the gallery. Compatible with Wordpress Mu. Requires WP/WPMU version 2.8+.
*/

/*  Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)

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

	3.1			- Bug fix:	dfcg_baseimgurl() moved to dfcg-gallery-core.php, and added conditional check on loading jq or mootools constructors
				- Bug fix:	Tidied up Settings text for easier gettext translation
				- Bug fix:	Tidied up Settings page CSS
				- Bug fix:	Fixed "Key Settings" display error when Restrict Scripts is set to Home page only ("home" was used incorrectly instead of "homepage").
				- Bug fix:	Fixed whitelist option error for WPMU in dfcg-admin-ui-sanitise.php
				- Bug fix:	Category default images folder can now be outside wp-content folder
				- Feature:	Added auto Description using custom $content excerpt + 7 options
	
	3.0			- Feature:	Added alternative jQuery gallery script and new associated options
				- Bug fix:	Improved data sanitisation
				- Feature: 	Added WP version check to Plugins screen. DCG now requires WP 2.8+
				- Feature: 	Added contextual help to Settings Page
				- Feature:	Added plugin meta links to Plugins main admin page
				- Feature: 	Added external link capability using dfcg-link custom field
				- Feature:	Added form validation + reminder messages to Settings page
				- Feature: 	Added Error messages to help users troubleshoot setup problems
				- Feature: 	Re-designed layout of Settings page, added Category selection dropdowns etc
				- Feature: 	New Javascript gallery options added to Settings page
				- Feature: 	Added "populate-method" Settings. User can now pick between 3: old way (called Multi Option),
							One category, or Pages.
				- Feature: 	Added Settings for limiting loading of scripts into head. New functions to handle this.
				- Feature: 	Added Full and Partial URL Settings to simplify location of images and be
							more suitable for "unusual" WP setups.
				- Feature: 	Added Padding Settings for Slide Pane Heading and Description
				- Bug fix: 	Complete re-write of code and file organisation for more efficient coding
				- Bug fix: 	Changed $options variable name to $dfcg_options to avoid conflicts
							with other plugins.
		
	2.2			- Feature:	Added template tag function for theme files
				- Feature:	Added "disable mootools" checkbox in Settings to avoid js framework
							being loaded twice if another plugin uses mootools.
				- Bug fix:	Changed handling of WP constants - now works as intended
				- Bug fix:	Removed activation_hook, not needed
				- Feature:	Changed options page CSS to better match with 2.7 look
				- Bug fix:	Fixed loading flicker with CSS change => dynamic-gallery.php
				- Bug fix:	Fixed error if selected post doesn't exist => dynamic-gallery.php
				- Bug fix:	Fixed XHTML validation error with user-defined styles/CSS moved to head
							with new file dfcg-user-styles.php for the output of user definable CSS
	
	2.1			- Bug fix:	Issue with path to scripts thanks to WP.org zip file naming convention
				
	2.0 beta	- Feature:	Major code rewrite and reorganisation of functions
				- Feature:	Added WPMU support
				- Feature:	Added RESET checkbox to reset options to defaults
				- Feature:	Added Gallery CSS options in the Settings page
			
	1.0			- Public Release
	
*/

/* ******************** DO NOT edit below this line! ******************** */

/* Prevent direct access to the plugin */
if (!defined('ABSPATH')) {
	exit(__( "Sorry, you are not allowed to access this page directly.", DFCG_DOMAIN ));
}


/* Pre-2.6 compatibility to find directories */
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );


/* Set constants for plugin */
define( 'DFCG_URL', WP_PLUGIN_URL.'/dynamic-content-gallery-plugin' );
define( 'DFCG_DIR', WP_PLUGIN_DIR.'/dynamic-content-gallery-plugin' );
define( 'DFCG_VER', '3.1 RC1' );
define( 'DFCG_DOMAIN', 'Dynamic_Content_Gallery' );
define( 'DFCG_WP_VERSION_REQ', '2.8' );
define( 'DFCG_FILE_NAME', 'dynamic-content-gallery-plugin/dynamic-gallery-plugin.php' );
define( 'DFCG_FILE_HOOK', 'dynamic_content_gallery' );
define( 'DFCG_PAGEHOOK', 'settings_page_'.DFCG_FILE_HOOK );



/***** Set up variables needed throughout the plugin *****/

// Internationalisation functionality
$dfcg_text_loaded = false;

// Load plugin options
$dfcg_options = get_option('dfcg_plugin_settings');

// Error image
$dfcg_errorimgurl = DFCG_URL . '/error-img/error.jpg';



/***** Load files needed for plugin to run ********************/

/* 	Load files needed for plugin to run
*
*	Required for gallery display
*	dfcg-gallery-core.php				Template tag, header scripts functions
*	dfcg-gallery-constructors.php		Three gallery constructor functions - mootools
*	dfcg-gallery-constructors-jq.php	Three gallery constructor functions - jquery
*	dfcg-gallery-errors.php				Browser and/or Page Source errors.
*	dfcg-gallery-content-limit-php		Auto description for Slide Pane
*
*	Required for Admin
*	dfcg-admin-core.php				Main Admin Functions: add page and related functions, options handling/upgrading
*	dfcg-admin-ui-functions.php		Functions for outputting Settings Page elements
*	dfcg-admin-ui-validation.php	Functions for validating Settings on load and submit
*	dfcg-admin-ui-css-js.php		Settings page CSS and JS
*	dfcg-admin-ui-help.php			Functions for Settings Page contextual help
*	dfcg-admin-custom-columns		Adds custom columns to Edit Posts & Edit Pages screens
*	dfcg-admin-ui-sanitise.php		Sanitisation callback function for register_settings
*
*	@since	3.0
*/ 
// Public files
if( !is_admin() ) {
	
	include_once( DFCG_DIR . '/includes/dfcg-gallery-core.php');
	
	if( $dfcg_options['scripts'] == 'mootools' ) {
		include_once( DFCG_DIR . '/includes/dfcg-gallery-constructors.php');
	} else {
		include_once( DFCG_DIR . '/includes/dfcg-gallery-constructors-jq.php');
	}
	
	include_once( DFCG_DIR . '/includes/dfcg-gallery-errors.php');
	include_once( DFCG_DIR . '/includes/dfcg-gallery-content-limit.php');
}

// Admin-only files
if( is_admin() ) {
	require_once( DFCG_DIR . '/includes/dfcg-admin-core.php');
	require_once( DFCG_DIR . '/includes/dfcg-admin-ui-functions.php');
	require_once( DFCG_DIR . '/includes/dfcg-admin-ui-validation.php');
	require_once( DFCG_DIR . '/includes/dfcg-admin-ui-css-js.php');
	require_once( DFCG_DIR . '/includes/dfcg-admin-ui-help.php');
	require_once( DFCG_DIR . '/includes/dfcg-admin-custom-columns.php');
	require_once( DFCG_DIR . '/includes/dfcg-admin-ui-sanitise.php');
}



/***** Add filters and actions ********************/

/* Admin - Register Settings as per new API */
// Function defined in dfcg-admin-core.php
add_action('admin_init', 'dfcg_options_init' );

/* Public - Loads scripts into header where gallery is displayed */
// Function defined in dfcg-gallery-core.php
add_action('wp_head', 'dfcg_load_scripts');

/* Public - Enqueue jQuery into header where gallery is displayed */
// Function defined in dfcg-gallery-core.php
add_action('template_redirect', 'dfcg_enqueue_script');

/* Admin - Adds Settings page */
// Function defined in dfcg-admin-core.php
add_action('admin_menu', 'dfcg_add_page');

/* Admin - Contextual Help to Settings page */
// Function defined in dfcg-admin-ui-help.php
add_filter('contextual_help', 'dfcg_admin_help', 10, 2);

/* Admin - Adds WP version warning on main Plugins screen */
// Function defined in dfcg-admin-core.php
add_action('after_plugin_row_dynamic-content-gallery-plugin/dynamic-gallery-plugin.php', 'dfcg_wp_version_check');

/* Admin - Adds Admin Notices when updating Settings */
// Function defined in dfcg-admin-core.php
add_action('admin_notices', 'dfcg_admin_notices');

/* Admin - Adds additional links in main Plugins page */
// Function defined in dfcg-admin-core.php
add_filter( 'plugin_row_meta', 'dfcg_plugin_meta', 10, 2 );

/* Admin - Adds additional Settings link in main Plugin page */
// Function defined in dfcg-admin-core.php
add_filter( 'plugin_action_links', 'dfcg_filter_plugin_actions', 10, 2 );



/***** Functions used by both public and admin *****/

/**	Function to load textdomain for Internationalisation functionality
*
*	Loads textdomain if $dfcg_text_loaded is false
*
*	Called by dfcg_add_page()
*	Called by dfcg-wp-ui.php
*	@uses	variable	$dfcg_text_loaded
*
*	@since	1.0
*/
// TODO: Is this really a public function too? Probably not.
function dfcg_load_textdomain() {
	
	global $dfcg_text_loaded;
   	
	// If textdomain is already loaded, do nothing
	if( $dfcg_text_loaded ) {
   		return;
   	}
	
	// Textdomain isn't already loaded, let's load it
   	load_plugin_textdomain(DFCG_DOMAIN, WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)), dirname(plugin_basename(__FILE__)));
   	
	// Change variable to prevent loading textdomain again
	$dfcg_text_loaded = true;
}