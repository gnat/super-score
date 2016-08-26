<?php namespace SuperScore\Model;

use SuperScore\Library\Database;

/**
* Model for Score table.
*/
class Score extends Model
{
	var $table = "score";

	/**
	* Save a Score entry.
	* @param array $data Data for Score entry.
	* @return bool Score data on Success. False on Failiure to update.
	*/
	function Save($data)
	{
		// Sanitize.
		if(!isset($data['UserId']) || !isset($data['LeaderboardId']) || !isset($data['Score']))
			return false;

		$data['UserId'] = intval($data['UserId']);
		$data['LeaderboardId'] = intval($data['LeaderboardId']);
		$data['Score'] = intval($data['Score']);
		$score_new = $data['Score'];

		$db = new Database();

		// Try to get the Score for this User on this Leaderboard.
		$dbstate = $db->db->prepare("select * from ".$this->table." where UserId=:UserId and LeaderboardId=:LeaderboardId limit 1");
		$dbstate->bindValue(":UserId", $data['UserId']);
		$dbstate->bindValue(":LeaderboardId", $data['LeaderboardId']);
		$dbstate->execute();

		// None exist, post the score.
		if($dbstate->rowCount() == 0)
		{
			$dbstate = $db->db->prepare("insert ignore into ".$this->table." (UserId,LeaderboardId,Score) values(:UserId,:LeaderboardId,:Score)");
			$dbstate->bindValue(":UserId", $data['UserId']);
			$dbstate->bindValue(":LeaderboardId", $data['LeaderboardId']);
			$dbstate->bindValue(":Score", $data['Score']);
			$dbstate->execute();
		}
		else // Score exists for this User/Leaderboard combo, should we update it?
		{
			$result = $dbstate->fetch();
			if(isset($result["Score"]))
				$score_new = $result['Score'];

			if($data['Score'] > $score_new)
			{
				// Update Score entry.
				$dbstate = $db->db->prepare("update ".$this->table." set Score=:Score where UserId=:UserId and LeaderboardId=:LeaderboardId limit 1");
				$dbstate->bindValue(":UserId", $data['UserId']);
				$dbstate->bindValue(":LeaderboardId", $data['LeaderboardId']);
				$dbstate->bindValue(":Score", $data['Score']);
				$dbstate->execute();

				$score_new = $data['Score'];
			}
		}
	
		// Re-calculate User rank and return.
		$dbstate = $db->db->prepare("select count(*) from ".$this->table." where LeaderboardId=:LeaderboardId and Score>=:Score order by Score desc");
		$dbstate->bindValue(":LeaderboardId", $data['LeaderboardId']);
		$dbstate->bindValue(":Score", $score_new);
		$dbstate->execute();
		$result = $dbstate->fetch();

		$output = array(
			"UserId" => $data['UserId'],
			"LeaderboardId" => $data['LeaderboardId'],
			"Score" => $score_new,
			"Rank" => $result[0]
		);

		return $output;
	}

	/**
	* Retrieve the Leaderboard.
	* @param array $data Data for Leaderboard query.
	* @return bool Leaderboard data on Success. False on Failiure.
	*/
	function Leaderboard($data)
	{
		// Sanitize.
		if(!isset($data['UserId']) || !isset($data['LeaderboardId']) || !isset($data['Offset']) || !isset($data['Limit']))
			return false;

		$data['UserId'] = intval($data['UserId']);
		$data['LeaderboardId'] = intval($data['LeaderboardId']);
		$data['Offset'] = intval($data['Offset']);
		$data['Limit'] = intval($data['Limit']);
		$score = 0;
		$rank_user = 0;

		$db = new Database();

		// Find the User's highest score on this Leaderboard.
		$dbstate = $db->db->prepare("select Score from ".$this->table." where LeaderboardId=:LeaderboardId and UserId=:UserId limit 1");
		$dbstate->bindValue(":LeaderboardId", $data['LeaderboardId']);
		$dbstate->bindValue(":UserId", $data['UserId']);
		$dbstate->execute();
		$result = $dbstate->fetch();

		if(isset($result["Score"]))
			$score = $result["Score"];

		// Re-calculate User rank and return.
		$dbstate = $db->db->prepare("select count(*) from ".$this->table." where LeaderboardId=:LeaderboardId and Score>=:Score order by Score desc");
		$dbstate->bindValue(":LeaderboardId", $data['LeaderboardId']);
		$dbstate->bindValue(":Score", $score);
		$dbstate->execute();
		$result = $dbstate->fetch();
		
		if(isset($result[0]))
			$rank_user = $result[0];

		// Find top ranking Users on the Leaderboard for the specified Offset and Limit.
		$dbstate = $db->db->prepare("select * from ".$this->table." where LeaderboardId=:LeaderboardId order by Score desc limit ".$data['Limit']." offset ".$data['Offset']);
		$dbstate->bindValue(":LeaderboardId", $data['LeaderboardId']);
		$dbstate->execute();
		$result = $dbstate->fetchAll();

		$leaderboard = array();
		$rank = 1 + $data['Offset']; // Offset rankings. Start at 1.

		// Assign users a ranking.
		foreach($result as $user)
		{
			$leaderboard[] = array(
				"UserId" => intval($user["UserId"]),
				"Score" => intval($user["Score"]),
				"Rank" => intval($rank)
			);
			$rank += 1; 
		}

		// Generate final JSON data.
		$output = array(
			"UserId" => intval($data['UserId']),
			"LeaderboardId" => intval($data['LeaderboardId']),
			"Score" => intval($score),
			"Rank" => intval($rank_user),
			"Entries" => $leaderboard
		);

		return $output;
	}
}
