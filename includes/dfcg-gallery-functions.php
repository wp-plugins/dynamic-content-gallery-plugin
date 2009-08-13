<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	Dynamic Content Gallery
*	@version	3.0 beta
*
*	These are the key functions which produce the markup output
*	for the gallery to run.
*
*	One function for each of the 3 populate-methods.
*		- Multi Option		dfcg_multioption_method_gallery()
*		- One Category		dfcg_multioption_method_gallery()
*		- Pages				dfcg_multioption_method_gallery()
*
*/
function dfcg_multioption_method_gallery() {

	// Need to declare these in each function
	global $dfcg_errmsgs, $dfcg_options, $dfcg_baseimgurl, $post;
	
	/* Get the plugin options */
	//$dfcg_options = get_option('dfcg_plugin_settings');
	
	/* Set up some variables to use in WP_Query */
	$dfcg_offset = 1;

	// Error image
	$dfcg_errorimgurl = DFCG_URL . '/error-img/error-multioption.jpg';

	/* Get the partial Path to the default "Category" images folder */
	// This path is relative to get_settings('siteurl')
	$dfcg_defimgmulti = $dfcg_options['defimgmulti'];

	// Set a variable for the full path to the category images folder
	$filename = ABSPATH . $dfcg_defimgmulti;

	$dfcg_query_list = array();

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
		$tmpoffs = $tmpoffs-$dfcg_offset;
	
		// Create temp assoc array $key=>$value pair
		$tmp_query_list[$tmpcats] = $tmpoffs;
	
		// Add this array to final array
		array_push($dfcg_query_list, $tmp_query_list);
	
		// Empty temp array ready for next loop
		unset($tmp_query_list);
	}

	/* Collect some info about our array, for later */
	$dfcg_selected_slots = count($dfcg_query_list);
	
	/* Validate that $dfcg_query_list has at least 2 items for gallery to work */
	if( $dfcg_selected_slots < 2 ) {
		$output .= "\n" . $dfcg_errmsgs['public'] . "\n";
		$output .= $dfcg_errmsgs['11'] . "\n";
		echo $output;
		return;
	}	


	// Start the Gallery Markup
	$output = "\n" . '<div id="myGallery"><!-- Start of Dynamic Content Gallery -->' . "\n\n";


	// Validation of output - not much needs to be done. 
	// Clicking Save in Settings will automatically assign a valid cat to each image slot.
	// This is because wp_dropdown_categories is set to "hide empty".
	// Any empty post selects will be ignored, as per for loop below,
	// therefore the only risk is that a post select is entered for a post
	// which doesn't exist. Eg, post #4, but there are only 3 in that cat.
	// This situation is dealt with by the counters...
	// We also validate that there are at least 2 post selects (see above)
	 
	// Set 2 counters to find out how many Posts are supposed to be output
	// by WP_Query, and how many posts are actually found by WP_Query
	// $counter:	Adds an image # in the markup page source
	//				Counts how many times we go through $dfcg_query_list foreach loop
	//				This is pre-WP_Query
	// $counter1:	Counts how many times WP_Query outputs anything
	//				We can then compare the two values to see if anything is missing
	$counter = 0;
	$counter1 = 0;

	/* Now loop through our array of all the cat/post selects and run the WP_Queries */
	foreach ($dfcg_query_list as $value) {
	
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
					$output .= "\n" . $dfcg_errmsgs['public'] . "\n";
					$output .= $dfcg_errmsgs['13'] . "\n\n";
					$output .= '</div><!-- End of Dynamic Content Gallery output -->'."\n\n";
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
						if( get_post_meta($post->ID, "dfcg-desc", true) ){
							// We have a Custom field description
							$output .= "\t" . '<p>' . get_post_meta($post->ID, "dfcg-desc", true) . '</p>' . "\n";

						} elseif( category_description($key) !== '') {
							// or Category has been selected and there is a category description
							$output .= "\t" . category_description($key) . "\n";

						} else {
							// or show the default description
							$output .= "\t" . '<p></p>' . "\n";
							$output .= "\t" . $dfcg_errmsgs['3'] . "\n";
						}

       					// Link
						$output .= "\t" . '<a href="'. get_permalink() .'" title="Read More" class="open"></a>' . "\n";

						// Get the images
						if( get_post_meta($post->ID, "dfcg-image", true) ) {
							$output .= "\t" . '<img src="'. $dfcg_baseimgurl . get_post_meta($post->ID, "dfcg-image", true) .'" alt="'. get_the_title() .'" class="full" />' . "\n";
        					$output .= "\t" . '<img src="'. $dfcg_baseimgurl . get_post_meta($post->ID, "dfcg-image", true) .'" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
							// Note: No Error message will be triggered if dfcg-image is set but URL is wrong, ie 404
						} else {
							// Path to Default Category image
							$filename1 = $filename . $key . '.jpg';
							if( file_exists($filename1) ) {
								$output .= "\t" . '<img src="'. get_settings('siteurl') . $dfcg_defimgmulti . $key .'.jpg" alt="'. get_the_title() .'" class="full" />' . "\n";
        						$output .= "\t" . '<img src="'. get_settings('siteurl') . $dfcg_defimgmulti . $key .'.jpg" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
							} else {
								$output .= "\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. get_the_title() .'" class="full" />' . "\n";
        						$output .= "\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
        						$output .= "\t" . $dfcg_errmsgs['4'] . "\n";
							}
						}

						// Close ImageElement div
						$output .= '</div>' . "\n";
				
						//clearstatcache(); Probably not needed as it is unlikely that filename will change during running of script.

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
			$output .= '<!-- Number of Posts to display as per DCG Settings = ' . $counter . ' -->' . "\n";
			$output .= '<!-- Number of Posts found = ' . $counter1 . ' -->' . "\n\n";
		}
	}
	
	// End of the gallery markup
	$output .= '</div><!-- End of Dynamic Content Gallery output -->'."\n\n";
	
	// Output the Gallery
	echo $output;
}


