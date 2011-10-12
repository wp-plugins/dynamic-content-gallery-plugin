<?php
/**
 * Functions and filters for adding custom columns to Edit Posts & Edit Pages screens
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2011
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
	exit( __( 'Sorry, you are not allowed to access this file directly.' ) );
}


// Filters and Actions to add the dfcg-image columns
// Only loaded if the relevant DCG Setting option is checked
if( isset( $dfcg_options['posts-column']) && $dfcg_options['posts-column'] == "true" ) {
	add_filter( 'manage_posts_columns', 'dfcg_image_column' );
	add_action( 'manage_posts_custom_column', 'dfcg_image_column_contents', 10, 2 );
}
if( isset($dfcg_options['pages-column']) && $dfcg_options['pages-column'] == "true" ) {
	add_filter( 'manage_pages_columns', 'dfcg_image_column' );
	add_action( 'manage_pages_custom_column', 'dfcg_image_column_contents', 10, 2 );
}

// Filters and Actions to add the dfcg-desc columns
// Only loaded if the relevant DCG Setting option is checked
if( isset( $dfcg_options['posts-desc-column']) && $dfcg_options['posts-desc-column'] == "true" ) {
	add_filter( 'manage_posts_columns', 'dfcg_desc_column' );
	add_action( 'manage_posts_custom_column', 'dfcg_desc_column_contents', 10, 2 );
}
if( isset( $dfcg_options['pages-desc-column']) && $dfcg_options['pages-desc-column'] == "true" ) {
	add_filter( 'manage_pages_columns', 'dfcg_desc_column' );
	add_action( 'manage_pages_custom_column', 'dfcg_desc_column_contents', 10, 2 );
}

// Filters and Actions to add the dfcg-sort columns - only ever used on Edit Pages screen
if( isset( $dfcg_options['pages-sort-column']) && $dfcg_options['pages-sort-column'] == "true" ) {
	add_filter( 'manage_pages_columns', 'dfcg_pages_sort_column' );
	add_action( 'manage_pages_custom_column', 'dfcg_pages_sort_column_contents', 10, 2 );
}

// Filters and Actions to add the Featured Image column
if( isset( $dfcg_options['posts-featured-image-column']) && $dfcg_options['posts-featured-image-column'] == "true" ) {
	add_filter( 'manage_posts_columns', 'dfcg_featured_image_column');
	add_action( 'manage_posts_custom_column', 'dfcg_featured_image_column_content', 10, 2);
}
if( isset( $dfcg_options['pages-featured-image-column']) && $dfcg_options['pages-featured-image-column'] == "true" ) {
	add_filter( 'manage_pages_columns', 'dfcg_featured_image_column');
	add_action( 'manage_pages_custom_column', 'dfcg_featured_image_column_content', 10, 2);
}


/**
 * Function to add DCG Image column
 *
 * Column to display _dfcg-image custom field, ie DCG Metabox Image URL
 *
 * @param array $defaults Default Edit screen columns
 * @return array $defaults Modified Edit screen columns
 * @since 3.3.3
 * @updated 4.0
 */
function dfcg_image_column( $defaults ) {
    $defaults['dfcg_image_column'] = __( 'DCG Image' );
    return $defaults;
}


/**
 * Function to populate new DCG Image column
 *
 * Displays DCG Metabox Image URL as a link (with thickbox class to display actual image on click)
 * Displays "Use as Main" and "Use as Thumb" if these have been checked. Useful for troubleshooting image issues.
 *
 * @param mixed $column_name	Name of Edit screen column
 * @param mixed $post_id	ID of Post/Page being displayed on Edit screen
 * @global string $dfcg_baseimgurl	URL to images folder
 * @global array $dfcg_options plugin options from db
 * @since 3.3.3
 * @updated 4.0
 */
function dfcg_image_column_contents( $column_name, $post_id ) {
    
	global $dfcg_baseimgurl, $dfcg_options, $dfcg_postmeta;
    
	// Check we're only messing with my column
	if( $column_name == 'dfcg_image_column' ) {
        
        // First see if we are using Featured Images for the DCG
        if( $dfcg_options['image-url-type'] == "auto" ) {
        
        	// Do we have a manual override for this post?
        	if( $main = get_post_meta( $post_id, '_dfcg-main-override', true ) ) {
        	
        		// Grab the manual override URL if it exists
        		if( $image = get_post_meta( $post_id, $dfcg_postmeta['image'], true ) ) {
        
        			$image = $dfcg_baseimgurl . $image;
					echo '<a href="'.$image.'" class="thickbox" title="DCG Metabox URL Override: '.$image.'">Manual image</a>';
                	
        		} else {
            
            		echo '<i>'.__('Override is checked, but image URL is missing.').'</i>';
            		echo '<i>'.__('Featured image will be used instead.').'</i>';
        		}
        	
        		echo '<br /><i>Main:</i> Yes';
        	
        	}
        	
        	
        	if( $thumb = get_post_meta( $post_id, '_dfcg-thumb-override', true ) ) {
				echo '<br /><i>Thumb:</i> Yes';
			} else {
				echo '<br /><i>Thumb:</i> No';
			}
        	
        	echo '<br />Featured image.';
        	if( has_post_thumbnail( $post_id ) ) {
				the_post_thumbnail( array(100,100) );
			}
		
		// We're using FULL or Partial manual images
		} else {
		
			$image = get_post_meta( $post_id, $dfcg_postmeta['image'], true );
			$image = $dfcg_baseimgurl . $image;
			echo '<a href="'.$image.'" class="thickbox" title="DCG Metabox URL: '.$image.'">'.$image.'</a>';
			
			
		
		}
        
        
        
        
        
        // Get Metabox Thumbnail Override checkbox
        if( $thumb = get_post_meta( $post_id, '_dfcg-thumb-override', true ) )
			echo '<br /><i>Use as Thumb:</i> Yes';
			
    }
}


