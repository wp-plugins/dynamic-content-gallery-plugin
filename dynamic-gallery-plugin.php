<?php
/*
Plugin Name: Dynamic Content Gallery
Plugin URI: http://www.studiograsshopper.ch/wordpress-plugins/dynamic-content-gallery-plugin-v2/
Version: 3.0 beta
Author: Ade Walker, Studiograsshopper
Author URI: http://www.studiograsshopper.ch
Description: Creates a dynamic gallery of images for latest and/or featured posts or pages. Set up the plugin options in Settings>Dynamic Content Gallery.
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

	3.0			- Feature: 	Added external link capability using dfcg-link custom field
				- Feature:	Added form validation + reminder messages to Settings page
				- Feature: 	Added Error messages to help users troubleshoot setup problems
				- Feature: 	Re-designed layout of Settings page, added Category selection dropdowns etc
				- Feature: 	New Javascript gallery options added to Settings page and main js file now migrated
							to PHP in order to allow better interaction with Settings.
							(jQuery handles this SO much better than Mootools).
				- Feature: 	Added "populate-method" Settings. User can now pick between old way,
							one category only, or Pages.
				- Feature: 	Added Settings for limiting loading of scripts into head. New function to handle this.
				- Feature: 	Added Full, Partial, No URL Settings to simplify location of images and be
							more suitable for "unusual" WP setups.
				- Feature: 	Added Padding Settings for Info Pane Heading and Description
				- Bug fix: 	Complete re-write of dynamic-gallery.php, more efficient coding
				- Bug fix: 	Changed $options variable name to $dfcg_options to avoid conflicts
							with other plugins.
				- Bug fix: 	Moved galleryStart() js function to HEAD within dfcg_header_scripts()
					
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
	exit("Sorry, you are not allowed to access this page directly.");
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


/* Set constants for plugin directory path and URL, version number, textdomain */
define( 'DFCG_URL', WP_PLUGIN_URL.'/dynamic-content-gallery-plugin' );
define( 'DFCG_DIR', WP_PLUGIN_DIR.'/dynamic-content-gallery-plugin' );
define( 'DFCG_VER', '2.3' );
define( 'DFCG_DOMAIN', 'Dynamic_Content_Gallery' );


/* Internationalization functionality */
$dfcg_text_loaded = false;

function dfcg_load_textdomain() {
	global $dfcg_text_loaded;
   	if( $dfcg_text_loaded ) {
   		return;
   	}

   	load_plugin_textdomain(DFCG_DOMAIN, WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)), dirname(plugin_basename(__FILE__)));
   	$dfcg_text_loaded = true;
}


/***** Files needed for plugin to run ********************/

/* 	Load the files needed for Gallery output to be displayed
*
*	dfcg-key-variables		Declares global scope variables = Options array variable, $dfcg_baseimgurl 
*	dfcg-error-messages		Browser and/or Page Source errors.
*	dfcg-gallery-functions	Three gallery constructor functions
*
*	@since	3.0
*/ 
include_once( DFCG_DIR . '/includes/dfcg-key-variables.php');
include_once( DFCG_DIR . '/includes/dfcg-error-messages.php');
include_once( DFCG_DIR . '/includes/dfcg-gallery-functions.php');


/**	Template tag to display gallery in theme files
*
*	Do not use in the Loop.
*
*	@uses	dynamic-gallery.php
*	@since	2.1
*/
function dynamic_content_gallery() {
	global $dfcg_options;
	include_once('dynamic-gallery.php');
}


/***** Functions to display gallery ******************** */

