<?php
/**
* Displays Settings Page
*
* @copyright Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
* @package dynamic_content_gallery
* @version 3.2
*
* @info Settings page for Wordpress and Wordpress Mu.
*
* 	All UI functions on this page are defined in dfcg-admin-ui-functions.php
*	dfcg_load_textdomain()		- defined in dfcg-admin-core.php
*	dfcg_options_js()			- defined in dfcg-admin-ui-js.php
*	dfcg_on_load_validation()	- defined in dfcg-admin-ui-validation.php
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}


// Load text domain
dfcg_load_textdomain();

// Load Settings Page JS
dfcg_options_js();

dfcg_on_load_validation($dfcg_options); // Run Settings validation checks on page load
?>

<div class="wrap" id="sgr-style">

	<?php screen_icon('options-general');// Display icon next to title ?>
	
	<h2><?php _e('Dynamic Content Gallery Configuration', DFCG_DOMAIN); ?></h2>
	
	<div class="metabox-holder">
		
<?php
/* Output the Settings Page boxes */
dfcg_ui_intro_menu();
?>

		<form method="post" action="options.php">

<?php
// Settings API, nonces etc
settings_fields('dfcg_plugin_settings_options');

// Image File management
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