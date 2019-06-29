<?php
//SHOPROOO商城资源
namespace app\api\v2\shop\transformer;

class ShopTransformer extends \App\Api\Foundation\Transformer
{
	public function transform(array $map)
	{
		return array('id' => $map['article_id'], 'title' => $map['title']);
	}
}

?>
