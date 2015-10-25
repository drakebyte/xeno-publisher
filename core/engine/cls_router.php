<?php

class Router {
	
	public $path = false;
	public $routes = array();
	
	public function __construct() {
		$this->RouteInit();
		$this->RequestPath();
	}
	
	public function RouteInit() {
		$DBroutes = DB::query( "SELECT * FROM %b", 'path' );
		foreach ($DBroutes as $k => $v) {
			debug( $v, null,true );
			$pattern = '/^' . str_replace('/', '\/', $v['path_url']) . '$/';
			$this->routes[$pattern] = $v;
		}
	}
	
	public function Route($pattern, $callback) {
		$pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
		$this->routes[$pattern] = $callback;
	}
	
	public function Execute() {
		debug( $this->routes, null,true );
		foreach ( $this->routes as $pattern => $callback ) {
			if (preg_match($pattern, $this->path, $params)) {
				array_shift($params);
				switch ($callback['path_type']) {
					case 'function':
						return call_user_func($callback['path_callback'], $params);
						break;
					default:
						debug( $callback['path_type'] . ' IS NOT A VALID CALLBACK TYPE', null,true );
				}
			}
		}
		debug( $this->path . ' IS NOT A VALID PATH', null,true );
	}
	public function RequestPath() {

		if (isset($_GET['q']) && is_string($_GET['q'])) {
			$this->path = $_GET['q'];
		}
		elseif (isset($_SERVER['REQUEST_URI'])) {
			$request_path = strtok($_SERVER['REQUEST_URI'], '?');
			$base_path_len = strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/'));
			$this->path = substr(urldecode($request_path), $base_path_len + 1);
			if ($this->path == basename($_SERVER['PHP_SELF'])) {
				$this->path = '';
			}
		}
		else {
			$this->path = '';
		}
		$this->path = trim($this->path, '/');
	}
}