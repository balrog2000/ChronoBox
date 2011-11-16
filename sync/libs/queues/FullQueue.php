<?php
class FullQueue extends GeneralQueue {
	public function execute() {
		$x = new XQueue();
		$nox = new NoXQueue();
		$this->enqueueMany($x->getQueue());
		$this->enqueueMany($nox->getQueue());
		parent::execute();
	}
} 