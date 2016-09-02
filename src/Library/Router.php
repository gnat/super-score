<?php namespace SuperScore\Library;

use SuperScore\Controller\Error;

/**
* Basic request routing.
* Add more routes in Router::Map()
*/
class Router
{
	var $routable = array();

	/** 
	* Attempt to route this request to the appropriate controller.
	* @param string $request Request URI passed to the server.
	*/
	function Route($request) 
	{
		$method = 'Main'; // Default method to route to within controller.
		$data = array(); // Data to be passed to controller.

		// Process request.
		$request = trim($request);
		$request = explode('?', $request);
		$route = explode('/', $request[0]);

		// Route available?
		if(empty($route[1]))
			$this->Error(); // No route specified. Cannot route.

		// Route method available?
		if(isset($route[2]) && !empty($route[2]))
			$method = $route[2];

		$post = file_get_contents("php://input"); // Get raw POST data.

		// Route POST data available?
		if(isset($post) && !empty($post))
			$data = $post;

		// Try to map the requested route to a real controller.
		$name = $this->Map($route[1]);

		if(class_exists($name)) 
		{
			$controller = new $name;

			// Ensure the requested method exists as well.
			if(method_exists($controller, $method)) 
				$controller->$method($data); // Success.
			else
				$this->Error(); // Cannot route.
		}
		else
			$this->Error(); // Cannot route.
	}

	/** 
	* Routing problem. Show JSON error message.
	*/
	function Error() 
	{
		$controller = new Error();
		$controller->Main(array(
			"Error" => "Please choose a valid API."
		));
	}

	/**
	* Map a route name to a real controller,
	* @param string $name Name passed in via URL to be used as routing key.
	* @return string|null Real controller string or null on failure.
	*/
	function Map($name = "")
	{
		$name = strtolower($name);

		// Add more public routes for your App here.
		$routes = array(
				"error" => "SuperScore\Controller\Error",
				"home" => "SuperScore\Controller\Home",
				"reset" => "SuperScore\Controller\Reset",
				"score" => "SuperScore\Controller\Score",
				"timestamp" => "SuperScore\Controller\Timestamp",
				"transaction" => "SuperScore\Controller\Transaction",
				"user" => "SuperScore\Controller\User",
				"welcome" => "SuperScore\Controller\Home"
			);

		$controller = null;

		if(isset($routes[$name]))
			$controller = $routes[$name];

		return $controller;
	}
}
