<?php
/**
 * Front-end - Error Messages
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2011
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * @info Error/Info messages generated in the event that Settings are not correct.
 * @info Messages are printed to the browser and/or Page Source.
 * @info This should help users get the gallery working.
 * @info Note: Admin notices error messages are handled in dfcg-admin-ui-validation.php
 *
 * @since 3.0
 */

/* Prevent direct access to this file */
if( !defined( 'ABSPATH' ) ) {
	exit( __( 'Sorry, you are not allowed to access this file directly.' ) );
}



/**
 * Function to control Error Reporting
 *
 * @uses	dfcg_errors()
 *
 * @global array $dfcg_options Array of plugin options from db
 * @return array $errmsgs Array of error messages, if Errors have been turned on in settings
 * @since 3.2
 */
function dfcg_errors_output() {
	
	global $dfcg_options;
	
	if( $dfcg_options['errors'] == "true" ) {
		
		// Create array of error messages
		$errmsgs = dfcg_errors();
		
		return $errmsgs;
	}
}


/**
 * Function which manages Error Message content and builds the array of messages
 *
 * Error 1x = Validation of source data input
 * 10
 * 11
 * 12	Multi	Only 1 posr select defined
 *
 * Error 2x = Validation of data output
 * 20
 * 21
 * 22
 * 23
 * 24
 * 25
 * 26
 * 29
 *
 * Error 3x = Image messages
 * Error 4x = Desc messages
 *
 * @return $dfcg_errmsgs	Array of all Error Messages
 * @since 3.2.2
 * @updated 4.0
 */
