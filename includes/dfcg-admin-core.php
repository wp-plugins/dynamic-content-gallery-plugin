<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.0 RC4
*
*	Core Admin Functions called by various add_filters and add_actions:
*		- Register Settings
*		- Add Settings Page
*		- Plugin action links
*		- Plugin row meta
*		- WP Version check
*		- Admin Notices for Settings reset
*		- Options handling and upgrading
*
*	@since	3.0
*
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}



/***** Admin Init *****/

/** Register Settings as per new API, 2.7+
*
*	Hooked to admin_init
*
*	@uses	dfcg_sanitise() = callback function for sanitising options
*	dfcg_plugin_settings_options 	= Options Group name
*	dfcg_plugin_settings 			= Option Name in db
*
*	@since	3.0
*/
function dfcg_options_init() {

	register_setting( 'dfcg_plugin_settings_options', 'dfcg_plugin_settings', 'dfcg_sanitise' );
}



/***** Settings Page and Plugins Page Functions *****/

/**	Create Admin settings page and populate options
*
*	@uses	dfcg_load_textdomain()
*	@uses	dfcg_options_page()
*	@uses	dfcg_set_gallery_options()
*
*	@since	1.0
*/	
function dfcg_add_page() {
	
	dfcg_load_textdomain();
	
	// check user credentials
	if ( current_user_can('manage_options') && function_exists('add_options_page') ) {
		
		// Add Settings Page
		$dfcgpage = add_options_page('Dynamic Content Gallery Options', 'Dynamic Content Gallery', 'manage_options', DFCG_FILE_HOOK, 'dfcg_options_page');
		
		// Populate plugin's options
		dfcg_set_gallery_options();
	}
	
	return $dfcgpage;
}


/**	Display the Settings page
*
*	Used by dfcg_add_page()
*
*	@since	1.0
*/	
function dfcg_options_page(){
	global $dfcg_options;
	include_once( DFCG_DIR . '/includes/dfcg-admin-ui-screen.php' );
}


/**	Display a Settings link in main Plugin page in Dashboard
*
*	Puts the Settings link in with Deactivate/Activate links in Plugins Settings page
*
*	Hooked to plugin_action_links filter
*
*	@since	1.0
*/	
function dfcg_filter_plugin_actions($links, $file){
	static $this_plugin;

	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if( $file == DFCG_FILE_NAME ) {
		$settings_link = '<a href="admin.php?page=' . DFCG_FILE_HOOK . '">' . __('Settings') . '</a>';
		$links = array_merge( array($settings_link), $links); // before other links
	}
	return $links;
}


/**	Display Plugin Meta Links in main Plugin page in Dashboard
*
*	Adds additional meta links in the plugin's info section in main Plugins Settings page
*
*	Hooked to plugin_row_meta filter, so only works for WP 2.8+
*
*	@since	3.0
*/	
function dfcg_plugin_meta($links, $file) {
 
	// $file is the main plugin filename
 
	// Check we're only adding links to this plugin
	if( $file == DFCG_FILE_NAME ) {
	
		// Create links
		$settings_link = '<a href="admin.php?page=' . DFCG_FILE_HOOK . '">' . __('Settings') . '</a>';
		$config_link = '<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/" target="_blank">' . __('Configuration Guide', DFCG_DOMAIN) . '</a>';
		$faq_link = '<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/faq/" target="_blank">' . __('FAQ', DFCG_DOMAIN) . '</a>';
		$docs_link = '<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/documentation/" target="_blank">' . __('Documentation', DFCG_DOMAIN) . '</a>';
		
		return array_merge(
			$links,
			array( $settings_link, $config_link, $faq_link, $docs_link )
			
		);
	}
 
	return $links;
}


