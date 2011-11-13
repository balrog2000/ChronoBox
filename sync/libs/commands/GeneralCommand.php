<?php
abstract class GeneralCommand implements ICommand {
	private $debug = 2;
	
	protected function debug($minLev, $txt) {
		if ($this->debug >= $minLev) {
			$this->out($txt);
		}
	}
	
	protected function setDebug($debug) {
		$prevDebug = $this->debug;
		$this->debug = $debug;
		return $prevDebug;
	}
	
	protected function out($txt, $nl = true) {
		printf("[%s] %s%s", Color::DARK_MAGENTA(get_class($this)), $txt, $nl ? "\n" : ' ');
	}
	
	protected function userAccept($prompt = 'OK?', $yes = 'y', $no = 'n') {
		$prompt = $prompt." ($yes/$no)";
		$this->out(Color::WHITE($prompt), false);
		if (Configure::get('yes')) {
			echo "\n";
			return true;
		}
		$line = trim(fgets(STDIN));
		return $line === $yes;
	}
	
	private function exec($command) {
		$output = array();
		$code = 1;
		$lastLine = exec($command, &$output, &$code);
		$this->debug(2, Color::WHITE('Exec: ').$command);
		$this->debug(2, Color::WHITE('Output: ').print_r($output, true));
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
	public function preTest() {
		$this->out('Empty preTest()');
		return true;
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