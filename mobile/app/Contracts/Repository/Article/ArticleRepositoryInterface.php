<?php
//SHOPROOO商城资源
namespace App\Contracts\Repository\Article;

interface ArticleRepositoryInterface
{
	public function all($cat_id, $columns, $offset, $requirement);

	public function detail($id);

	public function create($data);

	public function update($data);

	public function delete($id);
}


?>
