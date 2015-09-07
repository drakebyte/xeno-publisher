<?php
/**
 * Root directory of XenoPublisher installation.
 */
define('XENO_ROOT', getcwd());
define('XENO_URL', $_SERVER['SERVER_NAME']);

include_once (XENO_ROOT . '/core/xeno-load.php');

debug($GLOBALS, null,true);

echo 'finished';