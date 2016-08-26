<?php namespace SuperScore\Controller;

use SuperScore\Model;

/**
* Controller for User API.
*/
class User extends Controller
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
	* Load User entry and data.
	* @param array Data passed to controller.
	*/
	function Load($data) 
	{
		$data = strval($data);
		$data = json_decode($data, true);

		if(!$data)
			$this->Error("Invalid JSON.");

		$user = new Model\User();
		$user = $user->Load($data);
		
		$this->View('json');

		if(empty($user))
			echo "{ }"; // Blank JSON object.
		else
			echo json_encode($user);

		exit();
	}

	/** 
	* Save User entry and data.
	* @param array Data passed to controller.
	*/
	function Save($data) 
	{
		$data = strval($data);
		$data = json_decode($data, true);

		if(!$data)
			$this->Error("Invalid JSON.");

		$user = new Model\User();
		$user = $user->Save($data);

		if(empty($user))
			$this->Error("Could not create or update User.");
		else
			$this->Success();
	}
}
