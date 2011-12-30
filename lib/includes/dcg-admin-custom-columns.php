<?php
/**
 * Functions and filters for adding custom columns to Edit Posts & Edit Pages screens
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2012
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * Displays DCG Image, DCG Desc, Featured Image (if current_theme_supports('post-thumbnails'), which is tested in ui function)
 * If post-thumbnails aren't enabled, the UI checkboxes are set to blank, therefore the conditional checks in this function
 * will prevent display of the Featured Images column.
 *
 * All custom columns can be turned off in the DCG Settings Page.
 *
 * @since 3.2
 */

/* Prevent direct access to this file */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}


add_action( 'admin_init', 'dfcg_load_tools' );
/**
 * Helper function to determine whether to activate Filters and Actions to load Tools or not
 * based on DCG Settings > Tools tab checkboxes
 *
 * Hooked to 'admin_init'
 *
 * Checkboxes reduced to a single checkbox for each column.
 * The gallery method option determines which column is visible, independant of the checkbox status
 * eg: ID method - a checked column shows on posts, cpt and pages edit screens
 * eg: custom post - a checked column shows only on cpt edit screen
 * eg: multi/onecat - a checked column shows only on normal posts edit screen
 *
 * DCG Desc only appears if 'manual' is set - no point showing desc if excerpt/auto or none
 *
 * Column order will normally be DCG Image - DCG Desc - Featured Image
 * except for multi-option/one category = > Featured Image - DCG Image - DCG Desc
 *
 * Featured image column appears on everything - posts, pages, cpt - regardless of gallery method
 *
 * @global array $dfcg_options plugin options from db
 * @return load various actions and filters
 */
function dfcg_load_tools() {

	global $dfcg_options;

	
	switch ( $dfcg_options['populate-method'] ) {
	
		case 'custom-post':
			$cpt = $dfcg_options['cpt-name'];
			if( $dfcg_options['column-img'] == "true" ) {
				add_filter( 'manage_edit-'.$cpt.'_columns', 'dfcg_image_column' );
				add_action( 'manage_posts_custom_column', 'dfcg_image_column_contents', 10, 2 );
			}
			if( $dfcg_options['column-desc'] == "true" && $dfcg_options['desc-method'] == 'manual' ) {
				add_filter( 'manage_edit-'.$cpt.'_columns', 'dfcg_desc_column' );
				add_action( 'manage_posts_custom_column', 'dfcg_desc_column_contents', 10, 2 );
			}
			if( $dfcg_options['column-feat-img'] == "true" ) {
				add_filter( 'manage_posts_columns', 'dfcg_featured_image_column');
				add_action( 'manage_posts_custom_column', 'dfcg_featured_image_column_content', 10, 2);
				add_filter( 'manage_pages_columns', 'dfcg_featured_image_column');
				add_action( 'manage_pages_custom_column', 'dfcg_featured_image_column_content', 10, 2);
			}
			break;
			
		case 'id-method':
			if( $dfcg_options['column-img'] == "true" ) {
				add_filter( 'manage_posts_columns', 'dfcg_image_column' );
				add_action( 'manage_posts_custom_column', 'dfcg_image_column_contents', 10, 2 );
				add_filter( 'manage_pages_columns', 'dfcg_image_column' );
				add_action( 'manage_pages_custom_column', 'dfcg_image_column_contents', 10, 2 );
			}
			if( $dfcg_options['column-desc'] == "true" && $dfcg_options['desc-method'] == 'manual' ) {
				add_filter( 'manage_posts_columns', 'dfcg_desc_column' );
				add_action( 'manage_posts_custom_column', 'dfcg_desc_column_contents', 10, 2 );
				add_filter( 'manage_pages_columns', 'dfcg_desc_column' );
				add_action( 'manage_pages_custom_column', 'dfcg_desc_column_contents', 10, 2 );
			}
			if( $dfcg_options['column-sort'] == "true" ) {
				add_filter( 'manage_pages_columns', 'dfcg_sort_column' );
				add_action( 'manage_pages_custom_column', 'dfcg_sort_column_contents', 10, 2 );
				add_filter( 'manage_posts_columns', 'dfcg_sort_column' );
				add_action( 'manage_posts_custom_column', 'dfcg_sort_column_contents', 10, 2 );
			}
			if( $dfcg_options['column-feat-img'] == "true" ) {
				add_filter( 'manage_posts_columns', 'dfcg_featured_image_column');
				add_action( 'manage_posts_custom_column', 'dfcg_featured_image_column_content', 10, 2);
				add_filter( 'manage_pages_columns', 'dfcg_featured_image_column');
				add_action( 'manage_pages_custom_column', 'dfcg_featured_image_column_content', 10, 2);
			}
			break;
		
		default:
			if( $dfcg_options['column-img'] == "true" ) {
				add_filter( 'manage_edit-post_columns', 'dfcg_image_column' );
				add_action( 'manage_posts_custom_column', 'dfcg_image_column_contents', 10, 2 );
			}
			if( $dfcg_options['column-desc'] == "true" && $dfcg_options['desc-method'] == 'manual' ) {
				add_filter( 'manage_edit-post_columns', 'dfcg_desc_column' );
				add_action( 'manage_posts_custom_column', 'dfcg_desc_column_contents', 10, 2 );
			}
			if( $dfcg_options['column-feat-img'] == "true" ) {
				add_filter( 'manage_posts_columns', 'dfcg_featured_image_column');
				add_action( 'manage_posts_custom_column', 'dfcg_featured_image_column_content', 10, 2);
				add_filter( 'manage_pages_columns', 'dfcg_featured_image_column');
				add_action( 'manage_pages_custom_column', 'dfcg_featured_image_column_content', 10, 2);
			}
			break;
	}
}		
	



