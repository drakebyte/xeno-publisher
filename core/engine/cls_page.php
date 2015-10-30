<?php

class Page {
	public $title;
	public $type;
	public $status;
	public $content = array();
	
	// public $author;
	// public $created;
	// public $modified;
	// public $field = array();
	
	public function process( $Xeno ) {
		// array_shift($params);
		switch ($Xeno->Router->callback['path_type']) {
			case 'function':
				return call_user_func($Xeno->Router->callback['path_callback'], $Xeno->Router->params, $Xeno);
				break;
			case 'error':
				debug( 'THIS PAGE DOES NOT EXIST: ' . $Xeno->Router->callback['path_callback'], null,true );
				break;
			default:
				debug( $Xeno->Router->callback['path_type'] . ' IS NOT A VALID CALLBACK TYPE', null,true );
		}
	}
}