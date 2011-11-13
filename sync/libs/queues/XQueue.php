<?php
class XQueue extends GeneralQueue {
	public function execute() {
		$this->enqueue(new PackagesCommand());
		$this->enqueue(new EtcCommand());
		$this->enqueue(new MysqlCommand());
		parent::execute();
	}
} 