<?php namespace SuperScore\Library\Cache;

use SuperScore\Library\Cache\AbstractCache;

/**
* Cache implementation for Redis.
*/
class Redis extends AbstractCache
{
	var $timeout = 2.5; // Default timeout in seconds.
	var $timeout_interval = 100; // Default timeout interval delay in milliseconds.

	/**
	* Attempt to enable Redis cache. Set up Redis-specific configuration.
	* @return Redis|bool Returns Redis object on success, false on failure.
	*/
	function __construct($config = null)
	{
		// No custom configuration passed? Use default.
		if(empty($config))
			global $config;

		// Redis disabled?
		if(!$config->use_redis)
			return false;
		elseif(!class_exists('\Redis')) // Redis support available on server?
		{
			exit("Error: PHP Redis extension not found. You can disable Redis in Config.php. (".__FILE__.")");
		}
		else
		{
			$redis = new \Redis();

			// Attempt to connect to Redis server.
			if($redis->connect($config->server_redis['host'], $config->server_redis['port'], $this->timeout, NULL, $this->timeout_interval))
			{
				$authorized = false;

				// Try the password if one is configured.
				if(!empty($config->server_redis['password']) && $redis->auth($config->server_redis['password']))
					$authorized = true;
				elseif(empty($config->server_redis['password'])) // No password? Try a basic query.
				{
					try
					{
						if($redis->echo("test") == "test")
							$authorized = true;
					}
					catch(\RedisException $e)
					{
						exit("Error: Could not authenticate to Redis server. Check your Config.php. (".__FILE__.")");
					}
				}

				// Could not authenticate.
				if(!$authorized)
					exit("Error: Could not authenticate to Redis server. Check your Config.php. (".__FILE__.")");

				// Success.
				$redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
				$this->instance = $redis; // Success.
				$this->enabled = true; // Only enable if we get this far.
			}
			else
				return false; // Could not connect to Redis server.
		}
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

		$ttl = intval($ttl);

		if($ttl > 1) // Time to live set?
			return $this->instance->setEx($key, $ttl, $value);
		else
			return $this->instance->set($key, $value);
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

		return $this->instance->get($key);
	}

	/**
	* Delete key/value in the cache.
	* @param string $key Key associated to value.
	* @return bool Success?
	*/
	function KeyDelete($key)
	{
		return $this->instance->delete($key);
	}

	/**
	* Delete all keys/values in the cache.
	* @return bool Success?
	*/
	function KeyDeleteAll()
	{
		return $this->instance->flushAll();
	}
}
