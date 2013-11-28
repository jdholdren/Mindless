<?php

/*
Default Controller
*/

class HomeController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function IndexAction()
	{
		// Throw a 404 if we we're passed something unexpected
		if (func_num_args() > 0)
		{
			$this->throwStatus(404);
		}
	}
}