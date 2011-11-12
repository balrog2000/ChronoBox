<?php
class SynchronizeQueue extends GeneralQueue {
	public function execute() {
		$this->enqueue(new MysqlCommand());
		
		parent::execute();
	}
} 