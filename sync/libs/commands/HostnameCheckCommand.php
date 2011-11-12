<?php
class HostnameCheckCommand extends GeneralCommand {
	
	public function test() {
		$exec = $this->remoteExec('uname');
		return $exec['code'] === 0 && $exec['output'][0] == 'Linux';
	}
	
	public function execute() {
		 $exec = $this->remoteExec('hostname');
		 if ($exec['code'] !== 0 || $exec['output'][0] !== Configure::get('src_hostname')) {
		 	throw new FatalCommandException('You are on the wrong hostname');
		 }
	}
}
?>
