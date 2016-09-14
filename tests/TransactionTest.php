<?php

class TransactionTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		global $config;
		$this->config = $config;
	}

	public function testTransactionSave()
	{
		$object = new SuperScore\Model\Transaction();
		$object->DeleteAll(); // Remove old data.

		// Generate test data.
		$verifier = sha1($this->config->secret_key.$this->config->test_transactionid.$this->config->test_userid."2");

		$data =	array(
				'TransactionId'  => $this->config->test_transactionid,
				'UserId'  => $this->config->test_userid,
				'CurrencyAmount'  => 2,
				'Verifier'  => $verifier
			);

		$output = $object->Save($data);
		$this->assertTrue($output);
	}

	// Test database only (cache off).
	public function testTransactionLoad()
	{
		$this->config->use_apcu = false;
		$this->config->use_redis = false;

		$object = new SuperScore\Model\Transaction();

		// Generate test data.
		$data =	array(
				'UserId'  => $this->config->test_userid
			);

		$output = $object->Load($data);
		
		// Test data to check against output.
		$test = array(
				"UserId" => 1,
				"TransactionCount" => 1,
				"CurrencySum" => 2
			);

		$this->assertEquals($output, $test);
	}
}
