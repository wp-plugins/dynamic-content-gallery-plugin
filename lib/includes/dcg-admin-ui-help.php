<?php
/**
 * Help for DCG Settings page
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2013
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
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}


/**
 * Only load the help screens if Wp version is OK
 */
if (dfcg_check_version() ) {
	add_action('load-' . DFCG_PAGEHOOK, 'dfcg_plugin_help');
}


/**
 * Add contextual help to Admin Bar the new 3.3 way
 *
 * Hooked to 'load-$pagehook', therefore only runs on the DCG Settings page!
 *
 * Requires WP 3.3+
 *
 * @since 4.0
 *
 * @global $current_screen object global Screen object
 */
function dfcg_plugin_help() {
	
	global $current_screen;
	
	$sidebar = dfcg_help_sidebar();
	
	$current_screen->set_help_sidebar( $sidebar );
	
	$current_screen->add_help_tab( array(
		'id'      => 'dfcg-help-general',
		'title'   => __( 'General info', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_general'
	));
	
	$current_screen->add_help_tab( array(
		'id'      => 'dfcg-help-theme',
		'title'   => __( 'Theme integration', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_theme'
	));
	
	$current_screen->add_help_tab( array(
		'id'      => 'dfcg-help-quick',
		'title'   => __( 'Quick Start', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_quick'
	));

	$current_screen->add_help_tab( array(
		'id'      => 'dfcg-help-gallery',
		'title'   => __( 'Gallery Method', DFCG_DOMAIN ),
		'callback' => "dfcg_help_gallery"
	));

	$current_screen->add_help_tab( array(
		'id'      => 'dfcg-help-images',
		'title'   => __( 'Image Management', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_images'
	));
	
	$current_screen->add_help_tab( array(
		'id'      => 'dfcg-help-desc',
		'title'   => __( 'Descriptions', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_desc'
	));

	$current_screen->add_help_tab( array(
		'id'      => 'dfcg-help-css',
		'title'   => __( 'Gallery CSS', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_css'
	));

	$current_screen->add_help_tab( array(
		'id'      => 'dfcg-help-scripts',
		'title'   => __( 'Load Scripts', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_scripts'
	));

	$current_screen->add_help_tab( array(
		'id'      => 'dfcg-help-troubleshooting',
		'title'   => __( 'Troubleshooting', DFCG_DOMAIN ),
		'callback' => 'dfcg_help_trouble'
	));
}


/**
 * add_help_sidebar() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_sidebar() {

	$sidebar = '<h3>'.__( 'DCG Resources', DFCG_DOMAIN ) . '</h3>';
	
	$sidebar .= 'Version: ' . DFCG_VER;
	
	$sidebar .= '<ul>';
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'quick-start-guide/">'. __( 'Quick Start', DFCG_DOMAIN ) .'</a></li>'; 
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'configuration-guide/">'. __( 'Configuration Guide', DFCG_DOMAIN ) . '</a></li>';
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'documentation/">'. __( 'Documentation', DFCG_DOMAIN ) . '</a></li>';
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'faq/">'. __( 'FAQ', DFCG_DOMAIN ) . '</a></li>';
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'error-messages/">'. __( 'Error Messages', DFCG_DOMAIN ) . '</a></li>';
	$sidebar .= '<li><a target="_blank" href="'.DFCG_HOME .'changelog/">'. __( 'Change Log', DFCG_DOMAIN ) . '</a></li>';
	$sidebar .= '<li><a target="_blank" href="http://www.studiograsshopper.ch/forum/">'. __( 'Support Forum', DFCG_DOMAIN ) . '</a></li>';
	$sidebar .= '</ul>';
	
	
	$sidebar .= '<ul>';
	$sidebar .= '<li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=10131319">';
	$sidebar .= __( 'Donate', DFCG_DOMAIN ) . '</a></li>';
	$sidebar .= '</ul>';

	return $sidebar;
}

/**
 * General Info - add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_general() {
?>
	<h3><?php _e( 'Dynamic Content Gallery - Quick Help', DFCG_DOMAIN ); ?></h3>
	
	<p><?php _e( 'This Quick Help provides basic tips, accessed via the menu on the left, to help you understand how the plugin works and how to set up the Settings page. Additionally, detailed guides to Documentation, Configuration and other resources can be found via the DCG Resources links in the sidebar ---->', DFCG_DOMAIN ); ?></p>
	
	<p><strong><?php _e( "Inline Help: ", DFCG_DOMAIN ); ?></strong><?php _e( "Many options in the Settings page have a helpful 'Tip' pop-up wherever you see this symbol:", DFCG_DOMAIN ); ?> <img class="inline" src="<?php echo  DFCG_LIB_URL . '/admin-css-js/cluetip/images/help.png'; ?>" alt="" /></p>
	

	<h4><?php _e( 'Understanding the basics', DFCG_DOMAIN ); ?></h4>
	
	<p><?php _e( 'Each image in the DCG gallery is associated with a Post or a Page. The selection of posts/Pages is made in the Gallery Method tab. The Image Management options determine how the plugin selects the image for each Post/Page, either using the Featured Image or a manually chosen image URL entered in the in-post DCG Metabox.', DFCG_DOMAIN ); ?></p>
	
	<h4><?php _e( 'Please support future development!', DFCG_DOMAIN ); ?></h4>
	
	<p><?php _e( 'If you have found this plugin useful and wish to help support its future development, please consider making a donation via the link in the righthand sidebar. Thank you.', DFCG_DOMAIN ); ?></p>

<?php
}


/**
 * Theme Integration - add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_theme() {
?>
	<h3><?php _e( 'Dynamic Content Gallery - Quick Help - Theme Integration', DFCG_DOMAIN ); ?></h3>
	
	<p><?php _e( 'Before doing anything else you need to integrate the DCG in your theme using either the DCG Widget or the DCG Template Tag.', DFCG_DOMAIN ); ?></p>
	
	<h4>DCG Widget</h4>
	
	<p><?php _e( 'Assuming your theme has widget areas on your home page, using the DCG Widget is the simplest method to use as no coding is required. Go to Dashboard > Appearance > Widgets and drag the DCG Widget to an appropriate widget area. You can then add a title (optional) and even some "after" text which will display below the DCG.', DFCG_DOMAIN ); ?> <em><?php _e( 'Note that even if using the DCG Widget, you must set up the various options on the DCG Settings page.', DFCG_DOMAIN ); ?></em></p>
	
	<h4><?php _e( 'DCG Template Tag', DFCG_DOMAIN ); ?></h4>
	
	<p><?php _e( 'Alternatively, you can use the template tag in a theme template, like this:', DFCG_DOMAIN ); ?></p>
	
	<code>&lt;php dynamic_content_gallery(); ?&gt;</code><br /><br />
	
	<p><?php _e( 'Check out this tutorial:', DFCG_DOMAIN ); ?> <a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>configuration-guide/#template-code"><?php _e( 'How to choose the correct theme template when using the DCG Template Tag', DFCG_DOMAIN ); ?></a></p>

<?php
}


/**
 * Quick Help - add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_quick() {
?>
	<h3><?php _e( 'Dynamic Content Gallery - Quick Help - Getting Started', DFCG_DOMAIN ); ?></h3>
	
	<h4><?php _e( '90 second configuration', DFCG_DOMAIN ); ?></h4>
	
	<p><?php printf( '%s <a href="%s">%s</a> %s', __( 'The following assumes that you have followed the', DFCG_DOMAIN ), '#tab-panel-dfcg-help-theme', __( 'Theme Integration', DFCG_DOMAIN ), __( 'instructions.', DFCG_DOMAIN ) ); ?></p>
	
	<p><?php _e( 'When first installed, the plugin is pre-configured with default settings designed to get you up and running very quickly. For simplicity, the Gallery Method is set to "One category" with 5 posts, and Image Management is set to "Featured Images".', DFCG_DOMAIN ); ?></p>
	<p><?php _e( 'Therefore, to quickly see if the DCG is properly installed and integrated with your theme, go to the <span class="bold-italic">Gallery Method</span> tab and select a category which has 5 posts with Featured images, click Save, and then check the front end of your site to confirm that the DCG is displaying properly.', DFCG_DOMAIN ); ?></p>
	
	<p><?php _e( 'Now you have the DCG up and running, feel free to configure the options to suit your needs. In case of problems, refer to the Troubleshooting section of this Quick Help Guide.', DFCG_DOMAIN ); ?></p>
	<p>Still a little lost? Check out the <a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>quick-start-guide/"><?php _e('Quick Start', DFCG_DOMAIN); ?></a> guide.</p>

<?php
}


/**
 * Gallery Method - add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_gallery() {
?>
	<h3><?php _e( 'Dynamic Content Gallery - Quick Help - Gallery Method', DFCG_DOMAIN ); ?></h3>
	
	<p><?php _e( 'For many users the best option is One Category. It is great as a "set and forget" option, because it will always pull in the latest posts from your chosen category. The Custom Post option works in a similar way to the One Category method and is, obviously, the best option for pulling in posts from one Custom Post type. In server performance terms, these two methods are probably the most efficient ones to use.', DFCG_DOMAIN ); ?></p>
	
	<p><?php _e( 'The Multi-Option method is perfect for when you want to mix posts from different categories, for example if you want to feature the latest post from a number of different categories.', DFCG_DOMAIN ); ?></p>
	
	<p><?php _e( 'The ID Method is the most flexible because you can pull in any type of post (normal of Custom) and Pages, and mix them in any order you wish using the Sort Order field in the DCG metabox.', DFCG_DOMAIN ); ?></p>

<?php
}


/**
 * Image Management - add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_images() {
?>
	<h3><?php _e( 'Dynamic Content Gallery - Quick Help - Image Management', DFCG_DOMAIN ); ?></h3>
	
	<p><?php _e( 'Quite simply, the Featured Images option is the best and easiest to use. The manual methods (Full and Partial) are primarily there for reasons of backwards compatibility, for those users who have been using the DCG since its earliest versions.', DFCG_DOMAIN ) ;?></p>
	
	<p><em><?php _e( 'Note: it is quite possible that the manual image management options will be removed in future. Therefore users are encouraged to use the Featured Image option - it is easier and more flexible.', DFCG_DOMAIN ); ?></em></p>
	
	<p><?php _e( 'If you select the Partial URL option you will be prompted to enter the URL to the root folder for your images. You must enter a URL here otherwise the DCG will not find your images.', DFCG_DOMAIN ); ?></p>

<?php
}


/**
 * Descriptions - add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_desc() {
?>
	<h3><?php _e( 'Dynamic Content Gallery - Quick Help - Descriptions', DFCG_DOMAIN ); ?></h3>
	
	<p><?php _e( 'The Description is the text which appears below the post/Page title in the gallery Slide Pane.</p>
	<p>Four different options for creating the description are available: two "auto" options, one "manual" option, and one option to disable the description altogether.', DFCG_DOMAIN ); ?></p>
	
	<p><?php _e( 'If you want total control over the text, check the <span class="bold-italic">Manual</span> option and enter the descriptions in the in-post DCG Metabox for each post/Page. This option also allows you to set up a fallback description which will be displayed if a DCG Metabox description does not exist.', DFCG_DOMAIN ); ?></p>
	
	<p><?php _e( 'The recommended "auto" option is <span class="bold-italic">Auto</span>. This automatically creates a description from the post/Page content and allows you to specify the length of the description and customise its Read More link.', DFCG_DOMAIN ); ?></p>
	
	<p><?php _e( 'The alternative "auto" option is the <span class="bold-italic">Excerpt</span> option. This will display the post/Page Excerpt - either the handcrafted one if it exists, or an automatic Excerpt of the first 55 words of the post/Page. Bear in mind using an automatic Excerpt will probably result in too much text for the Slide Pane, though this can be dealt with by using the WordPress excerpt filter. You can learn more about this filter <a href="http://www.studiograsshopper.ch/code-snippets/customising-the_excerpt/" target="_blank">here</a>.', DFCG_DOMAIN ); ?></p>

<?php
}


/**
 * Gallery CSS - add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_css() {
?>
	<h3><?php _e( 'Dynamic Content Gallery - Quick Help - Gallery CSS', DFCG_DOMAIN ); ?></h3>
	
	<p><?php _e( 'The DCG Settings > Gallery CSS tab gives lots of options for customising the gallery\'s CSS.', DFCG_DOMAIN ); ?></p>
	
	<p><?php _e( 'The most important options here are those for the gallery height and gallery width. Not only do these settings determine the size of the gallery on the page, they also set the size of the images that the DCG automatically creates during the media upload process. Some points to note:', DFCG_DOMAIN ); ?></p>
	<ul>
		<li><?php _e( 'Once the gallery size settings have been saved, automatically sized images will only be created for new images uploaded from that point on.', DFCG_DOMAIN ); ?></li>
		
		<li><?php printf( '%s <a href="%s" target="_blank">%s</a> %s', __( 'To create DCG sized versions of images that are already in the Media Library, use the excellent', DFCG_DOMAIN ), 'http://wordpress.org/extend/plugins/regenerate-thumbnails/', __( 'Regenerate Thumbnails' ), __( 'plugin' ) ); ?>.</li>
		
		<li><?php _e( 'You will be prompted to run the Regenerate Thumbnails plugin whenever you change the gallery dimensions in the DCG Settings > Gallery CSS tab.', DFCG_DOMAIN ); ?></li>
	</ul>
	
	<p><?php _e( 'The default settings of the Gallery CSS tab have been designed to be a good starting point. Apart from the gallery height and width mentioned earlier, the other important setting here is the Slide Pane height setting. Note that when using the jQuery script option, the Slide Pane height is set automatically.', DFCG_DOMAIN ); ?></p>

<?php
}


/**
 * Javascript / Load Scripts - add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_scripts() {
?>
	<h3><?php _e( 'Dynamic Content Gallery - Quick Help - Javascript and Load Scripts', DFCG_DOMAIN ); ?></h3>
	
	<p><?php _e( 'Go to the DCG Settings > Javascript Options tab to set the choice of mootools (default) or jQuery scripts. Note that after changing this settings, click Save, then return to the Javascript Options tab to configure the other options in this tab. You have to do this because the javascript options are not identical for both scripts.', DFCG_DOMAIN ); ?></p>
	
	<p><?php _e( 'The DCG Settings > Load Scripts tab allows you to specify in which pages the javascript and gallery CSS are loaded. These settings ensure that the javascript and CSS are only loaded where needed, and therefore will not slow down the page load on all the other pages on your site.', DFCG_DOMAIN ); ?></p>
	
	<p><?php _e( 'In 99.9% of cases users will display the DCG on their home page. Therefore, if this is the case you for, select the Home page option in the DCG Settings > Load Scripts tab.', DFCG_DOMAIN ); ?></p>

<?php
}


/**
 * Troubleshooting - add_help_tab() callback
 * See dfcg_plugin_help()
 *
 * @since 4.0
 */
function dfcg_help_trouble() {
?>
	<h3><?php _e( 'Dynamic Content Gallery - Quick Help - Troubleshooting', DFCG_DOMAIN ); ?></h3>
	
	<ul>
		<li><?php _e( 'First, go to the DCG Settings > Tools tab and turn on Error Messages. Then refresh your browser and look at the Page Source of the page that contains the DCG. The Error Messages are displayed as HTML comments in the DCG markup and will help you identify where the problem lies.', DFCG_DOMAIN ); ?></li>
		
		<li><?php _e( 'For those not using Featured Images, 99% of problems are caused by incorrect image URLs entered in the DCG Metabox. Check those URLs!', DFCG_DOMAIN ); ?></li>
	
		<li><?php _e( 'If nothing is displayed where you expect to see the DCG, you probably have not integrated the DCG properly into your theme.', DFCG_DOMAIN ); ?></li>
	
		<li><?php _e( 'If the gallery is there on your page, but appears "stuck" with only the loading bar visible, first click open the thumbnail carousel and see if images are there. If you can see the thumbnails in the carousel, then the problem is most likely the URL of the first image in the gallery. If the thumbnails are not visible and/or the carousel isn\'t visible, you probably have a javascript conflict with another plugin. Go to the DCG Settings > Javascript Options tab and switch to jQuery and see what happens.', DFCG_DOMAIN ); ?></li>
	
		<li><?php printf( '%s <a href="%s" target="_blank">%s</a>.', __( 'If all else fails, and you have read all the documentation and checked your settings, post a question on the', DFCG_DOMAIN ), 'http://studiograsshopper.ch/forum/', __( 'support forum', DFCG_DOMAIN ) ); ?></li>
	</ul>

<?php
}