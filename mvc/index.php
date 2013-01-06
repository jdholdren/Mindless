<?php
/*
Entry point for setting global values and url mapping
*/
// Be sure to turn this off when not in production mode
ini_set('display_errors', 1);
/*
error_reporting(0);
@ini_set('display_errors', 0);
*/
// Initials

// Requie the core
require('./app/core.php');


// ROUTING LOGIC
$parsed = parse_url(strtolower($_SERVER['REQUEST_URI']));
$path = explode("/", $parsed['path']);
array_splice($path, 0, 1);
$script = explode("/", str_replace($_SERVER['DOCUMENT_ROOT'], "", $_SERVER['SCRIPT_FILENAME']));


while ($path[0] == $script[0])
{
	array_splice($path, 0 ,1);
	array_splice($script, 0,1);
}
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

// Remove the trailing space, if there is one
if (end($path) == "")
{
	array_pop($path);
}
reset($path);
$controller->invoke($action, $path);