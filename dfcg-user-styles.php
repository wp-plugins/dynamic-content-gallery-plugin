<?php
/**	This file is part of the DYNAMIC CONTENT GALLERY Plugin
*	*******************************************************
*	Copyright 2008-2009  Ade WALKER  (email : info@studiograsshopper.ch)
*
* 	@package	Dynamic Content Gallery
*	@version	3.0 RC3
*
*	Load user defined styles into the header.
*	This should ensure XHTML validation.
*/
?>

<style type="text/css">
.imageElement {
visibility: hidden;
}
#myGallery, #myGallerySet, #flickrGallery
{
	width: <?php echo $dfcg_options['gallery-width']; ?>px;
	height: <?php echo $dfcg_options['gallery-height']; ?>px;
	border: <?php echo $dfcg_options['gallery-border-thick']; ?>px solid <?php echo $dfcg_options['gallery-border-colour']; ?>;
	background: #000 url('<?php echo WP_PLUGIN_URL; ?>/dynamic-content-gallery-plugin/css/img/loading-bar-black.gif') no-repeat center;
}
.jdGallery .slideInfoZone
{
	height: <?php echo $dfcg_options['slide-height']; ?>px;
}
.jdGallery .slideInfoZone h2
{
	font-size: <?php echo $dfcg_options['slide-h2-size']; ?>px !important;
	margin: <?php echo $dfcg_options['slide-h2-margtb']; ?>px <?php echo $dfcg_options['slide-h2-marglr']; ?>px !important;
	padding: <?php echo $dfcg_options['slide-h2-padtb']; ?>px <?php echo $dfcg_options['slide-h2-padlr']; ?>px !important; 
	color: <?php echo $dfcg_options['slide-h2-colour']; ?> !important;
	}

.jdGallery .slideInfoZone p
{
	padding: <?php echo $dfcg_options['slide-p-padtb']; ?>px <?php echo $dfcg_options['slide-p-padlr']; ?>px !important;
	font-size: <?php echo $dfcg_options['slide-p-size']; ?>px !important;
	margin: <?php echo $dfcg_options['slide-p-margtb']; ?>px <?php echo $dfcg_options['slide-p-marglr']; ?>px !important;
	color: <?php echo $dfcg_options['slide-p-colour']; ?> !important;
}
</style>