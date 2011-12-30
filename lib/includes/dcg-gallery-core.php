<?php
/**
 * Front-end - These are the core front end functions for producing the gallery in the browser
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2012
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * - Defines template tag
 * - Loads gallery scripts and CSS
 * - Various helper functions used by the gallery constructor functions
 *
 *
 * NOTES about new script handling introduced in DCG v4.0:
 *
 * 1. wp_enqueue_style() is used to load pure CSS files, eg something.css
 * 2. wp_enqueue_script() is used to load pure JS files, eg something.js
 * 3. js function calls and dynamic CSS in a php file are simply echoed to the browser via wp_head or wp_footer as appropriate.
 *
 * The above are wrapped in various functions named dfcg_load_{something}() which are called by
 * the dfcg_scripts_css_loader() function, which is hooked to 'template_redirect' so that this function is run before any of the
 * action hooks that it then calls.
 * 
 * The logic of this setup is that dfcg_enqueue_scripts_css():
 * - determines whether it should load mootools or jquery css/js
 * - determines which page to load the scripts on, based on the DCG Settings > Load Scripts user options
 * - then, based on the above, processes various add_action calls to execute the relevant dfcg_load_{something}() functions.
 *
 * Note that the hook used in these add_action calls depends on which dfcg_load_{something}() function is being called:
 * - Anything using wp_enqueue_script() or wp_enqueue_style() is hooked to 'wp_enqueue_scripts' (as per Otto)
 * - Anything printed directly into the head or footer is hooked to wp_head or wp_footer 
 *
 * @since 3.0
 */

/* Prevent direct access to this file */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}


/**
 * Template tag to display gallery in theme files
 *
 * Do not use in the Loop.
 *
 * Note: DCG Widget uses this function too.
 *
 * @since 2.1
 * @updated 4.0
 *
 * @uses dfcg_multioption_method_gallery()
 * @uses dfcg_onecategory_method_gallery()
 * @uses dfcg_id_method_gallery()
 * @uses dfcg_jq_multioption_method_gallery()
 * @uses dfcg_jq_onecategory_method_gallery()
 * @uses dfcg_jq_id_method_gallery()
 *
 * @global array $dfcg_options Plugin options from db
 * @return echos out gallery markup and content
 */
function dynamic_content_gallery() {
	global $dfcg_options;
	
	if( $dfcg_options['scripts'] == 'mootools' ) {

		if( $dfcg_options['populate-method'] == 'multi-option' ) {
			// Populate method = MULTI-OPTION
			$output = dfcg_multioption_method_gallery();
	
		} elseif( $dfcg_options['populate-method'] == 'one-category' || $dfcg_options['populate-method'] == 'custom-post' ) {
			// Populate method = ONE CATEGORY or CUSTOM POST TYPE
			$output = dfcg_onecategory_method_gallery();
	
		} elseif( $dfcg_options['populate-method'] == 'id-method' ) {
			// Populate method = ID METHOD
			$output = dfcg_id_method_gallery();
		}

	} elseif( $dfcg_options['scripts'] == 'jquery' ) {
	
		if( $dfcg_options['populate-method'] == 'multi-option' ) {
			// Populate method = MULTI-OPTION
			$output = dfcg_jq_multioption_method_gallery();
	
		} elseif( $dfcg_options['populate-method'] == 'one-category' || $dfcg_options['populate-method'] == 'custom-post' ) {
			// Populate method = ONE CATEGORY or CUSTOM POST TYPE
			$output = dfcg_jq_onecategory_method_gallery();

		} elseif( $dfcg_options['populate-method'] == 'id-method' ) {
			// Populate method = PAGES
			$output = dfcg_jq_id_method_gallery();
		}

	} else {
	
		// Something has gone horribly wrong and there's no output!
		$output = '';
		$output .= "\n" . __('<p>Dynamic Content Gallery Error: View page source for details.</p>', DFCG_DOMAIN) . "\n";
		$output .= "\n" . '<!-- ' . __('DCG Error: The plugin is unable to generate any output.', DFCG_DOMAIN) .' -->';
		$output .= "\n" . '<!-- ' . __('Rating: Critical. Fix error in order to display gallery.', DFCG_DOMAIN) .' -->';
		$output .= "\n" . '<!-- ' . __('Fix: Check that the plugin has been installed properly and that all files contained within the download ZIP file have been uploaded to your server.', DFCG_DOMAIN) .' -->';

	}

	/* Output the gallery and markup */

	// Open wrapper div
	echo "\n" . '<div id="dfcg-outer-wrap"><!-- Start of Dynamic Content Gallery output -->';

	// Run hook
	do_action( 'dfcg_before' );

	// Gallery output
	echo $output;

	// Run hook
	do_action( 'dfcg_after' );

	// Close wrapper div
	echo "\n\n" . '</div><!-- end #dfcg-outer-wrap and Dynamic Content Gallery output -->' . "\n";
}


