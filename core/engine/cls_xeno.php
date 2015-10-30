<?php

/**
* Initialize main page object
*
* Create instances of vital classes
* Create the user object
* Create the page query arguments from URL
* Create the list of CSS and JS to be loaded in the template
*/

class Xeno {

	public $Router;
	public $User;
	public $Assets;
	public $Page;

	public function __construct()
	{
		$this->Router = new Router( $this );
		$this->User = new User( $this );
		$this->Assets = new Assets( $this );
		$this->Page = new Page( $this );
	}
}