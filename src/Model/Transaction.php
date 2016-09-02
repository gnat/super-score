<?php namespace SuperScore\Model;

use SuperScore\Library\Database;
use SuperScore\Library\Cache;

/**
* Model for Transaction table.
*/
class Transaction extends Model
{
	var $table = "transaction";
	
	/**
	* Save new Transaction.
	* @param array $data Data for new Transaction.
	* @return bool True on Success. False on Failiure.
	*/
	function Save($data)
	{
		// Sanitize.
		if(!isset($data['TransactionId']) || !isset($data['UserId']) || !isset($data['CurrencyAmount']) || !isset($data['Verifier']))
			return false;

		$data['TransactionId'] = intval($data['TransactionId']);
		$data['UserId'] = intval($data['UserId']);
		$data['CurrencyAmount'] = intval($data['CurrencyAmount']);

		global $config;
		
		// Verify hash.
		if(sha1($config->secret_key.$data['TransactionId'].$data['UserId'].$data['CurrencyAmount']) != $data['Verifier'])
			return false;

		$db = new Database();

		// Have we already recieved this Transaction?
		$dbstate = $db->db->prepare("select * from ".$this->table." where TransactionId=:TransactionId limit 1");
		$dbstate->bindValue(":TransactionId", $data['TransactionId']);
		$dbstate->execute();

		if($dbstate->rowCount() > 0)
			return false;

		// New transaction. Create it.
		$dbstate = $db->db->prepare("insert ignore into ".$this->table." (TransactionId,UserId,CurrencyAmount,Verifier) values(:TransactionId,:UserId,:CurrencyAmount,:Verifier)");
		$dbstate->bindValue(":TransactionId", $data['TransactionId']);
		$dbstate->bindValue(":UserId", $data['UserId']);
		$dbstate->bindValue(":CurrencyAmount", $data['CurrencyAmount']);
		$dbstate->bindValue(":Verifier", $data['Verifier']);

		if($dbstate->execute())
			return true;
		else
			return false;
	}

	/**
	* Load Transaction stats.
	* @param array $data UserId.
	* @return array Results: (UserId, TransactionCount, CurrencySum)
	*/
	function Load($data)
	{
		// Sanitize.
		if(!isset($data['UserId']))
			return false;

		$data['UserId'] = intval($data['UserId']);

		// First, try the cache.
		$cache = new Cache();
		$cache_key = 'Transaction:UserId:'.$data['UserId'];
		$output = $cache->KeyGet($cache_key);
		$output = json_decode($output);

		if(!empty($output))
			return $output;

		// Second, do real database query.
		$db = new Database();

		// Retrieve Transaction stats.
		$dbstate = $db->db->prepare("select count(*),sum(CurrencyAmount) from ".$this->table." where UserId=:UserId");
		$dbstate->bindValue(":UserId", $data['UserId']);
		$dbstate->execute();
		$result = $dbstate->fetch();

		// Format output array.
		$output = array(
			'UserId' => intval($data['UserId']),
			'TransactionCount' => intval($result[0]),
			'CurrencySum' => intval($result[1])
			);

		// Save to cache for next time.
		$cache->KeySet($cache_key, json_encode($output));

		return $output;
	}
}
