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
	
	$sidebar = dfcg_help_sidebar();
	
	$screen->add_help_sidebar( $sidebar );
	
	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-general',
		'title'   => __( 'General info', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_general'
	));

	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-quick',
		'title'   => __( 'Quick Start', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_quick'
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
		'id'      => 'dfcg-help-scripts',
		'title'   => __( 'Load Scripts', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_scripts'
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
function dfcg_help_sidebar() {

	$sidebar = '<h3>'.__('DCG Resources', DFCG_DOMAIN) . '</h3>';
	
	$sidebar .= 'Version: ' . DFCG_VER;
	
	$sidebar .= '<ul>';
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'quick-start-guide/">'. __('Quick Start', DFCG_DOMAIN) .'</a></li>'; 
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'configuration-guide/">'. __('Configuration Guide', DFCG_DOMAIN) . '</a></li>';
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'documentation/">'. __('Documentation', DFCG_DOMAIN) . '</a></li>';
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'faq/">'. __('FAQ', DFCG_DOMAIN) . '</a></li>';
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'error-messages/">'. __('Error Messages', DFCG_DOMAIN) . '</a></li>';
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'changelog/">'. __('Change Log', DFCG_DOMAIN) . '</a></li>';
	$sidebar .= '<li><a target="_blank" href="http://www.studiograsshopper.ch/forum/">'. __('Support Forum', DFCG_DOMAIN) . '</a></li>';
	$sidebar .= '</ul>';

	return $sidebar;
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
	
	<p><?php esc_html_e('This Quick Help provides basic tips, accessed via the menu on the left, to help you understand how the plugin works and how to set up the Settings page. Additionaly, detailed guides to Documentation, Configuration and other resources can be found via the links in the sidebar -->', DFCG_DOMAIN); ?></p>
	
	<p><strong><?php esc_html_e("Don't forget! ", DFCG_DOMAIN); ?></strong><?php esc_html_e("Nearly every option in the Settings page has a helpful 'Tip' pop-up wherever you see this symbol:", DFCG_DOMAIN); ?> <img class="inline" src="<?php echo  DFCG_LIB_URL . '/admin-css-js/cluetip/images/help.png'; ?>" alt="" /></p>
	

	<h4><?php esc_html_e('Understanding the basics', DFCG_DOMAIN); ?></h4>
	
	<p><?php esc_html_e('Each image in the DCG gallery is associated with a Post or a Page. The selection of posts/Pages is made in the Gallery Method tab. The Image Management options determine how the plugin associates images with Posts/Pages, either using the Featured Image, or manually by entering an image URL in the in-post DCG Metabox.', DFCG_DOMAIN); ?></p>
	
	<h4><?php esc_html_e('Still totally lost?', DFCG_DOMAIN); ?></h4>
	
	<p><?php esc_html_e('If you have read all the documentation and are still having problems, try the ', DFCG_DOMAIN); ?> <a target="_blank" href="http://www.studiograsshopper.ch/forum/"><?php esc_html_e('Support Forum', DFCG_DOMAIN); ?></a></p>

<?php
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_quick() {
?>
	<h3><?php esc_html_e('Dynamic Content Gallery - Quick Help - Getting Started', DFCG_DOMAIN); ?></h3>
	
	<h4><?php esc_html_e('2 minute configuration', DFCG_DOMAIN); ?></h4>
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
?>
	<h3><?php esc_html_e('Dynamic Content Gallery - Quick Help - Theme Integration', DFCG_DOMAIN); ?></h3>
	<p><?php esc_html_e('There are two methods you can use for integrating (ie displaying) the DCG in your theme - either the DCG Widget or a Template Tag.', DFCG_DOMAIN); ?></p>
	<h4>DCG Widget</h4>
	<p><?php esc_html_e('Assuming your theme has widget areas on your home page, using the DCG Widget is the simplest method to use as no coding is required. Go to Dashboard > Appearance > Widgets and drag the DCG Widget to an appropriate widget area. You can then add a title (optional) and even some "after" text which will display below the DCG.', DFCG_DOMAIN); ?></p>
	<p><?php esc_html_e('Note that even if using the DCG Widget, you must set up the various options on the DCG Settings page.', DFCG_DOMAIN); ?></p>
	<h4>Template Tag</h4>
	<p>For the template tag, add this to your theme:</p>
	<code>&lt;php dynamic_content_gallery(); ?&gt;</code>

<?php
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_gallery() {
?>
	<h3><?php esc_html_e('Dynamic Content Gallery - Quick Help - Gallery Method', DFCG_DOMAIN); ?></h3>

<?php
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_images() {
?>
	<h3><?php esc_html_e('Dynamic Content Gallery - Quick Help - Image Management', DFCG_DOMAIN); ?></h3>

<?php
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_desc() {
?>
	<h3><?php esc_html_e('Dynamic Content Gallery - Quick Help - Descriptions', DFCG_DOMAIN); ?></h3>

<?php
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_css() {
?>
	<h3><?php esc_html_e('Dynamic Content Gallery - Quick Help - Gallery CSS', DFCG_DOMAIN); ?></h3>

<?php
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_scripts() {
?>
	<h3><?php esc_html_e('Dynamic Content Gallery - Quick Help - Javascript and Load Scripts', DFCG_DOMAIN); ?></h3>

<?php
}


/**
 * add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_trouble() {
?>
	<h3><?php esc_html_e('Dynamic Content Gallery - Quick Help - Troubleshooting', DFCG_DOMAIN); ?></h3>

<?php
}