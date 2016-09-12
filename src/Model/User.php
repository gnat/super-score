<?php namespace SuperScore\Model;

use SuperScore\Library\Database;
use SuperScore\Library\Cache;

/**
* Model for User table.
*/
class User extends Model
{
	var $table = "user";

	/**
	* Save User entry (Create or Update).
	* @param array $data Data for new User entry.
	* @return bool True on Success. False on Failiure.
	*/
	function Save($data)
	{
		// Sanitize.
		if(!isset($data['UserId']) || !isset($data['Data']))
			return false;

		$data['UserId'] = intval($data['UserId']);

		$db = new Database();

		// Have we already recorded this User?
		$dbstate = $db->db->prepare("select * from ".$this->table." where UserId=:UserId limit 1");
		$dbstate->bindValue(":UserId", $data['UserId']);
		$dbstate->execute();

		// Yes. Update the User data instead.
		if($dbstate->rowCount() > 0)
		{
			$result = $dbstate->fetch();
			$result = json_decode($result[1], true);

			if($result && $data['Data'])
			{
				// Replace only what we already have, otherwise, append. Never delete.
				$new = array_replace_recursive($result, $data['Data']);
				$new = json_encode($new);

				// Update User entry.
				$dbstate = $db->db->prepare("update ".$this->table." set Data=:Data where UserId=:UserId limit 1");
				$dbstate->bindValue(":Data", $new);
				$dbstate->bindValue(":UserId", $data['UserId']);
			}
		}
		else // No, create new User entry.
		{
			$new = json_encode($data['Data']);

			if(!$new)
				return false; // Could not encode data for new User.

			$dbstate = $db->db->prepare("insert into ".$this->table." (UserId,Data) values(:UserId,:Data)");
			$dbstate->bindValue(":UserId", $data['UserId']);
			$dbstate->bindValue(":Data", $new);
		}

		if($dbstate->execute())
			return true;
		else
			return false;
	}

	/**
	* Load User entry.
	* @param array $data UserId to load User data.
	* @return bool Return JSON data on Success. False on Failiure.
	*/
	function Load($data)
	{
		// Sanitize.
		if(!isset($data['UserId']))
			return false;

		$data['UserId'] = intval($data['UserId']);

		// First, try the cache.
		$cache = new Cache();
		$cache_key = 'User.Load.'.$data['UserId'];
		$output = $cache->KeyGet($cache_key);
		$output = json_decode($output, true);

		// If in cache, return it.
		if(!empty($output))
			return $output;

		// Second, do real database query.
		$db = new Database();

		// Have we recorded this User?
		$dbstate = $db->db->prepare("select * from ".$this->table." where UserId=:UserId limit 1");
		$dbstate->bindValue(":UserId", $data['UserId']);
		$dbstate->execute();

		// Yes. Return the User data we saved.
		if($dbstate->rowCount() > 0)
		{
			$result = $dbstate->fetch();
			$result = json_decode($result[1], true);

			// Save to cache for next time, 10 second expiry.
			$cache->KeySet($cache_key, json_encode($result), 10);

			if(!empty($result))
				return $result;
		}
		
		return false; // No, user found.
	}
}
