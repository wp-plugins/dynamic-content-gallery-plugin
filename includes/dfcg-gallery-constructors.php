<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.1
*
*	These are the key functions which produce the markup output
*	for the gallery to run.
*
*	One function for each of the 3 populate-methods.
*		- Multi Option		dfcg_multioption_method_gallery()
*		- One Category		dfcg_onecategory_method_gallery()
*		- Pages				dfcg_pages_method_gallery()
*
*	@since	3.0
*
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}



/*	This function builds the gallery from Multi Option options
*
*	@uses	dfcg_baseimgurl()			Determines whether Full or Partial URL applies (see dfcg-gallery-core.php)
*
*	@param	$dfcg_errmsgs				Global: Array of error messages
*	@param	$dfcg_options				Global: Array of DCG options from wp_postmeta
*	@param	$dfcg_errorimgurl			Global: Absolute URL to DCG Error image
*	@param	$baseimgurl					Base URL for images. Empty if Full URL
*	@param	$offset						Turns Post Select (offxx) option to real wp_query offset
*	@param	$defimgmulti				Holds DCG Option: defimgmulti
*	@param	$filepath					Absolute path to default images directory
*	@param	$query_list					Array of cat/off pairs
*	@param	$selected_slots				Number of pairs in $query_list array
*	@param	$counter					Stores how many times $query_list is run through foreach loop
*	@param	$counter1					Stores how many times WP_Query is run (to do comparison for missing posts)
*	@param	$filename					Stores absolute path, incl filename, of category default image
*
*	@since	3.0
*/
function dfcg_multioption_method_gallery() {

	// Need to declare these in each function
	global $dfcg_errmsgs, $dfcg_options, $dfcg_errorimgurl, $post;
	
	// Set $baseimgurl variable for image URL
	$baseimgurl = dfcg_baseimgurl();
	
	// Set up variable to convert Slot to real Offset
	$offset = 1;

	// Get the absolute URL to the default "Category" images folder from Settings
	$defimgmulti = $dfcg_options['defimgmulti'];

	// Convert category images absolute URL to Path (with thanks to Charles Clarkson)
	//$filepath = preg_replace( '|^.+/wp-content|i', WP_CONTENT_DIR, $defimgmulti );
	
	// Added 3.1: Strip domain name from URL, replace with ABSPATH. Default folder can now be anywhere
	$filepath = str_replace( get_bloginfo('siteurl'), ABSPATH, $defimgmulti );
	
	$query_list = array();

	// Loop through the 9 possible cats/post selects
	for( $i=1; $i < 10; $i+=1 ) {
	
		// Set temp variables for catXX and offXX
		$tmpcat = 'cat0'.$i;
		$tmpoff = 'off0'.$i;
	
		// Get Settings
		$tmpcats = $dfcg_options[$tmpcat];
		$tmpoffs = $dfcg_options[$tmpoff];
	
		// If Post Select is empty, skip
		if( empty($tmpoffs) ) continue;
	
		// Convert Post Select to real Offset
		$tmpoffs = $tmpoffs-$offset;
	
		// Create temp assoc array $key=>$value pair
		$tmp_query_list[$tmpcats] = $tmpoffs;
	
		// Add this array to final array
		array_push($query_list, $tmp_query_list);
	
		// Empty temp array ready for next loop
		unset($tmp_query_list);
	}

	/* Collect some info about our array, for later */
	$selected_slots = count($query_list);
	
	/* Validate that $query_list has at least 2 items for gallery to work */
	if( $selected_slots < 2 ) {
		$output .= $dfcg_errmsgs['11'] . "\n";
		echo $output;
		return;
	}


	// Start the Gallery Markup
	$output = "\n" . '<div id="myGallery"><!-- Start of Dynamic Content Gallery -->' . "\n\n";


	// Validation of output - not much needs to be done. 
	// Clicking Save in Settings will automatically assign a valid cat to each image slot
	// because wp_dropdown_categories is set to "hide empty".
	// Any empty post selects will be ignored, as per foreach loop below,
	// therefore the only risk is that a post select is entered for a post
	// which doesn't exist. Eg, post #4, but there are only 3 in that cat.
	// This situation is dealt with by the counters...
	// We also validate that there are at least 2 post selects (see above)
	 
	// Set 2 counters to find out how many Posts are supposed to be output
	// by WP_Query, and how many posts are actually found by WP_Query
	// $counter:	Adds an image # in the markup page source
	//				Counts how many times we go through $query_list foreach loop
	//				This is pre-WP_Query
	// $counter1:	Counts how many times WP_Query outputs anything
	//				We can then compare the two values to see if anything is missing
	$counter = 0;
	$counter1 = 0;

	/* Now loop through our array of all the cat/post selects and run the WP_Queries */
	foreach ($query_list as $value) {
	
		// Go down into inner arrays which contain the cat/offset pairs
		if( is_array($value) ) {
		
			// Increment the counter
			$counter++;
		
			// Loop through the inner array (this only happens once before passing back to the outer foreach loop
			foreach ($value as $key => $value1) {
					
				// Now run the query using $key for cat and $value for offset
				$recent = new WP_Query("cat=$key&showposts=1&offset=$value1");
				
				
				// Do we have any posts? If this is the first loop and no post is found, we need to abort
				// because the gallery won't display. Although this check is performed on every loop, we
				// don't need to abort after Image slot #1 is tested.
				if( !$recent->have_posts() && $counter < 2 ) :
					$output .= $dfcg_errmsgs['13'] . "\n";
					$output .= "\n" . '</div><!-- End of Dynamic Content Gallery output -->' . "\n\n";
					echo $output;
					return;
				
				else :
					while($recent->have_posts()) : $recent->the_post();
				
						// Increment the second counter
						$counter1++;
					
						// Open the imageElement div
						$output .= '<div class="imageElement"><!-- DCG Image #' . $counter . ' -->' . "\n";

						// Display the page title
						$output .= "\t" . '<h3>' . get_the_title() . '</h3>' . "\n";

						// Get the description
						if( $dfcg_options['desc-method'] == 'manual' ) {
						
							if( get_post_meta($post->ID, "dfcg-desc", true) ){
								// We have a Custom field description
								$output .= "\t" . '<p>' . get_post_meta($post->ID, "dfcg-desc", true) . '</p>' . "\n";

							} elseif( category_description($key) !== '' ) {
								// show the category description (note: no <p> tags required)
								$output .= "\t" . category_description($key) . "\n";

							} elseif( $dfcg_options['defimagedesc'] !== '' ) {
								// or show the default description
								$output .= "\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>' . "\n";
							
							} else {
								// we have no descriptions (note: smoothgallery needs <p> tags or won't work)
								$output .= "\t" . '<p></p>' . "\n";
								$output .= "\t" . $dfcg_errmsgs['3'] . "\n";
							}
						
						} else {
							// We're using Auto custom excerpt
							$chars = $dfcg_options['max-char'];
							$more = $dfcg_options['more-text'];
							$auto_text = dfcg_the_content_limit( $chars, $more );
							$output .= "\t" . $auto_text . "\n";
						}

       					// Link - additional code courtesy of Martin Downer
						if( get_post_meta($post->ID, "dfcg-link", true) ){
							// We have an external/manual link
							$output .= "\t" . '<a href="'. get_post_meta($post->ID, "dfcg-link", true) .'" title="Read More" class="open"></a>' . "\n";
							
						} else {
							$output .= "\t" . '<a href="'. get_permalink() .'" title="Read More" class="open"></a>' . "\n";
						}

						// Get the images
						if( get_post_meta($post->ID, "dfcg-image", true) ) {
							$output .= "\t" . '<img src="'. $baseimgurl . get_post_meta($post->ID, "dfcg-image", true) .'" alt="'. get_the_title() .'" class="full" />' . "\n";
        					$output .= "\t" . '<img src="'. $baseimgurl . get_post_meta($post->ID, "dfcg-image", true) .'" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
							// Note: No Error message will be triggered if dfcg-image is set but URL is wrong, ie image gives 404
						} else {
							// Path to Default Category image
							$filename = $filepath . $key . '.jpg';
							// Does category image exist?
							if( file_exists($filename) ) {
								$output .= "\t" . '<img src="'. $defimgmulti . $key .'.jpg" alt="'. get_the_title() .'" class="full" />' . "\n";
        						$output .= "\t" . '<img src="'. $defimgmulti . $key .'.jpg" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
							} else {
								$output .= "\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. get_the_title() .'" class="full" />' . "\n";
        						$output .= "\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
        						$output .= "\t" . $dfcg_errmsgs['4'] . "\n";
							}
						}

						// Close ImageElement div
						$output .= '</div>' . "\n";

					endwhile; 

				endif; 	// End WP_Query if($recent... ) test
			} 			// End inner foreach loop
		} 				// End conditional check that $value is an array
	} 					// End outer foreach loop
			
	// Compare the 2 counters to see if outputs were as expected.
	// $counter = number of Post Selects in Settings. Also sets the "Image #" comment in Page Source.
	// $counter1 = number of WP_Query outputs.
	// If these values are not the same, WP_Query couldn't find a Post. 
	if( $counter - $counter1 !== 0 ) {
		$output .= $dfcg_errmsgs['12'] . "\n\n";
		if( $dfcg_options['errors'] == "true" ) {
			$output .= '<!-- ' . __('Number of Posts to display as per DCG Settings = ', DFCG_DOMAIN) . $counter . ' -->' . "\n";
			$output .= '<!-- ' . __('Number of Posts found = ', DFCG_DOMAIN) . $counter1 . ' -->' . "\n\n";
		}
	}
	
	// End of the gallery markup
	$output .= '</div><!-- End of Dynamic Content Gallery output -->'."\n\n";
	
	// Output the Gallery
	echo $output;
}


