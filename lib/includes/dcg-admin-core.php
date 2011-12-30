<?php
/**
 * Admin Core functions - this is the parent file that handles all the backend
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2012
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * @info Core Admin functions called by various add_filters and add_actions:
 * @info - Internationalisation
 * @info - Register Settings API
 * @info - Add Settings Page
 * @info - Load admin js and CSS
 * @info - Plugin action links
 * @info - Plugin row meta
 * @info - Admin Notices for WP Version and Post Thumbnails check
 * @info - Add image sizes to Media Uploader
 * @info - Miscellaneous helper functions used elsewhere
 * @info - DCG options handling and upgrading
 *
 * @since 3.0
 */


/**
 * Prevent direct access to this file
 */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}


/***** Internationalisation *****/

/**
 * Load textdomain for Internationalisation functionality
 *
 * Called by dcg-admin-ui-screen.php
 *
 * Loads textdomain if $dfcg_text_loaded (global variable) is false
 *
 * Note: .mo file should be named dynamic_content_gallery-xx_XX.mo and placed in the DCG plugin's languages folder.
 * xx_XX is the language code
 * For example, for French, file should be named: dynamic_content_gallery-fr_FR.mo
 *
 * WP_LANG constant must also be defined correctly in wp-config.php.
 *
 * @uses load_plugin_textdomain()
 *
 * @since 3.2
 * @updated 4.0
 * @global bool $dfcg_text_loaded Defined in dynamic-gallery-plugin.php
 * @return bool $dfcg_text_loaded True on success, NULL on failure
 */
function dfcg_load_textdomain() {
	
	global $dfcg_text_loaded;
   	
	// If textdomain is already loaded, do nothing
	if( $dfcg_text_loaded )
   		return;
	
	// Textdomain isn't already loaded, let's load it
	// $domain = DFCG_DOMAIN, $abs_rel_path = false, $plugin_rel_path = DFCG_LANG_DIR_REL )
   	load_plugin_textdomain( DFCG_DOMAIN, false, DFCG_LANG_DIR_REL );

	// Change variable to prevent loading textdomain again
	$dfcg_text_loaded = true;
}



/***** Settings API handling *****/


/**
 * Helper function to define key variables used by Settings API functions
 *
 * All functions which need these variables run this function first,
 * which makes it much easier for maintenance, etc.
 * Also, useful for portability to other plugins as only this function needs updating
 *
 * @since 4.0
 * @return array $output Array containing base variables used by Settings API
 */
function dfcg_base_settings() {

	$output = array();

	$output['dfcg_option_name'] =	'dfcg_plugin_settings';
	$output['dfcg_page_title'] =	'Dynamic Content Gallery Configuration'; 
	$output['dfcg_nice_name'] =		'Dynamic Content Gallery'; 
	
	return $output;
}


/**
 * Register Settings as per Settings API, 2.7+
 *
 * Hooked to 'admin_init'
 *
 * @uses dfcg_base_settings(), helper function for naming variables
 * @uses dfcg_sanitise(), callback function for sanitising options
 *
 * @since 4.0
 * @return calls register_setting() WP function
 */
function dfcg_register_settings() {
	
	// Grab base settings using helper function
	$base = dfcg_base_settings();
	
	// register_setting($option_group, $option_name, $sanitize_callback)
	register_setting( $base['dfcg_option_name'], $base['dfcg_option_name'], 'dfcg_sanitise' );
}



/***** Settings Page and Plugins Page Functions *****/

/**
 * Create Admin settings page and populate options
 *
 * Hooked to 'admin_menu'
 *
 * No need to check credentials - already built in to core wp function
 *
 * @uses dfcg_set_gallery_options()
 * @uses dfcg_do_settings_page()
 * @uses dfcg_load_admin_scripts()
 * @uses dfcg_load_admin_styles()
 *
 * @since 3.2
 * @updated 4.0
 * @global $dfcg_page_hook - need to declare as global for scope purposes in other functions
 * @return calls add_options_page() and add_actions
 */
function dfcg_add_to_options_menu() {
	
	global $dfcg_page_hook;
	
	// Populate plugin's options (since 3.3.1, now runs BEFORE settings page is added. Duh!)
	dfcg_set_gallery_options();
	
	// Grab base settings using helper function
	$base = dfcg_base_settings();
	$page_title = $base['dfcg_page_title'];
	$menu_title = $base['dfcg_nice_name'];
	
	// Add Settings Page
	// add_options_page($page_title, $menu_title, $capability, $menu_slug, $function)
	$dfcg_page_hook = add_options_page( $page_title, $menu_title, 'manage_options', DFCG_FILE_HOOK, 'dfcg_do_settings_page' );
	
	// Load Admin external scripts and CSS
	add_action( 'admin_enqueue_scripts', 'dfcg_load_admin_scripts', 100 );
	add_action( 'admin_enqueue_scripts', 'dfcg_load_admin_styles', 100 );
}


/**
 * Callback to load Admin JS
 *
 * Hooked to 'admin_enqueue_scripts' in dfcg_add_to_options_menu()
 *
 * @since 3.2
 * @updated 4.0
 *
 * @param string $pagehook, passed by admin_enqueue_scripts filter
 * @return null if not on DCG settings page, or enqueue scripts if on DCG settings page
 */
function dfcg_load_admin_scripts( $pagehook ) {
	
	global $current_screen;
	
	if( $pagehook !== $current_screen->id )
		return;
		
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'dfcg_admin_js', DFCG_LIB_URL . '/admin-css-js/ui-css-js/dcg-ui-admin.js', false, DFCG_VER );
	wp_enqueue_script( 'dfcg_cluetip_js', DFCG_LIB_URL . '/admin-css-js/cluetip/jquery.cluetip.min.js', false, DFCG_VER );
}


