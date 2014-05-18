<?php

/**
* Mindless MVC Framework
* Created by James Holdren
* Version 2.0.0
**/

/**
* Entry point for setting global values and url mapping
**/

session_start();

// Requie the core
require('./app/core.php');

if (!Config::$dev_mode) {
	error_reporting(0);
	@ini_set('display_errors', 0);
}

$uri = preg_replace('/' . preg_replace('/\//', '\/', preg_replace('/\/index.php/', '', $_SERVER['SCRIPT_NAME'])) . '/', '', $_SERVER['REQUEST_URI']);

$determinedRoute = $router->determineRoute($uri, strtolower($_SERVER['REQUEST_METHOD']));

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