<?php
class EtcCommand extends GeneralCommand {
	private $dirService = array(
		'/etc/apache2/' 				=> 'apache2',
		'/etc/mysql/'					=> 'mysql',
		'/etc/nginx/'					=> 'nginx',
		'/etc/ejabberd/' 				=> null,
		'/etc/phpmyadmin/' 				=> null,
		'/etc/hosts'					=> null,
		'/etc/udev/rules.d/51-android.rules' => null,
		'/etc/apt/'						=> null,
	);
	private $toMove = array();
	
	public function test() {
		foreach ($this->dirService as $dir => $svc) {
			$this->out('Testing service: '. Color::WHITE($dir));
			$cmd = RsyncHelper::cmd($dir, true, array('progress' => false));
			$output = $this->localExec($cmd);
			if ($output['code'] !== 0 || count($output['output']) == 4) {
				$this->out('Nothing to move');
				continue;
			}
			if ($this->userAccept()) {
				$this->toMove[$dir] = $svc;
			}
		}
		return !empty($this->toMove);
		
	}
	
	public function pre() {
		foreach ($this->toMove as $svc) {
			if (!is_null($svc)) {
				$this->localExec(ServiceHelper::stop($svc));
			}
		}
	}
	
	public function execute() {
		foreach ($this->toMove as $dir => $svc) {
			$this->out('Moving service: '. Color::WHITE($dir));
			$cmd = RsyncHelper::cmd($dir, false);
			$this->localPassthru($cmd);
		}
	}
	
	public function post() {
		foreach ($this->toMove as $svc) {
			if (!is_null($svc)) {
				$this->localExec(ServiceHelper::start($svc));
			}
		}
	}
	
	public function rescue() {
		$this->post();
	}
}
?>
