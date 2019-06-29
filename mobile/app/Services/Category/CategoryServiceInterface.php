<?php
//zend SHOPROOO在线更新版  禁止倒卖 一经发现停止任何服务
namespace App\Contracts\Services;

interface CategoryServiceInterface
{
	public function create();

	public function get();

	public function update();

	public function delete();

	public function search();

	public function category();
}


?>
