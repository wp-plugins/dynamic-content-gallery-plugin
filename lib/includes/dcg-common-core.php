<?php
/**
 * Common - These are core functions used in both Admin and Public
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2012
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * @info Various helper functions used by the gallery constructor functions and admin
 * @info dfcg_baseimgurl()
 * @info dfcg_postmeta_info()
 * @info dfcg_query_list()
 * @info dfcg_get_featured_image()
 * @info dfcg_get_custom_post_types()
 *
 * @since 4.0
 */


/**
 * Prevent direct access to this file
 */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}


/**
 * Function to determine base URL of custom field images
 *
 * If FULL or AUTO, baseimgurl is empty; if PARTIAL, baseimgurl is pulled from options
 *
 * @global array $dfcg_options Plugin options from db
 * @return string $output Either the base URL (PARTIAL) or empty (FULL)
 * @since 3.0
 * @updated 3.3
 */
function dfcg_baseimgurl() {

	global $dfcg_options;
	
	if( $dfcg_options['image-url-type'] == "full" || $dfcg_options['image-url-type'] == "auto" ) {
		$output = '';
		
	} else {
		
		$output = $dfcg_options['imageurl'];
	}
	
	return $output;
}


/**
 * Function to populate the $postmeta array with correct postmeta key names
 *
 * The main benefit of this is code portability in the event, for some reason, the postmeta meta_key names
 * are changed in future. In this case, only need to update this function.
 *
 * Used by gallery constructor functions
 *
 * @return array $postmeta
 * @since 3.3
 * @updated 4.0
 */
function dfcg_postmeta_info() {

	$postmeta['desc'] = '_dfcg-desc';
	$postmeta['image'] = '_dfcg-image';
	$postmeta['exclude'] = '_dfcg-exclude';
	$postmeta['link'] = '_dfcg-link';
	$postmeta['link-window'] = '_dfcg-link-window';
	$postmeta['link_title_attr'] = '_dfcg-link-title-attr';
	$postmeta['override-main'] = '_dfcg-main-override';
	$postmeta['override-thumb'] = '_dfcg-thumb-override';

	return $postmeta;
}



/**
 * Function to build an array of cat/off pairs from Multi Option Image Slot Settings
 *
 * Gets cat01 to cat10 and off01 to off10 from $dfcg_options array, skips empty image slots,
 * and builds an array for use in WP_Query in Multi-Option constructors.
 *
 * Used by all Multi Option constructor functions
 *
 * @global array $dfcg_options Plugin options from db
 * @return array $query_list	Array of cat/offset pairs
 * @since 3.2
 */
function dfcg_query_list() {

	global $dfcg_options;

	// Set up variable to convert Slot to real Offset
	$offset = 1;

	$query_list = array();

	// Loop through the 9 possible cats/post selects
	for( $i=1; $i < 10; $i+=1 ) {
	
		// Set temp variables for catXX and offXX
		$tmpcat = 'cat0'.$i;
		$tmpoff = 'off0'.$i;
	
		// Get Settings
		$tmpcats = $dfcg_options[$tmpcat];
		$tmpoffs = $dfcg_options[$tmpoff];
	
		// If Post Select is empty, skip
		if( empty( $tmpoffs ) ) continue;
	
		// Convert Post Select to real Offset
		$tmpoffs = $tmpoffs-$offset;
	
		// Create temp assoc array $key=>$value pair
		$tmp_query_list[$tmpcats] = $tmpoffs;
	
		// Add this array to final array
		array_push( $query_list, $tmp_query_list );
	
		// Empty temp array ready for next loop
		unset( $tmp_query_list );
	}
	return $query_list;
}


/**
 * Gets Featured Image "DCG_Main_wxh_true" sized image from the post/page
 *
 * Used by dfcg_get_image() function
 *
 * This assumes that the image has been uploaded since the last time the gallery height and width
 * DCG options were set. If not, the new image size may not exist and a "soft" resized version may be
 * displayed instead (ie, relies on browser resizing).
 * To avoid this, users should
 * re-run the Regenerate Thumbnails plugin to create the new image sizes.
 *
 * @uses current_theme_supports() WP function
 * @uses wp_get_attachment_image_src() WP function
 * @uses get_post_thumbnail() WP function
 *
 * @param $id (int|string) post ID
 * @global $dfcg_options (array) DCG Settings from db
 * @return $image (array) = src, w(idth), h(eight), class, or returns false
 *
 * @since 4.0
 */
function dfcg_get_featured_image( $id ) {

	if( !current_theme_supports( 'post-thumbnails' ) ) return false;
	
	global $dfcg_options;
		
	// Eg: 'DCG_Main_588x350_true'
	$size = 'DCG_Main_' . $dfcg_options['gallery-width'] . 'x' . $dfcg_options['gallery-height'] . '_' . $dfcg_options['crop'];
	
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size );
	
	// Set up the array elements to be sent back to the calling function
	if( $image ) {
		$image['src'] = $image[0];
		$image['w'] = $image[1];
		$image['h'] = $image[2];
		$image['class'] = 'dfcg-auto full';
		unset( $image[0], $image[1], $image[2], $image[3] );
	}	
	return $image;
}


/**
 * Helper function to get list of all registered Custom Post Types
 *
 * @since 3.3
 * @return object(array) $post_types Object containing all registered CPTs
 */
function dfcg_get_custom_post_types() {
	
	$args=array(
  		'public'   => true,
  		'_builtin' => false
		); 
	$output = 'objects'; // names or objects
	$operator = 'and'; // 'and' or 'or'
	$post_types = get_post_types($args, $output, $operator);
	
	return $post_types; // An object
}