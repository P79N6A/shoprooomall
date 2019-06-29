<?php
//SHOPROOO商城资源
namespace App\Api\Transformers;

class BrandTransformer extends \League\Fractal\TransformerAbstract
{
	public function transform(array $map)
	{
		return array('id' => $map['article_id'], 'title' => $map['title']);
	}
}

?>