/***** Functions to handle scripts ******************** */

/**
 * Function to load the appropriate MOOTOOLS or JQUERY scripts/css.
 *
 * Hooked to 'template_redirect' action
 *
 * This function determines whether to load mootools or jquery js/css, and on what page 
 *
 * This function replaces the following deprecated functions, wef v4.0:
 * - dfcg_load_scripts_header()
 * - dfcg_load_scripts_footer()
 * - dfcg_enqueue_jquery()
 *
 * The 7 (3 for mootools, 4 for jquery) dfcg_load-xxx() functions replace the following deprecated functions, wef v4.0:
 * - dfcg_mootools_scripts()
 * - dfcg_jquery_css()
 * - dfcg_jquery_smooth_scripts()
 *
 * @since 4.0
 *
 * @uses dfcg_load_mootools()
 * @uses dfcg_load_mootools_user()
 * @uses dfcg_load_jquery_smooth()
 * @uses dfcg_load_jquery_js()
 * @uses dfcg_load_jquery_smooth_user_css()
 * @uses dfcg_load_jquery_smooth_user_js()
 *
 * @global array $dfcg_options Plugin options from db
 */
function dfcg_enqueue_scripts_css() {
	
	global $dfcg_options;
	
	if( $dfcg_options['limit-scripts'] == 'homepage' && ( is_home() || is_front_page() ) ) {
    	
    	if( $dfcg_options['scripts'] == 'mootools' ) {
    	
			add_action( 'wp_enqueue_scripts', 'dfcg_load_mootools' );
			add_action( 'wp_head', 'dfcg_load_mootools_user' );
			
    	} else {
			
			add_action( 'wp_enqueue_scripts', 'dfcg_load_jquery_smooth' );
			add_action( 'wp_head', 'dfcg_load_jquery_smooth_user_css' );
			add_action( 'wp_footer', 'dfcg_load_jquery_smooth_user_js', 100 );
		
		}
    
    } elseif( $dfcg_options['limit-scripts'] == 'pagetemplate' ) {
	
		$dfcg_page_filenames = $dfcg_options['page-filename'];
	
		// Turn list into an array
		$dfcg_page_filenames = explode( ",", $dfcg_page_filenames );
	
		foreach ( $dfcg_page_filenames as $key ) {
			if( is_page_template( $key ) ) {
				
				// Mootools or Jquery?
				if( $dfcg_options['scripts'] == 'mootools' ) {
					
					add_action( 'wp_enqueue_scripts', 'dfcg_load_mootools' );
					add_action( 'wp_head', 'dfcg_load_mootools_user' );
				
				} else {
					
					add_action( 'wp_enqueue_scripts', 'dfcg_load_jquery_smooth' );
					add_action( 'wp_head', 'dfcg_load_jquery_smooth_user_css' );
					add_action( 'wp_footer', 'dfcg_load_jquery_smooth_user_js', 100 );
				
				}
    		}
    	}
		
	} elseif( $dfcg_options['limit-scripts'] == 'page' ) {
	
		$page_ids = $dfcg_options['page-ids'];
		
		// Turn list into array
		$page_ids = explode( ",", $page_ids );
		
		foreach ( $page_ids as $key ) {
			if( is_page( $key ) ) {
			
				// Mootools or Jquery?
				if( $dfcg_options['scripts'] == 'mootools' ) {
					
					add_action( 'wp_enqueue_scripts', 'dfcg_load_mootools' );
					add_action( 'wp_head', 'dfcg_load_mootools_user' );
				
				} else {
					
					add_action( 'wp_enqueue_scripts', 'dfcg_load_jquery_smooth' );
					add_action( 'wp_head', 'dfcg_load_jquery_smooth_user_css' );
					add_action( 'wp_footer', 'dfcg_load_jquery_smooth_user_js', 100 );
				
				}
			}
		}
		
    } elseif( $dfcg_options['limit-scripts'] == 'other' ) {
		
		if( $dfcg_options['scripts'] == 'mootools' ) {
	 		
	 		add_action( 'wp_enqueue_scripts', 'dfcg_load_mootools' );
			add_action( 'wp_head', 'dfcg_load_mootools_user' );
		
		} else {		
			
			add_action( 'wp_enqueue_scripts', 'dfcg_load_jquery_smooth' );
			add_action( 'wp_head', 'dfcg_load_jquery_smooth_user_css' );
			add_action( 'wp_footer', 'dfcg_load_jquery_smooth_user_js', 100 );
		
		}
	}
}


