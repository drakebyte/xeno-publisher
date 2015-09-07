<?php

/**
 * prefix the tables
 * USAGE:
 * wrap the query in this function and wrap the table names into {}
 */
function xeno_prefix_table($sql) {
	return strtr($sql, array('{' => XENO_TABLE_PREFIX, '}' => ''));
}


/**
 * get all xeno settings into an array
 */
function xeno_settings_get() {
	return array();
}