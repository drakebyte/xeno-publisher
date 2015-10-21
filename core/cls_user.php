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
		
		if (isset($_POST['username'], $_POST['password'])) {			
			$username = $_POST['username'];
			$password = $_POST['password']; 	//	our DB object does all the security needed to protect against injections
		} else {
			return false;
		}
		
		if (checkbrute($username) == true) {
			return false;
		}
		
		// Using prepared statements means that SQL injection is not possible.
		$logged_user = DB::queryFirstRow( query_prefix_table( "SELECT * FROM {users} WHERE user_name=%s AND user_password=%s" ), $username, md5($password) );
		
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
			debug( "LOGIN: SUCCESS", null,true );
			return true;
		} else {
			// Password is not correct
			// We record this attempt in the database
			$now = time();
			DB::insert(query_prefix_table('{login_attempts}'), array(
					  'user_name' => $username,
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
		header('Location: ../index.php');
	}
	
	public function RegisterUser() {
		if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {

			$validation_passed = true;
			$validation_errors = array();
			// Sanitize and validate the data passed in
			if (!ValidateString::AlphaNumericUnderscore($_POST['username'])) {
				$validation_passed = false;
				$validation_errors[] = "INVALID USERNAME";
			}
			if (!ValidateString::Email($_POST['email'])) {
				$validation_passed = false;
				$validation_errors[] = "INVALID EMAIL";
			}
			if(!$validation_passed) {
				$validation_errors[] = "REGISTER FAILED";
				debug( $validation_errors, null,true );
				return false;
			}
			
			
			
			
			
			
			
			
				debug( "REGISTER: REACHED END", null,true );
			return false;	//	do nothing more
			
			$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				// Not a valid email
				$error_msg .= '<p class="error">The email address you entered is not valid</p>';
			}

			$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
			if (strlen($password) != 128) {
				// The hashed pwd should be 128 characters long.
				// If it's not, something really odd has happened
				$error_msg .= '<p class="error">Invalid password configuration.</p>';
			}

			// Username validity and password validity have been checked client side.
			// This should should be adequate as nobody gains any advantage from
			// breaking these rules.
			//

			$prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";
			$stmt = $mysqli->prepare($prep_stmt);

			// check existing email  
			if ($stmt) {
			$stmt->bind_param('s', $email);
			$stmt->execute();
			$stmt->store_result();

			if ($stmt->num_rows == 1) {
				// A user with this email address already exists
				$error_msg .= '<p class="error">A user with this email address already exists.</p>';
				$stmt->close();
			}
				$stmt->close();
			} else {
				$error_msg .= '<p class="error">Database error Line 39</p>';
				$stmt->close();
			}

			// check existing username
			$prep_stmt = "SELECT id FROM members WHERE username = ? LIMIT 1";
			$stmt = $mysqli->prepare($prep_stmt);

			if ($stmt) {
				$stmt->bind_param('s', $username);
				$stmt->execute();
				$stmt->store_result();

				if ($stmt->num_rows == 1) {
					// A user with this username already exists
					$error_msg .= '<p class="error">A user with this username already exists</p>';
					$stmt->close();
				}
				$stmt->close();
			} else {
				$error_msg .= '<p class="error">Database error line 55</p>';
				$stmt->close();
			}

			// TODO: 
			// We'll also have to account for the situation where the user doesn't have
			// rights to do registration, by checking what type of user is attempting to
			// perform the operation.

			if (empty($error_msg)) {
				// Create a random salt
				//$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE)); // Did not work
				$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

				// Create salted password 
				$password = hash('sha512', $password . $random_salt);

				// Insert the new user into the database 
				if ($insert_stmt = $mysqli->prepare("INSERT INTO members (username, email, password, salt) VALUES (?, ?, ?, ?)")) {
					$insert_stmt->bind_param('ssss', $username, $email, $password, $random_salt);
					// Execute the prepared query.
					if (! $insert_stmt->execute()) {
						header('Location: ../error.php?err=Registration failure: INSERT');
					}
				}
				header('Location: ./register_success.php');
			}
		}
	}
}

//	http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL

function checkbrute($user_name) {
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