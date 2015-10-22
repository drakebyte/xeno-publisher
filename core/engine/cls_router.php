<?php

class Router {
	
	private static $routes = array();
	
	private function __construct() {}
	private function __clone() {}
	
	public static function route($pattern, $callback) {
		$pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
		self::$routes[$pattern] = $callback;
	}
	
	public static function execute($url) {
		foreach (self::$routes as $pattern => $callback) {
			if (preg_match($pattern, $url, $params)) {
				array_shift($params);
				switch ($callback['type']) {
					case 'function':
						return call_user_func($callback['page_callback'], $params);
						break;
					default:
						debug( $callback['type'] . ' IS NOT A VALID CALLBACK TYPE', null,true );
				}
			} else {
				debug( $url . ' IS NOT A VALID PATH', null,true );
			}
		}
	}
}