<?php

class UserAPITest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->config = new SuperScore\Config\Config();
		$this->config->Setup('dev');
	}

	public function testUserSaveAPI()
	{
		// Generate JSON query.
		$context = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/json; charset=utf-8',
				'content' => '{ "UserId":"'.$this->config->test_userid.'", "Data":{ "TestData":"HelloWorld", "SubData":{ "SubData":"HelloSubData" } } }'
			)
		);

		$context  = stream_context_create($context);
		$output = file_get_contents($this->config->host."/user/save", false, $context);
		$this->assertContains('success', $output, '', true);
	}

	public function testUserLoadAPI()
	{
		// Generate JSON query.
		$context = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/json; charset=utf-8',
				'content' => '{ "UserId":"'.$this->config->test_userid.'" }'
			)
		);

		$context  = stream_context_create($context);
		$output = file_get_contents($this->config->host."/user/load", false, $context);
		$this->assertContains('TestData', $output, '', true);
		$this->assertContains('HelloWorld', $output, '', true);
		$this->assertContains('HelloSubData', $output, '', true);
	}
}
