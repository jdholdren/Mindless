<?php

abstract class Controller
{
	protected $get;
	protected $post;
	protected $load;

	public function __construct()
	{
		$this->load = new Load();
	}

	/*
	Sets the parameters into either the get or set variables for use in the controller
	@param the parameter array 
	@param the group: either get or set
	@return void
	*/
	public function setParams($params, $group)
	{
		if ($group == "get")
		{
			$this->get = $params;
		}
		if ($group == "post")
		{
			$this->post = $params;
		}
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
	private function redirectToAction()
	{
		// TODO: redirect to action
	}
}