/**
 * Filter callback to add DCG Image column
 *
 * Column to display DCG Image in posts/pages edit screen
 *
 * Hooked to 'manage_edit-{$post_type}_columns', 'manage_posts_columns', 'manage_pages_columns' filters
 *
 * @since 3.3.3
 * @updated 4.0
 * @param array $defaults Default Edit screen columns
 * @return array $defaults Modified Edit screen columns
 */
function dfcg_image_column( $defaults ) {
    $defaults['dfcg_image_column'] = __( 'DCG Image' );
    return $defaults;
}


/**
 * Function to populate new DCG Image column
 *
 * Displays DCG Metabox Image URL as a link (with thickbox class to display actual image on click)
 * If Auto, looks for DCG metabox override and if it exists, displays link
 * If no DCG Metabox image, displays Featured image thumb with link to DCG image size
 *
 * @since 3.3.3
 * @updated 4.0
 * @param mixed $column_name	Name of Edit screen column
 * @param mixed $post_id	ID of Post/Page being displayed on Edit screen
 * @global string $dfcg_baseimgurl	URL to images folder
 * @global array $dfcg_options plugin options from db
 * @return echos out XHTML and contents of column
 */
function dfcg_image_column_contents( $column_name, $post_id ) {
    
	global $dfcg_baseimgurl, $dfcg_options, $dfcg_postmeta;
    
	// Check we're only messing with my column
	if( $column_name !== 'dfcg_image_column' ) return;
        
	// First see if we are using Featured Images for the DCG
	if( $dfcg_options['image-url-type'] == "auto" ) {
        	
		// Grab the manual override URL if it exists
        if( $image = get_post_meta( $post_id, $dfcg_postmeta['image'], true ) ) {
        
        	$image = $dfcg_baseimgurl . $image;
			echo '<a href="'.$image.'" class="thickbox" title="DCG Metabox URL Override: '.$image.'">DCG Metabox image</a>';
			echo '<br /><i>'.__('Featured image is overridden by DCG Metabox image.', DFCG_DOMAIN).'</i><br />';
                	
			return;	
        } 
        	
        	
        // No override, so show the featured image
		$img = dfcg_get_featured_image( $post_id );
			
		//$thumb = get_the_post_thumbnail( $post_id, 'DCG Thumb 100x75 TRUE' );
			
		if( $img ) {
			
			$img_title = 'Featured Image. Size of DCG version: '.$img['w'].'x'.$img['h'].' px';
			$info = '(DCG gallery dimensions are '.$dfcg_options['gallery-width'].'x'.$dfcg_options['gallery-height'].' px)';
				
			echo '<a href="'.$img['src'].'" class="thickbox" title="'.$img_title.' '.$info.'">';
			the_post_thumbnail( 'DCG_Thumb_100x75_true' );
			echo '</a><br />';
			echo 'Featured image';
		} else {
			echo '<i>' . __('Featured image not set', DFCG_DOMAIN) . '</i>';
        }
		
	// We're using FULL or Partial manual images
	} else {
		
		echo 'DCG Metabox URL:<br />';
			
		if( $image = get_post_meta( $post_id, $dfcg_postmeta['image'], true ) ) {
			$image = $dfcg_baseimgurl . $image;
			echo '<a href="'.$image.'" class="thickbox" title="DCG Metabox URL: '.$image.'">'.$image.'</a>';
			
		} else {
			echo '<i>' . __('Not found', DFCG_DOMAIN) . '</i>';
			
		}
	}
}


