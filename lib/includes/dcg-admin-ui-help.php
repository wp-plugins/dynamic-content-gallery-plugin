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
 * @TODO Clean this up!
 * @since 4.0
 */
add_action('load-' . DFCG_PAGEHOOK, 'dfcg_plugin_help');
function dfcg_plugin_help( $screen ) {
	
	$screen = get_current_screen();

	if ($screen->id != DFCG_PAGEHOOK)
		return;
		
	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-general',
		'title'   => __('General info', 'sfc'),
		'callback' => 'dfcg_help_general'
	));

	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-images',
		'title'   => __('Image Management', 'sfc'),
		//'content' => "HTML for help content",
		'callback' => 'dfcg_help_general'
	));

	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-gallery',
		'title'   => __('Gallery Method', 'sfc'),
		'content' => "HTML for help content",
	));
	
	$screen->add_help_tab( array(
		'id'      => 'dfcg-help-desc',
		'title'   => __('Descriptions', 'sfc'),
		'content' => "HTML for help content",
	));
}



function dfcg_help_general() {
?>
	<h3><?php esc_html_e('Dynamic Content Gallery - Quick Help', DFCG_DOMAIN); ?></h3>
	<p><?php esc_html_e('This Quick Help guide highlights some basic points only. Detailed guides to Documentation and Configuration can be found on the plugin\'s site here:', DFCG_DOMAIN); ?></p>
	<p>
		<a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>quick-start-guide/"><?php esc_html_e('Quick Start', DFCG_DOMAIN); ?></a> | 
		<a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>configuration-guide/"><?php esc_html_e('Configuration Guide', DFCG_DOMAIN); ?></a> | 
		<a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>documentation/"><?php esc_html_e('Documentation', DFCG_DOMAIN); ?></a> | 
		<a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>faq/"><?php esc_html_e('FAQ', DFCG_DOMAIN); ?></a> | 
		<a class="off-site" target="_blank" href="<?php echo DFCG_HOME; ?>error-messages/"><?php esc_html_e('Error Messages', DFCG_DOMAIN); ?></a>
	</p>
<?php
}