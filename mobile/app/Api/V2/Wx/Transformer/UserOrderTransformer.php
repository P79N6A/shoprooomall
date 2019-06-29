<?php
//SHOPROOO商城资源
namespace app\api\v2\wx\transformer;

class UserOrderTransformer extends \app\api\foundation\Transformer
{
	public function transform(array $map)
	{
		return array('order_id' => $map['order_id'], 'order_sn' => $map['order_sn'], 'user_id' => $map['user_id'], 'order_status' => $map['order_status'], 'shipping_status' => $map['shipping_status'], 'pay_status' => $map['pay_status'], 'consignee' => $map['consignee'], 'country' => $map['country'], 'province' => $map['province'], 'city' => $map['city'], 'district' => $map['district'], 'street' => $map['street'], 'address' => $map['address'], 'mobile' => $map['mobile'], 'shipping_id' => $map['shipping_id'], 'shipping_name' => $map['shipping_name'], 'pay_id' => $map['pay_id'], 'pay_name' => $map['pay_name']);
	}
}

?>
