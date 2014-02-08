<?php

abstract class Model {
	protected $db;
	
	/**
	* @param Key/value array of properties to set on the object
	**/
	public function __construct($properties = array()) {
		foreach($properties as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}

		global $db;
		$this->db = $db;
	}
}