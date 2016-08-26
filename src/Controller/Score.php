<?php namespace SuperScore\Controller;

use SuperScore\Library\Database;
use SuperScore\Model;

/**
* Controller for Score API.
*/
class Score extends Controller
{
	/** 
	* Default.
	* @param array Data passed to controller.
	*/
	function Main($data) 
	{
		$this->Load($data); // Default.
	}

	/** 
	* Load Leaderboard ranking.
	* @param array Data passed to controller.
	*/
	function Load($data) 
	{
		$data = strval($data);var_dump($data);
		$data = json_decode($data, true);

		if(!$data)
			$this->Error("Invalid JSON.");

		$score = new Model\Score();
		$score = $score->Leaderboard($data);

		if(empty($score))
			$this->Error("Could not load leaderboards.");
		
		$output = json_encode($score);
		$this->View('json');
		echo $output;
		exit();
	}

	/** 
	* Save score and find Leaderboard ranking.
	* @param array Data passed to controller.
	*/
	function Save($data) 
	{
		$data = strval($data);
		$data = json_decode($data, true);

		if(!$data)
			$this->Error("Invalid JSON.");

		$score = new Model\Score();
		$score = $score->Save($data);

		if(empty($score))
			$this->Error("Could not save score.");
		
		$output = json_encode($score);
		$this->View('json');
		echo $output;
		exit();
	}
}
