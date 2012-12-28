<?php
/*
Entry point for setting global values and url mapping
*/
// Be sure to turn this off when not in production mode
ini_set('display_errors', 1);

// Initials
define("CLEAN_URLS", true);

require('./app/core.php');

$parsed = parse_url($_SERVER['REQUEST_URI']);
$path = explode("/", $parsed['path']);
array_splice($path, 0, 2);

// Determine the controller
$controllerName = ucfirst(strtolower($path[0])) . "Controller";
if (($path[0] == "") || (!file_exists("./app/controllers/" . $controllerName . ".php")))
{
	$controllerName = "HomeController";
	require_once('./app/controllers/HomeController.php');
}
else
{
	array_splice($path, 0, 1);
	require_once('./app/controllers/' . $controllerName . '.php');
}

$controller = new $controllerName();

if((!empty($path)) && (method_exists($controller, ucfirst(strtolower($path[0])) . "Action")))
{
	$action = ucfirst(strtolower($path[0])) . "Action";
	array_splice($path, 0, 1);
}
else
{
	$action = "IndexAction";
}
if (CLEAN_URLS)
{
	// Clean up the urls
	$num_params = ceil(count($path) / 2);
	$params = array();
	for($i = 0; $i < $num_params; $i++)
	{
		$key = $i * 2;
		$value = $key + 1;
		if (array_key_exists($value, $path))
		{
			$params[$path[$key]] = $path[$value];
		}
		else
		{
			$params[$path[$key]] = "";
		}
	}
	$controller->setParams($params, "get");
}
else
{
	$controller->setParams($_GET, "get");
}
$controller->setParams($_POST, "post");

$controller->$action();