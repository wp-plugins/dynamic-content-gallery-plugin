<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.0 RC4
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


<div class="wrap" id="sgr-style"><a name="top"></a>

	<?php screen_icon('options-general');// Display icon next to title ?>
	
	<h2><?php _e('Dynamic Content Gallery Configuration', DFCG_DOMAIN); ?></h2>
	
	<form method="post" action="options.php">
	
		<?php settings_fields('dfcg_plugin_settings_options'); ?>
		
		<?php $dfcg_options = get_option('dfcg_plugin_settings'); // Load Options ?>
		
		<?php dfcg_on_load_validation($dfcg_options); // Run Settings validation checks on page load ?>
		
		<fieldset name="dynamic_content_gallery" class="options">
		
		<div class="metabox-holder">
			
			<div class="postbox">
				<h3><?php _e("General Information:", DFCG_DOMAIN); ?></h3>
				<div class="inside">
					<div style="float:left;width:690px;">
						<p><?php _e("Please read through this page and configure the plugin. Some Settings are Required, others are Optional, depending on how you want to configure the gallery.", DFCG_DOMAIN); ?> <em><?php _e("Use the links below to jump to the relevant section on this page:", DFCG_DOMAIN); ?></em></p>
						<p>
						<ul>
							<li><a href="#image-file"><?php _e("1. Image file management (REQUIRED)", DFCG_DOMAIN); ?></a></li>
							<li><a href="#gallery-method"><?php _e("2. Gallery Method (REQUIRED)", DFCG_DOMAIN); ?></a></li>
							<li><a href="#multi-option"><?php _e("2.1 MULTI OPTION Settings", DFCG_DOMAIN); ?></a> (<em><?php _e('Required if you selected Multi Option in <a href="#gallery-method">Gallery Method</a>', DFCG_DOMAIN); ?></em>)</li>
							<li><a href="#one-category"><?php _e("2.2 ONE CATEGORY Settings", DFCG_DOMAIN); ?></a> (<em><?php _e('Required if you selected One Category in <a href="#gallery-method">Gallery Method</a>', DFCG_DOMAIN); ?></em>)</li>
							<li><a href="#pages-method"><?php _e("2.3 PAGES Settings", DFCG_DOMAIN); ?></a> (<em><?php _e('Required if you selected Pages in <a href="#gallery-method">Gallery Method</a>', DFCG_DOMAIN); ?></em>)</li>
							<li><a href="#default-desc"><?php _e("3. Default description (OPTIONAL)", DFCG_DOMAIN); ?></a></li>
							<li><a href="#gallery-css"><?php _e("4. Gallery size and CSS options (REQUIRED)", DFCG_DOMAIN); ?></a></li>
							<li><a href="#gallery-js-scripts"><?php _e("5. Javascript framework selection (OPTIONAL)", DFCG_DOMAIN); ?></a></li>
							<li><a href="#gallery-js"><?php _e("6. Javascript configuration options (OPTIONAL)", DFCG_DOMAIN); ?></a></li>
							<li><a href="#restrict-scripts"><?php _e("7. Restrict script loading (RECOMMENDED)", DFCG_DOMAIN); ?></a></li>
							<li><a href="#error-messages"><?php _e("8. Error message options (OPTIONAL)", DFCG_DOMAIN); ?></a></li>
							<li><a href="#custom-columns"><?php _e("9. Add Custom Field columns to Posts and Pages Edit screen (OPTIONAL)", DFCG_DOMAIN); ?></a></li>
						</ul>
						</p>
					
						<?php dfcg_ui_intro_text(); ?>
					
					</div>
					
					<div class="postbox" id="sgr-info">
						<?php dfcg_ui_sgr_info(); ?>
					</div>
					
					<div style="clear:both;"></div>
				</div>
			</div>
			
			<?php
			/* Output the Settings Page boxes */
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
	
	// Credits
	dfcg_ui_credits();
	?>
</div>