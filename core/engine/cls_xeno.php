<?php
/*
	the main engine class. here we declare things needed for everything else. we do not like global prefix.
	logic:
		implement the hook system
		validate the url
		construct the user object
*/
class Xeno {

	public $Router;
	public $User;
	public $Hook;
	public $Page;

    public function __construct()
    {
		debug( 'XENO: initialized', null,true );
		
		
		$this->Hook = new Hook;
		$this->User = new User;
		$this->Router = new Router;
    }
}