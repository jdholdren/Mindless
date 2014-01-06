<?php

/**
* Mindless MVC Framework
* Created by James Holdren
* Version 1.0.1
**/

/**
* Entry point for setting global values and url mapping
**/

session_start();

// Requie the core
require('./app/core.php');

if (!$config['dev_mode']) {
	error_reporting(0);
	@ini_set('display_errors', 0);
}

$determinedRoute = $router->determineRoute($_SERVER['REQUEST_URI'], strtolower($_SERVER['REQUEST_METHOD']));

// Check if a controller has been created
if (!empty($determinedRoute['controller'])) {
	// A controller exists
	$controller = $determinedRoute['controller'];
}
else {
	// No controller exists, create one from the name
	// First require the file
	require('./app/controllers/' . $determinedRoute['controllerName'] . '.php');
	$controller = new $determinedRoute['controllerName'];
}

// Call the controller and action
$controller->invoke($determinedRoute['actionName'], $determinedRoute['params']);