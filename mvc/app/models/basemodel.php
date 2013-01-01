<?php

abstract class Model
{
	private $conn;

	public function __construct()
	{
		try
		{
			// Connect to db here
			$this->conn = new mysqli();
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
	protected function exec($sql, $replace)
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
}