<?php namespace SuperScore\Library;

use SuperScore\Library\Cache;

/**
* Tiered cache management.
* Queries fast APCu cache first, then the optional distributed Redis cache second.
*/
class Cache
{
	var $apcu = false;
	var $redis = false;

	/**
	* Set up cache instances.
	*/
	function __construct($config = null) 
	{
		// No custom configuration passed? Use default.
		if(empty($config))
			global $config;

		$this->apcu = new Cache\APCu();
		$this->redis = new Cache\Redis();
	}

	/** 
	* Set value to the associated key in the cache.
	* @param string $key Key associated to value.
	* @param string $value Value associated to key.
	* @param int $ttl Time to live in seconds. 0 will use default in specific cache class.
	* @return bool Success?
	*/
	function KeySet($key, $value, $ttl = 0) 
	{
		$output = null;

		if($this->apcu->enabled)
			$output = $this->apcu->KeySet($key, $value, $ttl);
		if($this->redis->enabled)
			$output = $this->redis->KeySet($key, $value, $ttl);

		return $output;
	}

	/** 
	* Get value of associated key in the cache.
	* @param string $key Key associated to value.
	* @return Array() Value associated to key.
	*/
	function KeyGet($key) 
	{
		$output = null;

		// Always try local APCu cache first!
		if($this->apcu->enabled)
			$output = $this->apcu->KeyGet($key);
		
		if(!empty($output))
			return $output;

		// Redis cache next!
		if($this->redis->enabled)
			$output = $this->redis->KeyGet($key);
		
		if(!empty($output) || is_array($output))
			return $output;

		return null;
	}

	/** 
	* Delete key/value in the cache.
	* @param string $key Key associated to value.
	* @return bool Success?
	*/
	function KeyDelete($key)
	{
		if($this->apcu->enabled)
			return $this->apcu->KeyDelete($key);
		if($this->redis->enabled)
			return $this->redis->KeyDelete($key);

		return false;
	}

	/** 
	* Delete all keys/values in all caches.
	* @return bool Success?
	*/
	function DeleteAll()
	{
		if($this->apcu->enabled)
			$this->apcu->KeyDeleteAll();
		if($this->redis->enabled)
			$this->redis->KeyDeleteAll();

		return true;
	}
}
