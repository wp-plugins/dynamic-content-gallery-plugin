<?php
/**
 * Key Settings functions
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2011
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * @info Various helper functions used by the gallery constructor functions and admin
 *
 * @since 4.0
 */
 
 
 /**
 * Prevent direct access to this file
 */
if( !defined( 'ABSPATH' ) ) {
	exit( __( 'Sorry, you are not allowed to access this file directly.' ) );
}



/**
 * Active Settings display shown on General tab on the DCG Settings page
 *
 * @global array $dfcg_options plugin options from db
 * @since 3.2.2
 * @updated 4.0
 */
function dfcg_ui_active_settings() {
	
	$output = '<h3>' . __('Your current Key Settings: ', DFCG_DOMAIN) . '</h3>';
	
	$output .= '<p>' . __('Please provide the information shown below if posting a question on the <a href="http://www.studiograsshopper.ch/forum/" target="_blank">Support Forum</a>.', DFCG_DOMAIN) . '</p>';
	
	
	// Start the table
	$output .= "\n" . '<table id="dfcg-key-settings"><tbody>';
	
	$output .= dcg_key_settings_image();
	
	$output .= dcg_key_settings_carousel();
	
	$output .= dcg_key_settings_gallery();
	
	$output .= dcg_key_settings_desc();
	
	$output .= dcg_key_settings_javascript();
	
	$output .= dcg_key_settings_scripts();
	
	$output .= dcg_key_settings_errors();
	
	// End the table
	$output .= '</tbody></table>';
	
	echo $output;
}


/**
 *
 *
 * @global $dfcg_options | array | DCG options from db
 * @return $output | string | tr row/cell contents
 * @since 4.0
 */
function dcg_key_settings_image() {

	global $dfcg_options;
	
	// Set up some pretty names
	if( $dfcg_options['image-url-type'] == 'auto' ) {
		$text = 'Featured Images';
		if( $dfcg_options['crop'] == 'true' ) {
			$text .= '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Hard Crop = Yes';
		} else {
			$text .= '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Hard Crop = No';
		}
	} elseif( $dfcg_options['image-url-type'] == 'full' ) {
		$text = 'Full URL';
	} else {
		$text = 'Partial URL';
	}
	
	$not_defined_msg = '<span class="error">' . __('not defined', DFCG_DOMAIN) . '</span>';
	
	$output = '<tr>';
	
	$output .= '<td rowspan="2" class="first"><a class="dfcg-panel-image-link" href="#dfcg-panel-image">' . __('Image Management', DFCG_DOMAIN) . '</a>:</td>';
	$output .= '<td class="second"><span>' . $text . '</span></td>';
	
	if( $dfcg_options['image-url-type'] == 'auto' && !current_theme_supports('post-thumbnails') ) {
		$output .= '<td class="third-error">&nbsp;</td>';
	} else {
		$output .= '<td class="third">&nbsp;</td>';
	}
	
	$output .= '</tr>';
	
	$output .= '<tr>';
	
	$output .= '<td class="second">';
	
	$output .= __('Images folder is: ', DFCG_DOMAIN);
	
	if( $dfcg_options['image-url-type'] == 'partial' ) {
		
		if( !empty( $dfcg_options['imageurl'] ) ) {
			$output .= '<span>' . $dfcg_options['imageurl'] . '</span></td>';
			$output .= '<td class="third">&nbsp;</td>'; 
		} else {
			$output .= $not_defined_msg . '</td>';
			$output .= '<td class="third-error">&nbsp;</td>';
		}
	
	
	} else {
	
		$output .= '<span>' . __('Not applicable for this Image Management option', DFCG_DOMAIN) . '</span></td>';
		$output .= '<td class="third">&nbsp;</td>';
	}
	
	$output .= '</tr>';
	
	return $output;
}


/**
 *
 *
 * @global $dfcg_options | array | DCG options from db
 * @return $output | string | tr row/cell contents
 * @since 4.0
 */
