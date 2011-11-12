<?php
abstract class GeneralCommand implements ICommand {
	private $debug = 2;
	
	protected function debug($minLev, $txt) {
		if ($this->debug >= $minLev) {
			$this->out($txt);
		}
	}
	protected function out($txt) {
		printf("[%s] %s\n", get_class($this), $txt);
	}
	
	private function exec($command) {
		$output = array();
		$code = 1;
		$lastLine = exec($command, &$output, &$code);
		$this->debug(2, $command);
		$this->debug(2, print_r($output, true));
		return compact('output', 'code', 'lastLine');
	}
	
	private function passthru($command) {
		$code = 1;
		passthru($command, &$code);
		return $code;
	}
	
	
	protected function localExec($command) {
		return $this->exec($command);
	}
	
	protected function localPassthru($command) {
		return $this->passthru($command);
	}
	
	private function wrapRemote($command) {
		return  'ssh '.Configure::get('src_addr').' '.escapeshellarg($command);
	}
	
	protected function remoteExec($command) {
		return $this->exec($this->wrapRemote($command));
	}
	
	protected function remotePassthru($command) {
		return $this->passthru($this->wrapRemote($command));
	}
	
	public function pre() {
		$this->out('Empty pre()');
	}
	public function test() {
		$this->out('Empty test() -> true');
		return true;
	}
	public function post() {
		$this->out('Empty post()');
	}
	public function rescue() {
		$this->out('Empty rescue()');
	}
	
	
} 