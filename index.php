<?php
/**
 * Root directory of XenoPublisher installation.
 */
define('XENO_ROOT', getcwd());
define('XENO_URL', $_SERVER['SERVER_NAME']);

include_once (XENO_ROOT . '/core/xeno-load.php');

debug(XENO_TABLE_PREFIX, null,true);
debug(xeno_prefix_table("SELECT * FROM {settings} WHERE name=%s"), null,true);

echo 'finished';