function dcg_key_settings_carousel() {

	global $dfcg_options;
	
	// Set up some pretty names
	if( $dfcg_options['thumb-type'] == 'featured-image' ) {
		$text = 'Featured Images';
	} else {
		$text = 'Legacy';
	}
	
	
	$output = '<tr>';
	
	$output .= '<td class="first"><a class="dfcg-panel-image-link" href="#dfcg-panel-image">' . __('Carousel Thumbnails', DFCG_DOMAIN) . '</a>:</td>';
	$output .= '<td class="second"><span>' . $text . '</span></td>';
	
	if( $dfcg_options['image-url-type'] == 'auto' && !current_theme_supports('post-thumbnails') ) {
		$output .= '<td class="third-error">&nbsp;</td>';
	} else {
		$output .= '<td class="third">&nbsp;</td>';
	}
	
	$output .= '</tr>';
	
	return $output;
}


/**
 *
 *
 * @global $dfcg_options | array | DCG options from db
 * @return $output | string | tr row/cell contents
 * @since 4.0
 */
function dcg_key_settings_gallery() {

	global $dfcg_options;
	
	$not_defined_msg = '<span class="error">' . __('not defined', DFCG_DOMAIN) . '</span>';
	
	$not_wpmu_msg = '<span>' . __('option not available in Multisite', DFCG_DOMAIN) . '</span>';
	
	// Set up some pretty names
	if( $dfcg_options['populate-method'] == 'multi-option' ) {
		$text = 'Multi Option';
	} elseif( $dfcg_options['populate-method'] == 'one-category' ) {
		$text = 'One Category';
	} elseif( $dfcg_options['populate-method'] == 'id-method' ) {
		$text = 'ID Method';
	} else {
		$text = 'Custom Post Type';
	}
	
	$output = '<tr>';

	$output .= '<td rowspan="3" class="first"><a class="dfcg-panel-gallery-link" href="#dfcg-panel-gallery">' . __('Gallery Method', DFCG_DOMAIN) . '</a>:</td>';
	$output .= '<td class="second"><span>' . $text . '</span></td>';
	$output .= '<td class="third">&nbsp;</td>';
	
	$output .= '</tr>';
	
	
	$subhead = __('Folder for Default Images is: ', DFCG_DOMAIN);
	
	// One category
	if( $dfcg_options['populate-method'] == 'one-category' ) {
				
		if( !empty( $dfcg_options['cat-display'] ) ) {
			$cat_name = get_cat_name( $dfcg_options['cat-display'] );
			$cat_id = $dfcg_options['cat-display'];
		} else {
			$cat_name = 'All';
			$cat_id = 'Not applicable';
		}
		
		$output .= '<tr>';
		
		$output .= '<td class="second">' . __('Category selected:', DFCG_DOMAIN) . '&nbsp;' . $cat_name . ' (ID: ' . $cat_id . ')</td>';
		
		$output .= '<td class="third">&nbsp;</td>';
		
		$output .= '</tr>';
		
		$output .= '<tr>';
		
		if( !is_multisite() ) {
			
			if( $dfcg_options['defimgfolder'] ) {
				$output .= '<td>' . $subhead . '<span>' . $dfcg_options['defimgfolder'] . '</span></td>';
				$output .= '<td class="third">&nbsp;</td>';
			} else {
				$output .= '<td>' . $subhead . $not_defined_msg . '</td>';
				$output .= '<td class="third-error">&nbsp;</td>';
			}
		} else {
		
			$output .= '<td>' . $subhead . $not_wpmu_msg . '</td>';
			$output .= '<td class="third">&nbsp;</td>';
		}
		
		$output .= '</tr>';
	}
	
	// Custom Post
	if( $dfcg_options['populate-method'] == 'custom-post' ) {
		
		$output .= '<tr>';
		
		$output .= '<td class="second">' . __('Taxonomy and Term selected:', DFCG_DOMAIN) . '&nbsp;';
		
		if( $dfcg_options['cpt-tax-and-term'] == 'all' ) {
		
			$output .= __('All posts', DFCG_DOMAIN) . '</td>';
			
		} else {
		
			$output .= $dfcg_options['cpt-tax-name'] . ' / ' . $dfcg_options['cpt-term-name'] . '</td>';
		}
		
		$output .= '<td class="third">&nbsp;</td>';
		
		$output .= '</tr>';
		
		$output .= '<tr>';
		
		if( !is_multisite() ) {
			
			if( $dfcg_options['defimgfolder'] ) {
				$output .= '<td>' . $subhead . $dfcg_options['defimgfolder'] . '</td>';
				$output .= '<td class="third">&nbsp;</td>';
			} else {
				$output .= '<td>' . $subhead . $not_defined_msg . '</td>';
				$output .= '<td class="third-error">&nbsp;</td>';
			}
		} else {
		
			$output .= '<td>' . $subhead . $not_wpmu_msg . '</td>';
			$output .= '<td class="third">&nbsp;</td>';
		}
		
		$output .= '</tr>';
	}
	
	
	// Multi Option
	if( $dfcg_options['populate-method'] == 'multi-option' ) {
	
		$query_pairs = dfcg_query_list();
		$selected_slots = count($query_pairs);
	
		$output .= '<tr>';
		
		$output .= '<td class="second">' . __('Number of slots configured:', DFCG_DOMAIN) . '&nbsp;' . $selected_slots . '</td>';
		
		if( $selected_slots < 2 ) {
			$output .= '<td class="third-error">&nbsp;</td>';
		} else {
			$output .= '<td class="third">&nbsp;</td>';
		}
		
		$output .= '</tr>';
		
		$output .= '<tr>';
		
		if( !is_multisite() ) {
	
			if( $dfcg_options['defimgfolder'] ) {
				$output .= '<td>' . $subhead . $dfcg_options['defimgfolder'] . '</td>';
				$output .= '<td class="third">&nbsp;</td>';
			} else {
				$output .= '<td>' . $subhead . $not_defined_msg . '</td>';
				$output .= '<td class="third-error">&nbsp;</td>';
			}
		} else {
			
			$output .= '<td>' . $subhead . $not_wpmu_msg . '</td>';
			$output .= '<td class="third">&nbsp;</td>';
		
		}
		
		$output .= '</tr>';
	}
	
	
	
	// We're using ID Method
	if( $dfcg_options['populate-method'] == 'id-method' ) {
	
		/* Grab IDs and turn the list into an array */
		$ids_selected = explode(",", $dfcg_options['ids-selected']);
		/* Store how many IDs were in list */
		$ids_selected_count = count($ids_selected);
	
		$output .= '<tr>';
		
		$output .= '<td class="second">' . __('IDs selected:', DFCG_DOMAIN) . '&nbsp;' . $dfcg_options['ids-selected'] . '<br />';
		
		$output .= __('Custom Image order:', DFCG_DOMAIN) . '&nbsp;' . $dfcg_options['id-sort-control'] . '</td>';
		
		if( $ids_selected_count < 2 || empty( $dfcg_options['id-sort-control'] ) ) {
			$output .= '<td class="third-error">&nbsp;</td>';
		} else {
			$output .= '<td class="third">&nbsp;</td>';
		}
		
		$output .= '</tr>';
		
		$output .= '<tr>';
	
		if( !is_multisite() ) {
				
			if( $dfcg_options['defimgid'] ) {
				$output .= '<td>' . __('Default image is: ', DFCG_DOMAIN) . $dfcg_options['defimgid'] . '</td>';
				$output .= '<td class="third">&nbsp;</td>';
			} else {
				$output .= '<td>' . __('Default image is: ', DFCG_DOMAIN) . '<span class="error">' . __('not defined', DFCG_DOMAIN) . '</td>';
				$output .= '<td class="third-error">&nbsp;</td>';
			}
		
		} else {
		
			$output .= '<td>' . $subhead . $not_wpmu_msg . '</td>';
			$output .= '<td class="third">&nbsp;</td>';
		}
		
		$output .= '</tr>';
	}
	
	return $output;

}


