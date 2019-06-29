<?php
//SHOPROOO商城资源
namespace App\Contracts\Services;

interface BrandServiceInterface
{
	public function create();

	public function get();

	public function update();

	public function delete();

	public function search();
}


?>
