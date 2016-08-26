<?php namespace SuperScore;

/**
* Super Score.
* Mobile game leaderboards, currency logging, and user data storage.
* @author Nathaniel Sabanski
* @link http://github.com/gnat/super-score
* @license MIT license.
*/

// Default error reporting.
error_reporting(E_ALL);

// Feel free to use PATH_ROOT as a filesystem anchor.
define('PATH_ROOT', realpath(dirname(__FILE__)));

// Autoload our class files.
require_once(PATH_ROOT.'/src/Library/Autoload.php');

// Set up for development or production.
$config = new Config\Config();
$config->Setup('production');

// Route request to proper controller.
$router = new Library\Router();
$router->Route($_SERVER['REQUEST_URI']); // Go !