/**
 *
 *
 * @global $dfcg_options | array | DCG options from db
 * @return $output | string | tr row/cell contents
 * @since 4.0
 */
function dcg_key_settings_scripts() {
	
	global $dfcg_options;
	
	$output = '<tr>';
	
	$output .= '<td class="first"><a class="dfcg-panel-scripts-link" href="#restrict-scripts">' . __('Load Scripts', DFCG_DOMAIN) . '</a>:</td>';
	$output .= '<td class="second">';
	
	if( $dfcg_options['limit-scripts'] == 'homepage' ) {
		$output .= __('Home Page', DFCG_DOMAIN) . '</td>';
		$output .= '<td class="third">&nbsp;</td>';
	
	} elseif( $dfcg_options['limit-scripts'] == 'page' ) {
		$output .= __('Page ID => ', DFCG_DOMAIN) . $dfcg_options['page-ids'] . '</td>';
		$output .= '<td class="third">&nbsp;</td>';
		
	} elseif( $dfcg_options['limit-scripts'] == 'pagetemplate' ) {
		$output .= __('Page Template => ', DFCG_DOMAIN) . $dfcg_options['page-filename'] . '</td>';
		$output .= '<td class="third">&nbsp;</td>';
		
	} else {
		$output .= __('All pages', DFCG_DOMAIN) . '</td>';
		$output .= '<td class="third">&nbsp;</td>';
		
	}
	
	$output .= '</tr>';
	
	return $output;
}


