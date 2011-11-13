<?php
class HomeCommand extends GeneralCommand {
	private $homeDir = '/home/tomek/';
	private $exclude = array(
		'.config/xfce4/xfconf/xfce-perchannel-xml/displays.xml',
		'.config/compiz/compizconfig/Default.ini',
	);
	private $wasRunning = false;
	private $wmctrl = 'wmctrl -l | grep -v panel | grep -v Pulpit | cut -d" " -f1 | xargs -n 1 wmctrl -i -c';
	
	public function preTest() {
		
		if (getenv('TERM') !== 'linux') {
			if ($this->userAccept('Close local gdm?')) {
				$this->localPassthru($this->wmctrl);
				sleep(5);
				$this->localPassthru('xfce4-session-logout');
				exit;
			}
			throw new FatalCommandException('Cannot run from X windows');
		}
		$status = $this->remoteExec(ServiceHelper::status('gdm3'));
		if (empty($status['output'])) {
			throw new FatalCommandException('cant query remote gdm');
		}
		if (strpos($status['output'][0], 'running') !== false) {
			$this->wasRunning = true;
			if ($this->userAccept('Kill remote gdm?')) {
				$wmctrl = 'DISPLAY=:0.0; export DISPLAY; xauth merge ~tomek/.Xcookie && ('.$this->wmctrl.')';
				$this->remoteExec($wmctrl);
				$this->out('sleep 3');
				sleep(3);
				$this->remoteExec($wmctrl);
				$this->out('sleep 5');
				sleep(5);
				$status = $this->remoteExec(ServiceHelper::stop('gdm3'));
				if ($status['code'] !== 0) {
					throw new FatalCommandException('cant kill remote gdm');
				}
			}
			else {
				throw new FatalCommandException('User refused to kill remote gdm');
			}
		}
		
	}
	
	public function test() {
		$cmd = RsyncHelper::cmd($this->homeDir, true, array('exclude' => $this->exclude));
		passthru($cmd." | less");
		return $this->userAccept();
	}
	
	public function execute() {
		$cmd = RsyncHelper::cmd($this->homeDir, false, array('exclude' => $this->exclude));
		$this->localPassthru($cmd);
	}
	
	public function post() {
		if ($this->wasRunning) {
			$this->remoteExec(ServiceHelper::start('gdm3'));
		}
		$this->localExec(ServiceHelper::start('gdm3'));
	}
	
	public function rescue() {
		$this->post();
	}
}
?>
