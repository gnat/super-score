<?php namespace SuperScore\Controller;

use SuperScore\Library\Database;
use SuperScore\Model;

/**
* Controller for clearing the databases.
* Probably wouldn't want this in a real production app, but could be useful here.
*/
class Reset extends Controller
{
	/** 
	* Delete all rows in models specified.
	* @param array Data passed to controller. Not used.
	*/
	function Main($data) 
	{	
		global $config;

		// Only continue if configuration allows it.
		if($config->db_url_reset)
		{
			$db = new Database();

			$model = new Model\User($db);
			$model->DeleteAll();
			$model = new Model\Score($db);
			$model->DeleteAll();
			$model = new Model\Transaction($db);
			$model->DeleteAll();
		}
		
		$this->Success();
	}
}