/**
 * Enqueue mootools smoothgallery CSS and JS files
 *
 * Hooked to 'wp_enqueue_scripts' in dfcg_enqueue_scripts_css()
 *
 * @since 4.0
 */
function dfcg_load_mootools() {

	global $dfcg_options;
	
	wp_enqueue_style( 'dcg_mootools', DFCG_LIB_URL . '/js-mootools/css/jd.gallery.css', false, DFCG_VER, 'all' );
	
	if( $dfcg_options['mootools'] !== '1' ) {
		wp_enqueue_script( 'dcg_mootools_core', DFCG_LIB_URL . '/js-mootools/scripts/mootools-1.2.4-core-jm.js', false, DFCG_VER );
		wp_enqueue_script( 'dcg_mootools_more', DFCG_LIB_URL . '/js-mootools/scripts/mootools-1.2.4.4-more.js', false, DFCG_VER );
	}
	wp_enqueue_script( 'dcg_mootools_js', DFCG_LIB_URL . '/js-mootools/scripts/jd.gallery_1_2_4_4.js', false, DFCG_VER );
	wp_enqueue_script( 'dcg_mootools_trans', DFCG_LIB_URL . '/js-mootools/scripts/jd.gallery.transitions_1_2_4_4.js', false, DFCG_VER );
}


/**
 * Load mootools user-defined js and CSS (with dynamic content from db options)
 *
 * Hooked to 'wp_head'
 *
 * @since 4.0
 *
 * @global $dfcg_options array DCG options from database
 */
function dfcg_load_mootools_user() {

	global $dfcg_options;
	
	printf( "\n" . '<!-- Dynamic Content Gallery plugin version %s www.studiograsshopper.ch  Begin scripts and dynamic CSS -->', DFCG_VER );
	
	// Add user defined CSS
	include_once( DFCG_LIB_DIR . '/includes/dcg-gallery-mootools-styles.php' );
	
	// Add JS function call to gallery
	echo "\n" . '<script type="text/javascript">
   function startGallery() {
      var myGallery = new gallery($("myGallery"), {
	  showArrows: '. $dfcg_options['showArrows'] .',
	  showCarousel: '. $dfcg_options['showCarousel'] .',
	  showInfopane: '. $dfcg_options['showInfopane'] .',
	  timed: '. $dfcg_options['timed'] .',
	  delay: '. $dfcg_options['delay'] .',
	  defaultTransition: "'. $dfcg_options['defaultTransition'] .'",
	  slideInfoZoneOpacity: '. $dfcg_options['slideInfoZoneOpacity'] .',
	  slideInfoZoneSlide: '. $dfcg_options['slideInfoZoneSlide'] .',
	  carouselMinimizedOpacity: '. $dfcg_options['carouselMinimizedOpacity'] .',
	  textShowCarousel: "'. $dfcg_options['textShowCarousel'] .'"
      });
   }
   window.addEvent("domready",startGallery);
</script>' . "\n";
	
	echo '<!-- End of Dynamic Content Gallery scripts and dynamic CSS -->' . "\n\n";
}



