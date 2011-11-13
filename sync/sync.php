#!/usr/bin/php
<?php
define('DS', DIRECTORY_SEPARATOR);
require 'libs'.DS.'ClassLoader.php';
spl_autoload_register(array(new ClassLoader(), 'load'));
try {
	require 'libs'.DS.'Configure.php';
	require 'libs'.DS.'Color.php';
	Configure::parseOpts();
	$queueName = Configure::get('queue');
	$queue = new $queueName();
	$queue->execute();
}
catch (Exception $e) {
	die($e->getMessage());
}


?>