/*	This function builds the gallery from One Category options
*
*	@uses	dfcg_baseimgurl()	Determines whether Full or Partial URL applies
*
*	@param	$dfcg_errmsgs			Global: Array of error messages
*	@param	$dfcg_options			Global: Array of DCG options from wp_postmeta
*	@param	$dfcg_errorimgurl		Global: Absolute URL to DCG Error image
*	@param	$baseimgurl				Base URL for images. Empty if Full URL
*	@param	$post_number			DCG option: number of posts to display
*	@param	$cat_selected			DCG option: selected category
*	@param	$defimgurl				DCG option: URL to default images folder
*	@param	$filepath				Absolute path to default images directory
*	@param	$def_img_name			Default image filename
*	@param	$filename				Stores absolute path, incl filename, of category default image
*	@param	$recent					WP_Query object
*	@param	$counter				Incremented variable to find number of posts output by wp_query
*
*	@since	3.0
*/
function dfcg_onecategory_method_gallery() {

	global $post, $dfcg_options, $dfcg_errmsgs, $dfcg_errorimgurl;

	// Set $baseimgurl variable for image URL
	$baseimgurl = dfcg_baseimgurl();
	
	/* Get the number of Posts to display */
	// No need to check that there is a minimum of 2 posts, thanks to dropdown in Settings
	$post_number = $dfcg_options['posts-number'];

	/* Get the Selected Category */
	// No need to check Category existence, or whether it has Posts,
	// thanks to use of dropdown in Settings
	$cat_selected = $dfcg_options['cat-display'];

	/* Get the URL to the default "Category" images folder from Settings */
	$defimgurl = $dfcg_options['defimgonecat'];

	// Convert category images folder absolute URL to Path (with thanks to Charles Clarkson)
	// $filepath = preg_replace( '|^.+/wp-content|i', WP_CONTENT_DIR, $defimgurl );
	
	// Added 3.1: Strip domain name from URL, replace with ABSPATH. Default folder can now be anywhere
	$filepath = str_replace( get_bloginfo('siteurl'), ABSPATH, $defimgurl );
	
	// Set a variable for the category default image using the cat ID number for the image name
	if( $cat_selected !== '' ) {
		$def_img_name = $cat_selected .'.jpg';
	} else {
		$def_img_name = 'all.jpg';
	}
	
	// Absolute path to default image
	// This needed for the file_exists() check.
	$filename = $filepath . $def_img_name;
	
	/* Do the WP_Query */
	$recent = new WP_Query("cat=$cat_selected&showposts=$post_number");
	// Do we have any posts?
	if ( $recent->have_posts() ) {

		// Set a counter to find out how many Posts are found in the WP_Query
		// Also used to add an image # in the markup page source
		$counter = 0;

		// Start the gallery markup
		$output = "\n" . '<div id="myGallery"><!-- Start of Dynamic Content Gallery output -->' . "\n\n";

		while($recent->have_posts()) : $recent->the_post();

			// Increment the counter
			$counter++;

			// Open the imageElement div
			$output .= '<div class="imageElement"><!-- DCG Image #' . $counter . ' -->'."\n";

			// Display the page title
			$output .= "\t" . '<h3>'. get_the_title() .'</h3>' . "\n";
			
			// Get the description
			if( $dfcg_options['desc-method'] == 'manual' ) {
			
				// Do we have a dfcg-desc?
				if( get_post_meta($post->ID, "dfcg-desc", true) ) {
					$output .= "\t" . '<p>'. get_post_meta($post->ID, "dfcg-desc", true) . '</p>' . "\n";
			
				// we have All cats
				} elseif( $cat_selected == '' ) {
				
					// TODO: Get the category ID so that cat descriptions can be displayed for ALL cats
				
					// Default description exists
					if( $dfcg_options['defimagedesc'] !== '' ) {
						// Show the default description
						$output .= "\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>' . "\n";
				
					} else {
						// There is no description
						$output .= "\t" . '<p></p>' . "\n";
						$output .= "\t" . $dfcg_errmsgs['3'] . "\n";
					}
				
				// we have Single cat and category desc exists
				} elseif( category_description($cat_selected) !== '') {
					// a category description exists
					$output .= "\t" . category_description($cat_selected) . "\n";
				
				// we have a Single cat and a default description exists
				} elseif( $dfcg_options['defimagedesc'] !== '') {
					// a default description exists
					$output .= "\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>' . "\n";
			
				// we have Single cat and no description
				} else {
					// Show the error message
					$output .= "\t" . '<p></p>' . "\n";
					$output .= "\t" . $dfcg_errmsgs['3'] . "\n";
				}
				
			} else {
				// We're using Auto custom excerpt
				$chars = $dfcg_options['max-char'];
				$more = $dfcg_options['more-text'];
				$auto_text = dfcg_the_content_limit( $chars, $more );
				$output .= "\t" . $auto_text . "\n";
			}

			// Link - additional code courtesy of Martin Downer
			if( get_post_meta($post->ID, "dfcg-link", true) ){
				// We have an external/manual link
				$output .= "\t" . '<a href="'. get_post_meta($post->ID, "dfcg-link", true) .'" title="Read More" class="open"></a>' . "\n";
							
			} else {
				$output .= "\t" . '<a href="'. get_permalink() .'" title="Read More" class="open"></a>' . "\n";
			}

			// Get the dfcg-image
			if( get_post_meta($post->ID, "dfcg-image", true) ) {
				$output .= "\t" . '<img src="'. $baseimgurl . get_post_meta($post->ID, "dfcg-image", true) .'" alt="'. get_the_title() .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. $baseimgurl . get_post_meta($post->ID, "dfcg-image", true) .'" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
				// Note: No Error message will be triggered if dfcg-image is set but URL is wrong, ie 404.
			
			} elseif( file_exists($filename) ) {
				// Display the "Category" default image
				$output .= "\t" . '<img src="'. $defimgurl . $def_img_name .'" alt="'. get_the_title() .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. $defimgurl . $def_img_name .'" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
			} else {
				$output .= "\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. get_the_title() .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
				$output .= "\t" . $dfcg_errmsgs['4'] . "\n";
			}

			// Close the ImageElement div
			$output .= '</div>'."\n\n";

		endwhile;

		/*	Compare original number of Posts with the WP_Query object output
			to check that the number of gallery images is the same.	If it's not the
			same, then there are less Posts in this Category than the posts-number Setting */

		if( $counter - $post_number !== 0 ) {
			$output .= "\n" . $dfcg_errmsgs['7'] . "\n";
			if( $dfcg_options['errors'] == "true" ) {
				$output .= '<!-- ' . __('Number of Posts to display as per DCG Settings = ', DFCG_DOMAIN) . $post_number . ' -->' . "\n";
				$output .= '<!-- ' . __('Number of Posts found = ', DFCG_DOMAIN) . $counter . ' -->' . "\n\n";
			}
		}

		// End of the gallery markup
		$output .= '</div><!-- End of Dynamic Content Gallery output -->'."\n\n";

	} else {
		/* Oops! The WP_Query couldn't find any Posts */
		// Theoretically this can never happen unless there is a WP problem
		$output .= $dfcg_errmsgs['8'] . "\n";
	}
	
	// Output the Gallery
	echo $output;
}


