<?php

abstract class Controller
{
	protected $load;
	// The token where we store login info in a session
	protected $sessionToken = 'mindless-mvc';

	public function __construct()
	{
		$this->load = new Load();
	}

	/*
	Checks that the current object holds the vars asked for
	@param the names of the vars
	@param search either post ('post') or get ('get')
	@return true if all are provided
	@return false if any are missing
	*/
	protected function checkVars($vars, $search)
	{
		if (is_array($vars))
		{
			foreach($vars as $key)
			{
				if(!isset($this->$search[$key]))
				{
					return false;
				}
			}
		}
		else
		{
			if (!isset($this->$search[$key]))
			{
				return false;
			}
		}
		return true;
	}

	/*
	Redirects the action
	@param the controller name OR the controller object
	@param the action name
	@return void
	// TODO make a function to accept params
	*/
	protected function redirectToAction($action, $controller = false, $params = null)
	{
		$action = strtolower($action);

		if (strtolower($action) == 'index')
		{
			$action = '';
		}

		if ($controller && $controller != 'Home')
		{
			$controller = strtolower($controller);
			$url = INSTALL_ROOT . $controller . '/' . $action;
		}
		else
		{
			$url = INSTALL_ROOT . $action;
		}

		header('Location: ' .$url);
		exit();
	}

	/*
	Calls an action
	@param the action name (Will already be formatted and appened 'Action')
	@param the array of parameters from the get array
	*/
	public function invoke($action, $params)
	{
		$decoded = array();

		foreach($params as $param)
		{
			$decoded[] = urldecode($param);
		}

		call_user_func_array(array($this, $action), $decoded);
	}

	/*
	Throws a status code and then exits
	@param the status code
	@param optional, the message
	@return it exits
	*/
	public function throwStatus($code, $message = " ")
	{
		header($message, true, $code);
		exit();
	}

	/*
	All controllers must have an Index Action, this is the defaul
	It throws a 400 status
	*/
	public function IndexAction()
	{
		$this->throwStatus(404);
	}

	/**
	* Checks to see if a user is logged in
	* @return boolean
	**/
	public function isLoggedIn()
	{
		if (isset($_SESSION[$this->sessionToken]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* Logs in the user by storing his email in the session
	* @param the email
	* @return void
	**/
	protected function logIn($email)
	{
		$_SESSION[$this->sessionToken] = $email;
	}

	/**
	* Logs out the current user
	* @return void
	**/
	protected function logOut()
	{
		$_SESSION = array();

		if (ini_get("session.use_cookies"))
		{
    		$params = session_get_cookie_params();
    		setcookie(session_name(), '', time() - 42000,
        	$params["path"], $params["domain"],
        	$params["secure"], $params["httponly"]
    		);
		}
		
		session_destroy();
	}

}