<?php
/**
* Functions for the version 3.2 wp_postmeta meta_key name upgrade
*
* @copyright Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
* @package dynamic_content_gallery
* @version 3.2
*
* @info Functions to upgrade wp_postmeta, and display upgrade admin screens
*
* @info The upgrade converts existing custom fields: dfcg-image, dfcg-desc, dfcg-link to _dfcg-image, _dfcg-desc, _dfcg-link.
* @info DCG custom fields will be handled by DCG metabox, and wont appear in custom field edit boxes
*
* @since 3.2
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit("Sorry, you are not allowed to access this file directly.");
}


/**
* Converts wp_postmeta meta_key names from x to _x
*
* Converts dfcg-image, dfcg-desc, dfcg-link
*
* @global array $wpdb WP database object
* @return array $output Array of database upgrade results
* @since 3.2
*/
function dfcg_update_postmeta() {

	global $wpdb;
	
	$metas = $wpdb->get_results(
		$wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = %s OR meta_key = %s OR meta_key = %s", 'dfcg-desc', 'dfcg-image', 'dfcg-link')
		);
		
	if( $metas ) {
		
		// Initialise counters
		$metas_count = count($metas);
		$update_counter = 0;
		$delete_counter = 0;
		
		// Update loop
		foreach ($metas as $meta) {
			
			$meta_key = $meta->meta_key;
			$new_meta_key = '_' . $meta->meta_key;
			$meta_value = $meta->meta_value;
			
			// Add new postmeta
			update_post_meta($meta->post_id, $new_meta_key, $meta_value);
			// Increment loop counter
			$update_counter++;
		}
		
		// Delete loop
		foreach ($metas as $stuff) {
			
			// Delete old postmeta
			delete_post_meta($stuff->post_id, $stuff->meta_key, $stuff->meta_value);
			// Increment loop counter
			$delete_counter++;
		}
		
		// Build results array
		$output['postmetas'] = $metas_count;
		$output['modified'] = $update_counter;
		$output['deleted'] = $delete_counter;
		
	} else {
		// There are no records
		$output['postmetas'] = esc_attr('None found');
		$output['modified'] = esc_attr('None');
		$output['deleted'] = esc_attr('None');
	}
	// Not sure we need this...
	$wpdb->flush();
	
	// Return results array
	return $output;
}


/**
* Function to display Admin Notices after Postmeta upgrade
*
* Displays Admin Notices re postmeta upgrade
*
* Hooked to 'admin_notices' action
*
* @since 3.2
*/	
function dfcg_admin_notice_postmeta() {
	
	$dfcg_postmeta_upgrade = get_option('dfcg_plugin_postmeta_upgrade');
	
	$message_start = '<div id="message" class="error"><p><strong>';
	$message_end = '</strong></p></div>';
	$get_var_page = '';
	$get_var_upgrade = '';
	
	if( !empty($_GET['dfcg_postmeta_upgrade']) ) {
		$get_var_upgrade = $_GET['dfcg_postmeta_upgrade'];
	}
	
	if( !empty($_GET['page']) ) {
		$get_var_page = $_GET['page'];
	}
	$current_page = basename($_SERVER['PHP_SELF']) . '?page=' . $get_var_page;
	
	// We're on DCG Settings page and halfway through upgrade process
	if( $current_page == 'options-general.php?page=' . DFCG_FILE_HOOK && $get_var_upgrade == 'just' ) {
	
		echo '<div class="updated fade" style="background-color:#ecfcde; border:1px solid #a7c886;"><p><strong>' . __('Congratulations! Custom Field data has been successfully updated.', DFCG_DOMAIN) . '</strong></p></div>';
	
	// We're on DCG Settings page and 'upgraded' doesn't exist
	} elseif( $current_page == 'options-general.php?page=' . DFCG_FILE_HOOK && empty($dfcg_postmeta_upgrade['upgraded']) ) {
		
		echo $message_start . __('Important! Dynamic Content Gallery Custom Field data must be upgraded.', DFCG_DOMAIN) . $message_end;
		
	
	// We're not on DCG Settings page and 'upgraded' doesn't exist
	} elseif( empty($dfcg_postmeta_upgrade['upgraded']) ) {
		
		echo $message_start . __('Important! Dynamic Content Gallery v3.2 has been installed. DCG Custom Field data must be upgraded.', DFCG_DOMAIN) .'&nbsp;'. __('Go to', DFCG_DOMAIN) . ' <a href="./options-general.php?page=dynamic_content_gallery">' . __('Settings Page', DFCG_DOMAIN) . '</a> ' . __('to run upgrade.', DFCG_DOMAIN) . $message_end;
	
	}
}


