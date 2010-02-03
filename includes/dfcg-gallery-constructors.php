<?php
/**
* Front-end - These are the constructor functions which produce the XHTML markup when using Mootools
*
* @copyright Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
* @package dynamic_content_gallery
* @version 3.2.1
*
* @info These are the key functions which produce the markup output
* @info for the gallery to run using mootools.
*
* @info One function for each of the 3 populate-methods.
*		- Multi Option		dfcg_multioption_method_gallery()
*		- One Category		dfcg_onecategory_method_gallery()
*		- Pages				dfcg_pages_method_gallery()
*
* @since 3.0
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}



/**
* This function builds the gallery from Multi Option options
*
* @uses	dfcg_errors_output()		Gets all Error Messages, if errors are on (see dfcg-gallery-errors.php)
* @uses	dfcg_baseimgurl()			Determines whether FULL or Partial URL applies (see dfcg-gallery-core.php)
* @uses	dfcg_query_list()			Builds array of cat/off pairs for WP_Query (see dfcg-gallery-core.php)
*
* @param array	$dfcg_errmsgs		Array of error messages. Output of dfcg_errors_output()
* @param string $baseimgurl			Base URL for images. Empty if FULL URL. Output of dfcg_baseurl()
* @param string $defimgmulti		Holds DCG Option: defimgmulti, URL to default images folder
* @param string $filepath			Absolute path to default images directory
* @param array 	$query_list			Array of cat/off pairs. Output of dfcg_query_list()
* @param string	$selected_slots		Number of pairs in $query_list array
* @param string	$counter			Stores how many times $query_list is run through foreach loop
* @param string $counter1			Stores how many times WP_Query is run (to do comparison for missing posts)
* @param string $counter2			Added 3.2: Stores how many posts are Excluded by _dfcg-exclude custom field being true
* @param string $filename			Stores absolute path, incl filename, of category default image
*
* @global array $dfcg_options Plugin options array from db
* @global array $dfcg_postmeta_upgrade options array from db
* @global array $post Post object
*
* @since 3.2
*/
function dfcg_multioption_method_gallery() {

	global $dfcg_options, $dfcg_postmeta_upgrade, $post;
	
	if( $dfcg_postmeta_upgrade['upgraded'] == 'completed' ) {
		$desc = '_dfcg-desc';
		$image = '_dfcg-image';
		$link = '_dfcg-link';
	} else {
		$desc = 'dfcg-desc';
		$image = 'dfcg-image';
		$link = 'dfcg-link';
	}
	
	// Build array of error messages (NULL if Errors are off)
	$dfcg_errmsgs = NULL;
	if( function_exists('dfcg_errors_output') ) {
		$dfcg_errmsgs = dfcg_errors_output();
	}
	
	// Set $baseimgurl variable for image URL
	$baseimgurl = dfcg_baseimgurl();

	// Get the absolute URL to the default "Category" images folder from Settings
	$defimgmulti = $dfcg_options['defimgmulti'];

	// Added 3.1: Strip domain name from URL, replace with ABSPATH. Default folder can now be anywhere
	$filepath = str_replace( get_bloginfo('siteurl'), ABSPATH, $defimgmulti );
	
	$query_list = dfcg_query_list();

	/* Collect some info about our array, for later */
	$selected_slots = count($query_list);
	
	/* Validate that $query_list has at least 2 items for gallery to work */
	if( $selected_slots < 2 ) {
		$output = $dfcg_errmsgs['11'] . "\n";
		echo $output;
		return;
	}


	// Start the Gallery Markup
	$output = "\n" . '<div id="myGallery"><!-- Start of Dynamic Content Gallery -->';


	// Validation of output - not much needs to be done. 
	// Clicking Save in Settings will automatically assign a valid cat to each image slot
	// because wp_dropdown_categories is set to "hide empty".
	// Any empty post selects will be ignored, as per foreach loop below,
	// therefore the only risk is that a post select is entered for a post
	// which doesn't exist. Eg, post #4, but there are only 3 in that cat.
	// This situation is dealt with by the counters...
	// We also validate that there are at least 2 post selects (see above)
	 
	// Set 3 counters to find out how many Posts are supposed to be output
	// by WP_Query, and how many posts are actually found by WP_Query, and how many posts were Excluded
	// $counter:	Adds an image # in the markup page source
	//				Counts how many times we go through $query_list foreach loop
	//				This is pre-WP_Query
	// $counter1:	Counts how many times WP_Query outputs anything
	// $counter2:	Counts how many Excluded Posts are found
	//				We can then compare the three values to see if anything is missing
	$counter = 0;
	$counter1 = 0;
	$counter2 = 0;

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
					$output .= "\n" . '</div><!-- End of Dynamic Content Gallery output -->' . "\n";
					echo $output;
					return;
				
				else :
					while($recent->have_posts()) : $recent->the_post();
				
						// Exclude the post if _dfcg-exclude custom field is true
						if( get_post_meta($post->ID, '_dfcg-exclude', true) == 'true' ) {
							$output .= "\n\n" . '<!-- DCG Image #' . $counter . ' has been Excluded by user -->';
							$counter2++;
							continue;
						}
						
						// Increment the second counter
						$counter1++;
					
						// Open the imageElement div
						$output .= "\n\n" . '<div class="imageElement"><!-- DCG Image #' . $counter . ' -->';

						// Display the page title
						$output .= "\n\t" . '<h3>' . get_the_title() . '</h3>';

						// Get the description
						if( $dfcg_options['desc-method'] == 'none' ) {
							// we don't want any descriptions (note: smoothgallery needs <p> tags or won't work)
							$output .= "\n\t" . '<p></p>';
						
						} elseif( $dfcg_options['desc-method'] == 'manual' ) {
						
							if( get_post_meta($post->ID, $desc, true) ){
								// We have a Custom field description
								$output .= "\n\t" . '<p>' . get_post_meta($post->ID, $desc, true) . '</p>';

							} elseif( category_description($key) !== '' ) {
								// show the category description (note: no <p> tags required)
								$output .= "\n\t" . category_description($key);

							} elseif( $dfcg_options['defimagedesc'] !== '' ) {
								// or show the default description
								$output .= "\n\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>';
							
							} else {
								// Fall back to Auto custom excerpt
								$chars = $dfcg_options['max-char'];
								$more = $dfcg_options['more-text'];
								$auto_text = dfcg_the_content_limit( $chars, $more );
								$output .= "\n\t" . $auto_text;
							}
						
						} else {
							// We're using Auto custom excerpt
							$chars = $dfcg_options['max-char'];
							$more = $dfcg_options['more-text'];
							$auto_text = dfcg_the_content_limit( $chars, $more );
							$output .= "\n\t" . $auto_text;
						}

       					// Link - additional code courtesy of Martin Downer
						if( get_post_meta($post->ID, $link, true) ){
							// We have an external/manual link
							$output .= "\n\t" . '<a href="'. get_post_meta($post->ID, $link, true) .'" title="Read More" class="open"></a>';
							
						} else {
							$output .= "\n\t" . '<a href="'. get_permalink() .'" title="Read More" class="open"></a>';
						}

						// Get the images
						if( get_post_meta($post->ID, $image, true) ) {
							$output .= "\n\t" . '<img src="'. $baseimgurl . get_post_meta($post->ID, $image, true) .'" alt="'. get_the_title() .'" class="full" />';
        					$output .= "\n\t" . '<img src="'. $baseimgurl . get_post_meta($post->ID, $image, true) .'" alt="'. get_the_title() .'" class="thumbnail" />';
							// Note: No Error message will be triggered if _dfcg-image is set but URL is wrong, ie image gives 404
						} else {
							// Path to Default Category image
							$filename = $filepath . $key . '.jpg';
							// Does category image exist?
							if( file_exists($filename) ) {
								$output .= "\n\t" . '<img src="'. $defimgmulti . $key .'.jpg" alt="'. get_the_title() .'" class="full" />';
        						$output .= "\n\t" . '<img src="'. $defimgmulti . $key .'.jpg" alt="'. get_the_title() .'" class="thumbnail" />';
							} else {
								$output .= "\n\t" . '<img src="'. DFCG_ERRORIMGURL .'" alt="'. get_the_title() .'" class="full" />';
        						$output .= "\n\t" . '<img src="'. DFCG_ERRORIMGURL .'" alt="'. get_the_title() .'" class="thumbnail" />';
        						$output .= "\n\t" . $dfcg_errmsgs['4'];
							}
						}

						// Close ImageElement div
						$output .= "\n" . '</div>';

					endwhile; 

				endif; 	// End WP_Query if($recent... ) test
			} 			// End inner foreach loop
		} 				// End conditional check that $value is an array
	} 					// End outer foreach loop
			
	// Compare the 3 counters to see if outputs were as expected.
	// $counter = number of Post Selects in Settings. Also sets the "Image #" comment in Page Source.
	// $counter1 = number of WP_Query outputs.
	// $counter2 = number of excluded posts.
	// If these values are not the same, WP_Query couldn't find a Post. 
	if( $counter - $counter1 - $counter2 !== 0 ) {
		$output .= "\n\n" . $dfcg_errmsgs['12'];
		if( $dfcg_options['errors'] == "true" ) {
			$output .= "\n" . '<!-- ' . __('Number of Posts to display as per DCG Settings = ', DFCG_DOMAIN) . $counter . ' -->';
			$output .= "\n" . '<!-- ' . __('Number of Posts found = ', DFCG_DOMAIN) . $counter1 . ' -->';
			$output .= "\n" . '<!-- ' . __('Number of Posts excluded by user = ', DFCG_DOMAIN) . $counter2 . ' -->';
		}
	}
	
	// End of the gallery markup
	$output .= "\n\n" . '</div><!-- End of Dynamic Content Gallery output -->' . "\n\n";
	
	// Output the Gallery
	echo $output;
}


