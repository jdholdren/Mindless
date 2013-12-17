<?php

// require core settings
require('./app/config.php');

require('load.php');

// Generic Classes
require('./app/controllers/basecontroller.php');
require('./app/models/basemodel.php');

// data retrieval
require('./app/activeRecord.php');

// Routing
require('./app/http/Router.php');
require('./app/http/Route.php');

// Construct Router
$router = new Router($_SERVER['SCRIPT_FILENAME'], false);

// Define Routes
require('./app/http/routing.php');

/**
* Define autoload class for modles
* Searches the models directory for model classes and requires the needed file
* @param string class name
* @return void
**/
function __autoload($className) {
	$url = './app/models/' . $className . '.php';
	$formsUrl = './app/models/formModels/' . $className . '.php';

	if (file_exists($url)) {
		require($url);
	}
	elseif (file_exists($formsUrl)) {
		require($formsUrl);
	}
	else {
		throw new Exception("Could not load class: " . $className);
	}
}