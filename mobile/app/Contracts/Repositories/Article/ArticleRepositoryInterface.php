<?php
//SHOPROOO商城
namespace App\Contracts\Repositories\Article;

interface ArticleRepositoryInterface
{
	public function all($cat_id, $columns, $page, $offset);

	public function detail($id);

	public function create($data);

	public function update($data);

	public function delete($id);
}


?>
