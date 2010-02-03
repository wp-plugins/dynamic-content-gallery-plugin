<?php
/**
* Functions for adding metaboxes to Post and Pages Write screen for display of custom fields
*
* @copyright Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
* @package dynamic_content_gallery
* @version 3.2.1
*
* @since 3.2
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit("Sorry, you are not allowed to access this file directly.");
}



/**
* Adds metaboxes to Post and Page screen
*
* Hooked to 'admin_menu'
*
* @global array $dfcg_options plugin options from db
* @global array $dfcg_postmeta_upgrade plugin options from db
* @since 3.2.1
*/
function dfcg_add_metabox() {

	global $dfcg_options, $dfcg_postmeta_upgrade;
	
	if( $dfcg_options['populate-method'] == 'pages' && $dfcg_postmeta_upgrade['upgraded'] == 'completed' ) {
	
		add_meta_box( DFCG_FILE_HOOK . '_box', __( 'Dynamic Content Gallery', DFCG_DOMAIN ), 'dfcg_meta_box', 'page', 'side', 'low' );
	
	} elseif( $dfcg_options['populate-method'] !== 'pages' && $dfcg_postmeta_upgrade['upgraded'] == 'completed' ) {
	
		add_meta_box( DFCG_FILE_HOOK . '_box', __( 'Dynamic Content Gallery', DFCG_DOMAIN ), 'dfcg_meta_box', 'post', 'side', 'low' );
	}
}


/**
* Populates metaboxes in Post and Page screen
*
* Called by add_meta_box() in dfcg_add_metabox() function
*
* Note: Markup follows WP standards for Post/Page Editor sidebar
*
* @global array $dfcg_options plugin options from db
* @param object $post object
* @since 3.2
*/
function dfcg_meta_box($post) {

	global $dfcg_options;
	
	
	// Use nonce for verification
	echo '<input type="hidden" name="dfcg_metabox_noncename" id="dfcg_metabox_noncename" value="' . 
	wp_create_nonce( DFCG_FILE_HOOK ) . '" />';
	
	
	// Actual content of metabox - same used for Post and Pages
	
	// Variables for use in the metabox
	if( $dfcg_options['image-url-type'] == 'partial' ) {
		$text = 'Partial URL.';
		$url = $dfcg_options['imageurl'];
		if( $url == '' ) {
			$url = '<span style="color:#D53131;">Not defined. You must define this in the DCG Settings page.</span>';
		}
	} else {
		$text = 'Full URL';
		$url = 'not used';
	}
	?>
	
<?php /* IMAGE BLOCK */ ?>
	<div class="dfcg-form">
		<h5><?php _e('Image URL', DFCG_DOMAIN); ?>:</h5>
		<label class="screen-reader-text" for="_dfcg-image"><?php _e('Image URL', DFCG_DOMAIN); ?></label>
		<textarea id="_dfcg-image" name="_dfcg-image" style="font-size:11px;" cols="38" rows="2"><?php echo get_post_meta($post->ID, '_dfcg-image', true); ?></textarea>
		<p><em>You are using <?php echo $text; ?>.</em>
		<?php if( $url !== 'not used' ) { ?>
			<br /><em>Images folder is: <?php echo $url; ?></em>
		<?php } ?>
		</p>
	</div>
	
<?php /* DESC BLOCK */ ?>
	
	<?php if( $dfcg_options['desc-method'] == 'manual' ) : // Only show dfcg-desc if Slide Pane Description is manual ?>

	<div class="dfcg-form">
		<h5><?php _e('Slide Pane Description', DFCG_DOMAIN); ?>:</h5>
		<label class="screen-reader-text" for="_dfcg-desc"><?php _e('Slide Pane Description', DFCG_DOMAIN); ?></label>
		<textarea id="_dfcg-desc" name="_dfcg-desc" style="font-size:11px;" cols="38" rows="4"><?php echo get_post_meta($post->ID, '_dfcg-desc', true); ?></textarea>
	</div>
	
	<?php else : // Slide Pane Description is Auto ?>
		
	<div class="dfcg-form">
		<h5><?php _e('Slide Pane Description', DFCG_DOMAIN); ?>:</h5>
		<p><em><?php _e('You are currently using', DFCG_DOMAIN); ?> <a href="<?php echo 'admin.php?page=' . DFCG_FILE_HOOK; ?>"><?php _e('Auto', DFCG_DOMAIN); ?></a> <?php _e('Slide Pane descriptions', DFCG_DOMAIN); ?>.</em></p>
		<input id="_dfcg-desc" name="_dfcg-desc" type="hidden" value="<?php echo get_post_meta($post->ID, '_dfcg-desc', true); ?>" />
	</div>
	<?php endif; ?>
	
<?php /* EXTERNAL LINK BLOCK */ ?>

	<div class="dfcg-form">
		<h5><?php _e('External link for image', DFCG_DOMAIN ); ?>:</h5>
		<label class="screen-reader-text" for="_dfcg-link"><?php _e('External link for image', DFCG_DOMAIN ); ?></label>
		<input id="_dfcg-link" name="_dfcg-link" style="font-size:11px;" size="41" type="text" value="<?php echo get_post_meta($post->ID, '_dfcg-link', true); ?>" />
	</div>
	
<?php /* PAGES SORT ORDER BLOCK */ ?>

	<?php if( $dfcg_options['populate-method'] == 'pages' && $dfcg_options['pages-sort-control'] == 'true' ) : ?>
	<div class="dfcg-form">
		<h5><?php _e('Sort Order', DFCG_DOMAIN); ?>:</h5>
		<label class="screen-reader-text" for="_dfcg-sort"><?php _e('Sort Order', DFCG_DOMAIN); ?></label>
		<input name="_dfcg-sort" id="_dfcg-sort" size="3" type="text" value="<?php echo get_post_meta($post->ID, '_dfcg-sort', true); ?>" />
		<p><em><?php _e('By default, Pages are arranged in the DCG in page/post ID number order. You can override this here by specifying a sort order.', DFCG_DOMAIN); ?></em></p>
	</div>
	<?php else : ?>
		<input id="_dfcg-sort" name="_dfcg-sort" type="hidden" value="<?php echo get_post_meta($post->ID, '_dfcg-sort', true); ?>" />
	<?php endif; ?>
	
<?php /* EXCLUDE POST BLOCK */ ?>
	
	<?php // Only show Exclude option for multi-option and one-category
	if( $dfcg_options['populate-method'] !== 'pages' ) {
		$exclude = false;
		if( get_post_meta($post->ID,'_dfcg-exclude',true) == 'true' ) {
			$exclude = true;
		} else {
			$exclude = false;
		}
	?>
	<div class="dfcg-form">
		<h5><?php _e('Exclude from gallery?', DFCG_DOMAIN); ?></h5>
		<input type="checkbox" id="_dfcg-exclude" name="_dfcg-exclude" <?php checked($exclude); ?> />
		<label for="_dfcg-exclude" style="font-size:10px;">&nbsp;<?php _e('Check to exclude', DFCG_DOMAIN ); ?></label>
	</div>
<?php
	}
}


