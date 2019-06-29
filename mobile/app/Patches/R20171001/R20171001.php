<?php
//SHOPROOO商城资源
namespace App\Patches\R20171001;

class R20171001 implements \App\Patches\Factory\PatchInterface
{
	/**
     * @var array
     */
	private $convert = array();

	public function updateDatabaseOptionally()
	{
		return false;
	}

	public function updateFiles()
	{
		$root_path = ROOT_PATH;
		$list = glob($root_path . 'app/Http/*');

		foreach ($list as $item) {
			$path = $root_path . 'app/Http/' . $item;
			if (is_dir($path) && (basename($item) === 'Proxy')) {
				del_dir($path);
			}
		}

		$list = array('connect', 'integrates', 'payment', 'shipping');

		foreach ($list as $item) {
			$path = $root_path . 'app/Modules/' . $item;

			if (is_dir($path)) {
				del_dir($path);
			}
		}
	}
}

?>