/**
 * Function to add dfcg-desc column
 *
 * @param array $defaults Default Edit screen columns
 * @return array $defaults Modified Edit screen columns
 * @since 3.3.3
 */
function dfcg_desc_column($defaults) {
    $defaults['dfcg_desc_column'] = __('DCG Desc');
    return $defaults;
}

/**
 * Function to populate new dfcg-desc column
 *
 * @param mixed $column_name	Name of Edit screen column
 * @param mixed $post_id	ID of Post/Page being displayed on Edit screen
 * 
 * @since 3.3.3
 * @updated 4.0
 */
function dfcg_desc_column_contents($column_name, $post_id) {
    
	// Check we're only messing with my column
	if( $column_name == 'dfcg_desc_column' ) {
	
		if( $desc = get_post_meta( $post_id, '_dfcg-desc', true ) ) {
		
			$desc = dfcg_shorten_desc( $desc );
			
			echo $desc;
			
		} else {
		
			echo '<i>'.__('None').'</i>';
			
		}
	}
}
        

/**
 * Function to add dfcg-sort columns
 *
 * @param array $defaults Default Edit screen columns
 * @return array $defaults Modified Edit screen columns
 * @since 3.3.3
 */
function dfcg_pages_sort_column($defaults) {
    $defaults['dfcg_pages_sort_column'] = __('DCG Page Sort');
    return $defaults;
}

/**
 * Function to populate new dfcg-sort columns
 *
 * @param mixed $column_name	Name of Edit screen column
 * @param mixed $post_id	ID of Post/Page being displayed on Edit screen
 * 
 * @since 3.3.3
 * @updated 4.0
 */
function dfcg_pages_sort_column_contents($column_name, $post_id) {
    
	// Check we're only messing with my column
	if( $column_name == 'dfcg_pages_sort_column' ) {
	
		if( $sort = get_post_meta( $post_id, '_dfcg-sort', true ) ) {
		
			echo $sort;
			
		} else {
		
			echo '<i>'.__('None').'</i>';
		
		}
	}
}
        

/**
 * Helper function to shorten the length of dfcg-desc when displayed in Post/Page Edit screen
 *
 * Based on my Limit Title plugin
 *
 * @param string $string	 Contents of dfcg-desc custom field
 * @return string $string Shortened dfcg-desc text
 * @since 3.0
 */
function dfcg_shorten_desc($string) {

	$length = '30';
	$replacer = ' [...]';
   
	if(strlen($string) > $length)
		$string = (preg_match('/^(.*)\W.*$/', substr($string, 0, $length+1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;

	return $string;
}


/**
 * Function to add Featured Image column
 *
 * @param array $defaults Default Edit screen columns
 * @return array $defaults Modified Edit screen columns
 * @since 4.0
 */
function dfcg_featured_image_column( $defaults ) {
       $defaults['dfcg_featured_image'] = __('Featured Image') ;
    	return $defaults;
}


/**
 * Function to populate Featured Image column
 *
 * @param mixed $column_name	Name of Edit screen column
 * @param mixed $post_id	ID of Post/Page being displayed on Edit screen
 * @since 4.0
 */
function dfcg_featured_image_column_content($column_name, $id) {
    
    // Check we're only messing with my column
    if( $column_name == 'dfcg_featured_image') {
    	
    	//$args = array(
		//	"class" => "dfcg-postthumb-auto thumbnail"
		//	);
		
		if( has_post_thumbnail( $id ) ) {
			the_post_thumbnail( array(80,80) );
		}
		//$size = 'DCG Thumb 100x75 TRUE';
	
		//$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size );
		
		//$thumb = get_the_post_thumbnail( $id, 'DCG Thumb 100x75 TRUE', $args );

		//if( $thumb )
			//echo $thumb;
			//echo '<a href="'.$image[0].'" class="thickbox" title="Featured Image">';
			//echo '<img .$image.'</a>';
		//else
			//echo '<i>Not set</i>';
    }
}