/**
* Saves data added/edited in metaboxes in Post and Page screen
*
* Hooked to 'save_post'
*
* Adapted from Write Panel plugin by Nathan Rice
*
* @param mixed $post_id Post ID
* @param object $post object
* @since 3.2.1
*/
function dfcg_save_metabox_data($post_id, $post) {
	
	// Check referrer is from DCG metabox
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

	// Build array from $_POST data	
	$newdata['_dfcg-image'] = $_POST['_dfcg-image'];
	$newdata['_dfcg-desc'] = $_POST['_dfcg-desc'];
	$newdata['_dfcg-link'] = $_POST['_dfcg-link'];
	$newdata['_dfcg-sort'] = $_POST['_dfcg-sort'];
	
	if( isset( $_POST['_dfcg-exclude'] ) ) {
		$newdata['_dfcg-exclude'] = $_POST['_dfcg-exclude'];
	} else {
		$newdata['_dfcg-exclude'] = false;
	}
	
	
	/* Sanitise data */
	
	// trim whitespace - all options
	foreach( $newdata as $key => $value ) {
		$input[$key] = trim($value);
	}
	
	
	// Deal with Image (could be partial or full)
	$newdata['_dfcg-image'] = esc_attr( $newdata['_dfcg-image'] );
	// If we are using Partial URL, check if first character in string is a /
	if( substr( $newdata['_dfcg-image'], 0, 1 ) == '/' ) {
		// Remove leading slash
		$newdata['_dfcg-image'] = substr( $newdata['_dfcg-image'], 1 );
	}
	
	// Deal with URLs
	$newdata['_dfcg-link'] = esc_url_raw( $newdata['_dfcg-link'] );
	
	
	// Deal with Description
	$allowed_html = array( 'a' => array('href' => array(),'title' => array() ), 'br' => array(), 'em' => array(), 'strong' => array() );
	$allowed_protocols = array( 'http', 'https', 'mailto', 'feed' );
	
	$newdata['_dfcg-desc'] = wp_kses( $newdata['_dfcg-desc'], $allowed_html, $allowed_protocols );
	
	
	// Deal with Sort Order
	$newdata['_dfcg-sort'] = substr( $newdata['_dfcg-sort'], 0, 4 );
	$newdata['_dfcg-sort'] = esc_attr($newdata['_dfcg-sort']);
	
	
	// Deal with checkbox - we don't want to save this postmeta if _dfcg-exclude is not true
	$newdata['_dfcg-exclude'] = $newdata['_dfcg-exclude'] ? 'true' : NULL;
	
	
	// Add values of $newdata as custom fields
	
	foreach ($newdata as $key => $value) {
		
		if( $post->post_type == 'revision' ) return; //don't store custom data twice
		
		$value = implode(',', (array)$value); //if $value is an array, make it a CSV (unlikely)
		
		if(get_post_meta($post->ID, $key, FALSE)) { //if the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { //if the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		
		if(!$value) delete_post_meta($post->ID, $key); //delete if any are blank, eg _dfcg-exclude is NULL
	}
}