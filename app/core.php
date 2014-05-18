<?php

// Core settings
require('./app/config.php');

require('load.php');

// Generic Classes
require('./app/controllers/ControllerBase.php');

// Data retrieval
require('./app/database/Database.php');
require('./app/database/Query.php');

// Routing
require('./app/http/Router.php');
require('./app/http/Route.php');

// Construct Router
$router = new Router($_SERVER['SCRIPT_FILENAME'], Config::$strict_routing);

// Define Routes
require('./app/http/routing.php');

/**
* Define autoload class for modles
* Searches the models directory for model classes and requires the needed file
* @param string class name
* @return void
*/
function __autoload($className) {
	$url = './app/models/' . $className . '.php';

	if (file_exists($url)) {
		require($url);
	}
	else {
		throw new Exception("Could not load class: " . $className);
	}
}