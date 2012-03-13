<?php
/**
 * Front-end - These are the constructor functions which produce the XHTML markup when using Mootools
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2012
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * @info One function for each of the 4 populate-methods.
 *		- Multi Option		dfcg_multioption_method_gallery()
 *		- One Category		dfcg_onecategory_method_gallery()
 *		- Custom Post Type	dfcg_onecategory_method_gallery()
 *		- ID Method			dfcg_id_method_gallery()
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
 * @since 3.3
 */

/* Prevent direct access to this file */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}



/**
 * This function builds the gallery from Multi Option options
 *
 * @uses dfcg_postmeta_info()		Builds array of postmeta key names (see dfcg-gallery-core.php)
 * @uses dfcg_errors_output()		Gets all Error Messages, if errors are on (see dfcg-gallery-errors.php)
 * @uses dfcg_query_list()			Builds array of cat/off pairs for WP_Query (see dfcg-gallery-core.php)
 * @uses dfcg_grab_post_image()		Gets the first image attachment from the Post (see dfcg-gallery-core.php)
 *
 * @var array	$errmsgs			Output of dfcg_errors_output()
 * @var string 	$def_img_folder_path	Absolute path to default images directory
 * @var array 	$query_pairs			cat/off pairs. Output of dfcg_query_list()
 * @var int		$selected_slots			Number of pairs in $query_pair array
 * @var int		$counter				Stores how many times $query_list is run through foreach loop
 * @var int	 	$counter1				Stores how many times WP_Query is run (to do comparison for missing posts)
 * @var int	 	$counter2				Added 3.2: Stores how many posts are Excluded by _dfcg-exclude custom field being true
 * @var string	$title					Post/page title. Output of get_the_title()
 * @var string 	$slide_text_html		Slide Pane description text. Output of dfcg_get_desc()
 * @var string	$link					Image link data. Output of dfcg_get_link()
 * @var string 	$image					(array) Main Image data. Output of dfcg_get_image()
 * @var string	$thumb_html				Thumbnail HTML. Output of dfcg_get_thumbnail()
 *
 * @global $post (object) Post object
 * @global $dfcg_options (array) Plugin options array from db
 * @global $dfcg_postmeta (array) - declared so that nested functions get access to it
 *
 * @return $output - string - all XHTML output for the gallery
 * @since 3.2
 * @updated 4.0
 */
