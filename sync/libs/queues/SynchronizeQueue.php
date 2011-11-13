<?php
class SynchronizeQueue extends GeneralQueue {
	public function execute() {
		#$this->enqueue(new MysqlCommand());
		$this->enqueue(new HomeCommand());
		parent::execute();
	}
} 