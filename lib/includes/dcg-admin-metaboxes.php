<?php
/**
 * Functions for adding metaboxes to Post and Pages Write screen for display of custom fields
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2011
 * @package dynamic_content_gallery
 * @version 4.0
 *
 *
 * @since 3.2
 */

/**
 * Prevent direct access to this file
 */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}



/**
 * Adds metaboxes to Post and Page screen
 *
 * Hooked to 'admin_menu'
 *
 * Note: since 4.0 DCG metabox appears on all CPT edit screens if ID Method selected
 *
 * @uses dfcg_get_custom_post_types()
 *
 * @since 3.2.1
 * @updated 4.0
 * @global array $dfcg_options plugin options from db
 * @return nothing Calls add_meta_box() function
 */
function dfcg_add_metabox() {

	global $dfcg_options;
	
	
	$name = __( 'Dynamic Content Gallery Metabox', DFCG_DOMAIN );
	$function = 'dfcg_meta_box';
	
	if( $dfcg_options['populate-method'] == 'multi-option' || $dfcg_options['populate-method'] == 'one-category' ) {
	
		add_meta_box( DFCG_FILE_HOOK . '_box', $name, $function, 'post', 'side', 'low' );
	}
	
	if( $dfcg_options['populate-method'] == 'id-method' ) {
	
		add_meta_box( DFCG_FILE_HOOK . '_box', $name, $function, 'post', 'side', 'low' );
		add_meta_box( DFCG_FILE_HOOK . '_box', $name, $function, 'page', 'side', 'low' );
		
		$post_types = dfcg_get_custom_post_types();
	
		foreach( $post_types as $post_type ) {
			add_meta_box( DFCG_FILE_HOOK . '_box', $name, $function, $post_type->name, 'side', 'low' );
		}
	}
	
	if( $dfcg_options['populate-method'] == 'custom-post' ) {
	
		// Only show Metabox on Edit Screen for selected Custom Post Type
		$post_type = $dfcg_options['cpt-name'];
		add_meta_box( DFCG_FILE_HOOK . '_box', $name, $function, $post_type, 'side', 'low' );
	}
}


/**
 * Populates metaboxes in Post and Page screen
 *
 * Called by add_meta_box() in dfcg_add_metabox() function
 *
 * Note: Markup follows WP standards for Post/Page Editor sidebar
 *
 * @since 3.2.2
 * @updated 4.0
 * @param object $post object
 * @global array $dfcg_options plugin options from db
 * @return echos the XHTML for the Metabox
 */
