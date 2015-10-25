<!-- TEMPLATE ENDED. REDUNDANT PLACEHOLDER BELOW -->
<link rel="stylesheet" type="text/css" href="/core/assets/css/reset.css" media="screen" />
<link rel="stylesheet" type="text/css" href="/core/assets/css/fonts.css" media="screen" />
<link rel="stylesheet" type="text/css" href="/core/assets/css/style.css" media="screen" />
<body>
<hr />
<center>TESTING DATA</center>
<hr />
<?php
//	****************************/*\****************************
//	************************* DB class ************************
$password = DB::queryFirstField( "SELECT user_password FROM %b WHERE user_id=%i" , 'user', 1 );
debug( 'DB::example ' . $password, null,true );

debug( DB::columnList( 'user' ), null,true );


//	****************************\*/****************************




//	****************************/*\****************************
//	************************** ROUTER *************************
//	every regex url part is a parameter for the function
$Xeno->Router->Route( 'news/(\w+)/(\d+)',
				array(
					'access_level' => 'view_pods',
					'path_callback' => 'rozsaszin',
					'path_type' => 'function',
				)
	);
function rozsaszin($params) {
	debug( 'CUSTOM PAGE CALLBACK SUCCEEDED', null,true );
	debug( $params, null,true );
}
function display_admin_menus($params) {
	debug( 'ADMIN MENU SUCCEEDED', null,true );
	debug( $params, null,true );
}

$Xeno->Router->execute();


//	****************************\*/****************************




//	****************************/*\****************************
//	************************* DB query ************************
$theme = XQuery::query_get_setting('current_theme');
debug( $theme, null,true );

//	****************************\*/****************************





//	****************************/*\****************************
//	************************* SESSION *************************
debug( $_SESSION, null,true );
//	****************************\*/****************************





//	****************************/*\****************************
//	************************** HOOKS **************************
$Xeno->Hook->add_action( 'xeno_init','the_custom_hook_function' );
function the_custom_hook_function( $somedata ){
	debug( $somedata, null,true );
	return true;
}

function FnWithHook() {
	global $Xeno;
	$SampleHookData = 'MAGIC: hook worked';
	$Xeno->Hook->do_action( 'xeno_init', $SampleHookData );
}

FnWithHook();
//	****************************\*/****************************





//	****************************/*\****************************
//	************************ MESSAGES *************************
Message::set('ichtyios message for a warning', 'warning');
Message::set('ichtyios message for a error', 'error');
Message::set('ichtyios message for a status', 'status');
Message::set('ichtyios message for another warning', 'warning');

echo Message::render();
//	****************************\*/****************************





//	****************************/*\****************************
//	*********************** SHORTCODES ************************

add_shortcode("quote", "quote");
function quote( $atts, $content = null ) {
		return '<div class="right text">"'.$content.'"</div>';
}

add_shortcode("alink", "alink");
function alink($atts, $content = null) {
		extract(shortcode_atts(array(
				"to" => 'http://net.default.com',
				"color" => 'green',
		), $atts));
		return '<a href="'.$to.'" style="color: ' . $color . '">'.$content.'</a>';
}

function testing_shortcodes( $content ) {
	return do_shortcode( $content );
}

echo testing_shortcodes( '<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. [quote]Nulla arcu quam, aliquet vitae ultrices nec, auctor ut dolor.[/quote] Etiam sagittis ante felis, a gravida nisl consectetur non. Quisque venenatis condimentum sem nec aliquet. Sed at dui eget urna bibendum rhoncus vel mollis sem. [alink to="http://www.net.tutsplus.com" color="red"]NetTuts+[/alink]' );
//	****************************\*/****************************





//	****************************/*\****************************
//	************************** USER **************************

if ($Xeno->User->auth) {
	$loggedstatus = 'Logged In';
} else {
	$loggedstatus = 'Logged Out';
}
debug( $loggedstatus, null,true );
if ( isset( $_POST['action'] ) ) {
	if ($_POST['action'] == 'login') {
		$Xeno->User->LogIn();
	}
	if ($_POST['action'] == 'register') {
		$Xeno->User->RegisterUser();
	}
	if ($_POST['action'] == 'logout') {
		$Xeno->User->LogOut();
	}
}

?>
<form action="index.php" method="post">
	<input type="hidden" name="action" value="login" />
	<label for="username">Username</label><input id="username" type="text" value="admin" name="username" />
	<label for="password">Password</label><input id="password" type="text" value="admin" name="password" />
	<input type="submit" value="Log In" name="submit" />
</form>
<form action="index.php" method="post">
	<input type="hidden" name="action" value="register" />
	<label for="username">Username</label><input id="username" type="text" value="admin" name="username" />
	<label for="password">Password</label><input id="password" type="text" value="admin" name="password" />
	<label for="email">Email</label><input id="email" type="text" value="admin@admin.com" name="email" />
	<input type="submit" value="Register" name="submit" />
</form>
<form action="index.php" method="post">
	<input type="hidden" name="action" value="logout" />
	<input type="submit" value="Log Out" name="submit" />
</form>
</body>
<?php
//	****************************\*/****************************

debug( $Xeno, null,true );