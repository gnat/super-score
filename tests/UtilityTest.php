<?php

class UtilityTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->config = new SuperScore\Config\Config();
		$this->config->Setup('dev');
	}

	public function testArrayToJson()
	{
		$data = null;
		$output = SuperScore\Library\Utility::ArrayToJson($data);
		$this->assertContains('{ }', $output);

		$data = 123456;
		$output = SuperScore\Library\Utility::ArrayToJson($data);
		$this->assertContains('123456', $output);

		$data = array();
		$output = SuperScore\Library\Utility::ArrayToJson($data);
		$this->assertContains('{ }', $output);

		$data = array("Hello" => "World");
		$output = SuperScore\Library\Utility::ArrayToJson($data);
		$this->assertContains('{"Hello":"World"}', $output);
	}
}
