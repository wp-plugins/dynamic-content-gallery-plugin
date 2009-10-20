<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	Dynamic Content Gallery
*	@version	3.0 RC3
*
*	Admin Settings Page options validation functions
*
*	@since	3.0
*
*/


/**	Function for validating user input on submit of Settings page form
*	
*	Prints validation messages to the Settings Page.
*
*	@param	array	$options_array, the options from $_POST variable
*
*	@since	3.0	
*/
function dfcg_on_submit_validation($options_array) {

	// If Partial URL is selected, imageurl must be defined
	if( $options_array['image-url-type'] == 'part' && empty($options_array['imageurl']) ) {
		echo '<div id="message" class="error"><p><strong>' . __('Error: You have selected "Partial" URL option in the <a href="#image-file">Image File Management settings</a>, but you have not defined the URL to your images folder.<br />Please enter the URL to your images folder in the <a href="#image-file">Image File Management settings</a>.') . '</strong></p></div>';
	}
	
	// If Pages, Page ID's must be defined
	if( $options_array['populate-method'] == 'pages' && empty($options_array['pages-selected']) ) {
		echo '<div id="message" class="error"><p><strong>' . __('Error: You have selected "Pages" <a href="#gallery-method">Gallery Method</a>, but you have not defined any Page ID\'s.<br />Please enter at least two valid Page ID\'s in <a href="#pages-method">Pages Settings</a>.') . '</strong></p></div>';
	}
	
	if ( function_exists('wpmu_create_blog') ) {
		// We're in WPMU, so ignore these messages
	} else {
		// If Multi Option, defimgmulti should be defined
		if( $options_array['populate-method'] == 'multi-option' && empty($options_array['defimgmulti']) ) {
			echo '<div id="message" class="updated"><p><strong>' . __('Warning: You have selected the "Multi Option" <a href="#gallery-method">Gallery Method</a>. Enter the Path to your Category default images folder in the <a href="#multi-option">Multi Option Settings</a> to take advantage of the default image feature.') . '</strong></p></div>';
		}
		
		// If One Category, defimgonecat should be defined
		if( $options_array['populate-method'] == 'one-category' && empty($options_array['defimgonecat']) ) {
			echo '<div id="message" class="updated"><p><strong>' . __('Warning: You have selected the "One Category" <a href="#2">Gallery Method</a>. Enter the Path to your Category default images folder in the <a href="#one-category">One Category Settings</a> to take advantage of the default image feature.') . '</strong></p></div>';
		}
		
		// If Pages, defimgpages should be defined
		if( $options_array['populate-method'] == 'pages' && empty($options_array['defimgpages']) ) {
			echo '<div id="message" class="updated"><p><strong>' . __('Warning: You have selected the "Pages" <a href="#2">Gallery Method</a>. Enter the URL of your default image in the <a href="#pages-method">Pages Settings</a> to take advantage of the default image feature.') . '</strong></p></div>';
		}
	}
	
	// deal with multioption Post Selects
	$multioption_raw_offsets = array (
		$options_array['off01'],
		$options_array['off02'],
		$options_array['off03'],
		$options_array['off04'],
		$options_array['off05'],
		$options_array['off06'],
		$options_array['off07'],
		$options_array['off08'],
		$options_array['off09'],
		);
		
	$multioption_offsets = array();
		
	foreach( $multioption_raw_offsets as $key => $value ) {
		$raw_offset = $multioption_raw_offsets[$key];
		if( !empty($raw_offset) ) {
			$temp_array = $multioption_raw_offsets[$key];
			array_push($multioption_offsets, $temp_array);
			unset($temp_array);
		}
	}
		
	if( $options_array['populate-method'] == 'multi-option' && count($multioption_offsets) < 2 ) {
		echo '<div id="message" class="error"><p><strong>' . __('Error: You have selected the "Multi Option" <a href="#gallery-method">Gallery Method</a>. You must enter at least 2 Posts Selects in the <a href="#multi-option">Multi Option Settings</a>.') . '</strong></p></div>';
	}

	// End of validation checks
}


