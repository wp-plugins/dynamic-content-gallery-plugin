<?php
/**
 * DCG Widget
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2013
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * Allows use of DCG in widgets. Uses Widget API.
 * Code borrowed from Genesis theme framework (www.studiopress.com) and WP core Text Widget.
 *
 * Although this gives multi-widget capability, DCG can only exist once on a page.
 * Remove/comment out any dynamic_content_gallery() template tag in relevant template file (if already used)
 *
 * Allows for a Title above the DCG and a text box below (can accept HTML as per normal Text Widget)
 * Additionally, there are two hooks provided so that you can programmatically add other content
 * above or below the DCG.
 *
 * @since 3.2.2
 * @updated 4.0
 */

/* Prevent direct access to this file */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}


add_action( 'widgets_init', create_function( '', "register_widget('Dynamic_Content_Gallery_Widget');" ) );

class Dynamic_Content_Gallery_Widget extends WP_Widget {

	function Dynamic_Content_Gallery_Widget() {
		$widget_ops = array( 'classname' => 'dfcg', 'description' => 'Displays the Dynamic Content Gallery in a widget.' );
		$control_ops = array( 'width' => 200, 'height' => 300, 'id_base' => 'dfcg-widget' );
		$this->WP_Widget( 'dfcg-widget', 'Dynamic Content Gallery', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract($args);
		
		$instance = wp_parse_args( (array)$instance, array(
			'text' => '',
			'title' => ''
		) );
		
		$text = apply_filters( 'widget_text', $instance['text'], $instance );
		
		echo $before_widget;
		
		// Run Hook
		do_action('dfcg_widget_before');
		
		// Do the title
		if ( !empty($instance['title'] ) )
			echo "\n\t\t\t\t" . $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;

		echo "\n\t\t\t\t" . '<div id="dfcg-widget"><!--Start #dfcg-widget-->' . "\n";
		
		// Display the DCG
		$dfcg = dynamic_content_gallery();
		echo $dfcg;
		
		// Do the Text After
		if( !empty($instance['text'] ) ) {
			echo "\t\t\t\t\t" . '<div class="dfcg-after-gallery">';
			echo $instance['filter'] ? wpautop($text) : $text;
			echo "\t\t\t\t\t" . '</div>';
		}
		
		// Run Hook
		do_action('dfcg_widget_after');
		
		echo "\n\t\t\t\t" . '</div><!--end #dfcg-widget-->' . "\n\n";
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		
		//$instance = $old_instance;
		$new_instance['title'] = strip_tags($new_instance['title']);
		
		// Deal with HTML
		if ( current_user_can( 'unfiltered_html' ) )
			$new_instance['text'] =  $new_instance['text'];
		else
			$new_instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); 
		
		// Deal with wpautop filter
		$new_instance['filter'] = isset($new_instance['filter']);
		
		return $new_instance;
	}

	function form( $instance ) {
	
		$instance = wp_parse_args( (array)$instance, array(
			'text' => '',
			'title' => ''
		) );
		
		$title = strip_tags( $instance['title'] );
		$text = format_to_edit( $instance['text'] );
		?>
		
		<p style="font-size:11px;"><?php _e('If you use this widget, make sure that you do not already have the dynamic_content_gallery() template tag in one of your template files. Setup up the gallery in the', DFCG_DOMAIN); ?> <a href="admin.php?page=<?php echo DFCG_FILE_HOOK; ?>">DCG <?php _e('Settings Page', DFCG_DOMAIN); ?></a>.</p>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title above DCG', DFCG_DOMAIN); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $title ); ?>" style="width:95%;" /></p>

		<p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text after DCG'); ?>:</label><br />
		<textarea id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" style="width: 95%;" rows="6"><?php echo $text; ?></textarea></p>

		<p style="font-size:11px;"><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs to "Text after DCG".'); ?></label></p>		
			
	<?php 
	}
}