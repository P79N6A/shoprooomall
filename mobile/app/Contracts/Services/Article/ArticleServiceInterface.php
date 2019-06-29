<?php
//zend SHOPROOO在线更新版  禁止倒卖 一经发现停止任何服务
namespace App\Contracts\Services\Article;

interface ArticleServiceInterface
{
	public function all($id);

	public function show($id);

	public function agreement();

	public function help();
}


?>
