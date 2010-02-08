<?php
/**
* Functions for displaying contents of Settings page
*
* @copyright Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
* @package dynamic_content_gallery
* @version 3.2.2
*
* @info These are the functions which produce the UI postboxes
* @info for the Settings page.
*
* @info Functions ending _wp are only used for Wordpress
* @info Functions ending _wpmu are only used for WPMU
*
* @since 3.0
*/


/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}


/**
* Metabox Save buttons
*
* @since 3.0
*/
function dfcg_ui_buttons() { ?>
	<div style="float:left;width:400px;margin:0;padding:0;"><p class="submit"><input type="submit" value="<?php _e('Save Changes'); ?>" /></p></div>
	<div style="float:right;margin:0;padding:0;width:300px;"><p class="submit"><a class="button-secondary" href="#sgr-style" title="Back to top" style="float:right;"><?php _e('Back to top', DFCG_DOMAIN); ?></a></p></div>
	<div style="clear:both;"></div>
</div><!-- end inside -->
</div><!-- end Postbox -->
<?php }


/**
* Active Settings display
*
* @global array $dfcg_options plugin options from db
* @since 3.2.2
*/
function dfcg_ui_active() {

	global $dfcg_options;
	
	$sep = ' | ';
	
	// Heading
	$output = '<p><span class="bold-italic">' . __('Your active key Settings: ', DFCG_DOMAIN) . '</span><br />';
	
	// Image File Management
	if( !function_exists('wpmu_create_blog') ) {
		
		$output .= '<a href="#image-file">' . __('Image File Management', DFCG_DOMAIN) . '</a>: <span class="key_settings">' . $dfcg_options['image-url-type'] . ' URL</span>';
		
		if( $dfcg_options['image-url-type'] == 'partial' ) {
			
			$output .= $sep . __('Images folder is: ', DFCG_DOMAIN);
			
			if( !empty( $dfcg_options['imageurl'] ) ) {
				$output .= '<span class="key_settings">' . $dfcg_options['imageurl'];
			} else {
				$output .= '<span class="key_settings">' . __('not defined', DFCG_DOMAIN);
			}
		
		$output .= '</span><br />';
		
		} else {
		
			$output .= '<br />';
		}
	}
	
	// Gallery Method
	$output .= '<a href="#gallery-method">' . __('Gallery Method', DFCG_DOMAIN) . '</a>: <span class="key_settings">' . $dfcg_options['populate-method'] . '</span>';
	
	if( !function_exists('wpmu_create_blog') ) {
	
		$subhead = $sep . __('Default Images folder is: ', DFCG_DOMAIN) . '<span class="key_settings">';
		
		// TODO: Sort out a message if default image URL has not been defined
		
		if( $dfcg_options['populate-method'] == 'multi-option' ) {
			if( $dfcg_options['defimgmulti'] ) {
				$output .= $subhead . $dfcg_options['defimgmulti'];
			} else {
				$output .= $subhead . __('not defined', DFCG_DOMAIN);
			}
		
		} elseif( $dfcg_options['populate-method'] == 'one-category' ) {
			if( $dfcg_options['defimgonecat'] ) {
				$output .= $subhead . $dfcg_options['defimgonecat'];
			} else {
				$output .= $subhead . __('not defined', DFCG_DOMAIN);
			}
		
		} elseif( $dfcg_options['defimgpages'] ) {
				$output .= $sep . __('Default image is: ', DFCG_DOMAIN) . '<span class="key_settings">' . $dfcg_options['defimgpages'];
		} else {
				$output .= $sep . __('Default image is: ', DFCG_DOMAIN) . '<span class="key_settings">' . __('not defined', DFCG_DOMAIN);
		}
		
		$output .= '</span><br />';
	
	} else {
	
		$output .= '<br />';
	
	}
	
	// Slide Pane Descriptions
	$output .= '<a href="#default-desc">' . __('Slide Pane Description', DFCG_DOMAIN) . '</a>: <span class="key_settings">' . $dfcg_options['desc-method'] . '</span><br />';
	
	// Error Messages
	$output .= '<a href="#error-messages">' . __('Page Source Error messages', DFCG_DOMAIN) . '</a>: <span class="key_settings">';
		
	if( $dfcg_options['errors'] ) {
		$output .= __('on', DFCG_DOMAIN);
	
	} else {
		$output .= __('off', DFCG_DOMAIN);
		
	}
	
	$output .= '</span>' . $sep;
	
	// Script framework
	$output .= '<a href="#gallery-js-scripts">' . __('Javascript Framework used', DFCG_DOMAIN) . '</a>: <span class="key_settings">' . $dfcg_options['scripts'] . '</span><br />';
	
	// Restrict Scripts
	$output .= '<a href="#restrict-scripts">' . __('Scripts restricted to', DFCG_DOMAIN) . '</a>: <span class="key_settings">';
	
	if( $dfcg_options['limit-scripts'] == 'homepage' ) {
		$output .= __('Home Page', DFCG_DOMAIN);
	
	} elseif( $dfcg_options['limit-scripts'] == 'page' ) {
		$output .= __('Page ID => ', DFCG_DOMAIN) . $dfcg_options['page-ids'];
		
	} elseif( $dfcg_options['limit-scripts'] == 'pagetemplate' ) {
		$output .= __('Page Template => ', DFCG_DOMAIN) . $dfcg_options['page-filename'];
		
	} else {
		$output .= __('All pages', DFCG_DOMAIN);
		
	}
	
	$output .= '</span>';
	
	$output .= '</p>';
	
	echo $output;
}


/**
* Intro box: menu and holder
*
* @uses dfcg_ui_intro_text()
* @uses dfcg_ui_sgr_info()
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_intro_menu() {
	global $dfcg_options;
	?>
<div class="postbox">
	<h3><?php _e("General Information:", DFCG_DOMAIN); ?></h3>
	<div class="inside">
		<div style="float:left;width:690px;">
			<p><?php _e("Please read through this page and configure the plugin. Some Settings are Required, others are Optional, depending on how you want to configure the gallery.", DFCG_DOMAIN); ?> <em><?php _e("Use the links below to jump to the relevant section on this page:", DFCG_DOMAIN); ?></em></p>
			<ul>
				<li><a href="#image-file">1. <?php _e("Image file management (REQUIRED)", DFCG_DOMAIN); ?></a></li>
				<li><a href="#gallery-method">2. <?php _e("Gallery Method (REQUIRED)", DFCG_DOMAIN); ?></a></li>
				<li><a href="#multi-option">2.1 <?php _e("MULTI OPTION Settings", DFCG_DOMAIN); ?></a> (<em><?php _e('Required if you selected Multi Option in <a href="#gallery-method">Gallery Method</a>', DFCG_DOMAIN); ?></em>)</li>
				<li><a href="#one-category">2.2 <?php _e("ONE CATEGORY Settings", DFCG_DOMAIN); ?></a> (<em><?php _e('Required if you selected One Category in <a href="#gallery-method">Gallery Method</a>', DFCG_DOMAIN); ?></em>)</li>
				<li><a href="#pages-method">2.3 <?php _e("PAGES Settings", DFCG_DOMAIN); ?></a> (<em><?php _e('Required if you selected Pages in <a href="#gallery-method">Gallery Method</a>', DFCG_DOMAIN); ?></em>)</li>
				<li><a href="#default-desc">3. <?php _e("Slide Pane Descriptions (REQUIRED)", DFCG_DOMAIN); ?></a></li>
				<li><a href="#gallery-css">4. <?php _e("Gallery size and CSS options (REQUIRED)", DFCG_DOMAIN); ?></a></li>
				<li><a href="#gallery-js-scripts">5. <?php _e("Javascript framework selection (OPTIONAL)", DFCG_DOMAIN); ?></a></li>
				<li><a href="#gallery-js">6. <?php _e("Javascript configuration options (OPTIONAL)", DFCG_DOMAIN); ?></a></li>
				<li><a href="#restrict-scripts">7. <?php _e("Restrict script loading (RECOMMENDED)", DFCG_DOMAIN); ?></a></li>
				<li><a href="#error-messages">8. <?php _e("Error message options (OPTIONAL)", DFCG_DOMAIN); ?></a></li>
				<li><a href="#custom-columns">9. <?php _e("Add Custom Field columns to Posts and Pages Edit screen (OPTIONAL)", DFCG_DOMAIN); ?></a></li>
			</ul>
								
			<?php dfcg_ui_intro_text(); ?>
					
		</div>
					
		<?php dfcg_ui_sgr_info(); ?>
										
		<div style="clear:both;"></div>
	</div><!-- end Postbox inside -->
</div><!-- end Postbox -->
<?php }


/**
* Intro box: content
*
* @uses dfcg_ui_active()
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_intro_text() {
	global $dfcg_options;
	?>
	<div class="dfcg-tip">
		<p><span class="bold-italic"><?php _e('Validation messages: ', DFCG_DOMAIN); ?></span><em><?php _e('After saving Settings, the plugin generates validation messages at the top of this page. Messages in red must be fixed, otherwise the gallery will not display. Messages in yellow mean that the gallery will display, but you are not taking advantage of the default images feature.', DFCG_DOMAIN); ?></em></p>
		<p><span class="bold-italic"><?php _e('Quick Help: ', DFCG_DOMAIN); ?></span><em><?php _e('Click the <strong>Help</strong> tab at the top of the screen for more information on setting up the plugin.', DFCG_DOMAIN); ?></em></p>
	
		<?php dfcg_ui_active(); ?>
	</div>
<?php }


/**
* Resources inner box: content
*
* @since 3.0
*/
function dfcg_ui_sgr_info() {
?>
<div class="postbox" id="sgr-info">	
	<h4><?php _e('Resources & Support', DFCG_DOMAIN); ?></h4>
	<p><a href="http://www.studiograsshopper.ch"><img src="<?php echo DFCG_URL . '/admin-assets/sgr_icon_75.jpg'; ?>" alt="studiograsshopper" /></a><strong><?php _e('Dynamic Content Gallery for WP and WPMU', DFCG_DOMAIN); ?></strong>.<br /><?php _e('Version', DFCG_DOMAIN); ?> <?php echo DFCG_VER; ?><br /><?php _e('Author', DFCG_DOMAIN); ?>: <a href="http://www.studiograsshopper.ch/">Ade Walker</a></p>
	<p><?php _e('For further information, or in case of configuration problems, please consult these comprehensive resources:', DFCG_DOMAIN); ?></p>
	<ul>
		<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/"><?php _e('Plugin Home page', DFCG_DOMAIN); ?></a></li>
		<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/"><?php _e('Configuration guide', DFCG_DOMAIN); ?></a></li>
		<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/documentation/"><?php _e('Documentation', DFCG_DOMAIN); ?></a></li>
		<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/faq/"><?php _e('FAQ', DFCG_DOMAIN); ?></a></li>
		<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/error-messages/"><?php _e('Error messages', DFCG_DOMAIN); ?></a></li>
		<li><a href="http://www.studiograsshopper.ch/forum/"><?php _e('Support Forum', DFCG_DOMAIN); ?></a></li>
	</ul>
	<p><?php _e('If you have found this plugin useful, please consider making a donation to help support future development. Your support will be much appreciated. Thank you!', DFCG_DOMAIN); ?></p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick" />
			<input type="hidden" name="hosted_button_id" value="7415216" />
			<input type="image" src="https://www.paypal.com/en_US/CH/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
		</form>
	
</div><!-- end sgr-info -->
<?php }


