<?php
//SHOPROOO商城资源
namespace App\Api\Controllers;

class ArticleController extends Controller
{
	public function index()
	{
		$article = \App\Models\Article::all();
		return $this->collection($article, new ArticleTransformer());
	}
}

?>
