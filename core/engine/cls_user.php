<?php

class User extends DB {
	public $id = false;
	public $name = false;
	public $roles = false;
	public $auth = false;
	
	public function __construct() {
		$this->auth = $this->CheckLoggedIn();
		$this->BuildUserObject();
	}
	private function CheckLoggedIn() {
		// Check if all session variables are set 
		if (isset(	$_SESSION['user_id'], 
					$_SESSION['login_string'])) {
	 
			$this->id = $_SESSION['user_id'];
			$login_string = $_SESSION['login_string'];
	 
			// Get the user-agent string of the user.
			$user_browser = $_SERVER['HTTP_USER_AGENT'];
			$user = DB::queryFirstRow( "SELECT user_password, user_status FROM %b WHERE user_id=%i", 'user', $this->id );
				if ($user['user_password'] && $user['user_status'] == 1) {
					$login_check = md5($user['user_password'] . $user_browser);
	 
					if ($login_check == $login_string) {
						return 1;
					} else {
						$this->LogOut();
						return false;
					}
				} else {
					$this->LogOut();
					return false;
				}
		} else {
			return false;
		}
	}
	
	private function BuildUserObject() {
		if($this->auth) {
			$user = DB::queryFirstRow( "SELECT * FROM %b WHERE user_id=%s" , 'user', $this->id );
			$this->name = $user['user_name'];
			$this->email = $user['user_email'];
			$this->created = $user['user_created'];
			$this->login = $user['user_login'];
			$this->status = $user['user_status'];
			$this->lang = $user['lang_id'];
		}
		return false;
	}
	

	public function LogIn() {
		global $hook;
		if (isset($_POST['username'], $_POST['password'])) {			
			$user['username'] = $_POST['username'];
			$password = $_POST['password']; 	//	our DB object does all the security needed to protect against injections
		} else {
			return false;
		}
		
		if ($this->CheckBrute($user['username']) == true) {
			return false;
		}
		
		// Using prepared statements means that SQL injection is not possible.
		$logged_user = DB::queryFirstRow( "SELECT * FROM %b WHERE user_name=%s AND user_password=%s" , 'user', $user['username'], md5($password) );
		
		if ( $logged_user ) {
			if ( $logged_user['user_status'] == 1 ) {
			// Password is correct!
			// Get the user-agent string of the user.
			$user_browser = $_SERVER['HTTP_USER_AGENT'];
			// XSS protection as we might print this value
			$this->id = preg_replace("/[^0-9]+/", "", $logged_user['user_id']);
			$_SESSION['user_id'] = $this->id;
			// XSS protection as we might print this value
			$user['username'] = preg_replace("/[^a-zA-Z0-9_\-]+/", 
														"", 
														$user['username']);
			$_SESSION['username'] = $user['username'];
			$_SESSION['login_string'] = md5($logged_user['user_password'] . $user_browser);
			debug( "LOGIN: SUCCESS", null,true );
			return true;
			} else {
				debug( "LOGIN: BANNED USER", null,true );
			}
		} else {
			// Password is not correct
			// We record this attempt in the database
			$now = time();
			DB::insert('login_attempts', array(
					  'user_name' => $user['username'],
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
			$user['username'] = $_POST['username'];
			$user['email'] = $_POST['email'];
			$user['password'] = $_POST['password'];
			
			//	logged in users cannot create new accounts
			if ($this->name) {
				$validation_passed = false;
				$validation_errors[] = "LOG OUT BEFORE CREATING NEW ACCOUNT";
			}
			
			// Sanitize and validate the data passed in
			if (!ValidateString::AlphaNumericUnderscore($user['username'])) {
				$validation_passed = false;
				$validation_errors[] = "INVALID USERNAME";
			}
			if (!ValidateString::Email($user['email'])) {
				$validation_passed = false;
				$validation_errors[] = "INVALID EMAIL";
			}
			//	we don't need duplicates
			if( DB::queryFirstField( "SELECT user_id FROM %b WHERE user_name=%s", 'user', $user['username'] )) {
				$validation_passed = false;
				$validation_errors[] = "USER EXISTS";
			}
			if( DB::queryFirstField( "SELECT user_id FROM %b WHERE user_email=%s", 'user', $user['email'] )) {
				$validation_passed = false;
				$validation_errors[] = "EMAIL EXISTS";
			}
			if(!$validation_passed) {
				$validation_errors[] = "REGISTER FAILED";
				debug( $validation_errors, null,true );
				return false;
			}
			
			// Insert the new user into the database
			$user['encryptedpassword'] = md5($user['password']);
			DB::insert('user', array(
					  'user_name' => $user['username'],
					  'user_password' => $user['encryptedpassword'],
					  'user_email' => $user['email'],
					  'user_created' => time(),
					  'user_status' => 1,
					  'lang_id' => 1,
					));
			$user['user_id'] = DB::insertId();

			debug( "REGISTER: USER CREATED. You can now login.", null,true );
			return true;
		}
	}
	

	public function CheckBrute($user_name) {
		$now = time();
		$valid_attempts_timeframe = $now - (2 * 60 * 60);	// All login attempts are counted from the past 2 hours.
		DB::query( "SELECT user_name FROM %b WHERE user_name = %s AND invalid_time > %i", 'login_attempts', $user_name, $valid_attempts_timeframe );
		$num_invalid_attempts = DB::count();
		if ($num_invalid_attempts > 5) {
			return true;
		} else {
			return false;
		}
	}
}