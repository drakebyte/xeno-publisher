<?php

/**
 * Outputs debug information.
 */
function debug( $data, $label = NULL, $print_r = FALSE ) {
	static $color;
	$color = ( $color == 'A3FFA3' ) ? 'A3DAFF' : 'A3FFA3';
	$debuginfo = debug_backtrace();
	$caller = print_r( $debuginfo[0], true );
	$callerfile = $debuginfo[0]['file'];
	$callerline = $debuginfo[0]['line'];
	$string = check_plain( $print_r ? print_r( $data, TRUE ) : var_export( $data, TRUE ) );

	// Display values with pre-formatting to increase readability.
	$string = '<pre style="font-size:14px;background:#' . $color . ' !important;padding:10px;color:#000 !important;white-space: pre-wrap;white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;">Called from: ' . $callerfile . ' Line: ' . $callerline . '<hr />' . $string . '</pre>';

	print( trim( $label ? "$label: $string" : $string ) );
}

/**
 * Encodes special characters in a plain-text string for display as HTML.
 *
 * Also validates strings as UTF-8 to prevent cross site scripting attacks on
 * Internet Explorer 6.
 */
function check_plain( $text ) {
	return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
}