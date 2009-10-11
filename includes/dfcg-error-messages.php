<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	Dynamic Content Gallery
*	@version	3.0 RC3
*
*	Error messages generated in the event that Settings are not correct.
*	Messages are printed to the browser and/or Page Source.
*	This should help users get the gallery working.
*	Note: Admin related error messages are handled in dfcg-wp-ui.php and dfcg-wpmu-ui.php
*
*	@since	3.0
*/


/* Public error messages - these are displayed in the browser */
$dfcg_errmsg_public = "Dynamic Content Gallery Error: View page source for details.";


/* Page Source error messages - these are shown as HTML comments in the Page Source */

/**	Error Message 1		Only one Page ID has been specified in Settings.
*	Populate-method: 	Pages
*	Trigger:			$dfcg_pages_selected_count < 2 returns TRUE.
*	Rating:				Critical
*	Reason:				Only one Page ID has been defined in Settings.
*	Action:				Print public error message, return, exit script.
*	Fix:				Enter a minimum of 2 Page IDs in the DCG Settings page
*	Notes:				See $dfcg_errmsg_2 for when no Page ID have been specified in DCG Settings
*						See $dfcg_errmsg_9 for when SQL query only finds one valid Page ID
*/
$dfcg_errmsg_1 .= "\n" . $dfcg_errmsg_public . "\n";
$dfcg_errmsg_1 .= '<!-- DCG Error Message 1: You have only specified one Page ID in the DCG Settings page. -->' . "\n";
$dfcg_errmsg_1 .= '<!--	Rating: Critical. Fix error in order to display gallery. -->' . "\n";
$dfcg_errmsg_1 .= '<!--	Fix: Enter a minimum of 2 valid Page IDs in the DCG Settings page for the gallery to work. -->';


/**	Error Message 2		No Page Ids have been specified in Settings.
*	Populate-method: 	Pages
*	Trigger:			!empty($dfcg_pages) returns FALSE.
*	Rating:				Critical
*	Reason:				No Page IDs have been specified in Settings.
*	Action:				Print public error message, return, exit script.
*	Fix:				Enter a minimum of 2 Page IDs in the DCG Settings page
*	Notes:				Fix is same as $dfcg_errmsg_1 but Reason is different.
*/
$dfcg_errmsg_2 .= "\n" . $dfcg_errmsg_public . "\n";
$dfcg_errmsg_2 .= '<!-- DCG Error Message 2: You have not specified any Page IDs in the DCG Settings page. -->' . "\n";
$dfcg_errmsg_2 .= '<!-- Rating: Critical. Fix error in order to display gallery. -->' . "\n";
$dfcg_errmsg_2 .= '<!-- Fix: Enter a minimum of 2 valid Page IDs in the DCG Settings page for the gallery to work. -->';


/**	Error Message 3		Missing Description
*	Populate-method: 	Pages, One Category, Multi Option
*	Trigger:			get_post_meta($dfcg_get_page->ID, "dfcg-desc", true) returns FALSE and
*						$dfcg_options['defimagedesc'] !== '' returns FALSE
*	Rating:				Non-critical
*	Reason:				Missing Description. The Post/Page does not have a custom field dfcg-desc defined and neither is a
*						Default Description defined in DCG Settings.
*	Action:				$dfcg_errmsg_3 displayed as HTML comment underneath <p> tags in Source markup.
*	Fix:				Either create a custom field dfcg-desc for the relevant Page/Post, or define a Default Description in the DCG Settings page.
*						Either of these fixes will clear both errmsg_public and this error message.
*	Notes:				Informational only, this error does not prevent the gallery running.
*/
$dfcg_errmsg_3 = '<!-- DCG Error Message 3: Custom Field dfcg-desc does nor exist and Default Description does not exist.
			Fix: Create a dfcg-desc Custom Field for this Page/Post and/or define a Default Description in the DCG Settings page. -->';


