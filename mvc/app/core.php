<?php

require('load.php');

// Generic Classes
require('./app/controllers/basecontroller.php');
require('./app/models/basemodel.php');

function __autoload($className)
{
	$url = './app/models/' . $className . '.php';
	if (file_exists($url))
	{
		require($url);
	}
	else
	{
		throw new Exception("Could load class: " . $className);
	}
}