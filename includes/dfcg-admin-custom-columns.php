<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.0 RC4
*
*	Functions and filters for adding custom columns to Edit Posts & Edit Pages screens
*
*	@since	3.0
*
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}


/** Add columns to Posts and Pages Edit screen to display dfcg-image custom field contents.
*
*	This can be turned off in the DCG Settings Page. 
*
*	@uses	manage_posts_column filter
*	@uses	manage_posts_custom_column action
*	@since	3.0
*/
// Filters and Actions to add the dfcg-image columns
if( $dfcg_options['posts-column'] == "true" ) {
	add_filter('manage_posts_columns', 'dfcg_posts_columns');
	add_action('manage_posts_custom_column', 'dfcg_custom_posts_column', 10, 2);
}
if( $dfcg_options['pages-column'] == "true" ) {
	add_filter('manage_pages_columns', 'dfcg_posts_columns');
	add_action('manage_pages_custom_column', 'dfcg_custom_posts_column', 10, 2);
}

// Filters and Actions to add the dfcg-desc columns
if( $dfcg_options['posts-desc-column'] == "true" ) {
	add_filter('manage_posts_columns', 'dfcg_posts_desc_columns');
	add_action('manage_posts_custom_column', 'dfcg_custom_posts_desc_column', 10, 2);
}
if( $dfcg_options['pages-desc-column'] == "true" ) {
	add_filter('manage_pages_columns', 'dfcg_posts_desc_columns');
	add_action('manage_pages_custom_column', 'dfcg_custom_posts_desc_column', 10, 2);
}


// Add dfcg-image columns
function dfcg_posts_columns($defaults) {
    $defaults['dcg_image'] = __('DCG dfcg-image');
    return $defaults;
}

// Populate new dfcg-image columns
function dfcg_custom_posts_column($column_name, $post_id) {
    
	global $wpdb;
    
	// Check we're only messing with my column
	if( $column_name == 'dcg_image' ) {
        
		// Query. TODO: Is prepare necessary?
		$dfcg_query = $wpdb->get_results(
			$wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE $wpdb->postmeta.post_id = %d AND $wpdb->postmeta.meta_key = %s", $post_id, 'dfcg-image')
			);
        
        if( $dfcg_query ) {
            $my_func = create_function('$att', 'return $att->meta_value;');
            $text = array_map( $my_func, $dfcg_query );
            echo implode(', ',$text);
        } else {
            echo '<i>'.__('None').'</i>';
        }
    }
}


// Add dfcg-desc columns
function dfcg_posts_desc_columns($defaults) {
    $defaults['dcg_desc'] = __('DCG dfcg-desc');
    return $defaults;
}

// Populate new dfcg-desc columns
function dfcg_custom_posts_desc_column($column_name, $post_id) {
    
	global $wpdb;
    
	// Check we're only messing with my column
	if( $column_name == 'dcg_desc' ) {
        
		// Query. TODO: Is prepare necessary?
		$dfcg_query = $wpdb->get_results(
			$wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE $wpdb->postmeta.post_id = %d AND $wpdb->postmeta.meta_key = %s", $post_id, 'dfcg-desc')
			);
        
        if( $dfcg_query ) {
            $my_func = create_function('$att', 'return $att->meta_value;');
            $text = array_map( $my_func, $dfcg_query );
            echo implode(', ',$text);
        } else {
            echo '<i>'.__('None').'</i>';
        }
    }
}
