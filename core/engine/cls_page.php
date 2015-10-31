<?php

class Page {
	public $title;
	public $content = array();
	
	public function process( $Xeno ) {
		array_shift($Xeno->Router->params);	//	we delete the first element, because it is obsolete
		switch ( $Xeno->Router->callback['path_type'] ) {
			case 'function':
				return call_user_func( $Xeno->Router->callback['path_callback'], $Xeno->Router->params, $Xeno );
				break;
			case 'pod':
				include_once ( XENO_CORE . '/engine/lib_pod.php' );
				$this->content = pod_load( $Xeno->Router->params[0] );
				break;
			case 'error':
				debug( 'THIS PAGE DOES NOT EXIST: ' . $Xeno->Router->callback['path_callback'], null,true );
				break;
			default:
				debug( $Xeno->Router->callback['path_type'] . ' IS NOT A VALID CALLBACK TYPE', null,true );
		}
	}
}