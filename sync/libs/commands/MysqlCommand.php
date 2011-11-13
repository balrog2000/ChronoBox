<?php
class MysqlCommand extends GeneralCommand {
	private $dbDir = '/var/lib/mysql/';
	
	public function preTest() {
		$this->localExec(ServiceHelper::stop('mysql'));
		$this->remoteExec(ServiceHelper::stop('mysql'));
	}
	
	public function test() {
		$cmd = RsyncHelper::cmd($this->dbDir, true, array('whole' => true));
		$this->localExec($cmd);
		return $this->userAccept();
	}
	
	public function execute() {
		$cmd = RsyncHelper::cmd($this->dbDir, false, array('whole' => true));
		$this->localPassthru($cmd);
	}
	
	public function post() {
		$this->localExec(ServiceHelper::start('mysql'));
		$this->localExec(ServiceHelper::restart('memcached'));
		$this->remoteExec(ServiceHelper::start('mysql'));
	}
	
	public function rescue() {
		$this->post();
	}
}
?>
