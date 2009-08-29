<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	Dynamic Content Gallery
*	@version	3.0 beta
*
*	Options page for Wordpress Mu.
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
	// WP_Query fails gracefully, and cat=all is ignored
	if( $updated_options['cat-display'] == 0 ) {
		$updated_options['cat-display'] = 'All';
	}
		
	// deal with absolute URLS and Paths: add trailing slash
	$abs_url_opts = array( 'imageurl' );
	foreach( $abs_url_opts as $key ) {
		// Trailingslashit if there is something to do it to
		if( !empty($updated_options[$key]) ) {
			$updated_options[$key] = trailingslashit($updated_options[$key]);
		}
	}
		
	// deal with relative URLS and Paths: add leading slash and add trailing slash
	// we need leading slash because siteurl and homeurl are untrailingslashed!
	$rel_url_opts = array( 'defimgmulti', 'defimgonecat' );
	foreach( $rel_url_opts as $key ) {
		// Only do something if there is something to do it to
		if( !empty($updated_options[$key]) ) {
			// Is there isn't leading slash?
			if( substr($updated_options[$key], 0, 1 ) !== '/' ) {
				// Insert leading slash
				$updated_options[$key] = substr_replace($updated_options[$key], '/', 0, 0);
			}
			// Trailingslash all rel paths
			$updated_options[$key] = trailingslashit($updated_options[$key]);
		}
	}
		
	// deal with the MOOTOOLS checkbox
	$onoff_opts = array( 'mootools' );
	foreach($onoff_opts as $key) {
		$updated_options[$key] = $updated_options[$key] ? '1' : '0';
	}
		
	// deal with the RESET checkbox and other bool options
	$bool_opts = array( 'reset', 'showArrows', 'showCarousel', 'showInfopane', 'timed', 'slideInfoZoneSlide', 'errors' );
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


