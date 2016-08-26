<?php

class TransactionAPITest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->config = new SuperScore\Config\Config();
		$this->config->Setup('dev');
	}

	public function testTransactionSaveAPI()
	{
		// Generate JSON query.
		$verifier = sha1($this->config->secret_key.$this->config->test_transactionid.$this->config->test_userid."2");

		$context = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/json; charset=utf-8',
				'content' => '{ "TransactionId":"'.$this->config->test_transactionid.'", "UserId":"'.$this->config->test_userid.'", "CurrencyAmount":"2", "Verifier":"'.$verifier.'" }'
			)
		);

		$context  = stream_context_create($context);
		$output = file_get_contents($this->config->host."/transaction/save", false, $context);
		$this->assertContains('success', $output, '', true);
	}

	public function testTransactionLoadAPI()
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
		$output = file_get_contents($this->config->host."/transaction/load", false, $context);
		$this->assertContains('userid', $output, '', true);
		$this->assertContains('transactioncount', $output, '', true);
		$this->assertContains('currencysum', $output, '', true);
	}
}