/**
 * Enqueue jQuery Smooth JS and CSS
 *
 * Hooked to 'wp_enqueue_scripts'
 *
 * Note: js file is enqueued to the footer, jQuery is enqueued to the head
 *
 * @since 4.0
 */
function dfcg_load_jquery_smooth() {
	
	wp_enqueue_style( 'dcg_jquery_css', DFCG_LIB_URL . '/js-jquery-smooth/css/dcg-jquery-smooth.css', false, DFCG_VER, 'all' );
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'dcg_smooth_js', DFCG_LIB_URL . '/js-jquery-smooth/scripts/dcg-jq-script.min.js', false, DFCG_VER, true );	

}

/**
 * Load jQuery Smooth dynamic CSS
 * 
 * Hooked to 'wp_head'
 *
 * @since 4.0
 *
 * @global $dfcg_options array DCG options from database
 */
function dfcg_load_jquery_smooth_user_css() {

	global $dfcg_options;
	
    // Add user-defined CSS files
	printf( "\n" . '<!-- Dynamic Content Gallery plugin version %s www.studiograsshopper.ch  Begin dynamic CSS -->', DFCG_VER );
	
	// Add user-defined CSS set in Settings page
	include_once( DFCG_LIB_DIR .'/includes/dcg-gallery-jquery-smooth-styles.php'  );
	
	echo '<!-- End of Dynamic Content Gallery dynamic CSS -->' . "\n\n";
}


/**
 * Load jQuery Smooth js function call (with dynamic params)
 *
 * Hooked to 'wp_footer' with very low priority to make sure it loads after the main js file
 *
 * @since 4.0
 *
 * @global $dfcg_options array DCG options from database
 */
function dfcg_load_jquery_smooth_user_js() {
	
	global $dfcg_options;
	
	printf( "\n" . '<!-- Dynamic Content Gallery plugin version %s www.studiograsshopper.ch  Add jQuery smoothSlideshow scripts -->' . "\n", DFCG_VER );
		
	echo '<script type="text/javascript">
		jQuery("#dfcg-slideshow").smoothSlideshow("#dfcg-wrapper", {
			showArrows: '. $dfcg_options['showArrows'] .',
			showCarousel: '. $dfcg_options['showCarousel'] .',
			showInfopane: '. $dfcg_options['showInfopane'] .',
			timed: '. $dfcg_options['timed'] .',
			delay: '. $dfcg_options['delay'] .',
			thumbScrollSpeed:4,
			preloader: true,
			preloaderImage: true,
			preloaderErrorImage: true,
			elementSelector: "li",
			imgContainer:"#dfcg-image",
			imgPrevBtn:"#dfcg-imgprev",
			imgNextBtn:"#dfcg-imgnext",
			imgLinkBtn:"#dfcg-imglink",
			titleSelector: "h3",
			subtitleSelector: "p",
			linkSelector: "a",
			imageSelector: "img.full",
			thumbnailSelector: "img.thumbnail",
			carouselContainerSelector: "#dfcg-thumbnails",
			thumbnailContainerSelector: "#dfcg-slider",
			thumbnailInfoSelector: "#dfcg-sliderInfo",
			carouselSlideDownSelector: "#dfcg-openGallery",
			carouselSlideDownSpeed: 500,
			infoContainerSelector:"#dfcg-text",
			borderActive:"#fff",
			slideInfoZoneOpacity: '. $dfcg_options['slideInfoZoneOpacity'] .',
			carouselOpacity: 0.3,
			thumbSpacing: 10,
			slideInfoZoneStatic: '. $dfcg_options['slideInfoZoneStatic'] .'
		});
		</script>';
	
	echo "\n" . '<!-- End of Dynamic Content Gallery plugin scripts -->' . "\n\n";
}



/***** Utility functions used by gallery constructor functions  ******************** */

/**
 * Function to get the link for the main image
 *
 * Used by gallery constructor functions
 *
 * Grabs the DCG Metabox External Link URL and title attribute (held as post_meta)
 * and returns these, or permalink and post/page title if they don't exist.
 *
 * @since 4.0
 *
 * @param int $id (required) post/page ID
 * @param string $title (required) post/page title used for link title attr
 * @global array $dfcg_postmeta Array containing dfcg custom field keys
 * @return array $link Array containing Link URL and link title attribute
 */
