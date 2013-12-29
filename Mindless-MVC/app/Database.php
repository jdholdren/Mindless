<?php

/**
* Database access layer
**/

class Database
{
	// The mysqli object
	private $conn;

	/**
	* Constructor
	* @param array of settings {
	* 	@param the user name
	* 	@param the password
	* 	@param the table name
	* 	@param the host name
	* }
	**/
	public function __construct($settings)
	{
		try
		{
			// Connect to db here
			$this->conn = new mysqli($settings['host'], $settings['user'], $settings['password'], $settings['databaseName']);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	/*
	Sanitizes vars, replaces them in sql, and executes
	@param string $sql (use <> for placeholders)
	@param replace the values to be bound in order
	@return mysqli result
	*/
	public function query($sql, $replace)
	{
		if(is_array($replace))
		{
			// Replace each <>
			foreach($replace as $value)
			{
				$sanit = $this->conn->escape_string($value);
				$sql = preg_replace("/<>/", $sanit, $sql, 1);
			}
		}
		else
		{
			// Replace <> just once
			$replace = $this->conn->escape_string($replace);
			$sql = preg_replace("/<>/", $replace, $sql, 1);
		}
		
		$result = $this->conn->query($sql);
				
		return $result;
	}

	/**
	* Gets the last insert id
	* @return int the id
	**/
	public function getLastInsertId()
	{
		return $this->conn->insert_id;
	}
}