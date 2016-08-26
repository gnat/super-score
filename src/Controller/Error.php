<?php namespace SuperScore\Controller;

use SuperScore\Library\Utility;

/**
* Controller for Error message.
*/
class Error extends Controller
{
	/** 
	* Display error.
	* @param array Data passed to controller.
	*/
	function Main($data) 
	{
		$this->Json($data); // Default.
	}

	/** 
	* Display JSON error.
	* @param array Data passed to render.
	*/
	function Json($data) 
	{
		$output = Utility::ArrayToJson($data);
		$this->View('json');
		echo $output;
		exit();
	}

	/** 
	* Display HTML error.
	* @param array Data passed to render.
	*/
	function Html($data) 
	{
		$this->View('header');
		$this->View('error', $data);
		$this->View('footer');
		exit();
	}

	/** 
	* Display plain text error.
	* @param array Data passed to render.
	*/
	function Text($data) 
	{
		$this->View('plain');
		foreach($data as $message)
			echo $message;
		exit();
	}
}
