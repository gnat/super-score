<?php namespace SuperScore;

// Default error reporting.
error_reporting(E_ALL);

// Feel free to use PATH_ROOT as a filesystem anchor.
$test_path = realpath(dirname(__FILE__));
$test_path = substr($test_path, 0, strrpos($test_path, '/'));
define('PATH_ROOT', $test_path);

// Autoload our class files.
require_once(PATH_ROOT.'/src/Library/Autoload.php');
