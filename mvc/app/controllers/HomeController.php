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
		// This is a sample rendering of a template using some dummy data
		$this->load->render('Demo', array('message' => 'Looks like your Mindless MVC installation is working!'), true);
	}
}