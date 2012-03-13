<?php
/**
 * Front-end - JQuery Flexslider module
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2012
 * @package dynamic_content_gallery
 * @version 4.0
 *
 *
 *
 * @since 4.0
 */

/* Prevent direct access to this file */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}


/**
 * Enqueue jQuery Flexslider JS and CSS
 *
 * Hooked to 'wp_enqueue_scripts'
 *
 * Note: js file is enqueued to the footer, jQuery is enqueued to the head
 *
 * @since 4.0
 */
function dfcg_load_flexslider() {
	
	wp_enqueue_style( 'dcg-flexslider-css', DFCG_FLEX_URL . '/css/flexslider.css', false, DFCG_VER, 'all' );
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'dcg-flexslider', DFCG_FLEX_URL . '/scripts/jquery.flexslider-min.js', false, DFCG_VER, true );
	
	wp_enqueue_script( 'dcg-flexslider-args', DFCG_FLEX_URL . '/scripts/jquery.flexslider-args.js', false, DFCG_VER, true );

}

/**
 * Load jQuery Flexslider dynamic CSS
 * 
 * Hooked to 'wp_head'
 *
 * @since 4.0
 *
 * @global $dfcg_options array DCG options from database
 */
function dfcg_load_user_flexslider() {

	global $dfcg_options;
	
    // Add user-defined CSS files
	printf( "\n" . '<!-- Dynamic Content Gallery plugin version %s www.studiograsshopper.ch  Begin dynamic CSS -->', DFCG_VER );
	
	// Add user-defined CSS set in Settings page
	include_once( DFCG_FLEX_DIR .'/dcg-flexslider-styles.php'  );
	
	echo '<!-- End of Dynamic Content Gallery dynamic CSS -->' . "\n\n";
}


/**
 * Load jQuery Flexslider js function call (with dynamic params)
 *
 * Hooked to 'wp_footer' with very low priority to make sure it loads after the main js file
 *
 * @since 4.0
 *
 * @global $dfcg_options array DCG options from database
 */
function dfcg_load_user_js_flexslider() {
	
	global $dfcg_options;
	
	printf( "\n" . '<!-- Dynamic Content Gallery plugin version %s www.studiograsshopper.ch  Add jQuery Flexslider scripts -->' . "\n", DFCG_VER );
		
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