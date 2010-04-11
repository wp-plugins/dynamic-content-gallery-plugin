<?php
/**
* Front-end - These are the core functions for loading scripts, creating template tag, etc
*
* @copyright Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
* @package dynamic_content_gallery
* @version 3.2.3
*
* @info These are the 'public' functions which produce the gallery in the browser
* @info Loads header scripts
* @info Defines template tag
*
* @since 3.0
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}


/**
* Template tag to display gallery in theme files
*
* Do not use in the Loop.
*
* @uses	dynamic-gallery.php
* @global array $dfcg_options Plugin options from db
* @since 2.1
*/
function dynamic_content_gallery() {
	global $dfcg_options;
	include_once( DFCG_DIR . '/dynamic-gallery.php' );
}


/***** Functions to display gallery ******************** */

/**
* Function to determine which pages get the MOOTOOLS or JQUERY scripts loaded into wp_head.
*
* Hooked to 'wp_head' action
*
* Settings options are homepage, a page template or other.
* Settings "other" loads scripts into every page.
*
* Determines whether to load MOOTOOLS or JQUERY scripts
*
* @uses	dfcg_mootools_scripts()
* @uses dfcg_jquery_scripts()
*
* @global array $dfcg_options Plugin options from db
* @since 3.2.2
*/
function dfcg_load_scripts() {
	
	global $dfcg_options;
	
	if( $dfcg_options['limit-scripts'] == 'homepage' && ( is_home() || is_front_page() ) ) {
    	
    	if( $dfcg_options['scripts'] == 'mootools' ) {
			dfcg_mootools_scripts($dfcg_options);
    	} else {
			dfcg_jquery_scripts($dfcg_options);
		}
    
    } elseif( $dfcg_options['limit-scripts'] == 'pagetemplate' ) {
	
		$dfcg_page_filenames = $dfcg_options['page-filename'];
	
		// Turn list into an array
		$dfcg_page_filenames = explode(",", $dfcg_page_filenames);
	
		foreach ( $dfcg_page_filenames as $key) {
			if( is_page_template($key) ) {
				
				// Mootools or Jquery?
				if( $dfcg_options['scripts'] == 'mootools' ) {
					dfcg_mootools_scripts($dfcg_options);
				} else {
					dfcg_jquery_scripts($dfcg_options);
				}
    		}
    	}
		
	} elseif( $dfcg_options['limit-scripts'] == 'page' ) {
	
		$page_ids = $dfcg_options['page-ids'];
		
		// Turn list into array
		$page_ids = explode(",", $page_ids);
		
		foreach ( $page_ids as $key ) {
			if( is_page($key) ) {
			
				// Mootools or Jquery?
				if( $dfcg_options['scripts'] == 'mootools' ) {
					dfcg_mootools_scripts($dfcg_options);
				} else {
					dfcg_jquery_scripts($dfcg_options);
				}
			}
		}
		
    } elseif( $dfcg_options['limit-scripts'] == 'other' ) {
		
		if( $dfcg_options['scripts'] == 'mootools' ) {
	 		dfcg_mootools_scripts($dfcg_options);
		} else {		
			dfcg_jquery_scripts($dfcg_options);
		}
	}
}


/**
* Enqueue jQuery in header
*
* Adds jQuery framework to header using template_redirect hook
*
* @uses wp_enqueue_script()
*
* @global array $dfcg_options Plugin options from db
* @since 3.2.2
*/
function dfcg_enqueue_script() {

	global $dfcg_options;
	
	if( $dfcg_options['scripts'] == 'jquery' && !is_admin() ) {
	
		if( $dfcg_options['limit-scripts'] == 'homepage' && ( is_home() || is_front_page() ) ) {
    		
    		// Pull in jQuery
			wp_enqueue_script('jquery');
    	
		} elseif( $dfcg_options['limit-scripts'] == 'pagetemplate' ) {
	
			$dfcg_page_filenames = $dfcg_options['page-filename'];
	
			// Turn list into an array
			$dfcg_page_filenames = explode(",", $dfcg_page_filenames);
	
			foreach ( $dfcg_page_filenames as $key) {
				if( is_page_template($key) ) {
				
					// Pull in jQuery
					wp_enqueue_script('jquery');
    			}
    		}
		
		} elseif( $dfcg_options['limit-scripts'] == 'page' ) {
	
			$page_ids = $dfcg_options['page-ids'];
			
			// Turn list into array
			$page_ids = explode(",", $page_ids);
			
			foreach ( $page_ids as $key ) {
				if( is_page($key) ) {
					
					// Pull in jQuery
				wp_enqueue_script('jquery');
				}
			}
		
    	} elseif( $dfcg_options['limit-scripts'] == 'other' ) {
			
			// Pull in jQuery
			wp_enqueue_script('jquery');
		}
	}
}


