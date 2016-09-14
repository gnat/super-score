<?php

class UserTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		global $config;
		$this->config = $config;
	}

	public function testUserSave()
	{
		$object = new SuperScore\Model\User();

		// Generate test data.
		$data =	array(
				'UserId'  => $this->config->test_userid,
				'Data'	  => array( 
					"TestData"=>"HelloWorld", 
					"SubData" => array( "SubData" => "HelloSubData" ) 
				)
			);

		$this->assertTrue($object->Save($data));
	}

	// Test database only (cache off).
	public function testUserLoad()
	{
		$this->config->use_apcu = false;
		$this->config->use_redis = false;

		$object = new SuperScore\Model\User();

		// Generate test data.
		$data =	array(
				'UserId'  => $this->config->test_userid
			);

		$output = $object->Load($data);
		$output = json_encode($output);
		$this->assertContains('TestData', $output, '', true);
		$this->assertContains('HelloWorld', $output, '', true);
		$this->assertContains('HelloSubData', $output, '', true);
	}

	// Test APCu cache.
	public function testUserLoadAPCu()
	{
		$this->config->use_apcu = true;
		$this->config->use_redis = false;

		$object = new SuperScore\Model\User();

		// Generate test data.
		$data =	array(
				'UserId'  => $this->config->test_userid
			);

		$output = $object->Load($data);
		$output = json_encode($output);
		$this->assertContains('TestData', $output, '', true);
		$this->assertContains('HelloWorld', $output, '', true);
		$this->assertContains('HelloSubData', $output, '', true);
	}

	// Test Redis cache.
	public function testUserLoadRedis()
	{
		$this->config->use_apcu = false;
		$this->config->use_redis = true;

		$object = new SuperScore\Model\User();

		// Generate test data.
		$data =	array(
				'UserId'  => $this->config->test_userid
			);

		$output = $object->Load($data);
		$output = json_encode($output);
		$this->assertContains('TestData', $output, '', true);
		$this->assertContains('HelloWorld', $output, '', true);
		$this->assertContains('HelloSubData', $output, '', true);
	}
}
