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
	public $Theme;
	public $User;
	public $Assets;
	public $Page;

	public function __construct()
	{
		$this->Router = new Router;
		$this->Theme = new Theme;
		$this->User = new User;
		$this->Assets = new Assets;
		$this->Page = new Page;
	}
}