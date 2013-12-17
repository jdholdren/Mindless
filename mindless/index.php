<?php
/**
*Entry point for setting global values and url mapping
**/

// Initials
define('DEV_MODE', true);

if (!DEV_MODE) {
	error_reporting(0);
	@ini_set('display_errors', 0);
}

session_start();
// Requie the core
require('./app/core.php');

echo 'Script Filename: ' . $_SERVER['REQUEST_URI'] . PHP_EOL;
print_r($router->determineRoute($_SERVER['REQUEST_URI'], strtolower($_SERVER['REQUEST_METHOD'])));