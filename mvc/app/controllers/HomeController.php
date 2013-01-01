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

	public function IndexAction($variable)
	{
		echo $variable;
		//$this->load->render('Demo', array('message' => 'Looks like your Mindless MVC installation is working!'), true);
	}

	public function HelloAction($something)
	{
		echo $something;
	}
}