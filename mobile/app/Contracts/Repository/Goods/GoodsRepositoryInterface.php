<?php
//SHOPROOO商城资源
namespace App\Contracts\Repository\Goods;

interface GoodsRepositoryInterface
{
	public function create(array $data);

	public function get($id);

	public function update(array $data);

	public function delete($id);

	public function search(array $data);

	public function sku($id);

	public function skuAdd();
}


?>
