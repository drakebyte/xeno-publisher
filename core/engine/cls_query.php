<?php

class XQuery {

	public static function query_get_setting( $name = false ) {
		if ( !$name ) {
			return false;
		}
		$setting = DB::queryFirstField( query_prefix_table( "SELECT setting_value FROM {settings} WHERE setting_name=%s" ), $name );
		return $setting;
	}
}



/**
 * prefix the tables
 * USAGE:
 * wrap the query in this function and wrap the table names into {}
 */
function query_prefix_table($sql) {
	return strtr( $sql, array( '{' => DB_TABLE_PREFIX, '}' => '' ) );
}

/**
 * get all xeno settings into an array
 */

/**
 * Validates the secret parameter from an URL against the one set in the database.
 */
function query_validate_secret() {
	if ( !empty( $_GET['secret'] ) && $_GET['secret'] == DB::queryOneField( 'setting_value', "SELECT * FROM %b WHERE setting_name=%s"), 'settings', 'secret' ) {
		return true;
	}
	return false;
}