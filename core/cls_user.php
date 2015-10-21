<?php

class User {
	public $id = false;
	public $name = false;
	public $role = false;
	
	public function __construct() {
		$this->LoggedIn();
	}
	public function LoggedIn() {
		// Check if all session variables are set 
		if (isset($_SESSION['user_id'], 
							$_SESSION['username'], 
							$_SESSION['login_string'])) {
	 
			$user_id = $_SESSION['user_id'];
			$login_string = $_SESSION['login_string'];
			$username = $_SESSION['username'];
	 
			// Get the user-agent string of the user.
			$user_browser = $_SERVER['HTTP_USER_AGENT'];
			$password = DB::queryFirstField( query_prefix_table( "SELECT user_password FROM {users} WHERE user_id=%i" ), $user_id );
				if ($password) {
					$login_check = md5($password . $user_browser);
	 
					if ($login_check == $login_string) {
						debug( 'LOGGEDIN', null,true );
						$this->name = $username;
						return true;
					} else {
						debug( 'NOLOGIN1', null,true );
						return false;
					}
				} else {
					debug( 'NOLOGIN2', null,true );
					return false;
				}
		} else {
			debug( 'NOLOGIN3', null,true );
			return false;
		}
	}
}

//	http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL

function login() {
	
	if (isset($_POST['username'], $_POST['password'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		debug( 'LOGIN: started', null,true );
	} else {
		debug( 'LOGIN: invalid request', null,true );
		return false;
	}
	
	if (checkbrute($_POST['username']) == true) {
		debug( 'LOGIN: you is blocked, hacker', null,true );
		return false;
	}
	
	// Using prepared statements means that SQL injection is not possible.
	$logged_user = DB::queryFirstRow( query_prefix_table( "SELECT * FROM {users} WHERE user_name=%s AND user_password=%s" ), $_POST['username'], md5($_POST['password']) );
	
	if ($logged_user) {
		// Password is correct!
		// Get the user-agent string of the user.
		$user_browser = $_SERVER['HTTP_USER_AGENT'];
		// XSS protection as we might print this value
		$user_id = preg_replace("/[^0-9]+/", "", $logged_user['user_id']);
		$_SESSION['user_id'] = $user_id;
		// XSS protection as we might print this value
		$username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
													"", 
													$username);
		$_SESSION['username'] = $username;
		$_SESSION['login_string'] = md5($logged_user['user_password'] . $user_browser);
		debug( 'LOGIN: successful', null,true );
		return true;
	} else {
		// Password is not correct
		// We record this attempt in the database
		$now = time();
		DB::insert(query_prefix_table('{login_attempts}'), array(
				  'user_name' => $_POST['username'],
				  'invalid_time' => $now
				));
		// No user exists.
		debug( 'LOGIN: fail', null,true );
		return false;
	}
}

function checkbrute($user_name) {
	$now = time();
	$valid_attempts_timeframe = $now - (2 * 60 * 60);	// All login attempts are counted from the past 2 hours.
	DB::query(query_prefix_table( "SELECT user_name FROM {login_attempts} WHERE user_name = %s AND invalid_time > %i" ), $user_name, $valid_attempts_timeframe );
	$num_invalid_attempts = DB::count();
	debug( $num_invalid_attempts . ' invalid attempts', null,true );
	if ($num_invalid_attempts > 5) {
		return true;
	} else {
		return false;
	}
}

function login_check() {
	// Check if all session variables are set 
	if (isset($_SESSION['user_id'], 
						$_SESSION['username'], 
						$_SESSION['login_string'])) {
 
		$user_id = $_SESSION['user_id'];
		$login_string = $_SESSION['login_string'];
		$username = $_SESSION['username'];
 
		// Get the user-agent string of the user.
		$user_browser = $_SERVER['HTTP_USER_AGENT'];
		$password = DB::queryFirstField( query_prefix_table( "SELECT user_password FROM {users} WHERE user_id=%i" ), $user_id );
			if ($password) {
				$login_check = md5($password . $user_browser);
 
				if ($login_check == $login_string) {
					debug( 'LOGGEDIN', null,true );
					return true;
				} else {
					debug( 'NOLOGIN1', null,true );
					return false;
				}
			} else {
				debug( 'NOLOGIN2', null,true );
				return false;
			}
	} else {
		debug( 'NOLOGIN3', null,true );
		return false;
	}
}