function dfcg_get_link( $id, $title ) {

	global $dfcg_postmeta;
	
	$link = array();
	
	// External Link URL
	if( $link['link_url'] = get_post_meta( $id, $dfcg_postmeta['link'], true ) ) {
	
		// External Link title
		$link['link_title_attr'] = get_post_meta( $id, $dfcg_postmeta['link_title_attr'], true );
		
	} else {
	
		$link['link_url'] = get_permalink( $id );
		$link['link_title_attr'] = esc_attr( $title );
		
	}
	
	return $link;
}



/**
 * Function to get the thumbnail for carousel
 *
 * Used by gallery constructor functions
 *
 * @since 3.3
 * @updated 4.0
 *
 * @uses get_the_post_thumbnail() WP function
 *
 * @param $id (integer) (required) post/page ID
 * @param $image_src (string) - URL to image
 * @param $title (string) - post/page title used for IMG alt attr
 * @global array $dfcg_options Plugin options from db
 * @return string $thumb_html HTML markup for thumbnail
 */
function dfcg_get_thumbnail( $id, $image_src, $title ) {
	global $dfcg_options;
	
	// Get the thumbnail - uses Post Thumbnails if AUTO images are used
	if( current_theme_supports( 'post-thumbnails' ) && $dfcg_options['thumb-type'] == "featured-image" && $dfcg_options['scripts'] == 'mootools') {
		
		$args = array(
			"class" => "dfcg-postthumb-auto thumbnail",
			"alt" => esc_attr( $title ),
			);
		
		$thumb = get_the_post_thumbnail( $id, 'DCG_Thumb_100x75_true', $args );
		
		if( $thumb ) {
			//print_r($thumb);
			$thumb_html = $thumb;
			
		} else {
			//print_r("not set");
			// A Featured Image has not been set for this post
			$thumb_html = sprintf( '<img class="dfcg-postthumb-notset thumbnail" src="%s" alt="%s" />', $image_src, esc_attr($title) );
		}
		
	} else {
		// Legacy thumbnails, therefore just use $image_src, no resizing etc
		$thumb_html = sprintf( '<img class="dfcg-thumb-legacy thumbnail" src="%s" alt="%s" />', $image_src, esc_attr($title) );
	}
	
	return $thumb_html;
}






/**
 * Function to get the main Image
 *
 * Used by gallery constructor functions
 *
 * Either returns the Featured Image or the DCG metabox URL - depending on the DCG Settings
 *
 * If Featured Images are used, this will be overridden by the DCG metabox URL, if not empty.
 * Alternatively, the default image will be returned or, if that doesn't exist, the Error Img
 *
 * In Featured Image mode, the Featured image can be overriden by an Image URL entered in the
 * DCG Metabox. If the Metabox image URL box is not empty, the URL entered here is displayed instead
 *
 * Function returns an array ($image) containing 5 elements - src, w(idth), h(eight), class, msg (message)
 * All XHTML markup is handled in the gallery constructor function, not here
 *
 * @since 4.0
 *
 * @uses dfcg_grab_featured_image() for getting the Featured Image
 * @uses get_post_meta() for getting the DCG metabox URL
 * @uses file_exists() to check if default image exists in location specified
 * @uses getimagesize() to get width and height of default images
 *
 * @param $id (integer) (required) post/page ID
 * @param $term_id (string/int) - Term ID, relevant for default image filenames in Multi Option, One Cat, Custom Post Types
 * @global $dfcg_options (array) DCG Settings from db
 * @global $dfcg_postmeta (array) DCG cutsom field keys
 * @global $dfcg_baseimgurl (string) Base URL of DCG metabox images (takes into account Full or Partial)
 * @return $image (array) = src, w(idth), h(eight), class, msg (message)
 */
