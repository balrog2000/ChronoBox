<?php
class SingleQueue extends GeneralQueue {
	public function execute() {
		if (Configure::get('command')) {
			$className = Configure::get('command');
		}
		$this->enqueue(new $className());
		parent::execute();
	}
} 