/**	Function to do WP Version check
*
*	DCG v3.0 requires WP 2.8+ to run. This function prints a warning
*	message in the main Plugins screen and on the DCG Settings page if version is less than 2.8.
*
*	Called by add_filter('after_action_row_$plugin', )
*
*	@since	3.0
*/	
function dfcg_wp_version_check() {
	
	$dfcg_wp_valid = version_compare(get_bloginfo("version"), DFCG_WP_VERSION_REQ, '>=');
	
	$current_page = basename($_SERVER['PHP_SELF']);
	
	// Check we are on the right screen
	if( $current_page == "plugins.php" ) {
	
		if( $dfcg_wp_valid ) {
			// Do nothing
			return;
			
		} elseif( !function_exists('wpmu_create_blog') ) {
			// We're in WP
			$version_message = '<tr class="plugin-update-tr"><td class="plugin-update" colspan="3">';
			$version_message .= '<div class="update-message" style="background:#FFEBE8;border-color:#BB0000;">';
			$version_message .= __('<strong>Warning!</strong> This version of Dynamic Content Gallery requires Wordpress', DFCG_DOMAIN) . ' <strong>' . DFCG_WP_VERSION_REQ . '</strong>+ ' . __('Please upgrade Wordpress to run this plugin.', DFCG_DOMAIN);
			$version_message .= '</div></td></tr>';
			echo $version_message;
			
		} else {
			// We're in WPMU
			$version_message = '<tr class="plugin-update-tr"><td class="plugin-update" colspan="3">';
			$version_message .= '<div class="update-message" style="background:#FFEBE8;border-color:#BB0000;">';
			$version_message .= __('<strong>Warning!</strong> This version of Dynamic Content Gallery requires WPMU', DFCG_DOMAIN) . ' <strong>' . DFCG_WP_VERSION_REQ . '</strong>+ ' . __('Please contact your Site Administrator.', DFCG_DOMAIN);
			$version_message .= '</div></td></tr>';
			echo $version_message;
		}
	}
	
	// This will also show the version warning message on the DCG Settings page and at the top of the Plugins page
	// We only need to check against options-general.php because this part of the function
	// will only be run by the calling function dfcg_on_load_validation() which is only run when we're on the DCG page.
	// TODO: Would be better to match against DCG page hook though...
	if( $current_page == "options-general.php" || $current_page == "plugins.php" ) {
		
		$version_msg_start = '<div class="error"><p>';
		$version_msg_end = '</p></div>';
		
		if( $dfcg_wp_valid ) {
			// Do nothing
			return;
			
		} elseif( !function_exists('wpmu_create_blog') ) {
			// We're in WP
			$version_msg .= '<strong>' . __('Warning! This version of Dynamic Content Gallery requires Wordpress', DFCG_DOMAIN) . ' ' . DFCG_WP_VERSION_REQ . '+ ' . __('Please upgrade Wordpress to run this plugin.', DFCG_DOMAIN) . '</strong>';
			echo $version_msg_start . $version_msg . $version_msg_end;
			
		} else {
			// We're in WPMU
			$version_msg .= '<strong>' . __('Warning! This version of Dynamic Content Gallery requires WPMU', DFCG_DOMAIN) . ' ' . DFCG_WP_VERSION_REQ . '+ ' . __('Please contact your Site Administrator.', DFCG_DOMAIN) . '</strong>';
			echo $version_msg_start . $version_msg . $version_msg_end;
		}
	}
}


/**	Function to display Admin Notices
*
*	Displays Admin Notices after Settings are reset etc
*
*	Hooked to admin_notices action
*
*	@since	3.0
*/	
function dfcg_admin_notices() {
	
	global $dfcg_options;
	
	if( $dfcg_options['just-reset'] == 'true' ) {
	
		echo '<div id="message" class="updated fade" style="background-color:#ecfcde; border:1px solid #a7c886;"><p><strong>' . __('Dynamic Content Gallery Settings have been reset to default settings.', DFCG_DOMAIN) . '</strong></p></div>';

		// Reset just-reset to false and update options accordingly
		$dfcg_options['just-reset'] = 'false';
		update_option('dfcg_plugin_settings', $dfcg_options);
	}
}



/***** Options handling and upgrading *****/

