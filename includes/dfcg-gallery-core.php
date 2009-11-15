<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.0 RC4
*
*	These are the 'public' functions which produce the gallery in the browser
*	Loads header scripts
*	Defines template tag
*
*	@since	3.0
*
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}


/**	Template tag to display gallery in theme files
*
*	Do not use in the Loop.
*
*	@uses	dynamic-gallery.php
*	@since	2.1
*/
function dynamic_content_gallery() {
	global $dfcg_options;
	include_once( DFCG_DIR . '/dynamic-gallery.php' );
}


/***** Functions to display gallery ******************** */

/* 	Function to determine which pages get the MOOTOOLS or JQUERY scripts loaded into wp_head.
*
*	Called by add_action('wp_head', ).
*	
*	Settings options are homepage, a page template or other.
*	Settings "other" loads scripts into every page.
*
*	Determines whether to load MOOTOOLS or JQUERY scripts
*
*	@uses	dfcg_header_scripts()
*	@since 	3.0
*/
function dfcg_load_scripts() {
	
	global $dfcg_options;
	
	if( $dfcg_options['limit-scripts'] == 'homepage' ) {
    
    	if( is_home() || is_front_page() ) {
    		
			if( $dfcg_options['scripts'] == 'mootools' ) {
				dfcg_mootools_scripts($dfcg_options);
    		} else {
				dfcg_jquery_scripts($dfcg_options);
			}
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
		
    } elseif( $dfcg_options['scripts'] == 'mootools' ) {
	 
		dfcg_mootools_scripts($dfcg_options);
	
    } else {
	
		dfcg_jquery_scripts($dfcg_options);
	}
}


/* 	Enqueue jQuery in header
*
*	Adds jQuery framework to header using template_redirect hook
*
*	@since 3.0
*/
function dfcg_enqueue_script() {

	global $dfcg_options;
	
	if( !is_admin() && $dfcg_options['scripts'] == 'jquery' ) {
	
		if( $dfcg_options['limit-scripts'] == 'homepage' ) {
    
    		if( is_home() || is_front_page() ) {
    		
				// Pull in jQuery
				wp_enqueue_script('jquery');
    		}
	
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
		
    	} else {
		
			// Pull in jQuery
			wp_enqueue_script('jquery');
		}
	}
}


/* 	Function to display MOOTOOLS header scripts and css
*
*	Called by dfcg_load_scripts which is hooked to wp_head action.
*	Loads scripts and CSS into head
*
*	@param 	array	$dfcg_options, the array of plugin options
*	@uses	includes dfcg-user-styles.php
*	@since	1.0
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


/* 	Function to display JQUERY header scripts and css
*
*	Called by dfcg_load_scripts which is hooked to wp_head action.
*	Loads scripts and CSS into head
*
*	@param 	array	$dfcg_options, the array of plugin options
*	@uses	includes dfcg-user-styles.php
*	@since	3.0
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

/* 8 Items used in mootools version
$dfcg_options['defaultTransition']
$dfcg_options['slideInfoZoneSlide']
$dfcg_options['textShowCarousel']
$dfcg_options['showCarousel']
$dfcg_options['showInfopane']
$dfcg_options['timed']
$dfcg_options['delay']
$dfcg_options['slideInfoZoneOpacity']
*/
