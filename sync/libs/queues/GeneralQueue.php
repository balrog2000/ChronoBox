<?php
abstract class GeneralQueue {
	
	protected $queued = array();
	
	
	protected function out($txt) {
		printf("[%s] %s\n", get_class($this), $txt);
	}
	
	protected function enqueue(ICommand $command) {
		$this->queued[] = $command;
	}
	
	private function fixMissing() {
		$hasHostnameCheck = false;
		foreach ($this->queued as &$queueElem) {
			if ($queueElem instanceof HostnameCheckCommand) {
				$hasHostnameCheck = true;
				break;
			}
		}
		if (!$hasHostnameCheck) {
			array_unshift($this->queued, new HostnameCheckCommand());
		}
	}
	
	public function execute() {
		$this->fixMissing();
		foreach ($this->queued as &$queueElem) {
			try {
				if ($queueElem->test()) {
					$queueElem->pre();
					$queueElem->execute();
					$queueElem->post();
				}
			}
			catch (FatalCommandException $e) {
				$this->out('FATAL: '.$e->getMessage());
				exit;
			}
			catch (CommandException $e) {
				$this->out('PROBLEM: '.$e->getMessage());
				$queueElem->rescue();
			}
		}
	}
} 