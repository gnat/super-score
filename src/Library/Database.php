<?php namespace SuperScore\Library;

/**
* Lightweight database layer on top of PDO.
* When instanced, sets up a sane PDO connection.
*/
class Database
{
	var $db = 0;
	var $host = "";
	var $user = "";
	var $password = "";
	var $name = "";
	var $prefix = "";

	/** 
	* Attempt to connect to database using the given configuration. Return PDO object.
	* @param Config $config Configuration object. Optional.
	* @return PDO|bool Returns PDO object on success, false on failure.
	*/
	function __construct($config = null) 
	{
		// No custom configuration passed? Use default.
		if(empty($config))
			global $config;

		// Sanity check.
		if(empty($config->db_host) || empty($config->db_user) || empty($config->db_name))
			exit("Error: Incomplete database configuration. (".__FILE__.")");

		// Attempt connection to database.
		try
		{
			$this->db = new \PDO("mysql:host=".$config->db_host.";dbname=".$config->db_name."", $config->db_user, $config->db_password);
		}
		catch(\PDOException $e)
		{
			exit("Error: Database connection failed. (".__FILE__.")");
		}

		$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
	}

	/** 
	* Return PDO error info if available.
	* @return string Returns PDO error info.
	*/
	function Error() 
	{
		return $this->db->errorInfo();
	}
}

