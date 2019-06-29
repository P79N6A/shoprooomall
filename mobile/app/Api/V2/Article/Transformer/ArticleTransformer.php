<?php
//SHOPROOO商城资源
namespace app\api\v2\article\transformer;

class ArticleTransformer extends \app\api\foundation\Transformer
{
	public function transform(array $map)
	{
		return array('id' => $map['article_id'], 'title' => $map['title']);
	}
}

?>
