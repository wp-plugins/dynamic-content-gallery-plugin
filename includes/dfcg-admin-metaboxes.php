<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.2
*
*	Adds metaboxes to Post and Pages Write screen for display of custom fields
*	
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit("Sorry, you are not allowed to access this file directly.");
}



/* 	Adds metaboxes to Post and Page screen
*
*	Hooked to 'admin_menu'
*
*	@since	3.2
*/
function dfcg_add_metabox() {

	global $dfcg_options;
	
	if( $dfcg_options['populate-method'] == 'pages' ) {
	
		add_meta_box( DFCG_FILE_HOOK . '_box', __( 'Dynamic Content Gallery', DFCG_DOMAIN ), 'dfcg_meta_box', 'page', 'side' );
	
	} else {
	
		add_meta_box( DFCG_FILE_HOOK . '_box', __( 'Dynamic Content Gallery', DFCG_DOMAIN ), 'dfcg_meta_box', 'post', 'side' );
	}
}


/* 	Populates metaboxes in Post and Page screen
*
*	Called by add_meta_box() in dfcg_add_metabox() function
*
*	@since	3.2
*/
function dfcg_meta_box($post) {

	global $dfcg_options;
	
	
	// Use nonce for verification ... ONLY USE ONCE!
	echo '<input type="hidden" name="dfcg_metabox_noncename" id="dfcg_metabox_noncename" value="' . 
	wp_create_nonce( DFCG_FILE_HOOK ) . '" />';
	
	
	// Actual content of metabox - same used for Post and Pages
	?>
	<div class="dfcg-form">
		<p><label for="dfcg-image"><b>dfcg-image</b> <?php _e('URL:', DFCG_DOMAIN ); ?></label></p>
		<textarea id="dfcg-image" name="dfcg-image" style="font-size:11px;" cols="38" rows="2"><?php echo get_post_meta($post->ID, 'dfcg-image', true); ?></textarea>
		<em>You are using <?php if( $dfcg_options['image-url-type'] == 'full' ) { ?>'Full URL'<?php } ?></em>
	</div>
	
	<?php if( $dfcg_options['desc-method'] == 'manual' ) { // Only show dfcg-desc if Slide Pane Description is manual ?>

		<div class="dfcg-form">
			<p><?php _e('You are currently using', DFCG_DOMAIN); ?> <a href="<?php echo 'admin.php?page=' . DFCG_FILE_HOOK; ?>">Manual</a> <?php _e('Slide Pane descriptions', DFCG_DOMAIN); ?></p>
			<p><label for="dfcg-desc"><b>dfcg-desc</b> <?php _e('Description:', DFCG_DOMAIN ); ?></label></p>
			<textarea id="dfcg-desc" name="dfcg-desc" style="font-size:11px;" cols="38" rows="4"><?php echo get_post_meta($post->ID, 'dfcg-desc', true); ?></textarea>
		</div>
	
	<?php } else { // Slide Pane Description is Auto ?>
		
		<div class="dfcg-form">
			<p><?php _e('You are currently using', DFCG_DOMAIN); ?> <a href="<?php echo 'admin.php?page=' . DFCG_FILE_HOOK; ?>">Auto</a> <?php _e('Slide Pane descriptions', DFCG_DOMAIN); ?></p>
		</div>
	<?php } ?>
	
	
	<?php // Only show Exclude option for multi-option and one-category
	if( $dfcg_options['populate-method'] !== 'pages' ) {
		$dfcg_exclude = false;
		if( get_post_meta($post->ID,'_dfcg-exclude',true) == 'true' ) {
			$dfcg_exclude = true;
		} else {
			$dfcg_exclude = false;
		}
	?>
		<div class="dfcg-form" style="padding-top:6px;padding-left:1px;">
		<input type="checkbox" id="_dfcg-exclude" name="_dfcg-exclude" <?php checked($dfcg_exclude); ?> />
		<label for="_dfcg-exclude">&nbsp;&nbsp;<?php _e('Exclude from gallery?', DFCG_DOMAIN ); ?></label></div>
<?php
	}
}


/* 	Saves data added/edited in metaboxes in Post and Page screen
*
*	Hooked to 'save_post'
*
*	@since	3.2
*/
function dfcg_save_metabox_data($post_id, $post) {
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['dfcg_metabox_noncename'], DFCG_FILE_HOOK )) {
	return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post->ID ))
		return $post->ID;
	} else {
		if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	}

	// OK, we're authenticated: Put data into an array
	
	$newdata['dfcg-image'] = $_POST['dfcg-image'];
	$newdata['dfcg-desc'] = $_POST['dfcg-desc'];
	$newdata['_dfcg-exclude'] = $_POST['_dfcg-exclude'];
	
	
	/* Sanitise data */
	
	// trim whitespace - all options
	foreach( $newdata as $key => $value ) {
		$input[$key] = trim($value);
	}
	
	
	// Deal with URL
	$newdata['dfcg-image'] = esc_url_raw( $newdata['dfcg-image'] );
	
	
	// Deal with Description
	$allowed_html = array( 'a' => array('href' => array(),'title' => array() ), 'br' => array(), 'em' => array(), 'strong' => array() );
	$allowed_protocols = array( 'http', 'https', 'mailto', 'feed' );
	
	$newdata['dfcg-desc'] = wp_kses( $newdata['dfcg-desc'], $allowed_html, $allowed_protocols );
	
	
	// Deal with checkbox - we don't want to save this postmeta if _dfcg-exclude is not true
	$newdata['_dfcg-exclude'] = $newdata['_dfcg-exclude'] ? 'true' : NULL;
	
	
	// Add values of $mydata as custom fields
	
	foreach ($newdata as $key => $value) {
		
		if( $post->post_type == 'revision' ) return; //don't store custom data twice
		
		$value = implode(',', (array)$value); //if $value is an array, make it a CSV (unlikely)
		
		if(get_post_meta($post->ID, $key, FALSE)) { //if the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { //if the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		
		if(!$value) delete_post_meta($post->ID, $key); //delete if blank, eg _dfcg-exclude is NULL
	}
}