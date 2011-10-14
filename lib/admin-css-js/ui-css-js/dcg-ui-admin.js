/**
 * JS for DCG Settings page
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2011
 * @package dynamic_content_gallery
 * @version 4.0
 *
 * Script version 1.0
 *
 * Loaded by dfcg_load_admin_scripts() which is hooked to admin_print_scripts-
 *
 * @since 4.0
 */
// Sliding panels for select options
jQuery(document).ready(function($) {
	
	var panels = [$("#dfcg-panel-image-top"), $("#dfcg-panel-gallery"), $("#dfcg-panel-desc")];

	$.each(panels, function(k,v) {
		v.find('input:radio:checked').live('load change', function() {
			var image_opts = v.find('div.dfcg-panel-image-opts');
			if ( $(this).val() !== 'partial' ) {
				image_opts.hide('fast');
			} else {
				image_opts.show('fast');
			}
			var crop_opts = v.find('tr.dfcg-crop-row');
			if ( $(this).val() !== 'auto' ) {
				crop_opts.hide('fast');
			} else {
				crop_opts.show('fast');
			}
			
		});
		v.find('input:radio:checked').live('load change', function() {
			var multi_opts = v.find('div.dfcg-panel-multi-opts');
			var onecat_opts = v.find('div.dfcg-panel-onecat-opts');
			var id_opts = v.find('div.dfcg-panel-id-opts');
			var cpt_opts = v.find('div.dfcg-panel-cpt-opts');
			if ( $(this).val() !== 'multi-option' ) {
				multi_opts.hide('fast');
			} else {
				multi_opts.show('fast');
			}
			if ( $(this).val() !== 'one-category' ) {
				onecat_opts.hide('fast');
			} else {
				onecat_opts.show('fast');
			}
			if ( $(this).val() !== 'id-method' ) {
				id_opts.hide('fast');
			} else {
				id_opts.show('fast');
			}
			if ( $(this).val() !== 'custom-post' ) {
				cpt_opts.hide('fast');
			} else {
				cpt_opts.show('fast');
			}
		});
		v.find('input:radio:checked').live('load change', function() {
			var desc_opts = v.find('div.dfcg-panel-desc-man-opts');
			if ( $(this).val() !== 'manual' ) {
				desc_opts.hide('fast');
			} else {
				desc_opts.show('fast');
			}
		});
	});
	
	// Controls DCG Settings page Saved message
	$("#setting-error-settings_updated").fadeIn(1000).fadeTo(3000, 1).fadeOut(1000);	
});

// jQuery UI Tabs
jQuery(document).ready(function($) {
	var $tabs = $("#dfcg-tabs").tabs();
				
	$(".dfcg-panel-gallery-link").click(function() { // bind click event to link
    	$tabs.tabs("select", 1); // switch to third tab
    	return false;
	});
	
	$(".dfcg-panel-image-link").click(function() { // bind click event to link
    	$tabs.tabs("select", 2); // switch to second tab
    	return false;
	});
				
	$(".dfcg-panel-desc-link").click(function() { // bind click event to link
    	$tabs.tabs("select", 3); // switch to fourth tab
    	return false;
	});
				
	$(".dfcg-panel-css-link").click(function() { // bind click event to link
    	$tabs.tabs("select", 4); // switch to fifth tab
    	return false;
	});
				
	$(".dfcg-panel-javascript-link").click(function() { // bind click event to link
    	$tabs.tabs("select", 5); // switch to sixth tab
    	return false;
	});
				
	$(".dfcg-panel-scripts-link").click(function() { // bind click event to link
    	$tabs.tabs("select", 6); // switch to seventh tab
    	return false;
	});
				
	$(".dfcg-panel-tools-link").click(function() { // bind click event to link
    	$tabs.tabs("select", 7); // switch to eighth tab
    	return false;
	});
				
	$(".dfcg-panel-help-link").click(function() { // bind click event to link
    	$tabs.tabs("select", 8); // switch to nine tab
    	return false;
	});
});

// Cluetip
jQuery(document).ready(function($) {
	$('a.load-local').cluetip({local:true, cursor: 'pointer', sticky: true, closePosition: 'title', draggable: true, width: 400});
});