/**
* Function to display MOOTOOLS header scripts and css
*
* Called by dfcg_load_scripts() which is hooked to wp_head action.
* Loads scripts and CSS into head
*
* @param array $dfcg_options, Plugin options from db
* @uses includes dfcg-user-styles.php
* @since 1.0
*/
function dfcg_mootools_scripts($dfcg_options) {
    
	// Add CSS file
	echo "\n" . '<!-- Dynamic Content Gallery plugin version ' . DFCG_VER . ' www.studiograsshopper.ch  Begin scripts -->' . "\n";
	echo '<link type="text/css" rel="stylesheet" href="' . DFCG_URL . '/js-mootools/css/jd.gallery.css" />' . "\n";
	
	// Should mootools framework be loaded?
	if ( $dfcg_options['mootools'] !== '1' ) {
		echo '<script type="text/javascript" src="' . DFCG_URL . '/js-mootools/scripts/mootools.v1.11.js"></script>' . "\n";
	}
	
	// Add gallery javascript files
	echo '<script type="text/javascript" src="' . DFCG_URL . '/js-mootools/scripts/jd.gallery.js"></script>' . "\n";
	echo '<script type="text/javascript" src="' . DFCG_URL . '/js-mootools/scripts/jd.gallery.transitions.js"></script>' . "\n";
	
	// Add JS function call to gallery
	echo '<script type="text/javascript">
   function startGallery() {
      var myGallery = new gallery($("myGallery"), {
	  showCarousel: '. $dfcg_options['showCarousel'] .',
	  showInfopane: '. $dfcg_options['showInfopane'] .',
	  timed: '. $dfcg_options['timed'] .',
	  delay: '. $dfcg_options['delay'] .',
	  defaultTransition: "'. $dfcg_options['defaultTransition'] .'",
	  slideInfoZoneOpacity: '. $dfcg_options['slideInfoZoneOpacity'] .',
	  slideInfoZoneSlide: '. $dfcg_options['slideInfoZoneSlide'] .',
	  textShowCarousel: "'. $dfcg_options['textShowCarousel'] .'"
      });
   }
   window.addEvent("domready",startGallery);
</script>' . "\n";
	
	// Add user defined CSS
	include_once( DFCG_DIR . '/includes/dfcg-gallery-mootools-styles.php');
	
	// End of scripts
	echo '<!-- End of Dynamic Content Gallery scripts -->' . "\n\n";
}


/**
* Function to display JQUERY header scripts and css
*
* Called by dfcg_load_scripts() which is hooked to wp_head action.
* Loads scripts and CSS into head
*
* @uses includes dfcg-user-styles.php
*
* @global array $dfcg_options Plugin options from db
* @since 3.0
*/
function dfcg_jquery_scripts() {

	global $dfcg_options;
	
    // Add javascript and CSS files
	echo "\n" . '<!-- Dynamic Content Gallery plugin version ' . DFCG_VER . ' www.studiograsshopper.ch  Begin jQuery scripts -->' . "\n";
	echo '<script type="text/javascript" src="' . DFCG_URL . '/js-jquery/scripts/jquery.easing.1.3.js"></script>' . "\n";
	echo '<script type="text/javascript" src="' . DFCG_URL . '/js-jquery/scripts/jquery.timers-1.1.2.js"></script>' . "\n";
	echo '<script type="text/javascript" src="' . DFCG_URL . '/js-jquery/scripts/jquery.galleryview-1.1.js"></script>' . "\n";
	
	// Add user-defined CSS set in Settings page
	include_once( DFCG_DIR .'/includes/dfcg-gallery-jquery-styles.php');
	
	// Add JS script function and arguments
	echo "<script type='text/javascript'>
	jQuery.noConflict();
	jQuery(document).ready(function(){
		jQuery('#dfcg_images').galleryView({
    		panel_width: ". $dfcg_options['gallery-width'] .",
    		panel_height: ". $dfcg_options['gallery-height'] .",
			overlay_height: ". $dfcg_options['slide-height'] .",
			overlay_opacity: ". $dfcg_options['slideInfoZoneOpacity'] .",
			overlay_color: '". $dfcg_options['slide-overlay-color'] ."',
			overlay_position: '". $dfcg_options['slide-overlay-position'] ."',
			background_panel: '". $dfcg_options['gallery-background'] ."',
    		transition_speed: ". $dfcg_options['transition-speed'] .",
    		transition_interval: ". $dfcg_options['delay'] .",
    		nav_theme: '". $dfcg_options['nav-theme'] ."',
    		border: '". $dfcg_options['gallery-border-thick'] ."px solid ". $dfcg_options['gallery-border-colour'] ."',
    		pause_on_hover: ". $dfcg_options['pause-on-hover'] .",
			fade_panels: ". $dfcg_options['fade-panels'] ."
		});
	});
	</script>";
	echo "\n" . '<!-- End of Dynamic Content Gallery plugin scripts -->' . "\n";
}


/**
* Function to determine base URL of custom field images
*
* If FULL => baseimgurl is empty, if PARTIAL => baseimgurl is pulled from options
*
* @global array $dfcg_options Plugin options from db
* @return string $output Either the base URL (PARTIAL) or empty (FULL)
* @since 3.0
*/
function dfcg_baseimgurl() {

	global $dfcg_options;
	
	// Do we have a base URL for Custom field images? Set base URL variable
	if ( $dfcg_options['image-url-type'] == "full" ) {
		// There is no base URL, so make it empty
		$output = '';
	} else {
		// Partial or No URL, therefore there is a base URL, so get it
		$output = $dfcg_options['imageurl'];
	}
	return $output;
}


/**
* Function to build an array of cat/off pairs from Multi Option Image Slot Settings
*
* Gets cat01 to cat10 and off01 to off10 from $dfcg_options array, skips empty image slots,
* and builds an array for use in WP_Query in Multi-Option constructors.
*
* Used by all js script framework constructors
*
* @global array $dfcg_options Plugin options from db
* @return array $query_list	Array of cat/off pairs
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
		if( empty($tmpoffs) ) continue;
	
		// Convert Post Select to real Offset
		$tmpoffs = $tmpoffs-$offset;
	
		// Create temp assoc array $key=>$value pair
		$tmp_query_list[$tmpcats] = $tmpoffs;
	
		// Add this array to final array
		array_push($query_list, $tmp_query_list);
	
		// Empty temp array ready for next loop
		unset($tmp_query_list);
	}
	return $query_list;
}