/**
* This function builds the gallery from One Category options
*
* @uses	dfcg_errors_output()		Gets all Error Messages, if errors are on (see dfcg-gallery-errors.php)
* @uses	dfcg_baseimgurl()			Determines whether FULL or Partial URL applies (see dfcg-gallery-core.php)
*
* @param array	$dfcg_errmsgs		Array of error messages. Output of dfcg_errors_output()
* @param string $baseimgurl			Base URL for images. Empty if FULL URL. Output of dfcg_baseurl()
* @param string $posts_number		DCG option: number of posts to display
* @param string $cat_selected		DCG option: selected category
* @param string $defimgurl			DCG option: URL to default images folder
* @param string $filepath			Absolute path to default images directory
* @param string	$def_img_name		Default image filename
* @param string	$filename			Stores absolute path, incl filename, of category default image
* @param array	$recent				WP_Query object
* @param string	$counter			Incremented variable to find number of posts output by wp_query
*
* @global array $dfcg_options Plugin options array from db
* @global array $dfcg_postmeta_upgrade options array from db
* @global array $post Post object
*
* @since 3.2
*/
function dfcg_onecategory_method_gallery() {

	global $post, $dfcg_options, $dfcg_postmeta_upgrade;
	
	if( $dfcg_postmeta_upgrade['upgraded'] == 'completed' ) {
		$desc = '_dfcg-desc';
		$image = '_dfcg-image';
		$link = '_dfcg-link';
	} else {
		$desc = 'dfcg-desc';
		$image = 'dfcg-image';
		$link = 'dfcg-link';
	}
	
	// Build array of error messages (NULL if Errors are off)
	$dfcg_errmsgs = NULL;
	if( function_exists('dfcg_errors_output') ) {
		$dfcg_errmsgs = dfcg_errors_output();
	}
	
	// Set $baseimgurl variable for image URL
	$baseimgurl = dfcg_baseimgurl();
	
	/* Get the number of Posts to display */
	// No need to check that there is a minimum of 2 posts, thanks to dropdown in Settings
	$posts_number = $dfcg_options['posts-number'];

	/* Get the Selected Category */
	// No need to check Category existence, or whether it has Posts,
	// thanks to use of dropdown in Settings
	$cat_selected = $dfcg_options['cat-display'];

	/* Get the URL to the default "Category" images folder from Settings */
	$defimgurl = $dfcg_options['defimgonecat'];

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
	$recent = new WP_Query("cat=$cat_selected&showposts=$posts_number");
	// Do we have any posts?
	if ( $recent->have_posts() ) {

		// Set a counter to find out how many Posts are found in the WP_Query
		// Also used to add an image # in the markup page source
		$counter = 0;
		$counter2 = 0;

		// Start the gallery markup
		$output = "\n" . '<div id="myGallery"><!-- Start of Dynamic Content Gallery output -->';

		while($recent->have_posts()) : $recent->the_post();

			// Increment the counter
			$counter++;
			
			// Exclude the post if _dfcg-exclude custom field is true
			if( get_post_meta($post->ID, '_dfcg-exclude', true) == 'true' ) {
				$output .= "\n\n" . '<!-- DCG Image #' . $counter . ' has been Excluded by user -->';
				$counter2++;
				continue;
			}

			// Open the imageElement div
			$output .= "\n\n" . '<div class="imageElement"><!-- DCG Image #' . $counter . ' -->';

			// Display the page title
			$output .= "\n\t" . '<h3>'. get_the_title() .'</h3>';
			
			// Get the description
			if( $dfcg_options['desc-method'] == 'none' ) {
				// we don't want any descriptions (note: smoothgallery needs <p> tags or won't work)
				$output .= "\n\t" . '<p></p>';
				
			} elseif( $dfcg_options['desc-method'] == 'manual' ) {
			
				// Do we have a _dfcg-desc?
				if( get_post_meta($post->ID, $desc, true) ) {
					$output .= "\n\t" . '<p>'. get_post_meta($post->ID, $desc, true) . '</p>';
			
				// we have All cats
				} elseif( $cat_selected == '' ) {
				
					// TODO: Cat descriptions are not used with ALL cats. Get the category ID so that cat descriptions can be displayed for ALL cats
				
					// Default description exists
					if( $dfcg_options['defimagedesc'] !== '' ) {
						// Show the default description
						$output .= "\n\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>';
				
					} else {
						// We're using Auto custom excerpt as fallback
						$chars = $dfcg_options['max-char'];
						$more = $dfcg_options['more-text'];
						$auto_text = dfcg_the_content_limit( $chars, $more );
						$output .= "\n\t" . $auto_text;
					}
				
				// we have Single cat and category desc exists
				} elseif( category_description($cat_selected) !== '') {
					// a category description exists
					$output .= "\n\t" . category_description($cat_selected);
				
				// we have a Single cat and a default description exists
				} elseif( $dfcg_options['defimagedesc'] !== '') {
					// a default description exists
					$output .= "\n\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>';
			
				// we have Single cat and no description
				} else {
					// We're using Auto custom excerpt as fallback
					$chars = $dfcg_options['max-char'];
					$more = $dfcg_options['more-text'];
					$auto_text = dfcg_the_content_limit( $chars, $more );
					$output .= "\n\t" . $auto_text;
				}
				
			} else {
				// We're using Auto custom excerpt
				$chars = $dfcg_options['max-char'];
				$more = $dfcg_options['more-text'];
				$auto_text = dfcg_the_content_limit( $chars, $more );
				$output .= "\n\t" . $auto_text;
			}

			// Link - additional code courtesy of Martin Downer
			if( get_post_meta($post->ID, $link, true) ){
				// We have an external/manual link
				$output .= "\n\t" . '<a href="'. get_post_meta($post->ID, $link, true) .'" title="Read More" class="open"></a>';
							
			} else {
				$output .= "\n\t" . '<a href="'. get_permalink() .'" title="Read More" class="open"></a>';
			}

			// Get the _dfcg-image
			if( get_post_meta($post->ID, $image, true) ) {
				$output .= "\n\t" . '<img src="'. $baseimgurl . get_post_meta($post->ID, $image, true) .'" alt="'. get_the_title() .'" class="full" />';
        		$output .= "\n\t" . '<img src="'. $baseimgurl . get_post_meta($post->ID, $image, true) .'" alt="'. get_the_title() .'" class="thumbnail" />';
				// Note: No Error message will be triggered if _dfcg-image is set but URL is wrong, ie 404.
			
			} elseif( file_exists($filename) ) {
				// Display the "Category" default image
				$output .= "\n\t" . '<img src="'. $defimgurl . $def_img_name .'" alt="'. get_the_title() .'" class="full" />';
        		$output .= "\n\t" . '<img src="'. $defimgurl . $def_img_name .'" alt="'. get_the_title() .'" class="thumbnail" />';
			} else {
				$output .= "\n\t" . '<img src="'. DFCG_ERRORIMGURL .'" alt="'. get_the_title() .'" class="full" />';
        		$output .= "\n\t" . '<img src="'. DFCG_ERRORIMGURL .'" alt="'. get_the_title() .'" class="thumbnail" />';
				$output .= "\n\t" . $dfcg_errmsgs['4'];
			}

			// Close the ImageElement div
			$output .= "\n" . '</div>';

		endwhile;

		/*	Compare number of Posts selected as per Settings ($posts_number) with the WP_Query object output ($counter)
			to check that the number of gallery images is the same.	If it's not the
			same, then there are less Posts in this Category than the posts-number Setting */

		if( $posts_number - $counter !== 0 ) {
			$output .= "\n\n" . $dfcg_errmsgs['7'];
		}
		
		// Print out stats
		if( $dfcg_options['errors'] == "true" ) {
			$post_found = $counter - $counter2;
			$output .= "\n" . '<!-- ' . __('Number of Posts to display as per DCG Settings = ', DFCG_DOMAIN) . $posts_number . ' -->';
			$output .= "\n" . '<!-- ' . __('Number of Posts found = ', DFCG_DOMAIN) . $post_found . ' -->';
			$output .= "\n" . '<!-- ' . __('Number of Posts excluded by user = ', DFCG_DOMAIN) . $counter2 . ' -->';
		}

		// End of the gallery markup
		$output .= "\n\n" . '</div><!-- End of Dynamic Content Gallery output -->'."\n\n";

	} else {
		/* Oops! The WP_Query couldn't find any Posts */
		// Theoretically this can never happen unless there is a WP problem
		$output = "\n" . $dfcg_errmsgs['8'];
	}
	
	// Output the Gallery
	echo $output;
}


