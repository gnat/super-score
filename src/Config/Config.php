<?php namespace SuperScore\Config;

/**
* Configuration management.
* Convenient area to switch out configurations on the fly.
*/
class Config
{
	var $environment = ""; // Development or production.
	var $host = ""; // Hostname.
	var $db_host = ""; // Hostname for your database server.
	var $db_user = ""; // Username for database account.
	var $db_password = ""; // Password for database account.
	var $db_name = ""; // Name of database you want to connect to.
	var $db_prefix = ""; // Optional prefix for database tables.
	var $db_url_reset = false; // Is http://localhost/reset available to automatically clear the database?
	var $secret_key = '123456789123456789123456789'; // Change this before going live!

	// For test suite. 
	var $test_userid = 1;
	var $test_leaderboardid = 1;
	var $test_transactionid = 1;

	/**
	* Set up for development.
	*/
	function Development()
	{
		$this->environment = "development";
		$this->host = "http://localhost";
		$this->db_host = "localhost";
		$this->db_user = "root";
		$this->db_password = "";
		$this->db_name = "test";
		$this->db_prefix = "";
		$this->db_url_reset = true;

		error_reporting(E_ALL); // New Error Reporting level.
	}

	/**
	* Set up for production.
	*/
	function Production()
	{
		$this->environment = "production";
		$this->host = "http://localhost";
		$this->db_host = "localhost";
		$this->db_user = "root";
		$this->db_password = "";
		$this->db_name = "test";
		$this->db_prefix = "";
		$this->db_url_reset = false;

		error_reporting(E_ERROR); // New Error Reporting level.
	}

	/**
	* Set up configuration.
	* This extra layer allows general cleanup operations to be
	* performed: Closing database connections, file handles, whatever.
	* @param string $type Currently either "production" or "development".
	*/
	function Setup($type)
	{
		$type = trim(strtolower($type));

		if($type == "production" || $type == "prod")
			$this->Production();
		else
			$this->Development();
	}
}

?>
