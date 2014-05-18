<?php

/**
* Query model for constructing sql queries and executing them
*/
class Query {
	// The table name
	private $table;

	// Array of where strings
	private $where = array();

	private $limit;
	
	// Array of all bindings to bind to the statement
	private $bindings = array();

	// The string of types
	private $ypes = '';

	public function __construct($table) {
		$this->table = $table;
	}

	/**
	* Specfies a 'WHERE' condition in the sql
	* @param {string} [key] The key name
	* @param {string} [operator] The operator to use, i.e. =, >, !=...
	* @param {string} [value] The value or right hand side
	* @return void
	*/
	public function where($key, $operator, $value) {
		// Make the template of a where statement
		$key = addslashes(Database::instance()->conn->escape_string($key));

		$this->where[] = "AND {$key} " . $operator . " ?";

		// Add key and value to bindings
		$this->bindings[] = $value;

		$this->types .= 's';
	}

	/**
	* Performs a select statement
	* @param {string} [fields] Comma separated fields to retrieve
	*/
	public function select($fields = '*', $limit = false, $start = false) {
		// The sql
		$sql = "SELECT " . $fields . " FROM `" . $this->table
			. '` WHERE ' . $this->getWhereString()
			. $this->getLimit();

		// Prepare the sql statement
		if (!$stmt = Database::instance()->conn->prepare($sql)) {
			print_r(Database::instance()->conn->error);
			die();
		}

		// Bind to the statement
		// Build the references to each parameter
		$params = array($this->types);

		// Get references to each binding
		for ($i = 0; $i < count($this->bindings); $i++) {
			$params[] = &$this->bindings[$i];
		}

		if (call_user_func_array(array($stmt, 'bind_param'), $params)) {
			$stmt->execute();

			return $stmt->get_result();
		}

		return false;
	}

	/**
	* Uses the where array to make a string
	* @return {string}
	*/
	private function getWhereString() {
		return ltrim(implode($this->where, ' '), 'ANDOR');
	}

	/**
	* Returns the string for the necessary limit
	* @param TODO
	*/
	private function getLimit() {
		// @TODO: Write a limit to string function
	}
}