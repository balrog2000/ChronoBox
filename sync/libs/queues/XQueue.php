<?php
class XQueue extends GeneralQueue {
	public function execute() {
		$this->enqueue(new MysqlCommand());
		$this->enqueue(new PackagesCommand());
		$this->enqueue(new EtcCommand());
		parent::execute();
	}
} 