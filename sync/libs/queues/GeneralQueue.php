<?php
abstract class GeneralQueue {
	
	protected $queued = array();
	
	
	protected function out($txt) {
		printf("[%s] %s\n", Color::MAGENTA(get_class($this)), $txt);
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
			$this->out(Color::blue('Begin: '.get_class($queueElem)));
			try {
				$queueElem->preTest();
				if ($queueElem->test()) {
					$queueElem->pre();
					$queueElem->execute();
					$queueElem->post();
				}
				else {
					$queueElem->rescue();
				}
			}
			catch (FatalCommandException $e) {
				$this->out(Color::red('FATAL: '.$e->getMessage()));
				exit;
			}
			catch (CommandException $e) {
				$this->out(Color::yellow('PROBLEM: '.$e->getMessage()));
				$queueElem->rescue();
			}
			$this->out(Color::blue('End: '.get_class($queueElem)));
		}
	}
} 