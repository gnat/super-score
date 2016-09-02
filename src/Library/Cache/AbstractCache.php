<?php namespace SuperScore\Library\Cache;

/**
* Abstract cache interface class.
*/
abstract class AbstractCache
{
	var $enabled = false; // Is this cache type enabled?
	var $ttl = 5; // Default key/value time to live in seconds.

	/** 
	* Set value to the associated key in the cache.
	* @param string $key Key associated to value.
	* @param string $value Value associated to key.
	* @param int $ttl Time to live in seconds.
	* @return bool Success?
	*/
	abstract function KeySet($key, $value, $ttl);
	
	/** 
	* Get value of associated key in the cache.
	* @param string $key Key associated to value.
	* @return Array() Value associated to key.
	*/
	abstract function KeyGet($key);

	/** 
	* Delete key/value in the cache.
	* @param string $key Key associated to value.
	* @return bool Success?
	*/
	abstract function KeyDelete($key);

	/** 
	* Delete all keys/values in the cache.
	* @return bool Success?
	*/
	abstract function KeyDeleteAll();
}