/*	Error Message 4		Missing Images
*	Populate-method: 	Pages, One Category, Multi Option
*	Trigger:			Pages:			get_post_meta($dfcg_get_page->ID, "dfcg-image", true) returns FALSE and
*										!empty($dfcg_options['defimgpages']) returns FALSE
*						One Category:	get_post_meta($dfcg_get_page->ID, "dfcg-image", true) returns FALSE and
*										file_exists($filename) returns FALSE, therefore a Category Default image doesn't exist.
*						Multi Option:	get_post_meta($dfcg_get_page->ID, "dfcg-image", true) returns FALSE and
*										file_exists($filename) returns FALSE, therefore a Category Default image doesn't exist.										
*	Rating:				Non-critical
*	Reason:				Missing image. The Post/Page does not have a custom field dfcg-image defined and a Default Image has not been defined.
*	Action:				$dfcg_errmsg_4 displayed as HTML comment underneath <img> tags in Source markup.
*	Fix:				Either create a custom field dfcg-image for the relevant Page/Post, or define a Default Description in the DCG Settings page.
*	Notes:				Informational only, this error does not prevent the gallery running.
*						This errmsg is NOT triggered if the information in dfcg-image and/or a default image is incorrect, ie the URL or path is wrong
*						Error image should also be displayed in gallery.
*/
$dfcg_errmsg_4 = "\t" . '<!-- DCG Error Message 4: Custom Field dfcg-image does not exist and Default Image does not exist.
				Fix: Create a dfcg-image Custom Field for this Page/Post and/or define a Default Image in the DCG Settings page. -->' . "\n";


/*	Error Message 5		Number of Page IDs found in db is not equal to the number of Page IDs selected in Settings
*	Populate-method: 	Pages
*	Trigger:			$dfcg_pages_selected_count !== $dfcg_pages_found_count
*	Rating:				Non-critical
*	Reason:				The number of Page IDs found in db is not equal to the number of Page IDs selected in Settings.
*	Action:				Error message displayed in Page Source only.
*	Fix:				Check the Page IDs entered in the DCG Settings page
*	Notes:				Informational only, this error does not prevent the gallery running.
*						
*/
$dfcg_errmsg_5 .= '<!-- DCG Error Message 5: Not all of the selected Page IDs are valid Pages. -->' . "\n";
$dfcg_errmsg_5 .= '<!-- Rating: Non-critical. This error does not prevent the gallery from working properly. -->' . "\n";
$dfcg_errmsg_5 .= '<!-- Fix: Check the Page IDs entered in the DCG Settings page. -->';


/**	Error Message 6		No valid Page ID's selected, or db query has failed
*	Populate-method: 	Pages
*	Trigger:			if( $dfcg_pages_found ) returns FALSE
*	Rating:				Critical
*	Action:				Print public error message, return, exit script.
*	Notes:
*/
$dfcg_errmsg_6 .= "\n" . $dfcg_errmsg_public . "\n";
$dfcg_errmsg_6 .= '<!-- DCG Error Message 6: None of the selected Page IDs are valid Pages or the database query has failed. -->' . "\n";
$dfcg_errmsg_6 .= '<!-- Rating: Critical. Fix error in order to display gallery. -->' . "\n";
$dfcg_errmsg_6 .= '<!-- Fix: Check the validity of the Page IDs entered in the DCG Settings page. At least 2 Page IDs must be valid.' . "\n";
$dfcg_errmsg_6 .= '<!-- Fix: If at least 2 of the selected Page IDs are valid, check server error logs. -->';


/*	Error Message 7
*	Populate-method: 	One Category
*	Trigger:			$counter - $dfcg_posts_number !== 0 returns FALSE
*	Rating:				Non-critical
*	Reason:				The number of Posts to display in Settings is not equal to the number of Posts found in WP_Query.
*						This means that there are less Posts in the selected Category than have been selected in Settings.
*	Action:				Error message is displayed in View Source.
*	Fix:				Reduce the "Number of Posts to display" in the DCG Settings page to match the Number of Posts found.
*	Notes:				Informational only, this error does not prevent the gallery running.
*						
*/
$dfcg_errmsg_7 .= '<!-- DCG Error Message 7: You have less Posts in the selected Category than the number specified in the Settings Page. -->' . "\n";
$dfcg_errmsg_7 .= '<!-- Rating: Non-critical. This error does not prevent the gallery from working properly. -->' . "\n";
$dfcg_errmsg_7 .= '<!-- Fix: Reduce the "Number of Posts to display" in the DCG Settings page to match the Number of Posts found. -->';


