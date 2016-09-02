<?php namespace SuperScore\Library\Cache;

use SuperScore\Library\Cache\AbstractCache;

/**
* Cache implementation for APCu.
*/
class APCu extends AbstractCache
{
	/** 
	* Attempt to enable APCu cache. Set up APCu-specific configuration.
	* @return APCu|bool Returns APCu object on success, false on failure.
	*/
	function __construct($config = null) 
	{
		// No custom configuration passed? Use default.
		if(empty($config))
			global $config;

		// APCu disabled?
		if(!$config->use_apcu)
			return false;
		elseif(!function_exists('apcu_store')) // APCu support available on server?
		{
			echo "Error: PHP APCu extension not found. APCu is highly recommended. You can disable APCu in Config.php. (".__FILE__.")";
			die();
		}
		else
			$this->enabled = true; // Only enable if we get this far.
	}

	/** 
	* Set value to the associated key in the cache.
	* @param string $key Key associated to value.
	* @param string $value Value associated to key.
	* @param int $ttl Time to live in seconds.
	* @return bool Success?
	*/
	function KeySet($key, $value, $ttl = 0)
	{
		if(empty($key) || empty($value)) // Sanity.
			return false;

		if(!$ttl) // Time to live not explicitly set? Use default.
			$ttl = $this->ttl;

		return apcu_store($key, $value, $ttl);
	}
	
	/** 
	* Get value of associated key in the cache.
	* @param string $key Key associated to value.
	* @return Array() Value associated to key.
	*/
	function KeyGet($key)
	{
		if(empty($key)) // Sanity.
			return false;

		$success = false;
		$data = apcu_fetch($key, $success);

		if(!$success)
			return false;
		else
			return $data;
	}

	/** 
	* Delete key/value in the cache.
	* @param string $key Key associated to value.
	* @return bool Success?
	*/
	function KeyDelete($key)
	{
		return apcu_delete($key);
	}

	/** 
	* Delete all keys/values in the cache.
	* @return bool Success?
	*/
	function KeyDeleteAll()
	{
		return apcu_clear_cache("user");
	}	
}
