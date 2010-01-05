<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.2
*
*	This is the file that displays the gallery, called by dynamic_content_gallery()
*	template tag function.
*
*	Note: the name of this file is preserved because some users will still be using the
*	old method of calling the plugin (now replaced with template tag)
*
*	3 methods of populating the gallery as per Settings:
*		- Multi Option
*		- One Category
*		- Pages
*
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit(__( "Sorry, you are not allowed to access this file directly.", DFCG_DOMAIN ));
}


/*	Determine which scripts are being loaded */
if( $dfcg_options['scripts'] == 'mootools' ) {

	/*	Populate method = MULTI-OPTION */
	if($dfcg_options['populate-method'] == 'multi-option' && function_exists('dfcg_multioption_method_gallery') ) {
		// Output the gallery
		dfcg_multioption_method_gallery();

	/*	Populate method = ONE CATEGORY */
	} elseif($dfcg_options['populate-method'] == 'one-category' && function_exists('dfcg_onecategory_method_gallery') ) {
		// Output the gallery
		dfcg_onecategory_method_gallery();

		/*	Populate method = PAGES */
	} elseif($dfcg_options['populate-method'] == 'pages' && function_exists('dfcg_pages_method_gallery') ) {
		// Output the gallery
		dfcg_pages_method_gallery();
	}

} elseif( $dfcg_options['scripts'] == 'jquery' ) {

	/*	Populate method = MULTI-OPTION */
	if($dfcg_options['populate-method'] == 'multi-option' && function_exists('dfcg_jq_multioption_method_gallery') ) {
		// Output the gallery
		dfcg_jq_multioption_method_gallery();

	/*	Populate method = ONE CATEGORY */
	} elseif($dfcg_options['populate-method'] == 'one-category' && function_exists('dfcg_jq_onecategory_method_gallery') ) {
		// Output the gallery
		dfcg_jq_onecategory_method_gallery();

		/*	Populate method = PAGES */
	} elseif($dfcg_options['populate-method'] == 'pages' && function_exists('dfcg_jq_pages_method_gallery') ) {
		// Output the gallery
		dfcg_jq_pages_method_gallery();
	}

/*	Something has gone horribly wrong and there's no output! */
} else {

	$output = '';
	$output .= $dfcg_errmsgs['public'];
	$output .= "\n" . $dfcg_errmsgs['10'] . "\n";
	echo $output;
}
?>