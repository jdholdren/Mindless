<?php

/**
* Class responsible for determining the route
**/

class Router {

	// An array for routes, sectioned by each request method
	// within each, routes stored as 'regex' => Route object
	private $routes = array(
		'any' => array(),
		'get' => array(),
		'post' => array(),
		'put' => array(),
		'delete' => array()
	);

	// Setting that says if strict routing is on or off
	private $strictRouting;

	// Allowed methods for requests
	private $allowedMethods = array('any', 'get', 'post', 'put', 'delete');

	/**
	* Constructor
	* @param Script Filename in $_SERVER
	* @param bool strict routing
	**/
	public function __construct($scriptFilename, $strict) {
		$this->strictRouting = $strict;
	}

	/**
	* Checks that a method type is allowed
	* @param string method type
	* @return boolean true if allowed, false otherwise
	**/
	private function validMethod($type) {
		return in_array($type, $this->allowedMethods);
	}

	/**
	* Adds a route
	* @param string the method. Either 'get', 'post', 'put', 'delete', or 'any'
	* @param string route string to match
	* @param string controller name
	* @param string action name
	* @return void
	**/
	public function route($method, $routeString, $controller, $action) {
		// Make sure that method is valid
		if (!$this->validMethod($method)) {
			throw new Exception('Method type is not valid.');
		}

		// Storing Routes //

		// Make regex for the routeString
		$regex = '/^';

		// Partition by /'s and anything with ':' is a variable
		$parts = explode('/', $routeString);

		// Remove the trailing empties, if there is one
		if (empty($parts[count($parts) - 1])) {
			array_splice($parts, -1, 1);
		}

		// Cycle through each part
		foreach($parts as $part) {

			// Add the beginning '/'
			$regex .= '\/';

			if (substr($part, 0, 1) == ':') {
				// It's a parameter, so add the regex rule for parameters
				$regex .= '([a-zA-Z0-9]+)';
			}
			else {
				// Just add the text
				$regex .= $part;
			}
		}

		// Complete the regex
		$regex .= '$/';

		// Store it in the appropriate array
		$this->routes[$method][$regex] = new Route($controller . 'Controller', $action);
	}

	/**
	* Checks a specified method array for a match
	* @param string method type
	* @param string uri
	* @return Request object if match. False otherwise
	**/
	private function searchForRoute($method, $uri) {
		// Matches spat out by the following regex function
		$matches;

		// Try to match the string via the regex
		foreach($this->routes[$method] as $regex => $route) {
			if (preg_match($regex, $uri, $matches)) {

				// Get the 'slash' parameters to pass on later, but the first needs to be removed
				array_splice($matches, 0, 1);

				// Set the parameters in the routes
				$route->setParams($matches);

				return $route;
			}
		}

		// No matches were found
		return false;
	}

	/**
	* Determines the controller and action not based on defined routes
	* @param string uri
	* @param string method
	* @return array(
	*		'controller' => string controller name
	*		'action' => string action name
	*		'params' => array of params to be passed
	*)
	* false otherwise
	**/
	public function searchForVanillaRoute($uri, $method) {
		$parts = explode('/', $uri);

		// Remove trailing empty
		if (empty($parts[count($parts) - 1])) {
			array_splice($parts, -1, 1);
		}

		// Remove the beginning empty
		if (empty($parts[0])) {
			array_splice($parts, 0, 1);
		}

		// Determine if the first part is a vaild controller name
		if (!empty($parts[0]) && file_exists($fileName = './controllers/' . ucfirst($parts[0]) . 'Controller.php')) {
			require($fileName);
			$controller = ucFirst($parts[0]) . 'Controller';

			// Remove the first part
			array_splice($parts, 0, 1);
		}
		// Not a valid controller name, the controller defaults to home
		else {
			require('./app/controllers/HomeController.php');
			$controller = 'HomeController';
		}

		$controller = new $controller;

		// Check that the next part is an action, first by the method specific action, i.e. NameActionMethod()
		if (!empty($parts[0]) && method_exists($controller, $parts[0] . 'Action' . $method)) {
			$action = $parts[0] . 'Action' . $method;

			// Splice off the action part
			array_splice($parts, 0 , 1);
		}
		// Now check that the catch all exists, i.e. NameAction()
		elseif (!empty($parts[0]) && method_exists($controller, $parts[0] . 'Action')) {
			$action = $parts[0] . 'Action';

			// Splice off the action part
			array_splice($parts, 0, 1);
		}
		// Check for IndexMethod()
		elseif (method_exists($controller, 'IndexAction' . $method)) {
			$action = 'IndexAction' . $method;
		}
		else {
			// The action is be default, index
			$action = 'IndexAction';
		}

		return array(
			'controller' => $controller,
			'actionName' => $action,
			'params' => $parts
		);
	}

	/**
	* Determines controller and action
	* @param string url
	* @param string request method
	* @return array(
	*		'controllerName' => string controller name 
	*		'actionName' => string action name
	*		'params' => array of params to be passed
	*		OPTIONAL 'controller' => the controller object
	*)
	**/
	public function determineRoute($uri, $method) {
		// Check that the method is allowed
		if (!$this->validMethod($method)) {
			// Switch the method to get
			$method = 'get';
		}

		// Remove the trailing slash of the uri, if there is one
		$uri = rtrim($uri, '/');

		// Defined routes take precedent over 
		// Check 'any' set first, then the method's array, then vanilla if strict is off
		if (($route = $this->searchForRoute('any', $uri)) || ($route = $this->searchForRoute($method, $uri))) {
			return array(
				'controllerName' => $route->getController(),
				'actionName' => $route->getAction(),
				'params' => $route->getParams()
			);
		}
		// Search for a vanilla route if strict routing is not enabled
		elseif ((!$this->strictRouting) && ($route = $this->searchForVanillaRoute($uri, $method))) {
			return $route;
		}
		else {
			// No routes we found, throw a 404
			$controller = new Controller();
			$controller->throwStatus(404);
			exit();
		}
	}
}