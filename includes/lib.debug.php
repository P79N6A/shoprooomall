<?php
//SHOPROOO独家更新版 Q群541247070
if (!defined('USE_DEBUGLIB')) {
	define('USE_DEBUGLIB', true);
}

if (USE_DEBUGLIB) {
	$MICROTIME_START = microtime();
	@$GLOBALS_initial_count = count($GLOBALS);
	class Print_a_class
	{	}
}

?>
