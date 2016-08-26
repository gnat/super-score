<?php namespace SuperScore;

/**
* Autoloader with recursive directory search.
*/
class Autoload
{
	/** 
	* Attempt to load class file by name.
	* @param string $name Class name.
	*/
	static function Load($class) 
	{
		// project-specific namespace prefix
		$prefix = 'SuperScore\\';

		// base directory for the namespace prefix
		$base_dir = PATH_ROOT.'/src/';

		// does the class use the namespace prefix?
		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) !== 0) {
		    // no, move to the next registered autoloader
		    return;
		}

		// get the relative class name
		$relative_class = substr($class, $len);

		// replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php
		$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

		// if the file exists, require it
		if (file_exists($file))
		    require $file;

		// Class not found: Simply continue so we can let PHP throw a warning on the correct line.
	}
}

// Register this class to handle autoloading.
spl_autoload_register(array('SuperScore\Autoload', 'Load'), true);
