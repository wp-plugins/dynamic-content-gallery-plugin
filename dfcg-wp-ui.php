<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	Dynamic Content Gallery
*	@version	3.0 RC3
*
*	Options page for Wordpress and Wordpress Mu.
*	
*/

dfcg_load_textdomain();

// Load JS and CSS
dfcg_options_css_js();

// Handle the updating of options
if( isset($_POST['info_update']) ) {
		
	// Is the user allowed to do this?
	if ( function_exists('current_user_can') && !current_user_can('manage_options') ) {
		die(__('Sorry. You do not have permission to do this.'));
	}
			
	// check the nonce
	check_admin_referer( 'dfcg-update' );
		
	// build the array from input
	$updated_options = $_POST['dfcg'];
		
	// trim whitespace within the array values
	foreach( $updated_options as $key => $value ) {
		$updated_options[$key] = trim($value);
	}
	
	// deal with One Category "All" option
	// This is to suppress WP_Class Error if category_description() is passed a '0'.
	// WP_Query fails gracefully, and cat='' is ignored
	if( $updated_options['cat-display'] == 0 ) {
		$updated_options['cat-display'] = '';
	}
	
	// deal with absolute URLS and Paths: add trailing slash
	if ( function_exists('wpmu_create_blog') ) {
		// We're in WPMU
		$abs_url_opts = array( 'imageurl' );
	} else {
		// We're in WP
		$abs_url_opts = array( 'imageurl', 'defimgmulti', 'defimgonecat' );
	}
	foreach( $abs_url_opts as $key ) {
		// Trailingslashit if there is something to do it to
		if( !empty($updated_options[$key]) ) {
			$updated_options[$key] = trailingslashit($updated_options[$key]);
		}
	}
	
	// deal with the MOOTOOLS checkbox
	$onoff_opts = array( 'mootools' );
	foreach($onoff_opts as $key) {
		$updated_options[$key] = $updated_options[$key] ? '1' : '0';
	}
	
	// deal with the RESET checkbox and other bool options
	$bool_opts = array( 'reset', 'showArrows', 'showCarousel', 'showInfopane', 'timed', 'slideInfoZoneSlide', 'errors', 'posts-column', 'pages-column' );
	foreach($bool_opts as $key) {
		$updated_options[$key] = $updated_options[$key] ? 'true' : 'false';
	}
	
	
	// If RESET is checked, reset the options
	if ( $updated_options['reset'] == "true" ) {
		dfcg_unset_gallery_options();	// clear out the old ones 
		dfcg_default_options();		// put back the defaults
		echo '<div id="message" class="updated fade"><p><strong>' . __('Dynamic Content Gallery Settings reset to defaults.') . '</strong></p></div>';
	} else {
		// Run validation checks on submit
		if ( function_exists('wpmu_create_blog') ) {
			// We're in WPMU, so validation isn't needed
		} else {
			// We're in WP, so validate
			dfcg_on_submit_validation($updated_options);
		}
		// Update the options
		update_option( 'dfcg_plugin_settings', $updated_options);
		// Display success message
		echo '<div id="message" class="updated fade"><p><strong>' . __('Dynamic Content Gallery Settings updated and saved.') . '</strong></p></div>';
	}
}


// Options
$dfcg_options = get_option('dfcg_plugin_settings');

// Run validation checks on page load
if ( function_exists('wpmu_create_blog') ) {
	// We're in WPMU, so validation isn't needed
} else {
	// We're in WP, so validate
	dfcg_on_load_validation($dfcg_options);
}
?>


