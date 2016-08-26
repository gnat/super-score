<?php namespace SuperScore\Controller;

use SuperScore\Library\Database;
use SuperScore\Model;

/**
* Controller for Transaction API.
*/
class Transaction extends Controller
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
	* Retrieve Transaction stats.
	* @param array Data passed to controller.
	*/
	function Load($data) 
	{
		$data = strval($data);
		$data = json_decode($data, true);

		if(!$data)
			$this->Error("Invalid JSON.");

		$transaction = new Model\Transaction();
		$transaction = $transaction->Load($data);

		if(empty($transaction))
			$this->Error("Could not load Transactions for this User.");
		
		$output = json_encode($transaction);
		$this->View('json');
		echo $output;
	}

	/** 
	* Save Transaction.
	* @param array Data passed to controller.
	*/
	function Save($data) 
	{
		$data = strval($data);
		$data = json_decode($data, true);

		if(!$data)
			$this->Error("Invalid JSON.");

		$transaction = new Model\Transaction();
		$transaction = $transaction->Save($data);

		if(!$transaction)
			$this->Error("Could not save Transaction. Transaction may already exist.");
		else
			$this->Success();
	}
}