/**
 *
 *
 * @global $dfcg_options | array | DCG options from db
 * @return $output | string | tr row/cell contents
 * @since 4.0
 */
function dcg_key_settings_desc() {

	global $dfcg_options;
	
	$output = '<tr>';
	
	$output .= '<td class="first"><a class="dfcg-panel-desc-link" href="#default-desc">' . __('Descriptions', DFCG_DOMAIN) . '</a>:</td>';
	
	$output .= '<td class="second">' . $dfcg_options['desc-method'] . '</td>';
	
	if( empty( $dfcg_options['desc-method'] ) ) {
		$output .= '<td class="third-error">&nbsp;</td>';
	} else {
		$output .= '<td class="third">&nbsp;</td>';
	}
	
	$output .= '</tr>';
	
	return $output;
}


/**
 *
 *
 * @global $dfcg_options | array | DCG options from db
 * @return $output | string | tr row/cell contents
 * @since 4.0
 */
function dcg_key_settings_javascript() {

	global $dfcg_options;
	
	$output = '</tr>';

	$output .= '<td class="first"><a class="dfcg-panel-javascript-link" href="#gallery-js-scripts">' . __('Javascript Options', DFCG_DOMAIN) . '</a>:</td>';
	
	$output .= '<td class="second">' . $dfcg_options['scripts'] . '</td>';
	
	if( empty( $dfcg_options['scripts'] ) ) {
		$output .= '<td class="third-error">&nbsp;</td>';
	} else {
		$output .= '<td class="third">&nbsp;</td>';
	}
	
	$output .= '</tr>';
	
	return $output;
}


/**
 *
 *
 * @global $dfcg_options | array | DCG options from db
 * @return $output | string | tr row/cell contents
 * @since 4.0
 */
function dcg_key_settings_errors() {
	
	global $dfcg_options;
	
	$output = '</tr>';
	
	$output .= '<td class="first"><a class="dfcg-panel-tools-link" href="#error-messages">' . __('Tools - Error Message options', DFCG_DOMAIN) . '</a>:</td>';
	
	$output .= '<td class="second">';
		
	if( $dfcg_options['errors'] == 'true' ) {
		$output .= __('on', DFCG_DOMAIN) . '</td>';
	
	} else {
		$output .= __('off', DFCG_DOMAIN) . '</td>';
	}
	
	$output .= '<td class="third">&nbsp;</td>';
	
	$output .= '</tr>';
	
	return $output;
}