/**	Function for adding default options
*	
*	Contains the latest version's default options.
*	Populates the options on first install (not upgrade) and
*	when Settings Reset is performed.
*
*	Used by the "upgrader" function dfcg_set_gallery_options().
*	
*	74 options (5 are WP only)
*
*	@since	3.0	
*/
function dfcg_default_options() {
	// Add WP/WPMU options - we'll deal with the differences in the Admin screens
	$dfcg_default_options = array(
		'populate-method' => 'one-category',					// Populate method for how the plugin works - since 2.3
		'cat-display' => '1',									// one-category: the ID of the selected category - since 2.3
		'posts-number' => '5',									// one-category: the number of posts to display - since 2.3
		'cat01' => '1',											// multi-option: the category IDs
		'cat02' => '1',											// multi-option: the category IDs
		'cat03' => '1',											// multi-option: the category IDs
		'cat04' => '1',											// multi-option: the category IDs
		'cat05' => '1',											// multi-option: the category IDs
		'cat06' => '1',											// multi-option: the category IDs
		'cat07' => '1',											// multi-option: the category IDs
		'cat08' => '1',											// multi-option: the category IDs
		'cat09' => '1',											// multi-option: the category IDs
		'off01' => '1',											// multi-option: the post select
		'off02' => '1',											// multi-option: the post select
		'off03' => '1',											// multi-option: the post select
		'off04' => '1',											// multi-option: the post select
		'off05' => '1',											// multi-option: the post select
		'off06' => '',											// multi-option: the post select
		'off07' => '',											// multi-option: the post select
		'off08' => '',											// multi-option: the post select
		'off09' => '',											// multi-option: the post select
		'pages-selected' => '',									// pages: Page ID's in comma separated list - since 2.3
		'homeurl' => get_option('home'),						// Stored, but not currently used...
		'image-url-type' => 'full',								// WP only. All methods: URL type for dfcg-images - since 2.3
		'imageurl' => '',										// WP only. All methods: URL for partial custom images
		'defimgmulti' => '',									// WP only. Multi-option: URL for default category image folder
		'defimgonecat' => '',									// WP only. One-category: URL for default category image folder
		'defimgpages' => '',									// WP only. Pages: URL for a default image
		'defimagedesc' => '',									// all methods: default description
		'gallery-width' => '460',								// all methods: CSS
		'gallery-height' => '250',								// all methods: CSS
		'slide-height' => '50',									// all methods: CSS
		'gallery-border-thick' => '0',							// all methods: CSS
		'gallery-border-colour' => '#000000',					// all methods: CSS
		'slide-h2-size' => '12',								// all methods: CSS
		'slide-h2-padtb' => '0',								// all methods: CSS
		'slide-h2-padlr' => '0',								// all methods: CSS
		'slide-h2-marglr' => '5',								// all methods: CSS
		'slide-h2-margtb' => '2',								// all methods: CSS
		'slide-h2-colour' => '#FFFFFF',							// all methods: CSS
		'slide-p-size' => '11',									// all methods: CSS
		'slide-p-padtb' => '0',									// all methods: CSS
		'slide-p-padlr' => '0',									// all methods: CSS
		'slide-p-marglr' => '5',								// all methods: CSS
		'slide-p-margtb' => '2',								// all methods: CSS
		'slide-p-colour' => '#FFFFFF',							// all methods: CSS
		'reset' => '0',											// Settings: Reset options state
		'mootools' => '0',										// Settings: Toggle on/off Mootools loading
		'limit-scripts' => 'homepage',							// Settings: Toggle on/off loading scripts on home page only
		'page-filename' => '',									// Settings: Specify a Page Template filename, for loading scripts
		'timed' => 'true',										// JS option
		'delay' => '9000',										// JS option
		'showCarousel' => 'true',								// JS option
		'showInfopane' => 'true',								// JS option
		'slideInfoZoneSlide' => 'true',							// JS option
		'slideInfoZoneOpacity' => '0.7',						// JS option
		'textShowCarousel' => 'Featured Articles',				// JS option
		'defaultTransition' => 'fade',							// JS option
		'errors' => '0',										// all methods: Error reporting on/off
		'posts-column' => 'true',								// all methods: Show edit posts column dfcg-image
		'pages-column' => 'true',								// all methods: Show edit pages column dfcg-image
		'posts-desc-column' => 'true',							// all methods: Show edit pages column dfcg-desc
		'pages-desc-column' => 'true',							// all methods: Show edit pages column dfcg-desc
		'just-reset' => 'false',								// all methods: Used for controlling admin_notices messages
		'scripts' => 'mootools',								// all methods: Selects js framework
		'slide-h2-weight' => 'bold',							// JS jquery only
		'slide-p-line-height' => '14',							// JS jquery only
		'slide-overlay-color' => '#000000',						// JS jquery only
		'slide-overlay-position' => 'bottom',					// JS jquery only
		'transition-speed' => '1500',							// JS jquery only
		'nav-theme' => 'light',									// JS jquery only
		'pause-on-hover' => 'true',								// JS jquery only
		'fade-panels' => 'true',								// JS jquery only
		'gallery-background' => '#000000'						// JS jquery only
	);
	
	// Return options array for use elsewhere
	return $dfcg_default_options;
}


