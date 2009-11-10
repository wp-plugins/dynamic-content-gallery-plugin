<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	dynamic_content_gallery
*	@version	3.0 RC4
*
*	Load user defined styles into the header.
*	This should ensure XHTML validation.
*/
?>

<?
/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}
?>

<style type="text/css">
#dfcg_images { visibility: hidden; }

#dfcg_images h3 {
	font-size: <?php echo $dfcg_options['slide-h2-size']; ?>px !important;
	font-weight: <?php echo $dfcg_options['slide-h2-weight']; ?>;
	color: <?php echo $dfcg_options['slide-h2-colour']; ?>;
	padding: <?php echo $dfcg_options['slide-h2-padtb']; ?>px <?php echo $dfcg_options['slide-h2-padlr']; ?>px !important;
	margin: <?php echo $dfcg_options['slide-h2-margtb']; ?>px <?php echo $dfcg_options['slide-h2-marglr']; ?>px !important;
	background: none !important;
	}

#dfcg_images p {
	font-size: <?php echo $dfcg_options['slide-p-size']; ?>px !important;
	color: <?php echo $dfcg_options['slide-p-colour']; ?>;
	line-height: <?php echo $dfcg_options['slide-p-line-height']; ?>px !important;
	padding: <?php echo $dfcg_options['slide-p-padtb']; ?>px <?php echo $dfcg_options['slide-p-padlr']; ?>px !important;
	margin: <?php echo $dfcg_options['slide-p-margtb']; ?>px <?php echo $dfcg_options['slide-p-marglr']; ?>px !important;
	}

#dfcg_images img {
	border: 0;
	}
</style>

<?php
// CSS option not used
?>