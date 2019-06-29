<?php
//SHOPROOO商城资源
namespace App\Api\Transformers;

class ArticleTransformer extends \League\Fractal\TransformerAbstract
{
	public function transform(\App\Models\Article $article)
	{
		return array('id' => $article->article_id, 'title' => $article->title);
	}
}

?>