function dfcg_flex_multioption_method_gallery() {

	global $post, $dfcg_options, $dfcg_postmeta;
	
	// Build array of error messages (NULL if Errors are off). Reset to NULL, just in case Settings have been changed
	$errmsgs = NULL;
	if( function_exists( 'dfcg_errors_output' ) ) {
		$errmsgs = dfcg_errors_output();
	}
	
	$query_pairs = dfcg_query_list();

	/* Collect some info about our array, for later */
	$selected_slots = count($query_pairs);
	
	/* Validate that $query_list has at least 2 items for gallery to work */
	if( $selected_slots < 2 ) {
		$output = $errmsgs['10'];
		return $output;
	}

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

	
	// Start the Gallery Markup
	$output = "\n" . '<div id="myGallery"><!-- Start #myGallery and Dynamic Content Gallery mootools gallery -->';

	
	/* Now loop through our array of all the cat/post selects and run the WP_Queries */
	foreach ($query_pairs as $query_pair) {
	
		// Go down into inner arrays which contain the cat/offset pairs
		if( !is_array($query_pair) ) {
			/* Oops! $value isn't an array */
			// Theoretically this can never happen unless there is a WP problem
			$output .= "\n\n" . '</div><!-- end #myGallery and Dynamic Content Gallery mootools gallery -->';
			$output .= "\n" . $errmsgs['11'];
			
			return $output;
		}
		
		// Increment the counter
		$counter++;
		
		// Loop through the inner array (this only happens once before passing back to the outer foreach loop
		foreach ($query_pair as $category_id => $post_select) {
					
			// Now run the query using $key for cat and $value for offset
			$recent = new WP_Query("cat=$category_id&showposts=1&offset=$post_select");
				
			// Do we have any posts? If this is the first loop and no post is found, we need to abort
			// because the gallery won't display. Although this check is performed on every loop, we
			// don't need to abort after Image slot #1 is tested.
			if( !$recent->have_posts() && $counter < 2 ) :
				$output .= "\n\n" . '</div><!-- end #myGallery and Dynamic Content Gallery mootools gallery -->';
				$output .= "\n" . $errmsgs['12'];
				
				return $output;
				
			else :
				while($recent->have_posts()) : $recent->the_post();
				
				// Exclude the post if _dfcg-exclude custom field is true
				if( get_post_meta($post->ID, $dfcg_postmeta['exclude'], true) == 'true' ) {
					$output .= "\n\n" . '<!-- DCG Image #' . $counter . ' has been Excluded by user -->';
					$counter2++;
					continue;
				}
						
				// Increment the second counter
				$counter1++;

				// Get the page title
				$title = get_the_title();
					
				// Get the slide pane description (post ID, cat/Term ID)			
				$slide_text_html = dfcg_get_desc( $post->ID, $category_id );
				
				// Get the Image Link
				$link = dfcg_get_link( $post->ID, $title );
				
				// Get the Image
				$image = dfcg_get_image($post->ID, $category_id);
									
				// Get the thumbnail - uses Post Thumbnails if AUTO images are used
				$thumb_html = dfcg_get_thumbnail($post->ID, $image['src'], $title);
				
				// Open the imageElement div
				$output .= "\n\n" . '<div class="imageElement"><!-- DCG Image #' . $counter . ' -->';
				
				// Display the page title
				$output .= "\n\t" . '<h3>' . $title . '</h3>';
								
				// Output slide pane description
				$output .= "\n\t" . $slide_text_html;

				// Output Image Link
				$output .= "\n\t" . '<a href="'. $link['link_url'] .'" title="' . $link['link_title_attr'] . '" class="open"></a>';
					
				// Output Image and Thumbnail
				$output .= "\n\t" . '<img width="'.$image['w'].'" height="'.$image['h'].'" src="'. $image['src'].'" class="'.$image['class'].'" alt="'.$title.'" title="'.$title.'" />';
				$output .= "\n\t" . $thumb_html;
				$output .= $errmsgs[$image['msg']];
					
				// Close ImageElement div
				$output .= "\n" . '</div>';

				endwhile; 

			endif; 	// End WP_Query if($recent... ) test
		} 			// End inner foreach loop
	} 				// End outer foreach loop
			
	// Compare the 3 counters to see if outputs were as expected.
	// $counter = number of Post Selects in Settings. Also sets the "Image #" comment in Page Source.
	// $counter1 = number of WP_Query outputs.
	// $counter2 = number of excluded posts.
	// If these values are not the same, WP_Query couldn't find a Post. 
	if( $counter - $counter1 - $counter2 !== 0 ) {
		$output .= "\n" . $errmsgs['20'];
		if( $dfcg_options['errors'] == "true" ) {
			$output .= "\n\n" . '<!-- ' . __('Number of Posts to display as per DCG Settings = ', DFCG_DOMAIN) . $counter . ' -->';
			$output .= "\n" . '<!-- ' . __('Number of Posts found = ', DFCG_DOMAIN) . $counter1 . ' -->';
			$output .= "\n" . '<!-- ' . __('Number of Posts excluded by user = ', DFCG_DOMAIN) . $counter2 . ' -->';
		}
	}
	
	// End of the gallery markup
	$output .= "\n\n" . '</div><!-- end #myGallery and Dynamic Content Gallery mootools gallery -->';
	
	// Output the Gallery
	return $output;
}


