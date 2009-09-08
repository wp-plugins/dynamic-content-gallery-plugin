<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	Dynamic Content Gallery
*	@version	3.0
*
*	Uninstall file for WP 2.7+
*	
*/
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}
delete_option('dfcg_plugin_settings');
delete_option('dfcg_version');
?>