/*	Error Message 8
*	Populate-method: 	One Category
*	Trigger:			WP_Query returned no results.
*	Rating:				Critical
*	Reason:				No results returned by WP_Query. Theoretically, thanks to use of
						wp_dropdown_categories and dropdown select for number of Posts,
						this situation should never happen.
*	Action:				Print public error message, return, exit script.
*	Fix:				Reactivate plugin and try again.
						Check that WP is working properly.
*	Notes:				This error message should never occur on a properly installed
*						and working WP install.
*/
$dfcg_errmsg_8 .= "\n" . $dfcg_errmsg_public . "\n";
$dfcg_errmsg_8 .= '<!-- DCG Error Message 8: The wp_query failed to find any Posts. -->' . "\n";
$dfcg_errmsg_8 .= '<!-- Rating: Critical. Fix error in order to display gallery. -->' . "\n";
$dfcg_errmsg_8 .= '<!-- Fix: Deactivate and reactivate the plugin and try again. -->';


/**	Error Message 9		Only 1 Page ID selected in Settings is valid, as per SQL query results
*	Populate-method: 	Pages
*	Trigger:			$dfcg_pages_found_count < 2 returns TRUE
*	Rating:				Critical
*	Action:				Print public error message, return, exit script.
*	Fix:				Ensure that there are a minimum of 2 valid Page IDs specified in the DCG Settings page.
*	Notes:				This is similar to Error Message 1, but is triggered by a check on the SQL results,
*						not on the number of selected Pages.
*/
$dfcg_errmsg_9 .= "\n" . $dfcg_errmsg_public . "\n";
$dfcg_errmsg_9 .= '<!-- DCG Error Message 9: Only one of the Page IDs specified in the DCG Settings page is a valid Page ID in the database. -->' . "\n";
$dfcg_errmsg_9 .= '<!-- Rating: Critical. Fix error in order to display gallery. -->' . "\n";
$dfcg_errmsg_9 .= '<!-- Fix: Ensure that there are a minimum of 2 valid Page IDs specified in the DCG Settings page. -->';


/**	Error Message 10	dynamic-gallery.php produces no output at all
*	Populate-method: 	All
*	Trigger:			dynamic-gallery.php produces no output at all, eg there is a missing included file.
*	Rating:				Critical
*	Action:				Print public error message, return, exit script.
*	Fix:				Check that plugin has been installed properly.
*	Notes:				This shouldn't happen with a correct plugin install 
*
*/
$dfcg_errmsg_10 .= '<!-- DCG Error Message 10: The plugin is unable to generate any output. -->' . "\n";
$dfcg_errmsg_10 .= '<!-- Rating: Critical. Fix error in order to display gallery. -->' . "\n";
$dfcg_errmsg_10 .= '<!-- Fix: Check that the plugin has been installed properly and that all files -->' . "\n";
$dfcg_errmsg_10 .= '<!-- contained within the download ZIP file have been uploaded to your server. -->';


/**	Error Message 11	Insufficient Post Selects have been defined in Settings
*	Populate-method: 	Multi Option
*	Trigger:			$dfcg_selected_slots < 2 returns TRUE.
*	Rating:				Critical
*	Reason:				Either 1 or 0 Post Selects have been defined in Settings
*	Action:				Print public error message, return, exit script.
*	Fix:				Enter a minimum of 2 Post Selects in the DCG Settings page
*	Notes:				This is a pre-WP_Query validation check, ie checks what is in Settings only
*						
*/
$dfcg_errmsg_11 .= "\n" . $dfcg_errmsg_public . "\n";
$dfcg_errmsg_11 .= '<!-- DCG Error Message 11: You have defined less than 2 Post Selects in the DCG Settings page. -->' . "\n";
$dfcg_errmsg_11 .= '<!-- Rating: Critical. Fix error in order to display gallery. -->' . "\n";
$dfcg_errmsg_11 .= '<!-- Fix: Enter a minimum of 2 valid Post Selects in the DCG Settings page for the gallery to work. -->';


