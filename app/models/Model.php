<?php

abstract class Model {
	/**
	* The table name to be used when contructing queries from the model
	*/
	protected static $tableName;

	/**
	* @param Key/value array of properties to set on the object
	*/
	public function __construct($properties = array()) {
		foreach($properties as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}

	/**
	* Searches for one record in the db
	* @param {key/val array} [props] Properties to match on the record
	* @return false if none found, associative array otherwise
	*/
	public static function findOne($props) {
		$query = new Query(static::$tableName);

		// Go through each 
		foreach ($props as $key => $value) {
			$query->where($key, '=', $value);
		}

		$result = $query->select();
		print_r($result->fetch_assoc());
	}
}