<?php
/**
 * Help for DCG Settings page
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2011
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * @info Uses new Screens API introduced in WP 3.3
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
 * Add contextual help to Admin Bar the new 3.3 way
 *
 * Hooked to 'load-$pagehook', therefore only runs on the DCG Settings page!
 *
 * Requires WP 3.3+
 *
 * @since 4.0
 */
add_action('load-' . DFCG_PAGEHOOK, 'dfcg_plugin_help');
function dfcg_plugin_help() {
	
	$screen = get_current_screen();
		
	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-general',
		'title'   => __( 'General info', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_general'
	));

	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-theme',
		'title'   => __( 'Theme integration', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_theme'
	));

	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-gallery',
		'title'   => __( 'Gallery Method', DFCG_DOMAIN ),
		'callback' => "dfcg_help_gallery"
	));

	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-images',
		'title'   => __( 'Image Management', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_images'
	));
	
	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-desc',
		'title'   => __( 'Descriptions', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_desc'
	));

	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-css',
		'title'   => __( 'Gallery CSS', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_css'
	));

	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-javascript',
		'title'   => __( 'Javascript Options', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_javascript'
	));

	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-load-scripts',
		'title'   => __( 'Load Scripts', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_load_scripts'
	));

	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-troubleshooting',
		'title'   => __( 'Troubleshooting', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_trouble'
	));
}

/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_general() {
?>
	<h3><?php esc_html_e('Dynamic Content Gallery - Quick Help', DFCG_DOMAIN); ?></h3>
	<p><?php esc_html_e('This Quick Help guide highlights some basic points only in order to help you set up the Settings page. Detailed guides to Documentation and Configuration can be found on the plugin\'s site here:', DFCG_DOMAIN); ?></p>
	<p>
		<a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>quick-start-guide/"><?php esc_html_e('Quick Start', DFCG_DOMAIN); ?></a> | 
		<a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>configuration-guide/"><?php esc_html_e('Configuration Guide', DFCG_DOMAIN); ?></a> | 
		<a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>documentation/"><?php esc_html_e('Documentation', DFCG_DOMAIN); ?></a> | 
		<a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>faq/"><?php esc_html_e('FAQ', DFCG_DOMAIN); ?></a> | 
		<a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>error-messages/"><?php esc_html_e('Error Messages', DFCG_DOMAIN); ?></a>
	</p>

	<h3><?php esc_html_e('Understanding the basics', DFCG_DOMAIN); ?></h3>
	<p><?php esc_html_e('Each image in the DCG gallery is associated with a Post or a Page. The selection of posts/Pages is made in the Gallery Method tab. The method the plugin uses to select an image for each of the selected posts/Pages is determined by the options selected in the Image Management tab.', DFCG_DOMAIN); ?></p>

	<h3><?php esc_html_e('2 minute configuration', DFCG_DOMAIN); ?></h3>
	<p><?php esc_html_e('When first installed, the plugin is configured with default settings designed to get you up and running very quickly. For simplicity, the default Gallery Method is set to "One category" with 5 posts, and Image Management is set to "Featured Images". Therefore, to quickly see if the DCG is properly installed and integrated with your theme, go to the Gallery Method tab and select a category which has 5 posts with Featured images, click Save, and then check the front end of your site to confirm that the DCG is displaying properly.', DFCG_DOMAIN); ?></p>
	<p><?php esc_html_e('Now you have the DCG up and running, feel free to configure the options to suit your needs. In case of problems, refer to the Troubleshooting section of this Quick Help Guide.', DFCG_DOMAIN); ?></p>



<?php
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_theme() {

}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_gallery() {
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_images() {
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_desc() {
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_css() {
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_javascript() {
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_load_scripts() {
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_trouble() {
}