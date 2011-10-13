<?php
/**
 * Help for DCG Settings page
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2011
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * @info Uses new screens API introduced in WP 3.3
 *
 * @since 3.0
 */

/**
 * Prevent direct access to this file
 */
if( !defined( 'ABSPATH' ) ) {
	exit( __( 'Sorry, you are not allowed to access this file directly.' ) );
}


add_action('add_screen_help_and_options', 'dfcg_plugin_help');
function dfcg_plugin_help( $screen ) {
	
	global $dfcg_page_hook;

	if ($screen->id != $dfcg_page_hook)
		return;
		
	$screen->add_help_tab( array(
		'id'      => 'sfc-base',
		'title'   => __('Connecting to Facebook', 'sfc'),
		'content' => "HTML for help content",
	));

	$screen->add_help_tab( array(
		'id'      => 'sfc-modules',
		'title'   => __('SFC Modules', 'sfc'),
		'content' => "HTML for help content",
	));

	$screen->add_help_tab( array(
		'id'      => 'sfc-login',
		'title'   => __('Login and Register', 'sfc'),
		'content' => "HTML for help content",
	));	
}