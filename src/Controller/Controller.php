<?php namespace SuperScore\Controller;

use SuperScore\Library\Utility;

/**
* Base class for Controllers.
*/
class Controller
{
	var $view_default = 'notice';
	var $view_path = PATH_ROOT.'/src/View/';
	var $view_extension = '.php';

	/** 
	* Default Main method.
	* @param array Data passed to controller.
	*/
	function Main($data) 
	{
	}

	/** 
	* Find and display View.
	* @param string $name View name.
	*/
	function View($name, $data = null) 
	{
		$name = $this->view_path.$name.$this->view_extension;

		if(file_exists($name))
			include $name;
	}

	/** 
	* Display Error and exit.
	* @param string $message Error message (Optional).
	*/
	function Error($message = "Not available.") 
	{
		$controller = new Error();
		$controller->Main(array(
			"Error" => $message
		));

		exit();
	}

	/** 
	* Display Success and exit.
	*/
	function Success() 
	{
		$data = array("Success" => true);
		$output = Utility::ArrayToJson($data);
		$this->View('json');
		echo $output;
		exit();
	}
}
