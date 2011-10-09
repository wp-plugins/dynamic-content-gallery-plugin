<?php
/**
* Front-end - CSS for jQuery smoothSlideshow script
*
* @copyright Copyright 2008-2011  Ade WALKER  (email : info@studiograsshopper.ch)
* @package dynamic_content_gallery
* @version 4.0
*
* @info Load user defined styles into the header.
* @info This should ensure XHTML validation.
*
* @since 3.3
* @updated 4.0
*/
?>

<?
/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit( __('Sorry, you are not allowed to access this file directly.', DFCG_DOMAIN) );
}
?>
<style type="text/css">	
#dfcg-fullsize {
	border:<?php echo $dfcg_options['gallery-border-thick']; ?>px solid <?php echo $dfcg_options['gallery-border-colour']; ?>;
	height:<?php echo $dfcg_options['gallery-height']; ?>px;
	width:<?php echo $dfcg_options['gallery-width']; ?>px;
	}
	
#dfcg-text {
	background-color:<?php echo $dfcg_options['slide-overlay-color']; ?> !important;
	width:<?php echo $dfcg_options['gallery-width']; ?>px;
	}

#dfcg-text h3 {
	color:<?php echo $dfcg_options['slide-h2-colour']; ?> !important;
	margin:<?php echo $dfcg_options['slide-h2-margtb']; ?>px <?php echo $dfcg_options['slide-h2-marglr']; ?>px !important;
	padding:<?php echo $dfcg_options['slide-h2-padtb']; ?>px <?php echo $dfcg_options['slide-h2-padlr']; ?>px !important;
	font-size:<?php echo $dfcg_options['slide-h2-size']; ?>px !important;
	font-weight:<?php echo $dfcg_options['slide-h2-weight']; ?> !important;
	}

#dfcg-text p {
	color:<?php echo $dfcg_options['slide-p-colour']; ?> !important;
	font-size: <?php echo $dfcg_options['slide-p-size']; ?>px !important;
	line-height:<?php echo $dfcg_options['slide-p-line-height']; ?>px !important;
	margin:<?php echo $dfcg_options['slide-p-margtb']; ?>px <?php echo $dfcg_options['slide-p-marglr']; ?>px !important;
	padding:<?php echo $dfcg_options['slide-p-padtb']; ?>px <?php echo $dfcg_options['slide-p-padlr']; ?>px !important;
	}
	
#dfcg-text p a, #dfcg-text p a:link, #dfcg-text p a:visited {
	color: <?php echo $dfcg_options['slide-p-a-color']; ?> !important;
	font-weight:<?php echo $dfcg_options['slide-p-a-weight']; ?> !important;
	}

#dfcg-text p a:hover {
	color: <?php echo $dfcg_options['slide-p-ahover-color']; ?> !important;
	font-weight:<?php echo $dfcg_options['slide-p-ahover-weight']; ?> !important;
	}
	
.dfcg-imgnav {
	height:<?php echo $dfcg_options['gallery-height']; ?>px;
	}
		
#dfcg-slidearea {
	width:<?php echo $dfcg_options['gallery-width']; ?>px;
	}
	
#dfcg-thumbnails .dfcg-sliderContainer {
    width: <?php echo $dfcg_options['gallery-width']; ?>px;
	}
</style>
<?php
// CSS option not used
?>