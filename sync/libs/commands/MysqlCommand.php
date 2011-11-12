<?php
class MysqlCommand extends GeneralCommand {
	public function pre() {
		$this->out('MysqlShutdown');
	}
	
	public function execute() {
		print 'ala';
	}
	
	public function rescue() {
		$this->localExec('/etc/init.d/mysql start');
	}
}
?>
