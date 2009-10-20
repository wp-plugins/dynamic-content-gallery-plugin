<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	Dynamic Content Gallery
*	@version	3.0 RC3
*
*	Functions and filters for adding custom columns to Edit Posts & Edit Pages screens
*
*	@since	3.0
*
*/


/** Add columns to Posts and Pages Edit screen to display dfcg-image custom field contents.
*
*	This can be turned off in the DCG Settings Page. 
*
*	@uses	manage_posts_column filter
*	@uses	manage_posts_custom_column action
*	@since	3.0
*/
// Filters and Actions to add the columns
if( $dfcg_options['posts-column'] == "true" ) {
	add_filter('manage_posts_columns', 'dfcg_posts_columns');
	add_action('manage_posts_custom_column', 'dfcg_custom_posts_column', 10, 2);
}
if( $dfcg_options['pages-column'] == "true" ) {
	add_filter('manage_pages_columns', 'dfcg_posts_columns');
	add_action('manage_pages_custom_column', 'dfcg_custom_posts_column', 10, 2);
}

// Add columns
function dfcg_posts_columns($defaults) {
    $defaults['custom_fields'] = __('Custom Field dfcg-image');
    return $defaults;
}

// Populate new columns
function dfcg_custom_posts_column($column_name, $post_id) {
    global $wpdb;
    if( $column_name == 'custom_fields' ) {
        $query = "SELECT *
FROM $wpdb->postmeta
WHERE $wpdb->postmeta.post_id = $post_id
AND $wpdb->postmeta.meta_key = 'dfcg-image'";
        $dfcg_images = $wpdb->get_results($query);
        if( $dfcg_images ) {
            $my_func = create_function('$att', 'return $att->meta_value;');
            $text = array_map($my_func, $dfcg_images);
            echo implode(', ',$text);
        } else {
            echo '<i>'.__('None').'</i>';
        }
    }
}