function dfcg_get_image( $id, $term_id = NULL ) {

	global $dfcg_options, $dfcg_postmeta, $dfcg_baseimgurl;
		
	$image = array();
	
	/***** Using Featured Images *****/
	if( $dfcg_options['image-url-type'] == "auto" ) {
			
		// Metabox image may override Auto Featured Image
		if( $image['src'] = get_post_meta( $id, $dfcg_postmeta['image'], true ) ) {
		
			$image['src'] = $dfcg_baseimgurl . $image['src'];
				
			/*$image_path = str_replace( get_bloginfo('url'), ABSPATH, $image['src'] );
			@list( $image['w'], $image['h'] ) = getimagesize( $image_path );
			// Note: this will throw a PHP error if image is hosted in a different domain!
			// May need to re-think this...
			*/
				
			// Assume that size of any manual image is same as gallery dimensions
			// This might not be true, but users should do their own resizing!
			$image['w'] = $dfcg_options['gallery-width'];
			$image['h'] = $dfcg_options['gallery-height'];
			
			$image['class'] = 'dfcg-auto-metabox full';
        	$image['msg'] = '31'; // Info: DCG metabox image URL overrides Featured Image.
        	// Note: No Error message will be triggered if Metabox image is set but URL is wrong, ie 404.
			
			return $image;
		}

		
		// No override, let's get the Featured Image
		$image = dfcg_get_featured_image( $id );
		
		if( $image['src'] ) {
			
			$image['msg'] = '30'; // Featured image found, no Override
			// Note: No Error message will be triggered if the attachment has been physically removed/moved by FTP for example, ie 404.
			
			return $image;
		}
		
		$msg = '34'; // Featured image not set (nor was there a Metabox URL)
		
	} // End of if( Auto )
	
	/***** Using Manual Images *****/
	if( $dfcg_options['image-url-type'] !== "auto" ) {
						
		// Get the Metabox image
		if( $image['src'] = get_post_meta( $id, $dfcg_postmeta['image'], true ) ) {
						
			$image['src'] = $dfcg_baseimgurl . $image['src'];
			//$image_path = str_replace( get_bloginfo( 'url' ), ABSPATH, $image['src'] );
			//@list( $image['w'], $image['h'] ) = getimagesize( $image_path );
			
			// Assume that size of any manual image is same as gallery dimensions
			// This might not be true, but users should do their own resizing!
			$image['w'] = $dfcg_options['gallery-width'];
			$image['h'] = $dfcg_options['gallery-height'];
			$image['class'] = "dfcg-metabox full";
        	$image['msg'] = '35';
			// Note: No Error message will be triggered if Metabox image is set but URL is wrong, ie image gives 404
			
			return $image;
		}
		
		$msg = '36'; // Full/Partial, No metabox URL
	}
	
	
	/***** No Metabox and no Featured Image - let's get default image *****/				
	
	// Get default image folder or URL (if id-method)
	if( $dfcg_options['populate-method'] == 'id-method' ) {
		
		$def_img_folder_url = $dfcg_options['defimgid'];
	
	} else {
	
		$def_img_folder_url = $dfcg_options['defimgfolder'];
	}
	
	// Convert URL to path. Strip domain name from URL, replace with ABSPATH. Default folder can now be anywhere
	// Note: ABSPATH has a trailingslash, site_url doesn't
	$home = site_url() . '/';
	$def_img_folder_path = str_replace( $home, ABSPATH, $def_img_folder_url );
	
	
	// Path to Default Category image
	if( $term_id == NULL ) {
		// We're in id-method
		$def_img_name = '';
	
	} elseif( $term_id !== '' ) {
		// We're in multi-option, or one-cat/custom-post with a term selected
		$def_img_name = $term_id . '.jpg';
	
	} else {
		// We're in one-cat/custom-post with no term selected, ie 'all'
		$def_img_name = 'all.jpg';
	}
	
	// Path to default image	
	$def_img_path = $def_img_folder_path . $def_img_name;
		
	if( file_exists( $def_img_path ) ) {
		@list( $image['w'], $image['h'] ) = getimagesize( $def_img_path );
		$image['src'] = $def_img_folder_url . $def_img_name;
		$image['class'] = "dfcg-default full";
		
		// $msg will either be 34 (from Auto), or 36 (from Full/Partial)
		if( $msg == '34') {
			
			$image['msg'] = '34.1'; // Auto, no Override, Featured image not set, Default image displayed
		
		} elseif( $msg == '36') {
			
			$image['msg'] = '36.1'; // Full/Partial, DCG Metabox image URL empty, Default image displayed
		}
		
		return $image;
		
	}
	
	//$msg = '37'; // Default image doesn't exist
								
	$image['src'] = DFCG_ERRORIMGURL;
	$image['w'] = '250';
	$image['h'] = '194';
	$image['class'] = "dfcg-error full";
	
	// $msg will either be 34 (Auto), or nothing (Full/Partial)
	if( $msg == '34') {
			
		$image['msg'] = '34.2'; // Auto, no Override, Featured image not set, No Default image, ErrorImg displayed
		
	} else{
			
		$image['msg'] = '36.2'; // Full/Partial, DCG Metabox image URL empty, No Default image, ErrorImg displayed
	}
        
	return $image;	
}