/* 	Function to determine whether to load scripts into head.
*
*	Called by wp_head action.
*	Determines whether to load dfcg_header_scripts depending
*	on Settings.
*	Settings options are homepage, a page template or other.
*	Settings "other" loads scripts into every page.
*
*	@uses	dfcg_header_scripts()
*	@since 	3.0
*/
function dfcg_load_scripts() {
	
	global $dfcg_options;
	
	if( $dfcg_options['limit-scripts'] == 'homepage' ) {
    	
    	if( is_home() || is_front_page() ) {
    		dfcg_header_scripts();
    	} else {
    		return;
    	}
    } elseif( $dfcg_options['limit-scripts'] == 'pagetemplate' ) {
		
		if( is_page_template($dfcg_options['page-filename']) ) {
			dfcg_header_scripts();
    	} else {
    		return;
    	}
    } else {
    	dfcg_header_scripts();
    }
}


/* 	Header scripts
*
*	Called by dfcg_load_scripts which is hooked to wp_head action.
*	Loads scripts and CSS into head
*
*	@since	1.0
*/
function dfcg_header_scripts() {
    
	global $dfcg_options;
    
    /* Add CSS file */
	echo "\n" . '<!-- Dynamic Content Gallery plugin version ' . DFCG_VER . ' www.studiograsshopper.ch  Begin scripts -->' . "\n";
	echo '<link type="text/css" rel="stylesheet" href="' . DFCG_URL . '/css/jd.gallery.css" />' . "\n";
	
	/* Should mootools framework be loaded? */
	if ( $dfcg_options['mootools'] !== '1' ) {
	echo '<script type="text/javascript" src="' . DFCG_URL . '/scripts/mootools.v1.11.js"></script>' . "\n";
	}
	
	/* Add gallery javascript files */
	echo '<script type="text/javascript" src="' . DFCG_URL . '/scripts/jd.gallery.php"></script>' . "\n";
	echo '<script type="text/javascript" src="' . DFCG_URL . '/scripts/jd.gallery.transitions.js"></script>' . "\n";
	
	/* Add JS function call to gallery */
	echo "<script type=\"text/javascript\">
   function startGallery() {
      var myGallery = new gallery($('myGallery'), {
      });
   }
   window.addEvent('domready',startGallery);
</script>" . "\n";
	
	/* Add user defined CSS */
	include_once('dfcg-user-styles.php');
	
	/* End of scripts */
	echo '<!-- End of Dynamic Content Gallery scripts -->' . "\n\n";
}
add_action('wp_head', 'dfcg_load_scripts');





/***** Admin and Settings Page ******************** */

/**	Setup the plugin and create Admin settings page
*
*	@uses	dfcg_textdomain()
*	@uses	dfcg_options_page()
*	@uses	dfcg_filter_plugin_actions()
*	@uses	dfcg_set_gallery_options()
*
*	@since	1.0
*/	
function dfcg_setup() {
	dfcg_load_textdomain();
	if ( current_user_can('manage_options') && function_exists('add_options_page') ) {
		$dfcgpage = add_options_page('Dynamic Content Gallery Options', 'Dynamic Content Gallery', 'manage_options', 'dynamic-gallery-plugin.php', 'dfcg_options_page');
		add_filter( 'plugin_action_links', 'dfcg_filter_plugin_actions', 10, 2 );
		dfcg_set_gallery_options();
		//add_action( 'admin_print_scripts-settings_page_dynamic-gallery-plugin', 'dfcg_admin_head' );
		//add_action( 'admin_print_styles-settings_page_dynamic-gallery-plugin', 'dfcg_admin_head_css', 'all');
	}
}
add_action('admin_menu', 'dfcg_setup');


// Future feature for version 3+. Leave for now.

/*function dfcg_admin_head() {
	wp_enqueue_script('jquery');
	//wp_enqueue_script('dfcg-slidebox', DFCG_URL . '/admin-assets/dfcg-slidepanel.js', 'jquery');
}
add_action( 'admin_print_scripts-settings_page_dynamic-gallery-plugin', 'dfcg_admin_head' );
*/
/*      
function dfcg_admin_head_css() {
	wp_enqueue_style('dfcg-slidebox-css', DFCG_URL . '/admin-assets/dfcg-slidepanel.css'); 
}
add_action( 'admin_print_styles-settings_page_dynamic-gallery-plugin', 'dfcg_admin_head_css','screen' );
*/
 

