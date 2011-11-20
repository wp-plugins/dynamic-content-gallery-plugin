<?php
/**
 * Uninstall file as per WP 2.7+
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2011
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * Removes options from db when plugin is deleted via Dashboard
 *
 * @since 3.2
 */

/* Prevent direct access to this file */
if( !defined( 'ABSPATH' ) ) {
	exit( __( 'Sorry, you are not allowed to access this file directly.' ) );
}

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

// Delete options from database
delete_option( 'dfcg_plugin_settings' );
delete_option( 'dfcg_version' );