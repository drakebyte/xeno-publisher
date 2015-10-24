<?php
/*
	the main engine class. here we declare things needed for everything else. we do not like global prefix.
	logic:
		implement the hook system
		validate the url
		construct the user object
*/
class Xeno {

	public $page;
	public $Hook;
	public $User;
	public $SampleHookData;

    public function __construct()
    {
		$this->Hook = new Hook;
		$this->User = new User;
		
		
		debug( 'cls Xeno initiated successfully', null,true );
    }
}