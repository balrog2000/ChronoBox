<?php
class RsyncHelper {
	public static function cmd($dir, $dry = true, $options = array()) {
		$exclude = '';
		if (!empty($options['exclude'])) {
			foreach ($options['exclude'] as $fName) {
				$exclude .= ' --exclude='.$fName;
			}
		}
		$whole = '';
		if (isset($options['whole']) && $options['whole']) {
			$whole = 'W';
		}
		$cmd = sprintf(
			'rsync --delete-after %s -aPvO%s%siz %s %s',
			$exclude,
			$whole,
			$dry ? 'n' : '',
			escapeshellarg(Configure::get('src_addr').':'.$dir),
			escapeshellarg($dir)
		);
		return $cmd;
	}
}