function dfcg_errors() {

	global $dfcg_options;
	
	$o = "\n" . '<!-- ';
	$c = ' -->';

	/* Public error messages - these are displayed in the browser */
	$front = __('<p>Dynamic Content Gallery Error: View page source for details.</p>', DFCG_DOMAIN);

	/* Page Source error messages - standard texts */
	$critical = $o . __('Rating: Critical. Fix error in order to display gallery.', DFCG_DOMAIN) . $c;
	$noncritical = $o . __('Rating: Non-critical. This error does not prevent the gallery from working properly.', DFCG_DOMAIN) . $c;

	$errmsgs = array();
	
	
	

	/***** Data source messages *****/
	
	#	Message 10			Insufficient Post Selects have been defined in Settings
	#	Type:				Error
	#	Populate-method: 	Multi Option
	#	Query:				Before query
	#	Trigger:			$selected_slots < 2 returns TRUE.
	#	Rating:				Critical
	#	Action:				Return, exit script
	$err = "\n" . $front . "\n";
	$err .= $o . __('DCG Error Message 10: You are using the Multi Option gallery method but you have defined less than 2 Post Selects in the DCG Settings page.', DFCG_DOMAIN) . $c;
	$err .= $critical;
	$err .= $o . __('Fix: Go to the DCG Settings > Gallery Method tab and configure a minimum of 2 Image Slots.', DFCG_DOMAIN) .' -->';
	
	$errmsgs['10'] = $err;
	
	
	#	Message 11			$query_pair isn't an array, or it's empty
	#	Type:				Error
	#	Populate-method:	Multi Option
	#	Query:				Before query
	#	Trigger:			dfcg_query_list() doesn't return an array, or it's empty
	#	Rating:				Critical
	#	Action:				Return, exit script
	$err = "\n" . $front . "\n";
	$err .= $o . __('DCG Error Message 11: Query list is not an array.', DFCG_DOMAIN) . $c;
	$err .= $critical;
	$err .= $o . __('Fix: An unexplained error has occurred. Try reinstalling the plugin.', DFCG_DOMAIN) .$c;
	
	$errmsgs['11'] = $err;
	
	
	#	Message 12			WP_Query couldn't find FIRST Post
	#	Type:				Error
	#	Populate-method: 	Multi Option
	#	Query:				After query
	#	Trigger:			if( !$recent->have_posts() && $counter < 2) returns TRUE
	#	Rating:				Critical
	#	Action:				Return, exit function.
	$err = "\n" . $front . "\n";
	$err .= $o . __('DCG Error Message 12: The Post for Image Slot 1 could not be found.', DFCG_DOMAIN) . $c;
	$err .= $critical;
	$err .= $o . __('Fix: Go to the DCG Settings > Gallery Method tab and check the configuration of the first Image Slot.', DFCG_DOMAIN) . $c;
	
	$errmsgs['12'] = $err;
	
	
	#	Message 13			No results returned by WP_Query. Theoretically, this situation should never happen.
	#	Type:				Error
	#	Populate-method: 	One Category, Custom Post Type
	#	Query:				After query
	#	Trigger:			WP_Query returned no results.
	#	Rating:				Critical
	#	Action:				Return, exit script
	$err = "\n" . $front . "\n";
	$err .= $o . __('DCG Error Message 13: You are using the One Category or Custom Post Type gallery method but the plugin failed to find any Posts.', DFCG_DOMAIN) . $c;
	$err .= $critical;
	$err .= $o . __('Fix: Check your One Category or Custom Post Type gallery method settings.', DFCG_DOMAIN) . $c;

	$errmsgs['13'] = $err;
	
	/*************************************************************************************/
	
	#	Message 14			Only one ID has been specified in Settings.
	#	Type:				Error
	#	Populate-method: 	ID Method
	#	Query:				Before query
	#	Trigger:			$ids_selected_count < 2 returns TRUE.
	#	Rating:				Critical
	#	Action:				Return, exit script.
	$err = "\n" . $front;
	$err .= $c . __('DCG Error Message 14: You are using the ID Method for populating the gallery, but you have only specified one ID in the DCG Settings page.', DFCG_DOMAIN) . $c;
	$err .= $critical;
	$err .= $o . __('Fix: Enter a minimum of 2 valid IDs in the DCG Settings > Gallery Method > ID Method options for the gallery to work.', DFCG_DOMAIN) . $c;
	
	$errmsgs['14'] = $err;


	#	Message 15			No IDs have been specified in Settings.
	#	Type:				Error
	#	Populate-method: 	ID Method
	#	Query:				Before query
	#	Trigger:			$ids_selected is empty
	#	Rating:				Critical
	#	Action:				Print public error message, return, exit script.
	$err = "\n" . $front;
	$err .= $o . __('DCG Error Message 15: You are using the ID Method for populating the gallery, but you have not specified any IDs in the DCG Settings page.', DFCG_DOMAIN) . $c;
	$err .= $critical;
	$err .= $o . __('Fix: Enter a minimum of 2 valid IDs in the DCG Settings > Gallery Method > ID Method options for the gallery to work.', DFCG_DOMAIN) . $c;

	$errmsgs['15'] = $err;
	
	
	#	Message 16			Only 1 selected ID is valid, as per SQL query results
	#	Type:				Error
	#	Populate-method: 	ID Method
	#	Query:				After query
	#	Trigger:			$ids_found_count < 2 returns TRUE
	#	Rating:				Critical
	#	Action:				Return, exit script.
	$err = "\n" . $front . "\n";
	$err .= "\n" . '<!-- ' . __('DCG Error Message 16: Only one of the IDs specified in the DCG Settings page is a valid ID in the database.', DFCG_DOMAIN) .' -->';
	$err .= "\n" . $critical;
	$err .= "\n" . '<!-- ' . __('Fix: Ensure that there are a minimum of 2 valid IDs specified in the DCG Settings page.', DFCG_DOMAIN) .' -->';
	
	$errmsgs['16'] = $err;
	
	
	#	Message 17			None of selected IDs are valid, or db query has failed
	#	Type:				Error
	#	Populate-method: 	ID Method
	#	Query:				After query
	#	Trigger:			if( $ids_found ) returns FALSE
	#	Rating:				Critical
	#	Action:				Return, exit script.
	$err = "\n" . $front . "\n";
	$err .= "\n" . '<!-- ' . __('DCG Error Message 17: You are using the ID Method for populating the gallery, but none of the selected Page/Post IDs are valid Pages/Posts, or the database query has failed.', DFCG_DOMAIN) .' -->';
	$err .= "\n" . $critical;
	$err .= "\n" . '<!-- ' . __('Fix: Check the validity of the Page/Post IDs entered in the DCG Settings > Gallery Method > ID Method options. At least 2 IDs must be valid.', DFCG_DOMAIN) .' -->';
	$err .= "\n" . '<!-- ' . __('Fix: If at least 2 of the selected IDs are valid, check server error logs.', DFCG_DOMAIN) .' -->';
	
	$errmsgs['17'] = $err;
	
	
	
	
	/*************************************************************************************/
	
	
	
	/***** Data output *****/
	
	#	Message 20			The number of Posts/Pages selected does not equal the number found
	#	Type:				Info
	#	Populate-method: 	All
	#	Query:				After query
	#	Trigger:			Multi Option:	if( $counter - $counter1 - $counter2 !== 0 ) returns TRUE
	#						ID Method:		$ids_selected_count !== $ids_found_count
	#	Rating:				Non Critical
	#	Action:				Print error message in Page Source only.
	if( $dfcg_options['populate-method'] == 'multi-option' ) {
	
		$err = $o . __('DCG Info Message 20: You are using the Multi Option Method for populating the gallery, but not all of the selected Posts exist.', DFCG_DOMAIN) . $c;
		$err .= $noncritical;
		$err .= $o . __('This could be caused by, for example, defining a Post Select of 4 but only 3 Posts exist in that Category.', DFCG_DOMAIN) . $c;
		$err .= $o . __('Look at the XHTML comments to see which Image # is missing.', DFCG_DOMAIN) .$c; 
		$err .= $o . __('Fix: Check the Post Select for this missing Image # in the DCG Settings > Gallery Method tab.', DFCG_DOMAIN) .$c;
	
	} elseif( $dfcg_options['populate-method'] == 'id-method' ) {
	
		$err = $o . __('DCG Info Message 20: You are using the ID Method for populating the gallery, but not all of the selected IDs exist.', DFCG_DOMAIN) .$c;
		$err .= $noncritical;
		$err .= $o . __('Fix: Check the IDs entered in the DCG Settings > Gallery Method tab.', DFCG_DOMAIN) . $c;
	} else {
	
		$err = $o . __('DCG Info Message 20: You have less Posts in the selected Category/Term than the number specified in the DCG Settings > Gallery Method tab.', DFCG_DOMAIN) . $c;
		$err .= $noncritical;
		$err .= $o . __('Fix: Reduce the "Number of Posts to display" in the DCG Settings > Gallery Method tab to match the Number of Posts found.', DFCG_DOMAIN) . $c;
	}
	
	$errmsgs['20'] = $err;
	
	

	

	
	

	/**	Error Message 29	dynamic-gallery.php produces no output at all - Doomsday Error!
	*	Populate-method: 	All
	*	Trigger:			dynamic-gallery.php produces no output at all, eg there is a missing included file.	
	*	Rating:				Critical
	*	Action:				Print public error message, return, exit script.
	*	Fix:				Check that plugin has been installed properly.
	*	Notes:				This shouldn't happen with a correct plugin install
	*						Updated 3.3
	*
	*/
	$err_29 = "\n" . $front . "\n";
	$err_29 .= "\n" . '<!-- ' . __('DCG Error Message 29: The plugin is unable to generate any output.', DFCG_DOMAIN) .' -->';
	$err_29 .= "\n" . $critical;
	$err_29 .= "\n" . '<!-- ' . __('Fix: Check that the plugin has been installed properly and that all files contained within the download ZIP file have been uploaded to your server.', DFCG_DOMAIN) .' -->';

	$errmsgs['29'] = $err_29;

	



	/***** IMAGE MESSAGES *****/	
	

	#	Message 30			Featured Image found
	#	Image Man:			Auto
	#	Override:			Not set
	$err = $o . __('DCG Message 30', DFCG_DOMAIN) . $c;
	$err .= $o . __('Image Management = Auto', DFCG_DOMAIN) . $c;
	$err .= $o . __('Featured Image is displayed', DFCG_DOMAIN) . $c;
	$errmsgs['30'] = $err;
	
	
	#	Message 31			DCG metabox image URL overrides Featured Image
	#	Image Man:			Auto
	#	Override:			Yes
	#	Note:				Only means that DCG Metabox URL was found - doesn't mean that Featured Image is actually set
	#	Note:				Doesn't prevent 404 due to DCG Metabox URL being incorrect
	$err = $o . __('DCG Message 31', DFCG_DOMAIN) . $c;
	$err .= $o . __('Image Management = Auto', DFCG_DOMAIN) . $c;
	$err .= $o . __('Featured Image is overridden by DCG Metabox image URL.', DFCG_DOMAIN) . $c;
	$err .= $o . __('If image is not visible, check that DCG Metabox image URL is correct.', DFCG_DOMAIN) . $c;
	$errmsgs['31'] = $err;
	
	
	#	Message 32			Not used
	#	Image Man:			None
	#	Override:			N/A
	#	Note:				Not used 
	
	
	#	Message 33			Not used
	#	Image Man:			None
	#	Override:			N/A
	#	Note:				Not used	
	
	
	#	Message 34			Featured image not set
	#	Image Man:			Auto
	#	Override:			Not set
	#	Note:				Either default or errorimg will be displayed instead
	$err = "\n" . '<!-- ' . __('DCG Message 34', DFCG_DOMAIN) .' -->';
	$err .= "\n" . '<!-- ' . __('Image Management = Auto', DFCG_DOMAIN) .' -->';
	$err .= "\n" . '<!-- ' . __('Error: Featured Image has not been set.', DFCG_DOMAIN) .' -->';
	$errmsgs['34'] = $err;
	
	
	#	Message 34.1		Featured image not set, Default image exists
	#	Image Man:			Auto
	#	Override:			Not set
	#	Note:				See message 34
	$err = $errmsgs['34'];
	$err .= $o . __('DCG Message 34.1: Default image is displayed instead.', DFCG_DOMAIN) . $c;
	$errmsgs['34.1'] = $err;
	
	
	#	Message 34.2		Featured image not set, Default image doesn't exist, Error image displayed
	#	Image Man:			Auto
	#	Override:			Not set
	#	Note:				See message 34
	$err = $errmsgs['34'];
	$err .= $o . __('DCG Message 34.2: Default image does not exist.', DFCG_DOMAIN) . $c;
	$err .= $o . __('Error image is displayed instead.', DFCG_DOMAIN) . $c;
	$errmsgs['34.2'] = $err;
	
	
	#	Message 35			DCG Metabox image is displayed
	#	Image Man:			Full / Partial
	#	Note:				Doesn't prevent 404 due to DCG Metabox URL being incorrect	
	$err = $o . __('DCG Message 35', DFCG_DOMAIN) . $c;
	$err .= $o . __('Image Management = Full or Partial', DFCG_DOMAIN) . $c;
	$err .= $o . __('DCG Metabox image URL is displayed.', DFCG_DOMAIN) . $c;
	$errmsgs['35'] = $err;
	
	
	#	Message 36			DCG Metabox image doesn't exist
	#	Image Man:			Full / Partial
	#	Note:				Doesn't prevent 404 due to DCG Metabox URL being incorrect	
	$err = "\n" . '<!-- ' . __('DCG Message 36', DFCG_DOMAIN) .' -->';
	$err .= "\n" . '<!-- ' . __('Image Management = Full or Partial', DFCG_DOMAIN) .' -->';
	$err .= "\n" . '<!-- ' . __('Error: DCG Metabox image URL not found.', DFCG_DOMAIN) .' -->';
	$errmsgs['36'] = $err;
	
	
	#	Message 36.1		DCG Metabox image URL empty, therefore Default image is displayed
	#	Image Man:			Full / Partial
	#	Note:				See message 36
	$err = $errmsgs['36'];
	$err .= $o . __('DCG Message 36.1: Default image is displayed instead.', DFCG_DOMAIN) . $c;
	$errmsgs['36.1'] = $err;
	
	
	#	Message 36.2		DCG Metabox image URL empty, Default image doesn't exist, ErrorImg displayed instead
	#	Image Man:			Full / Partial
	#	Note:				See message 36 and 36.1
	$err = $errmsgs['36'];
	$err .= $o . __('DCG Message 36.2: Default image does not exist.', DFCG_DOMAIN) . $c;
	$err .= $o . __('Error image is displayed instead.', DFCG_DOMAIN) . $c;
	$errmsgs['36.2'] = $err;

	
	$errmsgs['37'] = '';
	
	
	
	/***** DESC ERRORS *****/
	
	/**	Error Message 40	Missing Description
	*	Populate-method: 	ID Method, One Category, Multi Option
	*	Trigger:			get_post_meta($dfcg_get_page->ID, "dfcg-desc", true) returns FALSE and
	*						$dfcg_options['defimagedesc'] !== '' returns FALSE
	*	Rating:				Non-critical
	*	Reason:				Missing Description. The Post/Page does not have a custom field _dfcg-desc defined and neither is a
	*						Default Description defined in DCG Settings.
	*	Fix:				Either create a custom field _dfcg-desc for the relevant Page/Post, or define a Default Description in the DCG Settings page.
	*						Either of these fixes will clear both errmsg_public and this error message.
	*	Notes:				Informational only, this error does not prevent the gallery running.
	*						Updated 3.3
	*/
	$err_40 = "\n" . '<!-- ' . __('DCG Error Message 40: DCG Metabox Slide Pane Description is empty and Default Description does not exist.', DFCG_DOMAIN) .' -->';
	$err_40 .= "\n" . $noncritical;
	$err_40 .= "\n" . '<!--	' . __('Fix: Enter a description in the DCG Metabox Slide Pane Description field for this Page/Post and/or define a Default Description in the DCG Settings page.', DFCG_DOMAIN) .' -->';
	
	$errmsgs['40'] = $err_40;
	
	
	// Return array of Error Messages
	return $errmsgs;
}