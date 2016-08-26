<?php

class TimestampAPITest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->config = new SuperScore\Config\Config();
		$this->config->Setup('dev');
	}

	public function testTimestampAPI()
	{
		$output = file_get_contents($this->config->host."/timestamp");
		$this->assertContains('timestamp', $output, '', true);
	}
}