/**
 * Callback to load Admin CSS
 *
 * Hooked to 'admin_enqueue_scripts' in dfcg_add_to_options_menu()
 *
 * @since 4.0
 *
 * @param string $pagehook, passed by admin_enqueue_scripts filter
 * @return null if not on DCG settings page, or enqueue styles if on DCG settings page
 */
function dfcg_load_admin_styles( $pagehook ) {
	
	global $current_screen;
	
	if( $pagehook !== $current_screen->id )
		return;
	
	wp_enqueue_style( 'dfcg_admin_css', DFCG_LIB_URL . '/admin-css-js/ui-css-js/dcg-ui-admin.css', false, DFCG_VER );
	wp_enqueue_style( 'dfcg_tabs_css', DFCG_LIB_URL . '/admin-css-js/tabs/dcg-tabs-ui.css', false, DFCG_VER );
	wp_enqueue_style( 'dfcg_cluetip_css', DFCG_LIB_URL . '/admin-css-js/cluetip/jquery.cluetip.css', false, DFCG_VER );
}


/**
 * Callback to display the Settings page
 *
 * Called by add_options_page() in dfcg_add_to_options_menu()
 *
 * @since 3.2
 * @updated 4.0
 * @global $dfcg_options array, db main options
 * @return includes settings page file for display
 */
function dfcg_do_settings_page(){
	
	// Needed because this is passed to dfcg_on_load_validation() in dcg-admin-ui-screen.php
	// @TODO Not sure about this as options are pulled from db again in screen file. Why global?
	global $dfcg_options;
	
	include_once( DFCG_LIB_DIR . '/includes/dcg-admin-ui-screen.php' );
}


/**
 * Filter callback to display a Settings link in main Plugin page in Dashboard
 *
 * Hooked to 'plugin_action_links' filter
 *
 * Puts the 'Settings' link in with Deactivate link in Plugins page
 *
 * @since 1.0
 *
 * @param array $links Default links shown in first column, main Dashboard Plugins page
 * @param string $file File name of main plugin file
 * @return array $links Modified array of links to be shown in first column, main Dashboard Plugins page
 */
function dfcg_filter_plugin_actions($links, $file){
	static $this_plugin;

	if( !$this_plugin ) $this_plugin = plugin_basename( __FILE__ );

	if( $file == DFCG_FILE_NAME ) {
		$settings_link = sprintf( '<a href="%s">%s</a>', 'admin.php?page=' . DFCG_FILE_HOOK, __( 'Settings', DFCG_DOMAIN ) );
		$links = array_merge( $links, array( $settings_link ) ); // after other links
	}
	return $links;
}


/**
 * Filter callback to display Plugin Meta Links in main Plugin page in Dashboard
 *
 * Hooked to 'plugin_row_meta filter' so only works for WP 2.8+
 *
 * Adds additional meta links in the plugin's info section in main Plugins Settings page
 * Note: these links will only appear if plugin is activated
 *
 * @since 3.0
 * @updated 4.0
 *
 * @param array $links Default links for each plugin row
 * @param string $file Plugins.php filehook, ie the plugin's file name
 * @return array $links Modified array of links for the DCG's plugin row
 */
function dfcg_plugin_meta( $links, $file ) {
 
	// Check we're only adding links to this plugin
	if( $file == DFCG_FILE_NAME ) {
	
		// Create DCG links
		$settings = sprintf( '<a href="%s">%s</a>', 'admin.php?page=' . DFCG_FILE_HOOK, __( 'Settings', DFCG_DOMAIN ) );
		$quick = sprintf( '<a href="%s" target="_blank">%</a>', DFCG_HOME . 'quick-start-guide/', __( 'Quick Start Guide', DFCG_DOMAIN ) );
		$config = sprintf( '<a href="%s" target="_blank">%s</a>', DFCG_HOME . 'configuration-guide/', __( 'Configuration Guide', DFCG_DOMAIN ) );
		$faq = sprintf( '<a href="%s" target="_blank">%s</a>', DFCG_HOME . 'faq/', __( 'FAQ', DFCG_DOMAIN ) );
		$docs = sprintf( '<a href="%s" target="_blank">%s</a>', DFCG_HOME . 'documentation/', __( 'Documentation', DFCG_DOMAIN ) );
		
		return array_merge(
			$links,
			array( $settings, $quick, $config, $faq, $docs )
			
		);
	}
 
	return $links;
}


/***** Admin Notices and other warnings *****/


/**
 * WPMS check message
 *
 * Helper function for WPMS warning message 
 * Used by dfcg_checks_plugins_page() and dfcg_checks_settings_page()
 *
 * @since 4.0
 * @return string $msg Additional message for WPMS when WP version is insufficient
 */
function dfcg_do_messages_wpms() {
	
	$msg = __( 'Please contact your Network Administrator.', DFCG_DOMAIN );
			
	return $msg;
}


/**
 * WP Version check message
 *
 * Helper function for WP version warning message 
 * Used by dfcg_checks_plugins_page() and dfcg_checks_settings_page()
 *
 * @since 4.0
 * @return string $msg Message when WP version is insufficient
 */
function dfcg_do_version_messages() {
			
	$msg = sprintf( '<strong>%s</strong> %s', __( 'DCG Warning!', DFCG_DOMAIN ), __( 'This version of Dynamic Content Gallery requires WordPress ', DFCG_DOMAIN) . DFCG_WP_VERSION_REQ . '+' );
		
	return $msg;
}





/**
 * Check if required WP version is installed
 * 
 * Used by dfcg_checks_plugins_page() and dfcg_checks_settings_page()
 *
 * @since 4.0
 * @return bool Returns true if WP minimum required version is installed
 */
function dfcg_check_version() {

	$version_ok = version_compare( get_bloginfo( "version" ), DFCG_WP_VERSION_REQ, '>=' );
	
	if( $version_ok )
		return true;
	else
		return false;
}




