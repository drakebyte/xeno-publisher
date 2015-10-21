<!-- TEMPLATE ENDED. REDUNDANT PLACEHOLDER BELOW -->
<link rel="stylesheet" type="text/css" href="ui/css/reset.css" media="screen" />
<link rel="stylesheet" type="text/css" href="ui/css/fonts.css" media="screen" />
<link rel="stylesheet" type="text/css" href="ui/css/style.css" media="screen" />
<body>
<hr />
<center>PAGE END</center>
<hr />
<?php
//*************************************
//***********	HOOKS	***************

//	define a hook and use it
$hook->add_action( 'header_action','the_custom_hook_function' );
function the_custom_hook_function( $somedata ){
	debug( 'HOOK TRIGGERED with ' . $somedata, null,true );
	return true;
}
//	define a filter hook and use it
$hook->add_filter( 'header_filter','the_custom_filter_hook_function' );
function the_custom_filter_hook_function( $somedata ){
	debug( 'FILTER HOOK TRIGGERED', null,true );
	return 'filtered ' . $somedata;
}

//	the function using the hook
function testing_hooks( $somedata ) {
	global $hook;
	$return = do_shortcode( $somedata );
	$hook->do_action( 'header_action',$somedata ); 	//	trigger header_action hooks
	$return = $hook->apply_filters( 'header_filter',$somedata ); 	//	trigger header_filter filter hooks
	return $return;
}

//	executing the function with a hook implemented
echo testing_hooks('LOCSICS');

//***********	HOOKS	***************
//*************************************

//*************************************
//***********	SHORTCODES	***********
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

//***********	SHORTCODES	***********
//*************************************



debug( query_get_setting( 'current_theme' ), null,true );
debug( $_SESSION, null,true );
// login_check();
if ( isset( $_POST['action'] ) ) {
	if ($_POST['action'] == 'login') {
		$user->LogIn();
	}
	if ($_POST['action'] == 'register') {
		$user->RegisterUser();
	}
	if ($_POST['action'] == 'logout') {
		$user->LogOut();
	}
}
?>
HELP:
<li><a href="documentation/database.html" target="_blank">DATABASE</a></li>

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