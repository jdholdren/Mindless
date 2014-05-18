<?php
/**
* Any and all configuration info
*/
class Config {
	public static $db = array(
		'host' => '',
		'password' => '',
		'databaseName' => '',
		'user' => ''
	);

	public static $use_db = false;
	public static $strict_routing = false;
	public static $dev_mode = true;

	public function __set($name, $value) {
		return false;
	}
}