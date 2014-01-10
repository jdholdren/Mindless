<?php

class Controller
{
	protected $load;

	public function __construct() {
		$this->load = new Load();
	}

	/*
	Calls an action
	@param the action name (Will already be formatted and appened 'Action')
	@param the array of parameters from the get array
	*/
	public function invoke($action, $params) {
		call_user_func_array(array($this, $action), $params);
	}

	/*
	Throws a status code and then exits
	@param the status code
	@param optional, the message
	@return it exits
	*/
	public function throwStatus($code, $message = " ") {
		header($message, true, $code);
		exit();
	}

	/*
	All controllers must have an Index Action, this is the defaul
	It throws a 400 status
	*/
	public function IndexAction() {
		$this->throwStatus(404);
	}


	/**
	* Logs out the current user
	* @return void
	**/
	protected function logOut()
	{
		$_SESSION = array();

		if (ini_get("session.use_cookies")) {
    		$params = session_get_cookie_params();
    		setcookie(session_name(), '', time() - 42000,
        	$params["path"], $params["domain"],
        	$params["secure"], $params["httponly"]
    		);
		}
		
		session_destroy();
	}

}