<div class="wrap" id="sgr-style"><a name="top"></a>

	<?php screen_icon('options-general');// Display icon next to title ?>
	
	<h2><?php _e('Dynamic Content Gallery Configuration', DFCG_DOMAIN); ?></h2>
	
	<form method="post">
		
		<fieldset name="dynamic_content_gallery" class="options">
		
		<?php
		// put the nonce in
		wp_nonce_field('dfcg-update');
		?>
		
		<div class="metabox-holder">
			
			<div class="postbox">
				<h3>General Information:</h3>
				<div class="inside">
					<div style="float:left;width:700px;">
						<p><em><?php _e('You are using Dynamic Content Gallery version ', DFCG_DOMAIN); echo DFCG_VER;
						_e(' for Wordpress and Wordpress Mu.', DFCG_DOMAIN); ?></em></p>
						<p>
						<ul>
							<li><a href="#how-to">How to add the Dynamic Content Gallery to your Theme</a></li>
							<li><a href="#assign">How to assign an image and a description to each Post/Page</a></li>
							<li><a href="#external-link">How to assign an external link to a gallery image</a></li>
							<?php if ( function_exists('wpmu_create_blog') ) { ?>
								<li><a href="#upload-images">1. Uploading your images</a></li>
							<?php } else { ?>
								<li><a href="#default-images">How to name and organise your default images</a></li>
								<li><a href="#image-file">1. Image file management (REQUIRED)</a></li>
							<?php } ?>
							<li><a href="#gallery-method">2. Gallery Method (REQUIRED)</a></li>
							<li><a href="#multi-option">2.1 MULTI OPTION Settings</a> (<em>Required if you selected Multi Option in <a href="#gallery-method">Gallery Method</a></em>)</li>
							<li><a href="#one-category">2.2 ONE CATEGORY Settings</a> (<em>Required if you selected One Category in <a href="#gallery-method">Gallery Method</a></em>)</li>
							<li><a href="#pages-method">2.3 PAGES Settings</a> (<em>Required if you selected Pages in <a href="#gallery-method">Gallery Method</a></em>)</li>
							<li><a href="#default-desc">3. Default description (OPTIONAL)</a></li>
							<li><a href="#gallery-css">4. Gallery size and CSS options (REQUIRED)</a></li>
							<li><a href="#gallery-js">5. Javascript configuration options (OPTIONAL)</a></li>
							<li><a href="#restrict-script">6. Restrict script loading (RECOMMENDED)</a></li>
							<li><a href="#error-messages">7. Error message options (OPTIONAL)</a></li>
							<li><a href="#custom-columns">8. Add Custom Field column to Posts and Pages Edit screen (OPTIONAL)</a></li>
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
			
			// How to box
			dfcg_ui_howto();
			
			// Assign Posts/Pages
			dfcg_ui_assign();
			
			// External link
			dfcg_ui_link();
			
			if ( function_exists('wpmu_create_blog') ) {
				// Uploading images - WPMU ONLY
				dfcg_ui_create_wpmu();
			} else {
				// Create default images - WP ONLY
				dfcg_ui_create_wp();
			
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
				dfcg_ui_multi_end();
			
			// One Category
			dfcg_ui_onecat();
				if ( !function_exists('wpmu_create_blog') ) {
					// Default images - WP ONLY
					dfcg_ui_onecat_wp();
				}
				// One Category end box
				dfcg_ui_onecat_end();
			
			// Pages
			dfcg_ui_pages();
				if ( !function_exists('wpmu_create_blog') ) {
					// Default image - WP ONLY
					dfcg_ui_pages_wp();
				}
				// Pages box end
				dfcg_ui_pages_end();
			
			// Default Desc
			dfcg_ui_defdesc();
			
			// Gallery CSS
			dfcg_ui_css();
			
			// Javascript options
			dfcg_ui_javascript();
			
			if ( function_exists('wpmu_create_blog') ) {
				// Hidden fields - WP ONLY
				dfcg_ui_hidden_wp();
			} else {
				// Hidden fields - WPMU ONLY
				dfcg_ui_hidden_wpmu();
			}
			
			// Restrict Scripts
			dfcg_ui_restrict_scripts();
			
			// Error Messages
			dfcg_ui_errors();
			
			// Add Edit Posts/Pages columns
			dfcg_ui_columns();
			
			// Reset and End
			dfcg_ui_reset_end();
	
	// Credits
	dfcg_ui_credits();
	?>
</div>