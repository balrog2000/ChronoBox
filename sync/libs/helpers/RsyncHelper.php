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
		$progress = 'P';
		if (isset($options['progress']) && !$options['progress']) {
			$progress = '';
		}
		$cmd = sprintf(
			'rsync --delete-after %s -avOi%s%s%s %s %s',
			$exclude,
			$progress,
			$whole,
			$dry ? 'n' : '',
			escapeshellarg(Configure::get('src_addr').':'.$dir),
			escapeshellarg($dir)
		);
		return $cmd;
	}
}