/**
 * Callback to do WP Version check AND check that theme has add_theme_support('post-thumbnails')
 *
 * Hooked to 'after_action_row_$plugin' filter
 *
 * This function prints warning messages in the relevant row of the table in main Plugins screen.
 * This function replaces dfcg_wp_version_check() deprecated in v4.0
 *
 * @uses dfcg_check_version()
 * @uses dfcg_do_version_messages()
 * @uses dfcg_do_messages_wpms()
 *
 * @since 3.2
 * @updated 4.0
 * @global object(array) $current_screen The current admin screen object
 * @return echos messages wrapped in necessary XHTML markup for display in Plugins table
 */	
function dfcg_checks_plugins_page() {

	global $current_screen;

	if( $current_screen->id !== "plugins" )
		return;
	
	// Do the checks
	$check = dfcg_check_version();
	
	
	if( $check )
		return; // All is good, nothing to do here...
	
	// Define markup
	$msg_tr = '<tr class="plugin-update-tr"><td class="plugin-update" colspan="3">';
	
	$msg_div_red = '<div class="update-message" style="background:#FFEBE8;border-color:#BB0000;">';
	
	$msg_div_def = '<div class="update-message">';
	
	$msg_end = '</div></td></tr>';
	
	
	// WP Version is not valid
	if( !$check ) {
	
		$msg = dfcg_do_version_messages();
		
		if( is_multisite() )
			$msg .= dfcg_do_messages_wpms();
			
		echo $msg_tr . $msg_div_red . $msg . $msg_end;
	}
	
}


/**
 * Callback to do WP Version check AND check that theme has add_theme_support('post-thumbnails')
 *
 * Hooked to 'admin_notices'
 *
 * This function prints Admin Notices warning messages at top of DCG Settings page.
 *
 * @uses dfcg_check_version()
 * @uses dfcg_do_version_messages()
 * @uses dfcg_do_messages_wpms()
 *
 * @since 4.0
 * @global object(array) $current_screen The current admin screen object
 * @return echos messages for checks which are false
 */	
function dfcg_checks_settings_page() {	
	global $current_screen;
	
	// Being polite, only showing the nag on DCG Settings page
	if( $current_screen->id !== DFCG_PAGEHOOK )
		return;
	
	// Do the checks
	$check = dfcg_check_version();
	
	
	if( $check )
		return; // All is good, nothing to do here...
		
	
	// Define markup
	$err_start = '<div class="error"><p>';
	$notice_start = '<div class="updated"><p>';
	$msg_end = '</p></div>';
		
	
	if( !$check ) {
		
		$msg = dfcg_do_version_messages();
		
		if( is_multisite() )
			$msg .= dfcg_do_messages_wpms();
					
		echo $err_start . $msg . $msg_end;
	}
	
	
	
}


/**
 * Creates a DCG upgrade nag in the DCG Settings page
 *
 * Hooked to 'admin_notices'
 *
 * Code is based on wp-admin/includes/update.php wp_plugin_update_row() function
 * Checks plugin API response for DCG file name then prints admin notice if new version is available
 * Version detection compares plugin file version number with wp.org SVN version
 * Only shows nag on DCG Settings page - let's be polite!
 *
 * @uses dfcg_base_settings()
 *
 * @since 4.0
 * @global object(array) $current_screen The current admin screen object
 * @return echos nag message with XHTML markup
 */
function dfcg_upgrade_nag() {
	
	global $current_screen;
	
	// Being polite, only showing the nag on DCG Settings page
	if( $current_screen->id !== DFCG_PAGEHOOK )
		return;
	
	$current = get_site_transient( 'update_plugins' );
	
	if ( !isset( $current->response[ DFCG_FILE_NAME ] ) )
		return;

	$settings = dfcg_base_settings();
	$name = $settings['dfcg_nice_name'];
		
	$r = $current->response[ DFCG_FILE_NAME ];
	
	$details_url = admin_url('plugin-install.php?tab=plugin-information&plugin=' . $r->slug . '&TB_iframe=true&width=600&height=500');
	
	printf( '<div class="error"><p><strong>%s</strong> ', __( 'DCG Notice: Please upgrade!', DFCG_DOMAIN ) );
	
	if ( !current_user_can( 'update_plugins' ) )
		printf( __('Version %1$s of the %2$s is now available. <a href="%3$s" class="thickbox" title="%2$s">View version %1$s Details</a>.'), $r->new_version, $name, esc_url($details_url) );
	
	else
		printf( __('Version %1$s of the %2$s is now available. <a href="%3$s" class="thickbox" title="%2$s">View version %1$s Details</a> or <a href="%4$s">upgrade automatically</a>.'), $r->new_version, $name, esc_url($details_url), wp_nonce_url('update.php?action=upgrade-plugin&plugin=' . DFCG_FILE_NAME, 'upgrade-plugin_' . DFCG_FILE_NAME) );
	
	echo '</p></div>';
}


/**
 * Filter callback to add image sizes to Media Uploader
 *
 * Hooked to 'image_size_names_choose'
 *
 * WP 3.3 adds a new filter 'image_size_names_choose' to
 * the list of image sizes which are displayed in the Media Uploader
 * after an image has been uploaded.
 * See image_size_input_fields() in wp-admin/includes/media.php
 *
 * This callback needs to return an associative array ($sizes) containing 'name' = > 'label'
 * Unfortunately, add_image_size only creates a 'name' not a 'label', therefore we take
 * the DCG image 'name' and remove the underscores to create a a nice looking 'label'.
 *
 * @since 4.0
 * @param array $sizes Default image sizes (associative array)
 * @global string $dfcg_main_hard Registered image size name for DCG Main image with hard crop
 * @global string $dfcg_main_boxr Registered image size name for DCG Main image with box resize
 * @global array $dfcg_options db main plugin options
 * @return array $sizes Default image sizes plus DCG Main sizes (associative array)
 */
