<?php
/**
* Front-end - CSS for jQuery
*
* @copyright Copyright 2008-2010  Ade WALKER  (email : info@studiograsshopper.ch)
* @package dynamic_content_gallery
* @version 3.2.1
*
* @info Load user defined styles into the header.
* @info This should ensure XHTML validation.
*
* @since
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
	
#dfcg_images p a, #dfcg_images p a:link, #dfcg_images p a:visited {
	color: <?php echo $dfcg_options['slide-p-a-color']; ?> !important;
	font-weight:<?php echo $dfcg_options['slide-p-a-weight']; ?> !important;
	}
	
#dfcg_images p a:hover {
	color: <?php echo $dfcg_options['slide-p-ahover-color']; ?> !important;
	font-weight:<?php echo $dfcg_options['slide-p-ahover-weight']; ?> !important;
	}
</style>

<?php
// CSS option not used
?>