/**
* Displays first upgrade Settings page
*
* Uses $_POST rather than Settings API, due to probs with db update in callback
* On Submit, creates array of input called $dfcg_plugin_postmeta_upgrade
* 'Upgraded' status changed from NULL to 'start'
*
* @since 3.2
*/
function dfcg_ui_upgrade_page1() {
	?>
<div class="postbox">
	<h3><?php _e("Upgrade Required", DFCG_DOMAIN); ?></h3>
	<div class="inside">
		<div style="float:left;width:690px;">
			<h4><?php _e('Step 1 of 2', DFCG_DOMAIN); ?></h4>
		
			<form method="post" action="<?php echo htmlspecialchars( add_query_arg( 'dfcg_postmeta_upgrade', 'just' ) ); ?>">
			
			<?php wp_nonce_field('dfcg_plugin_postmeta_upgrade'); // Set nonce... ?>
			
			<p><?php _e("You must run this upgrade routine to continue using the Dynamic Content Gallery.", DFCG_DOMAIN); ?></p>
			<p><?php _e("This upgrade routine converts the existing dfcg-desc, dfcg-image and dfcg-link custom field names to the new format introduced in version 3.2 of the plugin, which means renaming these custom fields to _dfcg-desc, _dfcg-image and _dfcg-link.", DFCG_DOMAIN); ?></p>
			<p><?php _e("Custom field values (URLs, descriptions, external links) are not modified during this process, only the custom field names.", DFCG_DOMAIN); ?></p>
			<div class="dfcg-postmeta-db-info">
				<p><strong><?php _e("ATTENTION!", DFCG_DOMAIN); ?></strong></p>
				<?php if( !function_exists('wpmu_create_blog') ) { // We're in WP ?>
				<p><strong><?php _e("This upgrade routine makes permanent changes to your database. Backup your database before proceeding!", DFCG_DOMAIN); ?></strong></p>
				<?php } else { ?>
				<p><strong><?php _e("This upgrade routine makes permanent changes to your database. It is recommended to contact your Site Administrator to make sure you have a recent backup before proceeding.", DFCG_DOMAIN); ?></strong></p>
				<?php } ?>
			</div>
			<p><?php _e("Please note that if you are using the dfcg-desc, dfcg-image and dfcg-link custom fields for purposes unrelated to the DCG, you will need to update any references to these custom fields in your theme's template files.", DFCG_DOMAIN); ?></p>
			
			<input name="dfcg_plugin_postmeta_upgrade[modified]" id="dfcg-modified" type="hidden" value="0" />
			<input name="dfcg_plugin_postmeta_upgrade[postmetas]" id="dfcg-postmetas" type="hidden" value="0" />
			<input name="dfcg_plugin_postmeta_upgrade[upgraded]" id="dfcg-upgraded" type="hidden" value="start" />
			<div style="float:left;width:400px;margin:0;padding:0;">
				<p class="submit"><input class="button-primary" name="dfcg_upgrade_1" type="submit" value="<?php _e('Run Upgrade'); ?>" /></p>
			</div>
			</form>
			
		</div>
						
		<?php dfcg_ui_upgrade_sgr_info(); ?>
										
		<div style="clear:both;"></div>
	</div><!-- end Postbox inside -->
</div><!-- end Postbox -->
<?php }


/**
* Displays second upgrade Settings page
*
* This function is run if $dfcg_plugin_postmeta_upgrade['upgraded'] == 'just'
* See dfcg-admin-ui-upgrade-screen.php
* Switches $dfcg_plugin_postmeta_upgrade['upgraded'] to 'completed' and updates db options
*
* @param $dfcg_postmeta_upgrade array db options
* @since 3.2
*/
function dfcg_ui_upgrade_page2($dfcg_postmeta_upgrade) {
	
	// Update database option to save "completed" status
	$dfcg_postmeta_upgrade['upgraded'] = esc_attr('completed');
	update_option( 'dfcg_plugin_postmeta_upgrade', $dfcg_postmeta_upgrade );
	?>
<div class="postbox">
	<h3><?php _e("Upgrade Completed", DFCG_DOMAIN); ?></h3>
	<div class="inside">
		<div style="float:left;width:690px;">
			<h4><?php _e('Step 2 of 2', DFCG_DOMAIN); ?></h4>
			
						
			<p><?php _e("The custom field upgrade is completed.", DFCG_DOMAIN); ?></p>
			<div class="sgr-postmeta" style="background-color:#ecfcde; border:1px solid #a7c886;padding:0px 15px;">
				<p><strong><?php _e('Number of records found in _postmeta table:', DFCG_DOMAIN); ?> <?php echo $dfcg_postmeta_upgrade['postmetas']; ?></strong></p>
				<p><strong><?php _e('Number of records updated:', DFCG_DOMAIN); ?> <?php echo $dfcg_postmeta_upgrade['modified']; ?></strong></p>
			</div>
			<p><?php _e("Click Finish to continue.", DFCG_DOMAIN); ?></p>
					
			<div style="width:300px;margin:0;padding:0;margin:25px 0 0 0;">
				<p class="submit"><a class="button-primary" href="../wp-admin/options-general.php?page=dynamic_content_gallery&upgrade=completed" title="Finish"><?php _e('Finish', DFCG_DOMAIN); ?></a></p>
			</div>
			</form>			

		</div>
						
		<?php dfcg_ui_upgrade_sgr_info(); ?>
										
		<div style="clear:both;"></div>
	</div><!-- end Postbox inside -->
</div><!-- end Postbox -->
<?php }


/**
* Displays SGR info box on postmeta upgrade Settings pages
*
* @since 3.2
*/
function dfcg_ui_upgrade_sgr_info() {
?>
<div class="postbox" id="sgr-info">	
	<h4><?php _e('Resources &amp; Support', DFCG_DOMAIN); ?></h4>
	<p><a href="http://www.studiograsshopper.ch"><img src="<?php echo DFCG_URL . '/admin-assets/sgr_icon_75.jpg'; ?>" alt="studiograsshopper" /></a><strong><?php _e('Dynamic Content Gallery for WP and WPMU', DFCG_DOMAIN); ?></strong>.<br /><?php _e('Version', DFCG_DOMAIN); ?> <?php echo DFCG_VER; ?><br /><?php _e('Author', DFCG_DOMAIN); ?>: <a href="http://www.studiograsshopper.ch/">Ade Walker</a></p>
	<ul>
		<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/"><?php _e('Plugin Home page', DFCG_DOMAIN); ?></a></li>
		<li><a href="http://www.studiograsshopper.ch/forum/"><?php _e('Support Forum', DFCG_DOMAIN); ?></a></li>
	</ul>
</div><!-- end sgr-info -->
<?php }