/**	Error Message 12	WP_Query couldn't find a specific Post
*	Populate-method: 	Multi Option
*	Trigger:			if( $counter - $counter1 !== 0 ) returns TRUE
*	Rating:				Non Critical
*	Reason:				The number of Post Selects does not equal the number of posts found
*						by the WP_Query loops.
*	Action:				Print error message in Page Source only.
*	Fix:				Check the Post Select for the Image Slot # in the DCG Settings page.
*	Notes:				This is a post-WP_Query validation check, and simply compares the 2 counters.
*						$counter = number of Post Selects
*						$counter1 = number of times WP_Query is run
*/
$dfcg_errmsg_12 .= '<!-- DCG Error Message 12: The Post for at least one of your chosen Image Slots could not be found. -->' . "\n";
$dfcg_errmsg_12 .= '<!-- Rating: Non-critical. This error does not prevent the gallery from working properly. -->' . "\n";
$dfcg_errmsg_12 .= '<!-- This could be caused by, for example, defining a Post Select of 4 but only 3 Posts exist in that Category. -->' . "\n";
$dfcg_errmsg_12 .= '<!-- Look at the XHTML comments to see which Image # is missing. -->' . "\n"; 
$dfcg_errmsg_12 .= '<!-- Fix: Check the Post Select for this missing Image # in the DCG Settings page. -->';


/**	Error Message 13	WP_Query couldn't find FIRST Post
*	Populate-method: 	Multi Option
*	Trigger:			if( !$recent->have_posts() && $counter < 2) returns TRUE
*						This means that the first WP_Query doesn't have any posts
*	Rating:				Critical
*	Reason:				The Category in cat01 doesn't have any posts. This is likely caused by a new install, and the default is cat id=1
*						which has no posts. Therefore the gallery won't run if first image doesn't exist.
*	Action:				Print public error message, error message in Page Source, then exit function.
*	Fix:				Go to DCG Settings page and click Save Changes. This will clear the default cat01=1, and all can run normally.
*	Notes:				This is a post-WP_Query validation check, only triggered by the first WP_Query.
*						
*/
$dfcg_errmsg_13 .= "\n" . $dfcg_errmsg_public . "\n";
$dfcg_errmsg_13 .= '<!-- DCG Error Message 13: The Post for Image Slot 1 could not be found. -->' . "\n";
$dfcg_errmsg_13 .= '<!-- Rating: Critical. Fix error in order to display gallery. -->' . "\n";
$dfcg_errmsg_13 .= '<!-- This is because the plugin has set a default category for this Image Slot, but there are no posts in this category. -->' . "\n";
$dfcg_errmsg_13 .= '<!-- Fix: Go to the DCG Settings page and click Save Changes. The error should then clear itself. -->';


// Set up our error message array of all error messages
// This will be handier when using global scope declaration in gallery display functions


// If Error reporting is ON
if( $dfcg_options['errors'] == "true" ) {
	// Error messages are visible
	$dfcg_errmsgs = array (
		'1' => $dfcg_errmsg_1,
		'2' => $dfcg_errmsg_2,
		'3' => $dfcg_errmsg_3,
		'4' => $dfcg_errmsg_4,
		'5' => $dfcg_errmsg_5,
		'6' => $dfcg_errmsg_6,
		'7' => $dfcg_errmsg_7,
		'8' => $dfcg_errmsg_8,
		'9' => $dfcg_errmsg_9,
		'10' => $dfcg_errmsg_10,
		'11' => $dfcg_errmsg_11,
		'12' => $dfcg_errmsg_12,
		'13' => $dfcg_errmsg_13
		);
} else {
	// Error messages not visible
	$dfcg_errmsgs = array (
		'1' => '',
		'2' => '',
		'3' => '',
		'4' => '',
		'5' => '',
		'6' => '',
		'7' => '',
		'8' => '',
		'9' => '',
		'10' => '',
		'11' => '',
		'12' => '',
		'13' => '',
		);
}

?>