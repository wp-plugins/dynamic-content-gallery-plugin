<?php
/* This file is part of the DYNAMIC CONTENT GALLERY Plugin Version 2.0 beta 2
*****************************************************************************
Copyright 2008  Ade WALKER  (email : info@studiograsshopper.ch)

Options Page for Wordpress
*/

dfcg_load_textdomain();

// Handle the updating of options
	if( isset($_POST['info_update']) ) {
		
		// Is the user allowed to do this?
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Sorry. You do not have permission to do this.'));
			
		// check the nonce
		check_admin_referer( 'dfcg-update' );
		
		// build the array from input
		$updated_options = $_POST['dfcg'];
		
		// trim whitespace within the array values
		foreach( $updated_options as $key => $value ) {
			$updated_options[$key] = trim($value);
		}
		
		// deal with the RESET checkbox
		$bool_opts = array( 'reset' );
		foreach($bool_opts as $key) {
			$updated_options[$key] = $updated_options[$key] ? 'true' : 'false';
		}
		
		// If RESET is checked, reset the options
		if ( $updated_options['reset'] == "true" ) {
			dfcg_unset_gallery_options();	// clear out the old ones 
			dfcg_set_gallery_options();		// put back the defaults
			echo '<div id="message" class="updated fade"><p><strong>' . __('Dynamic Content Gallery Settings reset to defaults.') . '</strong></p></div>';
		} else {
		// Otherwise, update the options
		update_option( 'dfcg_plugin_settings', $updated_options);
		echo '<div id="message" class="updated fade"><p><strong>' . __('Dynamic Content Gallery Settings updated and saved.') . '</strong></p></div>';
		}
	}
	// Display the updated options
	$options = get_option('dfcg_plugin_settings');
	?>
