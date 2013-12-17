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
		$this->routes[$method][$regex] = new Route($controller, $action);
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
	* @param string method
	* @param string uri
	* @return array(
	*		'controller' => string controller name
	*		'action' => string action name
	*		'params' => array of params to be passed
	*)
	**/
	private function searchForVanillaRoute($method, $uri) {
		// @TODO: Determine route using vanilla rules
	}

	/**
	* Determines controller and action
	* @param string url
	* @param string request method
	* @return array(
	*		'controller' => string controller name
	*		'action' => string action name
	*		'params' => array of params to be passed
	*)
	**/
	public function determineRoute($uri, $method) {
		// Check that the method is allowed
		if (!$this->validMethod($method)) {
			// Switch the method to get
			$method = 'get';
		}

		// The route to be determined
		$route;

		// Check 'any' set first, then the method, then vanilla if strict is off
		if (($route = $this->searchForRoute('any', $uri) || ($route = $this->searchForRoute($method, $uri)) || ((!$this->strictRouting) && $router = $this->searchForVanillaRoute($method, $uri)))) {
			return array(
				'controller' => $route->getController(),
				'action' => $route->getAction(),
				'params' => $route->getParams()
			);
		}
		else {
			// No routes we found, throw a 404
			$controller = new Controller();
			$controller->throwStatus(404);
		}
	}
}