<?php
/**
* Admin Core functions - all the main stuff needed to run the backend
*
* @copyright Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
* @package dynamic_content_gallery
* @version 3.2.2
*
* @info Core Admin Functions called by various add_filters and add_actions:
* @info	- Internationalisation
* @info	- Register Settings
* @info	- Add Settings Page
* @info	- Plugin action links
* @info	- Plugin row meta
* @info	- WP Version check
* @info	- Admin Notices for Settings reset
* @info	- Options handling and upgrading
*
* @since 3.0
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}


/***** Internationalisation *****/

/**
* Function to load textdomain for Internationalisation functionality
*
* Loads textdomain if $dfcg_text_loaded is false
*
* Note: .mo file should be named dynamic-content-gallery-plugin-xx_XX.mo and placed in the DCG plugin's languages folder.
* xx_XX is the language code, eg fr_FR for French etc.
*
* @global $dfcg_text_loaded bool defined in dynamic-gallery-plugin.php
* @uses load_plugin_textdomain()
* @since 3.2
*/
function dfcg_load_textdomain() {
	
	global $dfcg_text_loaded;
   	
	// If textdomain is already loaded, do nothing
	if( $dfcg_text_loaded ) {
   		return;
   	}
	
	// Textdomain isn't already loaded, let's load it
   	load_plugin_textdomain(DFCG_DOMAIN, false, dirname(plugin_basename(__FILE__)). '/languages');
   	
	// Change variable to prevent loading textdomain again
	$dfcg_text_loaded = true;
}



/***** Admin Init *****/

/**
* Register Settings as per new Settings API, 2.7+
*
* Hooked to 'admin_init'
*
* dfcg_plugin_settings_options 	= Options Group name
* dfcg_plugin_settings 			= Option Name in db
*
* @uses dfcg_sanitise(), callback function for sanitising options
*
* @since 3.0
*/
function dfcg_options_init() {

	register_setting( 'dfcg_plugin_settings_options', 'dfcg_plugin_settings', 'dfcg_sanitise' );
}



/***** Settings Page and Plugins Page Functions *****/

/**
* Create Admin settings page and populate options
*
* Hooked to 'admin_menu'
*
* Renamed in 3.2, was dfcg_add_page()
* No need to check credentials - already built in to core wp function
*
* @uses	dfcg_options_page()
* @uses dfcg_loadjs_admin_head()
* @uses	dfcg_set_gallery_options()
*
* @return string $dfcg_page_hook, wp page hook
* @since 3.2
*/
function dfcg_add_to_options_menu() {
	
	// Add Settings Page
	$dfcg_page_hook = add_options_page('Dynamic Content Gallery Options', 'Dynamic Content Gallery', 'manage_options', DFCG_FILE_HOOK, 'dfcg_options_page');
	
	// Load Admin external scripts and CSS
	add_action( 'admin_print_scripts-settings_page_' . DFCG_FILE_HOOK, 'dfcg_loadjs_admin_head' );
	
	// Populate plugin's options
	dfcg_set_gallery_options();
	
	return $dfcg_page_hook; // May need this later
}


/**
* Function to load Admin CSS file
*
* Hooked to 'admin_print_scripts-settings_page_' in dfcg_add_to_options_menu()
*
* @since 3.2
*/
function dfcg_loadjs_admin_head() {
	
	echo '<link rel="stylesheet" href="' . DFCG_URL . '/admin-assets/dfcg-ui-admin.css" type="text/css" />' . "\n";
}



/**
* Display the Settings page
*
* Used by dfcg_add_to_options_menu()
*
* @global array $dfcg_options db main options
* @since 3.2
*/
function dfcg_options_page(){
	
	// Needed because this is passed to dfcg_on_load_validation() in dfcg-admin-ui-screen.php
	global $dfcg_options;
	
	// Need to get these back from the db because they may have been updated since page was last loaded
	$dfcg_postmeta_upgrade = get_option('dfcg_plugin_postmeta_upgrade');
	
	if( $dfcg_postmeta_upgrade['upgraded'] == 'completed' ) {
		include_once( DFCG_DIR . '/includes/dfcg-admin-ui-screen.php' );
	
	} else {
		// We need to upgrade
		include_once( DFCG_DIR . '/includes/dfcg-admin-ui-upgrade-screen.php' );
	}
}


