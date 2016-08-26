<?php

class ScoreAPITest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->config = new SuperScore\Config\Config();
		$this->config->Setup('dev');
	}

	public function testScoreSaveAPI()
	{
		// Generate JSON query.
		$context = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/json; charset=utf-8',
				'content' => '{ "UserId":"'.$this->config->test_userid.'", "LeaderboardId":"'.$this->config->test_leaderboardid.'", "Score":"1000"}'
			)
		);

		$context  = stream_context_create($context);
		$output = file_get_contents($this->config->host."/score/save", false, $context);
		$this->assertContains('score', $output, '', true);
		$this->assertContains('rank', $output, '', true);
	}

	public function testScoreLoadAPI()
	{
		// Generate JSON query.
		$context = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/json; charset=utf-8',
				'content' => '{ "UserId":"'.$this->config->test_userid.'", "LeaderboardId":"'.$this->config->test_leaderboardid.'", "Offset":"0", "Limit":"100"}'
			)
		);

		$context  = stream_context_create($context);
		$output = file_get_contents($this->config->host."/score/load", false, $context);
		$this->assertContains('rank', $output, '', true);
		$this->assertContains('entries', $output, '', true);
	}
}