function dfcg_meta_box( $post ) {

	global $dfcg_options;
	
	// Use nonce for verification
	echo '<input type="hidden" name="dfcg_metabox_noncename" id="dfcg_metabox_noncename" value="' . 
	wp_create_nonce( DFCG_FILE_HOOK ) . '" />';
	
	
	// Actual content of metabox - same used for Post and Pages
	
	// Variables for use in the metabox
	if( $dfcg_options['image-url-type'] == 'auto' ) {
		$link = 'Featured Images';
		$url = 'not used';
	}
	if( $dfcg_options['image-url-type'] == 'partial' ) {
		$link = 'Partial URL';
		$url = $dfcg_options['imageurl'];
		if( $url == '' ) {
			$url = '<span style="color:#D53131;">Not defined. You must define this in the DCG Settings page.</span>';
		}
	}
	if( $dfcg_options['image-url-type'] == 'full' ) {
		$link = 'Full URL';
		$url = 'not used';
	}
	?>
	
<?php /* IMAGE BLOCK */ ?>
	
	<h4><?php _e('Image URL', DFCG_DOMAIN); ?>:</h4>
		
	<?php if( $dfcg_options['image-url-type'] == 'auto' ) : ?>
		
		<p class="howto"><?php _e('You are using', DFCG_DOMAIN); ?> <a href="<?php echo 'admin.php?page=' . DFCG_FILE_HOOK; ?>"><?php echo $link; ?></a> <?php _e('Image Management. The DCG will use the Featured Image set for this Post/Page.', DFCG_DOMAIN); ?></p>
		<p class="howto"><strong><?php _e('Manual override (optional):', DFCG_DOMAIN); ?></strong><br /><?php _e('To override the Featured Image enter the URL (including http://) to the alternative image in the Image URL box below.', DFCG_DOMAIN); ?></p>
		
	<?php elseif( $dfcg_options['image-url-type'] == 'full' ) : ?>
			
		<p class="howto"><?php _e('You are using', DFCG_DOMAIN); ?> <a href="<?php echo 'admin.php?page=' . DFCG_FILE_HOOK; ?>"><?php echo $link;; ?></a> <?php _e('Image Management. Enter the URL to your image below.', DFCG_DOMAIN); ?></p>
		
	<?php elseif( $dfcg_options['image-url-type'] == 'partial' ) : ?>
			
		<p class="howto"><?php _e('You are using', DFCG_DOMAIN); ?> <a href="<?php echo 'admin.php?page=' . DFCG_FILE_HOOK; ?>"><?php echo $link; ?></a> <?php _e('Image Management. Enter the URL to your image below.', DFCG_DOMAIN); ?></p>
		
	<?php endif; ?>
		
	<p>
		<label class="screen-reader-text" for="_dfcg-image"><?php _e('Image URL', DFCG_DOMAIN); ?></label>
		<textarea id="_dfcg-image" name="_dfcg-image" class="large-text" cols="2" rows="2"><?php echo get_post_meta($post->ID, '_dfcg-image', true); ?></textarea>
	</p>
			
	<?php if( $url !== 'not used' ) { ?>
		<p><em>Images folder is: <?php echo $url; ?></em></p>
	<?php } ?>


	
<?php /* DESC BLOCK */ ?>
	
	<hr class="div" />
	
	<?php if( $dfcg_options['desc-method'] == 'manual' ) : // Only show dfcg-desc if Slide Pane Description is manual ?>

	<h4><?php _e('Slide Pane Description', DFCG_DOMAIN); ?>:</h4>
	<p class="howto"><?php _e('You are currently using', DFCG_DOMAIN); ?> <a href="<?php echo 'admin.php?page=' . DFCG_FILE_HOOK; ?>"><?php _e('Manual', DFCG_DOMAIN); ?></a> <?php _e('Slide Pane descriptions', DFCG_DOMAIN); ?>. <?php _e('Enter your Slide Pane text for this image below.', DFCG_DOMAIN); ?></p>
	<p>
		<label class="screen-reader-text" for="_dfcg-desc"><?php _e('Slide Pane Description', DFCG_DOMAIN); ?></label>
		<textarea id="_dfcg-desc" name="_dfcg-desc" class="large-text" cols="2" rows="4"><?php echo get_post_meta($post->ID, '_dfcg-desc', true); ?></textarea>
	</p>
	
	<?php elseif( $dfcg_options['desc-method'] == 'auto' )  : // Slide Pane Description is Auto ?>
		
	<h4><?php _e('Slide Pane Description', DFCG_DOMAIN); ?>:</h4>
	<p class="howto"><?php _e('You are currently using', DFCG_DOMAIN); ?> <a href="<?php echo 'admin.php?page=' . DFCG_FILE_HOOK; ?>"><?php _e('Auto', DFCG_DOMAIN); ?></a> <?php _e('Slide Pane descriptions', DFCG_DOMAIN); ?>.</p>
	
	<input id="_dfcg-desc" name="_dfcg-desc" type="hidden" value="<?php echo get_post_meta($post->ID, '_dfcg-desc', true); ?>" />
	
	
	<?php else : // Slide Pane Description is None ?>
	
	<h4><?php _e('Slide Pane Description', DFCG_DOMAIN); ?>:</h4>
	<p class="howto"><a href="<?php echo 'admin.php?page=' . DFCG_FILE_HOOK; ?>"><?php _e('Slide Pane descriptions', DFCG_DOMAIN); ?></a> <?php _e('are set to "None".', DFCG_DOMAIN) ; ?></p>
	
	<input id="_dfcg-desc" name="_dfcg-desc" type="hidden" value="<?php echo get_post_meta($post->ID, '_dfcg-desc', true); ?>" />
	
	<?php endif; ?>
	

<?php /* EXTERNAL LINK BLOCK */ ?>
	
	<hr class="div" />
	
	<h4><?php _e('External link for image', DFCG_DOMAIN ); ?>:</h4>
	<p class="howto"><?php _e('Enter a link here (including http://) if you want this image to link to somewhere other than the Post/Page permalink. Leave blank to link to the Post/Page.', DFCG_DOMAIN); ?></p>
	
	<p><strong><em><?php _e('External link URL', DFCG_DOMAIN ); ?>:</em></strong><br />
		<label class="screen-reader-text" for="_dfcg-link"><?php _e('External link URL', DFCG_DOMAIN ); ?></label>
		<input id="_dfcg-link" name="_dfcg-link" class="large-text" type="text" value="<?php echo get_post_meta($post->ID, '_dfcg-link', true); ?>" />
	</p>
	
	<p><strong><em><?php _e('Title attribute of external link', DFCG_DOMAIN ); ?>:</em></strong><br />
	
		<label class="screen-reader-text" for="_dfcg-link-title-attr"><?php _e('Title attribute of external link', DFCG_DOMAIN ); ?></label>
		<input id="_dfcg-link-title-attr" name="_dfcg-link-title-attr" class="large-text" type="text" value="<?php echo get_post_meta($post->ID, '_dfcg-link-title-attr', true); ?>" />
	</p>		
	
		
<?php /* ID METHOD SORT ORDER BLOCK */ ?>

	<?php if( $dfcg_options['populate-method'] == 'id-method' && $dfcg_options['id-sort-control'] == 'true' ) : ?>
	
	<hr class="div" />
	
	<h4><?php _e('Sort Order', DFCG_DOMAIN); ?>:</h4>
	<p>
		<label class="screen-reader-text" for="_dfcg-sort"><?php _e('Sort Order', DFCG_DOMAIN); ?></label>
		<input name="_dfcg-sort" id="_dfcg-sort" size="3" type="text" value="<?php echo get_post_meta($post->ID, '_dfcg-sort', true); ?>" />
	</p>
	<p class="howto"><?php _e('By default, images are arranged in the DCG in page/post ID number order. You can override this here by specifying a sort order.', DFCG_DOMAIN); ?></p>
	
	<?php else : ?>
		<input id="_dfcg-sort" name="_dfcg-sort" type="hidden" value="<?php echo get_post_meta($post->ID, '_dfcg-sort', true); ?>" />
	<?php endif; ?>
	
<?php /* EXCLUDE POST BLOCK - Only show Exclude option for multi-option, one-category and CPT */ ?>
	
	<?php if( $dfcg_options['populate-method'] !== 'id-method' ) :
		$exclude = false;
		if( get_post_meta($post->ID,'_dfcg-exclude',true) == 'true' ) {
			$exclude = true;
		} else {
			$exclude = false;
		}
	?>
	
	<hr class="div" />
	
	<h4><?php _e('Exclude this Post/Page from gallery?', DFCG_DOMAIN); ?></h4>
	<p>
		<input type="checkbox" id="_dfcg-exclude" name="_dfcg-exclude" <?php checked($exclude); ?> />
		<label for="_dfcg-exclude">&nbsp;<?php _e('Check to exclude', DFCG_DOMAIN ); ?></label>
	</p>
	
	<?php endif;
}


