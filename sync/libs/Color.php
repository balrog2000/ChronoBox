<?php
class Color {
	
	const GREEN 	= "\033[32;1m";
	const RED 		= "\033[31;1m";
	const YELLOW 	= "\033[33;1m";
	const BLUE 		= "\033[34;1m";
	const DARK_MAGENTA	= "\033[35m";
	const MAGENTA	= "\033[35;1m";
	const WHITE		= "\033[37;1m";
	
	const RESTORE 	= "\033[0m";
	
	public static function __callStatic($name, $args) {
		$text = $args[0];
		$constName = strtoupper($name);
		$color = constant('Color::'.$constName);
		if (is_null($color)) {
			return $text;
		}
		return $color.$text.Color::RESTORE;
	}
}
?>