/**
 * This function builds the gallery for One Category and Custom Post Type methods
 *
 *
 * @uses	dfcg_errors_output()		Gets all Error Messages, if errors are on (see dfcg-gallery-errors.php)
 * @uses	dfcg_baseimgurl()			Determines whether FULL or Partial URL applies (see dfcg-gallery-core.php)
 * @uses	dfcg_query_list()			Builds array of cat/off pairs for WP_Query (see dfcg-gallery-core.php)
 * @uses get_the_title()				WP function
 * @uses dfcg_get_desc()				Gets Slide Pane Description (see dfcg-gallery-core.php)
 * @uses dfcg_get_link()				Gets image link (see dfcg-gallery-core.php)
 * @uses dfcg_get_image()			Gets Main Image (see dfcg-gallery-core.php)
 * @uses dfcg_get_thumbnail()		Gets carousel Thumbnail (see dfcg-gallery-core.php)
 *
 *
 * @var array	$errmsgs				Array of error messages. Output of dfcg_errors_output()
 * @var string 	$posts_number			DCG option: number of posts to display (One Cat and CPT)
 * @var string 	$term_id				DCG option: selected category or taxonomy term (One Cat and CPT)
 * @var string 	$def_img_folder_url		DCG option: URL to default images folder (One Cat and CPT)
 * @var string	$query					Query string to be used by WP_QUERY (One Cat and CPT)
 * gvar string	$post_type				DCG option: Custom Post Type (CPT only)
 * @var string	$term_selected			Query string element for tax/term (CPT only)
 * @var string 	$def_img_folder_path	Absolute path to default images directory
 * @var object	$recent					WP_Query object
 * @var string	$counter				Stores how many times items in $recent wp_query loop
 * @var string	$counter2				Added 3.2: Stores how many posts are Excluded by _dfcg-exclude custom field being true
 * @var string	$title					Output of get_the_title(), Post title
 * @var string 	$slide_text				Output of dfcg_get_desc(), full XHTML markup for Slide Pane description text
 * @var array	$link					Output of dfcg_get_link(), array 'link_url', 'link_title_attr'
 * @var array	$image					Output of dfcg_get_image(), array 'src','w','h','class','error'
 * @var string	$thumb_html				Output of dfcg_get_thumbnail(), full XHTML markup for carousel thumbnail
 *
 * @global $post (object) WP Post object
 * @global $dfcg_options (array) Plugin options array from db
 * @global $dfcg_postmeta (array) Post meta keys - declared global so that this variable is available as a global in used functions
 *
 * @return $output - string - all XHTML output for the gallery
 * @since 3.2
 * @updated 4.0
 */
