<?php

class CacheTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		global $config;
		$this->config = $config;
	}

	// No cache.
	public function testKeySetNoCache()
	{
		$this->config->use_apcu = false;
		$this->config->use_redis = false;

		$cache = new SuperScore\Library\Cache();

		$output = $cache->KeySet('HelloWorld', 'HelloWorld', 10);
		$this->assertNull($output);
	}

	// No cache.
	public function testKeyGetNoCache()
	{
		$this->config->use_apcu = false;
		$this->config->use_redis = false;

		$cache = new SuperScore\Library\Cache();

		$output = $cache->KeyGet('HelloWorld');
		$this->assertNull($output);
	}

	// No cache.
	public function testKeyDeleteNoCache()
	{
		$this->config->use_apcu = false;
		$this->config->use_redis = false;

		$cache = new SuperScore\Library\Cache();

		$output = $cache->KeyDelete('HelloWorld');
		$this->assertFalse($output);
	}

	// APCu cache.
	public function testKeySetAPCu()
	{
		$this->config->use_apcu = true;
		$this->config->use_redis = false;

		$cache = new SuperScore\Library\Cache();

		$output = $cache->KeySet('HelloWorld', 'HelloWorld', 10);
		$this->assertTrue($output);
	}

	// APCu cache.
	public function testKeyGetAPCu()
	{
		$this->config->use_apcu = true;
		$this->config->use_redis = false;

		$cache = new SuperScore\Library\Cache();

		$output = $cache->KeyGet('HelloWorld');
		$this->assertContains('HelloWorld', $output, '', true);
	}

	// APCu cache.
	public function testKeyDeleteAPCu()
	{
		$this->config->use_apcu = true;
		$this->config->use_redis = false;

		$cache = new SuperScore\Library\Cache();

		$output = $cache->KeyDelete('HelloWorld');
		$this->assertTrue($output);
	}

	// Redis cache.
	public function testKeySetRedis()
	{
		$this->config->use_apcu = false;
		$this->config->use_redis = true;

		$cache = new SuperScore\Library\Cache();

		$output = $cache->KeySet('HelloWorld', 'HelloWorld', 10);
		$this->assertTrue($output);
	}

	// Redis cache.
	public function testKeyGetRedis()
	{
		$this->config->use_apcu = false;
		$this->config->use_redis = true;

		$cache = new SuperScore\Library\Cache();

		$output = $cache->KeyGet('HelloWorld');
		$this->assertContains('HelloWorld', $output, '', true);
	}

	// Redis cache.
	public function testKeyDeleteRedis()
	{
		$this->config->use_apcu = false;
		$this->config->use_redis = true;

		$cache = new SuperScore\Library\Cache();

		$output = $cache->KeyDelete('HelloWorld');
		$this->assertTrue($output);
	}
}
