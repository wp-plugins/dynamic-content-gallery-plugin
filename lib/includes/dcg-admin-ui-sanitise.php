<?php
/**
 * Settings API Callback Function - to sanitise DCG Settings page input
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2011
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * @info Sanitise Settings screen Options input.
 * @info register_settings() callback function.
 *
 * @since 3.2
 */


/**
 * Prevent direct access to this file
 */
if( !defined( 'ABSPATH' ) ) {
	exit( __( 'Sorry, you are not allowed to access this file directly.' ) );
}


/**
 * Settings API callback function
 *
 * Settings Error API functions added in v4.0
 *
 * @since 3.2.2
 * @updated 4.0
 * @param array $input $_POST input from form
 * @global array $dfcg_options plugin options from db
 * @return array $input Sanitised form input ready for db
 */
function dfcg_sanitise( $input ) {
	
	global $dfcg_options;

	// Is the user allowed to do this? Probably not needed...
	if ( function_exists( 'current_user_can' ) && !current_user_can( 'manage_options' ) ) {
		die( __( 'Sorry. You do not have permission to do this.', DFCG_DOMAIN ) );
	}
	
	
	
	/* If RESET box is checked, reset the options, and don't bother sanitising */
	
	if ( isset( $input['reset'] ) == "1" ) {
		
		// See wp-admin/includes/template.php
		$setting = 'dfcg_plugin_settings_options';
		$code = 'dfcg-reset';
		$message = __('Dynamic Content Gallery Settings have been reset to default settings.', DFCG_DOMAIN);
		$type = 'updated';

		add_settings_error($setting, $code, $message, $type);
		
		// put back the defaults -> also resets ['reset'] to '0'
		$input = dfcg_default_options();
		
		return $input;
	}
	
	
	/* If gallery-width or gallery-height has changed, trigger DCG Notice */
	if( intval( $input['gallery-width'] ) !== $dfcg_options['gallery-width'] ) {
		
		// See wp-admin/includes/template.php
		add_settings_error(
			'dfcg_plugin_size-change', 	// $setting
			'dfcg-size-change',			// $code
			__('DCG Notice: Image sizes have changed. Run Regenerate Thumbnails to generate new sizes for existing images.', DFCG_DOMAIN),
			'updated'					// $type
			);
			
		$input['size-change'] = 'true';
	}
	
	
	/***** Some error messages for later *****/
	
	// Generic error message - triggered by wp_die
	$dfcg_sanitise_error = __('An error has occurred. Go back and try again.', DFCG_DOMAIN);
	
	
	/***** Now correct certain options *****/
	
	// trim whitespace - all options
	foreach( $input as $key => $value ) {
		$input[$key] = trim($value);
	}
	
	
	// deal with just-reset option, overwrite it in case it's 'true' (Should never be the case...)
	$input['just-reset'] = '0';
	
	// deal with One Category Method "All" option to suppress WP_Class Error if category_description() is passed a '0'.
	// WP_Query will fail gracefully because cat='' is ignored
	// TODO: Probably not needed now due to sanitisation routines below
	if( $input['cat-display'] == 0 ) {
		$input['cat-display'] = '';
	}
	
	
	// Deal with Custom Post types / Taxonomy data - if All option was selected
	if( $input['cpt-tax-and-term'] == 'all' ) {
		
		$input['cpt-tax-name'] = '';
		$input['cpt-term-name'] = '';
		$input['cpt-term-id'] = '';
	}
	
	// Deal with Custom Post types / Taxonomy data in normal cases
	if( !empty( $input['cpt-name'] ) && $input['cpt-tax-and-term'] !== 'all' ) {
	
		// Split cpt-tax-and-term into assoc. array
		$temp = wp_parse_args( $input['cpt-tax-and-term'] );
		
		// Taxonomy and term names
		$tax_name = key($temp);
		$term_name = $temp[$tax_name];
		
		// Get term object, get_term_by($field, $value, $taxonomy)
		$term = get_term_by( 'name', $term_name, $tax_name );
		
		$input['cpt-tax-name'] = $tax_name;
		$input['cpt-term-name'] = $term_name;
		$input['cpt-term-id'] = $term->term_id;
	}
	
	
	
	/***** Organise the options by type etc, into arrays, then sanitise / validate / format correct *****/
	
	//	Whitelist options													(10)
	//	Path and URL options												(6)		(1)
	//	Bool options														(18)
	//	String options - no XHTML allowed									(6)
	//	String options - small - no XHTML allowed							(2)
	//	String options - some XHTML allowed									(1)
	//	String options - CSS hexcodes										(7)
	//	String options - numeric comma separated only 						(2)
	//	String options - filenames											(1)
	//	Integer options - positive - can be blank, can't be zero 			(9)
	//	Integer options - positive - can be blank, can't be zero 			(2)
	//	Integer options - positive - can't be blank, can't be zero 			(9)
	//	Integer options - positive integer - can't be blank, can be zero 	(18)
	//	Integer options - positive - large									(1)
	//	Total 																92
	
	
	/***** Whitelist options (10/10) *****/
	
	$whitelisted_opts = array(
							'image-url-type',			// 'full', 'partial'
							'populate-method',			// 'multi-option', 'one-category', 'id-method', 'custom-post'
							'defaultTransition',		// 'fade', 'fadeslideleft', 'continuousvertical', 'continuoushorizontal'
							'limit-scripts',			// 'homepage', 'pagetemplate', 'other', 'page'
							'scripts',					// 'mootools', 'jquery'
							'slide-h2-weight',			// 'bold', 'normal'
							'desc-method',				// 'manual', 'auto', 'none', 'excerpt'
							'slide-p-a-weight',			// 'bold', 'normal'
							'slide-p-ahover-weight',	// 'bold', 'normal'
							'thumb-type'				// 'featured-image', 'legacy'
						);
	
	// Define whitelist
	$dfcg_whitelist = array( 'full', 'partial', 'multi-option', 'one-category', 'id-method', 'custom-post', 'fade', 'fadeslideleft', 'continuousvertical', 'continuoushorizontal', 'homepage', 'pagetemplate', 'other', 'mootools', 'jquery', 'bold', 'normal', 'manual', 'auto', 'none', 'page', 'featured-image', 'legacy', 'excerpt' );
	
	// sanitise
	foreach( $whitelisted_opts as $key ) {
		
		// If option value is not in whitelist, die with error message
		if( !in_array( $input[$key], $dfcg_whitelist ) ) {
			
			//Used for testing: $input[$key] = 'dodgy';
			//var_dump($key, $input[$key]);
			wp_die( "Dynamic Content Gallery Message #99: " . $dfcg_sanitise_error . "<br />Error with option: " . $key . "<br />Value: " . $input[$key] );
		}
	}
	
	
	/***** Path and URL options (6/1) *****/
	
	if ( is_multisite() ) {
		$abs_url_opts = array( 'homeurl' );
	} else {
		$abs_url_opts = array( 'imageurl', 'defimgmulti', 'defimgonecat', 'defimgid', 'defimgcustompost', 'homeurl' );
	}
	
	// sanitise and add trailing slash
	foreach( $abs_url_opts as $key ) {
		if( !empty($input[$key]) ) {
			if( $key == 'defimgid' ) {
				// Sanitise for db only
				$input[$key] = esc_url_raw( $input[$key] );
			} else {
				// Trailingslashit if there is something to do it to
				$input[$key] = trailingslashit( $input[$key] );
				// Sanitise for db
				$input[$key] = esc_url_raw( $input[$key] );
			}
		}
	}
	

	
	
	/***** Bool options (18) Checkboxes *****/
	
	$bool_opts = array( 'reset', 'showCarousel', 'showInfopane', 'timed', 'slideInfoZoneSlide', 'mootools', 'errors', 'column-img', 'column-desc', 'column-sort', 'id-sort-control', 'showArrows', 'slideInfoZoneStatic', 'column-feat-img', 'crop', 'desc-man-link', 'add-media-sizes', 'size-change' );
	
	// sanitise, eg RESET checkbox
	foreach( $bool_opts as $key ) {
		$input[$key] = isset( $input[$key] ) ? 'true' : 'false';
	}
	
	
	/***** String options - no XHTML allowed (6) *****/
	
	$str_opts_no_html = array( 'textShowCarousel', 'more-text', 'cpt-name', 'cpt-tax-and-term', 'cpt-tax-name', 'cpt-term-name' );
	
	// sanitise
	foreach( $str_opts_no_html as $key ) {
		
		// Extract first 50 characters (v3.2: increased from 25 chars to allow longer Featured Article text) 
		$input[$key] = substr( $input[$key], 0, 50 );
		
		$input[$key] = wp_filter_nohtml_kses( $input[$key] );
	}
	
	
	/***** String options - small - no XHTML allowed (2) *****/
	
	$str_opts_small_no_html = array( 'slideInfoZoneOpacity', 'carouselMinimizedOpacity' );
	
	// sanitise
	foreach( $str_opts_small_no_html as $key ) {
		
		// Extract first 3 characters 
		$input[$key] = substr( $input[$key], 0, 3 );
		
		$input[$key] = wp_filter_nohtml_kses( $input[$key] );
	}
	
	
	/***** String options - some XHTML allowed (1) *****/
	
	$str_opts_html = array( 'defimagedesc' );
	
	// Note, form already includes stripslashes
	
	$allowed_html = array( 'a' => array('href' => array(),'title' => array() ), 'br' => array(), 'em' => array(), 'strong' => array() );
	
	$allowed_protocols = array( 'http', 'https', 'mailto', 'feed' );
	
	// sanitise
	foreach( $str_opts_html as $key ) {
		$input[$key] = wp_kses( $input[$key], $allowed_html, $allowed_protocols );
	} 
	
	
	/***** String options - CSS hexcodes (7) *****/
	
	$str_opts_hexcode = array( 'gallery-border-colour', 'slide-h2-colour', 'slide-p-colour', 'slide-overlay-color', 'slide-p-a-color', 'slide-p-ahover-color', 'gallery-background' );
	
	// deal with String options - CSS hexcodes - will accept valid 3 or 6 char codes
	foreach( $str_opts_hexcode as $key ) {
		
		// Make sure value contains only allowed numbers and characters
		if( !preg_match_all('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $input[$key], $result ) ) {
			
			// See wp-admin/includes/template.php
			add_settings_error(
				'dfcg_plugin_settings_options',
				$key,
				 __('DCG Settings error: Gallery CSS tab. ', DFCG_DOMAIN) . $key . ' : ' . $input[$key] . __(' This is not a valid hex code for CSS.', DFCG_DOMAIN),
				 'error'
				 );
		}
	}
	
	
	/***** String options - numeric comma separated only (2) *****/
	
	$str_opts_csv_num = array( 'ids-selected', 'page-ids' );
	
	// sanitise
	foreach( $str_opts_csv_num as $key ) {
		
		if( !empty( $input[$key] ) ) {
			// Strip out any whitespace within list
			$input[$key] = str_replace( " ", "", $input[$key] );
			
			// If first character in string is a comma
			if( substr( $input[$key], 0, 1 ) == ',' ) {
				// Remove the first comma in the list
				$input[$key] = substr( $input[$key], 1 );
			}
			
			// If last character in string is a comma
			if( substr( $input[$key], -1) == ',' ) {
				// Remove the final comma in the list
				$input[$key] = substr( $input[$key], 0, substr( $input[$key], -1)-1 );
			}
			
			// Make sure list only contains numbers and commas
			if( !preg_match_all( '/^[0-9,]+$/i', $input[$key], $result ) ) {
				// Resets the dodgy $input to the existing value. Better user-experience in case of failure.
				$input[$key] = $dfcg_options[$key];
			}
		}
	}
	
	
	/***** String options - filenames (1) *****/
	
	$str_opts_filename = array( 'page-filename' );
	
	// This can be a comma separated list of page template filenames
	
	// sanitise
	foreach( $str_opts_filename as $key ) {
		
		if( !empty( $input[$key] ) ) {
		
			// Convert filename list to array
			$filenames = explode(',', $input[$key]);
			
			foreach( $filenames as $filename ) {
				// Strip out any whitespace within list
				$filename = str_replace( " ", "", $filename );
			
				// Make sure filename is alpha-num plus hypens and underscores with .php extension
				if( preg_match_all('/^([A-Za-z0-9_-]+(?=\.(php))\.\2)$/i', $filename, $result) ) {
					// Add ok filename to temp array
					$temp_array[] = $filename;
				} 
			}
			// Convert array back to comma separated list
			$input[$key] = implode(',', $temp_array);
		}
	}
	
	
	/***** Integer options - positive - can be blank, can't be 0 (9) *****/
	
	$int_opts_can_be_blank = array( 'off01', 'off02', 'off03', 'off04', 'off05', 'off06', 'off07', 'off08', 'off09' );
	
	// sanitise, but leave blank as empty, not 0
	foreach( $int_opts_can_be_blank as $key ) {
		//
		if( $input[$key] == 0 || $input[$key] == '0' ) {
			$input[$key] = '';
		} else {
			// Strip out any whitespace within
			$input[$key] = str_replace( " ", "", $input[$key] );
			// Extract first 2 characters
			$input[$key] = substr( $input[$key], 0, 2 );
			// Cast as integer
			$input[$key] = absint( $input[$key] );
		}
	}
	
	
	/***** Integer options - positive - can be blank, can't be 0 (2) *****/
	
	// Note: cat-display can be blank to avoid WP_Query error on first loading plugin
	
	$int_opts_can_be_blank_big = array( 'cat-display', 'cpt-term-id' );
	
	// sanitise, but leave blank as empty, not 0
	foreach( $int_opts_can_be_blank_big as $key ) {
		//
		if( $input[$key] == 0 || $input[$key] == '0' ) {
			$input[$key] = '';
		} else {
			// Strip out any whitespace within
			$input[$key] = str_replace( " ", "", $input[$key] );
			// Extract first 4 characters
			$input[$key] = substr( $input[$key], 0, 4 );
			// Cast as integer
			$input[$key] = absint( $input[$key] );
		}
	}
	
	
	/***** Integer options - positive - can't be blank, can't be zero (9) *****/
	
	// Theoretically, this isn't needed, unless user turns off Select boxes in browser
	
	$int_opts_nonblank_nonzero = array( 'cat01', 'cat02', 'cat03', 'cat04', 'cat05', 'cat06', 'cat07', 'cat08', 'cat09' );
	
	// sanitise, but leave blank and zero as 1
	foreach( $int_opts_nonblank_nonzero as $key ) {
		//
		if( empty( $input[$key] ) ) {
			$input[$key] = 1;
		} else {
			// Extract first 6 characters - increased from 4 to allow for big cat ID numbers
			$input[$key] = substr( $input[$key], 0, 6 );
			// Cast as integer
			$input[$key] = absint( $input[$key] );
		}
	}
	
	
	/***** Integer options - positive integer - can't be blank, can be zero (18) *****/
	
	$int_opts_nonblank = array( 'posts-number', 'gallery-width', 'gallery-height', 'gallery-border-thick', 'slide-height', 'slide-h2-size', 'slide-h2-padtb', 'slide-h2-padlr', 'slide-h2-marglr', 'slide-h2-margtb', 'slide-p-size', 'slide-p-padtb', 'slide-p-padlr', 'slide-p-marglr', 'slide-p-margtb', 'slide-p-line-height', 'max-char', 'cpt-posts-number' );
	
	// sanitise, limit to 4 chars, convert blanks to 0
	foreach( $int_opts_nonblank as $key ) {
		// Strip out any whitespace within
		$input[$key] = str_replace( " ", "", $input[$key] );
		// Extract first 4 characters
		$input[$key] = substr( $input[$key], 0, 4 );
		// Cast as integer
		$input[$key] = absint( $input[$key] );
	}
	
	
	/***** Integer options - positive - large (1) *****/
	
	$int_opts_large = array( 'delay' );
	
	// sanitise, limit to 5 chars, can't be blank, minimum value = 1000
	foreach( $int_opts_large as $key ) {
		// Strip out any whitespace within
		$input[$key] = str_replace( " ", "", $input[$key] );
		// Extract first 5 characters
		$input[$key] = substr( $input[$key], 0, 5 );
		// Cast as integer
		$input[$key] = absint( $input[$key] );
		// Minimum value = 1000 (otherwise gallery js script will go crazy)
		$min_value = 1000;
		if( $input[$key] < $min_value ) {
			$input[$key] = 1000;
		}
	}
	
	/*global $wp_settings_errors;
	var_dump($wp_settings_errors);
	exit;*/
	
	// Return sanitised options array ready for db
	return $input;
}