<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	Dynamic Content Gallery
*	@version	3.0
*
*	Key variables required for the plugin to run.
*	These are used by the functions which output the markup
*	and gallery content.
*	
*/


/* First let's load Options otherwise nothing will run */
$dfcg_options = get_option('dfcg_plugin_settings');


/* Error image */
$dfcg_errorimgurl = DFCG_URL . '/error-img/error.jpg';


/* Variables that are needed for all populate-method settings */

// Do we have a base URL for Custom field images? Set base URL variable
if ( $dfcg_options['image-url-type'] == "full" ) {
	// There is no base URL, so make it empty
	$dfcg_baseimgurl = '';
} else {
	// Partial or No URL, therefore there is a base URL, so get it
	$dfcg_baseimgurl = $dfcg_options['imageurl'];
}

?>