<?php
class NoXQueue extends GeneralQueue {
	public function execute() {
		$this->enqueue(new HomeCommand());
		parent::execute();
	}
} 