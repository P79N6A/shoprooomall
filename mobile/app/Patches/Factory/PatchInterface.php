<?php
//SHOPROOO商城资源
namespace App\Patches\Factory;

interface PatchInterface
{
	public function updateDatabaseOptionally();

	public function updateFiles();
}


?>
