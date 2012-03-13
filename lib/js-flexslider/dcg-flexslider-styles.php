<?php
/**
 * Front-end - CSS for jQuery Flexslider script
 *
 * @author Ade WALKER  (email : info@studiograsshopper.ch)
 * @copyright Copyright 2008-2012
 * @package dynamic_content_gallery
 * @version 4.0
 *
 *
 * jQuery FlexSlider v1.8
 * http://www.woothemes.com/flexslider/
 * Original CSS Copyright 2012 WooThemes
 *
 *
 * @info Load user-defined, ie dynamic, styles into the header.
 * @info This should ensure XHTML validation.
 *
 * @since 4.0
 */


/* Prevent direct access to this file */
if( !defined( 'ABSPATH' ) ) {
	exit( _( 'Sorry, you are not allowed to access this file directly.' ) );
}
?>


<style type="text/css">
/* Browser Resets */
.flex-container a:active,
.flexslider a:active,
.flex-container a:focus,
.flexslider a:focus  {outline: none;}
.slides,
.flex-control-nav,
.flex-direction-nav {margin: 0; padding: 0; list-style: none;}

/* FlexSlider Necessary Styles
*********************************/
.flexslider {width: 100%; margin: 0; padding: 0;}
.flexslider .slides > li {display: none; -webkit-backface-visibility: hidden;} /* Hide the slides before the JS is loaded. Avoids image jumping */
.flexslider .slides img {max-width: 100%; display: block;}
.flex-pauseplay span {text-transform: capitalize;}

/* Clearfix for the .slides element */
.slides:after {content: "."; display: block; clear: both; visibility: hidden; line-height: 0; height: 0;}
html[xmlns] .slides {display: block;}
* html .slides {height: 1%;}

/* No JavaScript Fallback */
/* If you are not using another script, such as Modernizr, make sure you
 * include js that eliminates this class on page load */
.no-js .slides > li:first-child {display: block;}


/* FlexSlider Default Theme
*********************************/
.flexslider {background: #fff; border: 4px solid #fff; position: relative; -webkit-border-radius: 5px; -moz-border-radius: 5px; -o-border-radius: 5px; border-radius: 5px; zoom: 1;}
.flexslider .slides {zoom: 1;}
.flexslider .slides > li {position: relative;}
/* Suggested container for "Slide" animation setups. Can replace this with your own, if you wish */
.flex-container {zoom: 1; position: relative;}

/* Caption style */
/* IE rgba() hack */
.flex-caption {background:none; -ms-filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#4C000000,endColorstr=#4C000000);
filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#4C000000,endColorstr=#4C000000); zoom: 1;}
.flex-caption {width: 96%; padding: 2%; margin: 0; position: absolute; left: 0; bottom: 0; background: rgba(0,0,0,.3); color: #fff; text-shadow: 0 -1px 0 rgba(0,0,0,.3); font-size: 14px; line-height: 18px;}

/* Direction Nav */
.flex-direction-nav { height: 0; }
.flex-direction-nav li a {width: 52px; height: 52px; margin: -13px 0 0; display: block; background: url(theme/bg_direction_nav.png) no-repeat; position: absolute; top: 50%; cursor: pointer; text-indent: -999em;}
.flex-direction-nav li .next {background-position: -52px 0; right: -21px;}
.flex-direction-nav li .prev {left: -20px;}
.flex-direction-nav li .disabled {opacity: .3; filter:alpha(opacity=30); cursor: default;}

/* Control Nav */
.flex-control-nav {width: 100%; position: absolute; bottom: -30px; text-align: center;}
.flex-control-nav li {margin: 0 0 0 5px; display: inline-block; zoom: 1; *display: inline;}
.flex-control-nav li:first-child {margin: 0;}
.flex-control-nav li a {width: 13px; height: 13px; display: block; background: url(theme/bg_control_nav.png) no-repeat; cursor: pointer; text-indent: -999em;}
.flex-control-nav li a:hover {background-position: 0 -13px;}
.flex-control-nav li a.active {background-position: 0 -26px; cursor: default;}
</style>