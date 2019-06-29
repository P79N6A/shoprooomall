<?php
//SHOPROOO商城资源
namespace app\api\v2\wx\transformer;

class GoodsTransformer extends \app\api\foundation\Transformer
{
	public function transform(array $map)
	{
		return array('goods_id' => $map['goods_id'], 'goods_name' => $map['goods_name'], 'shop_price' => $map['shop_price'], 'goods_thumb' => __HOST__ . $map['goods_thumb'], 'goods_sales' => $map['sales_volume'], 'market_price' => $map['market_price'], 'goods_stock' => $map['goods_number']);
	}
}

?>