/*	This function builds the gallery from One Category options
*
*	@param	$dfcg_options		Array of DCG options from wp_postmeta
*	@param	$dfcg_errorimgurl	Absolute URL to DCG Error image
*	@param	$dfcg_posts_number	DCG option: number of posts to display
*	@param	$dfcg_cat			DCG option: selected category
*	@param	$dfcg_defimagepath	DCG option: relative path from siteurl to default images folder
*	@param	$filename			Absolute path from server root to the default image for the selected category
*	@param	$recent				WP_Query object
*	@param	$counter			Incremented variable to find number of posts output by wp_query
*
*	@since	2.3
*
*
*/
function dfcg_onecategory_method_gallery() {

	global $post, $dfcg_options, $dfcg_errmsgs, $dfcg_baseimgurl;

	/* Get the URL to One Category Error image. */
	// This is an absolute URL
	$dfcg_errorimgurl = DFCG_URL . '/error-img/error-onecategory.jpg';

	/* Get the number of Posts to display */
	// No need to check that there is a minimum of 2 posts, thanks to dropdown in Settings
	$dfcg_posts_number = $dfcg_options['posts-number'];

	/* Get the Selected Category */
	// No need to check Category existence, or whether it has Posts,
	// thanks to use of dropdown in Settings
	$dfcg_cat = $dfcg_options['cat-display'];

	/* Get the path to the default "Category" images folder */
	// This path is relative to get_settings('siteurl')
	$dfcg_defimgonecat = $dfcg_options['defimgonecat'];

	// Set a variable for the category default image using the cat ID number for the image name
	// This needed for the file_exists() check.
	$filename = ABSPATH . $dfcg_defimgonecat . $dfcg_cat .'.jpg';

	/* Do the WP_Query */
	$recent = new WP_Query("cat=$dfcg_cat&showposts=$dfcg_posts_number");
	// TODO: This validation never returns false, so is useless. Needs to be replaced with multioption validation.
	// Do we have any posts?
	if ( $recent ) {

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

			// Do we have a dfcg-desc?
			if( get_post_meta($post->ID, "dfcg-desc", true) ) {
				$output .= "\t" . '<p>'. get_post_meta($post->ID, "dfcg-desc", true) . '</p>' . "\n";

			} elseif( $dfcg_cat !== 0 ) {
				
				if( category_description($dfcg_cat) !== '') {
					// a category description exists
					$output .= "\t" . category_description($dfcg_cat) . "\n";
				}

			} elseif( $dfcg_options['defimagedesc'] !== '' ) {
				// Show the default description
				$output .= "\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>' . "\n";

			} else {
				// Show the error message
				$output .= "\t" . '<p></p>' . "\n";
				$output .= "\t" . $dfcg_errmsgs['3'] . "\n";
			}

			// Link
			$output .= "\t" . '<a href="'. get_permalink() .'" title="Read More" class="open"></a>' . "\n";

			// Get the dfcg-image
			if( get_post_meta($post->ID, "dfcg-image", true) ) {
				$output .= "\t" . '<img src="'. $dfcg_baseimgurl . get_post_meta($post->ID, "dfcg-image", true) .'" alt="'. get_the_title() .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. $dfcg_baseimgurl . get_post_meta($post->ID, "dfcg-image", true) .'" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
				// Note: No Error message will be triggered if dfcg-image is set but URL is wrong, ie 404.
			} elseif( file_exists($filename) ) {
				// Display the "Category" default image
				$output .= "\t" . '<img src="'. get_settings('siteurl') . $dfcg_defimgonecat . $dfcg_cat .'.jpg" alt="'. get_the_title() .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. get_settings('siteurl') . $dfcg_defimgonecat . $dfcg_cat .'.jpg" alt="'. get_the_title() .'" class="thumbnail" />' . "\n";
			} else {
				// Display One Category Error image
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

		if( $counter - $dfcg_posts_number !== 0 ) {
			$output .= "\n" . $dfcg_errmsgs['7'] . "\n";
			if( $dfcg_options['errors'] == "true" ) {
				$output .= '<!-- Number of Posts to display as per DCG Settings = ' . $dfcg_posts_number . ' -->' . "\n";
				$output .= '<!-- Number of Posts found = ' . $counter . ' -->' . "\n\n";
			}
		}

		// End of the gallery markup
		$output .= '</div><!-- End of Dynamic Content Gallery output -->'."\n\n";

	} else {
		/* Oops! The WP_Query couldn't find any Posts */
		// Theoretically this can never happen unless there is a WP problem
		$output .= "\n" . $dfcg_errmsgs['public'] . "\n";
		$output .= $dfcg_errmsgs['8'] . "\n";
	}
	
	// Output the Gallery
	echo $output;
}


/*	This function builds the gallery from Pages options
*
*	@param	$dfcg_options				Array of DCG options from wp_postmeta
*	@param	$dfcg_errorimgurl			Absolute URL to DCG Error image
*	@param	$dfcg_pages_selected		DCG option: comma separated list of Page IDs
*	@param	$dfcg_pages_selected_count	No. of pages specified in DCG options
*	@param	$dfcg_temp					Array of Page IDs from comma separated list. Only used in order to run count() function
*	@param	$dfcg_pages_found			$wpdb query object
*	@param	$dfcg_pages_found_count		Number of Pages in $wpdb query object
*	@param	$counter					Incremented variable to add image # in HTML comments markup
*
*	@since	2.3
*
*
*/
function dfcg_pages_method_gallery() {

	global $dfcg_options, $dfcg_errmsgs, $dfcg_baseimgurl;

	/* Set the URL to Pages Error image. */
	// This is an absolute URL
	$dfcg_errorimgurl = DFCG_URL . '/error-img/error-pages.jpg';

	/* Get the comma separated list of Page ID's */
	$dfcg_pages_selected = trim($dfcg_options['pages-selected']);

	if( !empty($dfcg_pages_selected) ) {

		/* Get rid of the final comma so that the variable is ready for use in SQL query */
		// If last character in string is a comma
		if( substr( $dfcg_pages_selected, -1) == ',' ) {
			// Remove the final comma in the list
			$dfcg_pages_selected = substr( $dfcg_pages_selected, 0, substr( $dfcg_pages_selected, -1)-1 );
		}

		/* Turn the list into an array */
		$dfcg_temp = explode(",", $dfcg_pages_selected);
		/* Store how many IDs were in list */
		$dfcg_pages_selected_count = count($dfcg_temp);

		/* If only one Page ID has been specified in Settings: print error messages and exit */
		if( $dfcg_pages_selected_count < 2 ) {
			$output .= "\n" . $dfcg_errmsgs['public'] . "\n";
			$output .= $dfcg_errmsgs['1'] . "\n";
			echo $output;
			return;
		}

	} else {
		/* There are no Page IDs in Settings: print error messages and exit */
		$output .= "\n" . $dfcg_errmsgs['public'] . "\n";
		$output .= $dfcg_errmsgs['2'] . "\n";
		echo $output;
		return;
	}


	/* Instantiate the $wpdb object */
	global $wpdb;

	/* Do the query */
	$dfcg_pages_found = $wpdb->get_results( "SELECT ID,post_title,post_type FROM $wpdb->posts
											WHERE post_type = 'page'
											AND $wpdb->posts.ID IN( $dfcg_pages_selected )" );

	/* If we have results from the query */
	if( $dfcg_pages_found ) {

		// Validation: Check how many Pages the query found
		// The results if this are printed to Page Source further down
		$dfcg_pages_found_count = count($dfcg_pages_found);
	
		// If less than 2, print error messages and exit function
		if( $dfcg_pages_found_count < 2 ) {
			$output .= "\n" . $dfcg_errmsgs['public'] . "\n";
			$output .= "\n" . $dfcg_errmsgs['9'] . "\n";
			if( $dfcg_options['errors'] == "true" ) {
				$output .= '<!-- Number of Pages selected in DCG Settings = ' . $dfcg_pages_selected_count . ' -->' . "\n";
				$output .= '<!-- Number of Pages found = ' . $dfcg_pages_found_count . ' -->' . "\n\n";
			}
			echo $output;
			return;
		}

		// Set a counter to add an image # in the markup page source
		$counter = 0;

		// Start the gallery markup
		$output .= "\n" . '<div id="myGallery"><!-- Start of Dynamic Content Gallery output -->'."\n\n";

		foreach( $dfcg_pages_found as $dfcg_page_found ) :

			// Increment the counter
			$counter++;

			// Open the imageElement div
			$output .= '<div class="imageElement"><!-- DCG Image #' . $counter . '-->' . "\n";

			// Display the page title
			$output .= "\t" . '<h3>'. $dfcg_page_found->post_title .'</h3>' . "\n";

			// Do we have a dfcg-desc?
			if( get_post_meta($dfcg_page_found->ID, "dfcg-desc", true) ) {
				$output .= "\t" . '<p>' . get_post_meta($dfcg_page_found->ID, "dfcg-desc", true) . '</p>' . "\n";

			} elseif( $dfcg_options['defimagedesc'] !== '' ) {
				// Show the default description
				$output .= "\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>' . "\n";

			} else {
				// Show the error message
				$output .= "\t" . '<p></p>' . "\n";
				$output .= "\t" . $dfcg_errmsgs['3'] . "\n";
			}

			// Link
			$output .= "\t" . '<a href="'. get_permalink( $dfcg_page_found->ID ) .'" title="Read More" class="open"></a>' . "\n";

			// Get the dfcg-image
			if( get_post_meta($dfcg_page_found->ID, "dfcg-image", true) ) {
				$output .= "\t" . '<img src="'. $dfcg_baseimgurl . get_post_meta($dfcg_page_found->ID, "dfcg-image", true) .'" alt="'. $dfcg_page_found->post_title .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. $dfcg_baseimgurl . get_post_meta($dfcg_page_found->ID, "dfcg-image", true) .'" alt="'. $dfcg_page_found->post_title .'" class="thumbnail" />' . "\n";
				// Note: No Error message will be triggered if dfcg-image is set but URL is wrong, ie 404.
			} elseif( !empty($dfcg_options['defimgpages']) ) {
				// Display the "Pages" default image
				$output .= "\t" . '<img src="'. $dfcg_options['defimgpages'] .'" alt="'. $dfcg_page_found->post_title .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. $dfcg_options['defimgpages'] .'" alt="'. $dfcg_page_found->post_title .'" class="thumbnail" />' . "\n";
        		// Note: No Error message will be triggered if defimgpages is set but URL is wrong, ie 404.
			} else {
				// Display Pages Error image
				$output .= "\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. $dfcg_page_found->post_title .'" class="full" />' . "\n";
        		$output .= "\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. $dfcg_page_found->post_title .'" class="thumbnail" />' . "\n";
				$output .= "\t" . $dfcg_errmsgs['4'] . "\n";
			}

			// Close the ImageElement div
			$output .= '</div>'."\n\n";

		endforeach;

		/*	Compare $dfcg_pages_selected_count with the db query object $dfcg_pages_found_count
			to check that the number of gallery images is the same.	If it's not the
			same, then one or more of the selected Page IDs are not valid Pages */

		if( $dfcg_pages_found_count !== $dfcg_pages_selected_count) {
			$output .= "\n" . $dfcg_errmsgs['5'] . "\n";
			if( $dfcg_options['errors'] == "true" ) {
				$output .= '<!-- Number of Pages selected in DCG Settings = ' . $dfcg_pages_selected_count . ' -->' . "\n";
				$output .= '<!-- Number of Pages found = ' . $dfcg_pages_found_count . ' -->' . "\n\n";
			}
		}

		// End of the gallery markup
		$output .= '</div><!-- End of Dynamic Content Gallery output -->'."\n\n";
		
	} else {
		/* Oops! Either none of the Page IDs are valid or the db query failed in some way */
		$output .= "\n" . $dfcg_errmsgs['public'] . "\n";
		$output .= $dfcg_errmsgs['6'] . "\n";
	}
	
	// Output the Gallery
	echo $output;
}