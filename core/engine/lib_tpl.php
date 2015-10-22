<?php

//	Print the page
function tpl_render_page() {
	$themename = tpl_get_theme_folder();
	$themepage = tpl_get_page();
	$extension = '.tpl.php';
	include_once( '/site/design/' . $themename . '/front/header' . $extension );
	include_once( '/site/design/' . $themename . '/front/' . $themepage . $extension );
	include_once( '/site/design/' . $themename . '/front/footer' . $extension );
}

function tpl_get_page() {
	return 'page';
}

function tpl_get_theme_folder() {
	$themename = query_get_setting( 'current_theme' );
	if (file_exists( XENO_ROOT . '/site/design/' . $themename . '/info.txt' )) {
		return $themename;
	}
	return 'prime';
}

function tpl_get_css() {
	return;
}

function tpl_get_js( $location ) {
	return;
}