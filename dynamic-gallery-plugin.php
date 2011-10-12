<?php
/*
Plugin Name: Dynamic Content Gallery
Plugin URI: http://www.studiograsshopper.ch/dynamic-content-gallery/
Version: 4.0
Author: Ade Walker, Studiograsshopper
Author URI: http://www.studiograsshopper.ch
Description: Creates a dynamic gallery of images for latest or featured content selected from one or more normal post categories, pages, Custom Post Type posts, or a mix of these. Configurable options and choice of using mootools or jquery to display the gallery. Compatible with Network-enabled (Multisite) Wordpress. Requires WP version 3.0+.
*/


/***** Copyright 2008-2011  Ade WALKER  (email : info@studiograsshopper.ch) *****/


/***** License information *****
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


/***** About Version History info *****
Bug fix:	means that something was broken and has been fixed
Enhance:	means code has been improved either for better optimisation, code organisation, compatibility with wider use cases
Feature:	means new user functionality has been added
*/


/***** Version History *****

= 4.0 =
* Feature:	Added carouselMinimizedOpacity option for mootools = carousel label minimised opacity
* Feature:	Added dfcg_before and dfcg_after hooks to dynamic_content_gallery() output
* Feature:	Added dfcg_widget_before and dfcg_widget_after hooks to DCG Widget
* Feature:	Added option to use Post Excerpt in Slide Pane descriptions
* Feature:	Added manual link title attr for external links in DCG Metabox
* Feature: 	Added Featured Image column in posts/pages Edit screen
* Feature:	Added option to append Read More link to manual descriptions
* Enhance:	V3.2 postmeta upgrade functionality has been removed completely
* Enhance:	New global $dfcg_utilities db option added, used for misc admin stuff
* Enhance:	Added dfcg_metabox_notices() hooked to Admin Notices to validate DCG Metabox input
* Enhance:	Removed inline styles from dfcg-admin-metaboxes.php to match WP3.2 default admin styles
* Enhance:	Image link title attribute uses post/page title or external link title, for accessibility
* Enhance:	Changed Post Thumbnail support error message from error to warning
* Enhance:	Gallery constructor functions now return output rather than echo
* Enhance:	Added classes to <p> tags in slide pane descriptions
* Enhance:	Added class="dfcg-desc-auto" to dfcg_content_limit() function output
* Enhance:	dfcg_set_gallery_options() completely re-written
* Enhance:	UI JS moved to new dfcg-ui-admin.js file
* Enhance:	dfcg-admin-ui-js.php and contents deprecated
* Enhance:	Now properly using wp_enqueue_script and wp_enqueue_style for loading admin JS and CSS
* Enhance:	Added DCG upgrade nag to DCG Settings page
* Enhance:	Added new helper functions for admin messages - dfcg_version_messages() and dfcg_post_thumbnail_messages()
* Enhance:	dfcg_admin_notice_reset() deprecated, replaced by dfcg_settings_reset()
* Enhance:	dfcg_wp_version_check() deprecated, replaced by dfcg_checks()
* Enhance:	dfcg_admin_notices() added - deals with version/post thumbnail messages
* Enhance:	Settings page UI improved - sliding panels show/hide depending on selected options etc
* Enhance:	Rewritten Help tab content in Settings page
* Enhance:	Added new file dcg-common-core.php for dfcg_baseimgurl() and dfcg_postmeta_info() functions
* Enhance:	Settings page > General Tab, Key Settings output improved
* Enhance:	Renamed the filters in dfcg_get_the_content_limit() function (added dfcg_ prefix)
* Enhance:	Removed references to *load_textdomain* in PHP comments - to prevent Codestyling Local. plugin reporting an error!!!!
* Enhance:	Added DFCG_LIB_URL constant
* Enhance:	Added DFCG_LIB_DIR constant
* Enhance:	DFCG_DOMAIN constant now defined as dynamic_content_gallery
* Enhance:	Added DFCG_LANG_DIR constant for location of plugin's languages folder
* Enhance:	Added DFCG_HOME constant
* Enhance:	Added DFCG_NAME constant
* Enhance:	File/folder structure reorganised - all folders now in 'lib' folder
* Enhance:	All file prefixes cahnged to dcg- from dfcg-
* Enhance:	dfcg-gallery-constructors-jq.php renamed dcg-constructors-jq-smooth.php
* Enhance:	dfcg-gallery-constructors.php renamed dcg-constructors-mootools.php
* Enhance:	dfcg-gallery-constructors.php renamed dcg-constructors-mootools.php
* Enhance:	dcg-gallery-constructors-jq-smooth.php renamed dcg-constructors-jq-smooth.php
* Enhance:	WPMS now tested with is_multisite() rather than function_exists('wpmu_create_blog')
* Enhance:	Script/styles enqueuing is reworked and now always uses wp_enqueue_script() and wp_enqueue_style()
* Bug fix:	Removed deprecated -moz-opacity CSS from jdgallery.css
* Bug fix:	DCG Metabox now appears on all CPT edit screens when ID Method is selected
* Bug fix:	Fixed minor XHTML validation errors in Settings page (id's, inline styles, etc)
* Bug fix:	Fixed PHP warnings in dfcg-widget.php
				
= 3.3.5 =
* Bug fix:	Fixes HTML markup error in dfcg-admin-metaboxes.php (missing </em> tag in External Link block)

= 3.3.4 =
* Feature:	Gallery background colour option added
* Feature:	on/off option for Slide Pane animation added to jQuery script (v2.6)
* Enhance:	Tidied up DCG Metabox markup and contents
* Bug fix:	jQuery script conflict with Adblock browser add-on fixed (v2.7)
* Bug fix:	jQuery script vertical image alignment with small images fixed (v2.7.5)
* Bug fix:	Fixed PHP warning re undefined $cat_selected variable in dfcg-gallery-constructors-jq-smooth.php
				
= 3.3.3 =
* Bug fix:	Upgraded jQuery script to v2.5 to fix IE img alignment, and non-linking img when showArrows is off
* Bug fix:	Added z-index:1; to #dfcg-fullsize selector in dfcg-gallery-jquery-smooth-styles.php
* Bug fix:	Fixed slide pane padding issue in #dfcg-text selector in dfcg-gallery-jquery-smooth-styles.php
* Bug fix:	Fixed IE img link disappearing. Changed CSS in #dfcg-imglink in dfcg-gallery-jquery-smooth-styles.php
	
= 3.3.2 =
* Feature:	Added showArrows checkbox for mootools and jQuery, navigation arrows now optional from within Settings
* Bug fix:	Fixed URL error to loading-bar-black.gif 
* Bug fix:	Fixed Slide Pane options errors / hidden fields in dfcg-admin-ui-functions.php
	
= 3.3.1 =
* Bug fix:	Fixed options handling of new 3.3 options in dfcg-admin-core.php and dfcg-admin-ui-screen.php
	
= 3.3 =
* Feature:	Support for Custom Post Types added
* Feature:	New Auto Image Management option - pulls in Post/Page Image Attachment
* Feature:	Carousel thumbnails now generated using WP Post Thumbnails feature
* Feature:	New jQuery script, replaces galleryview script. Plays nicer with jQuery 1.4.2 used by WP3.0
* Feature:	Gallery images and thumbnails can now be automatically populated by post image attachments
* Feature:	Mootools js updated to use Mootools 1.2.4
* Enhance:	Constructor functions cleaned up and improved
* Enhance:	Pages method now called ID Method (as both Post and Page ID's can be specified)
* Enhance:	dfcg_pages_method_gallery() renamed to dfcg_id_method_gallery()
* Enhance:	dfcg_jq_pages_method_gallery() renamed to dfcg_jq_id_method_gallery()
* Enhance:	DCG Metabox visible in both Write Posts and Write Pages, if ID Method is selected
* Enhance:	New tabbed interface for the DCG Settings Page
* Enhance:	Tooltips added to DCG Settings Page to declutter the interface
* Enhance:	Contextual help now moved to DCG Settings Page Help tab. dfcg-admin-ui-help.php deprecated.
* Enhance:	Cleaned up interface text strings, re-worded some strings to make info more understandable
* Bug fix:	Removed unnecessary noConflict() call in dfcg_jquery_scripts() function
* Bug fix:	Fixed html entities encoding for alt attribute in ID Method contructors (formerly Pages method). Props: Joe Veler.
	
= 3.2.3 =
* Bug fix:	Fixes contextual help compatibility issue with WP3.0
	
= 3.2.2 =
* Feature:	DCG Widget added
* Enhance:	Updated dfcg_ui_1_image_wp() info re DCG Metabox
* Enhance:	Updated dfcg_ui_multi_wp() info re DCG Metabox
* Enhance:	Updated dfcg_ui_onecat_wp() info re DCG Metabox
* Enhance:	Updated dfcg_ui_pages_wp() info re DCG Metabox
* Enhance:	Updated dfcg_ui_defdesc() info re DCG Metabox
* Enhance:	Updated dfcg_ui_columns() info re DCG Metabox
* Enhance:	Updated dfcg_ui_create_wpmu() info re DCG Metabox
* Enhance:	Updated contextual help text in dfcg_admin_help_content() re DCG Metabox
* Enhance:	Updated Error Message text in dfcg_errors() re DCG Metabox
* Bug fix:	Added conditional tags to add_action, add_filter hooks in main plugin file
	
= 3.2.1 =
* Bug fix:	Fixed PHP warning on undefined index when _dfcg-exclude is unchecked
* Bug fix:	Fixed missing arg error in dfcg_add_metabox() (in dfcg-admin-metaboxes.php)
* Bug fix:	Fixed metabox error of extra http:// when using Partial URL settings (dfcg-admin-metaboxes.php)
* Bug fix:	Added sanitisation routine to dfcg_save_metabox_data() to remove leading slash when using Partial URL setting
* Bug fix: 	Increased sanitisation cat01 etc char limit to 6 chars to avoid problems with large cat IDs
	
= 3.2 =
* Feature:	Added custom sort order option for Pages Method using _dfcg-sort custom field, with on/off option in Settings
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
* Bug fix:	Corrected path error for .mo files in load_plugin_textdomain in plugin main file
* Bug fix:	Fixed Settings Page Donate broken link
* Bug fix:	Increased sanitisation cat-display limit to 4 characters
* Bug fix:	Increased sanitisation Carousel text limit to 50 characters
* Bug fix:	Removed unneeded call to dfcg_load_textdomain() in dfcg_add_to_options_menu()
* Bug fix:	Mootools jd.gallery.js - increased thumbIdleOpacity to 0.4 for improved carousel visuals in IE
	
= 3.1 =
* Feature:	Added auto Description using custom $content excerpt + 7 options
* Enhance:	dfcg_baseimgurl() moved to dfcg-gallery-core.php, and added conditional check on loading jq or mootools constructors
* Enhance:	Tidied up Settings text for easier gettext translation
* Enhance:	Tidied up Settings page CSS
* Bug fix:	Fixed "Key Settings" display error when Restrict Scripts is set to Home page only ("home" was used incorrectly instead of "homepage").
* Bug fix:	Fixed whitelist option error for WPMU in dfcg-admin-ui-sanitise.php
* Bug fix:	Category default images folder can now be outside wp-content folder
	
= 3.0 =
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
* Feature:	Added template tag function for theme files
* Feature:	Added "disable mootools" checkbox in Settings to avoid js framework	being loaded twice if another plugin uses mootools.
* Feature:	Changed options page CSS to better match with 2.7 look
* Bug fix:	Changed handling of WP constants - now works as intended
* Bug fix:	Removed activation_hook, not needed
* Bug fix:	Fixed loading flicker with CSS change => dynamic-gallery.php
* Bug fix:	Fixed error if selected post doesn't exist => dynamic-gallery.php
* Bug fix:	Fixed XHTML validation error with user-defined styles/CSS moved to head with new file dfcg-user-styles.php for the output of user definable CSS
	
= 2.1 =
* Bug fix:	Issue with path to scripts thanks to WP.org zip file naming convention
				
= 2.0 beta =
* Feature:	Major code rewrite and reorganisation of functions
* Feature:	Added WPMU support
* Feature:	Added RESET checkbox to reset options to defaults
* Feature:	Added Gallery CSS options in the Settings page
			
= 1.0 =
* Public Release

See README.txt file for release dates
	
*/

