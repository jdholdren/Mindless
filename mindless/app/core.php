<?php

// require core settings
require('./app/config.php');

require('load.php');

// Generic Classes
require('./app/controllers/basecontroller.php');
require('./app/models/basemodel.php');

// data retrieval
require('./app/activeRecord.php');

function __autoload($className)
{
	$url = './app/models/' . $className . '.php';
	$formsUrl = './app/models/formModels/' . $className . '.php';

	if (file_exists($url))
	{
		require($url);
	}
	elseif (file_exists($formsUrl))
	{
		require($formsUrl);
	}
	else
	{
		throw new Exception("Could not load class: " . $className);
	}
}

// Make an instance of data retrieval object
global $ar;
$ar = new activeRecord($config['db']);