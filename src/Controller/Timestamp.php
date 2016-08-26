<?php namespace SuperScore\Controller;

/**
* Sample API endpoint controller.
*/
class Timestamp extends Controller
{
	/** 
	* Display Unix timestamp.
	* @param array Data passed to controller. Not used.
	*/
	function Main($data) 
	{
		$output = array("timestamp" => time());
		$output = json_encode($output);
		$this->View('json');
		echo $output;
	}
}