function dfcg_filter_image_size_names_muploader( $sizes ) {
	
	global $dfcg_main_hard, $dfcg_main_boxr, $dfcg_options;
	
	if( $dfcg_options['add-media-sizes'] == 'false' ) return $sizes;
	
	$hard = str_replace('_', ' ', $dfcg_main_hard);
	$sizes[$dfcg_main_hard] = $hard . ' (hard crop)';
	
	$boxr = str_replace('_', ' ', $dfcg_main_boxr);
	$sizes[$dfcg_main_boxr] = $boxr. ' (box resize)';
	
	return $sizes;
}



/***** Options handling and upgrading *****/

/**
 * Build default options
 *
 * Contains the latest version's default options.
 * Populates the options on first install (not upgrade) and
 * when Settings Reset is performed.
 *
 * Used by the "upgrader" function dfcg_set_gallery_options().
 *
 * 89 options (3 are WP only)
 *
 * @since 3.2.2
 * @updated 4.0
 * @return array $default_options Array of default options for the plugin
 */
function dfcg_default_options() {
	
	$default_options = array(
		'image-url-type' => 'auto',					// Image Management: URL type for images: [full], [partial], [auto] (added 3.3)
		'imageurl' => '',							// WP ONLY. Image Management: URL for [partial] URL type
		'populate-method' => 'one-category',		// Populate method: [multi-option], [one-category], [id-method], [custom-post]
		'cat01' => '1',								// multi-option: the category IDs
		'cat02' => '1',								// multi-option: the category IDs
		'cat03' => '1',								// multi-option: the category IDs
		'cat04' => '1',								// multi-option: the category IDs
		'cat05' => '1',								// multi-option: the category IDs
		'cat06' => '1',								// multi-option: the category IDs
		'cat07' => '1',								// multi-option: the category IDs
		'cat08' => '1',								// multi-option: the category IDs
		'cat09' => '1',								// multi-option: the category IDs
		'off01' => '1',								// multi-option: the post select
		'off02' => '1',								// multi-option: the post select
		'off03' => '1',								// multi-option: the post select
		'off04' => '1',								// multi-option: the post select
		'off05' => '1',								// multi-option: the post select
		'off06' => '',								// multi-option: the post select
		'off07' => '',								// multi-option: the post select
		'off08' => '',								// multi-option: the post select
		'off09' => '',								// multi-option: the post select
		'cat-display' => '1',						// one-category: the ID of the selected category - since 2.3
		'posts-number' => '5',						// one-category: the number of posts to display - since 2.3
		'ids-selected' => '',						// ID: Page/Post ID's in comma separated list - since 2.3 (renamed in 3.3)
		'id-sort-control' => 'false',				// ID: Allow custom sort of images using _dfcg-sort: bool
		'cpt-name' => '',							// post-type: the Custom Post type name, eg ade_products (user selected)
		'cpt-posts-number' => '5',					// post-type: the number of posts to display (user selected)
		'cpt-tax-and-term' => '',					// post-type: the taxonomy and term to display posts from (eg my_products=guitars)
		'cpt-tax-name' => '',						// post-type: Name of selected taxonomy (calculated)
		'cpt-term-name' => '',						// post-type: Name of selected term within selected taxonomy (calculated)
		'cpt-term-id' => '',						// post-type: ID of selected term (calculated)
		'defimgid' => '',							// WP ONLY. ID Method: URL for a default image
		'defimgfolder' => '',						// WP ONLY. URL for default image folder - Multi-option, One-category, Custom-post
		'defimagedesc' => '',						// Desc: default description - only works if [manual]
		'desc-method' => 'auto',					// Desc: Select how to display descriptions: [manual],[auto],[none],[excerpt]
		'max-char' => '100',						// Desc: No. of characters for custom excerpt
		'more-text' => '[more]',					// Desc: 'More' text for custom excerpt
		'gallery-width' => '460',					// all methods: CSS
		'gallery-height' => '250',					// all methods: CSS
		'slide-height' => '50',						// all methods: CSS - mootools only
		'gallery-border-thick' => '0',				// all methods: CSS
		'gallery-border-colour' => '#000000',		// all methods: CSS
		'slide-h2-size' => '12',					// all methods: CSS
		'slide-h2-padtb' => '0',					// all methods: CSS
		'slide-h2-padlr' => '0',					// all methods: CSS
		'slide-h2-marglr' => '5',					// all methods: CSS
		'slide-h2-margtb' => '2',					// all methods: CSS
		'slide-h2-colour' => '#FFFFFF',				// all methods: CSS
		'slide-p-size' => '11',						// all methods: CSS
		'slide-p-padtb' => '0',						// all methods: CSS
		'slide-p-padlr' => '0',						// all methods: CSS
		'slide-p-marglr' => '5',					// all methods: CSS
		'slide-p-margtb' => '2',					// all methods: CSS
		'slide-p-colour' => '#FFFFFF',				// all methods: CSS
		'slide-h2-weight' => 'bold',				// all methods: CSS [bold], [normal]
		'slide-p-line-height' => '14',				// all methods: CSS
		'slide-overlay-color' => '#000000',			// all methods: CSS
		'slide-p-a-color' => '#FFFFFF',				// all methods: More text CSS
		'slide-p-ahover-color' => '#FFFFFF',		// all methods: More text CSS
		'slide-p-a-weight' => 'normal',				// all methods: More text CSS: [bold], [normal]
		'slide-p-ahover-weight' => 'bold',			// all methods: More text CSS: [bold], [normal]
		'gallery-background' => '#000000',			// all methods: CSS (added 3.3.4)
		'reset' => '0',								// Reset: Reset options state
		'limit-scripts' => 'homepage',				// Load Scripts: Select scripts loading: [homepage],[pagetemplate],[page],[other]
		'page-filename' => '',						// Load Scripts: Specify a Page Template filename, for loading scripts
		'page-ids' => '',							// Load Scripts: ordinary page ID numbers
		'scripts' => 'mootools',					// JS option: Selects js framework: [mootools], [jquery]
		'timed' => 'true',							// JS option
		'delay' => '9000',							// JS option
		'showCarousel' => 'true',					// JS option
		'showInfopane' => 'true',					// JS option 
		'slideInfoZoneSlide' => 'true',				// JS option - mootools only
		'slideInfoZoneOpacity' => '0.7',			// JS option
		'carouselMinimizedOpacity' => '0.4',		// JS option - mootools only (added 3.4)
		'textShowCarousel' => 'Featured Articles',	// JS option
		'defaultTransition' => 'fade',				// JS option - mootools only
		'mootools' => '0',							// JS option: Toggle on/off Mootools loading - mootools only
		'showArrows' => 'true',						// JS option: added 3.3.3
		'slideInfoZoneStatic' => 'false',			// JS option: (jquery only) added in 3.3.4 with v2.6 jquery script
		'errors' => 'false',						// Tools: Error reporting on/off
		'column-img' => 'true',						// Tools: Show column _dfcg-image in edit screens
		'column-desc' => 'true',					// Tools: Show column _dfcg_desc in edit screens
		'column-sort' => 'true',					// Tools: Show column _dfcg-sort in edit screens
		'column-feat-img' => 'true',				// Tools: Show column Featured Image in edit screens
		'thumb-type' => 'featured-image',			// Thumbs: [featured-image] or [legacy] - mootools only
		'crop' => 'true',							// Feat Image crop hard/box resize [true],[false]
		'desc-man-link' => 'true',					// Append Read More link to manual descriptions
		'add-media-sizes' => 'true',				// Tools: add DCG image sizes to Media Uploader
		'size-change' => 'false'					// Validation helper for DCG gallery size change
	);
	
	// Return options array for use elsewhere
	return $default_options;
}