/* ******************** DO NOT edit below this line! ******************** */

/* Prevent direct access to the plugin */
if( !defined( 'ABSPATH' ) ) {
	exit( __( 'Sorry, you are not allowed to access this page directly.' ) );
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
define( 'DFCG_URL', 			WP_PLUGIN_URL . '/dynamic-content-gallery-plugin' );
define( 'DFCG_DIR', 			WP_PLUGIN_DIR . '/dynamic-content-gallery-plugin' );
define( 'DFCG_LANG_DIR', 		'/dynamic-content-gallery-plugin/languages' );
define( 'DFCG_VER', 			'4.0' );
define( 'DFCG_DOMAIN', 			'dynamic_content_gallery' );
define( 'DFCG_NAME', 			'Dynamic Content Gallery' );
define( 'DFCG_WP_VERSION_REQ', 	'4.0' );
define( 'DFCG_FILE_NAME', 		'dynamic-content-gallery-plugin/dynamic-gallery-plugin.php' );
define( 'DFCG_FILE_HOOK', 		'dynamic_content_gallery' );
define( 'DFCG_PAGEHOOK', 		'settings_page_' . DFCG_FILE_HOOK );
define( 'DFCG_LIB_URL', 		DFCG_URL . '/lib' );
define( 'DFCG_LIB_DIR', 		DFCG_DIR . '/lib' );
define( 'DFCG_ERRORIMGURL', 	DFCG_LIB_URL . '/error-img/error.jpg' );
define( 'DFCG_HOME', 			'http://www.studiograsshopper.ch/dynamic-content-gallery/');



/***** Set up variables needed throughout the plugin *****/

// Internationalisation functionality
$dfcg_text_loaded = false;

// Load plugin options
$dfcg_options = get_option( 'dfcg_plugin_settings' );
$dfcg_utilities = get_option( 'dfcg_utilities' );



/***** Load files needed for plugin to run ********************/

/**
 * Load files needed for plugin to run
 *
 * Required for both admin and gallery display
 * dcg-common-core.php
 * dcg-widget.php
 *
 * Required for gallery display			Note conditionals based on user Settings, to minimise script loading
 * dcg-gallery-core.php					Template tag, header/enqueue scripts functions
 * dcg-constructors-mootools.php		Gallery constructor functions - mootools
 * dcg-constructors-jq-smooth.php		Gallery constructor functions - jquery
 * dcg-gallery-errors.php				Browser and/or Page Source errors.
 * dcg-gallery-content-limit.php		Auto description for Slide Pane
 *
 * Required for Admin
 * dcg-admin-core.php					Main Admin Functions: add page and related functions, options handling/upgrading
 * dcg-admin-ui-functions.php			Functions for outputting Settings Page elements
 * dcg-admin-ui-validation.php			Functions for validating Settings on load and submit
 * dcg-admin-custom-columns				Adds custom columns to Edit Posts & Edit Pages screens
 * dcg-admin-ui-sanitise.php			Sanitisation callback function for register_settings
 * dcg-admin-metaboxes.php				Adds metabox to Post and Page write screen for access to hidden custom fields
 * dcg-admin-key-settings.php			Functions for Admin Key Settings display
 *
 * Files included elsewhere, within functions
 * dcg-admin-ui-screen.php					Admin, Settings page	- included by dfcg_options_page() in dcg-admin-core.php
 * dcg-gallery-jquery-smooth-styles.php		Public, CSS				- included by dfcg_jquery_css() in dcg-gallery-core.php
 * dcg-gallery-mootools-styles.php			Public, CSS				- included by dfcg_mootools_scripts() in dcg-gallery-core.php
 *
 * @deprecated dfcg-gallery-constructors-jq.php
 * @deprecated dfcg-gallery-jquery-styles.php
 * @deprecated dfcg-admin-ui-help.php
 * @deprecated dfcg-admin-postmeta-upgrade.php
 * @deprecated dfcg-admin-ui-upgrade-screen.php 
 *
 * @since 3.2
 * @updated 4.0
 */ 
require_once( DFCG_LIB_DIR . '/includes/dcg-common-core.php' );

// Front-end files
if( !is_admin() ) {
	
	include_once( DFCG_LIB_DIR . '/includes/dcg-gallery-core.php' );
	
	if( $dfcg_options['scripts'] == 'mootools' ) {
		include_once( DFCG_LIB_DIR . '/includes/dcg-constructors-mootools.php' );
	} else {
		include_once( DFCG_LIB_DIR . '/includes/dcg-constructors-jq-smooth.php' );
	}
	
	if( $dfcg_options['errors'] == 'true' ) {
		include_once( DFCG_LIB_DIR . '/includes/dcg-gallery-errors.php' );
	}
	
	if( $dfcg_options['desc-method'] !== 'none' ) {
		include_once( DFCG_LIB_DIR . '/includes/dcg-gallery-content-limit.php' );
	}
}

// Admin-only files
if( is_admin() ) {
	require_once( DFCG_LIB_DIR . '/includes/dcg-admin-core.php' );
	require_once( DFCG_LIB_DIR . '/includes/dcg-admin-ui-functions.php' );
	require_once( DFCG_LIB_DIR . '/includes/dcg-admin-ui-validation.php' );
	require_once( DFCG_LIB_DIR . '/includes/dcg-admin-custom-columns.php' );
	require_once( DFCG_LIB_DIR . '/includes/dcg-admin-ui-sanitise.php' );
	require_once( DFCG_LIB_DIR . '/includes/dcg-admin-metaboxes.php' );
	require_once( DFCG_LIB_DIR . '/includes/dcg-admin-key-settings.php' );
}

// DCG Widget
require_once( DFCG_LIB_DIR . '/includes/dcg-widget.php' );


/***** Add filters and actions ********************/

/* Front-end - Loads scripts and css where gallery is displayed */
// Functions defined in dcg-gallery-core.php
add_action( 'template_redirect', 'dfcg_scripts_css_loader' );

if( is_admin() ) {
	/* Admin - Register Settings as per new API */
	// Function defined in dcg-admin-core.php
	add_action( 'admin_init', 'dfcg_options_init' );

	/* Admin - Adds Settings page */
	// Function defined in dcg-admin-core.php
	add_action( 'admin_menu', 'dfcg_add_to_options_menu' );

	/* Admin - Adds Metaboxes to Post/Page Editor */
	// Function defined in dcg-admin-metaboxes.php
	add_action( 'admin_menu', 'dfcg_add_metabox' );

	/* Admin - Saves Metabox data in Post/Page Editor */
	// Function defined in dcg-admin-metaboxes.php
	add_action( 'save_post', 'dfcg_save_metabox_data', 1, 2 );

	/* Admin - Adds WP version warning and Post Thumbnail warning in Plugins table */
	// Function defined in dcg-admin-core.php
	add_action( 'after_plugin_row_' . DFCG_FILE_NAME, 'dfcg_checks' );

	/* Admin - Adds Upgrade nag to DCG Settings page */
	// Function defined in dcg-admin-core.php
	add_action( 'admin_notices', 'dfcg_upgrade_nag', 9 );
	
	/* Admin - Adds Admin Notice when resetting Settings */
	// Function defined in dcg-admin-core.php
	add_action( 'admin_notices', 'dfcg_settings_reset' );
	
	/* Admin - Adds WP version and Post Thumbnail warning to relevant admin pages */
	// Function defined in dcg-admin-core.php
	add_action( 'admin_notices', 'dfcg_admin_notices' );
	
	/* Admin - Adds WP version and Post Thumbnail warning to relevant admin pages */
	// Function defined in dcg-admin-core.php
	add_action( 'admin_notices', 'dfcg_metabox_notices', 20 );

	/* Admin - Adds additional links in main Plugins page */
	// Function defined in dcg-admin-core.php
	add_filter( 'plugin_row_meta', 'dfcg_plugin_meta', 10, 2 );

	/* Admin - Adds additional Settings link in main Plugin page */
	// Function defined in dcg-admin-core.php
	add_filter( 'plugin_action_links', 'dfcg_filter_plugin_actions', 10, 2 );
}

/**** Added in version 4.0 *****/


/**** Set some global variables *****/
// Can't set these until all necessary files have been included
// These functions are defined in dcg-common-core.php
$dfcg_postmeta = dfcg_postmeta_info();
$dfcg_baseimgurl = dfcg_baseimgurl();


/***** Add DCG Image Sizes *****/
// New image management introduced in version 4.0
// Creates a new image size based on the gallery width and height CSS settings
// Note that Regenerate Thumbnails, or equivalent plugin, must be run whenever these values are changed
// in the DCG Settings page.

// Set main gallery image sizes
$dfcg_main_hard = 'DCG_Main_' . $dfcg_options['gallery-width'] . 'x' . $dfcg_options['gallery-height'] . '_true';
$dfcg_main_boxr = 'DCG_Main_' . $dfcg_options['gallery-width'] . 'x' . $dfcg_options['gallery-height'] . '_false';

// Add DCG image sizes
add_image_size('DCG_Thumb_100x75_true', 100, 75, true);
add_image_size($dfcg_main_hard, $dfcg_options['gallery-width'], $dfcg_options['gallery-height'], true);
add_image_size($dfcg_main_boxr, $dfcg_options['gallery-width'], $dfcg_options['gallery-height'], false);




// New WP 3.3

// This function only adds DCG Main sizes to the array
function dfcg_filter_image_size_names_muploader( $sizes ) {
	
	global $dfcg_main_hard, $dfcg_main_boxr;
	
	$hard = str_replace('_', ' ', $dfcg_main_hard);
	$sizes[$dfcg_main_hard] = $hard;
	
	$boxr = str_replace('_', ' ', $dfcg_main_boxr);
	$sizes[$dfcg_main_boxr] = $boxr;
	
	//var_dump($sizes);
	return $sizes;
}
//add_filter( 'image_size_names_choose', 'dfcg_filter_image_size_names_muploader', 100, 1 );