<?php
/**
 * @file
 * Functions and variables that need to be loaded on every request.
 */

/**
 * include core libraries.
 */
include_once ( XENO_ROOT . '/site/settings.php' );

include_once ( XENO_ROOT . '/core/engine/cls_db.php' );
include_once ( XENO_ROOT . '/core/engine/cls_user.php' );
include_once ( XENO_ROOT . '/core/engine/cls_hook.php' );
include_once ( XENO_ROOT . '/core/engine/cls_router.php' );
include_once ( XENO_ROOT . '/core/engine/cls_validate.php' );
include_once ( XENO_ROOT . '/core/engine/cls_streamfile.php' );

include_once ( XENO_ROOT . '/core/engine/lib_tpl.php' );
include_once ( XENO_ROOT . '/core/engine/lib_query.php' );
include_once ( XENO_ROOT . '/core/engine/lib_common.php' );
include_once ( XENO_ROOT . '/core/engine/lib_formatting.php' );
include_once ( XENO_ROOT . '/core/engine/lib_shortcodes.php' );

 
/**
 * Define statics and globals
 */
$config = array_key_exists($_SERVER['SERVER_NAME'],$confgroup) ? $confgroup[$_SERVER['SERVER_NAME']] : $confgroup['default'];	//	get the config

define( 'VERSION',			'1.0');
define( 'DB_ENCODING',		'utf8' );
define( 'XENO_STATUS',		$config['dev'] );
define( 'DB_HOST',			$config['host'] );
define( 'DB_PORT',			$config['port'] );
define( 'DB_TABLE_PREFIX',	$config['prefix'] );
define( 'DB_USER',			$config['username'] );
define( 'DB_PASS',			$config['password'] );
define( 'DB_NAME',			$config['database'] );

/**
 * prepare database.
 */
DB::$user		=	DB_USER;
DB::$password	=	DB_PASS;
DB::$dbName		=	DB_NAME;
DB::$host		=	DB_HOST;
DB::$port		=	DB_PORT;
DB::$encoding	=	DB_ENCODING;

//	start a secure session
sec_session_start();

//	defining global classes
$hook = new Hook;
$user = new User;