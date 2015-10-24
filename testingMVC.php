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
$password = DB::queryFirstField( query_prefix_table( "SELECT user_password FROM %b WHERE user_id=%i" ), 'user', 1 );
debug( 'DB::example ' . $password, null,true );

debug( DB::columnList( 'user' ), null,true );


//	****************************\*/****************************





//	****************************/*\****************************
//	************************* session *************************
debug( $_SESSION, null,true );
//	****************************\*/****************************





//	****************************/*\****************************
//	************************** HOOKS **************************
$Xeno->Hook->add_action( 'xeno_init','the_custom_hook_function' );
function the_custom_hook_function( $somedata ){
	debug( 'HOOK TRIGGERED with ' . $somedata, null,true );
	return true;
}
$Xeno->FnWithHook();
//	****************************\*/****************************