/**
* This function builds the gallery from Pages options
*
* @uses	dfcg_errors_output()		Gets all Error Messages, if errors are on (see dfcg-gallery-errors.php)
* @uses	dfcg_baseimgurl()			Determines whether FULL or Partial URL applies (see dfcg-gallery-core.php)
*
* @param array	$dfcg_errmsgs			Array of error messages. Output of dfcg_errors_output()
* @param string $baseimgurl				Base URL for images. Empty if FULL URL. Output of dfcg_baseurl()
* @param string $pages_selected			DCG option: comma separated list of Page IDs
* @param string	$pages_selected_count	No. of pages specified in DCG options
* @param array	$pages_found			$wpdb query object
* @param string	$pages_found_count		Number of Pages in $wpdb query object
* @param string	$counter				Incremented variable to add image # in HTML comments markup
*
* @global array $dfcg_options Plugin options array from db
* @global array $dfcg_postmeta_upgrade options array from db
* @global array $wpdb WP $wpdb database object
*
* @since 3.2
*/
function dfcg_pages_method_gallery() {

	global $dfcg_options, $dfcg_postmeta_upgrade;
	
	if( $dfcg_postmeta_upgrade['upgraded'] == 'completed' ) {
		$desc = '_dfcg-desc';
		$image = '_dfcg-image';
		$link = '_dfcg-link';
	} else {
		$desc = 'dfcg-desc';
		$image = 'dfcg-image';
		$link = 'dfcg-link';
	}
	
	// Build array of error messages (NULL if Errors are off)
	$dfcg_errmsgs = NULL;
	if( function_exists('dfcg_errors_output') ) {
		$dfcg_errmsgs = dfcg_errors_output();
	}
	
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
			$output = $dfcg_errmsgs['1'] . "\n";
			echo $output;
			return;
		}

	} else {
		/* There are no Page IDs in Settings: print error messages and exit */
		$output = $dfcg_errmsgs['2'] . "\n";
		echo $output;
		return;
	}

	/* Instantiate the $wpdb object */
	global $wpdb;
	
	if( $dfcg_options['pages-sort-control'] == 'true' ) {
	
		/* User defined sort order for Pages */
		$sort = esc_attr('_dfcg-sort');
	
		/* Do the query - with thanks to Austin Matzko for sprintf help */
		$pages_found = $wpdb->get_results(
  			sprintf("SELECT ID,post_title,post_content
				FROM $wpdb->posts
				LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id AND $wpdb->postmeta.meta_key = '%s'
				WHERE $wpdb->posts.ID IN( %s )
				ORDER BY $wpdb->postmeta.meta_value ASC", $sort, implode(',', array_map( 'intval', $pages_selected )) )
			);
	
	} else {
		
		/* Do the query - with thanks to Austin Matzko for sprintf help */
		/* Note: simplified query without custom sort ordering */
		$pages_found = $wpdb->get_results(
  			sprintf("SELECT ID,post_title,post_content FROM $wpdb->posts WHERE $wpdb->posts.ID IN( %s )", implode(',', array_map( 'intval', $pages_selected ) ) )
			);
	}
	
	/* If we have results from the query */
	if( $pages_found ) {

		// Validation: Check how many Pages the query found
		// The results if this are printed to Page Source further down
		$pages_found_count = count($pages_found);
	
		// If less than 2, print error messages and exit function
		if( $pages_found_count < 2 ) {
			$output = "\n" . $dfcg_errmsgs['9'];
			if( $dfcg_options['errors'] == "true" ) {
				$output .= "\n" . '<!-- ' . __('Number of Pages selected in DCG Settings = ', DFCG_DOMAIN) . $pages_selected_count . ' -->';
				$output .= "\n" . '<!-- ' . __('Number of Pages found = ', DFCG_DOMAIN) . $pages_found_count . ' -->';
			}
			echo $output;
			return;
		}

		// Set a counter to add an image # in the markup page source
		$counter = 0;

		// Start the gallery markup
		$output = "\n" . '<div id="myGallery"><!-- Start of Dynamic Content Gallery output -->';

		foreach( $pages_found as $page_found ) :

			// Increment the image counter
			$counter++;

			// Open the imageElement div
			$output .= "\n" . '<div class="imageElement"><!-- DCG Image #' . $counter . '-->';

			// Display the page title
			$output .= "\n\t" . '<h3>'. $page_found->post_title .'</h3>';

			// Get the description
			if( $dfcg_options['desc-method'] == 'none' ) {
				// we don't want any descriptions (note: smoothgallery needs <p> tags or won't work)
				$output .= "\n\t" . '<p></p>';
			
			} elseif( $dfcg_options['desc-method'] == 'manual' ) {
			
				// Do we have a _dfcg-desc?
				if( get_post_meta($page_found->ID, $desc, true) ) {
					$output .= "\n\t" . '<p>' . get_post_meta($page_found->ID, $desc, true) . '</p>';

				} elseif( $dfcg_options['defimagedesc'] !== '' ) {
					// Show the default description
					$output .= "\n\t" . '<p>' . stripslashes( $dfcg_options['defimagedesc'] ) . '</p>';

				} else {
					// We're using Auto custom excerpt as fallback
					$page_content = $page_found->post_content;
					$page_id = $page_found->ID;
					$chars = $dfcg_options['max-char'];
					$more = $dfcg_options['more-text'];
					$auto_text = dfcg_the_content_limit( $chars, $more, $page_content, $page_id );
					$output .= "\n\t" . $auto_text;
				}
				
			} else {
				// We're using Auto custom excerpt
				$page_content = $page_found->post_content;
				$page_id = $page_found->ID;
				$chars = $dfcg_options['max-char'];
				$more = $dfcg_options['more-text'];
				$auto_text = dfcg_the_content_limit( $chars, $more, $page_content, $page_id );
				$output .= "\n\t" . $auto_text;
			}

			// Link - additional code courtesy of Martin Downer
			if( get_post_meta($page_found->ID, $link, true) ){
				// We have an external/manual link
				$output .= "\n\t" . '<a href="'. get_post_meta($page_found->ID, $link, true) .'" title="Read More" class="open"></a>';
							
			} else {
				$output .= "\n\t" . '<a href="'. get_permalink($page_found->ID) .'" title="Read More" class="open"></a>';
			}

			// Get the _dfcg-image
			if( get_post_meta($page_found->ID, $image, true) ) {
				$output .= "\n\t" . '<img src="'. $baseimgurl . get_post_meta($page_found->ID, $image, true) .'" alt="'. $page_found->post_title .'" class="full" />';
        		$output .= "\n\t" . '<img src="'. $baseimgurl . get_post_meta($page_found->ID, $image, true) .'" alt="'. $page_found->post_title .'" class="thumbnail" />';
				// Note: No Error message will be triggered if _dfcg-image is set but URL is wrong, ie 404.
			
			} elseif( !empty($dfcg_options['defimgpages']) ) {
				// Display the "Pages" default image
				$output .= "\n\t" . '<img src="'. $dfcg_options['defimgpages'] .'" alt="'. $page_found->post_title .'" class="full" />';
        		$output .= "\n\t" . '<img src="'. $dfcg_options['defimgpages'] .'" alt="'. $page_found->post_title .'" class="thumbnail" />';
        		// Note: No Error message will be triggered if defimgpages is set but URL is wrong, ie 404.
			
			} else {
				// Display Pages Error image
				$output .= "\n\t" . '<img src="'. DFCG_ERRORIMGURL .'" alt="'. $page_found->post_title .'" class="full" />';
        		$output .= "\n\t" . '<img src="'. DFCG_ERRORIMGURL .'" alt="'. $page_found->post_title .'" class="thumbnail" />';
				$output .= "\n\t" . $dfcg_errmsgs['4'];
			}

			// Close the ImageElement div
			$output .= "\n" . '</div>';

		endforeach;

		/*	Compare $pages_selected_count with the db query object $pages_found_count
			to check that the number of gallery images is the same.	If it's not the
			same, then one or more of the selected Page IDs are not valid Pages */

		if( $pages_found_count !== $pages_selected_count) {
			$output .= "\n" . $dfcg_errmsgs['5'];
			if( $dfcg_options['errors'] == "true" ) {
				$output .= "\n" . '<!-- ' . __('Number of Pages selected in DCG Settings = ', DFCG_DOMAIN) . $pages_selected_count . ' -->';
				$output .= "\n" . '<!-- ' . __('Number of Pages found = ', DFCG_DOMAIN) . $pages_found_count . ' -->';
			}
		}

		// End of the gallery markup
		$output .= "\n" . '</div><!-- End of Dynamic Content Gallery output -->' . "\n\n";
		
	} else {
		/* Oops! Either none of the Page IDs are valid or the db query failed in some way */
		$output = "\n" . $dfcg_errmsgs['6'];
	}
	
	// Output the Gallery
	echo $output;
}