/**	Function for validation on fresh load of Settings Page (not after Submit)
*	
*	Prints validation messages to the Settings Page.
*
*	@param	array	$options_array, options from db
*
*	@since	3.0	
*/
function dfcg_on_load_validation($options_array) {

	// If Partial URL is selected, imageurl must be defined
	if( $options_array['image-url-type'] == 'part' && empty($options_array['imageurl']) && !isset($_POST['info_update']) ) {
		echo '<div id="message" class="error"><p><strong>' . __('Reminder! <a name=""></a>You are using the "Partial" URL option in the <a href="#image-file">Image File Management settings</a>. You must enter the URL to your images folder in <a href="#image-file">Section 1</a>.') . '</strong></p></div>';
	}
	
	// If Pages, Page ID's must be defined
	if( $options_array['populate-method'] == 'pages' && empty($options_array['pages-selected']) && !isset($_POST['info_update']) ) {
		echo '<div id="message" class="error"><p><strong>' . __('Reminder!: You are using the "Pages" <a href="#gallery-method">Gallery Method</a>. You must enter at least two valid Page ID\'s in <a href="#pages-method">Section 2.3</a>.') . '</strong></p></div>';
	}
	
	if ( function_exists('wpmu_create_blog') ) {
		// We're in WPMU, so ignore these messages
	} else {
		// If Multi Option, defimgmulti should be defined
		if( $options_array['populate-method'] == 'multi-option' && empty($options_array['defimgmulti']) && !isset($_POST['info_update'])) {
			echo '<div id="message" class="updated"><p><strong>' . __('Reminder! You are using the "Multi Option" <a href="#gallery-method">Gallery Method</a>. Enter the Path to your Category default images folder in the <a href="#multi-option">Multi Option</a> section to take advantage of the default image feature.') . '</strong></p></div>';
		}
		
		// If One Category, defimgonecat should be defined
		if( $options_array['populate-method'] == 'one-category' && empty($options_array['defimgonecat']) && !isset($_POST['info_update']) ) {
			echo '<div id="message" class="updated"><p><strong>' . __('Reminder! You are using the "One Category" <a href="#gallery-method">Gallery Method</a>. Enter the Path to your Category default images folder in the <a href="#one-category">One Category</a> section to take advantage of the default image feature.') . '</strong></p></div>';
		}
	
		// If Pages, defimgpages should be defined
		if( $options_array['populate-method'] == 'pages' && empty($options_array['defimgpages']) && !isset($_POST['info_update']) ) {
			echo '<div id="message" class="updated"><p><strong>' . __('Reminder! You are using the "Pages"  <a href="#2">Gallery Method</a>. Enter the URL of your default image in the <a href="#pages-method">Pages</a> section to take advantage of the default image feature.') . '</strong></p></div>';
		}
	}
	
	// deal with multioption Post Selects
	$multioption_raw_offsets = array (
		$options_array['off01'],
		$options_array['off02'],
		$options_array['off03'],
		$options_array['off04'],
		$options_array['off05'],
		$options_array['off06'],
		$options_array['off07'],
		$options_array['off08'],
		$options_array['off09'],
		);
		
	$multioption_offsets = array();
		
	foreach( $multioption_raw_offsets as $key => $value ) {
		$raw_offset = $multioption_raw_offsets[$key];
		if( !empty($raw_offset) ) {
			$temp_array = $multioption_raw_offsets[$key];
			array_push($multioption_offsets, $temp_array);
			unset($temp_array);
		}
	}
		
	if( $options_array['populate-method'] == 'multi-option' && count($multioption_offsets) < 2 && !isset($_POST['info_update']) ) {
		echo '<div id="message" class="error"><p><strong>' . __('Reminder! You are using the "Multi Option" <a href="#gallery-method">Gallery Method</a>. You must enter at least 2 Posts Selects in <a href="#multi-option">Multi Option Settings</a>..') . '</strong></p></div>';
	}
}