/**	Display the Settings page
*
*	Used by dfcg_setup()
*	Selects between WP or WPMU pages
*
*	@since	1.0
*/	
function dfcg_options_page(){
	global $dfcg_options;
	if ( function_exists('wpmu_create_blog') ) {
		// Load the WPMU options page
		include_once('dfcg-wpmu-ui.php');
		// Load the WP options page
	} else {
		include_once('dfcg-wp-ui.php');
	}
}


/**	Display a Settings link in main Plugin page in Dashboard
*
*	Used by dfcg_setup()
*
*	@since	1.0
*/	
function dfcg_filter_plugin_actions($links, $file){
	static $this_plugin;

	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if( $file == $this_plugin ){
		$settings_link = '<a href="admin.php?page=dynamic-gallery-plugin.php">' . __('Settings') . '</a>';
		$links = array_merge( array($settings_link), $links); // before other links
	}
	return $links;
}


/**	Function for adding default options
*	
*	Contains the latest version's default options.
*	Used by the "upgrader" function dfcg_set_gallery_options.
*	Used if Reset button is clicked.
*
*	@since	3.0	
*/
function dfcg_default_options() {
	// Add WP/WPMU options - we'll deal with the differences in the Admin screens
	$dfcg_default_options = array(
		'populate-method' => 'multi-option',					// Populate method for how the plugin works - since 2.3
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
		'off06' => '1',											// multi-option: the post select
		'off07' => '1',											// multi-option: the post select
		'off08' => '1',											// multi-option: the post select
		'off09' => '1',											// multi-option: the post select
		'pages-selected' => '',									// pages: Page ID's in comma separated list - since 2.3
		'homeurl' => get_option('home'),						// Stored, but not currently used...
		'image-url-type' => 'full',								// WP only. All methods: URL type for dfcg-images - since 2.3
		'imageurl' => '',										// WP only. All methods: URL for part or nourl custom images
		'defimgmulti' => '',									// WP only. Multi-option: Path for default category image folder
		'defimgonecat' => '',									// WP only. One-category: Path for default category image folder
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
		'reset' => 'false',										// Settings: Reset options state
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
		'errors' => 'true',										// all methods: Error reporting on/off
	);
	
	// Add options
	add_option('dfcg_plugin_settings', $dfcg_default_options );
}


/**	Function for upgrading options
*	
*	Loads options on admin_menu hook.
*	Includes "upgrader" routine to update existing install.
*	In 2.3 - "imagepath" is deprecated, replaced by "imageurl" in 2.3
*	In 2.3 - "defimagepath" is deprecated, replaced by "defimgmulti" and "defimgonecat"
*	29 orig options + 30 new options added , total now is 59
*
*	Hooked to admin_menu
*
*	@uses 	dfcg_default_options()
*	@since	3.0	
*/
function dfcg_set_gallery_options() {
	
	// Get current options
	$dfcg_existing = get_option( 'dfcg_plugin_settings' );
	// Get current version number
	$dfcg_prev_version = get_option('dfcg_version');
	
	// Existing version is same as this version
	if( $dfcg_prev_version == DFCG_VER ) {
		// Nothing to do here...
		return;
	
	// There are existing options and version is out of date	
	} elseif( $dfcg_existing && $dfcg_prev_version < DFCG_VER ) {
		
		// We're upgrading from version 2.2 to 2.3
		// Assign old imagepath to new imageurl
		$dfcg_existing['imageurl'] = $dfcg_existing['imagepath'];
		
		// Assign old defimagepath to defimgmulti and defimgonecat
		$dfcg_existing['defimgmulti'] = $dfcg_existing['defimagepath'];
		$dfcg_existing['defimgonecat'] = $dfcg_existing['defimagepath'];
		
		// Remove old keys from db
		unset($dfcg_existing['imagepath']);
		unset($dfcg_existing['defimagepath']);
		
		// Now add new version 2.3 options
		$dfcg_existing['populate-method'] = 'multi-option';						// Populate method for how the plugin works - since 2.3
		$dfcg_existing['cat-display'] = '1';									// one-category: the ID of the selected category - since 2.3
		$dfcg_existing['posts-number'] = '5';									// one-category: the number of posts to display - since 2.3
		$dfcg_existing['pages-selected'] = '';									// pages: Page ID's in comma separated list - since 2.3
		$dfcg_existing['image-url-type'] = 'full';								// WP only. All methods: URL type for dfcg-images - since 2.3
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
		$dfcg_existing['cat06'] = '1';
		$dfcg_existing['cat07'] = '1';
		$dfcg_existing['cat08'] = '1';
		$dfcg_existing['cat09'] = '1';
		$dfcg_existing['off06'] = '';
		$dfcg_existing['off07'] = '';
		$dfcg_existing['off08'] = '';
		$dfcg_existing['off09'] = '';
		$dfcg_existing['errors'] = 'true';
				
		// Delete the old and add the upgraded options
		delete_option('dfcg_plugin_settings');
		add_option( 'dfcg_plugin_settings', $dfcg_existing );
		
		// Add version to the options db
		add_option('dfcg_version', DFCG_VER );
		
	} else {
		// It's a clean install, so load everything
		dfcg_default_options();
			
		// Add version to the options db
		add_option('dfcg_version', DFCG_VER );
	}
}


