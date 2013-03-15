<?php
/**
 * Displays Settings Page
 *
 * This file is included by dfcg_options_page() which is hooked to admin_menu
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2013
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * @info Settings page for Wordpress and Wordpress MS.
 *
 * Note that Help tab is only loaded if not in WP 3.3+
 * Note that local scope applies because this page is included by a function
 *
 * All UI functions on this page are defined in dcg-admin-ui-functions.php
 * dfcg_load_textdomain()		- defined in dcg-admin-core.php
 * dfcg_base_settings()			- defined in dcg-admin-core.php
 * dfcg_on_load_validation()	- defined in dcg-admin-ui-validation.php
 *
 * @since 3.0
 * @updated 4.0
 */


/**
 * Prevent direct access to this file
 */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}


// Load text domain
dfcg_load_textdomain();

// Grab base settings using helper function
$dfcg_base = dfcg_base_settings();

// Pull options from db - added in 3.3.1 to solve new options issue
$dfcg_options = get_option( $dfcg_base['dfcg_option_name'] );

 // Run Settings validation checks on page load
dfcg_on_load_validation( $dfcg_options );
?>

<div class="wrap" id="dfcg-settings-page-wrap">

	<?php screen_icon('options-general');// Display icon next to title ?>
	
	<h2><?php echo $dfcg_base['dfcg_page_title']?></h2>
	
	<p><strong><?php _e('Version: ', DFCG_DOMAIN); ?><?php echo DFCG_VER; ?></strong></p>
		
	<form method="post" action="options.php">

	<?php settings_fields( $dfcg_base['dfcg_option_name'] ); // Settings API, nonces etc ?>
	
	<div id="dfcg-tabs">
		<ul id="dfcg-tab-titles">
			<li id="dfcg-tab-general"><a href="#dfcg-panel-general"><?php _e('General', DFCG_DOMAIN) ?></a></li>
			<li id="dfcg-tab-gallery"><a href="#dfcg-panel-gallery"><?php _e('Gallery Method', DFCG_DOMAIN) ?></a></li>
			<li id="dfcg-tab-image"><a href="#dfcg-panel-image"><?php _e('Image Management', DFCG_DOMAIN) ?></a></li>
			<li id="dfcg-tab-desc"><a href="#dfcg-panel-desc"><?php _e('Descriptions', DFCG_DOMAIN) ?></a></li>
			<li id="dfcg-tab-css"><a href="#dfcg-panel-css"><?php _e('Gallery CSS', DFCG_DOMAIN) ?></a></li>
			<li id="dfcg-tab-javascript"><a href="#dfcg-panel-javascript"><?php _e('Javascript Options', DFCG_DOMAIN) ?></a></li>
			<li id="dfcg-tab-scripts"><a href="#dfcg-panel-scripts"><?php _e('Load Scripts', DFCG_DOMAIN) ?></a></li>
			<li id="dfcg-tab-tools"><a href="#dfcg-panel-tools"><?php _e('Tools', DFCG_DOMAIN) ?></a></li>

			<?php if( !dfcg_check_version() ) : ?>
			<li id="dfcg-tab-help" class="last"><a href="#dfcg-panel-help"><?php _e('Help', DFCG_DOMAIN) ?></a></li>
			<?php endif; ?>
		</ul>
	
		<div class="dfcg-panel" id="dfcg-panel-general">
			<?php dfcg_ui_general(); ?>
		</div><!-- end #dfcg-panel-general -->
	
		<div class="dfcg-panel" id="dfcg-panel-gallery">
			<?php dfcg_ui_gallery(); // Gallery Method ?>
			<?php dfcg_ui_multi(); // Multi-Option ?>
			<?php dfcg_ui_onecat(); // One Category ?>
			<?php dfcg_ui_id(); // ID Method ?>
			<?php dfcg_ui_custom_post(); // Custom Post type ?>
		</div><!-- end #dfcg-panel-gallery -->
	
		<div class="dfcg-panel" id="dfcg-panel-image">
			<?php dfcg_ui_image(); // Image File management ?>
		</div><!-- end #dfcg-panel-image -->		

		<div class="dfcg-panel" id="dfcg-panel-desc">
			<?php dfcg_ui_desc(); // Descriptions ?>
		</div><!-- end #dfcg-panel-desc -->
	
		<div class="dfcg-panel" id="dfcg-panel-css">
			<?php dfcg_ui_css(); // Gallery CSS ?>
		</div><!-- end #dfcg-panel-css -->
	
		<div class="dfcg-panel" id="dfcg-panel-javascript">
			<?php dfcg_ui_js_framework(); // Javascript Framework options ?>
			<?php dfcg_ui_javascript(); // Javascript configuration options ?>
		</div><!-- end #dfcg-panel-javascript -->
	
		<div class="dfcg-panel" id="dfcg-panel-scripts">
			<?php dfcg_ui_restrict_scripts(); // Restrict Scripts ?>
		</div><!-- end #dfcg-panel-scripts -->
	
		<div class="dfcg-panel" id="dfcg-panel-tools">
			<?php dfcg_ui_errors(); // Error Messages ?>
			<?php dfcg_ui_tools(); // Add Edit Posts/Pages columns, etc ?>
		</div><!-- end #dfcg-panel-tools -->
	
		<?php if( !dfcg_check_version() ) : ?>
		
		<div class="dfcg-panel" id="dfcg-panel-help">
			<?php dfcg_ui_help(); // Help stuff ?>
		</div><!-- end #dfcg-panel-help -->

		<?php endif; ?>
	
		<?php dfcg_ui_hidden_wp(); // Hidden fields WP and WPMS

		if ( is_multisite() ) {
			// Additional hidden fields - WPMS ONLY
			dfcg_ui_hidden_wpms();
		} ?>

	</div><!-- end #dfcg-tabs -->
	
	<?php dfcg_ui_reset_end(); // Reset and End ?>
	
	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>
	
	</form>
	
	<?php dfcg_ui_credits(); // Credits ?>	
	
</div><!-- end #dfcg-settings-page-wrap .wrap -->