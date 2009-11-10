<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.0 RC4
*
*	Uninstall file for WP 2.7+
*	
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit("Sorry, you are not allowed to access this file directly.");
}

if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}
// Delete options from database
delete_option('dfcg_plugin_settings');
delete_option('dfcg_version');
?>