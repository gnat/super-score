<?php

class ScoreTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		global $config;
		$this->config = $config;
	}

	public function testScoreSave()
	{
		$object = new SuperScore\Model\Score();
		$object->DeleteAll(); // Remove old data.

		// Generate test data.
		$data =	array(
				'UserId'  => $this->config->test_userid,
				'LeaderboardId'  => $this->config->test_leaderboardid,
				'Score'  => 1000
			);

		$output = $object->Save($data);
		$output = json_encode($output);
		$this->assertContains('score', $output, '', true);
		$this->assertContains('rank', $output, '', true);
	}

	// Test database only (cache off).
	public function testScoreLeaderboard()
	{
		$this->config->use_apcu = false;
		$this->config->use_redis = false;

		$object = new SuperScore\Model\Score();

		// Generate test data.
		$data =	array(
				'UserId'  => $this->config->test_userid,
				'LeaderboardId'  => $this->config->test_leaderboardid,
				'Offset'  => 0,
				'Limit' => 0
			);

		$output = $object->Leaderboard($data);
		$output = json_encode($output);
		$this->assertContains('rank', $output, '', true);
		$this->assertContains('entries', $output, '', true);
	}
}
