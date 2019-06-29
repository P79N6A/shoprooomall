<?php
//SHOPROOO 禁止倒卖 一经发现停止任何服务
function get_order_detection_list()
{
	$no_main_order = ' and (select count(*) from ' . $GLOBALS['ecs']->table('order_info') . ' as oi2 where oi2.main_order_id = o.order_id) = 0 ';
	$confirm_take_time = gmtime();
	$sql = 'SELECT o.order_id, o.user_id, o.order_sn , o.order_status, o.shipping_status, o.pay_status,' . 'o.order_amount, o.goods_amount, o.tax, o.shipping_fee, o.insure_fee, o.pay_fee, o.pack_fee, o.card_fee,' . 'o.bonus, o.integral_money, o.coupons, o.discount, o.money_paid, o.surplus, o.confirm_take_time' . ' FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o' . (' WHERE o.shipping_status = 1 AND (o.shipping_time + o.auto_delivery_time * 24 * 3600) <= \'' . $confirm_take_time . '\' ') . $no_main_order . ' ORDER BY o.add_time DESC ';
	$row = $GLOBALS['db']->getAll($sql);

	if ($row) {
		foreach ($row as $key => $value) {
			$operator = $GLOBALS['_LANG']['system_handle'];
			$note = $GLOBALS['_LANG']['self_motion_goods'];
			order_action($value['order_sn'], $value['order_status'], SS_RECEIVED, $value['pay_status'], $note, $operator, 0, $confirm_take_time);
			$GLOBALS['db']->query('UPDATE ' . $GLOBALS['ecs']->table('order_info') . (' SET confirm_take_time = \'' . $confirm_take_time . '\', ') . 'order_status = \'' . $value['order_status'] . '\', ' . 'shipping_status = \'' . SS_RECEIVED . '\', pay_status = \'' . $value['pay_status'] . '\'' . ' WHERE order_id = \'' . $value['order_id'] . '\'');
			$seller_id = $GLOBALS['db']->getOne('SELECT ru_id FROM ' . $GLOBALS['ecs']->table('order_goods') . ' WHERE order_id = \'' . $value['order_id'] . '\'', true);
			$value_card = $GLOBALS['db']->getOne('SELECT use_val FROM ' . $GLOBALS['ecs']->table('value_card_record') . ' WHERE order_id = \'' . $value['order_id'] . '\'', true);
			$return_amount = get_order_return_amount($value['order_id']);
			$other = array('user_id' => $value['user_id'], 'seller_id' => $seller_id, 'order_id' => $value['order_id'], 'order_sn' => $value['order_sn'], 'order_status' => $value['order_status'], 'shipping_status' => SS_RECEIVED, 'pay_status' => $value['pay_status'], 'order_amount' => $value['total_fee'], 'return_amount' => $return_amount, 'goods_amount' => $value['goods_amount'], 'tax' => $value['tax'], 'shipping_fee' => $value['shipping_fee'], 'insure_fee' => $value['insure_fee'], 'pay_fee' => $value['pay_fee'], 'pack_fee' => $value['pack_fee'], 'card_fee' => $value['card_fee'], 'bonus' => $value['bonus'], 'integral_money' => $value['integral_money'], 'coupons' => $value['coupons'], 'discount' => $value['discount'], 'value_card' => $value_card, 'money_paid' => $value['money_paid'], 'surplus' => $value['surplus'], 'confirm_take_time' => $confirm_take_time);

			if ($seller_id) {
				get_order_bill_log($other);
			}
		}
	}

	return $row;
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';

if ($_REQUEST['detection'] == '1') {
	get_order_detection_list();
}

?>
