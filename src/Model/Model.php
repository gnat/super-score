<?php namespace SuperScore\Model;

use SuperScore\Library\Database;

/**
* Base class for Models.
*/
class Model
{
	var $db = 0;
	var $table = '';

	/** 
	* Constructor. Optionally pass in database object.
	* @param Database $db Database object. (Optional)
	*/
	function __construct($db = 0) 
	{
		if(!$db) // No existing database passed in? Create one.
			$db = new Database();

		$this->db = $db;
	}

	/** 
	* Count number of entries in this Model's table.
	* @return bool Success?
	*/
	function Count() 
	{
		// Are we connected to a database?
		if(!$this->db)
			return false;

		$db = $this->db->db->prepare("select count(*) from ".$this->table);
		return $db->execute();
	}

	/** 
	* Delete all entries from Model's table.
	* @return bool Success?
	*/
	function DeleteAll() 
	{
		// Are we connected to a database?
		if(!$this->db)
			return false;

		$db = $this->db->db->prepare("delete from ".$this->table);
		return $db->execute();
	}
}
