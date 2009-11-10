<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.0 RC4
*
*	These are the key functions which produce the markup output
*	for the gallery to run using JQUERY scripts.
*
*	One function for each of the 3 populate-methods.
*		- Multi Option		dfcg_jq_multioption_method_gallery()
*		- One Category		dfcg_jq_onecategory_method_gallery()
*		- Pages				dfcg_jq_pages_method_gallery()
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
*	@uses	dfcg_baseimgurl()			Determines whether Full or Partial URL applies
*
*	@param	$dfcg_options				Array of DCG options from wp_postmeta
*	@param	$dfcg_errorimgurl			Absolute URL to DCG Error image
*	@param	$dfcg_offset				Turns Post Select (offxx) option to real wp_query offset
*	@param	$dfcg_defimgmulti			Holds DCG Option: defimgmulti
*	@param	$filepath					Absolute path to default images directory
*	@param	$dfcg_query_list			Array of cat/off pairs
*	@param	$dfcg_selected_slots		Number of pairs in $dfcg_query_list array
*	@param	$counter					Stores how many times $dfcg_query_list is run through foreach loop
*	@param	$counter1					Stores how many times WP_Query is run (to do comparison for missing posts)
*
*	@since	3.0
*/
function dfcg_jq_multioption_method_gallery() {

	// Need to declare these in each function
	global $dfcg_errmsgs, $dfcg_options, $dfcg_errorimgurl, $post;
	
	// Set $dfcg_baseimgurl variable for image URL
	$dfcg_baseimgurl = dfcg_baseimgurl();
	
	/* Set up some variables to use in WP_Query */
	$dfcg_offset = 1;

	/* Get the URL to the default "Category" images folder from Settings */
	// This URL is absolute
	$dfcg_defimgmulti = $dfcg_options['defimgmulti'];

	// Convert category images absolute URL to Path (with thanks to Charles Clarkson)
	$filepath = preg_replace( '|^.+/wp-content|i', WP_CONTENT_DIR, $dfcg_defimgmulti ); 

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
		$output .= $dfcg_errmsgs['11'] . "\n";
		echo $output;
		return;
	}


	// Start the Gallery Markup
	$output = "\n" . '<div id="dfcg_images" class="galleryview"><!-- Start of Dynamic Content Gallery -->' . "\n\n";


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
					$output .= $dfcg_errmsgs['13'] . "\n";
					$output .= "\n" . '</div><!-- End of Dynamic Content Gallery output -->' . "\n\n";
					echo $output;
					return;
				
				else :
					while($recent->have_posts()) : $recent->the_post();
				
						// Increment the second counter
						$counter1++;
					
						// Open the panel div
						$output .= '<div class="panel"><!-- DCG Image #' . $counter . ' -->' . "\n";

						// Link - additional code courtesy of Martin Downer
						if( get_post_meta($post->ID, "dfcg-link", true) ){
							// We have an external/manual link
							$output .= "\t" . '<a href="'. get_post_meta($post->ID, "dfcg-link", true) .'" rel="bookmark">' . "\n";
							
						} else {
							$output .= "\t" . '<a href="'. get_permalink() .'" rel="bookmark">' . "\n";
						}
						
						// Get the images
						if( get_post_meta($post->ID, "dfcg-image", true) ) {
							$output .= "\t\t" . '<img src="'. $dfcg_baseimgurl . get_post_meta($post->ID, "dfcg-image", true) .'" alt="'. get_the_title() .'" />' . "\n";
        					// Note: No Error message will be triggered if dfcg-image is set but URL is wrong, ie image gives 404
						} else {
							// Path to Default Category image
							$filename = $filepath . $key . '.jpg';
							if( file_exists($filename) ) {
								$output .= "\t\t" . '<img src="'. $dfcg_defimgmulti . $key .'.jpg" alt="'. get_the_title() .'" />' . "\n";
        					} else {
								$output .= "\t\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. get_the_title() .'" />' . "\n";
        						$output .= "\t\t" . $dfcg_errmsgs['4'] . "\n";
							}
						}
						
						// Close the link XHTML tag
						$output .= "\t" . '</a>' . "\n";
						
						// Open panel-overlay div
						$output .= "\t" .'<div class="panel-overlay">' . "\n";
						
						// Display the page title
						$output .= "\t\t" . '<h3>' . get_the_title() . '</h3>' . "\n";

						// Get the description
						if( get_post_meta($post->ID, "dfcg-desc", true) ){
							// We have a Custom field description
							$output .= "\t\t" . '<p>' . get_post_meta($post->ID, "dfcg-desc", true) . '</p>' . "\n";

						} elseif( category_description($key) !== '' ) {
							// show the category description (no <p> tags required)
							$output .= "\t\t" . category_description($key) . "\n";

						} elseif( $dfcg_options['defimagedesc'] !== '' ) {
							// or show the default description
							$output .= "\t\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>' . "\n";
							
						} else {
							// we have no descriptions
							$output .= "\t\t" . '<p></p>' . "\n";
							$output .= "\t\t" . $dfcg_errmsgs['3'] . "\n";
						}

       					// Close the panel-overlay div
						$output .= "\t" . '</div>'."\n";
			
						// Close the panel div
						$output .= '</div>'."\n\n";

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
*	@uses	dfcg_baseimgurl()
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
*	@since	3.0
*/
function dfcg_jq_onecategory_method_gallery() {

	global $post, $dfcg_options, $dfcg_errmsgs, $dfcg_errorimgurl;

	// Set $dfcg_baseimgurl variable for image URL
	$dfcg_baseimgurl = dfcg_baseimgurl();
	
	/* Get the number of Posts to display */
	// No need to check that there is a minimum of 2 posts, thanks to dropdown in Settings
	$dfcg_posts_number = $dfcg_options['posts-number'];

	/* Get the Selected Category */
	// No need to check Category existence, or whether it has Posts,
	// thanks to use of dropdown in Settings
	$dfcg_cat = $dfcg_options['cat-display'];

	/* Get the URL to the default "Category" images folder from Settings */
	// This is an absolute URL
	$dfcg_defimgonecat = $dfcg_options['defimgonecat'];

	// Convert category images folder absolute URL to Path (with thanks to Charles Clarkson)
	$filepath = preg_replace( '|^.+/wp-content|i', WP_CONTENT_DIR, $dfcg_defimgonecat );
	
	// Set a variable for the category default image using the cat ID number for the image name
	if( $dfcg_cat !== '' ) {
		$dfcg_defimgonecat_image = $dfcg_cat .'.jpg';
	} else {
		$dfcg_defimgonecat_image = 'all.jpg';
	}
	
	// Absolute path to default image
	// This needed for the file_exists() check.
	$filename1 = $filepath . $dfcg_defonecat_image;
	
	/* Do the WP_Query */
	$recent = new WP_Query("cat=$dfcg_cat&showposts=$dfcg_posts_number");
	// Do we have any posts?
	if ( $recent->have_posts() ) {

		// Set a counter to find out how many Posts are found in the WP_Query
		// Also used to add an image # in the markup page source
		$counter = 0;

		// Start the gallery markup
		$output = "\n" . '<div id="dfcg_images" class="galleryview"><!-- Start of Dynamic Content Gallery output -->' . "\n\n";

		while($recent->have_posts()) : $recent->the_post();

			// Increment the counter
			$counter++;

			// Open the panel div
			$output .= '<div class="panel"><!-- DCG Image #' . $counter . ' -->'."\n";

			// Link - additional code courtesy of Martin Downer
			if( get_post_meta($post->ID, "dfcg-link", true) ){
				// We have an external/manual link
				$output .= "\t" . '<a href="'. get_post_meta($post->ID, "dfcg-link", true) .'" rel="bookmark">' . "\n";
							
			} else {
				$output .= "\t" . '<a href="'. get_permalink() .'" rel="bookmark">' . "\n";
			}

			// Get the dfcg-image
			if( get_post_meta($post->ID, "dfcg-image", true) ) {
				$output .= "\t\t" . '<img src="'. $dfcg_baseimgurl . get_post_meta($post->ID, "dfcg-image", true) .'" alt="'. get_the_title() .'" />' . "\n";
        		
				// Note: No Error message will be triggered if dfcg-image is set but URL is wrong, ie image gives 404.
			} elseif( file_exists($filename1) ) {
				// Display the "Category" default image
				$output .= "\t\t" . '<img src="'. $dfcg_defimgonecat . $dfcg_defimgonecat_image .'" alt="'. get_the_title() .'" />' . "\n";
        		
			} else {
				// Display One Category Error image
				$output .= "\t\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. get_the_title() .'" class="full" />' . "\n";
        		$output .= "\t" . $dfcg_errmsgs['4'] . "\n";
			}

			// Close the link XHTML tag
			$output .= "\t" . '</a>' . "\n";

			// Open panel-overlay div
			$output .= "\t" .'<div class="panel-overlay">' . "\n";

			// Display the page title
			$output .= "\t\t" . '<h3>'. get_the_title() .'</h3>' . "\n";
			
			// Do we have a dfcg-desc?
			if( get_post_meta($post->ID, "dfcg-desc", true) ) {
				$output .= "\t\t" . '<p>'. get_post_meta($post->ID, "dfcg-desc", true) . '</p>' . "\n";
			
			// we have All cats
			} elseif( $dfcg_cat == '' ) {
				
				// TODO: Get the category ID so that cat descriptions can be displayed for ALL cats
				
				// Default description exists
				if( $dfcg_options['defimagedesc'] !== '' ) {
					// Show the default description
					$output .= "\t\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>' . "\n";
				
				} else {
					// There is no description
					$output .= "\t\t" . '<p></p>' . "\n";
					$output .= "\t" . $dfcg_errmsgs['3'] . "\n";
				}
				
			// we have Single cat and category desc exists
			} elseif( category_description($dfcg_cat) !== '') {
				// a category description exists
				$output .= "\t\t" . category_description($dfcg_cat) . "\n";
				
			// we have a Single cat and a default description exists
			} elseif( $dfcg_options['defimagedesc'] !== '') {
				// a default description exists
				$output .= "\t\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>' . "\n";
			
			// we have Single cat and no description
			} else {
				// Show the error message
				$output .= "\t\t" . '<p></p>' . "\n";
				$output .= "\t" . $dfcg_errmsgs['3'] . "\n";
			}

			// Close the panel-overlay div
			$output .= "\t" . '</div>'."\n";
			
			// Close the panel div
			$output .= '</div>'."\n\n";

		endwhile;

		/*	Compare original number of Posts with the WP_Query object output
			to check that the number of gallery images is the same.	If it's not the
			same, then there are less Posts in this Category than the posts-number Setting */

		if( $counter - $dfcg_posts_number !== 0 ) {
			$output .= "\n" . $dfcg_errmsgs['7'] . "\n";
			if( $dfcg_options['errors'] == "true" ) {
				$output .= '<!-- ' . __('Number of Posts to display as per DCG Settings = ', DFCG_DOMAIN) . $dfcg_posts_number . ' -->' . "\n";
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
*	@param	$dfcg_options				Array of DCG options from wp_postmeta
*	@param	$dfcg_errorimgurl			Absolute URL to DCG Error image
*	@param	$dfcg_pages_selected		DCG option: comma separated list of Page IDs
*	@param	$dfcg_pages_selected_count	No. of pages specified in DCG options
*	@param	$dfcg_temp					Array of Page IDs from comma separated list. Only used in order to run count() function
*	@param	$dfcg_pages_found			$wpdb query object
*	@param	$dfcg_pages_found_count		Number of Pages in $wpdb query object
*	@param	$counter					Incremented variable to add image # in HTML comments markup
*
*	@since	3.0
*/
function dfcg_jq_pages_method_gallery() {

	global $dfcg_options, $dfcg_errmsgs, $dfcg_errorimgurl;

	// Set $dfcg_baseimgurl variable for image URL
	$dfcg_baseimgurl = dfcg_baseimgurl();
	
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
		$dfcg_pages_selected = explode(",", $dfcg_pages_selected);
		/* Store how many IDs were in list */
		$dfcg_pages_selected_count = count($dfcg_pages_selected);

		/* If only one Page ID has been specified in Settings: print error messages and exit */
		if( $dfcg_pages_selected_count < 2 ) {
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
	$dfcg_pages_found = $wpdb->get_results(
  		sprintf("SELECT ID,post_title FROM $wpdb->posts WHERE $wpdb->posts.ID IN( %s )", implode(',', array_map( 'intval', $dfcg_pages_selected ) ) )
		);
											
	/* If we have results from the query */
	if( $dfcg_pages_found ) {

		// Validation: Check how many Pages the query found
		// The results if this are printed to Page Source further down
		$dfcg_pages_found_count = count($dfcg_pages_found);
	
		// If less than 2, print error messages and exit function
		if( $dfcg_pages_found_count < 2 ) {
			$output .= $dfcg_errmsgs['9'] . "\n";
			if( $dfcg_options['errors'] == "true" ) {
				$output .= '<!-- ' . __('Number of Pages selected in DCG Settings = ', DFCG_DOMAIN) . $dfcg_pages_selected_count . ' -->' . "\n";
				$output .= '<!-- ' . __('Number of Pages found = ', DFCG_DOMAIN) . $dfcg_pages_found_count . ' -->' . "\n\n";
			}
			echo $output;
			return;
		}

		// Set a counter to add an image # in the markup page source
		$counter = 0;

		// Start the gallery markup
		$output .= "\n" . '<div id="dfcg_images" class="galleryview"><!-- Start of Dynamic Content Gallery output -->'."\n\n";

		foreach( $dfcg_pages_found as $dfcg_page_found ) :

			// Increment the counter
			$counter++;

			// Open the imageElement div
			$output .= '<div class="panel"><!-- DCG Image #' . $counter . '-->' . "\n";

			// Link - additional code courtesy of Martin Downer
			if( get_post_meta($dfcg_page_found->ID, "dfcg-link", true) ){
				// We have an external/manual link
				$output .= "\t" . '<a href="'. get_post_meta($dfcg_page_found->ID, "dfcg-link", true) .'" rel="bookmark">' . "\n";
			
			} else {
				$output .= "\t" . '<a href="'. get_permalink($dfcg_page_found->ID) .'" rel="bookmark">' . "\n";
			}
			
			// Get the dfcg-image
			if( get_post_meta($dfcg_page_found->ID, "dfcg-image", true) ) {
				$output .= "\t\t" . '<img src="'. $dfcg_baseimgurl . get_post_meta($dfcg_page_found->ID, "dfcg-image", true) .'" alt="'. $dfcg_page_found->post_title .'" />' . "\n";
        		
				// Note: No Error message will be triggered if dfcg-image is set but URL is wrong, ie image gives 404.
			} elseif( !empty($dfcg_options['defimgpages']) ) {
				// Display the "Pages" default image
				$output .= "\t\t" . '<img src="'. $dfcg_options['defimgpages'] .'" alt="'. $dfcg_page_found->post_title .'" />' . "\n";
        		
        		// Note: No Error message will be triggered if defimgpages is set but URL is wrong, ie 404.
			} else {
				// Display Pages Error image
				$output .= "\t\t" . '<img src="'. $dfcg_errorimgurl .'" alt="'. $dfcg_page_found->post_title .'" />' . "\n";
        		$output .= "\t" . $dfcg_errmsgs['4'] . "\n";
			}
			
			// Close the link XHTML tag
			$output .= "\t" . '</a>' . "\n";

			// Open panel-overlay div
			$output .= "\t" .'<div class="panel-overlay">' . "\n";
			
			// Display the page title
			$output .= "\t\t" . '<h3>'. $dfcg_page_found->post_title .'</h3>' . "\n";

			// Do we have a dfcg-desc?
			if( get_post_meta($dfcg_page_found->ID, "dfcg-desc", true) ) {
				$output .= "\t\t" . '<p>' . get_post_meta($dfcg_page_found->ID, "dfcg-desc", true) . '</p>' . "\n";

			} elseif( $dfcg_options['defimagedesc'] !== '' ) {
				// Show the default description
				$output .= "\t\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>' . "\n";

			} else {
				// Show the error message
				$output .= "\t\t" . '<p></p>' . "\n";
				$output .= "\t" . $dfcg_errmsgs['3'] . "\n";
			}

			// Close the panel div
			$output .= '</div>'."\n\n";

		endforeach;

		/*	Compare $dfcg_pages_selected_count with the db query object $dfcg_pages_found_count
			to check that the number of gallery images is the same.	If it's not the
			same, then one or more of the selected Page IDs are not valid Pages */

		if( $dfcg_pages_found_count !== $dfcg_pages_selected_count) {
			$output .= "\n" . $dfcg_errmsgs['5'] . "\n";
			if( $dfcg_options['errors'] == "true" ) {
				$output .= '<!-- ' . __('Number of Pages selected in DCG Settings = ', DFCG_DOMAIN) . $dfcg_pages_selected_count . ' -->' . "\n";
				$output .= '<!-- ' . __('Number of Pages found = ', DFCG_DOMAIN) . $dfcg_pages_found_count . ' -->' . "\n\n";
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