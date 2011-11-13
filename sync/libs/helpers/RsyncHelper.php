<?php
class RsyncHelper {
	public static function cmd($dir, $dry = true) {
		$cmd = sprintf(
			'rsync -avOW%siz %s %s',
			$dry ? 'n' : '',
			escapeshellarg(Configure::get('src_addr').':'.$dir),
			escapeshellarg($dir)
		);
		return $cmd;
	}
}
