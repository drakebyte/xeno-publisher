<?php
/**
 * Root directory of XenoPublisher installation.
 */
define( 'XENO_ROOT', __DIR__ );
define( 'XENO_URL', ( empty( $_SERVER['HTTPS'] ) ? 'http' : 'https' ) 
						. '://' 
						. $_SERVER['SERVER_NAME'] 
						. ( ( empty($_SERVER['HTTPS']) == 'http' && $_SERVER['SERVER_PORT'] == 80 || !empty($_SERVER['HTTPS'] ) == 'https' && $_SERVER['SERVER_PORT'] == 443 ) ? '' : ':' . $_SERVER['SERVER_PORT'] ) 
						. ( empty( substr(__DIR__, strlen( $_SERVER[ 'DOCUMENT_ROOT' ] ) ) ) ? '' : '/' . substr( __DIR__, strlen( $_SERVER[ 'DOCUMENT_ROOT' ] ) ) ) 
						. '/');
//	
include_once ( XENO_ROOT . '/core/xeno-load.php' );

// xeno_prepare_content();
// xeno_apply_hooks();
tpl_render_page();
?>

<!-- TEMPLATE ENDED. REDUNDANT PLACEHOLDER BELOW -->
<hr />
<center>PAGE END</center>
<hr />
<?php
debug( query_get_setting( 'current_theme' ), null,true );

//	login
login();
debug( $_SESSION, null,true );
// login_check();
?>
HELP:
<li><a href="documentation/database.html" target="_blank">DATABASE</a></li>

<form action="index.php" method="post">
	<label for="username">Username</label><input id="username" type="text" value="admin" name="username" />
	<label for="password">password</label><input id="password" type="text" value="admin" name="password" />
	<input type="submit" value="submit" name="Log In" />
</form>