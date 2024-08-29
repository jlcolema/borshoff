<?php
///////////////////////////////////////
// READ THIS
// This is a fallback template for pages in the system.
// It is strictly for displaying the subpages of About and Careers
// Because this type of page is displayed in the flyout menus, this
// template is in place only to provide a hook to get back into
// the AJAX when someone visits the permalink for one of these
// flyout pages. The ajax.js (highest version number) will grab the
// url and display the appropriate flyouts. It's complicated...
///////////////////////////////////////

$pageParent = get_topmost_ancestor($post->ID);

// handle the about derivative pages
if( $pageParent == 14 ){
	require_once('page-about.php');
	die();
}

// handle the careers derivative pages
if( $pageParent == 18 ){
	require_once('page-careers.php');
	die();
}

// handle the blog homepage	
if( is_home() ){
	require_once('index.php');
	die();
}