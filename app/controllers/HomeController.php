<?php

/**
* Default Controller
*/

class HomeController extends Controller {
	public function IndexAction() {
		$this->load->render('Default', false, true);
	}
}