<?php

/**
* Prepare the page data for the view
*
* Run the init hook before everything
* Parse url request and decide what to do
* Continue in xeno-render.php
*/

HOOK::do_action( 'xeno_init', $Xeno );	//	do something before the page starts rendering, for example add extra scripts or css etc

$Xeno->Router->execute($Xeno);

$Xeno->Page->process($Xeno);

// $Xeno->