/**
 * Saves data added/edited in metaboxes in Post and Page screen
 *
 * Hooked to 'save_post'
 *
 * Adapted from Write Panel plugin by Nathan Rice
 *
 * @since 3.2.1
 * @updated 4.0
 * @param mixed $post_id Post ID
 * @param object $post object
 * @return nothing Calls add_* update_* delete_option functions to save validated data to db
 */
function dfcg_save_metabox_data( $post_id, $post ) {
	
	// Check referrer is from DCG metabox
	if ( !wp_verify_nonce( isset( $_POST['dfcg_metabox_noncename'] ), DFCG_FILE_HOOK ) ) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post->ID ) )
			return $post->ID;
	} else {
		if ( !current_user_can( 'edit_post', $post->ID ) )
			return $post->ID;
	}

	// Build array from $_POST data	
	$newdata['_dfcg-image'] = $_POST['_dfcg-image'];
	$newdata['_dfcg-desc'] = $_POST['_dfcg-desc'];
	$newdata['_dfcg-link'] = $_POST['_dfcg-link'];
	$newdata['_dfcg-sort'] = $_POST['_dfcg-sort'];
	$newdata['_dfcg-link-title-attr'] = $_POST['_dfcg-link-title-attr'];
	
	
	// Deal with checkboxes.
	// Note: we don't want to save data for unchecked checkboxes
	$newdata['_dfcg-exclude'] = $_POST['_dfcg-exclude'] ? 'true' : NULL;
	
	
	
	/* Validate and Sanitise data */
	
	// trim whitespace - all options
	foreach( $newdata as $key => $value ) {
		$input[$key] = trim( $value );
	}
	
	
	// Deal with Image (could be partial or full)
	if( $newdata['_dfcg-image'] ) {
		
		// If we are using Partial URL, check if first character in string is a /
		if( substr( $newdata['_dfcg-image'], 0, 1 ) == '/' ) {
			// Remove leading slash
			$newdata['_dfcg-image'] = substr( $newdata['_dfcg-image'], 1 );
		}
		
		$newdata['_dfcg-image'] = esc_attr( $newdata['_dfcg-image'] );
	} 
	
	
	
	// Deal with URLs
	$newdata['_dfcg-link'] = esc_url_raw( $newdata['_dfcg-link'] );
	
	// Deal with Link Attribute
	$newdata['_dfcg-link-title-attr'] = esc_attr( $newdata['_dfcg-link-title-attr'] );
	
	// Deal with Description
	$allowed_html = array( 'a' => array('href' => array(),'title' => array() ), 'br' => array(), 'em' => array(), 'strong' => array() );
	$allowed_protocols = array( 'http', 'https', 'mailto', 'feed' );
	
	$newdata['_dfcg-desc'] = wp_kses( $newdata['_dfcg-desc'], $allowed_html, $allowed_protocols );
	
	
	// Deal with Sort Order
	$newdata['_dfcg-sort'] = substr( $newdata['_dfcg-sort'], 0, 4 );
	$newdata['_dfcg-sort'] = esc_attr($newdata['_dfcg-sort']);
	
	
	// Add values of $newdata as custom fields
	
	foreach( $newdata as $key => $value ) {
		
		if( $post->post_type == 'revision' ) return; //don't store custom data twice
		
		$value = implode(',', (array)$value); //if $value is an array, make it a CSV (unlikely)
		
		if( get_post_meta( $post->ID, $key, FALSE ) ) { //if the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { //if the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		
		if( !$value ) delete_post_meta( $post->ID, $key ); //delete if any are blank or NULL
	}
}