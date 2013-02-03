<?php

abstract class Controller
{
	protected $load;

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
	private function checkVars($vars, $search)
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
	*/
	protected function redirectToAction($controller, $action, $params = null)
	{
		require_once('./app/controllers/' . ucfirst(strtolower($controller)) . '.php');
		$controller = new $controller();
		$action = ucfirst(strtolower($action)) . 'Action';
		return $controller->invoke($action, $params);
	}

	/*
	Calls an action
	@param the action name (Will already be formatted and appened 'Action')
	@param the array of parameters from the get array
	*/
	public function invoke($action, $params)
	{
		call_user_func_array(array($this, $action), $params);
	}

	/*
	Throws a status code and then exits
	@param the status code
	@param optional, the message
	@return it exits
	*/
	public function throwStatus($code, $message = "")
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
		$this->throwStatus(400);
	}
}