/**	Function to delete options
*	
*	Needed for pre 2.7 WP to delete options from database on deactivation.
*	Used to clear options if Reset button is clicked.
*
*	@since	1.0
*/
function dfcg_unset_gallery_options() {
	delete_option('dfcg_plugin_settings');
}


/** Determine whether to register deactivation hook if installed on pre 2.7 WP.
*
*	This is not needed in WP 2.7+, as deletion of Options is 
*	handled by uninstall.php.
*	Check if "register_uninstall_hook" functions exists, which is post WP 2.7 only.
*	If in WP < 2.7, we register_deactivation_hook()
*
*	@uses	dfcg_unset_gallery_options()
*	@since	1.0
*/
if ( !function_exists('register_uninstall_hook') ) {
     // we're in < 2.7 so register the deactivation hook
     register_deactivation_hook(__FILE__, 'dfcg_unset_gallery_options');
}


/**	Function for validating user input on submit of Settings page form
*	
*	Prints validation messages to the Settings Page.
*
*	@param	array	$options_array, the options from $_POST variable
*
*	@since	3.0	
*/
function dfcg_on_submit_validation($options_array) {

	// $options_array is the array of options from the db
	 
	// If Partial URL is selected, imageurl must be defined
	if( $options_array['image-url-type'] == 'part' && empty($options_array['imageurl']) ) {
		echo '<div id="message" class="error"><p><strong>' . __('Validation check: You have selected "Partial" URL option in your <a href="#1">Image File Management settings</a>, but you have not defined the URL to your images folder.<br />Please enter the URL to your images folder in <a href="#1">Section 1</a>.') . '</strong></p></div>';
	}
	
	// If No URL is selected, imageurl must be defined
	if( $options_array['image-url-type'] == 'nourl' && empty($options_array['imageurl']) ) {
		echo '<div id="message" class="error"><p><strong>' . __('Validation check: You have selected "No URL" option in your <a href="#1">Image File Management settings</a>, but you have not defined the URL to your images folder.<br />Please enter the URL to your images folder in <a href="#1">Section 1</a>.') . '</strong></p></div>';
	}
	
	if ( function_exists('wpmu_create_blog') ) {
		// We're in WPMU, so ignore these messages
	} else {
		// If Multi Option, defimgmulti must be defined
		if( $options_array['populate-method'] == 'multi-option' && empty($options_array['defimgmulti']) ) {
			echo '<div id="message" class="updated"><p><strong>' . __('Validation check: You have selected to display the gallery using the "Multi Option" method in <a href="#2">Section 2</a>, but you have not defined the Path to your default images.<br />Please enter the Path to your Category default images folder in <a href="#2.2">Section 2.2</a>.') . '</strong></p></div>';
		}
		
		// If One Category, defimgonecat must be defined
		if( $options_array['populate-method'] == 'one-category' && empty($options_array['defimgonecat']) ) {
			echo '<div id="message" class="updated"><p><strong>' . __('Validation check: You have selected to display the gallery using the "One Category" method in <a href="#2">Section 2</a>, but you have not defined the Path to your default images.<br />Please enter the Path to your Category default images folder in <a href="#2.1">Section 2.1</a>.') . '</strong></p></div>';
		}
		
		// If Pages, defimgpages must be defined
		if( $options_array['populate-method'] == 'pages' && empty($options_array['defimgpages']) ) {
			echo '<div id="message" class="updated"><p><strong>' . __('Validation check: You have selected to display the gallery using the "Pages" method in <a href="#2">Section 2</a>, but you have not defined the URL to your default image.<br />Please enter the URL to your Pages default image in <a href="#2.3">Section 2.3</a>.') . '</strong></p></div>';
		}
	}
	
	// deal with multioption Post Selects
	$multioption_raw_offsets = array (
		$options_array['off01'],
		$options_array['off02'],
		$options_array['off03'],
		$options_array['off04'],
		$options_array['off05'],
		$options_array['off06'],
		$options_array['off07'],
		$options_array['off08'],
		$options_array['off09'],
		);
		
	$multioption_offsets = array();
		
	foreach( $multioption_raw_offsets as $key => $value ) {
		$raw_offset = $multioption_raw_offsets[$key];
		if( !empty($raw_offset) ) {
			$temp_array = $multioption_raw_offsets[$key];
			array_push($multioption_offsets, $temp_array);
			unset($temp_array);
		}
	}
		
	if( $options_array['populate-method'] == 'multi-option' && count($multioption_offsets) < 2 ) {
		echo '<div id="message" class="error"><p><strong>' . __('Validation check: You have selected to display the gallery using the "Multi Option" method in <a href="#2">Section 2</a>, but you have not defined a minimum of 2 Post Selects.<br />Please enter at least 2 Posts Selects in <a href="#2.1">Section 2.1</a>.') . '</strong></p></div>';
	}

	// End of validation checks
}


