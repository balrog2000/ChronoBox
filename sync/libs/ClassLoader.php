<?php
set_include_path(
    get_include_path()
    .PATH_SEPARATOR."/libs/"
);
class ClassLoader {
	public static function load($className) {
		$allowedFolders = array(
			'Command'	=> 'libs'.DS.'commands',
			'Queue'		=> 'libs'.DS.'queues',
			'Exception'	=> 'libs'.DS.'exceptions',
			'Helper'	=> 'libs'.DS.'helpers',
		);
		if (preg_match_all('/([A-Z][a-z_0-9]*)/', $className, $matches)) {
			$classParts = $matches[1];
			$folder = end($classParts);
			if (!isset($allowedFolders[$folder])) {
				throw new Exception('Folder not allowed');
			}
			require_once($allowedFolders[$folder].DS.$className.'.php');
		}
		else {
			throw new Exception('Class not found');	
		}
	}
}
?>