/*	This function builds the gallery from Pages options
*
*	@uses	dfcg_baseimgurl()
*
*	@param	$dfcg_errmsgs					Global: Array of error messages
*	@param	$dfcg_options					Global: Array of DCG options from wp_postmeta
*	@param	$dfcg_errorimgurl				Global: Absolute URL to DCG Error image
*	@param	$baseimgurl						Base URL for images. Empty if Full URL
*	@param	$pages_selected					DCG option: comma separated list of Page IDs
*	@param	$pages_selected_count			No. of pages specified in DCG options
*	@param	$pages_found					$wpdb query object
*	@param	$pages_found_count				Number of Pages in $wpdb query object
*	@param	$counter						Incremented variable to add image # in HTML comments markup
*
*	@since	3.0
*/
function dfcg_pages_method_gallery() {

	global $dfcg_options, $dfcg_errmsgs, $dfcg_errorimgurl;

	// Set $baseimgurl variable for image URL
	$baseimgurl = dfcg_baseimgurl();
	
	/* Get the comma separated list of Page ID's */
	$pages_selected = trim($dfcg_options['pages-selected']);

	if( !empty($pages_selected) ) {

		/* Get rid of the final comma so that the variable is ready for use in SQL query */
		// If last character in string is a comma
		if( substr( $pages_selected, -1) == ',' ) {
			// Remove the final comma in the list
			$pages_selected = substr( $pages_selected, 0, substr( $pages_selected, -1)-1 );
		}

		/* Turn the list into an array */
		$pages_selected = explode(",", $pages_selected);
		/* Store how many IDs were in list */
		$pages_selected_count = count($pages_selected);

		/* If only one Page ID has been specified in Settings: print error messages and exit */
		if( $pages_selected_count < 2 ) {
			$output .= $dfcg_errmsgs['1'] . "\n";
			echo $output;
			return;
		}

	} else {
		/* There are no Page IDs in Settings: print error messages and exit */
		$output .= $dfcg_errmsgs['2'] . "\n";
		echo $output;
		return;
	}


	/* Instantiate the $wpdb object */
	global $wpdb;
	
	/* Do the query - with thanks to Austin Matzko for sprintf help */
	$pages_found = $wpdb->get_results(
  		sprintf("SELECT ID,post_title,post_content FROM $wpdb->posts WHERE $wpdb->posts.ID IN( %s )", implode(',', array_map( 'intval', $pages_selected ) ) )
		);
											
	/* If we have results from the query */
	if( $pages_found ) {

		// Validation: Check how many Pages the query found
		// The results if this are printed to Page Source further down
		$pages_found_count = count($pages_found);
	
		// If less than 2, print error messages and exit function
		if( $pages_found_count < 2 ) {
			$output .= $dfcg_errmsgs['9'] . "\n";
			if( $dfcg_options['errors'] == "true" ) {
				$output .= '<!-- ' . __('Number of Pages selected in DCG Settings = ', DFCG_DOMAIN) . $pages_selected_count . ' -->' . "\n";
				$output .= '<!-- ' . __('Number of Pages found = ', DFCG_DOMAIN) . $pages_found_count . ' -->' . "\n\n";
			}
			echo $output;
			return;
		}

		// Set a counter to add an image # in the markup page source
		$counter = 0;

		// Start the gallery markup
		$output .= "\n" . '<div id="myGallery"><!-- Start of Dynamic Content Gallery output -->'."\n\n";

		foreach( $pages_found as $page_found ) :

			// Increment the image counter
			$counter++;

			// Open the imageElement div
			$output .= '<div class="imageElement"><!-- DCG Image #' . $counter . '-->' . "\n";

			// Display the page title
			$output .= "\t" . '<h3>'. $page_found->post_title .'</h3>' . "\n";

			// Get the description
			if( $dfcg_options['desc-method'] == 'manual' ) {
			
				// Do we have a dfcg-desc?
				if( get_post_meta($page_found->ID, "dfcg-desc", true) ) {
					$output .= "\t" . '<p>' . get_post_meta($page_found->ID, "dfcg-desc", true) . '</p>' . "\n";

				} elseif( $dfcg_options['defimagedesc'] !== '' ) {
					// Show the default description
					$output .= "\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>' . "\n";

				} else {
					// Show the error message
					$output .= "\t" . '<p></p>' . "\n";
					$output .= "\t" . $dfcg_errmsgs['3'] . "\n";
				}
				
			} else {
				// We're using Auto custom excerpt
				$page_content = $page_found->post_content;
				$page_id = $page_found->ID;
				$chars = $dfcg_options['max-char'];
				$more = $dfcg_options['more-text'];
				$auto_text = dfcg_the_content_limit( $chars, $more, $page_content, $page_id );
				$output .= "\t" . $auto_text . "\n";
			}

			// Link - additional code courtesy of Martin Downer
			if( get_post_meta($page_found->ID, "dfcg-link", true) ){
				// We have an external/manual link
				$output .= "\t" . '<a href="'. get_post_meta($page_found->ID, "dfcg-link", true) .'" title="Read More" class="open"></a>' . "\n";
							
			} else {
				$output .= "\t" . '<a href="'. get_permalink($page_found->ID) .'" title="Read More" class="open"></a>' . "\n";
			}

			// Get the dfcg-image
			if( get_post_meta($page_found->ID, "dfcg-image", true) ) {
				$output .= "\t" . '<img src="'. $baseimgurl . get_post_meta($page_found->ID, "dfcg-image", true) .'" alt="'. $page_found->post_title .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. $baseimgurl . get_post_meta($page_found->ID, "dfcg-image", true) .'" alt="'. $page_found->post_title .'" class="thumbnail" />' . "\n";
				// Note: No Error message will be triggered if dfcg-image is set but URL is wrong, ie 404.
			
			} elseif( !empty($dfcg_options['defimgpages']) ) {
				// Display the "Pages" default image
				$output .= "\t" . '<img src="'. $dfcg_options['defimgpages'] .'" alt="'. $page_found->post_title .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. $dfcg_options['defimgpages'] .'" alt="'. $page_found->post_title .'" class="thumbnail" />' . "\n";
        		// Note: No Error message will be triggered if defimgpages is set but URL is wrong, ie 404.
			
			} else {
				// Display Pages Error image
				$output .= "\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. $page_found->post_title .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. $page_found->post_title .'" class="thumbnail" />' . "\n";
				$output .= "\t" . $dfcg_errmsgs['4'] . "\n";
			}

			// Close the ImageElement div
			$output .= '</div>'."\n\n";

		endforeach;

		/*	Compare $pages_selected_count with the db query object $pages_found_count
			to check that the number of gallery images is the same.	If it's not the
			same, then one or more of the selected Page IDs are not valid Pages */

		if( $pages_found_count !== $pages_selected_count) {
			$output .= "\n" . $dfcg_errmsgs['5'] . "\n";
			if( $dfcg_options['errors'] == "true" ) {
				$output .= '<!-- ' . __('Number of Pages selected in DCG Settings = ', DFCG_DOMAIN) . $pages_selected_count . ' -->' . "\n";
				$output .= '<!-- ' . __('Number of Pages found = ', DFCG_DOMAIN) . $pages_found_count . ' -->' . "\n\n";
			}
		}

		// End of the gallery markup
		$output .= '</div><!-- End of Dynamic Content Gallery output -->'."\n\n";
		
	} else {
		/* Oops! Either none of the Page IDs are valid or the db query failed in some way */
		$output .= $dfcg_errmsgs['6'] . "\n";
	}
	
	// Output the Gallery
	echo $output;
}