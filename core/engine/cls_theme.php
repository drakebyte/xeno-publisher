<?php

//	Print the page
function tpl_render_page() {
	$themepath = tpl_get_theme_folder();
	$themepage = tpl_get_page();
	$extension = '.tpl.php';
	include_once( $themepath . '/ui/header' . $extension );
	include_once( $themepath . '/ui/' . $themepage . $extension );
	include_once( $themepath . '/ui/footer' . $extension );
}

function tpl_get_page() {
	return 'page';
}

function tpl_get_theme_folder() {
	$themename = query_get_setting( 'current_theme' );
	if (file_exists( XENO_SITE . '/design/' . $themename . '/info.txt' )) {
		return '/site/design/' . $themename;
	}
	return '/core/design/prime';
}

function tpl_get_css() {
	return;
}

function tpl_get_js( $location ) {
	return;
}