<?php
//SHOPROOO商城资源
namespace App\Contracts\Repositories\Article;

interface CategoryRepositoryInterface
{
	public function all($cat_id, $columns, $offset);

	public function detail($cat_id, $columns);

	public function create(array $data);

	public function update(array $data);

	public function delete($id);
}


?>
