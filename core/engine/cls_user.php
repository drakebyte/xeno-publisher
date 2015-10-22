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
						$this->name = $username;
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
		} else {
			return false;
		}
	}
	

	public function LogIn() {
		global $hook;
		if (isset($_POST['username'], $_POST['password'])) {			
			$login['username'] = $_POST['username'];
			$password = $_POST['password']; 	//	our DB object does all the security needed to protect against injections
		} else {
			return false;
		}
		
		if ($this->CheckBrute($login['username']) == true) {
			return false;
		}
		
		// Using prepared statements means that SQL injection is not possible.
		$logged_user = DB::queryFirstRow( query_prefix_table( "SELECT * FROM {users} WHERE user_name=%s AND user_password=%s" ), $login['username'], md5($password) );
		
		if ($logged_user) {
			// Password is correct!
			// Get the user-agent string of the user.
			$user_browser = $_SERVER['HTTP_USER_AGENT'];
			// XSS protection as we might print this value
			$user_id = preg_replace("/[^0-9]+/", "", $logged_user['user_id']);
			$_SESSION['user_id'] = $user_id;
			// XSS protection as we might print this value
			$login['username'] = preg_replace("/[^a-zA-Z0-9_\-]+/", 
														"", 
														$login['username']);
			$_SESSION['username'] = $login['username'];
			$_SESSION['login_string'] = md5($logged_user['user_password'] . $user_browser);
			
			$hook->do_action( 'hook_registration_success', $login );
			debug( "LOGIN: SUCCESS", null,true );
			return true;
		} else {
			// Password is not correct
			// We record this attempt in the database
			$now = time();
			DB::insert(query_prefix_table('{login_attempts}'), array(
					  'user_name' => $login['username'],
					  'invalid_time' => $now
					));
			// No user exists.
			return false;
		}
	}
	
	public function LogOut() {
		// Unset all session values 
		$_SESSION = array();
		 
		// get session parameters 
		$params = session_get_cookie_params();
		 
		// Delete the actual cookie. 
		setcookie(session_name(),
				'', time() - 42000, 
				$params["path"], 
				$params["domain"], 
				$params["secure"], 
				$params["httponly"]);
		 
		// Destroy session 
		session_destroy();
		header('Location: /index.php');
	}
	
	public function RegisterUser() {
		global $hook;
		if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
			
			$validation_passed = true;
			$validation_errors = array();
			$newborn['username'] = $_POST['username'];
			$newborn['email'] = $_POST['email'];
			$newborn['password'] = $_POST['password'];
			
			//	logged in users cannot create new accounts
			if ($this->name) {
				$validation_passed = false;
				$validation_errors[] = "LOG OUT BEFORE CREATING NEW ACCOUNT";
			}
			
			// Sanitize and validate the data passed in
			if (!ValidateString::AlphaNumericUnderscore($newborn['username'])) {
				$validation_passed = false;
				$validation_errors[] = "INVALID USERNAME";
			}
			if (!ValidateString::Email($newborn['email'])) {
				$validation_passed = false;
				$validation_errors[] = "INVALID EMAIL";
			}
			//	we don't need duplicates
			if( DB::queryFirstField( query_prefix_table( "SELECT user_id FROM {users} WHERE user_name=%s" ), $newborn['username'] )) {
				$validation_passed = false;
				$validation_errors[] = "USER EXISTS";
			}
			if( DB::queryFirstField( query_prefix_table( "SELECT user_id FROM {users} WHERE user_email=%s" ), $newborn['email'] )) {
				$validation_passed = false;
				$validation_errors[] = "EMAIL EXISTS";
			}
			if(!$validation_passed) {
				$validation_errors[] = "REGISTER FAILED";
				debug( $validation_errors, null,true );
				return false;
			}
			
			// Insert the new user into the database
			$newborn['encryptedpassword'] = md5($newborn['password']);
			DB::insert(query_prefix_table('{users}'), array(
					  'user_name' => $newborn['username'],
					  'user_password' => $newborn['encryptedpassword'],
					  'user_email' => $newborn['email'],
					  'user_created' => time(),
					  'user_status' => 1,
					  'lang_name' => 1,
					));
			$newborn['user_id'] = DB::insertId();
			
			$hook->do_action( 'hook_registration_success', $newborn );
			
			debug( "REGISTER: USER CREATED. You can now login.", null,true );
			return true;
		}
	}
	

	public function CheckBrute($user_name) {
		$now = time();
		$valid_attempts_timeframe = $now - (2 * 60 * 60);	// All login attempts are counted from the past 2 hours.
		DB::query(query_prefix_table( "SELECT user_name FROM {login_attempts} WHERE user_name = %s AND invalid_time > %i" ), $user_name, $valid_attempts_timeframe );
		$num_invalid_attempts = DB::count();
		if ($num_invalid_attempts > 5) {
			return true;
		} else {
			return false;
		}
	}
}