/**
 * Function for loading and upgrading options
 *
 * Loads options on 'admin_menu' hook.
 * Completely re-written - changed to "incremental" upgrading in v3.3.3
 *
 * Called by dfcg_add_page() which is hooked to 'admin_menu'
 *
 * In 2.3 - Change 'imagepath' is deprecated, replaced by 'imageurl'
 * In 2.3 - Change 'defimagepath' is deprecated, replaced by 'defimgmulti' and 'defimgonecat'
 * In 2.3 - Total options = 29 orig options + 30 new options added = 59
 *
 * In RC2 - Change: nourl value of 'image-url-type' is deprecated
 *
 * In RC3 - Added 2: 'posts-column', 'pages-column'
 * In RC3 - Total options = 59 + 2 = 61
 *
 * In RC4 - Added 13: 'posts-desc-column', 'pages-desc-column', 'just-reset', 'scripts', +9 jQuery options
 * In RC4 - Change: 'part' value of 'image-url-type' to 'partial'
 * In RC4 - Total options = 61 + 13 = 74
 *
 * In 3.1 - Added 7: 'desc-method','max-char','more-text','slide-p-a-color','slide-p-ahover-color','slide-p-a-weight','slide-p-ahover-weight'
 * In 3.1 - Total options = 74 + 7 = 81
 *
 * In 3.2 - Change: 'desc-method' can now have three values - auto, manual, none
 * In 3.2 - Added 2: 'pages-sort-column', 'pages-sort-control'
 * In 3.2 - Total options = 81 + 2 = 83
 *
 * In 3.2.2 - Added 1: 'page-ids'
 * In 3.2.2 - Change: new value 'page' added to 'limit-scripts' option
 * In 3.2.2 - Total options = 83 + 1 = 84
 *
 * In 3.3 - Change: new value 'auto' added to 'image-url-type' option
 * In 3.3 - Change: 'pages-selected' option renamed as 'ids-selected' (handles Post and Page IDs)
 * In 3.3 - Change: 'defimgpages' option renamed as 'defimgid'
 * In 3.3 - Change: 'pages-sort-control' option renamed as 'id-sort-control'
 * In 3.3 - Change: 'pages' value of "populate-method" is changed to 'id-method'
 * In 3.3 - Deleted 6: 'nav-theme', 'pause-on-hover', 'transition-speed', 'fade-panels', 'slide-overlay-position', 'gallery-background'
 * In 3.3 - Added 5: 'thumb-type' 'defimgcustompost', 'custom-post-type', 'custom-post-type-number', 'custom-post-type-tax'
 * In 3.3 - Change: 'custom-post' value added to 'populate-method' option
 * In 3.3 - Total options = 84 - 6 + 5 = 83
 *
 * In 3.3.1 - Corrected '==' syntax to '=' for new options that should have been added in 3.3. What an idiot,eh?
 *
 * In 3.3.2 - Added 1: 'showArrows' for mootools and jQuery
 * In 3.3.2 - Total options = 83 + 1 = 84
 *
 * In 3.3.3 - Total options = 84
 *
 * In 3.3.4 - Added 'slideInfoZoneStatic' options for fixed or sliding Slide Pane with jQuery
 * In 3.3.4 - Added 'gallery-background' option - mootools and jquery
 * In 3.3.4 - Total options = 84 + 2 = 86
 *
 * In 4.0 - Added "excerpt" value to "desc-method" option
 * In 4.0 - Added 3: 'cpt-tax-name', 'cpt-term-name', 'cpt-term-id'
 * In 4.0 - Added 6: 'column-feat-img', 'carouselMinimizedOpacity', 'desc-man-link', 'add-media-sizes', 'crop', 'size-change'
 * In 4.0 - Removed 3: 'just-reset', 'pages-column', 'pages-desc-column'
 * In 4.0 - Removed 4: 'homeurl', 'defimgmulti', 'defimgonecat', 'defimgcustompost'
 * In 4.0 - Added 1: 'defimgfolder'
 * In 4.0 - Total options = 86 + 3 + 6 - 3 + 4 - 1 = 89
 *
 * @uses dfcg_default_options()
 *
 * @since 3.2.2
 * @updated 4.0
 * @return if DCG version up to date, returns NULL, otherwise upgrades plugin's options in the db
 */
