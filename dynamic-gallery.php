<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	Dynamic Content Gallery
*	@version	3.0
*
*	This is the file that displays the gallery.
*	3 methods of populating the gallery as per Settings:
*		- Multi Option
*		- One Category
*		- Pages
*	
*/


/*	Populate method = MULTI-OPTION */
if($dfcg_options['populate-method'] == 'multi-option' && function_exists('dfcg_multioption_method_gallery') ) {
	// Output the gallery
	dfcg_multioption_method_gallery();

/*	Populate method = PAGES */
} elseif($dfcg_options['populate-method'] == 'pages' && function_exists('dfcg_pages_method_gallery') ) {
	// Output the gallery
	dfcg_pages_method_gallery();

/*	Populate method = ONE CATEGORY */
} elseif($dfcg_options['populate-method'] == 'one-category' && function_exists('dfcg_onecategory_method_gallery') ) {
	// Output the gallery
	dfcg_onecategory_method_gallery();

/*	Something has gone horribly wrong! */
} else {
	$output = $dfcg_errmsgs['public'];
	$output .= $dfcg_errmsgs['10'] . "\n";
	echo $output;
}
?>