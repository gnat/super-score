<?php namespace SuperScore\Controller;

/**
* Home page controller. (Sample, not used).
*/
class Home extends Controller
{
	/** 
	* Display welcome notice.
	* @param array Data passed to controller. Not used.
	*/
	function Main($data) 
	{
		$data = array("Message" => "Welcome. Please select an API.");
		$this->View('header');
		$this->View('notice', $data);
		$this->View('footer');
		exit();
	}
}