function dfcg_set_gallery_options() {
	
	// Get current version number (first introduced in 3.0 beta / 2.3)
	$existing_version = get_option('dfcg_version');
	
	// Existing version is same as this version - nothing to do here...
	if( $existing_version == DFCG_VER )
		return;
	
	
	/***** Ok, we need to do something - let's prepare some stuff *****/
	
	// Clean up version numbers, otherwise version_compare won't always work as expected
	if( $existing_version == '3.0 RC2' )
		$existing_version = '2.3.2';
		
	if( $existing_version == '3.0 RC3' )
		$existing_version = '2.3.3';
		
	if( $existing_version == '3.0 RC4' )
		$existing_version = '2.3.4';
	
	$existing_opts = get_option( 'dfcg_plugin_settings' );



	/***** Clean install - it's a wasteland here *****/
	if ( empty( $existing_version ) && empty( $existing_opts ) && empty( $utilities ) ) {			
		
		$new_opts = dfcg_default_options();
		
		add_option( 'dfcg_plugin_settings', $new_opts );
		add_option( 'dfcg_version', DFCG_VER );
				
		return;
	}
	
	
	
	/***** Logic check in case $existing_version exists but there are no $existing_opts - eg bad uninstall *****/
	
	if( $existing_version && empty( $existing_opts ) ) {
		
		$new_opts = dfcg_default_options(); // Clean reinstall
		
		add_option( 'dfcg_plugin_settings', $new_opts );
		update_option( 'dfcg_version', DFCG_VER );
		
		// See if pre v4.0 postmeta upgrade db option is lying around
		$postmeta = get_option( 'dfcg_plugin_postmeta_upgrade' );
		if( $postmeta ) {
			delete_option( 'dfcg_plugin_postmeta_upgrade'); // Clear out old
		}
		
		return;
	}
	
	
	
	/***** Logic check in case $existing_version doesn't exist but there are $existing_opts *****/
	
	if( empty( $existing_version ) && $existing_opts ) {
		$existing_version = '2.2'; // Force upgrades to be run
	}
	
	
	/***** Upgrade to 2.3 from 2.2 *****/
	if ( version_compare($existing_version, '2.3', '<') ) {
	
		// 29 options
		//$existing = get_option( 'dfcg_plugin_settings' );
		
		// Add 1 new option - Assign old imagepath to new imageurl
		$existing_opts['imageurl'] = $existing_opts['homeurl'] . $existing_opts['imagepath'];
		
		// Add 2 new options - Assign old defimagepath to defimgmulti and defimgonecat
		$existing_opts['defimgmulti'] = $existing_opts['homeurl'] . $existing_opts['defimagepath'];
		$existing_opts['defimgonecat'] = $existing_opts['homeurl'] . $existing_opts['defimagepath'];
		
		// Delete 2 options
		unset($existing_opts['imagepath']);
		unset($existing_opts['defimagepath']);
		
		
		// Add new 29 options
		$new_opts = array(
			'populate-method' => 'multi-option',
			'cat-display' => '1',
			'posts-number' => '5',
			'pages-selected' => '',
			'image-url-type' => 'partial',
			'defimgpages' => '',
			'slide-h2-padtb' => '0',
			'slide-h2-padlr' => '0',
			'slide-p-padtb' => '0',
			'slide-p-padlr' => '0',
			'limit-scripts' => 'homepage',
			'page-filename' => '',
			'timed' => 'true',
			'delay' => '9000',
			'showCarousel' => 'true',
			'showInfopane' => 'true',
			'slideInfoZoneSlide' => 'true',
			'slideInfoZoneOpacity' => '0.7',
			'textShowCarousel' => 'Featured Articles',
			'defaultTransition' => 'fade',
			'cat06' => '1',
			'cat07' => '1',
			'cat08' => '1',
			'cat09' => '1',
			'off06' => '',
			'off07' => '',
			'off08' => '',
			'off09' => '',
			'errors' => 'true'
			);
		
		// Total options = 29 + 1 + 2 - 2 + 29 = 59
		$updated = wp_parse_args( $existing_opts, $new_opts );
		
		update_option( 'dfcg_plugin_settings', $updated );
	}
	
	
	
	/***** Upgrade to 3.0 RC2 (2.3.2) from 2.3 (aka 3.0 beta) *****/
	if ( version_compare($existing_version, '2.3.2', '<') ) {
	
		// 59 options
		$existing_opts = get_option( 'dfcg_plugin_settings' );
		
		// Value 'nourl' is deprecated
		if( $existing_opts['image-url-type'] == 'nourl' )
			$existing_opts['image-url-type'] = 'part';

		// Total options = 59
		update_option( 'dfcg_plugin_settings', $existing_opts );
	}
	
	
	
	/***** Upgrade to 3.0 RC3 (2.3.3) from 3.0 RC2 *****/
	if ( version_compare($existing_version, '2.3.3', '<') ) {
	
		// 59 options
		$existing_opts = get_option( 'dfcg_plugin_settings' );
	
		// Add new 2 options
		$new_opts = array(
			'posts-column' => 'true',
			'pages-column' => 'true'
			);
		
		// Total options = 59 + 2 = 61
		$updated = wp_parse_args( $existing_opts, $new_opts );
		
		update_option( 'dfcg_plugin_settings', $updated );
	}
	
	
	
	/***** Upgrade to 3.0 RC4 (2.3.4) from 3.0 RC3 *****/
	if ( version_compare($existing_version, '2.3.4', '<') ) {
	
		// 61 options
		$existing_opts = get_option( 'dfcg_plugin_settings' );
		
		// 'part' changed to 'partial'
		if( $existing_opts['image-url-type'] == 'part' )
			$existing_opts['image-url-type'] = 'partial';
		
		// Add new 13 options
		$new_opts = array(
			'posts-desc-column' => 'true',
			'pages-desc-column' => 'true',
			'just-reset' => 'false',
			'scripts' => 'mootools',
			'slide-h2-weight' => 'bold',							
			'slide-p-line-height' => '14',
			'slide-overlay-color' => '#000000',
			'slide-overlay-position' => 'bottom',
			'transition-speed' => '1500',
			'nav-theme' => 'light',
			'pause-on-hover' => 'true',
			'fade-panels' => 'true',
			'gallery-background' => '#000000'
		);
		
		// Total options = 61 + 13 = 74
		$updated = wp_parse_args( $existing_opts, $new_opts );
		
		update_option( 'dfcg_plugin_settings', $updated );
	}
	
	
	
	/***** Upgrade to 3.0 from 3.0 RC4 *****/
	if ( version_compare($existing_version, '3.0', '<') ) {
		
		// Nothing to do here...
	}
	
	
	
	/***** Upgrade to 3.1 from 3.0 *****/
	if ( version_compare($existing_version, '3.1', '<') ) {
	
		// 74 options
		$existing_opts = get_option( 'dfcg_plugin_settings' );
		
		// Add new 7 options
		$new_opts = array(
			'desc-method' => 'manual',
			'max-char' => '100',
			'more-text' => '[more]',
			'slide-p-a-color' => '#FFFFFF',
			'slide-p-ahover-color' => '#FFFFFF',
			'slide-p-a-weight' => 'normal',
			'slide-p-ahover-weight' => 'bold'
			);
			
		// Total options = 74 + 7 = 81
		$updated = wp_parse_args( $existing_opts, $new_opts );
		
		update_option( 'dfcg_plugin_settings', $updated );
	}
		
	
	
	/***** Upgrade to 3.2 from 3.1 *****/
	if ( version_compare($existing_version, '3.2', '<') ) {
	
		// 81 options
		$existing_opts = get_option( 'dfcg_plugin_settings' );
		
		// Add new 2 options
		$new_opts = array(
			'pages-sort-column' => 'true',
			'pages-sort-control' => 'false'
			);
		
		// Total options = 81 + 2 = 83
		$updated = wp_parse_args( $existing_opts, $new_opts );
		
		update_option( 'dfcg_plugin_settings', $updated );
	}
	
	
	
	/***** Upgrade to 3.2.1 from 3.2 *****/
	if ( version_compare($existing_version, '3.2.1', '<') ) {
		
		// Nothing to do here...
	}
	
	
	
	/***** Upgrade to 3.2.2 from 3.2.1 *****/
	if ( version_compare($existing_version, '3.2.2', '<') ) {
	
		// 83 options
		$existing_opts = get_option( 'dfcg_plugin_settings' );
		
		// Add new 1 option
		$new_opts = array(
			'page-ids' => ''
			);
	
		// Total options = 83 + 1 = 84
		$updated = wp_parse_args( $existing_opts, $new_opts );
		
		update_option( 'dfcg_plugin_settings', $updated );
	}
	
	
	
	/***** Upgrade to 3.2.3 from 3.2.2 *****/
	if ( version_compare($existing_version, '3.2.3', '<') ) {
		
		// Nothing to do here...
	}
	
	
	
	/***** Upgrade to 3.3 from 3.2.3 *****/
	if ( version_compare($existing_version, '3.3', '<') ) {
	
		// 84 options
		$existing_opts = get_option( 'dfcg_plugin_settings' );
		
				
		// Add new 3 options = renamed old options
		$existing_opts['ids-selected'] = $existing_opts['pages-selected'];
		$existing_opts['defimgid'] = $existing_opts['defimgpages'];
		$existing_opts['id-sort-control'] = $existing_opts['pages-sort-control'];
		
		// 'pages' changed to 'id-method'
		if( $existing_opts['populate-method'] == 'pages' ) {
			$existing_opts['populate-method'] = 'id-method';
		}
		
		// Delete 3 deprecated options (renamed in 3.3)
		unset( $existing_opts['pages-selected'] );
		unset( $existing_opts['defimgpages'] );
		unset( $existing_opts['pages-sort-control'] );
		
		// Delete 6 deprecated options
		unset($existing_opts['nav-theme']);
		unset($existing_opts['pause-on-hover']);
		unset($existing_opts['transition-speed']);
		unset($existing_opts['fade-panels']);
		unset($existing_opts['slide-overlay-position']);
		unset($existing_opts['gallery-background']);
		
		// Add new 5 options
		$new_opts = array(
			'thumb-type' => 'legacy',
			'custom-post-type' => '',
			'custom-post-type-tax' => '',
			'custom-post-type-number' => '5',
			'defimgcustompost' => ''
			);

		// Total options = 84 + 3 - 3 - 6 + 5 = 83
		$updated = wp_parse_args( $existing_opts, $new_opts );
		
		update_option( 'dfcg_plugin_settings', $updated );
	}
		
		
	
	/***** Upgrade to 3.3.1 from 3.3 *****/
	if ( version_compare($existing_version, '3.3.1', '<') ) {
		
		// Nothing to do here...
	}
	
	
	
	/***** Upgrade to 3.3.2 from 3.3.1 *****/
	if ( version_compare($existing_version, '3.3.2', '<') ) {
	
		// 83 options
		$existing_opts = get_option( 'dfcg_plugin_settings' );
	
		// Add new 1 options
		$new_opts = array(
			'showArrows' => 'true'
			);
		
		// Total options = 83 + 1 = 84
		$updated = wp_parse_args( $existing_opts, $new_opts );
		
		update_option( 'dfcg_plugin_settings', $updated );
	}
	
	
	
	/***** Upgrade to 3.3.3 from 3.3.2 *****/
	if ( version_compare($existing_version, '3.3.3', '<') ) {
	
		// Nothing to do here...
	}
	
	
	
	/***** Upgrade to 3.3.4 from 3.3.3 *****/
	if ( version_compare($existing_version, '3.3.4', '<') ) {
	
		// 84 options
		$existing_opts = get_option( 'dfcg_plugin_settings' );
	
		// Add new 2 option
		$new_opts = array(
			'slideInfoZoneStatic' => 'false',
			'gallery-background' => '#000000'
			);
		
		// Total options = 84 + 2 = 86
		$updated = wp_parse_args( $existing_opts, $new_opts );
		
		update_option( 'dfcg_plugin_settings', $updated );
	}
	
	
	
	/***** Upgrade to 3.3.5 from 3.3.4 *****/
	if ( version_compare($existing_version, '3.3.5', '<') ) {
	
		// Nothing to do here...
	}
	
	
	
	/***** Upgrade to 3.3.6 from 3.3.5 *****/
	if ( version_compare($existing_version, '3.3.6', '<') ) {
	
		// Nothing to do here...
	}


	
	
	/***** Upgrade to 4.0 from 3.3.6 *****/
	if ( version_compare($existing_version, '4.0', '<') ) {
	
		// 86 options
		$existing_opts = get_option( 'dfcg_plugin_settings' );
		
		// Add 1 new, and reassign values to it = 87
		$new_opts['defimgfolder'] = '';
		if( $existing_opts['populate-method'] == 'multi-option' )
			$new_opts['defimgfolder'] = $existing_opts['defimgmulti'];
			
		if( $existing_opts['populate-method'] == 'one-category' )
			$new_opts['defimgfolder'] = $existing_opts['defimgonecat'];
			
		if( $existing_opts['populate-method'] == 'custom-post' )
			$new_opts['defimgfolder'] = $existing_opts['defimgcustompost'];
			
		// Delete 4 = 83
		unset( $existing_opts['defimgmulti'] );
		unset( $existing_opts['defimgonecat'] );
		unset( $existing_opts['defimgcustompost'] );
		unset( $existing_opts['homeurl'] );
		
		// Option renaming: add 3 new = 86
		$new_opts['cpt-name'] = $existing_opts['custom-post-type'];
		$new_opts['cpt-posts-number'] = $existing_opts['custom-post-type-number'];
		$new_opts['cpt-tax-and-term'] = $existing_opts['custom-post-type-tax'];
		
		// Option renaming: delete 3 old = 83
		unset( $existing_opts['custom-post-type'] );
		unset( $existing_opts['custom-post-type-number'] );
		unset( $existing_opts['custom-post-type-tax'] );
		
		//renaming: add 3 new = 86
		$new_opts['column-img'] = $existing_opts['posts-column'];
		$new_opts['column-desc'] = $existing_opts['posts-desc-column'];
		$new_opts['column-sort'] = $existing_opts['pages-sort-column'];
		// renaming: delete 5 old = 81
		unset( $existing_opts['posts-column'] );
		unset( $existing_opts['posts-desc-column'] );
		unset( $existing_opts['pages-column'] );
		unset( $existing_opts['pages-desc-column'] );
		unset( $existing_opts['pages-sort-column'] );
		
		// Re-assign a value = 81
		if( $existing_opts['thumb-type'] == "post-thumbnails" )
			$existing_opts['thumb-type'] = "featured-image";
	
		// Add 3 new = 84
		if( $existing_opts['cpt-tax-and-term'] == 'all' || empty( $existing_opts['cpt-tax-and-term'] ) ) {
			
			$new_opts['cpt-tax-name'] = '';
			$new_opts['cpt-term-name'] = '';
			$new_opts['cpt-term-id'] = '';
			
		} else {
		
			// Split cpt-tax-and-term into assoc. array
			$temp = wp_parse_args( $existing_opts['cpt-tax-and-term'] );
		
			// Taxonomy and term names
			$tax_name = key($temp);
			$term_name = $temp[$tax_name];
		
			// Get term object, get_term_by($field, $value, $taxonomy)
			$term = get_term_by( 'name', $term_name, $tax_name);
		
			$new_opts['cpt-tax-name'] = $tax_name;
			$new_opts['cpt-term-name'] = $term_name;
			$new_opts['cpt-term-id'] = $term->term_id;
		
		}
		
		// Add 6 new = 90		
		$new_opts['column-feat-img'] = 'true';
		$new_opts['crop'] = 'true';
		$new_opts['carouselMinimizedOpacity'] = '0.4';
		$new_opts['desc-man-link'] = 'true';
		$new_opts['add-media-sizes'] = 'false';
		$new_opts['size-change'] = 'false';
		
		// Remove 1 option = 89
		unset( $existing_opts['just-reset'] );
		
		// Total options = 89
		$updated = wp_parse_args( $existing_opts, $new_opts );
		
		update_option( 'dfcg_plugin_settings', $updated );
		
		// Deal with deprecated $dfcg_postmeta_upgrade
		delete_option( 'dfcg_plugin_postmeta_upgrade' );
		
	}
	
	// FINALLY, Update version no. in the db
	update_option('dfcg_version', DFCG_VER );
}