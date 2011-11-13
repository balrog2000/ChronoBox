<?php
class ServiceHelper {
	private static $etcinitd = '/etc/init.d/';
	public static function start($svc) {
		return sprintf(self::$etcinitd.'%s start', $svc);
	}
	public static function stop($svc) {
		return sprintf(self::$etcinitd.'%s stop', $svc);
	}
	public static function restart($svc) {
		return sprintf(self::$etcinitd.'%s restart', $svc);
	}
	public static function status($svc) {
		return sprintf(self::$etcinitd.'%s status', $svc);
	}
}
?>