/**
 * Function to get the Slide Pane description
 *
 * Used by gallery constructor functions
 *
 * Outputs the HTML for the Slide Pane text, in <p> tags
 *
 * @since 4.0
 *
 * @uses dfcg_the_content_limit(), creates Auto description (see dfcg-gallery-content-limit.php)
 *
 * @param $id (integer) (required) post/page ID
 * @param $term_id (string/int) - Term ID, relevant for category/custom taxonomy descriptions
 * @global $dfcg_options (array)
 * @global $dfcg_postmeta (array)
 * @return $desc_html (string) HTML markup and text of Slide Pane description
 */
function dfcg_get_desc( $id, $term_id = NULL ) {

	global $dfcg_options, $dfcg_postmeta;
	
	$desc_html = '';

	if( $dfcg_options['desc-method'] == 'auto' ) {
		// We're using Auto custom excerpt
		$desc_html = dfcg_the_content_limit( $dfcg_options['max-char'], $dfcg_options['more-text'] );
					
	} elseif( $dfcg_options['desc-method'] == 'manual' ) {
	
		// Do we append Read More to manual descriptions?
		if( $dfcg_options['desc-man-link'] ) {
			$more = '&nbsp;<a href="'.get_permalink().'">' . $dfcg_options['more-text'] . '</a>';
		} else {
			$more = '';
		}
						
		if( get_post_meta( $id, $dfcg_postmeta['desc'], true ) ){
			// We have a Custom field description - takes priority
			$desc_html = '<p class="dfcg-desc-metabox">' . get_post_meta( $id, $dfcg_postmeta['desc'], true ) . $more . '</p>';

		} elseif( $dfcg_options['populate-method'] == 'multi-option' && category_description( $term_id ) !== '' ) {
			// show the category description (note: no <p> tags required)
			$desc_html = category_description( $term_id );
			
		} elseif( $dfcg_options['populate-method'] == 'one-category' && category_description( $term_id ) !== '' ) {
			// show the category description (note: no <p> tags required)
			$desc_html = category_description( $term_id );
			
		} elseif( $dfcg_options['populate-method'] == 'custom-post-type' && term_description( $term_id, $dfcg_options['cpt-tax-name'] ) ) {
			// show the category description (note: no <p> tags required)
			$desc_html = term_description( $term_id, $dfcg_options['cpt-tax-name'] );

		} elseif( $dfcg_options['defimagedesc'] !== '' ) {
			// or show the default description
			$desc_html = '<p class="dfcg-desc-default">' . stripslashes( $dfcg_options['defimagedesc'] ) . $more . '</p>';
							
		} else {
			// Fall back to Auto custom excerpt
			$desc_html = dfcg_the_content_limit( $dfcg_options['max-char'], $dfcg_options['more-text'] );
		}
		
	} elseif( $dfcg_options['desc-method'] == 'excerpt' ) {
		// No <p> tags needed because we run this through the_excerpt filters
		$desc_html = get_the_excerpt();
		$desc_html = apply_filters( 'the_excerpt', $desc_html );
		
	} else {
		// We're using "None" (note: smoothgallery needs <p> tags or won't work)
		$desc_html = '<p class="dfcg-desc-none"></p>';
	}
	
	return $desc_html;
}