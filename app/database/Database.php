<?php

/**
* Database access layer
**/

class Database {
	/**
	* The singleton instance of the db object
	*/
	private static $db;

	// The mysqli object
	public $conn;

	/**
	* Constructor
	* @param array of settings {
	* 	@param the user name
	* 	@param the password
	* 	@param the table name
	* 	@param the host name
	* }
	*/
	private function __construct($settings) {
		try {
			// Connect to db here
			$this->conn = new mysqli($settings['host'], $settings['user'], $settings['password'], $settings['databaseName']);
		}
		catch(Exception $e) {
			die($e->getMessage());
		}
	}

	/**
	* Sanitizes vars, replaces them in sql, and executes
	* @param string $sql (use <> for placeholders)
	* @param replace the values to be bound in order
	* @return mysqli result
	*/
	public static function query($sql, $replace) {
		if(is_array($replace)) {
			// Replace each <>
			foreach($replace as $value) {
				$sanit = Database::instance()->conn->escape_string($value);
				$sql = preg_replace("/<>/", $sanit, $sql, 1);
			}
		}
		else {
			// Replace <> just once
			$replace = Database::instance()->conn->escape_string($replace);
			$sql = preg_replace("/<>/", $replace, $sql, 1);
		}
		
		$result = $this->conn->query($sql);
				
		return $result;
	}

	/**
	* Gets the last insert id
	* @return int the id
	*/
	public static function getLastInsertId() {
		return $this->conn->insert_id;
	}

	/**
	* Returns the instance of the db object
	*/
	public static function & instance() {
		// Only create an instance if none exist
		if (!self::$db) {
			self::$db = new Database(Config::$db);
		}
		return self::$db;
	}
}