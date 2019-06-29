<?php
//zend by SHOPROOO商城资源
namespace App\Modules\Api\Transformers;

class ArticleTransformer
{
	public function transform(\App\Models\Article $article)
	{
		return array('id' => $article->article_id, 'title' => $article->title);
	}
}


?>
