<?php
class PackagesCommand extends GeneralCommand {
	private $updateCmd = 'apt-get update && apt-get dist-upgrade';
	private $pkgList = 'dpkg --get-selections | grep -v deinstall | awk \'{print $1}\'';
	private $toAdd = array();
	private $toDelete = array();
	
	
	private function getPackages($output) {
		$pkg = array();
		foreach($output['output'] as $line) {
			$pkg[] = trim($line);
		}
		sort($pkg);
		return $pkg;
	}
	
	public function preTest() {
		
		$this->localPassthru($this->updateCmd);
		$this->remotePassthru($this->updateCmd);
		
	}
//	
	public function test() {
		$local = $this->localExec($this->pkgList);
		$remote = $this->remoteExec($this->pkgList);
		
		if (($local['code'] | $remote['code']) > 0) {
			throw new CommandException('Unable to query packages');
		}
		
		$pkgLocal = $this->getPackages($local);
		$pkgRemote = $this->getPackages($remote);
		
		$added = array_diff($pkgRemote, $pkgLocal);
		$deleted = array_diff($pkgLocal, $pkgRemote);
		
		
		$this->out(sprintf("local count: %d, remote count: %d", count($pkgLocal), count($pkgRemote)));
		$this->out(sprintf('Added: %s', join(' ', $added)));
		if ($this->userAccept('Install?')) {
			$this->toAdd = $added;
		}
		
	
		$this->out(sprintf('Deleted: %s', join(' ', $deleted)));
		if ($this->userAccept('Uninstall?')) {
			$this->toDelete = $deleted;	
		}
		return true;
	}
//	
	public function execute() {
		if (!empty($this->toAdd)) {
			$this->out('Adding');
			$this->localPassthru('apt-get install '.join(' ', $this->toAdd));
		}
		if (!empty($this->toDelete)) {
			$this->out('Deleting');
			$this->localPassthru('apt-get remove '.join(' ', $this->toDelete));
		}
	}

}
?>
