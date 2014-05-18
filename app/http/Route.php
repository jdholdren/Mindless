<?php

/**
* Represents a defined route
**/

class Route {
	
	// Controller name
	private $controller, $action, $params;

	/**
	* @param string controller name
	* @param string action name
	*/
	public function __construct($controller, $action) {
		$this->controller = $controller;
		$this->action = $action;
	}

	/**
	* @return Controller name
	*/
	public function getController() {
		return $this->controller;
	}

	/**
	* @return Action name
	**/
	public function getAction() {
		return $this->action;
	}

	/**
	* Sets parameters
	* @param array of parameters
	* @return void
	*/
	public function setParams($params) {
		$this->params = $params;
	}

	/**
	* @return params
	*/
	public function getParams() {
		return $this->params;
	}
}