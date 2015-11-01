<?php

/**
* Load the correct theme files
*
* Decide which component gets loaded.
* Build the head with the styles, scripts and meta
* Render the correct site header
* Render the correct site content
* Render the correct site sidebars and widgets
* Build the footer with the scripts
*/

//	if ajax		then content/loop/partial
//	if json		then json formatted data
//	print		then content only without widgets and sidebars, load print css
//	desktop		then everything

//	execute the hook before the render starts
HOOK::do_action( 'xeno_prerender', $Xeno );
//	start rendering
$Xeno->Theme->setTheme($Xeno->Router->theme);



//	finish rendering, safe to delete everything after this line.

// $Xeno->Assets->Rebuild();	//	force rebuild minified assets
$Xeno->Assets->PrintCSS();
$Xeno->Assets->PrintJS( 'footer' );


debug( $Xeno, null,true );