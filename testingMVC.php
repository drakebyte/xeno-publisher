<!-- TEMPLATE ENDED. REDUNDANT PLACEHOLDER BELOW -->
<hr />
<center>TESTING DATA</center>
<hr />
<?php
$timing->printElapsedTime();

//	****************************/*\****************************
//	************************* ASSETS **************************
$Xeno->Assets->AddJS( 'jquery', XENO_URL . 'core/assets/js/jquery.js', 'footer' );
$Xeno->Assets->AddJS( 'jquery.minicolors', XENO_URL . 'core/assets/js/jquery.minicolors.js', 'footer' );
$Xeno->Assets->AddCSS( 'reset', XENO_URL . 'core/assets/css/reset.css', 'footer' );
$Xeno->Assets->AddCSS( 'fonts', XENO_URL . 'core/assets/css/fonts.css', 'footer' );
$Xeno->Assets->AddCSS( 'style', XENO_URL . 'core/assets/css/style.css', 'footer' );
$Xeno->Assets->AddCSS( 'jquery.minicolors', XENO_URL . 'core/assets/css/jquery.minicolors.css', 'footer' );


// $Xeno->Assets->Rebuild();	//	force rebuild minified assets
$Xeno->Assets->PrintCSS();
$Xeno->Assets->PrintJS( 'footer' );
?>
	<script>
		$(document).ready( function() {
			$('.colorfield').each( function() {
				$(this).minicolors({
					control: $(this).attr('data-control') || 'hue',
					defaultValue: $(this).attr('data-defaultValue') || '',
					format: $(this).attr('data-format') || 'hex',
					keywords: $(this).attr('data-keywords') || '',
					inline: $(this).attr('data-inline') === 'true',
					letterCase: $(this).attr('data-letterCase') || 'lowercase',
					opacity: $(this).attr('data-opacity'),
					position: $(this).attr('data-position') || 'bottom left',
					change: function(hex, opacity) {
						var log;
						try {
							log = hex ? hex : 'transparent';
							if( opacity ) log += ', ' + opacity;
							console.log(log);
						} catch(e) {}
					},
					theme: 'default'
				});
			});
		});
	</script>
	<div class="form-group">
		<label for="rgba">rgb(a)</label>
		<br>
		<input type="text" id="rgba" class="colorfield" data-format="rgb" data-opacity=".5" value="rgba(52, 64, 158, 0.5)">
	</div>
<?php
$timing->printElapsedTime();
//	****************************\*/****************************




//	****************************/*\****************************
//	************************* DB class ************************
$password = DB::queryFirstField( "SELECT user_password FROM %b WHERE user_id=%i" , 'user', 1 );
debug( 'DB::example ' . $password, null,true );

debug( $Xeno->User->perm, null,true );

$timing->printElapsedTime();
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

$timing->printElapsedTime();
//	****************************\*/****************************




//	****************************/*\****************************
//	************************* XQuery **************************
$theme = XQuery::get_setting('current_theme');
debug( $theme, null,true );
$timing->printElapsedTime();
//	****************************\*/****************************





//	****************************/*\****************************
//	************************* SESSION *************************
debug( $_SESSION, null,true );
$timing->printElapsedTime();
//	****************************\*/****************************





//	****************************/*\****************************
//	************************** HOOKS **************************
HOOK::add_action( 'xeno_init','the_custom_hook_function' );
function the_custom_hook_function( $somedata ){
	debug( $somedata, null,true );
	return true;
}

function FnWithHook() {
	global $Xeno;
	$SampleHookData = 'MAGIC: hook worked';
	HOOK::do_action( 'xeno_init', $SampleHookData );
}

FnWithHook();
$timing->printElapsedTime();
//	****************************\*/****************************





//	****************************/*\****************************
//	************************ MESSAGES *************************
Message::set('ichtyios message for a warning', 'warning');
Message::set('ichtyios message for a error', 'error');
Message::set('ichtyios message for a status', 'status');
Message::set('ichtyios message for another warning', 'warning');

echo Message::render();
$timing->printElapsedTime();
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
$timing->printElapsedTime();
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
<?php
$timing->printElapsedTime();
//	****************************\*/****************************

// debug( $Xeno, null,true );

	
	// Stop/end timing
	$timing->stop();

	// Print only total execution time
	$timing->printTotalExecutionTime();

	// Print full stats
	$timing->printFullStats();