function dfcg_flex_onecategory_method_gallery() {

	global $post, $dfcg_options, $dfcg_postmeta;
	
	// Build array of error messages (NULL if Errors are off). Reset to NULL, just in case Settings have been changed
	$errmsgs = NULL;
	if( function_exists( 'dfcg_errors_output' ) ) {
		$errmsgs = dfcg_errors_output();
	}
	
	
	if( $dfcg_options['populate-method'] == 'one-category' ) {
		/* Get the number of Posts to display */
		// No need to check that there is a minimum of 2 posts, thanks to dropdown in Settings
		$posts_number = $dfcg_options['posts-number'];
		
		/* Get the Selected Category/Term */
		// No need to check Category existence, or whether it has Posts,
		// thanks to use of wp_dropdown_categories in Settings
		// With One Category Method, this is the cat ID
		$term_id = $dfcg_options['cat-display'];
		
		$term_selected = '';
		
		if( $term_id !== 'all' )
			$term_selected = $dfcg_options['cat-display'];
		
		/* The query */
		$query = array( 
					'cat' => $term_selected,
					'showposts' => $posts_number
				);

	}

	if( $dfcg_options['populate-method'] == 'custom-post' ) {
		/* Get the Custom Post Type */
		$post_type = $dfcg_options['cpt-name'];
		
		/* Get the number of Posts to display */
		// No need to check that there is a minimum of 2 posts, thanks to dropdown in Settings
		$posts_number = $dfcg_options['cpt-posts-number'];
		
		// Get Term ID, eg 65
		$term_id = $dfcg_options['cpt-term-id'];
		
		// Initialise variable for later on (prevents PHP undefined index errors)
		$term_selected = '';
		
		/* Get the Selected Category/Term */
		// In format "taxonomy_name=term_Name" eg ade_products=guitars
		if( $dfcg_options['cpt-tax-and-term'] == 'all' ) {
			$term_id = $dfcg_options['cpt-tax-and-term'];
			$term_selected = '';
		} else {
			$term_selected = '&' . $dfcg_options['cpt-tax-and-term'];
		}
			
		/* The query */
		$query = 'post_type=' . $post_type . $term_selected . '&showposts=' . $posts_number;
	}
	
	
	/* Do the WP_Query */
	$recent = new WP_Query( $query );
	
	// Do we have any posts?
	if ( !$recent->have_posts() ) {
		/* Oops! The WP_Query couldn't find any Posts */
		// Theoretically this can never happen unless there is a WP problem
		$output = "\n" . $errmsgs['13'];
		return $output;
	}
	

	// Set a counter to find out how many Posts are found in the WP_Query
	// Also used to add an image # in the markup page source
	$counter = 0;
	$counter2 = 0;

	// Start the gallery markup
	$output = "\n" . '<div id="myGallery"><!-- Start of Dynamic Content Gallery mootools gallery -->';

	while($recent->have_posts()) : $recent->the_post();

		// Increment the counter
		$counter++;
			
		// Exclude the post if _dfcg-exclude custom field is true
		if( get_post_meta($post->ID, $dfcg_postmeta['exclude'], true) == 'true' ) {
			$output .= "\n\n" . '<!-- DCG Image #' . $counter . ' has been Excluded by user -->';
			$counter2++;
			continue;
		}
		
		// Get the post title (sanitised)
		$title = get_the_title();
					
		// Get the slide pane description (post ID, cat/Term ID)			
		$slide_text_html = dfcg_get_desc( $post->ID, $term_id );
			
		// Get the Image Link
		$link = dfcg_get_link( $post->ID, $title );
				
		// Get the Image
		$image = dfcg_get_image($post->ID, $term_id);
									
		// Get the thumbnail - uses Post Thumbnails if AUTO images are used
		$thumb_html = dfcg_get_thumbnail($post->ID, $image['src'], $title);

		// Open the imageElement div
		$output .= "\n\n" . '<div class="imageElement"><!-- DCG Image #' . $counter . ' -->';
		
		// Display the page title
		$output .= "\n\t" . '<h3>'. $title .'</h3>';
						
		// Output slide pane description
		$output .= "\n\t" . $slide_text_html;
			
		// Output Image Link
		$output .= "\n\t" . '<a href="'. $link['link_url'] .'" title="' . $link['link_title_attr'] . '" class="open"></a>';
			
		// Output Image and Thumbnail
		$output .= "\n\t" . '<img width="'.$image['w'].'" height="'.$image['h'].'" src="'. $image['src'].'" class="'.$image['class'].'" alt="'.$title.'" title="'.$title.'" />';
		$output .= "\n\t" . $thumb_html;
		$output .= $errmsgs[$image['msg']];

		// Close the ImageElement div
		$output .= "\n" . '</div>';

	endwhile;

	/*	Compare number of Posts selected as per Settings ($posts_number) with the WP_Query object output ($counter)
		to check that the number of gallery images is the same.	If it's not the
		same, then there are less Posts in this Category than the posts-number Setting */

	if( $posts_number - $counter !== 0 ) {
		$output .= "\n" . $errmsgs['20'];
	}
		
	// Print out stats
	if( $dfcg_options['errors'] == "true" ) {
		$post_found = $counter - $counter2;
		$output .= "\n\n" . '<!-- ' . __('Number of Posts to display as per DCG Settings = ', DFCG_DOMAIN) . $posts_number . ' -->';
		$output .= "\n" . '<!-- ' . __('Number of Posts found = ', DFCG_DOMAIN) . $post_found . ' -->';
		$output .= "\n" . '<!-- ' . __('Number of Posts excluded by user = ', DFCG_DOMAIN) . $counter2 . ' -->';
	}

	// End of the gallery markup
	$output .= "\n\n" . '</div><!-- End of Dynamic Content Gallery mootools gallery -->';
	
	// Output the Gallery
	return $output;
}


