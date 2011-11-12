<?php
class Configure {
	private static $opts = array();
	
	public static function parseOpts() {
		$options = getopt('s:h:q::');
		if (!isset($options['h']) || !isset($options['s'])) {
			throw new Exception("Usage: ./sync.php -s src_addr -h src_hostname [-q queue]\n");
		}
		$config = array(
			'src_addr' 		=> $options['s'],
			'src_hostname'	=> $options['h'],
			'queue'			=> isset($options['q']) ? $options['q'] : false
		);	
		if (!$config['queue']) {
			$config['queue'] = 'SynchronizeQueue';
		}
		foreach($config as $key => $val) {
			self::set($key, $val);
		}
	}
	
	public static function get($key) {
		if (empty(self::$opts)) {
			throw new Exception('Configure not initialized');
		}
		if (!array_key_exists($key, self::$opts)) {
			throw new Exception('Attempt to get undefined key');
		}
		return self::$opts[$key];
	}
	
	public static function set($key, $val) {
		self::$opts[$key] = $val;
	}
}