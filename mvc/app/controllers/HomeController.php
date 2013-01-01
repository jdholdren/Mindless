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
		$this->load->render('Demo', array('test' => 'value'), true);
	}
}