/**
* This function builds the gallery from ID Method options
*
* NOTE: This function was renamed in v3.3 (formally dfcg_pages_method_gallery() )
*
* @uses dfcg_postmeta_info()		Builds array of postmeta key names (see dfcg-gallery-core.php)
* @uses	dfcg_errors_output()		Gets all Error Messages, if errors are on (see dfcg-gallery-errors.php)
* @uses	dfcg_baseimgurl()			Determines whether FULL or Partial URL applies (see dfcg-gallery-core.php)
* @uses	dfcg_query_list()			Builds array of cat/off pairs for WP_Query (see dfcg-gallery-core.php)
* @uses dfcg_the_content_limit()	Creates Auto description (see dfcg-gallery-content-limit.php)
* @uses dfcg_grab_post_image()		Gets the first image attachment from the Post (see dfcg-gallery-core.php)
*
* @var array	$postmeta				Array of postmeta keys, eg _dfcg-image, etc. Output of dfcg_postmeta_info()
* @var array	$errmsgs			Array of error messages. Output of dfcg_errors_output()
* @var string 	$baseimgurl				Base URL for images. Empty if FULL URL. Output of dfcg_baseurl()
* @var string 	$ids_selected			DCG option: comma separated list of Page/Post IDs
* @var string	$ids_selected_count		No. of Page/Post IDs specified in DCG options
* @var array	$ids_found				$wpdb query object
* @var string	$ids_found_count		Number of Pages in $wpdb query object
* @var string	$counter				Incremented variable to add image # in HTML comments markup
* @var string 	$slide_text				Slide Pane description text
* @var string	$chars					Stores value of $dfcg_options['max-char'], used as param in dfcg_the_content_limit()
* @var string	$more					Stores value of $dfcg_options['more-text'], used as param in dfcg_the_content_limit()
* @var string	$id_content				Stores value of $id_found->post_content, used as param in dfcg_the_content_limit()
* @var string	$id_id					Stores value of $id_found->ID, used as param in dfcg_the_content_limit()
* @var string	$link					Image link URL, either to Post/Page or External
* @var string 	$auto_image				First image attachment in the Post, as URL
* @var string	$image_src				SRC of gallery image
* @var string	$image_err				Error message, if relevant
* @var string	$thumb					Stores output of get_the_post_thumbnail() function, for accessing Post Thumbnails/Featured Image
* @var string	$thumb_html				Stores HTML of thumbnail IMG
*
* @global array $dfcg_options Plugin options array from db
* @global array $wpdb WP $wpdb database object
*
* @return $output - string - all XHTML output for the gallery
* @since 3.2
* @updated 4.0
*/
function dfcg_flex_id_method_gallery() {

	global $dfcg_options, $dfcg_postmeta;
	
	// Build array of error messages (NULL if Errors are off)
	$errmsgs = NULL;
	if( function_exists( 'dfcg_errors_output' ) ) {
		$errmsgs = dfcg_errors_output();
	}
	
	
	/* Get the comma separated list of Page ID's */
	$ids_selected = trim( $dfcg_options['ids-selected'] );

	if( !empty( $ids_selected ) ) {

		/* Get rid of the final comma so that the variable is ready for use in SQL query */
		// If last character in string is a comma
		if( substr( $ids_selected, -1 ) == ',' ) {
			// Remove the final comma in the list
			$ids_selected = substr( $ids_selected, 0, substr( $ids_selected, -1)-1 );
		}

		/* Turn the list into an array */
		$ids_selected = explode(",", $ids_selected);
		/* Store how many IDs were in list */
		$ids_selected_count = count($ids_selected);

		/* If only one Page ID has been specified in Settings: print error messages and exit */
		if( $ids_selected_count < 2 ) {
			$output = $errmsgs['14'] . "\n";
			return $output;
		}

	} else {
		/* There are no IDs specified in Settings: print error messages and exit */
		$output = $errmsgs['15'] . "\n";
		return $output;
	}


	/* Instantiate the $wpdb object */
	global $wpdb;
	
	if( $dfcg_options['id-sort-control'] == 'true' ) {
	
		/* User defined sort order for Pages */
		$sort = esc_attr('_dfcg-sort');
	
		/* Do the query - with thanks to Austin Matzko for sprintf help */
		$ids_found = $wpdb->get_results(
  			sprintf("SELECT ID,post_title,post_content
				FROM $wpdb->posts
				LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id AND $wpdb->postmeta.meta_key = '%s'
				WHERE $wpdb->posts.ID IN( %s )
				ORDER BY $wpdb->postmeta.meta_value ASC", $sort, implode(',', array_map( 'intval', $ids_selected )) )
			);
	
	} else {
		
		/* Do the query - with thanks to Austin Matzko for sprintf help */
		/* Note: simplified query without custom sort ordering */
		$ids_found = $wpdb->get_results(
  			sprintf("SELECT ID,post_title,post_content FROM $wpdb->posts WHERE $wpdb->posts.ID IN( %s )", implode(',', array_map( 'intval', $ids_selected ) ) )
			);
	}
	
	/* If we have results from the query */
	if( $ids_found ) {

		// Validation: Check how many Pages the query found
		// The results if this are printed to Page Source further down
		$ids_found_count = count($ids_found);
	
		// If less than 2, print error messages and exit function
		if( $ids_found_count < 2 ) {
			$output = "\n" . $errmsgs['16'];
			if( $dfcg_options['errors'] == "true" ) {
				$output .= "\n" . '<!-- ' . __('Number of Pages selected in DCG Settings = ', DFCG_DOMAIN) . $ids_selected_count . ' -->';
				$output .= "\n" . '<!-- ' . __('Number of Pages found = ', DFCG_DOMAIN) . $ids_found_count . ' -->';
			}
			return $output;
		}

		// Set a counter to add an image # in the markup page source
		$counter = 0;

		// Start the gallery markup
		$output = "\n" . '<div id="myGallery"><!-- Start of Dynamic Content Gallery mootools gallery -->';

		foreach( $ids_found as $id_found ) :

			// Increment the image counter
			$counter++;
			
			// Get the page title
			$title = esc_attr($id_found->post_title);
					
			// Get the slide pane description (post ID, cat/Term ID)			
			$slide_text_html = dfcg_get_desc( $id_found->ID, '', $id_found->post_content );
				
			// Get the Image Link
			$link = dfcg_get_link( $id_found->ID, $title );
				
			// Get the Image
			$image = dfcg_get_image($id_found->ID);
									
			// Get the thumbnail - uses Post Thumbnails if AUTO images are used
			$thumb_html = dfcg_get_thumbnail($id_found->ID, $image['src'], $title);

			// Open the imageElement div
			$output .= "\n" . '<div class="imageElement"><!-- DCG Image #' . $counter . '-->';

			// Display the page title
			$output .= "\n\t" . '<h3>'. $title .'</h3>';
			
			// Output slide pane description
			$output .= "\n\t" . $slide_text_html;
						
			// Output Image Link
			$output .= "\n\t" . '<a href="'. $link['link_url'] .'" title="' . $link['link_title_attr'] . '" class="open"></a>';

			
			// Output image and thumbnail
			$output .= "\n\t" . '<img src="'. $image['src'] . '" alt="'. $title .'" class="' . $image['class'] . ' full" />';
			$output .= "\n\t" . $thumb_html;
			$output .= $errmsgs[$image['msg']];
			
			// Close the ImageElement div
			$output .= "\n" . '</div>';

		endforeach;

		/*	Compare $pages_selected_count with the db query object $pages_found_count
			to check that the number of gallery images is the same.	If it's not the
			same, then one or more of the selected Page IDs are not valid Pages */

		if( $ids_found_count !== $ids_selected_count) {
			$output .= "\n" . $errmsgs['20'];
			if( $dfcg_options['errors'] == "true" ) {
				$output .= "\n" . '<!-- ' . __('Number of IDs selected in DCG Settings = ', DFCG_DOMAIN) . $ids_selected_count . ' -->';
				$output .= "\n" . '<!-- ' . __('Number of IDs found = ', DFCG_DOMAIN) . $ids_found_count . ' -->';
			}
		}

		// End of the gallery markup
		$output .= "\n" . '</div><!-- End of Dynamic Content Gallery mootools gallery -->' . "\n\n";
		
	} else {
		/* Oops! Either none of the selected IDs are valid or the db query failed in some way */
		$output = "\n" . $errmsgs['17'];
	}
	
	// Output the Gallery
	return $output;
}