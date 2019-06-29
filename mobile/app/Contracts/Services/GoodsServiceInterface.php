<?php
//SHOPROOO商城资源
namespace App\Contracts\Services;

interface GoodsServiceInterface
{
	public function create();

	public function get();

	public function update();

	public function delete();

	public function listing();

	public function delisting();

	public function search();

	public function getInventory();

	public function getOnsale();
}


?>
