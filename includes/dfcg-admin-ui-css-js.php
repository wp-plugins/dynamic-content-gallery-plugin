<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.1
*
*	Admin Settings page CSS and Javascript
*
*	@since	3.0
*
*/

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}


/**	Function for loading JS and CSS for Settings Page
*	
*	Code idea from Nathan Rice, Theme Options plugin.
*
*	@since	3.0	
*/
function dfcg_options_css_js() {
echo <<<CSS

<style type="text/css">
.form-table th {font-size:11px;}
.metabox-holder {float:left;margin:0;padding:0;}
.sgr-credits {border-top:1px solid #CCCCCC;margin:10px 0px 0px 0px;padding:10px 10px 0px 0px;float:left;}
.sgr-credits p {font-size:11px;}
#sgr-info {float:right;width:260px;background:#f9f9f9;padding:0px 20px 10px 20px;margin:20px 10px 10px 10px;border:1px solid #DFDFDF;}
#sgr-info ul {list-style-type:none;margin-left:0px;}
#sgr-info img {float:left;margin:0px 10px 10px 0px;border:none;}
#sgr-info input {float:right;margin:0px 0px 10px 10px;}
#sgr-info h4 {font-size:12px;}
div.inside {padding: 0px 10px 10px 10px;margin:0px;}
.inside p {font-size:11px;padding:0px 0px 0px 0px;line-height:20px;}
.inside table p {margin:0;padding:0;}
.inside ul {list-style-type:disc;margin-left:30px;font-size:11px;}
.inside h4 {font-size:11px;margin:1em 0;}
.postbox-sgr {padding:0px 10px;margin:0px;}
.error p, .updated p {font-size:11px;line-height:20px;}
.dfcg-tip {margin:0px;padding:10px 0px 0px 0px;}
.dfcg-tip-box {font-size:11px;line-height:20px;font-style:italic;margin:0 80px 0 0;
-moz-border-radius-topleft: 5px;
-moz-border-radius-topright: 5px;
-moz-border-radius-bottomleft: 5px;
-moz-border-radius-bottomright: 5px;
-khtml-border-radius: 5px;
-webkit-border-top-left-radius: 5px;
-webkit-border-top-right-radius: 5px;
-webkit-border-bottom-left-radius: 5px;
-webkit-border-bottom-right-radius: 5px;
padding:1px 0 1px 5px;border:1px solid #e9e9e9;background:#eeffff;}
.key_settings {color:#D53131;}
.help-outer p, .help-outer ul, .help-outer li {font-size:11px;padding:0px 0px 0px 0px;line-height:15px;}
.help-outer h4 {margin:0px 0px 10px 0px;}
.bold-italic {font-weight:bold;font-style:italic;}
</style>

CSS;
echo <<<JS

<script type="text/javascript">
jQuery(document).ready(function($) {
	$(".fade").fadeIn(1000).fadeTo(3000, 1).fadeOut(1000);
});
</script>

JS;
}
