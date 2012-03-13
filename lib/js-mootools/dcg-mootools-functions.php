<?php
/**
 * Front-end - Mootools module
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
 * Enqueue mootools smoothgallery CSS and JS files
 *
 * Hooked to 'wp_enqueue_scripts' in dfcg_enqueue_scripts_css()
 *
 * @since 4.0
 */
function dfcg_load_mootools() {

	global $dfcg_options;
	
	wp_enqueue_style( 'dcg-mootools', DFCG_MOOTOOLS_URL . '/css/jd.gallery.css', false, DFCG_VER, 'all' );
	
	if( $dfcg_options['mootools'] !== '1' ) {
		wp_enqueue_script( 'dcg-mootools-core', DFCG_MOOTOOLS_URL . '/scripts/mootools-1.2.4-core-jm.js', false, DFCG_VER );
		wp_enqueue_script( 'dcg-mootools-more', DFCG_MOOTOOLS_URL . '/scripts/mootools-1.2.4.4-more.js', false, DFCG_VER );
	}
	wp_enqueue_script( 'dcg-mootools-js', DFCG_MOOTOOLS_URL . '/scripts/jd.gallery_1_2_4_4.js', false, DFCG_VER );
	wp_enqueue_script( 'dcg-mootools-trans', DFCG_MOOTOOLS_URL . '/scripts/jd.gallery.transitions_1_2_4_4.js', false, DFCG_VER );
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
function dfcg_load_user_mootools() {

	global $dfcg_options;
	
	printf( "\n" . '<!-- Dynamic Content Gallery plugin version %s www.studiograsshopper.ch  Begin scripts and dynamic CSS -->', DFCG_VER );
	
	// Add user defined CSS
	include_once( DFCG_MOOTOOLS_DIR . '/dcg-gallery-mootools-styles.php' );
	
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