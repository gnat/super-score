<?php namespace SuperScore\Library;

/**
* Collection of useful Utility methods which can be called statically.
*/
class Utility
{
	/**
	* Converts associative Array into JSON object.
	* If this is not possible, returns a valid but blank JSON object.
	* @return String Valid JSON string.
	*/
	static function ArrayToJson($data = array()) 
	{
		if(empty($data))
			return "{ }"; // Blank JSON object.

		$data = json_encode($data);
		
		if(empty($data))
			return "{ }"; // Blank JSON object.
		else
			return $data;
	}
}