/**
 * Filter callback to add DCG Desc column
 *
 * Column to display DCG Desc in posts/pages edit screen
 *
 * Hooked to 'manage_edit-{$post_type}_columns', 'manage_posts_columns', 'manage_pages_columns' filters
 *
 * @since 3.3.3
 * @param array $defaults Default Edit screen columns
 * @return array $defaults Modified Edit screen columns
 */
function dfcg_desc_column($defaults) {
    $defaults['dfcg_desc_column'] = __('DCG Desc');
    return $defaults;
}


/**
 * Function to populate new DCG Desc column
 *
 * @since 3.3.3
 * @updated 4.0
 * @param mixed $column_name	Name of Edit screen column
 * @param mixed $post_id	ID of Post/Page being displayed on Edit screen
 * @return echos out XHTML and contents of column
 */
function dfcg_desc_column_contents($column_name, $post_id) {
    
	global $dfcg_options;
	
	// Check we're only messing with my column
	if( $column_name !== 'dfcg_desc_column' ) return;
			
	echo 'DCG Metabox Desc:<br />';
			
	if( $desc = get_post_meta( $post_id, '_dfcg-desc', true ) ) {
		
		$desc = dfcg_shorten_desc( $desc );
			
		echo $desc;
			
	} else {
		
		echo '<i>'.__('Not found', DFCG_DOMAIN).'</i>';
	}
}
        

/**
 * Filter callback to add DCG Sort column
 *
 * Column to display DCG Sort in posts/pages edit screen
 *
 * Hooked to 'manage_edit-{$post_type}_columns', 'manage_posts_columns', 'manage_pages_columns' filters
 *
 * @since 3.3.3
 * @param array $defaults Default Edit screen columns
 * @return array $defaults Modified Edit screen columns
 */
function dfcg_sort_column($defaults) {
    $defaults['dfcg_pages_sort_column'] = __('DCG Sort');
    return $defaults;
}


/**
 * Function to populate new DCG Sort column
 *
 * @since 3.3.3
 * @updated 4.0
 * @param mixed $column_name Name of Edit screen column
 * @param mixed $post_id ID of Post/Page being displayed on Edit screen
 * @return echos out XHTML and contents of column 
 */
function dfcg_sort_column_contents($column_name, $post_id) {
    
	// Check we're only messing with my column
	if( $column_name !== 'dfcg_pages_sort_column' ) return;
	
	if( $sort = get_post_meta( $post_id, '_dfcg-sort', true ) ) {
		
		echo $sort;
			
	} else {
		
		echo '<i>'.__('None', DFCG_DOMAIN).'</i>';
		
	}
}
        

/**
 * Helper function to shorten the length of dfcg-desc when displayed in Post/Page Edit screen
 *
 * Based on my Limit Title plugin
 *
 * @since 3.0
 * @param string $string Contents of DCG Desc for a post or page
 * @return string $string Shortened dfcg-desc text
 */
function dfcg_shorten_desc($string) {

	$length = '30';
	$replacer = ' [...]';
   
	if(strlen($string) > $length)
		$string = (preg_match('/^(.*)\W.*$/', substr($string, 0, $length+1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;

	return $string;
}


/**
 * Filter callback to add Featured Image column
 *
 * Hooked to 'manage_edit-{$post_type}_columns', 'manage_posts_columns', 'manage_pages_columns' filters
 *
 * @since 4.0
 * @param array $defaults Default Edit screen columns
 * @return array $defaults Modified Edit screen columns
 */
function dfcg_featured_image_column( $defaults ) {
       $defaults['dfcg_featured_image'] = __('Featured Image') ;
    	return $defaults;
}


/**
 * Function to populate Featured Image column
 *
 * @since 4.0
 * @param mixed $column_name Name of Edit screen column
 * @param mixed $post_id ID of Post/Page being displayed on Edit screen
 * @return echos out XHTML and contents of column 
 */
function dfcg_featured_image_column_content($column_name, $id) {
    
    // Check we're only messing with my column
    if( $column_name !== 'dfcg_featured_image') return;
		
	if( has_post_thumbnail( $id ) ) {
		the_post_thumbnail( 'DCG_Thumb_100x75_true' );
	} else {
		echo '<i>' . __('Not set', DFCG_DOMAIN) . '</i>';
    }
}