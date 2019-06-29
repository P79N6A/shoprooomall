<?php
//SHOPROOO商城资源
class Autoloader
{
	static public function autoload($class)
	{
		$name = $class;

		if (false !== strpos($name, '\\')) {
			$name = strstr($class, '\\', true);
		}

		$filename = TOP_AUTOLOADER_PATH . '/top/' . $name . '.php';

		if (is_file($filename)) {
			include $filename;
			return NULL;
		}

		$filename = TOP_AUTOLOADER_PATH . '/top/request/' . $name . '.php';

		if (is_file($filename)) {
			include $filename;
			return NULL;
		}

		$filename = TOP_AUTOLOADER_PATH . '/top/domain/' . $name . '.php';

		if (is_file($filename)) {
			include $filename;
			return NULL;
		}

		$filename = TOP_AUTOLOADER_PATH . '/aliyun/' . $name . '.php';

		if (is_file($filename)) {
			include $filename;
			return NULL;
		}

		$filename = TOP_AUTOLOADER_PATH . '/aliyun/request/' . $name . '.php';

		if (is_file($filename)) {
			include $filename;
			return NULL;
		}

		$filename = TOP_AUTOLOADER_PATH . '/aliyun/domain/' . $name . '.php';

		if (is_file($filename)) {
			include $filename;
			return NULL;
		}
	}
}

spl_autoload_register('Autoloader::autoload');

?>
