<?php
/**
 * Front-end - This is the main file used to display the gallery
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2011
 * @package dynamic_content_gallery
 * @version 4.0
 *
 *
 * This file is included by dynamic_content_gallery() in dfcg-gallery-core.php
 * therefore local scope applies to variables here - unless global in dynamic_content_gallery()
 *
 * MOOTOOLS Markup
 * ---------------
 * <div id="dfcg-outer-wrap"><!-- Start of Dynamic Content Gallery -->
 *
 *	dfcg_before() hook
 *
 * 	<div id="myGallery"><!-- Start of DCG Mootools output -->
 *
 *		<div class="imageElement"><!-- DCG Image #' . $counter . ' -->
 *			<h3> Title </h3>
 *			<p> Slide Pane Text </p>
 *			<a href="Post or External link" title="Link Title Attribute" class="open"></a>
 *			<img width="" height="" src="Main image" class"full ..." alt="" title"" />
 *			<img width="" height="" src="Main image" class"full ..." alt="" title"" />
 *		</div>
 *
 *		<div class="imageElement"><!-- DCG Image #' . $counter . ' -->
 *			Next item markup, etc
 *		</div>
 *
 * 	</div><!-- end #myGallery and end of DCG Mootools output -->
 *
 *	dfcg_after() hook
 *
 * </div><!-- end #dfcg-outer-wrap and end of Dynamic Content Gallery output -->
 *
 * @since 3.0
 * @updated 4.0
 */


/**
 * Prevent direct access to this file
 */
if( !defined( 'ABSPATH' ) ) {
	exit( __( 'Sorry, you are not allowed to access this file directly.' ) );
}


/**
 * Generate DCG output based on which scripts are being loaded
 */
if( $dfcg_options['scripts'] == 'mootools' ) {

	if( $dfcg_options['populate-method'] == 'multi-option' ) {
		// Populate method = MULTI-OPTION
		$dfcg_output = dfcg_multioption_method_gallery();
	
	} elseif( $dfcg_options['populate-method'] == 'one-category' || $dfcg_options['populate-method'] == 'custom-post' ) {
		// Populate method = ONE CATEGORY or CUSTOM POST TYPE
		$dfcg_output = dfcg_onecategory_method_gallery();
	
	} elseif( $dfcg_options['populate-method'] == 'id-method' ) {
		// Populate method = ID METHOD
		$dfcg_output = dfcg_id_method_gallery();
	}


} elseif( $dfcg_options['scripts'] == 'jquery' ) {
	
	if( $dfcg_options['populate-method'] == 'multi-option' ) {
		// Populate method = MULTI-OPTION
		$dfcg_output = dfcg_jq_multioption_method_gallery();
	
	} elseif( $dfcg_options['populate-method'] == 'one-category' || $dfcg_options['populate-method'] == 'custom-post' ) {
		// Populate method = ONE CATEGORY or CUSTOM POST TYPE
		$dfcg_output = dfcg_jq_onecategory_method_gallery();

	} elseif( $dfcg_options['populate-method'] == 'id-method' ) {
		// Populate method = PAGES
		$dfcg_output = dfcg_jq_id_method_gallery();
	}

} else {
	
	// Something has gone horribly wrong and there's no output!
	$dfcg_output = '';
	$dfcg_output .= "\n" . $dfcg_errmsgs['29'] . "\n";
}

/* Output the gallery and markup */

// Open wrapper div
echo "\n" . '<div id="dfcg-outer-wrap"><!-- Start of Dynamic Content Gallery output -->';

// Run hook
do_action( 'dfcg_before' );

// Gallery output
echo $dfcg_output;

// Run hook
do_action( 'dfcg_after' );

// Close wrapper div
echo "\n\n" . '</div><!-- end #dfcg-outer-wrap and Dynamic Content Gallery output -->' . "\n";

?>