<style>
#dfcgstyle {font-size:95%;}
#dfcgstyle p{margin:6px 0px;}
#dfcgstyle table{background:#F1F1F1;}
.dfcginfo {background:#EAF3FA;margin:24px 0px 0px 0px;padding:10px 10px 10px 10px;}
.dfcgopts {background:#F1F1F1;padding:10px 10px 10px 10px;}
.dfcgcredits {border-top:1px solid #CCCCCC;margin:10px 0px 0px 0px;padding:10px 0px 0px 0px;}
</style>
<div class="wrap" id="dfcgstyle">

	<h2>Dynamic Content Gallery Configuration</h2>
	<form method="post">
		<?php
		// put the nonce in
		wp_nonce_field('dfcg-update');
		?>
		
		<div class="dfcginfo">
			<p>You are using the version for Wordpress</p>
			<p><?php _e('This is where you set up the selection of Categories and the paths to the locations of the gallery images. For further information, see the README document supplied with the plugin or visit the', DFCG_DOMAIN); ?> <a href="http://www.studiograsshopper.ch/dynamic-content-gallery-configuration/">Dynamic Content Gallery configuration</a> page.</p>
			<p><strong><?php _e('IMPORTANT: You must follow the instructions in sections 1 and 2 below and configure this page before using the plugin.', DFCG_DOMAIN); ?></strong></p>
		</div>
		
		<div class="dfcginfo">
			<h3><?php _e('How to add the Dynamic Content Gallery to your Theme:', DFCG_DOMAIN); ?></h3>
			<p><?php _e('To display the Dynamic Content Gallery in your theme, add this code to your theme file wherever you want to display the gallery:', DFCG_DOMAIN); ?><br /><br />
			<code>&lt;?php include (ABSPATH . '/wp-content/plugins/dynamic-content-gallery-plugin/dynamic-gallery.php'); ?&gt;</code></p>
		</div>
		
		<fieldset name="dynamic_content_gallery" class="options">
		
			<div class="dfcginfo">
				<h3><?php _e('1. Assigning an image and a description to each post:', DFCG_DOMAIN); ?></h3>
				<p><?php _e('In order to display a unique image and description for each post, create two Post Custom Fields when writing a post:', DFCG_DOMAIN); ?></p> 
				<ul>
					<li>Key = <strong>dfcg-image</strong> <?php _e('with a Value =', DFCG_DOMAIN); ?> <strong><?php _e('Image filename including extension', DFCG_DOMAIN); ?></strong> <?php _e('eg.', DFCG_DOMAIN); ?> <em>myImage.jpg</em></li>
					<li>Key = <strong>dfcg-desc</strong> with a Value = <strong>Description text</strong> eg. <em>Here's our latest news!</em></li>
				</ul>
				<p><?php _e('You must upload all such custom field images to the folder you specify in section 4 below.', DFCG_DOMAIN); ?></p>
			</div>
					
			<div class="dfcginfo">
				<h3><?php _e('2. Creating and naming your default images:', DFCG_DOMAIN); ?></h3>
        	
				<p>By default, the plugin assumes that your default images are named as follows: <em>XX.jpg</em> where XX is the category ID with which this image is associated. For example, the default image that will be displayed for a post in category ID=10 which does not have a Post Custom Field of <strong>dfcg-image</strong> must be named <em>10.jpg</em>.</p>
				<p>Once you've created these default images, and named them as per these instructions, upload the default images to the folder specified in section 5 below.</p>
			</div>
					
			<div class="dfcginfo">
				<h3>3. Select the categories and posts (REQUIRED):</h3>
				<p>The gallery is designed to display 5 images.  For each of the 5 gallery image "slots", the plugin will display the Post Custom Field image, Post Title and Post Custom Field image description determined by the combination of the Category ID and Post Select indicated below. For the Post Select: enter <strong>1</strong> for latest post, <strong>2</strong> for last but one post, <strong>3</strong> for post before that, and so on. Possible schemes are:</p>
				<ul>
					<li>Pull latest post from 5 categories: Enter a different ID number in each <strong>Category ID</strong> and enter <strong>1</strong> in each <strong>Post Select</strong>.</li>
					<li>Pull latest 5 posts from 1 category: Enter same ID number in each <strong>Category ID</strong> and enter <strong>1</strong>, <strong>2</strong>, <strong>3</strong>, <strong>4</strong>, <strong>5</strong> in the <strong>Post Select</strong> boxes.</li>
					<li>Pull latest 5 posts regardless of category: Blank out the ID numbers in each <strong>Category ID</strong> and enter <strong>1</strong>, <strong>2</strong>, <strong>3</strong>, <strong>4</strong>, <strong>5</strong> in the <strong>Post Select</strong> boxes.</li>
					<li>Any other combination of <strong>Category ID</strong> and <strong>Post Select</strong> as desired.</li>
				</ul>
			</div>
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th width="120px" scope="row">Image "Slots"</th><th scope="row">Category ID</th><th scope="row">Post Select</th>
					</tr>
					<tr valign="top">
						<td width="120px">1st image</td>
						<td><input name="dfcg[cat01]" id="cat01" size="5" value="<?php echo $options['cat01']; ?>" /></td>
						<td><input name="dfcg[off01]" id="off01" size="5" value="<?php echo $options['off01']; ?>" />&nbsp;Ex. Enter <strong>1</strong> for latest post.</td>
					</tr>
					<tr valign="top">
						<td>2nd image</td>
						<td><input name="dfcg[cat02]" id="cat02" size="5" value="<?php echo $options['cat02']; ?>" /></td>
						<td><input name="dfcg[off02]" id="off02" size="5" value="<?php echo $options['off02']; ?>" /></td>
					</tr>
					<tr valign="top">
						<td>3rd image</td>
						<td><input name="dfcg[cat03]" id="cat03" size="5" value="<?php echo $options['cat03']; ?>" /></td>
						<td><input name="dfcg[off03]" id="off03" size="5" value="<?php echo $options['off03']; ?>" /></td>
					</tr>
					<tr valign="top">
						<td>4th image</td>
						<td><input name="dfcg[cat04]" id="cat04" size="5" value="<?php echo $options['cat04']; ?>" /></td>
						<td><input name="dfcg[off04]" id="off04" size="5" value="<?php echo $options['off04']; ?>" /></td>
					</tr>
					<tr valign="top">
						<td>5th image</td>
						<td><input name="dfcg[cat05]" id="cat05" size="5" value="<?php echo $options['cat05']; ?>" /></td>
						<td><input name="dfcg[off05]" id="dfcg-off05" size="5" value="<?php echo $options['off05']; ?>" /></td>
					</tr>
				</tbody>
			</table>
			
		
			<div class="dfcginfo">
				<h3>4. Location of post custom images (REQUIRED):</h3>
				<p>Enter the relative path to the folder which contains the images that are referenced in the Posts Custom Field <strong>dfcg-image</strong>.  This path should be relative to the root of your Wordpress blog which is <?php echo $options['homeurl']; ?>.  For example, if your images are stored in your Uploads folder, the relative path will be: <em>/wp-content/uploads/</em>.</p>
			</div>
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">Path to custom images folder:</th>
						<td><input name="dfcg[imagepath]" id="dfcg-imagepath" size="50"value="<?php echo $options['imagepath']; ?>" />&nbsp;Important: include slashes at the beginning and end of the path.</td>
					</tr>
				</tbody>
			</table>
			       
	   		<div class="dfcginfo">
				<h3>5. Location of default images (REQUIRED):</h3>
				<p>Enter the relative path to the folder which contains the default images which will be pulled into the gallery.  These default images
		are only used by the plugin in the event that the post does not have an image specified in the post custom field Key <strong>dfcg-image</strong>.</p>
				<p>This path should be relative to the root of your Wordpress blog which is <?php echo $options['homeurl']; ?>.  For example, if your default images are stored in your Uploads folder, the relative path will be: <em>/wp-content/uploads/</em>.</p>
			</div>
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">Path to default images folder:</th>
						<td><input name="dfcg[defimagepath]" id="dfcg-defimagepath" size="50" value="<?php echo $options['defimagepath']; ?>" /></input>&nbsp;Important: include slashes at the beginning and end of the path.</td> 
       				</tr>
				</tbody>
			</table>
       		        
			<div class="dfcginfo">
				<h3>6. Default image description (OPTIONAL):</h3>
				<p>By default the Dynamic Content Gallery plugin displays a description for each image displayed. The plugin looks for the image description in this sequence:</p>
				<ol>
					<li>Checks the post for a custom field with the Key of <strong>dfcg-desc</strong> and if this doesn't exist =></li>
					<li>Pulls in the Category Description set up in WP Admin>Manage>Categories and if this doesn't exist =></li>
					<li>Shows the description below.</li>
				</ol>
				<p>Be aware that the gallery has relatively little space in which to display this text and therefore it is recommended to keep this description short, probably less than 20 words.</p>
			</div>
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">Description:</th>
						<td><textarea name="dfcg[defimagedesc]" cols="75" rows="2" id="dfcg-defimagedesc"><?php echo stripslashes( $options['defimagedesc'] ); ?></textarea></td>
					</tr>
				</tbody>
			</table>
						
			<div class="dfcginfo">
				<h3>7. Gallery size and CSS options (REQUIRED):</h3>
				<p>This is where you set up various layout and CSS options for your gallery including the size of the gallery, the height of the "Slider", gallery border, and font sizes, colours and margins for the text displayed in the "Slider". The addition of these options in this Settings page saves you having to customise the plugin's CSS stylesheet. These are the items that users most often need to personalise to fit with their theme.</p>	
			</div>
			<table class="optiontable form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">Gallery Width:</th>
						<td><input name="dfcg[gallery-width]" id="dfcg-gallery-width" size="5" value="<?php echo $options['gallery-width']; ?>" />&nbsp;in pixels. <em>Default is 460px.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Gallery Height:</th>
						<td><input name="dfcg[gallery-height]" id="dfcg-gallery-height" size="5" value="<?php echo $options['gallery-height']; ?>" />&nbsp;in pixels. <em>Default is 250px.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Slider Height:</th>
						<td><input name="dfcg[slide-height]" id="dfcg-slide-height" size="5" value="<?php echo $options['slide-height']; ?>" />&nbsp;in pixels. <em>Default is 50px.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Gallery border width:</th>
						<td><input name="dfcg[gallery-border-thick]" id="dfcg-gallery-border-thick" size="3" value="<?php echo $options['gallery-border-thick']; ?>" />&nbsp;in pixels. If you don't want a border enter 0 in this box. <em>Default is 1px.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Gallery border colour:</th>
						<td><input name="dfcg[gallery-border-colour]" id="dfcg-gallery-border-colour" size="8" value="<?php echo $options['gallery-border-colour']; ?>" />&nbsp;Enter color hex code like this #000000. <em>Default is #000000.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Heading font size:</th>
						<td><input name="dfcg[slide-h2-size]" id="dfcg-slide-h2-size" size="5" value="<?php echo $options['slide-h2-size']; ?>" />&nbsp;in pixels. <em>Default is 12px.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Heading font colour:</th>
						<td><input name="dfcg[slide-h2-colour]" id="dfcg-slide-h2-colour" size="8" value="<?php echo $options['slide-h2-colour']; ?>" />&nbsp;Enter color hex code like this #FFFFFF. <em>Default is #FFFFFF.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Heading Margin top and bottom:</th>
						<td><input name="dfcg[slide-h2-margtb]" id="dfcg-slide-h2-margtb" size="3" value="<?php echo $options['slide-h2-margtb']; ?>" />&nbsp;in pixels. <em>Default is 2px.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Heading Margin left and right:</th>
						<td><input name="dfcg[slide-h2-marglr]" id="dfcg-slide-h2-marglr" size="5" value="<?php echo $options['slide-h2-marglr']; ?>" />&nbsp;in pixels. <em>Default is 5px.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Description font size:</th>
						<td><input name="dfcg[slide-p-size]" id="dfcg-slide-p-size" size="5" value="<?php echo $options['slide-p-size']; ?>" />&nbsp;in pixels. <em>Default is 11px.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Description font colour:</th>
						<td><input name="dfcg[slide-p-colour]" id="dfcg-slide-p-colour" size="8" value="<?php echo $options['slide-p-colour']; ?>" />&nbsp;Enter color hex code like this #FFFFFF. <em>Default is #FFFFFF.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Description Margin top and bottom:</th>
						<td><input name="dfcg[slide-p-margtb]" id="dfcg-slide-p-margtb" size="5" value="<?php echo $options['slide-p-margtb']; ?>" />&nbsp;in pixels. <em>Default is 2px.</em></td>
					</tr>
					<tr valign="top">
						<th scope="row">Description Margin left and right:</th>
						<td><input name="dfcg[slide-p-marglr]" id="dfcg-slide-p-marglr" size="5" value="<?php echo $options['slide-p-marglr']; ?>" />&nbsp;in pixels. <em>Default is 5px.</em></td>
					</tr>
				</tbody>
			</table>
				
			<br />
			<input name="dfcg[homeurl]" id="dfcg-homeurl" type="hidden" value="<?php echo $options['homeurl']; ?>" />
			
			<p class="submit"><input type="checkbox" name="dfcg[reset]" id="dfcg-reset" value="<?php echo $options['reset']; ?>" />&nbsp;<strong><?php _e('Reset all options to the Default settings')?></strong></p>
        
		</fieldset>
		<p class="submit"><input type="submit" name="info_update" value="<?php _e('Update Options') ?>" /></p>
	</form>
	
	<div class="dfcgcredits">
		<p>For further information please read the README document included in the plugin download, or visit the <a href="http://www.studiograsshopper.ch/dynamic-content-gallery-configuration/">Dynamic Content Gallery configuration</a> page.</p>
		<p>The Dynamic Content Gallery plugin uses the SmoothGallery script developed by <a href="http://smoothgallery.jondesign.net/">Jonathan Schemoul</a>, and is inspired by the Featured Content Gallery developed by Jason Schuller. Grateful acknowledgements to Jonathan's wonderful script and Jason's popular Wordpress plugin implementation.</p> 
		<p>Dynamic Content Gallery plugin for Wordpress and Wordpress Mu by <a href="http://www.studiograsshopper.ch/">Ade Walker</a>&nbsp;&nbsp;&nbsp;<strong>Version: 2.0 beta</strong></p>      
		<p>If you have found this plugin useful, please consider making a donation to help support future development. Your support will be much appreciated. Thank you!</p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
		</form>
	</div>
</div>
