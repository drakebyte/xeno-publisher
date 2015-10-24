<?php
/*
	the main engine class. here we declare things needed for everything else. we do not like global prefix.
	logic:
		implement the hook system
		validate the url
		construct the user object
*/
class Xeno {
	
	public $DB;
	public $Hook;
	public $SampleHookData;

    public function __construct()
    {
		$this->Hook = new Hook;
		
		
		
		//	
		
		
		
		
		
		
		debug( 'cls Xeno initiated successfully', null,true );
    }
	
	public function FnWithHook() {
		$this->SampleHookData = 'MAGIC: hook worked';
		$this->Hook->do_action( 'xeno_init', $this->SampleHookData );
	}
}