/**
* Image File Management: box and content
*
* @uses dfcg_ui_buttons()
*
* @global array $dfcg_options plugin options from db
* @since 3.2.2
*/
function dfcg_ui_1_image_wp() {
	global $dfcg_options;
	?>
	<div id="image-file" class="postbox">
		<h3>1. <?php _e('Image file management (REQUIRED)', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('Complete the following settings to set up your gallery image file management preferences. Your selection determines the form of the image URL which is entered in the <strong>Image URL</strong> field in the Write Post/Page screen DCG Metabox.', DFCG_DOMAIN); ?> <em><?php _e('Further information about this setting can be found in the', DFCG_DOMAIN); ?> <a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/"><?php _e('Configuration guide', DFCG_DOMAIN); ?></a>.</em></p>
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[image-url-type]" id="dfcg-fullurl" type="radio" style="margin-right:5px;" value="full" <?php checked('full', $dfcg_options['image-url-type']); ?> />
						<label for="dfcg-fullurl"><?php _e('Full URL (Default)', DFCG_DOMAIN); ?></label></th>
						<td><p><?php _e('Enter <strong>Image URL</strong> in this format:', DFCG_DOMAIN); ?> <span class="bold-italic">http://www.yourdomain.com/folder/anotherfolder/myimage.jpg</span><br />
						<?php _e('Select this option if you want complete freedom to reference images anywhere in your site and in multiple locations.', DFCG_DOMAIN); ?></p>
						<div class="dfcg-tip-box"><p><b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e('This is the best option if you keep images in many different directories both inside and outside of the /wp-content/uploads folder. Also, select this option if your images are stored off-site eg Flickr, Picasa etc. This is also the recommended option if you use the Media Uploader for uploading images to your site - just copy the File URL from the Uploader screen and paste it into the DCG Metabox Image URL field.', DFCG_DOMAIN); ?></p></div></td>
					</tr>
						
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[image-url-type]" id="dfcg-parturl" type="radio" style="margin-right:5px;" value="partial" <?php checked('partial', $dfcg_options['image-url-type']); ?> />
						<label for="dfcg-parturl"><?php _e('Partial URL', DFCG_DOMAIN); ?></label></th>
						<td><p><?php _e('Enter <strong>Image URL</strong> in this format (for example): ', DFCG_DOMAIN); ?><span class="bold-italic">subfoldername/myimage.jpg</span><br />
						<?php _e('Select this option if your images are organised into many sub-folders within one main folder. The URL to the main folder is entered in the field below.', DFCG_DOMAIN); ?></p></td>
					</tr>
						
					<tr valign="top">
						<th scope="row"><?php _e('URL to images folder:', DFCG_DOMAIN); ?></th>
						<td><?php _e('If you selected <strong>Partial URL</strong> you must also specify the URL to the top-level folder which contains the relevant sub-folders and images. Include your domain name in this URL, for example:', DFCG_DOMAIN); ?> <span class="bold-italic">http://www.yourdomain.com/myspecial_image_folder/</span></td>
					</tr>
						
					<tr valign="top">
						<th scope="row"></th>
						<td><input name="dfcg_plugin_settings[imageurl]" id="dfcg-imageurl" size="75" value="<?php echo $dfcg_options['imageurl']; ?>" /></td>
					</tr>
				</tbody>
			</table>
			<?php dfcg_ui_buttons(); ?>
<?php }


/**
* Gallery Method: box and content
*
* @uses dfcg_ui_buttons()
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_2_method() {
	global $dfcg_options;
	?>
	<div id="gallery-method" class="postbox">
		<h3>2. <?php _e('Gallery Method (REQUIRED)', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('The Dynamic Content Gallery offers three different methods for populating the gallery with images. Select the option most appropriate for your needs.', DFCG_DOMAIN); ?></p>
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[populate-method]" id="dfcg-populate-multi" type="radio" style="margin-right:5px;" value="multi-option" <?php checked('multi-option', $dfcg_options['populate-method']); ?> />
						<label for="dfcg-populate-multi"><?php _e('Multi Option', DFCG_DOMAIN); ?></label></th>
						<td><p><?php _e('Complete freedom to select up to 9 images from a mix of categories. Set up the relevant options in', DFCG_DOMAIN); ?> <a href="#multi-option">2.1 MULTI OPTION <?php _e('Settings', DFCG_DOMAIN); ?></a></p>
						<div class="dfcg-tip-box"><p><b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e('This is the original method used in previous versions of the plugin, and the option to choose if you want to mix posts from different categories.', DFCG_DOMAIN); ?></p></div></td>
					</tr>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[populate-method]" id="dfcg-populate-one" type="radio" style="margin-right:5px;"  value="one-category" <?php checked('one-category', $dfcg_options['populate-method']); ?> />
						<label for="dfcg-populate-one"><?php _e('One Category', DFCG_DOMAIN); ?></label></th>
						<td><p><?php _e('Images are pulled from a user-definable number of Posts from one selected Category. Set up the relevant options in', DFCG_DOMAIN); ?> <a href="#one-category">2.2 ONE CATEGORY <?php _e('Settings', DFCG_DOMAIN); ?></a></p>
						<div class="dfcg-tip-box"><p><b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e('This is the best option if you use a Featured or News category for highlighting certain posts.', DFCG_DOMAIN); ?><br />
						<b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e('You can also use this option to display the latest Posts from all categories.', DFCG_DOMAIN); ?></p></div></td>
					</tr>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[populate-method]" id="dfcg-populate-pages" type="radio" style="margin-right:5px;" value="pages" <?php checked('pages', $dfcg_options['populate-method']); ?> />
						<label for="dfcg-populate-pages"><?php _e('Pages', DFCG_DOMAIN); ?></label></th>
						<td><?php _e('Images are pulled from Pages, rather than Posts. Set up the relevant options in', DFCG_DOMAIN); ?> <a href="#pages-method">2.3 PAGES <?php _e('Settings', DFCG_DOMAIN); ?></a></td>
					</tr>
				</tbody>
			</table>
			<?php dfcg_ui_buttons(); ?>
<?php }


/**
* Multi-Option: box and content
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_multi() {
	global $dfcg_options;
	?>
	<div id="multi-option" class="postbox">
		<h3>2.1 MULTI OPTION <?php _e('Settings', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('Configure this section if you chose Multi Option in the', DFCG_DOMAIN); ?> <a href="#gallery-method">Gallery Method</a> <?php _e('Settings', DFCG_DOMAIN); ?>. <?php _e('The Multi Option method of populating the gallery provides up to 9 image "slots", each of which can be configured with its own Category and "Post Select". For the Post Select: enter <strong>1</strong> for the latest post, <strong>2</strong> for the last-but-one post, <strong>3</strong> for the post before that, and so on.', DFCG_DOMAIN); ?></p>
			<p><em><?php _e('Further information on the possible schemes can be found in the ', DFCG_DOMAIN); ?><a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/"><?php _e('Configuration guide', DFCG_DOMAIN); ?></a>.</em></p>
			<div class="dfcg-tip-box"><p><b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e("If you want to pull in the latest posts from one category, don't use Multi Option, use the One Category <a href=\"#gallery-method\">Gallery Method</a> instead. It's much more efficient in terms of database queries.", DFCG_DOMAIN); ?><br />
			<b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e("Want to show less than 9 images? Delete the contents of the Post Select fields for image slots you don't need.", DFCG_DOMAIN); ?></p></div>
			
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><strong><?php _e('Image "Slots"', DFCG_DOMAIN); ?></strong></th>
						<td><strong><?php _e('Category Select', DFCG_DOMAIN); ?></strong></td>
						<td><strong><?php _e('Post Select', DFCG_DOMAIN); ?></strong></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('1st image', DFCG_DOMAIN); ?></th>
						<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat01'], 'name' => 'dfcg_plugin_settings[cat01]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
						<td><input name="dfcg_plugin_settings[off01]" id="off01" size="5" value="<?php echo $dfcg_options['off01']; ?>" />&nbsp;<em>Ex. Enter <strong>1</strong> for latest post.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('2nd image', DFCG_DOMAIN); ?></th>
						<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat02'], 'name' => 'dfcg_plugin_settings[cat02]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
						<td><input name="dfcg_plugin_settings[off02]" id="off02" size="5" value="<?php echo $dfcg_options['off02']; ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('3rd image', DFCG_DOMAIN); ?></th>
						<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat03'], 'name' => 'dfcg_plugin_settings[cat03]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
						<td><input name="dfcg_plugin_settings[off03]" id="off03" size="5" value="<?php echo $dfcg_options['off03']; ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('4th image', DFCG_DOMAIN); ?></th>
						<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat04'], 'name' => 'dfcg_plugin_settings[cat04]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
						<td><input name="dfcg_plugin_settings[off04]" id="off04" size="5" value="<?php echo $dfcg_options['off04']; ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('5th image', DFCG_DOMAIN); ?></th>
						<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat05'], 'name' => 'dfcg_plugin_settings[cat05]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
						<td><input name="dfcg_plugin_settings[off05]" id="off05" size="5" value="<?php echo $dfcg_options['off05']; ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('6th image', DFCG_DOMAIN); ?></th>
						<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat06'], 'name' => 'dfcg_plugin_settings[cat06]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
						<td><input name="dfcg_plugin_settings[off06]" id="off06" size="5" value="<?php echo $dfcg_options['off06']; ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('7th image', DFCG_DOMAIN); ?></th>
						<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat07'], 'name' => 'dfcg_plugin_settings[cat07]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
						<td><input name="dfcg_plugin_settings[off07]" id="off07" size="5" value="<?php echo $dfcg_options['off07']; ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('8th image', DFCG_DOMAIN); ?></th>
						<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat08'], 'name' => 'dfcg_plugin_settings[cat08]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
						<td><input name="dfcg_plugin_settings[off08]" id="off08" size="5" value="<?php echo $dfcg_options['off08']; ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('9th image', DFCG_DOMAIN); ?></th>
						<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat09'], 'name' => 'dfcg_plugin_settings[cat09]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
						<td><input name="dfcg_plugin_settings[off09]" id="off09" size="5" value="<?php echo $dfcg_options['off09']; ?>" /></td>
					</tr>
				</tbody>
			</table>
<?php }


/**
* Multi-Option default image folder: content
* WP ONLY
*
* @global array $dfcg_options plugin options from db
* @since 3.2.2
*/
function dfcg_ui_multi_wp() {
	global $dfcg_options;
	?>
	<table class="optiontable form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('URL to default "Category" images folder:', DFCG_DOMAIN); ?></th>
				<td><?php _e('Enter the URL to the folder which contains the default images.  The default images will be pulled into the gallery in the event that Posts do not have an image specified in the Write Post DCG Metabox <strong>Image URL</strong>.  This must be an <b>absolute</b> URL.  For example, if your default images are stored in a folder named "default" in your <em>wp-content/uploads</em> folder, the URL entered here will be:', DFCG_DOMAIN); ?> <em>http://www.yourdomain.com/wp-content/uploads/default/</em></td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td><input name="dfcg_plugin_settings[defimgmulti]" id="dfcg-defimgmulti" size="75" value="<?php echo $dfcg_options['defimgmulti']; ?>" /></td> 
    		</tr>
		</tbody>
	</table>
<?php }


/**
* One Category: box and contents
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_onecat() {
	global $dfcg_options;
	?>
	<div id="one-category" class="postbox">
		<h3>2.2 ONE CATEGORY <?php _e('Settings', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('Configure this section if you chose One Category in the', DFCG_DOMAIN); ?> <a href="#gallery-method">Gallery Method</a> <?php _e('Settings', DFCG_DOMAIN); ?>.</p>
				<table class="optiontable form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><?php _e('Select the Category:', DFCG_DOMAIN); ?></th>
							<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat-display'], 'name' => 'dfcg_plugin_settings[cat-display]', 'orderby' => 'Name' , 'hierarchical' => 0, 'hide_empty' => 1, 'show_option_all' => __('All', DFCG_DOMAIN) )); ?><span style="padding-left:30px"><em><?php _e('Posts from this category will be displayed in the gallery.', DFCG_DOMAIN); ?></em></span></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Number of Posts to display:', DFCG_DOMAIN); ?></th>
							<td><select name="dfcg_plugin_settings[posts-number]">
								<option style="padding-right:10px;" value="2" <?php selected('2', $dfcg_options['posts-number']); ?>>2</option>
								<option style="padding-right:10px;" value="3" <?php selected('3', $dfcg_options['posts-number']); ?>>3</option>
								<option style="padding-right:10px;" value="4" <?php selected('4', $dfcg_options['posts-number']); ?>>4</option>
								<option style="padding-right:10px;" value="5" <?php selected('5', $dfcg_options['posts-number']); ?>>5</option>
								<option style="padding-right:10px;" value="6" <?php selected('6', $dfcg_options['posts-number']); ?>>6</option>
								<option style="padding-right:10px;" value="7" <?php selected('7', $dfcg_options['posts-number']); ?>>7</option>
								<option style="padding-right:10px;" value="8" <?php selected('8', $dfcg_options['posts-number']); ?>>8</option>
								<option style="padding-right:10px;" value="9" <?php selected('9', $dfcg_options['posts-number']); ?>>9</option>
								<option style="padding-right:10px;" value="10" <?php selected('10', $dfcg_options['posts-number']); ?>>10</option>
								<option style="padding-right:10px;" value="11" <?php selected('11', $dfcg_options['posts-number']); ?>>11</option>
								<option style="padding-right:10px;" value="12" <?php selected('12', $dfcg_options['posts-number']); ?>>12</option>
								<option style="padding-right:10px;" value="13" <?php selected('13', $dfcg_options['posts-number']); ?>>13</option>
								<option style="padding-right:10px;" value="14" <?php selected('14', $dfcg_options['posts-number']); ?>>14</option>
								<option style="padding-right:10px;" value="15" <?php selected('15', $dfcg_options['posts-number']); ?>>15</option>
								</select>
								<span style="padding-left:70px"><em><?php _e('The minimum number of Posts is 2, the maximum is 15 (for performance reasons).', DFCG_DOMAIN); ?></em></span></td>
						</tr>
					</tbody>
				</table>
<?php }


/**
* One Category default image folder: content
* WP ONLY
*
* @global array $dfcg_options plugin options from db
* @since 3.2.2
*/
function dfcg_ui_onecat_wp() {
	global $dfcg_options;
	?>
 	<table class="optiontable form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('URL to default "Category" images folder:', DFCG_DOMAIN); ?></th>
				<td><?php _e('Enter the URL to the folder which contains the default images.  The default images will be pulled into the gallery in the event that Posts do not have an image specified in the Write Post DCG Metabox <strong>Image URL</strong>.  This must be an <b>absolute</b> URL.  For example, if your default images are stored in a folder named "default" in your <em>wp-content/uploads</em> folder, the URL entered here will be:', DFCG_DOMAIN); ?> <em>http://www.yourdomain.com/wp-content/uploads/default/</em></td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td><input name="dfcg_plugin_settings[defimgonecat]" id="dfcg-defimgonecat" size="75" value="<?php echo $dfcg_options['defimgonecat']; ?>" /></td> 
    		</tr>
		</tbody>
	</table>
<?php }


/**
* Pages: box and content
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_pages() {
	global $dfcg_options;
	?>
	<div id="pages-method" class="postbox">
		<h3>2.3 PAGES <?php _e('Settings', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('Configure this section if you chose Pages in the', DFCG_DOMAIN); ?> <a href="#gallery-method">Gallery Method</a> <?php _e('Settings', DFCG_DOMAIN); ?>.</p>
				<table class="optiontable form-table">
					<tbody>
						<tr>
							<th scope="row"><?php _e('Page ID numbers:', DFCG_DOMAIN); ?></th>
							<td><input name="dfcg_plugin_settings[pages-selected]" id="dfcg-pages-selected" size="75" value="<?php echo $dfcg_options['pages-selected']; ?>" /><br />
							<em><?php _e("Enter ID's in a comma separated list with no spaces, eg: 2,7,8,19,21", DFCG_DOMAIN); ?></em></td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Use Custom Image Order:', DFCG_DOMAIN); ?></th>
							<td><input name="dfcg_plugin_settings[pages-sort-control]" id="dfcg-pages-sort-control" type="checkbox" value="1" <?php checked('true', $dfcg_options['pages-sort-control']); ?> />
							<span style="padding-left:15px"><em><?php _e("Check the box if you want to apply your own ordering to the images in the Gallery.", DFCG_DOMAIN); ?></em></span></td>
						</tr>
					</tbody>
				</table>
<?php }


/**
* Pages default image: content
* WP ONLY
*
* @global array $dfcg_options plugin options from db
* @since 3.2.2
*/
function dfcg_ui_pages_wp() {
	global $dfcg_options;
	?>
	<table class="optiontable form-table">
		<tbody>
			<tr>
				<th scope="row"><?php _e('Specify a default image:', DFCG_DOMAIN); ?></th>
				<td><?php _e("This image will be displayed in the event that your Pages do not have an image specified in the Write Page DCG Metabox <strong>Image URL</strong> to one of your selected Pages.", DFCG_DOMAIN); ?><br /><?php _e('Upload a suitable image to your server and enter the absolute URL to this default image.', DFCG_DOMAIN); ?><br /><?php _e('For example: ', DFCG_DOMAIN); ?><em>http://www.yourdomain.com/somefolder/anotherfolder/mydefaultimage.jpg</em></td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td><input name="dfcg_plugin_settings[defimgpages]" id="dfcg-defimgpages" size="100" value="<?php echo $dfcg_options['defimgpages']; ?>" /></td>
			</tr>
		</tbody>
	</table>
<?php }


/**
* Default Desc: box and content
*
* @uses dfcg_ui_buttons()
*
* @global array $dfcg_options plugin options from db
* @since 3.2.2
*/
function dfcg_ui_defdesc() {
	global $dfcg_options;
	?>
	<div id="default-desc" class="postbox">
		<h3><?php _e('3. Slide Pane Descriptions:', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('This option is applicable to all', DFCG_DOMAIN); ?> <a href="#gallery-method">Gallery Method</a> <?php _e('Settings', DFCG_DOMAIN); ?>. <?php _e('Choose between Manual or Auto Description methods for displaying a description for each image in the gallery Slide Pane, or select None if you do not want to display any descriptions in the Slide Pane:', DFCG_DOMAIN); ?> <em><?php _e('The Slide Pane has relatively little space. It is recommended to keep the description short, probably less than 25 words or so.', DFCG_DOMAIN); ?></em></p>
			
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[desc-method]" id="desc-method-manual" type="radio" style="margin-right:5px;" value="manual" <?php checked('manual', $dfcg_options['desc-method']); ?> />
						<label for="desc-method-manual"><?php _e('Manual', DFCG_DOMAIN); ?></label></th>
						<td><p><?php _e('With this method the plugin looks for the image description in this sequence: (1) a manual description entered in the Write Post/Page <strong>Slide Pane Description</strong>, (2) a Category Description if that exists (not applicable to the Pages Gallery Method), (3) the default description created here, or finally (4) the Auto description.', DFCG_DOMAIN); ?></p>
							<div class="dfcg-tip-box">
								<p><b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e('Want to use Category Descriptions? Set them up in Dashboard>Posts>Categories.', DFCG_DOMAIN); ?></p>
								<p><b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e('Even if you have selected Manual, if you intend to use Auto text as a fallback in the event no manual descriptions have been set for individual Posts/Pages, set the Auto number of characters and More link options shown under Auto options below.', DFCG_DOMAIN); ?></p>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td><label for="dfcg-defimagedesc"><b><?php _e('Manual default Description:', DFCG_DOMAIN); ?></b> <em><?php _e('Allowed XHTML tags are:', DFCG_DOMAIN); ?></em> &lt;a href=" " title=" "&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;br /&gt;</label><br />
						<textarea name="dfcg_plugin_settings[defimagedesc]" id="dfcg-defimagedesc" cols="85" rows="2"><?php echo stripslashes( $dfcg_options['defimagedesc'] ); ?></textarea></td>
					</tr>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[desc-method]" id="desc-method-auto" type="radio" style="margin-right:5px;" value="auto" <?php checked('auto', $dfcg_options['desc-method']); ?> />
						<label for="desc-method-auto"><?php _e('Auto', DFCG_DOMAIN); ?></label></th>
						<td><?php _e('Descriptions are created automatically as a custom excerpt from your Post/Page content. The length of this custom excerpt is set in the below input field.', DFCG_DOMAIN); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td><input name="dfcg_plugin_settings[max-char]" id="dfcg-max-char" size="5" value="<?php echo $dfcg_options['max-char']; ?>" /><span style="padding-left:20px"><em><?php _e('Number of characters to display in the Slide Pane description.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td><input name="dfcg_plugin_settings[more-text]" id="dfcg-more-text" size="15" value="<?php echo $dfcg_options['more-text']; ?>" /><span style="padding-left:20px"><em><?php _e('Text for "more" link added to Auto description.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[desc-method]" id="desc-method-none" type="radio" style="margin-right:5px;" value="none" <?php checked('none', $dfcg_options['desc-method']); ?> />
						<label for="desc-method-none"><?php _e('None', DFCG_DOMAIN); ?></label></th>
						<td><p><?php _e('With this method no descriptions will be displayed in the Slide Pane, only the Post/Page title.', DFCG_DOMAIN); ?></p></td>
					</tr>
				</tbody>
			</table>
			<?php dfcg_ui_buttons(); ?>
<?php }


/**
* Gallery CSS: box and content
*
* @uses dfcg_ui_buttons()
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_css() {
	global $dfcg_options;
	?>
	<div id="gallery-css" class="postbox">
		<h3><?php _e('4. Gallery size and CSS options (REQUIRED):', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('Configure various layout and CSS options for your gallery including the size of the gallery, the height of the Slide Pane, gallery border, and the font sizes, colours and margins for the text displayed in the Slide Pane.', DFCG_DOMAIN); ?></p>	
			
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><?php _e('Gallery Width:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[gallery-width]" id="dfcg-gallery-width" size="5" value="<?php echo $dfcg_options['gallery-width']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 460px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Gallery Height:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[gallery-height]" id="dfcg-gallery-height" size="5" value="<?php echo $dfcg_options['gallery-height']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 250px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Gallery border width:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[gallery-border-thick]" id="dfcg-gallery-border-thick" size="3" value="<?php echo $dfcg_options['gallery-border-thick']; ?>" />&nbsp;px <span style="padding-left:20px;"> <?php _e("If you don't want a border enter 0 in this box.", DFCG_DOMAIN); ?> <em><?php _e('Default is 1px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Gallery border colour:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[gallery-border-colour]" id="dfcg-gallery-border-colour" size="8" value="<?php echo $dfcg_options['gallery-border-colour']; ?>" />&nbsp;<span style="padding-left:7px;"><?php _e('Enter color hex code like this #000000.', DFCG_DOMAIN); ?> <em><?php _e('Default is #000000.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php if( $dfcg_options['scripts'] == 'jquery' ) : ?>
					<tr valign="top">
						<th scope="row"><?php _e('Gallery Background', DFCG_DOMAIN); ?>*:</th>
						<td><input name="dfcg_plugin_settings[gallery-background]" id="dfcg-gallery-background" size="8" value="<?php echo $dfcg_options['gallery-background']; ?>" />&nbsp;<span style="padding-left:7px;"><?php _e('Enter color hex code like this #000000.', DFCG_DOMAIN); ?> <em><?php _e('Default is #000000.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php endif; ?>
					<tr valign="top">
						<th scope="row"><?php _e('Slide Pane Height:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-height]" id="dfcg-slide-height" size="3" value="<?php echo $dfcg_options['slide-height']; ?>" /> px <span style="padding-left:20px;"><em><?php _e('Default is 50px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php if( $dfcg_options['scripts'] == 'jquery' ) : ?>
					<tr valign="top">
						<th scope="row"><?php _e('Slide Pane Background', DFCG_DOMAIN); ?>*:</th>
						<td><input name="dfcg_plugin_settings[slide-overlay-color]" id="dfcg-slide-overlay-color" size="8" value="<?php echo $dfcg_options['slide-overlay-color']; ?>" />&nbsp;<span style="padding-left:7px;"><?php _e('Enter color hex code like this #000000.', DFCG_DOMAIN); ?> <em><?php _e('Default is #000000.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Slide Pane Position', DFCG_DOMAIN); ?>*:</th>
						<td><select name="dfcg_plugin_settings[slide-overlay-position]">
							<option style="padding-right:10px;" value="bottom" <?php selected('bottom', $dfcg_options['slide-overlay-position']); ?>>bottom</option>
							<option style="padding-right:10px;" value="top" <?php selected('top', $dfcg_options['slide-overlay-position']); ?>>top</option>
							</select>&nbsp;<span style="padding-left:6px;"><?php _e('Choose position of Slide Pane.', DFCG_DOMAIN); ?> <em><?php _e('Default is bottom.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php else : ?>
					<?php endif; ?>
					<tr valign="top">
						<th scope="row"><?php _e('Heading font size:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-h2-size]" id="dfcg-slide-h2-size" size="3" value="<?php echo $dfcg_options['slide-h2-size']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 12px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php if( $dfcg_options['scripts'] == 'jquery' ) : ?>
					<tr valign="top">
						<th scope="row"><?php _e('Heading font weight', DFCG_DOMAIN); ?>*:</th>
						<td><select name="dfcg_plugin_settings[slide-h2-weight]">
							<option style="padding-right:10px;" value="bold" <?php selected('bold', $dfcg_options['slide-h2-weight']); ?>>bold</option>
							<option style="padding-right:10px;" value="normal" <?php selected('normal', $dfcg_options['slide-h2-weight']); ?>>normal</option>
							</select>&nbsp;<span style="padding-left:6px;"><?php _e('Choose Heading font-weight.', DFCG_DOMAIN); ?> <em><?php _e('Default is bold.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php else : ?>
					<?php endif; ?>
					<tr valign="top">
						<th scope="row"><?php _e('Heading font colour:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-h2-colour]" id="dfcg-slide-h2-colour" size="8" value="<?php echo $dfcg_options['slide-h2-colour']; ?>" />&nbsp;<span style="padding-left:7px;"><?php _e('Enter color hex code like this #000000.', DFCG_DOMAIN); ?> <em><?php _e('Default is #FFFFFF.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Heading Padding top and bottom:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-h2-padtb]" id="dfcg-slide-h2-padtb" size="3" value="<?php echo $dfcg_options['slide-h2-padtb']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 0px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Heading Padding left and right:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-h2-padlr]" id="dfcg-slide-h2-padlr" size="3" value="<?php echo $dfcg_options['slide-h2-padlr']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 0px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Heading Margin top and bottom:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-h2-margtb]" id="dfcg-slide-h2-margtb" size="3" value="<?php echo $dfcg_options['slide-h2-margtb']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 2px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Heading Margin left and right:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-h2-marglr]" id="dfcg-slide-h2-marglr" size="3" value="<?php echo $dfcg_options['slide-h2-marglr']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 5px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Description font size:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-p-size]" id="dfcg-slide-p-size" size="3" value="<?php echo $dfcg_options['slide-p-size']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 11px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Description font colour:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-p-colour]" id="dfcg-slide-p-colour" size="8" value="<?php echo $dfcg_options['slide-p-colour']; ?>" />&nbsp;<span style="padding-left:7px;"><?php _e('Enter color hex code like this #000000.', DFCG_DOMAIN); ?> <em><?php _e('Default is #FFFFFF.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php if( $dfcg_options['scripts'] == 'jquery' ) : ?>
					<tr valign="top">
						<th scope="row"><?php _e('Description line height', DFCG_DOMAIN); ?>*:</th>
						<td><input name="dfcg_plugin_settings[slide-p-line-height]" id="dfcg-slide-p-line-height" size="3" value="<?php echo $dfcg_options['slide-p-line-height']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 14px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php else : ?>
					<?php endif; ?>
					<tr valign="top">
						<th scope="row"><?php _e('Description Padding top and bottom:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-p-padtb]" id="dfcg-slide-p-padtb" size="3" value="<?php echo $dfcg_options['slide-p-padtb']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 0px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Description Padding left and right:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-p-padlr]" id="dfcg-slide-p-padlr" size="3" value="<?php echo $dfcg_options['slide-p-padlr']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 0px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Description Margin top and bottom:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-p-margtb]" id="dfcg-slide-p-margtb" size="3" value="<?php echo $dfcg_options['slide-p-margtb']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 2px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Description Margin left and right:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-p-marglr]" id="dfcg-slide-p-marglr" size="3" value="<?php echo $dfcg_options['slide-p-marglr']; ?>" />&nbsp;px <span style="padding-left:20px;"><em><?php _e('Default is 5px.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php // More link CSS added for Auto description option ?>
					<tr valign="top">
						<th scope="row"><?php _e('Description More link colour:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-p-a-color]" id="dfcg-slide-p-a-color" size="8" value="<?php echo $dfcg_options['slide-p-a-color']; ?>" />&nbsp;<span style="padding-left:7px;"><?php _e('Only applicable if you selected', DFCG_DOMAIN); ?> <a href="#default-desc"><?php _e('Auto Descriptions', DFCG_DOMAIN); ?></a>. <?php _e('Enter color hex code like this #000000.', DFCG_DOMAIN); ?> <em><?php _e('Default is #FFFFFF.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Description More link font weight:', DFCG_DOMAIN); ?></th>
						<td><select name="dfcg_plugin_settings[slide-p-a-weight]">
							<option style="padding-right:10px;" value="bold" <?php selected('bold', $dfcg_options['slide-p-a-weight']); ?>>bold</option>
							<option style="padding-right:10px;" value="normal" <?php selected('normal', $dfcg_options['slide-p-a-weight']); ?>>normal</option>
							</select>&nbsp;<span style="padding-left:6px;"><?php _e('Only applicable if you selected', DFCG_DOMAIN); ?> <a href="#default-desc"><?php _e('Auto Descriptions', DFCG_DOMAIN); ?></a>. <?php _e('Choose More link font-weight.', DFCG_DOMAIN); ?> <em><?php _e('Default is normal.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Description More link hover colour:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slide-p-ahover-color]" id="dfcg-slide-p-ahover-color" size="8" value="<?php echo $dfcg_options['slide-p-ahover-color']; ?>" />&nbsp;<span style="padding-left:7px;"><?php _e('Only applicable if you selected', DFCG_DOMAIN); ?> <a href="#default-desc"><?php _e('Auto Descriptions', DFCG_DOMAIN); ?></a>. <?php _e('Enter color hex code like this #000000.', DFCG_DOMAIN); ?> <em><?php _e('Default is #FFFFFF.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Description More link hover font weight:', DFCG_DOMAIN); ?></th>
						<td><select name="dfcg_plugin_settings[slide-p-ahover-weight]">
							<option style="padding-right:10px;" value="bold" <?php selected('bold', $dfcg_options['slide-p-ahover-weight']); ?>>bold</option>
							<option style="padding-right:10px;" value="normal" <?php selected('normal', $dfcg_options['slide-p-ahover-weight']); ?>>normal</option>
							</select>&nbsp;<span style="padding-left:6px;"><?php _e('Only applicable if you selected', DFCG_DOMAIN); ?> <a href="#default-desc"><?php _e('Auto Descriptions', DFCG_DOMAIN); ?></a>. <?php _e('Choose More link hover font-weight.', DFCG_DOMAIN); ?> <em><?php _e('Default is bold.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
				</tbody>
			</table>
			<?php dfcg_ui_buttons(); ?>
<?php }


/**
* Select Javascript Framework
*
* @uses dfcg_ui_buttons()
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_js_framework() {
	global $dfcg_options;
	?>
	<div id="gallery-js-scripts" class="postbox">
		<h3><?php _e('5. Select Javascript framework (OPTIONAL):', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('Select the javascript framework to be used to display the gallery.', DFCG_DOMAIN); ?></p>
			
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[scripts]" id="dfcg-scripts-mootools" type="radio" style="margin-right:5px;" value="mootools" <?php checked('mootools', $dfcg_options['scripts']); ?> />
						<label for="dfcg-scripts-mootools">Mootools (Default)</label></th>
						<td><?php _e('Use SmoothGallery Mootools script. This is the default setting.', DFCG_DOMAIN); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[scripts]" id="dfcg-scripts-jquery" type="radio" style="margin-right:5px;" value="jquery" <?php checked('jquery', $dfcg_options['scripts']); ?> />
						<label for="dfcg-scripts-jquery">jQuery</label></th>
						<td><?php _e('Use alternative jQuery script. Select this option in case of javascript conflicts with other plugins.', DFCG_DOMAIN); ?><br />
						<em><b><?php _e('Note', DFCG_DOMAIN); ?></b>: <?php _e('This script does not currently feature a Carousel (in development).', DFCG_DOMAIN); ?></em></td>
					</tr>
				</tbody>
			</table>
			<?php dfcg_ui_buttons(); ?>
<?php }


/**
* Javascript options: box and content
*
* @uses dfcg_ui_buttons()
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_javascript() {
	global $dfcg_options;
	?>
	<div id="gallery-js" class="postbox">
		<h3><?php _e('6. Javascript configuration options (OPTIONAL):', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e("Configure various default javascript settings for your gallery. The inclusion of these options in this Settings page saves you having to customise the plugin's javascript files.", DFCG_DOMAIN); ?></p>

			<table class="optiontable form-table">
				<tbody>
					<?php if( $dfcg_options['scripts'] == 'mootools' ) : ?>
					<tr valign="top">
						<th scope="row"><?php _e('Show Carousel:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[showCarousel]" id="dfcg-showCarousel" type="checkbox" value="true" <?php checked('true', $dfcg_options['showCarousel']); ?> /><span style="padding-left:50px"><em><?php _e('Check the box to display thumbnail Carousel. Default is CHECKED.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Carousel label:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[textShowCarousel]" id="dfcg-textShowCarousel" size="25" value="<?php echo $dfcg_options['textShowCarousel']; ?>" /><span style="padding-left:30px"><em><?php _e('Label for Carousel tab. Only visible if "Show Carousel" is checked. Default is Featured Articles.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Show Slide Pane:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[showInfopane]" id="dfcg-showInfopane" type="checkbox" value="1" <?php checked('true', $dfcg_options['showInfopane']); ?> /><span style="padding-left:50px"><em><?php _e('Check the box to display Slide Pane. Default is CHECKED.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Animate Slide Pane:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slideInfoZoneSlide]" id="dfcg-slideInfoZoneSlide" type="checkbox" value="1" <?php checked('true', $dfcg_options['slideInfoZoneSlide']); ?> /><span style="padding-left:50px"><em><?php _e('Check the box to have Slide Pane slide into view. Default is CHECKED.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php endif; ?>
					<tr valign="top">
						<th scope="row"><?php _e('Slide Pane Opacity:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[slideInfoZoneOpacity]" id="dfcg-slideInfoZoneOpacity" size="10" value="<?php echo $dfcg_options['slideInfoZoneOpacity']; ?>" /><span style="padding-left:30px"><em><?php _e('Opacity of Slide Pane. 1.0 is fully opaque, 0.0 is fully transparent. Default is 0.7.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php if( $dfcg_options['scripts'] == 'mootools' ) : ?>
					<tr valign="top">
						<th scope="row"><?php _e('Timed transitions:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[timed]" id="dfcg-timed" type="checkbox" value="1" <?php checked('true', $dfcg_options['timed']); ?> /><span style="padding-left:50px"><em><?php _e('Check the box to have timed image transitions. Default is CHECKED.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<?php endif; ?>
					<tr valign="top">
						<th scope="row"><?php _e('Transitions delay:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[delay]" id="dfcg-delay" size="10" value="<?php echo $dfcg_options['delay']; ?>" /><span style="padding-left:30px"><em><?php _e('Enter the delay time (in milliseconds, minimum 1000) between image transitions. Default is 9000.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
				<?php if( $dfcg_options['scripts'] == 'jquery' ) : ?>
					<tr valign="top">
						<th scope="row"><?php _e('Transitions speed', DFCG_DOMAIN); ?>*:</th>
						<td><input name="dfcg_plugin_settings[transition-speed]" id="dfcg-transition-speed" size="10" value="<?php echo $dfcg_options['transition-speed']; ?>" /><span style="padding-left:30px"><em><?php _e('Enter the speed of image transitions (in milliseconds, minimum 1000). Default is 1500.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Navigation theme', DFCG_DOMAIN); ?>*:</th>
						<td><select name="dfcg_plugin_settings[nav-theme]">
							<option style="padding-right:10px;" value="light" <?php selected('light', $dfcg_options['nav-theme']); ?>>light</option>
							<option style="padding-right:10px;" value="dark" <?php selected('dark', $dfcg_options['nav-theme']); ?>>dark</option>
							</select>&nbsp;<span style="padding-left:6px;"><?php _e('Choose style of next/prev arrows: light or dark. <em>Default is light.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<td style="width:110px"><?php _e('Pause on hover', DFCG_DOMAIN); ?>*:</td>
						<td><input name="dfcg_plugin_settings[pause-on-hover]" id="dfcg-pause-on-hover" type="checkbox" value="1" <?php checked('true', $dfcg_options['pause-on-hover']); ?> /><span style="padding-left:50px"><?php _e('Check the box to pause the slideshow on mouseover. <em>Default is CHECKED.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<td><?php _e('Fade panels', DFCG_DOMAIN); ?>*:</td>
						<td><input name="dfcg_plugin_settings[fade-panels]" id="dfcg-fade-panels" type="checkbox" value="1" <?php checked('true', $dfcg_options['fade-panels']); ?> /><span style="padding-left:50px"><?php _e('Check the box to use fade transitions between images. <em>Default is CHECKED.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
				<?php endif; ?>
				<?php if( $dfcg_options['scripts'] == 'mootools' ) : ?>
					<tr valign="top">
						<th scope="row"><?php _e('Transition type:', DFCG_DOMAIN); ?></th>
						<td><select name="dfcg_plugin_settings[defaultTransition]">
							<option style="padding-right:10px;" value="fade" <?php selected('fade', $dfcg_options['defaultTransition']); ?>>fade</option>
							<option style="padding-right:10px;" value="fadeslideleft" <?php selected('fadeslideleft', $dfcg_options['defaultTransition']); ?>>fadeslideleft</option>
							<option style="padding-right:10px;" value="continuousvertical" <?php selected('continuousvertical', $dfcg_options['defaultTransition']); ?>>continuousvertical</option>
							<option style="padding-right:10px;" value="continuoushorizontal" <?php selected('continuoushorizontal', $dfcg_options['defaultTransition']); ?>>continuoushorizontal</option>
							</select><span style="padding-left:30px"><em><?php _e('Select the type of image transition from "fade", "fadeslideleft", "continuoushorizontal" or "continuousvertical". Default is "fade".', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Disable Mootools:') ?></th>
						<td><input name="dfcg_plugin_settings[mootools]" id="dfcg-mootools" type="checkbox" value="1" <?php checked('1', $dfcg_options['mootools']); ?> /><span style="padding-left:50px"><em><?php _e('Check the box ONLY in the event that another plugin is already loading the Mootools Javascript library files in your site. Default is UNCHECKED.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
				<?php endif; ?>					
				</tbody>
			</table>
			<?php dfcg_ui_buttons(); ?>
<?php }


/**
* Restrict Scripts loading: box and content
*
* @uses dfcg_ui_buttons()
*
* @global array $dfcg_options plugin options from db
* @since 3.2.2
*/
function dfcg_ui_restrict_scripts() {
	global $dfcg_options;
	?>
	<div id="restrict-scripts" class="postbox">
		<h3><?php _e('7. Restrict script loading (RECOMMENDED):', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e("This option lets you restrict the loading of the plugin's javascript to the page that will actually display the gallery. This prevents the scripts being loaded on all pages unnecessarily, which will help to minimise the impact of the plugin on page loading times. This option applies to both mootools and jquery <a href=\"#gallery-js-scripts\">Javascript Framework settings</a>.", DFCG_DOMAIN); ?></p>

			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[limit-scripts]" id="limit-scripts-home" type="radio" style="margin-right:5px;" value="homepage" <?php checked('homepage', $dfcg_options['limit-scripts']); ?> />
						<label for="limit-scripts-home"><?php _e('Home page only', DFCG_DOMAIN); ?></label></th>
						<td><p><?php _e("Select this option to load the plugin's scripts ONLY on the home page.", DFCG_DOMAIN); ?></p>
						<div class="dfcg-tip-box"><p><b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e('Best option if the gallery will only be used on the home page of your site. This is the default.', DFCG_DOMAIN); ?><br />
						<b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e('Select this option if you use a Static Front Page defined in Dashboard > Settings > Reading and the gallery will only be shown on the home page.', DFCG_DOMAIN); ?></p></div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[limit-scripts]" id="limit-scripts-pages" type="radio" style="margin-right:5px;" value="page" <?php checked('page', $dfcg_options['limit-scripts']); ?> />
						<label for="limit-scripts-pages"><?php _e('Pages', DFCG_DOMAIN); ?></label></th>
						<td><?php _e("Select this option to load the plugin's scripts ONLY when a specific Page is being used to display the gallery.", DFCG_DOMAIN); ?><br />
						<div class="dfcg-tip-box"><p><b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e('Best option if the gallery is displayed using a Page. Enter the Page <strong>ID</strong> below.', DFCG_DOMAIN); ?></p></div></td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td><input name="dfcg_plugin_settings[page-ids]" id="dfcg-page-ids" size="45" value="<?php echo $dfcg_options['page-ids']; ?>" /><span style="padding-left:20px"><em><?php _e('ID of the Page, eg 42. Multiple pages are also possible, like this: 2,43,17', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[limit-scripts]" id="limit-scripts-page" type="radio" style="margin-right:5px;" value="pagetemplate" <?php checked('pagetemplate', $dfcg_options['limit-scripts']); ?> />
						<label for="limit-scripts-page"><?php _e('Specific Page Template', DFCG_DOMAIN); ?></label></th>
						<td><?php _e("Select this option to load the plugin's scripts ONLY when a specific Page Template is being used to display the gallery.", DFCG_DOMAIN); ?><br />
						<div class="dfcg-tip-box"><p><b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e('Best option if the gallery is displayed using a Page Template. Enter the Page Template <strong>filename</strong> below.', DFCG_DOMAIN); ?></p></div></td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td><input name="dfcg_plugin_settings[page-filename]" id="dfcg-page-filename" size="45" value="<?php echo $dfcg_options['page-filename']; ?>" /><span style="padding-left:20px"><em><?php _e('Filename of the Page Template, eg mypagetemplate.php.', DFCG_DOMAIN); ?></em></span></td>
					</tr>
					<tr valign="top">
						<th scope="row"><input name="dfcg_plugin_settings[limit-scripts]" id="limit-scripts-other" style="margin-right:5px;" type="radio" value="other" <?php checked('other', $dfcg_options['limit-scripts']); ?> />
						<label for="limit-scripts-other"><?php _e('Other', DFCG_DOMAIN); ?></label></th>
						<td><?php _e('Check this option if none of the above apply to your setup.', DFCG_DOMAIN); ?><br />
						<div class="dfcg-tip-box"><p><b><?php _e('Tip', DFCG_DOMAIN); ?></b>: <?php _e("The plugin's scripts will be loaded in every page. Not recommended.", DFCG_DOMAIN); ?></p></div></td>
					</tr>
				</tbody>
			</table>
			<?php dfcg_ui_buttons(); ?>
<?php }


/**
* Error Messages: box and content
*
* @uses dfcg_ui_buttons()
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_errors() {
	global $dfcg_options;
	?>
	<div id="error-messages" class="postbox">
		<h3><?php _e('8. Error Message options (OPTIONAL)', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('The plugin produces informative error messages in the event that Posts, Pages, images and descriptions have not been configured properly, which will assist with troubleshooting. These error messages, if activated, are output to the Page Source of the gallery as HTML comments.', DFCG_DOMAIN); ?> <em><?php _e('Error message explanations can be found in the', DFCG_DOMAIN); ?> <a href="http://www.studiograsshopper.ch/dynamic-content-gallery/error-messages/">Dynamic Content Gallery <?php _e('Error Messages', DFCG_DOMAIN); ?></a> <?php _e('guide.', DFCG_DOMAIN); ?></em></p>
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><?php _e('Show Error Messages:', DFCG_DOMAIN); ?></th>
						<td><input name="dfcg_plugin_settings[errors]" id="dfcg-errors" type="checkbox" value="1" <?php checked('true', $dfcg_options['errors']); ?> /><span style="font-size:11px;margin-left:20px;"><em><?php _e('Check box to show Page Source error messages, uncheck the box to hide them. Default is UNCHECKED.', DFCG_DOMAIN)?></em></span></td>
					</tr>
				</tbody>
			</table>
			<?php dfcg_ui_buttons(); ?>
<?php }


/**
* Posts/Pages edit columns: box and content
*
* @uses dfcg_ui_buttons()
*
* @global array $dfcg_options plugin options from db
* @since 3.2.2
*/
function dfcg_ui_columns() {
	global $dfcg_options;
	?>
	<div id="custom-columns" class="postbox">
		<h3><?php _e('9. Add Custom Field columns to Posts and Pages Edit screen (OPTIONAL)', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('These settings let you display a column in the Edit Posts and Edit Pages screens to show the value of the <strong>Image URL</strong> and manual <strong>Slide Pane Descriptions</strong> entered in the Write Post/Page DCG Metabox. This can be useful to help keep track of the Image URLs and manual Slide Pane Descriptions without having to open each individual Post or Page.', DFCG_DOMAIN); ?></p>
			<p><em><?php _e('To hide the additional columns in the Edit Posts and Edit Pages screens, uncheck the boxes then click the "Save Changes" button. Default is CHECKED.', DFCG_DOMAIN)?></em></p>
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><?php _e('Show columns in Edit Posts:', DFCG_DOMAIN); ?></th>
						<td>dfcg-image URL: <input type="checkbox" name="dfcg_plugin_settings[posts-column]" id="dfcg-posts-column" value="1" <?php checked('true', $dfcg_options['posts-column']); ?> />
						<span style="padding-left:50px;"><?php _e('dfcg-desc Description:', DFCG_DOMAIN); ?></span> <input type="checkbox" name="dfcg_plugin_settings[posts-desc-column]" id="dfcg-posts-desc-column" value="1" <?php checked('true', $dfcg_options['posts-desc-column']); ?> /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Show columns in Edit Pages:', DFCG_DOMAIN); ?></th>
						<td>dfcg-image URL: <input type="checkbox" name="dfcg_plugin_settings[pages-column]" id="dfcg-pages-column" value="1" <?php checked('true', $dfcg_options['pages-column']); ?> />
						<span style="padding-left:50px;"><?php _e('dfcg-desc Description:', DFCG_DOMAIN); ?></span> <input type="checkbox" name="dfcg_plugin_settings[pages-desc-column]" id="dfcg-pages-desc-column" value="1" <?php checked('true', $dfcg_options['pages-desc-column']); ?> />
						<span style="padding-left:50px;"><?php _e('Sort Order:', DFCG_DOMAIN); ?></span> <input type="checkbox" name="dfcg_plugin_settings[pages-sort-column]" id="dfcg-pages-sort-column" value="1" <?php checked('true', $dfcg_options['pages-sort-column']); ?> /></td>
					</tr>
				</tbody>
			</table>
			<?php dfcg_ui_buttons(); ?>
<?php }


/**
* Form hidden fields
* WP ONLY
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_hidden_wp() {
	global $dfcg_options;
	?>
	<?php // Always hidden in WP/WPMU ?>
	<input name="dfcg_plugin_settings[homeurl]" id="dfcg-homeurl" type="hidden" value="<?php echo $dfcg_options['homeurl']; ?>" />
	<input name="dfcg_plugin_settings[just-reset]" id="dfcg-just-reset" type="hidden" value="<?php echo $dfcg_options['just-reset']; ?>" />
	<?php if($dfcg_options['scripts'] == 'mootools' ) : // +9 Hidden if mootools is loaded ?>
	<input name="dfcg_plugin_settings[slide-overlay-color]" id="dfcg-slide-overlay-color" type="hidden" value="<?php echo $dfcg_options['slide-overlay-color']; ?>" />
	<input name="dfcg_plugin_settings[slide-overlay-position]" id="dfcg-slide-overlay-position" type="hidden" value="<?php echo $dfcg_options['slide-overlay-position']; ?>" />
	<input name="dfcg_plugin_settings[slide-h2-weight]" id="dfcg-slide-h2-weight" type="hidden" value="<?php echo $dfcg_options['slide-h2-weight']; ?>" />
	<input name="dfcg_plugin_settings[slide-p-line-height]" id="dfcg-slide-p-line-height" type="hidden" value="<?php echo $dfcg_options['slide-p-line-height']; ?>" />
	<input name="dfcg_plugin_settings[transition-speed]" id="dfcg-transition-speed" type="hidden" value="<?php echo $dfcg_options['transition-speed']; ?>" />
	<input name="dfcg_plugin_settings[nav-theme]" id="dfcg-nav-theme" type="hidden" value="<?php echo $dfcg_options['nav-theme']; ?>" />
	<input name="dfcg_plugin_settings[pause-on-hover]" id="dfcg-pause-on-hover" type="hidden" value="<?php echo $dfcg_options['pause-on-hover']; ?>" />
	<input name="dfcg_plugin_settings[fade-panels]" id="dfcg-fade-panels" type="hidden" value="<?php echo $dfcg_options['fade-panels']; ?>" />
	<input name="dfcg_plugin_settings[gallery-background]" id="dfcg-gallery-background" type="hidden" value="<?php echo $dfcg_options['gallery-background']; ?>" />
	<?php else : // jquery is loaded, +7 Hidden if jquery is loaded ?>
	<input name="dfcg_plugin_settings[showCarousel]" id="dfcg-showCarousel" type="hidden" value="<?php echo $dfcg_options['showCarousel']; ?>" />
	<input name="dfcg_plugin_settings[textShowCarousel]" id="dfcg-textShowCarousel" type="hidden" value="<?php echo $dfcg_options['textShowCarousel']; ?>" />
	<input name="dfcg_plugin_settings[showInfopane]" id="dfcg-showInfopane" type="hidden" value="<?php echo $dfcg_options['showInfopane']; ?>" />
	<input name="dfcg_plugin_settings[slideInfoZoneSlide]" id="dfcg-slideInfoZoneSlide" type="hidden" value="<?php echo $dfcg_options['slideInfoZoneSlide']; ?>" />
	<input name="dfcg_plugin_settings[timed]" id="dfcg-timed" type="hidden" value="<?php echo $dfcg_options['timed']; ?>" />
	<input name="dfcg_plugin_settings[defaultTransition]" id="dfcg-defaultTransition" type="hidden" value="<?php echo $dfcg_options['defaultTransition']; ?>" />
	<input name="dfcg_plugin_settings[mootools]" id="dfcg-mootools" type="hidden" value="<?php echo $dfcg_options['mootools']; ?>" />
	<?php endif; ?>
	
<?php }


/**
* Form hidden fields
* WPMU ONLY
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_hidden_wpmu() {
	global $dfcg_options;
	?>
	<?php // Always hidden in WP/WPMU ?>
	<input name="dfcg_plugin_settings[homeurl]" id="dfcg-homeurl" type="hidden" value="<?php echo $dfcg_options['homeurl']; ?>" />
	<input name="dfcg_plugin_settings[just-reset]" id="dfcg-just-reset" type="hidden" value="<?php echo $dfcg_options['just-reset']; ?>" />
	<?php // Always hidden in WPMU ?>
	<input name="dfcg_plugin_settings[image-url-type]" id="dfcg-image-url-type" type="hidden" value="<?php echo $dfcg_options['image-url-type']; ?>" />
	<input name="dfcg_plugin_settings[imageurl]" id="dfcg-imageurl" type="hidden" value="<?php echo $dfcg_options['imageurl']; ?>" />
	<input name="dfcg_plugin_settings[defimgmulti]" id="dfcg-defimgmulti" type="hidden" value="<?php echo $dfcg_options['defimgmulti']; ?>" />
	<input name="dfcg_plugin_settings[defimgonecat]" id="dfcg-defimgonecat" type="hidden" value="<?php echo $dfcg_options['defimgonecat']; ?>" />
	<input name="dfcg_plugin_settings[defimgpages]" id="dfcg-defimgpages" type="hidden" value="<?php echo $dfcg_options['defimgpages']; ?>" />
	<?php if($dfcg_options['scripts'] == 'mootools' ) : // +9 Hidden if mootools is loaded ?>
	<input name="dfcg_plugin_settings[slide-overlay-color]" id="dfcg-slide-overlay-color" type="hidden" value="<?php echo $dfcg_options['slide-overlay-color']; ?>" />
	<input name="dfcg_plugin_settings[slide-overlay-position]" id="dfcg-slide-overlay-position" type="hidden" value="<?php echo $dfcg_options['slide-overlay-position']; ?>" />
	<input name="dfcg_plugin_settings[slide-h2-weight]" id="dfcg-slide-h2-weight" type="hidden" value="<?php echo $dfcg_options['slide-h2-weight']; ?>" />
	<input name="dfcg_plugin_settings[slide-p-line-height]" id="dfcg-slide-p-line-height" type="hidden" value="<?php echo $dfcg_options['slide-p-line-height']; ?>" />
	<input name="dfcg_plugin_settings[transition-speed]" id="dfcg-transition-speed" type="hidden" value="<?php echo $dfcg_options['transition-speed']; ?>" />
	<input name="dfcg_plugin_settings[nav-theme]" id="dfcg-nav-theme" type="hidden" value="<?php echo $dfcg_options['nav-theme']; ?>" />
	<input name="dfcg_plugin_settings[pause-on-hover]" id="dfcg-pause-on-hover" type="hidden" value="<?php echo $dfcg_options['pause-on-hover']; ?>" />
	<input name="dfcg_plugin_settings[fade-panels]" id="dfcg-fade-panels" type="hidden" value="<?php echo $dfcg_options['fade-panels']; ?>" />
	<input name="dfcg_plugin_settings[gallery-background]" id="dfcg-gallery-background" type="hidden" value="<?php echo $dfcg_options['gallery-background']; ?>" />
	<?php else : // jquery is loaded, +7 Hidden if jquery is loaded ?>
	<input name="dfcg_plugin_settings[showCarousel]" id="dfcg-showCarousel" type="hidden" value="<?php echo $dfcg_options['showCarousel']; ?>" />
	<input name="dfcg_plugin_settings[textShowCarousel]" id="dfcg-textShowCarousel" type="hidden" value="<?php echo $dfcg_options['textShowCarousel']; ?>" />
	<input name="dfcg_plugin_settings[showInfopane]" id="dfcg-showInfopane" type="hidden" value="<?php echo $dfcg_options['showInfopane']; ?>" />
	<input name="dfcg_plugin_settings[slideInfoZoneSlide]" id="dfcg-slideInfoZoneSlide" type="hidden" value="<?php echo $dfcg_options['slideInfoZoneSlide']; ?>" />
	<input name="dfcg_plugin_settings[timed]" id="dfcg-timed" type="hidden" value="<?php echo $dfcg_options['timed']; ?>" />
	<input name="dfcg_plugin_settings[defaultTransition]" id="dfcg-defaultTransition" type="hidden" value="<?php echo $dfcg_options['defaultTransition']; ?>" />
	<input name="dfcg_plugin_settings[mootools]" id="dfcg-mootools" type="hidden" value="<?php echo $dfcg_options['mootools']; ?>" />
	<?php endif; ?>
	
<?php }


/**
* Reset box and form end XHTML
*
* @global array $dfcg_options plugin options from db
* @since 3.0
*/
function dfcg_ui_reset_end() {
	global $dfcg_options;
	?>
	<div class="postbox-sgr">
		<p>
		<input type="checkbox" name="dfcg_plugin_settings[reset]" id="dfcg-reset" value="1" <?php checked('true', $dfcg_options['reset']); ?> />
		<span style="font-weight:bold;padding-left:10px;"><?php _e('Reset all options to the Default settings', DFCG_DOMAIN); ?></span>
		<span style="font-size:11px;padding-left:10px;"><em><?php _e('Check the box, then click the "Save Changes" button.', DFCG_DOMAIN)?></em></span>
		</p>
	</div>
        
	
<?php }


/**
* Credits: box and content
*
* @since 3.0
*/
function dfcg_ui_credits() {
	?>
	<div class="sgr-credits">
		<p><?php _e('For further information please visit these resources:', DFCG_DOMAIN); ?></p>
		<p>
			<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/configuration-guide/"><?php _e('Configuration guide', DFCG_DOMAIN); ?></a> | 
		  	<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/documentation/"><?php _e('Documentation page', DFCG_DOMAIN); ?></a> | 
			<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/faq/"><?php _e('FAQ', DFCG_DOMAIN); ?></a> | 
			<a href="http://www.studiograsshopper.ch/dynamic-content-gallery/error-messages/"><?php _e('Error Messages', DFCG_DOMAIN); ?></a>
		</p>
		<p><?php _e('The Dynamic Content Gallery plugin uses the mootools SmoothGallery script developed by', DFCG_DOMAIN); ?> <a href="http://smoothgallery.jondesign.net/">Jonathan Schemoul</a> <?php _e('and a modified version of the jQuery Galleryview script developed by', DFCG_DOMAIN); ?> <a href="http://www.spaceforaname.com/jquery/galleryview/">Jack Anderson</a>, <?php _e('and was forked from the original Featured Content Gallery v1.0 developed by Jason Schuller. Grateful acknowledgements to Jonathan, Jack and Jason.', DFCG_DOMAIN); ?></p> 
		<p><?php _e('Dynamic Content Gallery plugin for Wordpress and Wordpress Mu', DFCG_DOMAIN); ?>&nbsp;&copy;&nbsp;2008-2010 <a href="http://www.studiograsshopper.ch/">Ade Walker</a>&nbsp;&nbsp;&nbsp;<strong><?php _e('Version: ', DFCG_DOMAIN); ?><?php echo DFCG_VER; ?></strong></p>      
		
	</div><!-- end sgr-credits -->
<?php }


/**
* Uploading images: box and content
* WPMU ONLY
*
* @since 3.2.2
*/
function dfcg_ui_create_wpmu() {
	?>
	<div id="upload-images" class="postbox">
		<h3><?php _e('1. Uploading your images', DFCG_DOMAIN); ?></h3>
		<div class="inside">
			<p><?php _e('Use the Media Uploader in the Write Post/Page screen to upload your gallery images. With the Media Uploader pop-up open, select "Choose Files to Upload" and browse to your chosen image. Once the Media Uploader screen has uploaded your file and finished "crunching", copy the URL shown in the "File URL" box and paste it in to the <strong>Image URL</strong> field in the DCG Metabox in the Write Post/Page screen.', DFCG_DOMAIN); ?></p>
		</div>
	</div>
<?php }