<div class="wrap" id="dfcgstyle"><a name="top"></a>

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
					<p><em><?php _e('You are using Dynamic Content Gallery version ', DFCG_DOMAIN); echo DFCG_VER; _e(' for Wordpress Mu.', DFCG_DOMAIN); ?></em></p>
					<p>
						<ul>
							<li><a href="#how to">How to add the Dynamic Content Gallery to your Theme</a></li>
							<li><a href="#assign">How to assign an image and a description to each Post/Page</a></li>
							<li><a href="#link">How to assign an external link to a gallery image</a></li>
							<li><a href="#create">1. Uploading your images</a></li>
							<li><a href="#2">2. Gallery Method (REQUIRED)</a></li>
							<li><a href="#2.1">2.1 MULTI OPTION Settings</a> (<em>Required if you selected Multi Option in <a href="#2">Gallery Method</a></em>)</li>
							<li><a href="#2.2">2.2 ONE CATEGORY Settings</a> (<em>Required if you selected One Category in <a href="#2">Gallery Method</a></em>)</li>
							<li><a href="#2.3">2.3 PAGES Settings</a> (<em>Required if you selected Pages in <a href="#2">Gallery Method</a></em>)</li>
							<li><a href="#3">3. Default description (OPTIONAL)</a></li>
							<li><a href="#4">4. Gallery size and CSS options (REQUIRED)</a></li>
							<li><a href="#5">5. Javascript configuration options (OPTIONAL)</a></li>
							<li><a href="#6">6. Restrict script loading (RECOMMENDED)</a></li>
							<li><a href="#7">7. Error message options (OPTIONAL)</a></li>
						</ul>
					</p>
					<p><?php _e('Please read through this page carefully and select your configuration preferences. Some settings are Required, others are Optional, depending on how you want to use the plugin.', DFCG_DOMAIN); ?></p>
					<p><strong><em><?php _e('Error messages: ', DFCG__DOMAIN); ?></strong><?php _e('In the event of configuration errors, the plugin generates informative error messages in the HTML Source of the page where your gallery is loaded. Please refer to these to assist you with troubleshooting any issues.', DFCG_DOMAIN); ?></em></p>
					<p><?php _e('For further information, see the README.txt document supplied with the plugin or visit the', DFCG_DOMAIN); ?> <a href="http://www.studiograsshopper.ch/dynamic-content-gallery-configuration/">Dynamic Content Gallery configuration</a> page and comprehensive <a href="http://www.studiograsshopper.ch/dynamic-content-gallery-v3-faq/">FAQ</a>.</p>
					</div>
					
					<div class="postbox" id="dfcg-info">
						<h4>Resources & Information</h4>
					<p><a href="http://www.studiograsshopper.ch"><img src="<?php echo DFCG_URL . '/admin-assets/sgr_icon_75.jpg'; ?>" alt="studiograsshopper" /></a><strong>Dynamic Content Gallery for WP and WPMU</strong>.<br />Version <?php echo DFCG_VER; ?><br />Author: <a href="http://www.studiograsshopper.ch/">Ade Walker</a></p>
						<p>For further information, or in case of configuration problems, please consult these comprehensive resources:</p>
						<ul>
								<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery-configuration/">Configuration page</a></li>
								<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery-v3-documentation/">Documentation</a></li>
								<li><a href="http://www.studiograsshopper.ch/dynamic-content-gallery-v3-faq/">FAQ</a></li>
								<li><a href="http://www.studiograsshopper.ch/forum/">Support Forum</a></li>
							</ul>
						<p>This plugin represents a considerable investment of my time and energy. If you have found this plugin useful, please consider making a donation to help support future development. Your support will be much appreciated. Thank you! 
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="7415216">
							<input type="image" src="https://www.paypal.com/en_US/CH/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>
					</p>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
			
			<a name="how to"></a>
			<div class="postbox">
				<h3><?php _e('How to add the Dynamic Content Gallery to your Theme:', DFCG_DOMAIN); ?></h3>
				<div class="inside">
					<p><?php _e('Add this code to the relevant theme template file depending on where you want to display the Dynamic Content Gallery:', DFCG_DOMAIN); ?></p>
					<p><code>&lt;?php dynamic_content_gallery(); ?&gt;</code></p>
					<p><em><b><?php _e('If upgrading from a previous version:', DFCG_DOMAIN); ?></b> <?php _e('You may continue to use the code (shown below) in your theme file. However, it is recommended to use the new code (shown above) to ensure compatibility with future versions of the plugin.', DFCG_DOMAIN); ?></em><br /><br />
					<code>&lt;?php include (ABSPATH . '/wp-content/plugins/dynamic-content-gallery-plugin/dynamic-gallery.php'); ?&gt;</code>
					<p><em><b><?php _e('Note: Do not use either of these within the Loop.', DFCG_DOMAIN); ?></b></em></p>
				</div>
			</div>
			
			<a name="assign"></a>
			<div class="postbox">
				<h3><?php _e("How to assign an image and a description to each Post/Page:", DFCG_DOMAIN); ?></h3>
				<div class="inside">
					<p><?php _e("Images are pulled into the gallery from custom fields created in the relevant Posts/Pages:", DFCG_DOMAIN); ?></p> 
					<ul>
						<li>Custom field <strong>dfcg-desc</strong> <?php _e("for the Description which will appear in the gallery Slide Pane. For example: ", DFCG_DOMAIN); ?> <em><?php _e("Here's our latest news!", DFCG_DOMAIN); ?></em></li>
						<li>Custom field <strong>dfcg-image</strong> <?php _e("for the image filename, including extension, with EITHER the full, partial URL, or no URL, depending on your ", DFCG_DOMAIN); ?><a href="#1">Image file management</a> Settings.</li>
					</ul>
				</div>
			</div>
			
			<a name="link"></a>
			<div class="postbox">
				<h3><?php _e("How to assign an external link to a gallery image:", DFCG_DOMAIN); ?></h3>
				<div class="inside">
					<p><?php _e("By default, clicking on an image in the gallery takes the user to the image's Post or Page. If you wish to link a gallery image to a different resource, eg another page on your site or an external site, create the following custom field for the relevant Post/Page:", DFCG_DOMAIN); ?></p> 
					<ul>
						<li>Custom field <strong>dfcg-link</strong> <?php _e("with the full URL for the link. For example: ", DFCG_DOMAIN); ?> <em><?php _e("http://www.anothersite.com", DFCG_DOMAIN); ?></em></li>
					</ul>
				</div>
			</div>
			
			<a name="create"></a>
			<div class="postbox">
				<h3><?php _e('1. Uploading your images', DFCG_DOMAIN); ?></h3>
				<div class="inside">
        			<p>Use the Media Uploader in Write Posts / Write Pages to upload your gallery images. With the Media Uploader pop-up open, select "Choose Files to Upload" and browse to your chosen image. Once the Media Uploader screen has uploaded your file and finsihed "crunching", copy the URL shown in the "File URL" box and paste it in to the <strong>dfcg-image</strong> custom field in the Write Post screen.</p>
				</div>
			</div>
		
			<a name="2"></a>
			<div class="postbox">
				<h3>2. Gallery Method (REQUIRED):</h3>
				<div class="inside">
					<p>The Dynamic Content Gallery offers three different methods for populating the gallery with images. Select the option most appropriate for your needs.</p>
					<table class="optiontable form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><input name="dfcg[populate-method]" style="margin-right:5px;" type="radio" id="dfcg-populate-multi" value="multi-option" <?php checked('multi-option', $dfcg_options['populate-method']); ?> />
									<label for=id="dfcg-populate-multi">Multi Option</label></th>
								<td>Complete freedom to select up to 9 images from a mix of categories. Set up the relevant options in <a href="#2.1">2.1 MULTI OPTION Settings</a><br />
								<em><b>Tip</b>: This is a the original method used in previous versions of the plugin, and the option to choose if you want to mix posts from different categories.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row"><input name="dfcg[populate-method]" style="margin-right:5px;" type="radio" id="dfcg-populate-one" value="one-category" <?php checked('one-category', $dfcg_options['populate-method']); ?> />
									<label for=id="dfcg-populate-one">One Category</label></th>
								<td>Images are pulled from a user-definable number of Posts assigned to one selected Category. Set up the relevant options in <a href="#2.2">2.2 ONE CATEGORY Settings</a><br />
								<em><b>Tip</b>: This is a good option if you use a Featured or News category for highlighting certain posts.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row"><input name="dfcg[populate-method]" style="margin-right:5px;" type="radio" id="dfcg-populate-pages" value="pages" <?php checked('pages', $dfcg_options['populate-method']); ?> />
									<label for=id="dfcg-populate-pages">Pages</label></th>
								<td>Images are pulled from Pages, rather than Posts. Set up the relevant options in <a href="#2.3">2.3 PAGES</a><br />
								<em><b>Tip</b>: This could be a good option if your site is more CMS than Blog.</em></td>
							</tr>
						</tbody>
					</table>
					<p class="submit"><input type="submit" name="info_update" value="<?php _e('Save Changes') ?>" /><a class="button-secondary" href="#top" title="Back to top" style="float:right;">Back to top</a></p>
				</div>
			</div>
			
			<a name="2.1"></a>
			<div class="postbox">
				<h3>2.1 MULTI OPTION Settings</h3>
				<div class="inside">
					<p>Configure this section if you chose Multi Option in the <a href="#2">Gallery Method</a> Settings. The Multi Option method of populating the gallery provides up to 9 image "slots", each of which can be configured with its own Category and "Post Select".</p>
					<p>For the Post Select: enter <strong>1</strong> for the latest post, <strong>2</strong> for the last-but-one post, <strong>3</strong> for the post before that, and so on. Further information on the possible schemes can be found on the <a href="http://www.studiograsshopper.ch/dynamic-content-gallery-configuration/">Dynamic Content Gallery configuration</a> page.</p>
					<p><em><b>Tip</b>: If you want to pull in the latest posts from one category, don't use Multi Option, use the One Category <a href="#2">Gallery Method</a> instead. It's much more efficient in terms of database queries.</em><br />
					<em><b>Tip</b>: Want to show less than 9 images? Delete the contents of the Post Select fields for image slots you don't need.</em></p>
			
					<table class="optiontable form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><strong>Image "Slots"</strong></th>
								<td><strong>Category Select</strong></td>
								<td><strong>Post Select</strong></td>
							</tr>
							<tr valign="top">
								<th scope="row">1st image</th>
								<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat01'], 'name' => 'dfcg[cat01]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
								<td><input name="dfcg[off01]" id="off01" size="5" value="<?php echo $dfcg_options['off01']; ?>" />&nbsp;<em>Ex. Enter <strong>1</strong> for latest post.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">2nd image</th>
								<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat02'], 'name' => 'dfcg[cat02]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
								<td><input name="dfcg[off02]" id="off02" size="5" value="<?php echo $dfcg_options['off02']; ?>" /></td>
							</tr>
							<tr valign="top">
								<th scope="row">3rd image</th>
								<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat03'], 'name' => 'dfcg[cat03]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
								<td><input name="dfcg[off03]" id="off03" size="5" value="<?php echo $dfcg_options['off03']; ?>" /></td>
							</tr>
							<tr valign="top">
								<th scope="row">4th image</th>
								<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat04'], 'name' => 'dfcg[cat04]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
								<td><input name="dfcg[off04]" id="off04" size="5" value="<?php echo $dfcg_options['off04']; ?>" /></td>
							</tr>
							<tr valign="top">
								<th scope="row">5th image</th>
								<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat05'], 'name' => 'dfcg[cat05]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
								<td><input name="dfcg[off05]" id="off05" size="5" value="<?php echo $dfcg_options['off05']; ?>" /></td>
							</tr>
							<tr valign="top">
								<th scope="row">6th image</th>
								<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat06'], 'name' => 'dfcg[cat06]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
								<td><input name="dfcg[off06]" id="off06" size="5" value="<?php echo $dfcg_options['off06']; ?>" /></td>
							</tr>
							<tr valign="top">
								<th scope="row">7th image</th>
								<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat07'], 'name' => 'dfcg[cat07]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
								<td><input name="dfcg[off07]" id="off07" size="5" value="<?php echo $dfcg_options['off07']; ?>" /></td>
							</tr>
							<tr valign="top">
								<th scope="row">8th image</th>
								<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat08'], 'name' => 'dfcg[cat08]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
								<td><input name="dfcg[off08]" id="off08" size="5" value="<?php echo $dfcg_options['off08']; ?>" /></td>
							</tr>
							<tr valign="top">
								<th scope="row">9th image</th>
								<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat09'], 'name' => 'dfcg[cat09]', 'orderby' => 'Name' , 'hierarchical' => 1, 'hide_empty' => 1 )); ?></td>
								<td><input name="dfcg[off09]" id="off09" size="5" value="<?php echo $dfcg_options['off09']; ?>" /></td>
							</tr>
						</tbody>
					</table>
					
					<p class="submit"><input type="submit" name="info_update" value="<?php _e('Save Changes') ?>" /><a class="button-secondary" href="#top" title="Back to top" style="float:right;">Back to top</a></p>
				</div>
			</div>
			
			<a name="2.2"></a>
			<div class="postbox">
				<h3>2.2 ONE CATEGORY Settings</h3>
				<div class="inside">
					<p>Configure this section if you chose One Category in the <a href="#2">Gallery Method</a> Settings.</p>
					<table class="optiontable form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">Select the Category:</th>
								<td><?php wp_dropdown_categories(array('selected' => $dfcg_options['cat-display'], 'name' => 'dfcg[cat-display]', 'orderby' => 'Name' , 'hierarchical' => 0, 'hide_empty' => 1, 'show_option_all' => 'All' )); ?><span style="padding-left:30px"><em>Posts from this category will be displayed in the gallery.</em></span></td>
							</tr>
							<tr valign="top">
								<th scope="row">Number of Posts to display:</th>
								<td><select name="dfcg[posts-number]">
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
								<span style="padding-left:70px"><em>The minimum number of Posts is 2, the maximum is 15 (for performance reasons).</em></span></td>
							</tr>
							
						</tbody>
					</table>
					<p class="submit"><input type="submit" name="info_update" value="<?php _e('Save Changes') ?>" /><a class="button-secondary" href="#top" title="Back to top" style="float:right;">Back to top</a></p>
				</div>
			</div>
			
			<a name="2.3"></a>
			<div class="postbox">
				<h3>2.3 PAGES Settings</h3>
				<div class="inside">
					<p>Configure this section if you chose Pages in the <a href="#2">Gallery</a> Settings.</p>
					<table class="optiontable form-table">
						<tbody>
							<tr>
								<th scope="row">Page ID numbers:</th>
								<td><input name="dfcg[pages-selected]" id="dfcg-pages-selected" size="75" value="<?php echo $dfcg_options['pages-selected']; ?>" /><br />
								<em>Enter ID's in a comma separated list with no spaces, eg: 2,7,8,19,21</em></td>
							</tr>
							
						</tbody>
					</table>
					<p class="submit"><input type="submit" name="info_update" value="<?php _e('Save Changes') ?>" /><a class="button-secondary" href="#top" title="Back to top" style="float:right;">Back to top</a></p>
				</div>
			</div>
		
			<a name="3"></a>
			<div class="postbox">
				<h3>3. Default description (OPTIONAL):</h3>
				<div class="inside">
					<p>This option is applicable to all <a href="#2">Gallery Method</a> Settings.</p>
					<p>The Dynamic Content Gallery displays a description for each image in the gallery Slide Pane. The plugin looks for the image description in this sequence: firstly, a custom field <strong>dfcg-desc</strong> if that exists, secondly a Category Description if that exists (not applicable to the Pages Gallery Method), and finally the default description created here.</p>
					<p><em><b>Tip</b>: Create the Category Descriptions in Dashboard>Posts>Categories.<br />
					<b>Tip</b>: The Slide Pane has relatively little space in which to display this text, and therefore it is recommended to keep the description short, probably less than 25 words.</em></p>
			
					<table class="optiontable form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">Default Description:</th>
								<td><textarea name="dfcg[defimagedesc]" cols="75" rows="2" id="dfcg-defimagedesc"><?php echo stripslashes( $dfcg_options['defimagedesc'] ); ?></textarea></td>
							</tr>	
						</tbody>
					</table>
					<p class="submit"><input type="submit" name="info_update" value="<?php _e('Save Changes') ?>" /><a class="button-secondary" href="#top" title="Back to top" style="float:right;">Back to top</a></p>
				</div>
			</div>
						
			<a name="4"></a>
			<div class="postbox">
				<h3>4. Gallery size and CSS options (REQUIRED):</h3>
				<div class="inside">
					<p>Configure various layout and CSS options for your gallery including the size of the gallery, the height of the Slide Pane, gallery border, and the font sizes, colours and margins for the text displayed in the Slide Pane. The addition of these options in this Settings page saves you having to customise the plugin's CSS stylesheet.</p>	
			
					<table class="optiontable form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">Gallery Width:</th>
								<td><input name="dfcg[gallery-width]" id="dfcg-gallery-width" size="5" value="<?php echo $dfcg_options['gallery-width']; ?>" />&nbsp;in pixels. <em>Default is 460px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Gallery Height:</th>
								<td><input name="dfcg[gallery-height]" id="dfcg-gallery-height" size="5" value="<?php echo $dfcg_options['gallery-height']; ?>" />&nbsp;in pixels. <em>Default is 250px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Slide Pane Height:</th>
								<td><input name="dfcg[slide-height]" id="dfcg-slide-height" size="5" value="<?php echo $dfcg_options['slide-height']; ?>" />&nbsp;in pixels. <em>Default is 50px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Gallery border width:</th>
								<td><input name="dfcg[gallery-border-thick]" id="dfcg-gallery-border-thick" size="3" value="<?php echo $dfcg_options['gallery-border-thick']; ?>" />&nbsp;in pixels. If you don't want a border enter 0 in this box. <em>Default is 1px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Gallery border colour:</th>
								<td><input name="dfcg[gallery-border-colour]" id="dfcg-gallery-border-colour" size="8" value="<?php echo $dfcg_options['gallery-border-colour']; ?>" />&nbsp;Enter color hex code like this #000000. <em>Default is #000000.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Heading font size:</th>
								<td><input name="dfcg[slide-h2-size]" id="dfcg-slide-h2-size" size="5" value="<?php echo $dfcg_options['slide-h2-size']; ?>" />&nbsp;in pixels. <em>Default is 12px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Heading font colour:</th>
								<td><input name="dfcg[slide-h2-colour]" id="dfcg-slide-h2-colour" size="8" value="<?php echo $dfcg_options['slide-h2-colour']; ?>" />&nbsp;Enter color hex code like this #FFFFFF. <em>Default is #FFFFFF.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Heading Padding top and bottom:</th>
								<td><input name="dfcg[slide-h2-padtb]" id="dfcg-slide-h2-padtb" size="3" value="<?php echo $dfcg_options['slide-h2-padtb']; ?>" />&nbsp;in pixels. <em>Default is 0px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Heading Padding left and right:</th>
								<td><input name="dfcg[slide-h2-padlr]" id="dfcg-slide-h2-padlr" size="5" value="<?php echo $dfcg_options['slide-h2-padlr']; ?>" />&nbsp;in pixels. <em>Default is 0px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Heading Margin top and bottom:</th>
								<td><input name="dfcg[slide-h2-margtb]" id="dfcg-slide-h2-margtb" size="3" value="<?php echo $dfcg_options['slide-h2-margtb']; ?>" />&nbsp;in pixels. <em>Default is 2px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Heading Margin left and right:</th>
								<td><input name="dfcg[slide-h2-marglr]" id="dfcg-slide-h2-marglr" size="5" value="<?php echo $dfcg_options['slide-h2-marglr']; ?>" />&nbsp;in pixels. <em>Default is 5px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Description font size:</th>
								<td><input name="dfcg[slide-p-size]" id="dfcg-slide-p-size" size="5" value="<?php echo $dfcg_options['slide-p-size']; ?>" />&nbsp;in pixels. <em>Default is 11px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Description font colour:</th>
								<td><input name="dfcg[slide-p-colour]" id="dfcg-slide-p-colour" size="8" value="<?php echo $dfcg_options['slide-p-colour']; ?>" />&nbsp;Enter color hex code like this #FFFFFF. <em>Default is #FFFFFF.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Description Padding top and bottom:</th>
								<td><input name="dfcg[slide-p-padtb]" id="dfcg-slide-p-padtb" size="5" value="<?php echo $dfcg_options['slide-p-padtb']; ?>" />&nbsp;in pixels. <em>Default is 0px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Description Padding left and right:</th>
								<td><input name="dfcg[slide-p-padlr]" id="dfcg-slide-p-padlr" size="5" value="<?php echo $dfcg_options['slide-p-padlr']; ?>" />&nbsp;in pixels. <em>Default is 0px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Description Margin top and bottom:</th>
								<td><input name="dfcg[slide-p-margtb]" id="dfcg-slide-p-margtb" size="5" value="<?php echo $dfcg_options['slide-p-margtb']; ?>" />&nbsp;in pixels. <em>Default is 2px.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Description Margin left and right:</th>
								<td><input name="dfcg[slide-p-marglr]" id="dfcg-slide-p-marglr" size="5" value="<?php echo $dfcg_options['slide-p-marglr']; ?>" />&nbsp;in pixels. <em>Default is 5px.</em></td>
							</tr>
						</tbody>
					</table>
					<p class="submit"><input type="submit" name="info_update" value="<?php _e('Save Changes') ?>" /><a class="button-secondary" href="#top" title="Back to top" style="float:right;">Back to top</a></p>
				</div>
			</div>
			
			<a name="5"></a>
			<div class="postbox">
				<h3>5. Javascript configuration options (OPTIONAL):</h3>
				<div class="inside">
					<p>Configure various default javascript settings for your gallery. The addition of these options in this Settings page saves you having to customise the plugin's javascript files.</p>

					<table class="optiontable form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">Show Carousel:</th>
								<td><input name="dfcg[showCarousel]" type="checkbox" id="dfcg-showCarousel" value="true" <?php checked('true', $dfcg_options['showCarousel']); ?> /><span style="padding-left:50px"><em>Check the box to display thumbnail Carousel. Default is CHECKED.</em></span></td>
							</tr>
							<tr valign="top">
								<th scope="row">Carousel label:</th>
								<td><input name="dfcg[textShowCarousel]" id="dfcg-textShowCarousel" size="25" value="<?php echo $dfcg_options['textShowCarousel']; ?>" /><span style="padding-left:30px"><em>Label for Carousel tab. Only visible if "Show Carousel" is checked. Default is Featured Articles.</em></span></td>
							</tr>
							<tr valign="top">
								<th scope="row">Show Slide Pane:</th>
								<td><input name="dfcg[showInfopane]" type="checkbox" id="dfcg-showInfopane" value="1" <?php checked('true', $dfcg_options['showInfopane']); ?> /><span style="padding-left:50px"><em>Check the box to display Slide Pane. Default is CHECKED.</em></span></td>
							</tr>
							<tr valign="top">
								<th scope="row">Animate Slide Pane:</th>
								<td><input name="dfcg[slideInfoZoneSlide]" type="checkbox" id="dfcg-slideInfoZoneSlide" value="1" <?php checked('true', $dfcg_options['slideInfoZoneSlide']); ?> /><span style="padding-left:50px"><em>Check the box to have Slide Pane slide into view. Default is CHECKED.</em></span></td>
							</tr>
							<tr valign="top">
								<th scope="row">Slide Pane Opacity:</th>
								<td><input name="dfcg[slideInfoZoneOpacity]" size="10" id="dfcg-slideInfoZoneOpacity" value="<?php echo $dfcg_options['slideInfoZoneOpacity']; ?>" /><span style="padding-left:50px"><em>Opacity of Slide Pane. 1.0 is fully opaque, 0.0 is fully transparent. Default is 0.7.</em></span></td>
							</tr>
							<tr valign="top">
								<th scope="row">Timed transitions:</th>
								<td><input name="dfcg[timed]" type="checkbox" id="dfcg-timed" value="1" <?php checked('true', $dfcg_options['timed']); ?> /><span style="padding-left:50px"><em>Check the box to have timed image transitions. Default is CHECKED.</em></span></td>
							</tr>
							<tr valign="top">
								<th scope="row">Transitions delay:</th>
								<td><input name="dfcg[delay]" id="dfcg-delay" size="10" value="<?php echo $dfcg_options['delay']; ?>" /><span style="padding-left:30px"><em>Enter the delay time (in milliseconds) between image transitions. Default is 9000.</em></span></td>
							</tr>
							<tr valign="top">
								<th scope="row">Transition type:</th>
								<td><select name="dfcg[defaultTransition]">
									<option style="padding-right:10px;" value="fade" <?php selected('fade', $dfcg_options['defaultTransition']); ?>>fade</option>
									<option style="padding-right:10px;" value="fadeslideleft" <?php selected('fadeslideleft', $dfcg_options['defaultTransition']); ?>>fadeslideleft</option>
									<option style="padding-right:10px;" value="continuousvertical" <?php selected('continuousvertical', $dfcg_options['defaultTransition']); ?>>continuousvertical</option>
									<option style="padding-right:10px;" value="continuoushorizontal" <?php selected('continuoushorizontal', $dfcg_options['defaultTransition']); ?>>continuoushorizontal</option>
									</select><span style="padding-left:30px"><em>Select the type of image transition from "fade", "fadeslideleft", "continuoushorizontal" or "continuousvertical". Default is "fade".</em></span></td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Disable Mootools:') ?></th>
								<td><input name="dfcg[mootools]" type="checkbox" id="dfcg-mootools" value="1" <?php checked('1', $dfcg_options['mootools']); ?> /><span style="padding-left:50px"><em>Check the box ONLY in the event that another plugin is already loading the Mootools Javascript library files in your site. Default is UNCHECKED.</em></span></td>
							</tr>						
						</tbody>
					</table>
					<p class="submit"><input type="submit" name="info_update" value="<?php _e('Save Changes') ?>" /><a class="button-secondary" href="#top" title="Back to top" style="float:right;">Back to top</a></p>
				</div>
			</div>
							
			<input name="dfcg[homeurl]" id="dfcg-homeurl" type="hidden" value="<?php echo $dfcg_options['homeurl']; ?>" />
			<input name="dfcg[image-url-type]" id="dfcg-image-url-type" type="hidden" value="<?php echo $dfcg_options['image-url-type']; ?>" />
			<input name="dfcg[imageurl]" id="dfcg-imageurl" type="hidden" value="<?php echo $dfcg_options['imageurl']; ?>" />
			<input name="dfcg[defimgmulti]" id="dfcg-defimgmulti" type="hidden" value="<?php echo $dfcg_options['defimgmulti']; ?>" />
			<input name="dfcg[defimgonecat]" id="dfcg-defimgonecat" type="hidden" value="<?php echo $dfcg_options['defimgonecat']; ?>" />
			<input name="dfcg[defimgpages]" id="dfcg-defimgpages" type="hidden" value="<?php echo $dfcg_options['defimgpages']; ?>" />
			
			<a name="6"></a>
			<div class="postbox">
				<h3>6. Restrict script loading (RECOMMENDED):</h3>
				<div class="inside">
					<p>This option lets you restrict the loading of the plugin's javascript to the page that will actually display the gallery. This prevents the scripts being loaded on all pages unnecessarily, which will help to minimise the impact of the plugin on page loading times.</p>

					<table class="optiontable form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><input name="dfcg[limit-scripts]" style="margin-right:5px;" type="radio" id="limit-scripts-home" value="homepage" <?php checked('homepage', $dfcg_options['limit-scripts']); ?> />
									<label for="limit-scripts-home">Home page only</label></th>
								<td>Select this option to load the plugin's scripts ONLY on the homepage.<br />
								<em><b>Tip</b>: Best option if the gallery will only be used on the home page of your site. This is the default.</em>
								<br />
								<em><b>Tip</b>: Select this option if you use a Static Front Page defined in Dashboard > Settings > Reading.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row"><input name="dfcg[limit-scripts]" style="margin-right:5px;" type="radio" id="limit-scripts-page" value="pagetemplate" <?php checked('pagetemplate', $dfcg_options['limit-scripts']); ?> />
									<label for="limit-scripts-page">Specific Page Template</label></th>
								<td>Select this option to load the plugin's scripts ONLY when a specific Page Template is being used to display the gallery.<br />
								<em><b>Tip</b>: Best option if the gallery is displayed using a Page Template. Enter the Page Template <strong>filename</strong> below.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row"><input name="dfcg[limit-scripts]" style="margin-right:5px;" type="radio" id="limit-scripts-other" value="other" <?php checked('other', $dfcg_options['limit-scripts']); ?> />
									<label for="limit-scripts-other">Other</label></th>
								<td>Check this option if none of the above apply to your setup.<br />
								<em><b>Tip</b>: The plugin's scripts will be loaded in every page. Not recommended.</em></td>
							</tr>
							<tr valign="top">
								<th scope="row">Page Template filename:</th>
								<td><input name="dfcg[page-filename]" size="35" id="dfcg-page-filename" value="<?php echo $dfcg_options['page-filename']; ?>" /><span style="padding-left:20px"><em>Filename of the Page Template, eg mypagetemplate.php.</em></span></td>
							</tr>
						</tbody>
					</table>
					<p class="submit"><input type="submit" name="info_update" value="<?php _e('Save Changes') ?>" /><a class="button-secondary" href="#top" title="Back to top" style="float:right;">Back to top</a></p>
				</div>
			</div>
			
			<a name="7"></a>
			<div class="postbox">
				<h3>7. Error Message options (OPTIONAL)</h3>
				<div class="inside">
					<p>The plugin produces informative error messages in the event that Posts, Pages, images and descriptions have not been configured properly. These error messages are output to the Page Source of the gallery. You may choose whether to turn off the visibility of these error messages.</p>
					<p><label for="dfcg-errors">
						<input type="checkbox" name="dfcg[errors]" id="dfcg-errors" value="1" <?php checked('true', $dfcg_options['errors']); ?>" />&nbsp;<strong><?php _e('Error messages visible')?></strong> <span style="font-size:11px;margin-left:20px;"><em><?php _e('To hide error messages, uncheck the box then click the "Save Changes" button. Default is CHECKED.')?></em></span>
					</label></p>
					<p class="submit"><input type="submit" name="info_update" value="<?php _e('Save Changes') ?>" /><a class="button-secondary" href="#top" title="Back to top" style="float:right;">Back to top</a></p>
				</div>
			</div>
			
			<div class="postbox-dfcg" style="padding:0px 10px;margin:0px;">
				<p><label for="dfcg-reset">
					<input type="checkbox" name="dfcg[reset]" id="dfcg-reset" value="<?php echo $dfcg_options['reset']; ?>" />&nbsp;<strong><?php _e('Reset all options to the Default settings')?></strong> <span style="font-size:11px;"><em><?php _e('Check the box, then click the "Save Changes" button.')?></em></span>
				</label></p>
			</div>
        
		</fieldset>
		<p class="submit"><input type="submit" name="info_update" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
	</form>
	
	<div class="dfcgcredits">
		<p>For further information please read the README document included in the plugin download, or visit the <a href="http://www.studiograsshopper.ch/dynamic-content-gallery-configuration/">Configuration page</a>,  <a href="http://www.studiograsshopper.ch/dynamic-content-gallery-v3-documentation/">Documentation page</a> and comprehensive <a href="http://www.studiograsshopper.ch/dynamic-content-gallery-v3-faq/">FAQ</a>.</p>
		<p>The Dynamic Content Gallery plugin uses the SmoothGallery script developed by <a href="http://smoothgallery.jondesign.net/">Jonathan Schemoul</a>, and is inspired by the Featured Content Gallery originally developed by Jason Schuller. Grateful acknowledgements to Jonathan's wonderful script and Jason's popular Wordpress plugin implementation.</p> 
		<p>Dynamic Content Gallery plugin for Wordpress and Wordpress Mu by <a href="http://www.studiograsshopper.ch/">Ade Walker</a>&nbsp;&nbsp;&nbsp;<strong>Version: <?php echo DFCG_VER; ?></strong> for WPMU</p>      
	</div>
</div>