/**	Function for validation on fresh load of Settings Page (not after Submit)
*	
*	Prints validation messages to the Settings Page.
*
*	@param	array	$options_array, options from db
*
*	@since	3.0	
*/
function dfcg_on_load_validation($options_array) {

	// $options_array is the array of options from the db

	// If Partial URL is selected, imageurl must be defined
	if( $options_array['image-url-type'] == 'part' && empty($options_array['imageurl']) && !isset($_POST['info_update']) ) {
		echo '<div id="message" class="error"><p><strong>' . __('Reminder! <a name=""></a>You have selected "Partial" URL option in your <a href="#1">Image File Management settings</a>. You must enter the URL to your images folder in <a href="#1">Section 1</a>.') . '</strong></p></div>';
	}
	
	// If No URL is selected, imageurl must be defined
	if( $options_array['image-url-type'] == 'nourl' && empty($options_array['imageurl']) && !isset($_POST['info_update']) ) {
		echo '<div id="message" class="error"><p><strong>' . __('Reminder! <a name=""></a>You have selected "No URL" option in your <a href="#1">Image File Management settings</a>. You must enter the URL to your images folder in <a href="#1">Section 1</a>.') . '</strong></p></div>';
	}
	
	if ( function_exists('wpmu_create_blog') ) {
		// We're in WPMU, so ignore these messages
	} else {
		// If Multi Option, defimgmulti must be defined
		if( $options_array['populate-method'] == 'multi-option' && empty($options_array['defimgmulti']) && !isset($_POST['info_update'])) {
			echo '<div id="message" class="updated"><p><strong>' . __('Reminder! You are using the "Multi Option" <a href="#2">Gallery Method</a>. Enter the Path to your Category default images folder in the <a href="#2.1">Multi Option</a> section to take advantage of the default image feature.') . '</strong></p></div>';
		}
		
		// If One Category, defimgonecat must be defined
		if( $options_array['populate-method'] == 'one-category' && empty($options_array['defimgonecat']) && !isset($_POST['info_update']) ) {
			echo '<div id="message" class="updated"><p><strong>' . __('Reminder! You are using the "One Category" <a href="#2">Gallery Method</a>. Enter the Path to your Category default images folder in the <a href="#2.2">One Category</a> section to take advantage of the default image feature.') . '</strong></p></div>';
		}
	
		// If Pages, defimgpages must be defined
		if( $options_array['populate-method'] == 'pages' && empty($options_array['defimgpages']) && !isset($_POST['info_update']) ) {
			echo '<div id="message" class="updated"><p><strong>' . __('Reminder! You are using the "Pages"  <a href="#2">Gallery Method</a>. Enter the URL of your default image in the <a href="#2.3">Pages</a> section to take advantage of the default image feature.') . '</strong></p></div>';
		}
	}
	
	// deal with multioption Post Selects
	$multioption_raw_offsets = array (
		$options_array['off01'],
		$options_array['off02'],
		$options_array['off03'],
		$options_array['off04'],
		$options_array['off05'],
		$options_array['off06'],
		$options_array['off07'],
		$options_array['off08'],
		$options_array['off09'],
		);
		
	$multioption_offsets = array();
		
	foreach( $multioption_raw_offsets as $key => $value ) {
		$raw_offset = $multioption_raw_offsets[$key];
		if( !empty($raw_offset) ) {
			$temp_array = $multioption_raw_offsets[$key];
			array_push($multioption_offsets, $temp_array);
			unset($temp_array);
		}
	}
		
	if( $options_array['populate-method'] == 'multi-option' && count($multioption_offsets) < 2 && !isset($_POST['info_update']) ) {
		echo '<div id="message" class="error"><p><strong>' . __('Reminder! You have selected to display the gallery using the "Multi Option" method in <a href="#2">Section 2</a>. Please enter at least 2 Posts Selects in <a href="#2.1">Section 2.1</a>.') . '</strong></p></div>';
	}
}


