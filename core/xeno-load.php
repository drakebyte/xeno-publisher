<?php
/**
 * @file
 * Functions and variables that need to be loaded on every request.
 */

/**
 * include core libraries.
 */
include_once (XENO_ROOT . '/config/settings.php');
include_once (XENO_ROOT . '/core/database.php');
include_once (XENO_ROOT . '/core/query.php');
include_once (XENO_ROOT . '/core/common.php');

 
/**
 * Define statics and globals
 */
define('VERSION', '1.0');	//	The current system version.
$config = array_key_exists($_SERVER['SERVER_NAME'],$confgroup) ? $confgroup[$_SERVER['SERVER_NAME']] : $confgroup['default'];	//	get the config
define('XENO_STATUS', $config['dev']);	// Status of the project
$GLOBALS['settings'] = xeno_settings_get();	//	load the settings table

/**
 * prepare database.
 */
DB::$user = $config['username'];
DB::$password = $config['password'];
DB::$dbName = $config['database'];
DB::$host = $config['host'];
DB::$port = $config['port'];
DB::$encoding = 'utf8';