/**
* Display a Settings link in main Plugin page in Dashboard
*
*	Puts the Settings link in with Deactivate/Activate links in Plugins Settings page
*
*	Hooked to 'plugin_action_links' filter
*
* @return array $links Array of links shown in first column, main Dashboard Plugins page
* @since 1.0
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


/**
* Display Plugin Meta Links in main Plugin page in Dashboard
*
* Adds additional meta links in the plugin's info section in main Plugins Settings page
*
* Hooked to 'plugin_row_meta filter' so only works for WP 2.8+
*
* @param array $links Default links for each plugin row
* @param string $file plugins.php filehook
*
* @return array $links Array of customised links shown in plugin row after activation
* @since 3.0
*/
function dfcg_plugin_meta($links, $file) {
 
	// Check we're only adding links to this plugin
	if( $file == DFCG_FILE_NAME ) {
	
		// Create DCG links
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


/**
* Function to do WP Version check
*
* DCG v3.0 requires WP 2.8+ to run. This function prints a warning
* message in the main Plugins screen and on the DCG Settings page if version is less than 2.8.
*
* Hooked to 'after_action_row_$plugin' filter
*
* @since 3.2
*/	
function dfcg_wp_version_check() {
	
	$wp_valid = version_compare(get_bloginfo("version"), DFCG_WP_VERSION_REQ, '>=');
	
	$current_page = basename($_SERVER['PHP_SELF']);
	
	// Check we are on the right screen and version is not valid
	if( $current_page == "plugins.php" && !$wp_valid ) {
		
		$version_msg_start = '<tr class="plugin-update-tr"><td class="plugin-update" colspan="3">';
		$version_msg_start .= '<div class="update-message" style="background:#FFEBE8;border-color:#BB0000;">';
		$version_msg_end = '</div></td></tr>';
		
		if( !function_exists('wpmu_create_blog') ) {
			// We're in WP
			$version_msg = __('<strong>Warning!</strong> This version of Dynamic Content Gallery requires Wordpress', DFCG_DOMAIN) . ' <strong>' . DFCG_WP_VERSION_REQ . '</strong>+ ' . __('Please upgrade Wordpress to run this plugin.', DFCG_DOMAIN);
			echo $version_msg_start . $version_msg . $version_msg_end;
		
		} else {
			// We're in WPMU
			$version_msg = __('<strong>Warning!</strong> This version of Dynamic Content Gallery requires WPMU', DFCG_DOMAIN) . ' <strong>' . DFCG_WP_VERSION_REQ . '</strong>+ ' . __('Please contact your Site Administrator.', DFCG_DOMAIN);
			echo $version_msg_start . $version_msg . $version_msg_end;
		}
	}
	
	// The following will also show the version warning message on the DCG Settings page and at the top of the Plugins page
	// We only need to check against options-general.php because this part of the function
	// will only be run by the calling function dfcg_on_load_validation() which is only run when we're on the DCG page.
	// TODO: Would be better to hook to admin_notices though...
	if( ( $current_page == "options-general.php" || $current_page == "plugins.php" ) && !$wp_valid ) {
		
		$version_msg_start = '<div class="error"><p>';
		$version_msg_end = '</p></div>';
		
		if( !function_exists('wpmu_create_blog') ) {
			// We're in WP
			$version_msg = '<strong>' . __('Warning! This version of Dynamic Content Gallery requires Wordpress', DFCG_DOMAIN) . ' ' . DFCG_WP_VERSION_REQ . '+ ' . __('Please upgrade Wordpress to run this plugin.', DFCG_DOMAIN) . '</strong>';
			echo $version_msg_start . $version_msg . $version_msg_end;
			
		} else {
			// We're in WPMU
			$version_msg = '<strong>' . __('Warning! This version of Dynamic Content Gallery requires WPMU', DFCG_DOMAIN) . ' ' . DFCG_WP_VERSION_REQ . '+ ' . __('Please contact your Site Administrator.', DFCG_DOMAIN) . '</strong>';
			echo $version_msg_start . $version_msg . $version_msg_end;
		}
	}
}


/**
* Function to display Admin Notices after Settings Page reset
*
* Displays Admin Notices after Settings are reset
*
* Hooked to 'admin_notices' action
*
* @global array $dfcg_options db main plugin options
* @since 3.0
*/	
function dfcg_admin_notice_reset() {
	
	global $dfcg_options;
	
	if( $dfcg_options['just-reset'] == 'true' ) {
	
		echo '<div id="message" class="updated fade" style="background-color:#ecfcde; border:1px solid #a7c886;"><p><strong>' . __('Dynamic Content Gallery Settings have been reset to default settings.', DFCG_DOMAIN) . '</strong></p></div>';

		// Reset just-reset to false and update db options
		$dfcg_options['just-reset'] = 'false';
		update_option('dfcg_plugin_settings', $dfcg_options);
	}
}



/***** Options handling and upgrading *****/

/**
* Function for building default options
*
* Contains the latest version's default options.
* Populates the options on first install (not upgrade) and
* when Settings Reset is performed.
*
* Used by the "upgrader" function dfcg_set_gallery_options().
*
* 84 options (5 are WP only)
*
* @since 3.2.2
*/
function dfcg_default_options() {
	// Add WP/WPMU options - we'll deal with the differences in the Admin screens
	$default_options = array(
		'populate-method' => 'one-category',					// Populate method for how the plugin works - since 2.3: multi-option, one-category, pages
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
		'image-url-type' => 'full',								// WP only. All methods: URL type for dfcg-images - since 2.3: full, partial
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
		'limit-scripts' => 'homepage',							// Settings: Select scripts loading: homepage, pagetemplate, other
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
		'scripts' => 'mootools',								// all methods: Selects js framework: mootools, jquery
		'slide-h2-weight' => 'bold',							// JS jquery only: bold, normal
		'slide-p-line-height' => '14',							// JS jquery only
		'slide-overlay-color' => '#000000',						// JS jquery only
		'slide-overlay-position' => 'bottom',					// JS jquery only: bottom,top
		'transition-speed' => '1500',							// JS jquery only
		'nav-theme' => 'light',									// JS jquery only: light, dark
		'pause-on-hover' => 'true',								// JS jquery only
		'fade-panels' => 'true',								// JS jquery only
		'gallery-background' => '#000000',						// JS jquery only
		'desc-method' => 'manual',								// all methods: Select how to display descriptions: manual, auto, none
		'max-char' => '100',									// all methods: No. of characters for custom excerpt
		'more-text' => '[more]',								// all methods: More text for custom excerpt
		'slide-p-a-color' => '#FFFFFF',							// all methods: More text CSS
		'slide-p-ahover-color' => '#FFFFFF',					// all methods: More text CSS
		'slide-p-a-weight' => 'normal',							// all methods: More text CSS: bold, normal
		'slide-p-ahover-weight' => 'bold',						// all methods: More text CSS: bold, normal
		'pages-sort-column' => 'true',							// Pages: Show edit pages column _dfcg-sort: bool
		'pages-sort-control' => 'false',						// Pages: Allow custom sort of images using _dfcg-sort: bool
		'page-ids' => ''										// Restrict scripts: ordinary page ID numbers
	);
	
	// Return options array for use elsewhere
	return $default_options;
}


/**
* Function for loading and upgrading options
*
* Loads options on 'admin_menu' hook.
*
* Called by dfcg_add_page() which is hooked to 'admin_menu'
*
* In 2.3 - "imagepath" is deprecated, replaced by "imageurl" in 2.3
* In 2.3 - "defimagepath" is deprecated, replaced by "defimgmulti" and "defimgonecat"
* In 2.3 - 29 orig options + 30 new options added , total now is 59
* In RC2 - "nourl" value of "image-url-type" is deprecated
* In RC3 - "posts-column" added
* In RC3 - "pages-column" added
* In RC3 - Total options is 59 + 2 = 61
* In RC4 - "posts-desc-column" added
* In RC4 - "pages-desc-column" added
* In RC4 - "just-reset" added
* In RC4 - "scripts" added
* In RC4 - 9 jQuery options added
* In RC4 - Total options is 61 + 13 = 74
* In RC4 - "part" value of "image-url-type" is changed to "partial"
* In 3.1 - "desc-method" added
* In 3.1 - "max-char" added
* In 3.1 - "more-text" added
* In 3.1 - "slide-p-a-color", "slide-p-ahover-color", "slide-p-a-weight", "slide-p-ahover-weight" added
* In 3.1 - Total options = 74 + 7 = 81
* In 3.2 - "desc-method" can now have three values - auto, manual, none
* In 3.2 - 'pages-sort-column' added
* In 3.2 - 'pages-sort-control' added
* In 3.2 - Total options = 81 + 2 = 83
* In 3.2.2 - 'page-ids' option added
* In 3.2.2 - new value 'page' added to 'limit-scripts' option
* In 3.2.2 - Total options = 83 + 1 = 84
*
*
* @uses dfcg_default_options()
* @since 3.2.2
*/
function dfcg_set_gallery_options() {
	
	// Get currently stored options - if they exist
	$existing = get_option( 'dfcg_plugin_settings' );
	
	// Get current version number
	$existing_version = get_option('dfcg_version');
	
	
	// Existing version is same as this version
	if( $existing_version == DFCG_VER ) {
		// Nothing to do here...
		return;
	
	
	// We're upgrading from 3.2.1
	} elseif( $existing && $existing_version == '3.2.1' ) {
		
		// Added in 3.2.2
		$existing['page-ids'] = ''; 	// Restrict scripts: ordinary page ID numbers
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from 3.2
	} elseif( $existing && $existing_version == '3.2' ) {
		
		// Added in 3.2.2
		$existing['page-ids'] = '';
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from 3.1
	} elseif( $existing && $existing_version == '3.1' ) {
		
		// Added in 3.2.2
		$existing['page-ids'] = '';
		// Added in 3.2
		$existing['pages-sort-column'] = 'true';	// Pages: Show edit pages column _dfcg-sort: bool
		$existing['pages-sort-control'] = 'false';	// Pages: Allow custom sort of images using _dfcg-sort: bool
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from 3.1 RC1
	} elseif( $existing && $existing_version == '3.1 RC1' ) {
		
		// Added in 3.2.2
		$existing['page-ids'] = '';
		// Added in 3.2
		$existing['pages-sort-column'] = 'true';
		$existing['pages-sort-control'] = 'false';
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from 3.0
	} elseif( $existing && $existing_version == '3.0' ) {
		
		// Added in 3.2.2
		$existing['page-ids'] = '';
		// Added in 3.2
		$existing['pages-sort-column'] = 'true';
		$existing['pages-sort-control'] = 'false';
		// Added in 3.1
		$existing['desc-method'] = 'manual';			// all methods: Select whether desc is orig manual method or new custom excerpt for auto description
		$existing['max-char'] = '100';					// all methods: No. of characters for custom excerpt
		$existing['more-text'] = '[more]';				// all methods: More text for custom excerpt
		$existing['slide-p-a-color'] = '#FFFFFF';		// all methods: More text CSS
		$existing['slide-p-ahover-color'] = '#FFFFFF';	// all methods: More text CSS
		$existing['slide-p-a-weight'] = 'normal';		// all methods: More text CSS
		$existing['slide-p-ahover-weight'] = 'bold';	// all methods: More text CSS
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from 3.0 RC4
	} elseif( $existing && $existing_version == '3.0 RC4' ) {
		
		// Added in 3.2.2
		$existing['page-ids'] = '';
		// Added in 3.2
		$existing['pages-sort-column'] = 'true';
		$existing['pages-sort-control'] = 'false';
		// Added in 3.1
		$existing['desc-method'] = 'manual';
		$existing['max-char'] = '100';
		$existing['more-text'] = '[more]';
		$existing['slide-p-a-color'] = '#FFFFFF';
		$existing['slide-p-ahover-color'] = '#FFFFFF';
		$existing['slide-p-a-weight'] = 'normal';
		$existing['slide-p-ahover-weight'] = 'bold';
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from 3.0 RC3
	} elseif( $existing && $existing_version == '3.0 RC3' ) {
		
		// 'part' changed to 'partial'
		if( $existing['image-url-type'] == 'part' ) {
			$existing['image-url-type'] = 'partial';
		}
		
		// Added in 3.2.2
		$existing['page-ids'] = '';
		// Added in 3.2
		$existing['pages-sort-column'] = 'true';
		$existing['pages-sort-control'] = 'false';
		// Added in 3.1
		$existing['desc-method'] = 'manual';
		$existing['max-char'] = '100';
		$existing['more-text'] = '[more]';
		$existing['slide-p-a-color'] = '#FFFFFF';
		$existing['slide-p-ahover-color'] = '#FFFFFF';
		$existing['slide-p-a-weight'] = 'normal';
		$existing['slide-p-ahover-weight'] = 'bold';
		// Added in 3.0 RC4
		$existing['posts-desc-column'] = 'true';
		$existing['pages-desc-column'] = 'true';
		$existing['just-reset'] = 'false';
		$existing['scripts'] = 'mootools';
		$existing['slide-h2-weight'] = 'bold';
		$existing['slide-p-line-height'] = '14';
		$existing['slide-overlay-color'] = '#000000';
		$existing['slide-overlay-position'] = 'bottom';
		$existing['transition-speed'] = '1500';
		$existing['nav-theme'] = 'light';
		$existing['pause-on-hover'] = 'true';
		$existing['fade-panels'] = 'true';
		$existing['gallery-background'] = '#000000';
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	//We're upgrading from 3.0 RC2
	} elseif( $existing && $existing_version == '3.0 RC2' ) {
		
		// 'part' changed to 'partial'
		if( $existing['image-url-type'] == 'part' ) {
			$existing['image-url-type'] = 'partial';
		}
		
		// Added in 3.2.2
		$existing['page-ids'] = '';
		// Added in 3.2
		$existing['pages-sort-column'] = 'true';
		$existing['pages-sort-control'] = 'false';
		// Added in 3.1
		$existing['desc-method'] = 'manual';
		$existing['max-char'] = '100';
		$existing['more-text'] = '[more]';
		$existing['slide-p-a-color'] = '#FFFFFF';
		$existing['slide-p-ahover-color'] = '#FFFFFF';
		$existing['slide-p-a-weight'] = 'normal';
		$existing['slide-p-ahover-weight'] = 'bold';
		//Added in 3.0 RC4
		$existing['posts-desc-column'] = 'true';
		$existing['pages-desc-column'] = 'true';
		$existing['just-reset'] = 'false';
		$existing['scripts'] = 'mootools';
		$existing['slide-h2-weight'] = 'bold';
		$existing['slide-p-line-height'] = '14';
		$existing['slide-overlay-color'] = '#000000';
		$existing['slide-overlay-position'] = 'bottom';
		$existing['transition-speed'] = '1500';
		$existing['nav-theme'] = 'light';
		$existing['pause-on-hover'] = 'true';
		$existing['fade-panels'] = 'true';
		$existing['gallery-background'] = '#000000';
		// Added in 3.0 RC3
		$existing['posts-column'] = 'true';
		$existing['pages-column'] = 'true';
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from pre-RC2 v3 version (which used version number 2.3)
	} elseif( $existing && $existing_version == '2.3' ) {
		
		// If NO URL exists, change it to Partial URL (NO URL is deprecated)
		if( $existing['image-url-type'] == 'nourl' ) {
			$existing['image-url-type'] = 'partial';
		}
		// 'part' changed to 'partial'
		if( $existing['image-url-type'] == 'part' ) {
			$existing['image-url-type'] = 'partial';
		}
		
		// Added in 3.2.2
		$existing['page-ids'] = '';
		// Added in 3.2
		$existing['pages-sort-column'] = 'true';
		$existing['pages-sort-control'] = 'false';
		// Added in 3.1
		$existing['desc-method'] = 'manual';
		$existing['max-char'] = '100';
		$existing['more-text'] = '[more]';
		$existing['slide-p-a-color'] = '#FFFFFF';
		$existing['slide-p-ahover-color'] = '#FFFFFF';
		$existing['slide-p-a-weight'] = 'normal';
		$existing['slide-p-ahover-weight'] = 'bold';
		// Added in 3.0 RC4
		$existing['posts-desc-column'] = 'true';
		$existing['pages-desc-column'] = 'true';
		$existing['just-reset'] = 'false';
		$existing['scripts'] = 'mootools';
		$existing['slide-h2-weight'] = 'bold';
		$existing['slide-p-line-height'] = '14';
		$existing['slide-overlay-color'] = '#000000';
		$existing['slide-overlay-position'] = 'bottom';
		$existing['transition-speed'] = '1500';
		$existing['nav-theme'] = 'light';
		$existing['pause-on-hover'] = 'true';
		$existing['fade-panels'] = 'true';
		$existing['gallery-background'] = '#000000';
		// Added in 3.0 RC3
		$existing['posts-column'] = 'true';
		$existing['pages-column'] = 'true';
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $existing );
		
		// Update version no. in the db
		update_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from version 2.2
	} elseif( $existing && $existing_version !== DFCG_VER ) {
		
		// Assign old imagepath to new imageurl
		// imagepath was the URL excluding "Home" and the custom field entry
		$existing['imageurl'] = $existing['homeurl'] . $existing['imagepath'];
		
		// Assign old defimagepath to defimgmulti and defimgonecat
		$existing['defimgmulti'] = $existing['homeurl'] . $existing['defimagepath'];
		$existing['defimgonecat'] = $existing['homeurl'] . $existing['defimagepath'];
		
		// Remove old keys from db
		unset($existing['imagepath']);
		unset($existing['defimagepath']);
		
		// Added in 3.2.2
		$existing['page-ids'] = '';
		// Added in 3.2
		$existing['pages-sort-column'] = 'true';
		$existing['pages-sort-control'] = 'false';
		// Added in 3.1
		$existing['desc-method'] = 'manual';
		$existing['max-char'] = '100';
		$existing['more-text'] = '[more]';
		$existing['slide-p-a-color'] = '#FFFFFF';
		$existing['slide-p-ahover-color'] = '#FFFFFF';
		$existing['slide-p-a-weight'] = 'normal';
		$existing['slide-p-ahover-weight'] = 'bold';
		// Added in 3.0 RC4
		$existing['posts-desc-column'] = 'true';
		$existing['pages-desc-column'] = 'true';
		$existing['just-reset'] = 'false';
		$existing['scripts'] = 'mootools';
		$existing['slide-h2-weight'] = 'bold';
		$existing['slide-p-line-height'] = '14';
		$existing['slide-overlay-color'] = '#000000';
		$existing['slide-overlay-position'] = 'bottom';
		$existing['transition-speed'] = '1500';
		$existing['nav-theme'] = 'light';
		$existing['pause-on-hover'] = 'true';
		$existing['fade-panels'] = 'true';
		$existing['gallery-background'] = '#000000';
		// Added in 3.0 RC3
		$existing['posts-column'] = 'true';
		$existing['pages-column'] = 'true';
		// Added in 2.3
		$existing['populate-method'] = 'multi-option';
		$existing['cat-display'] = '1';
		$existing['posts-number'] = '5';
		$existing['pages-selected'] = '';
		$existing['image-url-type'] = 'partial';
		$existing['defimgpages'] = '';
		$existing['slide-h2-padtb'] = '0';
		$existing['slide-h2-padlr'] = '0';
		$existing['slide-p-padtb'] = '0';
		$existing['slide-p-padlr'] = '0';
		$existing['limit-scripts'] = 'homepage';
		$existing['page-filename'] = '';
		$existing['timed'] = 'true';
		$existing['delay'] = '9000';
		$existing['showCarousel'] = 'true';
		$existing['showInfopane'] = 'true';
		$existing['slideInfoZoneSlide'] = 'true';
		$existing['slideInfoZoneOpacity'] = '0.7';
		$existing['textShowCarousel'] = 'Featured Articles';
		$existing['defaultTransition'] = 'fade';
		$existing['cat06'] = '1';
		$existing['cat07'] = '1';
		$existing['cat08'] = '1';
		$existing['cat09'] = '1';
		$existing['off06'] = '';
		$existing['off07'] = '';
		$existing['off08'] = '';
		$existing['off09'] = '';
		$existing['errors'] = 'true';
		
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $existing );
		
		// Add version no. in the db
		add_option('dfcg_version', DFCG_VER );
	
	
	// We're upgrading from some unknown earlier version, and settings exist
	} elseif( $existing ) {
		
		// Clear out the old options
		delete_option('dfcg_plugin_settings');
		
		// Add the new. User will have to redo Settings Page setup
		$default_options = dfcg_default_options();
		add_option('dfcg_plugin_settings', $default_options );
		
		// Add version no. in the db
		add_option('dfcg_version', DFCG_VER );
	
	
	// It's a clean install
	} else {
		
		// Add the new options
		$default_options = dfcg_default_options();
		add_option('dfcg_plugin_settings', $default_options );
		
		// Add version to the options db
		add_option('dfcg_version', DFCG_VER );
		
		// Add postmeta upgrade flag
		$postmeta_upgrade = array();
		$postmeta_upgrade['upgraded'] = 'completed';
		add_option('dfcg_plugin_postmeta_upgrade', $postmeta_upgrade);
	}
}