/**	Function for upgrading options
*	
*	Loads options on admin_menu hook.
*	Includes "upgrader" routine to update existing install.
*
*	Called by dfcg_add_page() which is hooked to admin_menu
*
*	In 2.3 - "imagepath" is deprecated, replaced by "imageurl" in 2.3
*	In 2.3 - "defimagepath" is deprecated, replaced by "defimgmulti" and "defimgonecat"
*	In 2.3 - 29 orig options + 30 new options added , total now is 59
*	In RC2 - "nourl" value of "image-url-type" is deprecated
*	In RC3 - "posts-column" added
*	In RC3 - "pages-column" added
*	In RC3 - Total options is 59 + 2 = 61
*	In RC4 - "posts-desc-column" added
*	In RC4 - "pages-desc-column" added
*	In RC4 - "just-reset" added
*	In RC4 - "scripts" added
*	In RC4 - 9 jQuery options added
*	In RC4 - Total options is 61 + 13 = 74
*	In RC4 - "part" value of "image-url-type" is changed to "partial"
*
*
*	@uses 	dfcg_default_options()
*	@since	3.0	
*/
function dfcg_set_gallery_options() {
	
	// Get currently stored options
	$dfcg_existing = get_option( 'dfcg_plugin_settings' );
	
	// Get current version number
	$dfcg_prev_version = get_option('dfcg_version');
	
	
	// Existing version is same as this version
	if( $dfcg_prev_version == DFCG_VER ) {
		// Nothing to do here...
		return;
	
	
	// We're upgrading from 3.0 RC4
	} elseif( $dfcg_existing && $dfcg_prev_version == '3.0 RC4' ) {
	
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from 3.0 RC3
	} elseif( $dfcg_existing && $dfcg_prev_version == '3.0 RC3' ) {
		
		// 'part' changed to 'partial'
		if( $dfcg_existing['image-url-type'] == 'part' ) {
			$dfcg_existing['image-url-type'] = 'partial';
		}
		
		// Add new options added since 3.0 RC3
		$dfcg_existing['posts-desc-column'] = 'true';
		$dfcg_existing['pages-desc-column'] = 'true';
		$dfcg_existing['just-reset'] = 'false';
		$dfcg_existing['scripts'] = 'mootools';
		$dfcg_existing['slide-h2-weight'] = 'bold';							// JS jquery only
		$dfcg_existing['slide-p-line-height'] = '14';						// JS jquery only
		$dfcg_existing['slide-overlay-color'] = '#000000';					// JS jquery only
		$dfcg_existing['slide-overlay-position'] = 'bottom';				// JS jquery only
		$dfcg_existing['transition-speed'] = '1500';						// JS jquery only
		$dfcg_existing['nav-theme'] = 'light';								// JS jquery only
		$dfcg_existing['pause-on-hover'] = 'true';							// JS jquery only
		$dfcg_existing['fade-panels'] = 'true';								// JS jquery only
		$dfcg_existing['gallery-background'] = '#000000';					// JS jquery only
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $dfcg_existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	//We're upgrading from 3.0 RC2
	} elseif( $dfcg_existing && $dfcg_prev_version == '3.0 RC2' ) {
		
		// 'part' changed to 'partial'
		if( $dfcg_existing['image-url-type'] == 'part' ) {
			$dfcg_existing['image-url-type'] = 'partial';
		}
		
		// Add new options added since 3.0 RC2
		$dfcg_existing['posts-column'] = 'true';
		$dfcg_existing['pages-column'] = 'true';
		$dfcg_existing['posts-desc-column'] = 'true';
		$dfcg_existing['pages-desc-column'] = 'true';
		$dfcg_existing['just-reset'] = 'false';
		$dfcg_existing['scripts'] = 'mootools';
		$dfcg_existing['slide-h2-weight'] = 'bold';							// JS jquery only
		$dfcg_existing['slide-p-line-height'] = '14';						// JS jquery only
		$dfcg_existing['slide-overlay-color'] = '#000000';					// JS jquery only
		$dfcg_existing['slide-overlay-position'] = 'bottom';				// JS jquery only
		$dfcg_existing['transition-speed'] = '1500';						// JS jquery only
		$dfcg_existing['nav-theme'] = 'light';								// JS jquery only
		$dfcg_existing['pause-on-hover'] = 'true';							// JS jquery only
		$dfcg_existing['fade-panels'] = 'true';								// JS jquery only
		$dfcg_existing['gallery-background'] = '#000000';					// JS jquery only
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $dfcg_existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from pre-RC2 v3 version (which used version number 2.3)
	} elseif( $dfcg_existing && $dfcg_prev_version == '2.3' ) {
		
		// If NO URL exists, change it to Partial URL (NO URL is deprecated)
		if( $dfcg_existing['image-url-type'] == 'nourl' ) {
			$dfcg_existing['image-url-type'] = 'partial';
		}
		// 'part' changed to 'partial'
		if( $dfcg_existing['image-url-type'] == 'part' ) {
			$dfcg_existing['image-url-type'] = 'partial';
		}
		
		// Add new options since 2.3
		$dfcg_existing['posts-column'] = 'true';
		$dfcg_existing['pages-column'] = 'true';
		$dfcg_existing['posts-desc-column'] = 'true';
		$dfcg_existing['pages-desc-column'] = 'true';
		$dfcg_existing['just-reset'] = 'false';
		$dfcg_existing['scripts'] = 'mootools';
		$dfcg_existing['slide-h2-weight'] = 'bold';							// JS jquery only
		$dfcg_existing['slide-p-line-height'] = '14';						// JS jquery only
		$dfcg_existing['slide-overlay-color'] = '#000000';					// JS jquery only
		$dfcg_existing['slide-overlay-position'] = 'bottom';				// JS jquery only
		$dfcg_existing['transition-speed'] = '1500';						// JS jquery only
		$dfcg_existing['nav-theme'] = 'light';								// JS jquery only
		$dfcg_existing['pause-on-hover'] = 'true';							// JS jquery only
		$dfcg_existing['fade-panels'] = 'true';								// JS jquery only
		$dfcg_existing['gallery-background'] = '#000000';					// JS jquery only
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $dfcg_existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from version 2.2
	} elseif( $dfcg_existing && $dfcg_prev_version !== DFCG_VER ) {
		
		// Assign old imagepath to new imageurl
		// imagepath was the URL excluding "Home" and the custom field entry
		$dfcg_existing['imageurl'] = $dfcg_existing['homeurl'] . $dfcg_existing['imagepath'];
		
		// Assign old defimagepath to defimgmulti and defimgonecat
		$dfcg_existing['defimgmulti'] = $dfcg_existing['homeurl'] . $dfcg_existing['defimagepath'];
		$dfcg_existing['defimgonecat'] = $dfcg_existing['homeurl'] . $dfcg_existing['defimagepath'];
		
		// Remove old keys from db
		unset($dfcg_existing['imagepath']);
		unset($dfcg_existing['defimagepath']);
		
		// Add new options added since 2.2
		$dfcg_existing['populate-method'] = 'multi-option';						// Populate method for how the plugin works - since 2.3
		$dfcg_existing['cat-display'] = '1';									// one-category: the ID of the selected category - since 2.3
		$dfcg_existing['posts-number'] = '5';									// one-category: the number of posts to display - since 2.3
		$dfcg_existing['pages-selected'] = '';									// pages: Page ID's in comma separated list - since 2.3
		$dfcg_existing['image-url-type'] = 'partial';							// WP only. All methods: URL type for dfcg-images - since 2.3
		$dfcg_existing['defimgpages'] = '';										// WP only. Pages: URL for a default image
		$dfcg_existing['slide-h2-padtb'] = '0';									// all methods: CSS
		$dfcg_existing['slide-h2-padlr'] = '0';									// all methods: CSS
		$dfcg_existing['slide-p-padtb'] = '0';									// all methods: CSS
		$dfcg_existing['slide-p-padlr'] = '0';									// all methods: CSS
		$dfcg_existing['limit-scripts'] = 'homepage';							// Settings: Toggle on/off loading scripts on home page only
		$dfcg_existing['page-filename'] = '';									// Settings: Specify a Page Template filename, for loading scripts
		$dfcg_existing['timed'] = 'true';										// JS option
		$dfcg_existing['delay'] = '9000';										// JS option
		$dfcg_existing['showCarousel'] = 'true';								// JS option
		$dfcg_existing['showInfopane'] = 'true';								// JS option
		$dfcg_existing['slideInfoZoneSlide'] = 'true';							// JS option
		$dfcg_existing['slideInfoZoneOpacity'] = '0.7';							// JS option
		$dfcg_existing['textShowCarousel'] = 'Featured Articles';				// JS option
		$dfcg_existing['defaultTransition'] = 'fade';							// JS option
		$dfcg_existing['cat06'] = '1';											// multi-option: the category IDs
		$dfcg_existing['cat07'] = '1';											// multi-option: the category IDs
		$dfcg_existing['cat08'] = '1';											// multi-option: the category IDs
		$dfcg_existing['cat09'] = '1';											// multi-option: the category IDs
		$dfcg_existing['off06'] = '';											// multi-option: the post select
		$dfcg_existing['off07'] = '';											// multi-option: the post select
		$dfcg_existing['off08'] = '';											// multi-option: the post select
		$dfcg_existing['off09'] = '';											// multi-option: the post select
		$dfcg_existing['errors'] = 'true';										// all methods: Error reporting on/off
		$dfcg_existing['posts-column'] = 'true';								// all methods: Show edit posts image column
		$dfcg_existing['pages-column'] = 'true';								// all methods: Show edit pages image column
		$dfcg_existing['posts-desc-column'] = 'true';							// all methods: Show edit posts desc column
		$dfcg_existing['pages-desc-column'] = 'true';							// all methods: Show edit pages desc column
		$dfcg_existing['just-reset'] = 'false';									// all methods: Used for controlling admin_notices messages
		$dfcg_existing['scripts'] = 'mootools';									// all methods: Selects js framework
		$dfcg_existing['slide-h2-weight'] = 'bold';							// JS jquery only
		$dfcg_existing['slide-p-line-height'] = '14';						// JS jquery only
		$dfcg_existing['slide-overlay-color'] = '#000000';					// JS jquery only
		$dfcg_existing['slide-overlay-position'] = 'bottom';				// JS jquery only
		$dfcg_existing['transition-speed'] = '1500';						// JS jquery only
		$dfcg_existing['nav-theme'] = 'light';								// JS jquery only
		$dfcg_existing['pause-on-hover'] = 'true';							// JS jquery only
		$dfcg_existing['fade-panels'] = 'true';								// JS jquery only
		$dfcg_existing['gallery-background'] = '#000000';					// JS jquery only
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $dfcg_existing );
		
		// Add version no. in the db
		add_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from some unknown earlier version, and settings exist
	} elseif( $dfcg_existing ) {
		
		// Clear out the old options
		delete_option('dfcg_plugin_settings');
		
		// Add the new. User will have to redo Settings Page setup
		$dfcg_default_options = dfcg_default_options();
		add_option('dfcg_plugin_settings', $dfcg_default_options );
		
		// Add version no. in the db
		add_option('dfcg_version', DFCG_VER );
	
	
	// It's a clean install
	} else {
		
		// Add the new options
		$dfcg_default_options = dfcg_default_options();
		add_option('dfcg_plugin_settings', $dfcg_default_options );
		
		// Add version to the options db
		add_option('dfcg_version', DFCG_VER );
	}
}