/**	Function for loading JS and CSS for Settings Page
*	
*	Code from Nathan Rice, Theme Options plugin.
*
*	@since	3.0	
*/
function dfcg_options_css_js() {
echo <<<CSS

<style type="text/css">
.form-table th {font-size:11px;}
.metabox-holder {float:left;}
.sgr-credits {border-top:1px solid #CCCCCC;margin:10px 0px 0px 0px;padding:10px 0px 0px 0px;}
.sgr-credits p {font-size:11px;}
#sgr-info {float:right;width:260px;background:#f9f9f9;padding:0px 20px 10px 20px;margin:20px 10px 10px 10px;border:1px solid #DFDFDF;}
#sgr-info ul {list-style-type:none;margin-left:0px;}
#sgr-info img {float:left;margin:0px 10px 10px 0px;border:none;}
#sgr-info input {float:right;margin:0px 0px 10px 10px;}
#sgr-info h4 {font-size:12px;}
div.inside {padding: 0px 10px 10px 10px;margin:0px;}
.inside p {font-size:11px;padding:0px 0px 0px 0px;line-height:20px;}
.inside ul {list-style-type:disc;margin-left:30px;font-size:11px;}
.inside h4 {font-size:11px;margin:1em 0;}
.postbox-sgr {padding:0px 10px;margin:0px;}
.error p, .updated p {font-size:11px;line-height:20px;}	
</style>

CSS;
echo <<<JS

<script type="text/javascript">
jQuery(document).ready(function($) {
	$(".fade").fadeIn(1000).fadeTo(3000, 1).fadeOut(1000);
});
</script>

JS;
}