<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.1
*
*	Options page for Wordpress and Wordpress Mu.
*
*	All UI functions on this page are defined in dfcg-admin-ui-functions.php
*	dfcg_load_textdomain() is defined in dynamic-gallery-plugin.php
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}


// Load text domain
dfcg_load_textdomain();

// Load Settings Page JS and CSS
dfcg_options_css_js();
?>

<div class="wrap" id="sgr-style">

	<?php screen_icon('options-general');// Display icon next to title ?>
	
	<h2><?php _e('Dynamic Content Gallery Configuration', DFCG_DOMAIN); ?></h2>
	
	<div class="metabox-holder">
	
		<form method="post" action="options.php">
	
<?php
// Settings API, nonces etc
settings_fields('dfcg_plugin_settings_options');

$dfcg_options = get_option('dfcg_plugin_settings'); // Load Options

dfcg_on_load_validation($dfcg_options); // Run Settings validation checks on page load

/* Output the Settings Page boxes */
dfcg_ui_intro_menu();

if ( function_exists('wpmu_create_blog') ) {
	// Uploading images - WPMU ONLY
	dfcg_ui_create_wpmu();
} else {
	// Image File Management - WP ONLY
	dfcg_ui_1_image_wp();
}

// Gallery Method
dfcg_ui_2_method();

// Multi-Option
dfcg_ui_multi();
if ( !function_exists('wpmu_create_blog') ) {
	// Default images - WP ONLY
	dfcg_ui_multi_wp();
}
// Multi-Option end box
dfcg_ui_buttons();

// One Category
dfcg_ui_onecat();
if ( !function_exists('wpmu_create_blog') ) {
	// Default images - WP ONLY
	dfcg_ui_onecat_wp();
}
// One Category end box
dfcg_ui_buttons();

// Pages
dfcg_ui_pages();
if ( !function_exists('wpmu_create_blog') ) {
	// Default image - WP ONLY
	dfcg_ui_pages_wp();
}
// Pages box end
dfcg_ui_buttons();

// Default Desc
dfcg_ui_defdesc();

// Gallery CSS
dfcg_ui_css();

// Javascript Framework options
dfcg_ui_js_framework();

// Javascript configuration options
dfcg_ui_javascript();

// Restrict Scripts
dfcg_ui_restrict_scripts();

// Error Messages
dfcg_ui_errors();

// Add Edit Posts/Pages columns
dfcg_ui_columns();

if ( function_exists('wpmu_create_blog') ) {
	// Hidden fields - WPMU ONLY
	dfcg_ui_hidden_wpmu();
} else {
	// Hidden fields - WP ONLY
	dfcg_ui_hidden_wp();
}

// Reset and End
dfcg_ui_reset_end();
?>
	
			<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>
		</form>
	
<?php	// Credits
		dfcg_ui_credits();
?>
	</div><!-- end meta-box holder -->
	
</div><!-- end sgr-style wrap -->