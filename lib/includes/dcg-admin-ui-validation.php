<?php
/**
 * Settings Page options validation functions
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2012
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * @info Validates certain key Settings and produces validation messages after Settings Page form is submitted
 *
 * @since 3.0
 */

/**
 * Prevent direct access to this file
 */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}


/**
 * Function for validation on loading of Settings Page
 *	
 * Prints validation messages to the Settings Page.
 * Thanks to register_settings/settings_fields, Settings Page
 * is refreshed after Submit, so this function can be used
 * either on fresh load, or after submit. See dfcg-ui-admin-screen.php.
 *
 * Updated for new Custom Post type Gallery Method in 3.3
 *
 * @since 3.0
 * @updated 4.0
 *
 * @param array $options_array, Options from db
 */
function dfcg_on_load_validation( $options_array ) {

	$class = 'error';
	$notice = __( 'DCG Warning!', DFCG_DOMAIN );
	
	// If Partial URL is selected, imageurl must be defined
	if( $options_array['image-url-type'] == 'partial' && empty( $options_array['imageurl'] ) ) {
		
		printf( '<div class="%s"><p><strong>%s %s</strong></p></div>', $class, $notice, __( 'You have selected the "Partial" URL option but you have not defined the URL to your images folder in the Image Management tab.', DFCG_DOMAIN ) );
	}
	
	// If Pages, Page ID's must be defined
	if( $options_array['populate-method'] == 'id-method' && empty( $options_array['ids-selected'] ) ) {
		
		printf( '<div class="%s"><p><strong>%s %s</strong></p></div>', $class, $notice, __( 'You are using the "ID Method" Gallery Method. You must enter at least two valid Post/Page ID\'s in the ID Method settings in the Gallery Method tab.', DFCG_DOMAIN ) );
	}
	
	// If Multi Option, must be minimum of 2 Post Selects
	if( $options_array['populate-method'] == 'multi-option' ) {
	
		$multioption_offsets = dfcg_query_list();
		
		if( count( $multioption_offsets ) < 2 ) {
			printf( '<div class="%s"><p><strong>%s %s</strong></p></div>', $class, $notice, __( 'You are using the "Multi Option" Gallery Method. You must enter at least 2 Posts Selects in the Multi Option settings in the Gallery Method tab.', DFCG_DOMAIN ) );
		}
	}
	
	// Yellow warning messages - not used if Multisite
	if ( !is_multisite() ) {
		
		$class = 'updated';
		$notice = __( 'DCG Notice:', DFCG_DOMAIN );
		
		// Default image folder / URL hasn't been defined
		if( $options_array['populate-method'] == 'id-method' && empty( $options_array['defimgid'] ) ) {
			
			printf( '<div class="%s"><p><strong>%s</strong> %s</p></div>', $class, $notice, __( 'You are using the "ID Method" Gallery Method. Enter the URL of your default image in the ID Method settings in the Gallery Method tab to take advantage of the default image feature.', DFCG_DOMAIN ) );
			
		} elseif( empty( $options_array['defimgfolder'] ) ) {
			
			printf( '<div class="%s"><p><strong>%s</strong> %s</p></div>', $class, $notice, __( 'Enter the Path to your default images folder in the Image Management tab to take advantage of the default image feature.', DFCG_DOMAIN ) );
		}
		
	}
}