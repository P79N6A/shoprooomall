<?php
//SHOPROOO
function shipping_list()
{
	$sql = 'SELECT shipping_id, shipping_name ' . 'FROM ' . $GLOBALS['ecs']->table('shipping') . ' WHERE enabled = 1';
	return $GLOBALS['db']->getAll($sql);
}

function shipping_info($shipping_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('shipping') . (' WHERE shipping_id = \'' . $shipping_id . '\' ') . 'AND enabled = 1';
	return $GLOBALS['db']->getRow($sql);
}

function shipping_area_info($shipping_id, $region = '')
{
	$sql = 'SELECT s.shipping_code, s.shipping_name, ' . 's.shipping_desc, s.insure, s.support_cod ' . 'FROM ' . $GLOBALS['ecs']->table('shipping') . ' AS s ' . ('WHERE s.shipping_id = \'' . $shipping_id . '\' AND s.enabled = 1');
	$row = $GLOBALS['db']->getRow($sql);
	return $row;
}

function shipping_insure_fee($shipping_code, $goods_amount, $insure)
{
	if (strpos($insure, '%') === false) {
		return floatval($insure);
	}
	else {
		$path = ADDONS_PATH . 'shipping/' . $shipping_code . '.php';

		if (file_exists($path)) {
			include_once $path;
			$shipping = new $shipping_code();
			$insure = floatval($insure) / 100;

			if (method_exists($shipping, 'calculate_insure')) {
				return $shipping->calculate_insure($goods_amount, $insure);
			}
			else {
				return ceil($goods_amount * $insure);
			}
		}
		else {
			return false;
		}
	}
}

function payment_list()
{
	$sql = 'SELECT pay_id, pay_name ' . 'FROM ' . $GLOBALS['ecs']->table('payment') . ' WHERE enabled = 1';
	return $GLOBALS['db']->getAll($sql);
}

function payment_info($pay_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('payment') . (' WHERE pay_id = \'' . $pay_id . '\' AND enabled = 1');
	return $GLOBALS['db']->getRow($sql);
}

function pay_fee($payment_id, $order_amount, $cod_fee = NULL)
{
	$pay_fee = 0;
	$payment = payment_info($payment_id);
	$rate = $payment['is_cod'] && !is_null($cod_fee) ? $cod_fee : $payment['pay_fee'];

	if (strpos($rate, '%') !== false) {
		$val = floatval($rate) / 100;
		$pay_fee = 0 < $val ? $order_amount * $val / (1 - $val) : 0;
	}
	else {
		$pay_fee = floatval($rate);
	}

	return round($pay_fee, 2);
}

function available_payment_list($support_cod, $cod_fee = 0, $is_online = false)
{
	$sql = 'SELECT pay_id, pay_code, pay_name, pay_fee,is_online,pay_desc, pay_config, is_cod' . ' FROM ' . $GLOBALS['ecs']->table('payment') . ' WHERE enabled = 1 ';

	if (!$support_cod) {
		$sql .= 'AND is_cod = 0 ';
	}

	if ($is_online) {
		$sql .= 'AND is_online = \'1\' ';
	}

	$sql .= 'ORDER BY pay_order ASC';
	$res = $GLOBALS['db']->query($sql);
	$pay_list = array();

	foreach ($res as $row) {
		if ($row['is_cod'] == '1') {
			$row['pay_fee'] = $cod_fee;
		}

		$row['format_pay_fee'] = strpos($row['pay_fee'], '%') !== false ? $row['pay_fee'] : price_format($row['pay_fee'], false);
		$modules[] = $row;
	}

	if (isset($modules)) {
		return $modules;
	}
}

function pack_list()
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('pack');
	$res = $GLOBALS['db']->query($sql);
	$list = array();

	foreach ($res as $row) {
		$row['format_pack_fee'] = price_format($row['pack_fee'], false);
		$row['format_free_money'] = price_format($row['free_money'], false);
		$list[] = $row;
	}

	return $list;
}

function pack_info($pack_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('pack') . (' WHERE pack_id = \'' . $pack_id . '\'');
	return $GLOBALS['db']->getRow($sql);
}

function pack_fee($pack_id, $goods_amount)
{
	$pack = pack_info($pack_id);
	$val = floatval($pack['free_money']) <= $goods_amount && 0 < $pack['free_money'] ? 0 : floatval($pack['pack_fee']);
	return $val;
}

function card_list()
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('card');
	$res = $GLOBALS['db']->query($sql);
	$list = array();

	foreach ($res as $row) {
		$row['format_card_fee'] = price_format($row['card_fee'], false);
		$row['format_free_money'] = price_format($row['free_money'], false);
		$list[] = $row;
	}

	return $list;
}

function card_info($card_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('card') . (' WHERE card_id = \'' . $card_id . '\'');
	return $GLOBALS['db']->getRow($sql);
}

function card_fee($card_id, $goods_amount)
{
	$card = card_info($card_id);
	return $card['free_money'] <= $goods_amount && 0 < $card['free_money'] ? 0 : $card['card_fee'];
}

function order_info($order_id, $order_sn = '')
{
	$total_fee = ' (o.goods_amount - o.discount + o.tax + o.shipping_fee + o.insure_fee + o.pay_fee + o.pack_fee + o.card_fee) AS total_fee ';
	$order_id = intval($order_id);

	if (0 < $order_id) {
		$sql = 'SELECT o.*,p.pay_code, r.use_val, ' . $total_fee . ' FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o LEFT JOIN ' . $GLOBALS['ecs']->table('payment') . ' AS p ON o.pay_id=p.pay_id ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('value_card_record') . ' AS r ON r.order_id = o.order_id ' . (' WHERE o.order_id = \'' . $order_id . '\'');
	}
	else {
		$sql = 'SELECT o.*, p.pay_code, r.use_val, ' . $total_fee . ' from ' . $GLOBALS['ecs']->table('order_info') . ' as o LEFT JOIN ' . $GLOBALS['ecs']->table('payment') . ' AS p ON o.pay_id=p.pay_id ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('value_card_record') . ' AS r ON r.order_id = o.order_id ' . (' WHERE o.order_sn = \'' . $order_sn . '\'');
	}

	$order = $GLOBALS['db']->getRow($sql);

	if ($order['cost_amount'] <= 0) {
		$order['cost_amount'] = goods_cost_price($order['order_id']);
	}

	$os = L('os');

	if ($order) {
		$order['formated_goods_amount'] = price_format($order['goods_amount'], false);
		$order['formated_discount'] = price_format($order['discount'], false);
		$order['formated_tax'] = price_format($order['tax'], false);
		$order['formated_shipping_fee'] = price_format($order['shipping_fee'], false);
		$order['formated_insure_fee'] = price_format($order['insure_fee'], false);
		$order['formated_pay_fee'] = price_format($order['pay_fee'], false);
		$order['formated_pack_fee'] = price_format($order['pack_fee'], false);
		$order['formated_card_fee'] = price_format($order['card_fee'], false);
		$order['formated_total_fee'] = price_format($order['total_fee'], false);
		$order['formated_money_paid'] = price_format($order['money_paid'], false);
		$order['formated_bonus'] = price_format($order['bonus'], false);
		$order['formated_integral_money'] = price_format($order['integral_money'], false);
		$order['formated_surplus'] = price_format($order['surplus'], false);
		$order['formated_value_card'] = price_format($order['use_val'], false);
		$order['formated_order_amount'] = price_format(abs($order['order_amount']), false);
		$order['formated_add_time'] = local_date($GLOBALS['_CFG']['time_format'], $order['add_time']);
	}

	return $order;
}

function order_finished($order)
{
	return $order['order_status'] == OS_CONFIRMED && ($order['shipping_status'] == SS_SHIPPED || $order['shipping_status'] == SS_RECEIVED) && ($order['pay_status'] == PS_PAYED || $order['pay_status'] == PS_PAYING);
}

function order_goods($order_id)
{
	$sql = 'SELECT og.*, (og.goods_price * og.goods_number) AS subtotal,g.shop_price, g.is_shipping, g.goods_weight AS goodsweight, g.goods_img, g.goods_thumb, ' . 'g.goods_cause, g.is_shipping FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS og ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON og.goods_id = g.goods_id ' . (' WHERE og.order_id = \'' . $order_id . '\' ORDER BY og.parent_id, og.rec_id DESC');
	$res = $GLOBALS['db']->query($sql);
	$goods_list = array();

	foreach ($res as $row) {
		$row['goods_thumb'] = get_image_path($row['goods_thumb']);

		if ($row['extension_code'] == 'package_buy') {
			$row['package_goods_list'] = get_package_goods($row['goods_id']);
			$sql = 'SELECT activity_thumb FROM {pre}goods_activity WHERE act_id =' . $row['goods_id'] . ' and is_finished = 0 and review_status = 3';
			$activity_thumb = $GLOBALS['db']->getRow($sql);
			$row['goods_thumb'] = get_image_path($activity_thumb['activity_thumb']);
		}

		$row['goods_img'] = get_image_path($row['goods_img']);
		$row['warehouse_name'] = $GLOBALS['db']->getOne('SELECT region_name FROM ' . $GLOBALS['ecs']->table('region_warehouse') . ' WHERE region_id = \'' . $row['warehouse_id'] . '\'');
		$row['goods_amount'] = $row['goods_price'] * $row['goods_number'];
		$goods_con = get_con_goods_amount($row['goods_amount'], $row['goods_id'], 0, 0, $row['parent_id']);
		$goods_con['amount'] = explode(',', $goods_con['amount']);
		$row['amount'] = min($goods_con['amount']);
		$row['dis_amount'] = $row['goods_amount'] - $row['amount'];
		$row['dis_amount_formated'] = price_format($row['dis_amount'], false);
		$row['goods_price_format'] = price_format($row['goods_price']);
		$extension_id = $GLOBALS['db']->getOne('SELECT extension_id FROM ' . $GLOBALS['ecs']->table('order_info') . (' WHERE order_id = \'' . $order_id . '\''));
		if ($row['extension_code'] == 'presale' && !empty($extension_id)) {
			$row['url'] = url('presale/index/detail', array('id' => $extension_id));
		}
		else if ($row['extension_code'] == 'package_buy') {
			$row['url'] = url('package/index/index');
		}
		else {
			$row['url'] = url('goods/index/index', array('id' => $row['goods_id']));
		}

		$row['shop_name'] = get_shop_name($row['ru_id'], 1);
		$row['shop_url'] = url('store/index/shop_info', array('id' => $row['ru_id']));
		$goods_list[] = $row;
	}

	return $goods_list;
}

function order_amount($order_id, $include_gift = true)
{
	$sql = 'SELECT SUM(goods_price * goods_number) ' . 'FROM ' . $GLOBALS['ecs']->table('order_goods') . (' WHERE order_id = \'' . $order_id . '\'');

	if (!$include_gift) {
		$sql .= ' AND is_gift = 0';
	}

	return floatval($GLOBALS['db']->getOne($sql));
}

function order_weight_price($order_id)
{
	$sql = 'SELECT SUM(g.goods_weight * o.goods_number) AS weight, ' . 'SUM(o.goods_price * o.goods_number) AS amount ,' . 'SUM(o.goods_number) AS number ' . 'FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS o, ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . ('WHERE o.order_id = \'' . $order_id . '\' ') . 'AND o.goods_id = g.goods_id';
	$row = $GLOBALS['db']->getRow($sql);
	$row['weight'] = floatval($row['weight']);
	$row['amount'] = floatval($row['amount']);
	$row['number'] = intval($row['number']);
	$row['formated_weight'] = formated_weight($row['weight']);
	return $row;
}

function get_new_group_cart_goods($cart_goods_list_new)
{
	$car_goods = array();

	foreach ($cart_goods_list_new as $key => $goods) {
		foreach ($goods['goods_list'] as $k => $list) {
			$car_goods[] = $list;
		}
	}

	return $car_goods;
}

function order_fee($order, $goods, $consignee, $type = 0, $cart_value = '', $pay_type = 0, $cart_goods_list = '', $warehouse_id = 0, $area_id = 0, $store_id = 0, $store_type = '')
{
	$step = '';
	$shipping_list = array();

	if (is_array($type)) {
		$step = $type['step'];
		$shipping_list = $type['shipping_list'];
		$type = $type['type'];
	}

	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
		$c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
		$c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	if (!isset($order['extension_code'])) {
		$order['extension_code'] = '';
	}

	if ($order['extension_code'] == 'group_buy') {
		$group_buy = group_buy_info($order['extension_id']);
	}

	if ($order['extension_code'] == 'presale') {
		$presale = presale_info($order['extension_id']);
	}

	$total = array('real_goods_count' => 0, 'gift_amount' => 0, 'goods_price' => 0, 'market_price' => 0, 'discount' => 0, 'pack_fee' => 0, 'card_fee' => 0, 'shipping_fee' => 0, 'shipping_insure' => 0, 'integral_money' => 0, 'bonus' => 0, 'coupons' => 0, 'value_card' => 0, 'surplus' => 0, 'cod_fee' => 0, 'pay_fee' => 0, 'tax' => 0, 'dis_amount' => 0, 'goods_price_formated' => 0, 'presale_price' => 0);
	$weight = 0;
	$arr = array();

	foreach ($goods as $key => $val) {
		if ($val['is_real']) {
			$total['real_goods_count']++;
		}

		$arr[$key]['goods_amount'] = $val['goods_price'] * $val['goods_number'];
		$total['goods_price_formated'] += $arr[$key]['goods_amount'];
		$goods_con = get_con_goods_amount($arr[$key]['goods_amount'], $val['goods_id'], 0, 0, $val['parent_id']);
		$goods_con['amount'] = explode(',', $goods_con['amount']);
		$arr[$key]['amount'] = min($goods_con['amount']);
		$total['goods_price'] += $arr[$key]['amount'];
		if (isset($val['deposit']) && 0 < $val['deposit'] && $val['rec_type'] == CART_PRESALE_GOODS) {
			$total['presale_price'] += $val['deposit'] * $val['goods_number'];
		}

		$total['market_price'] += $val['market_price'] * $val['goods_number'];
		$total['dis_amount'] += $val['dis_amount'];
	}

	$total['saving'] = $total['market_price'] - $total['goods_price'];
	$total['save_rate'] = $total['market_price'] ? round($total['saving'] * 100 / $total['market_price']) . '%' : 0;
	$total['goods_price_formated'] = price_format($total['goods_price_formated'], false);
	$total['market_price_formated'] = price_format($total['market_price'], false);
	$total['saving_formated'] = price_format($total['saving'], false);
	$total['dis_amount_formated'] = price_format($total['dis_amount'], false);
	if ($order['extension_code'] != 'group_buy' && $order['extension_code'] != 'bargain_buy' && $order['extension_code'] != 'team_buy') {
		$discount = compute_discount(3, $cart_value, 0, 0, $_SESSION['flow_type']);
		$total['discount'] = $discount['discount'];

		if ($total['goods_price'] < $total['discount']) {
			$total['discount'] = $total['goods_price'];
		}
	}

	$bonus_amount = $total['discount'];
	$total['discount_formated'] = price_format($total['discount'], false);

	if ($GLOBALS['_CFG']['can_invoice'] == 1) {
		$total['tax'] = get_order_invoice_total($total['goods_price'], $order['inv_content']);
	}
	else {
		$total['tax'] = 0;
	}

	$total['tax_formated'] = price_format($total['tax'], false);

	if (!empty($order['pack_id'])) {
		$total['pack_fee'] = pack_fee($order['pack_id'], $total['goods_price']);
	}

	$total['pack_fee_formated'] = price_format($total['pack_fee'], false);

	if (!empty($order['card_id'])) {
		$total['card_fee'] = card_fee($order['card_id'], $total['goods_price']);
	}

	$total['card_fee_formated'] = price_format($total['card_fee'], false);

	if (!empty($order['bonus_id'])) {
		$bonus = bonus_info($order['bonus_id']);
		$total['bonus'] = $bonus['type_money'];
		$total['admin_id'] = $bonus['admin_id'];
	}

	$total['bonus_formated'] = price_format($total['bonus'], false);

	if (!empty($order['bonus_kill'])) {
		$bonus = bonus_info(0, $order['bonus_kill']);
		$total['bonus_kill'] = $order['bonus_kill'];
		$total['bonus_kill_formated'] = price_format($total['bonus_kill'], false);
	}

	$coupons = array();
	$total['coupons'] = 0;
	if (isset($order['uc_id']) && !empty($order['uc_id'])) {
		$coupons = get_coupons($order['uc_id'], array('c.cou_id', 'c.cou_man', 'c.cou_type', 'c.ru_id', 'c.cou_money', 'cu.uc_id'));
		$coupons['cou_money'] = 0 < $coupons['uc_money'] ? $coupons['uc_money'] : $coupons['cou_money'];
		$total['cou_type'] = $coupons['cou_type'];
	}

	if (!empty($coupons) && $coupons['cou_type'] != 5) {
		$total['coupons'] = $coupons['cou_money'];
	}

	$total['coupons_formated'] = price_format($total['coupons'], false);

	if (!empty($order['vc_id'])) {
		$value_card = value_card_info($order['vc_id']);
		$total['value_card'] = $value_card['card_money'];
		$total['card_dis'] = $value_card['vc_dis'] < 1 ? $value_card['vc_dis'] * 10 : '';
		$total['vc_dis'] = $value_card['vc_dis'] ? $value_card['vc_dis'] : 1;
	}

	$shipping_cod_fee = NULL;
	if (0 < $store_id || $store_type) {
		$total['shipping_fee'] = 0;
	}
	else {
		$total['shipping_fee'] = get_order_shipping_fee($cart_goods_list, $consignee, $cart_value, $shipping_list, $step, $coupons);
		if (!empty($coupons) && $coupons['cou_type'] == 5 && $total['shipping_fee'] == 0) {
			$total['shipping_fee_old'] = get_order_shipping_fee($cart_goods_list, $consignee, $cart_value, $shipping_list, $step);
			$total['shipping_fee_old_formated'] = price_format($total['shipping_fee_old'], false);
		}
	}

	$total['shipping_fee_formated'] = price_format($total['shipping_fee'], false);
	$total['shipping_insure_formated'] = price_format($total['shipping_insure'], false);
	$max_amount = $total['goods_price'] == 0 ? $total['goods_price'] : $total['goods_price'] - $bonus_amount;
	if ($order['extension_code'] == 'group_buy' && 0 < $group_buy['deposit']) {
		$total['amount'] = $total['goods_price'] + $total['shipping_fee'];
	}
	else {
		if ($order['extension_code'] == 'presale' && 0 < $presale['deposit']) {
			$total['amount'] = $total['presale_price'] + $total['shipping_fee'];
		}
		else if ($order['extension_code'] == 'team_buy') {
			$total['amount'] = $total['goods_price'] + $total['shipping_fee'] + $total['shipping_insure'] + $total['tax'];
		}
		else {
			if (!empty($order['vc_id']) && 0 < $total['value_card']) {
				$total['amount'] = ($total['goods_price'] - $total['discount'] + $total['tax'] + $total['pack_fee'] + $total['card_fee']) * $total['vc_dis'] + $total['shipping_fee'] + $total['shipping_insure'] + $total['cod_fee'];
			}
			else {
				$total['amount'] = $total['goods_price'] - $total['discount'] + $total['tax'] + $total['pack_fee'] + $total['card_fee'] + $total['shipping_fee'] + $total['shipping_insure'] + $total['cod_fee'];
			}

			$use_bonus = min($total['bonus'], $max_amount);
			$use_coupons = $total['coupons'];
			$use_value_card = 0;
			if (!empty($order['vc_id']) && 0 < $total['value_card']) {
				$value1 = $total['value_card'];
				$value2 = ($max_amount - $use_bonus - $use_coupons) * $total['vc_dis'] + $total['shipping_insure'] + $total['cod_fee'];
				$use_value_card = min($value1, $value2);
				$total['value_card_formated'] = price_format($use_value_card, false);
				$total['use_value_card'] = $use_value_card;
			}

			if (isset($total['bonus_kill'])) {
				$use_bonus_kill = min($total['bonus_kill'], $max_amount);
				$total['amount'] -= $price = number_format($total['bonus_kill'], 2, '.', '');
			}

			$total['bonus'] = $use_bonus;
			$total['bonus_formated'] = price_format($total['bonus'], false);
			$total['coupons'] = $use_coupons;
			$total['coupons_formated'] = price_format($total['coupons'], false);
			$total['amount'] -= $use_bonus + $use_coupons + $use_value_card;
			$max_amount -= $use_bonus + $use_coupons + $use_value_card;
		}
	}

	$order['surplus'] = 0 < $order['surplus'] ? $order['surplus'] : 0;

	if (0 < $total['amount']) {
		if (isset($order['surplus']) && $total['amount'] < $order['surplus']) {
			$order['surplus'] = $total['amount'];
			$total['amount'] = 0;
		}
		else {
			$total['amount'] -= floatval($order['surplus']);
		}
	}
	else {
		$order['surplus'] = 0;
		$total['amount'] = 0;
	}

	$total['surplus'] = $order['surplus'];
	$total['surplus_formated'] = price_format($order['surplus'], false);
	$order['integral'] = 0 < $order['integral'] ? $order['integral'] : 0;
	if (0 < $total['amount'] && 0 < $max_amount && 0 < $order['integral']) {
		$integral_money = value_of_integral($order['integral']);
		$use_integral = min($total['amount'], $max_amount, $integral_money);
		$total['amount'] -= $use_integral;
		$total['integral_money'] = $use_integral;
		$order['integral'] = integral_of_value($use_integral);
	}
	else {
		$total['integral_money'] = 0;
		$order['integral'] = 0;
	}

	$total['integral'] = $order['integral'];
	$total['integral_formated'] = price_format($total['integral_money'], false);
	$_SESSION['flow_order'] = $order;
	$se_flow_type = isset($_SESSION['flow_type']) ? $_SESSION['flow_type'] : '';
	if (!empty($order['pay_id']) && (0 < $total['real_goods_count'] || $se_flow_type != CART_EXCHANGE_GOODS)) {
		$total['pay_fee'] = pay_fee($order['pay_id'], $total['amount'], $shipping_cod_fee);
	}

	$total['pay_fee_formated'] = price_format($total['pay_fee'], false);
	$total['amount'] += $total['pay_fee'];
	$total['amount_formated'] = price_format($total['amount'], false);

	if ($order['extension_code'] == 'group_buy') {
		$total['will_get_integral'] = $group_buy['gift_integral'];
	}
	else if ($order['extension_code'] == 'exchange_goods') {
		$total['will_get_integral'] = 0;
	}
	else {
		$total['will_get_integral'] = get_give_integral($goods, $cart_value, $warehouse_id, $area_id);
	}

	$total['will_get_bonus'] = $order['extension_code'] == 'exchange_goods' ? 0 : price_format(get_total_bonus(), false);
	$total['formated_goods_price'] = price_format($total['goods_price'], false);
	$total['formated_market_price'] = price_format($total['market_price'], false);
	$total['formated_saving'] = price_format($total['saving'], false);

	if ($order['extension_code'] == 'exchange_goods') {
		$sql = 'SELECT SUM(eg.exchange_integral * c.goods_number) ' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c,' . $GLOBALS['ecs']->table('exchange_goods') . 'AS eg ' . 'WHERE c.goods_id = eg.goods_id AND ' . $c_sess . '  AND c.rec_type = \'' . CART_EXCHANGE_GOODS . '\' ' . '  AND c.is_gift = 0 AND c.goods_id > 0 AND eg.review_status = 3' . ' GROUP BY eg.goods_id';
		$exchange_integral = $GLOBALS['db']->getOne($sql);
		$total['exchange_integral'] = $exchange_integral;
	}

	return $total;
}

function get_order_invoice_total($goods_price, $inv_content)
{
	$tax = 0;
	$invoice = get_invoice_list($GLOBALS['_CFG']['invoice_type'], 1, $inv_content);
	$rate = floatval($invoice['rate']) / 100;

	if (0 < $rate) {
		$tax = $rate * $goods_price;
	}

	return $tax;
}

function get_order_shipping_fee($cart_goods, $consignee = '', $cart_value = '', $shipping_list = '', $step = '', $coupons = '')
{
	$step_array = array('insert_Consignee');
	$shipping_fee = 0;

	if ($cart_goods) {
		$shipping_list = !empty($shipping_list) && !is_array($shipping_list) ? explode(',', $shipping_list) : '';

		if (empty($shipping_list)) {
			foreach ($cart_goods as $key => $row) {
				$shipping = isset($row['shipping']) ? $row['shipping'] : array();

				if ($shipping) {
					if (!empty($step) && in_array($step, $step_array)) {
						$str_shipping = '';

						foreach ($shipping as $skey => $srow) {
							$str_shipping .= $srow['shipping_id'] . ',';
						}

						$str_shipping = get_del_str_comma($str_shipping);
						$str_shipping = explode(',', $str_shipping);
						if (isset($row['tmp_shipping_id']) && $row['tmp_shipping_id'] && in_array($row['tmp_shipping_id'], $str_shipping)) {
							$have_shipping = 1;
						}
						else {
							$have_shipping = 0;
						}
					}

					foreach ($shipping as $kk => $vv) {
						if (!empty($step) && in_array($step, $step_array)) {
							if ($have_shipping == 0) {
								if ($vv['default'] == 1) {
									$row['tmp_shipping_id'] = $vv['shipping_id'];
								}
								else if ($kk == 0) {
									$row['tmp_shipping_id'] = $vv['shipping_id'];
								}
							}
							else if ($vv['default'] == 1) {
								if ($row['tmp_shipping_id'] != $vv['shipping_id']) {
									$row['tmp_shipping_id'] = $vv['shipping_id'];
								}
							}
						}

						if (!empty($coupons) && $row['ru_id'] == $coupons['ru_id']) {
							if ($coupons['cou_type'] == 5) {
								if ($coupons['cou_man'] <= $row['goods_amount'] || $coupons['cou_man'] == 0) {
									$cou_region = get_coupons_region($coupons['cou_id']);
									$cou_region = !empty($cou_region) ? explode(',', $cou_region) : array();

									if ($cou_region) {
										if (!in_array($consignee['province'], $cou_region)) {
											$vv['shipping_fee'] = 0;
										}
									}
									else {
										$vv['shipping_fee'] = 0;
									}
								}
							}
						}

						if (isset($row['tmp_shipping_id'])) {
							if (isset($vv['shipping_id'])) {
								if ($row['tmp_shipping_id'] == $vv['shipping_id']) {
									if (isset($row['shipping_code']) && $row['shipping_code'] == 'cac') {
										$vv['shipping_fee'] = 0;
									}

									$shipping_fee += $vv['shipping_fee'];
								}
							}
						}
						else if ($vv['default'] == 1) {
							if ($row['shipping_code'] == 'cac') {
								$vv['shipping_fee'] = 0;
							}

							$shipping_fee += $vv['shipping_fee'];
						}
					}
				}
			}
		}
		else {
			foreach ($cart_goods as $key => $row) {
				if ($row['shipping']) {
					foreach ($row['shipping'] as $skey => $srow) {
						if ($shipping_list[$key] == $srow['shipping_id'] && $srow['shipping_code'] != 'cac') {
							$shipping_fee += $srow['shipping_fee'];
						}
					}
				}
			}
		}
	}

	return $shipping_fee;
}

function update_order($order_id, $order)
{
	return $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $order, 'UPDATE', 'order_id = \'' . $order_id . '\'');
}

function get_order_sn()
{
	$time = explode(' ', microtime());
	$time = $time[1] . $time[0] * 1000;
	$time = explode('.', $time);
	$time = isset($time[1]) ? $time[1] : 0;
	$time = date('YmdHis') + $time;
	mt_srand((double) microtime() * 1000000);
	return $time . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

function cart_goods($type = CART_GENERAL_GOODS, $cart_value = '', $ru_type = 0, $warehouse_id = 0, $area_id = 0, $area_city = 0, $consignee = '', $store_id = 0)
{
	$rec_txt = array('普通', '团购', '拍卖', '夺宝奇兵', '积分商城', '预售', '秒杀');
	$where = '1';
	if (isset($store_id) && 0 < $store_id) {
		$where .= ' AND c.store_id = \'' . $store_id . '\' ';
	}

	$goods_where = ' AND g.is_delete = 0 ';

	if ($type == CART_PRESALE_GOODS) {
		$goods_where .= ' AND g.is_on_sale = 0 ';
	}

	if (!empty($_SESSION['user_id'])) {
		$c_sess = ' AND c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$c_sess = ' AND c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$goodsIn = '';

	if (!empty($cart_value)) {
		$goodsIn = ' AND c.rec_id in (' . $cart_value . ')';
	}

	$where_area = '';

	if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
		$where_area = ' AND c.area_city = \'' . $area_city . '\'';
	}

	$sql = 'SELECT c.warehouse_id, c.area_id, c.area_city, c.rec_id, c.user_id, c.goods_id, g.user_id as ru_id, g.cat_id, c.goods_name, g.goods_thumb, c.goods_sn, c.goods_number, g.default_shipping, g.goods_weight as goodsweight, ' . 'c.market_price, c.goods_price, c.goods_attr, c.is_real, c.extension_code, c.parent_id, c.is_gift, c.rec_type, ' . 'c.goods_price * c.goods_number AS subtotal, c.goods_attr_id, c.goods_number, c.stages_qishu, ' . 'c.parent_id, c.group_id, pa.deposit, g.is_shipping, g.freight, g.tid, g.shipping_fee, c.store_id, g.brand_id,g.cloud_id,g.cloud_goodsname ' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON c.goods_id = g.goods_id ' . $goods_where . 'LEFT JOIN ' . $GLOBALS['ecs']->table('presale_activity') . ' AS pa ON pa.goods_id = g.goods_id AND pa.review_status = 3 ' . ('WHERE ' . $where . ' ') . $c_sess . ('AND c.rec_type = \'' . $type . '\'') . $goodsIn . ' AND c.is_checked = 1 AND c.extension_code <> \'package_buy\' ORDER BY c.parent_id ASC, c.rec_id DESC';
	$arr = $GLOBALS['db']->getAll($sql);
	$sql = 'SELECT c.warehouse_id, c.rec_id, c.area_city, c.user_id, c.goods_id, c.ru_id, c.goods_name, c.goods_sn, c.goods_number, ' . 'c.market_price, c.goods_price, c.goods_attr, c.is_real, c.extension_code, c.parent_id, c.is_gift, c.rec_type, ' . 'c.goods_price * c.goods_number AS subtotal, c.goods_attr_id, c.goods_number, c.stages_qishu,' . ' c.parent_id, c.group_id, g.is_shipping, g.freight, g.tid, g.shipping_fee, g.brand_id,g.cloud_id, g.cloud_goodsname ' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON c.goods_id = g.goods_id ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('goods_activity') . ' AS ga ON c.goods_id = ga.act_id ' . ('WHERE ' . $where . ' ') . $c_sess . ('AND c.rec_type = \'' . $type . '\'') . $goodsIn . ' AND c.extension_code = \'package_buy\' AND ga.review_status = 3 order by c.rec_id DESC';
	$arr2 = $GLOBALS['db']->getAll($sql);
	$arr = array_merge($arr, $arr2);

	if ($GLOBALS['_CFG']['add_shop_price'] == 1) {
		$add_tocart = 1;
	}
	else {
		$add_tocart = 0;
	}

	foreach ($arr as $key => $value) {
		$currency_format = !empty($GLOBALS['_CFG']['currency_format']) ? explode('%', $GLOBALS['_CFG']['currency_format']) : '';
		$attr_id = !empty($value['goods_attr_id']) ? explode(',', $value['goods_attr_id']) : '';

		if ($value['extension_code'] !== 'package_buy') {
			if (1 < count($currency_format)) {
				if ($value['parent_id'] == 0) {
					$goods_price = trim(get_final_price($value['goods_id'], $value['goods_number'], true, $attr_id, $value['warehouse_id'], $value['area_id'], $value['area_city'], 0, 0, $add_tocart), $currency_format[0]);
				}

				$cart_price = trim($value['goods_price'], $currency_format[0]);
			}
			else {
				$goods_price = get_final_price($value['goods_id'], $value['goods_number'], true, $attr_id, $value['warehouse_id'], $value['area_id'], $value['area_city'], 0, 0, $add_tocart);
				$cart_price = $value['goods_price'];
			}
		}

		$goods_price = floatval($goods_price);
		$cart_price = floatval($cart_price);
		if ($goods_price != $cart_price && empty($value['is_gift']) && empty($value['group_id'])) {
			$value['price_is_invalid'] = 1;
		}
		else {
			$value['price_is_invalid'] = 0;
		}

		if ($value['price_is_invalid'] && $value['rec_type'] == 0 && empty($value['is_gift']) && $value['extension_code'] != 'package_buy') {
			if (isset($_SESSION['flow_type']) && $_SESSION['flow_type'] == 0 && 0 < $goods_price) {
				get_update_cart_price($goods_price, $value['rec_id']);
				$value['goods_price'] = $goods_price;
			}
		}

		if ($value['is_invalid'] == 1) {
			dao('cart')->data(array('is_checked' => 0))->where(array('rec_id' => $value['rec_id']))->save();
		}

		$arr[$key]['formated_goods_price'] = price_format($value['goods_price'], false);
		$arr[$key]['formated_subtotal'] = price_format($arr[$key]['subtotal'], false);

		if ($value['extension_code'] == 'package_buy') {
			$value['amount'] = 0;
			$arr[$key]['dis_amount'] = 0;
			$arr[$key]['dis_amount_formated'] = price_format($arr[$key]['dis_amount'], false);
			$arr[$key]['package_goods_list'] = get_package_goods($value['goods_id']);
			$package = get_package_goods_info($arr[$key]['package_goods_list']);
			$arr[$key]['package_saving'] = abs($package['save_subtotal'] - $value['goods_price']);
			$arr[$key]['formated_package_saving'] = price_format($arr[$key]['package_saving'], false);
			$arr[$key]['goods_weight'] = $package['goods_weight'];
			$arr[$key]['goodsweight'] = $package['goods_weight'];
			$arr[$key]['goods_number'] = $value['goods_number'];
			$arr[$key]['attr_number'] = 1;
			$sql = 'SELECT activity_thumb FROM {pre}goods_activity WHERE act_id = \'' . $value['goods_id'] . '\' and review_status = 3';
			$activity_thumb = $GLOBALS['db']->getRow($sql);
			$arr[$key]['goods_thumb'] = get_image_path($activity_thumb['activity_thumb']);
		}
		else {
			$arr[$key]['cloud_goodsname'] = $value['cloud_goodsname'];
			$arr[$key]['cloud_id'] = $value['cloud_id'];
			$goods_con = get_con_goods_amount($value['subtotal'], $value['goods_id'], 0, 0, $value['parent_id']);
			$goods_con['amount'] = explode(',', $goods_con['amount']);
			$value['amount'] = min($goods_con['amount']);
			$arr[$key]['subtotal'] = $value['subtotal'];
			$arr[$key]['dis_amount'] = $value['subtotal'] - $value['amount'];
			$arr[$key]['dis_amount_formated'] = price_format($arr[$key]['dis_amount'], false);
			$arr[$key]['goods_thumb'] = get_image_path($value['goods_thumb']);
			$arr[$key]['formated_market_price'] = price_format($value['market_price'], false);
			$arr[$key]['formated_presale_deposit'] = price_format($value['deposit'], false);
			$arr[$key]['region_name'] = $GLOBALS['db']->getOne('select region_name from ' . $GLOBALS['ecs']->table('region_warehouse') . ' where region_id = \'' . $value['warehouse_id'] . '\'');
			$arr[$key]['rec_txt'] = $rec_txt[$value['rec_type']];

			if ($value['rec_type'] == 1) {
				$sql = 'SELECT act_id,act_name FROM ' . $GLOBALS['ecs']->table('goods_activity') . ' WHERE act_type = \'' . GAT_GROUP_BUY . '\' AND goods_id = \'' . $value['goods_id'] . '\' AND review_status = 3';
				$group_buy = $GLOBALS['db']->getRow($sql);
				$arr[$key]['url'] = url('groupbuy/index/detail', array('id' => $group_buy['act_id']));
				$arr[$key]['act_name'] = $group_buy['act_name'];
			}
			else if ($value['rec_type'] == 5) {
				$sql = 'SELECT act_id,act_name FROM ' . $GLOBALS['ecs']->table('presale_activity') . ' WHERE goods_id = \'' . $value['goods_id'] . '\' AND review_status = 3';
				$presale = $GLOBALS['db']->getRow($sql);
				$arr[$key]['url'] = url('presale/index/detail', array('id' => $presale['act_id']));
				$arr[$key]['act_name'] = $presale['act_name'];
			}
			else {
				$arr[$key]['url'] = build_uri('goods', array('gid' => $value['goods_id']), $value['goods_name']);
			}

			if ($value['extension_code'] == 'presale' || 1 < $value['rec_type']) {
				$arr[$key]['attr_number'] = 1;
			}
			else {
				if ($ru_type == 1 && 0 < $warehouse_id && $store_id == 0) {
					$leftJoin = ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . (' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $warehouse_id . '\' ');
					$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ');
					$sql = 'SELECT IF(g.model_price < 1, g.goods_number, IF(g.model_price < 2, wg.region_number, wag.region_number)) AS goods_number, g.user_id, g.model_attr FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . $leftJoin . ' WHERE g.goods_id = \'' . $value['goods_id'] . '\' LIMIT 1';
					$goodsInfo = $GLOBALS['db']->getRow($sql);
					$products = get_warehouse_id_attr_number($value['goods_id'], $value['goods_attr_id'], $goodsInfo['user_id'], $warehouse_id, $area_id, $area_city);
					$attr_number = $products['product_number'];

					if ($goodsInfo['model_attr'] == 1) {
						$table_products = 'products_warehouse';
						$type_files = ' and warehouse_id = \'' . $warehouse_id . '\'';
					}
					else if ($goodsInfo['model_attr'] == 2) {
						$table_products = 'products_area';
						$type_files = ' and area_id = \'' . $area_id . '\'';

						if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
							$type_files .= ' AND city_id = \'' . $area_city . '\'';
						}
					}
					else {
						$table_products = 'products';
						$type_files = '';
					}

					$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table($table_products) . ' WHERE goods_id = \'' . $value['goods_id'] . '\'' . $type_files . ' LIMIT 0, 1';
					$prod = $GLOBALS['db']->getRow($sql);

					if (empty($prod)) {
						$attr_number = $GLOBALS['_CFG']['use_storage'] == 1 ? $goodsInfo['goods_number'] : 1;
					}

					if (0 < $value['cloud_id']) {
						$attr_number = get_jigon_products_stock($prod);
					}

					$arr[$key]['attr_number'] = $attr_number;
				}
				else {
					$arr[$key]['attr_number'] = $value['goods_number'];
				}
			}

			if (0 < $store_id) {
				$sql = 'SELECT goods_number,ru_id FROM' . $GLOBALS['ecs']->table('store_goods') . (' WHERE store_id = \'' . $store_id . '\' AND goods_id = \'') . $value['goods_id'] . '\' ';
				$goodsInfo = $GLOBALS['db']->getRow($sql);
				$products = get_warehouse_id_attr_number($value['goods_id'], $value['goods_attr_id'], $goodsInfo['ru_id'], 0, 0, 0, '', $store_id);
				$attr_number = $products['product_number'];

				if ($value['goods_attr_id']) {
					$arr[$key]['attr_number'] = $attr_number;
				}
				else {
					$arr[$key]['attr_number'] = $goodsInfo['goods_number'];
				}
			}
		}
	}

	if ($ru_type == 1) {
		$arr = get_cart_goods_ru_list($arr, $ru_type);
		$arr = get_cart_ru_goods_list($arr, $cart_value, $consignee, $store_id);
	}

	return $arr;
}

function set_cloud_order_goods($cart_goods = array(), $order = array())
{
	if (!$GLOBALS['_CFG']['cloud_dsc_appkey']) {
		return $requ = array();
	}

	$cloud_order = array();
	$order_request = array();
	$order_detaillist = array();

	foreach ($cart_goods as $cart_goods_key => $cart_goods_val) {
		if (0 < $cart_goods_val['cloud_id']) {
			$arr = array();
			$arr['goodName'] = $cart_goods_val['cloud_goodsname'];
			$arr['goodId'] = $cart_goods_val['cloud_id'];

			if ($cart_goods_val['goods_attr_id']) {
				$goods_attr_id = explode(',', $cart_goods_val['goods_attr_id']);
				$where = '';

				foreach ($goods_attr_id as $key => $val) {
					$where .= ' AND FIND_IN_SET(\'' . $val . '\', REPLACE(goods_attr, \'|\', \',\')) ';
				}

				$sql = 'SELECT cloud_product_id,inventoryid FROM ' . $GLOBALS['ecs']->table('products') . ' WHERE goods_id = \'' . $cart_goods_val['goods_id'] . '\'' . $where . ' LIMIT 1';
				$products_info = $GLOBALS['db']->getRow($sql);
				$arr['inventoryId'] = $products_info['inventoryid'];
				$arr['productId'] = $products_info['cloud_product_id'];
			}

			$arr['quantity'] = $cart_goods_val['goods_number'];
			$arr['deliveryWay'] = '3';
			$order_detaillist[] = $arr;
		}
	}

	if (!empty($order_detaillist)) {
		$order_request['orderDetailList'] = $order_detaillist;
		$order_request['address'] = $order['address'];
		$order_request['area'] = get_table_date('region', 'region_id=\'' . $order['district'] . '\'', array('region_name'), 2);
		$order_request['city'] = get_table_date('region', 'region_id=\'' . $order['city'] . '\'', array('region_name'), 2);
		$order_request['province'] = get_table_date('region', 'region_id=\'' . $order['province'] . '\'', array('region_name'), 2);
		$order_request['remark'] = $order['postscript'];
		$order_request['mobile'] = intval($order['mobile']);
		$order_request['payType'] = 99;
		$order_request['linkMan'] = $order['consignee'];
		$order_request['billType'] = !empty($order['invoice_type']) ? 2 : 1;
		$order_request['billHeader'] = $order['inv_payee'];
		$order_request['isBill'] = $order['need_inv'];
		$order_request['taxNumber'] = '';

		if ($order_request['billType'] == 2) {
			$sql = 'SELECT company_name,tax_id FROM' . $GLOBALS['ecs']->table('users_vat_invoices_info') . 'WHERE user_id = \'' . $order['user_id'] . '\' ';
			$users_vat_invoices_info = $GLOBALS['db']->getRow($sql);
			$order_request['billHeader'] = $users_vat_invoices_info['company_name'];
			$order_request['taxNumber'] = $users_vat_invoices_info['tax_id'];
		}

		$cloud = new \App\Services\Erp\JigonService();
		$requ = $cloud->push($order_request, $order);
		$requ = json_decode($requ, true);
	}

	return $requ;
}

function cart_amount($include_gift = true, $type = CART_GENERAL_GOODS, $cart_value = '')
{
	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$sql = 'SELECT SUM(goods_price * goods_number) ' . ' FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . ('AND rec_type = \'' . $type . '\' ');

	if (!$include_gift) {
		$sql .= ' AND is_gift = 0 AND goods_id > 0';
	}

	if ($cart_value) {
		$sql .= ' AND rec_id ' . db_create_in($cart_value);
	}

	return floatval($GLOBALS['db']->getOne($sql));
}

function cart_goods_exists($id, $spec, $type = CART_GENERAL_GOODS)
{
	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('cart') . 'WHERE ' . $sess_id . (' AND goods_id = \'' . $id . '\' ') . 'AND parent_id = 0 AND goods_attr = \'' . get_goods_attr_info($spec) . '\' ' . ('AND rec_type = \'' . $type . '\'');
	return 0 < $GLOBALS['db']->getOne($sql);
}

function cart_weight_price($type = CART_GENERAL_GOODS, $cart_value)
{
	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
		$c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
		$c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$goodsIn = '';
	$pack_goodsIn = '';

	if (!empty($cart_value)) {
		$goodsIn = ' and c.rec_id in(' . $cart_value . ')';
		$pack_goodsIn = ' and rec_id in(' . $cart_value . ')';
	}

	$package_row['weight'] = 0;
	$package_row['amount'] = 0;
	$package_row['number'] = 0;
	$packages_row['free_shipping'] = 1;
	$sql = 'SELECT goods_id, goods_number, goods_price FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE extension_code = \'package_buy\' AND ' . $sess_id . $pack_goodsIn;
	$row = $GLOBALS['db']->getAll($sql);

	if ($row) {
		$packages_row['free_shipping'] = 0;
		$free_shipping_count = 0;

		foreach ($row as $val) {
			$sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('package_goods') . ' AS pg, ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . 'WHERE g.goods_id = pg.goods_id AND g.is_shipping = 0 AND pg.package_id = \'' . $val['goods_id'] . '\'';
			$shipping_count = $GLOBALS['db']->getOne($sql);

			if (0 < $shipping_count) {
				$sql = 'SELECT SUM(g.goods_weight * pg.goods_number) AS weight, ' . 'SUM(pg.goods_number) AS number, g.freight FROM ' . $GLOBALS['ecs']->table('package_goods') . ' AS pg, ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . 'WHERE g.goods_id = pg.goods_id AND g.is_shipping = 0 AND g.freight <> 2 AND pg.package_id = \'' . $val['goods_id'] . '\'';
				$goods_row = $GLOBALS['db']->getRow($sql);
				$package_row['weight'] += floatval($goods_row['weight']) * $val['goods_number'];
				$package_row['amount'] += floatval($val['goods_price']) * $val['goods_number'];
				$package_row['number'] += intval($goods_row['number']) * $val['goods_number'];
			}
			else {
				$free_shipping_count++;
			}
		}

		$packages_row['free_shipping'] = $free_shipping_count == count($row) ? 1 : 0;
	}

	$sql = 'SELECT g.goods_weight, c.goods_price, c.goods_number, g.freight ' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = c.goods_id ' . 'WHERE ' . $c_sess . ('AND rec_type = \'' . $type . '\' AND g.is_shipping = 0 AND g.freight <> 2 AND c.extension_code != \'package_buy\' ') . $goodsIn;
	$res = $GLOBALS['db']->getAll($sql);
	$weight = 0;
	$amount = 0;
	$number = 0;

	if ($res) {
		foreach ($res as $key => $row) {
			if ($row['freight'] == 1) {
				$weight += 0;
			}
			else {
				$weight += $row['goods_weight'] * $row['goods_number'];
			}

			$amount += $row['goods_price'] * $row['goods_number'];
			$number += $row['goods_number'];
		}
	}

	$packages_row['weight'] = floatval($weight) + $package_row['weight'];
	$packages_row['amount'] = floatval($amount) + $package_row['amount'];
	$packages_row['number'] = intval($number) + $package_row['number'];
	$packages_row['formated_weight'] = formated_weight($packages_row['weight']);
	return $packages_row;
}

function addto_cart($goods_id, $num = 1, $spec = array(), $parent = 0, $cart_extends = array(), $rec_type = CART_GENERAL_GOODS)
{
	$GLOBALS['err']->clean();
	$_parent_id = $parent;
	$warehouse_id = !empty($cart_extends['warehouse_id']) ? $cart_extends['warehouse_id'] : 0;
	$area_id = !empty($cart_extends['area_id']) ? $cart_extends['area_id'] : 0;
	$area_city = !empty($cart_extends['area_city']) ? $cart_extends['area_city'] : 0;
	$store_id = !empty($cart_extends['store_id']) ? $cart_extends['store_id'] : 0;
	$take_time = !empty($cart_extends['take_time']) ? $cart_extends['take_time'] : '';
	$store_mobile = !empty($cart_extends['store_mobile']) ? $cart_extends['store_mobile'] : '';
	$where_area = '';

	if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
		$where_area = ' AND wag.city_id = \'' . $area_city . '\'';
	}

	$leftJoin = '';
	$shop_price = 'wg.warehouse_price, wg.warehouse_promote_price, wg.region_number as wg_number, wag.region_price, wag.region_promote_price, wag.region_number as wag_number, g.model_price, g.model_attr, ';
	$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . (' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $warehouse_id . '\' ');
	$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ' . $where_area);

	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
		$sess = '';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
		$sess = real_cart_mac_ip();
	}

	$sql = 'SELECT wg.w_id, g.goods_name, g.goods_sn, g.is_on_sale, g.is_real, g.user_id as ru_id, g.model_inventory, g.model_attr, ' . 'wg.region_number AS wg_number, wag.region_number AS wag_number, ' . 'g.market_price, IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price)) AS org_price, ' . 'g.promote_start_date,g.promote_end_date, g.goods_weight, g.integral, g.extension_code, ' . 'g.goods_number, g.is_alone_sale, g.is_shipping, g.freight, g.tid, g.shipping_fee, g.commission_rate, ' . ('IFNULL(IFNULL(mp.user_price, IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price)) * \'' . $_SESSION['discount'] . '\'), g.shop_price * \'' . $_SESSION['discount'] . '\')  AS shop_price, ') . 'IFNULL(IF(g.model_price < 1, g.promote_price, IF(g.model_price < 2, wg.warehouse_promote_price, wag.region_promote_price)), g.promote_price) AS promote_price ' . 'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . $leftJoin . ' LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' . ('ON mp.goods_id = g.goods_id AND mp.user_rank = \'' . $_SESSION['user_rank'] . '\' ') . (' WHERE g.goods_id = \'' . $goods_id . '\'') . ' AND g.is_delete = 0';
	$goods = $GLOBALS['db']->getRow($sql);

	if (0 < $store_id) {
		$goods['goods_number'] = $GLOBALS['db']->getOne('SELECT  goods_number FROM' . $GLOBALS['ecs']->table('store_goods') . (' WHERE goods_id = \'' . $goods_id . '\' AND store_id = \'' . $store_id . '\''));
	}

	if (empty($goods)) {
		$GLOBALS['err']->add(L('goods_not_exists'), ERR_NOT_EXISTS);
		return false;
	}

	if (0 < $_parent_id) {
		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('cart') . (' WHERE goods_id=\'' . $_parent_id . '\' AND ') . $sess_id . ' AND extension_code <> \'package_buy\'';

		if ($GLOBALS['db']->getOne($sql) == 0) {
			$GLOBALS['err']->add(L('no_basic_goods'), ERR_NO_BASIC_GOODS);
			return false;
		}
	}

	if ($goods['is_on_sale'] == 0) {
		$GLOBALS['err']->add(L('not_on_sale'), ERR_NOT_ON_SALE);
		return false;
	}

	if (empty($parent) && $goods['is_alone_sale'] == 0) {
		$GLOBALS['err']->add(L('cannt_alone_sale'), ERR_CANNT_ALONE_SALE);
		return false;
	}

	if (0 < $store_id) {
		$table_products = 'store_products';
		$type_files = ' and store_id = \'' . $store_id . '\'';
	}
	else if ($goods['model_attr'] == 1) {
		$table_products = 'products_warehouse';
		$type_files = ' and warehouse_id = \'' . $warehouse_id . '\'';
	}
	else if ($goods['model_attr'] == 2) {
		$table_products = 'products_area';
		$type_files = ' and area_id = \'' . $area_id . '\'';

		if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
			$type_files .= ' AND city_id = \'' . $area_city . '\'';
		}
	}
	else {
		$table_products = 'products';
		$type_files = '';
	}

	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table($table_products) . (' WHERE goods_id = \'' . $goods_id . '\'') . $type_files . ' LIMIT 0, 1';
	$prod = $GLOBALS['db']->getRow($sql);
	if (is_spec($spec) && !empty($prod)) {
		$product_info = get_products_info($goods_id, $spec, $warehouse_id, $area_id, $area_city, $store_id);
	}

	if (empty($product_info)) {
		$product_info = array('product_number' => 0, 'product_id' => 0);
	}

	if ($goods['model_inventory'] == 1) {
		$goods['goods_number'] = $goods['wg_number'];
	}
	else if ($goods['model_inventory'] == 2) {
		$goods['goods_number'] = $goods['wag_number'];
	}

	if ($GLOBALS['_CFG']['use_storage'] == 1) {
		if (0 < $store_id) {
			$lang_shortage = L('store_shortage');
		}
		else {
			$lang_shortage = L('shortage');
		}

		$is_product = 0;
		if (is_spec($spec) && !empty($prod)) {
			if (!empty($spec)) {
				if ($product_info['product_number'] < $num) {
					$GLOBALS['err']->add(sprintf($lang_shortage, $product_info['product_number']), ERR_OUT_OF_STOCK);
					return false;
				}
			}
		}
		else {
			$is_product = 1;
		}

		if ($is_product == 1) {
			if ($goods['goods_number'] < $num) {
				$GLOBALS['err']->add(sprintf(L('shortage'), $goods['goods_number']), ERR_OUT_OF_STOCK);
				return false;
			}
		}
	}

	$warehouse_area['warehouse_id'] = $warehouse_id;
	$warehouse_area['area_id'] = $area_id;
	$warehouse_area['area_city'] = $area_city;

	if ($GLOBALS['_CFG']['add_shop_price'] == 1) {
		$add_tocart = 1;
	}
	else {
		$add_tocart = 0;
	}

	$spec_price = spec_price($spec, $goods_id, $warehouse_area);
	$goods_price = get_final_price($goods_id, $num, true, $spec, $warehouse_id, $area_id, $area_city, 0, 0, $add_tocart);
	$goods['market_price'] += $spec_price;
	$goods_attr = get_goods_attr_info($spec, 'pice', $warehouse_id, $area_id);
	$goods_attr_id = '';

	if (!empty($spec)) {
		$goods_attr_id = join(',', $spec);
	}

	$cartData = array('user_id' => $_SESSION['user_id'], 'session_id' => $sess, 'goods_id' => $goods_id, 'goods_sn' => addslashes($goods['goods_sn']), 'product_id' => $product_info['product_id'], 'goods_name' => addslashes($goods['goods_name']), 'market_price' => $goods['market_price'], 'goods_attr' => addslashes($goods_attr), 'goods_attr_id' => $goods_attr_id, 'is_real' => $goods['is_real'], 'model_attr' => $goods['model_attr'], 'warehouse_id' => $warehouse_id, 'area_id' => $area_id, 'area_city' => $area_city, 'ru_id' => $goods['ru_id'], 'extension_code' => $goods['extension_code'], 'is_gift' => 0, 'is_shipping' => $goods['is_shipping'], 'rec_type' => $rec_type, 'add_time' => gmtime(), 'freight' => $goods['freight'], 'tid' => $goods['tid'], 'shipping_fee' => $goods['shipping_fee'], 'commission_rate' => $goods['commission_rate'], 'store_id' => $store_id, 'store_mobile' => $store_mobile, 'take_time' => $take_time);
	$basic_list = array();
	$sql = 'SELECT parent_id, goods_price ' . 'FROM ' . $GLOBALS['ecs']->table('group_goods') . (' WHERE goods_id = \'' . $goods_id . '\'') . (' AND goods_price <= \'' . $goods_price . '\'') . (' AND parent_id = \'' . $_parent_id . '\'') . ' ORDER BY goods_price';
	$res = $GLOBALS['db']->query($sql);

	foreach ($res as $row) {
		$basic_list[$row['parent_id']] = $row['goods_price'];
	}

	$basic_count_list = array();

	if ($basic_list) {
		$sql = 'SELECT goods_id, SUM(goods_number) AS count ' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . ' AND parent_id = 0' . ' AND extension_code <> \'package_buy\' ' . ' AND goods_id ' . db_create_in(array_keys($basic_list)) . ' GROUP BY goods_id';
		$res = $GLOBALS['db']->query($sql);

		foreach ($res as $row) {
			$basic_count_list[$row['goods_id']] = $row['count'];
		}
	}

	if ($basic_count_list) {
		$sql = 'SELECT parent_id, SUM(goods_number) AS count ' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . (' AND goods_id = \'' . $goods_id . '\'') . ' AND extension_code <> \'package_buy\' ' . ' AND parent_id ' . db_create_in(array_keys($basic_count_list)) . ' GROUP BY parent_id';
		$res = $GLOBALS['db']->query($sql);

		foreach ($res as $row) {
			$basic_count_list[$row['parent_id']] -= $row['count'];
		}
	}

	foreach ($basic_list as $parent_id => $fitting_price) {
		if ($num <= 0) {
			break;
		}

		if (!isset($basic_count_list[$parent_id])) {
			continue;
		}

		if ($basic_count_list[$parent_id] <= 0) {
			continue;
		}

		$cartData['goods_price'] = max($fitting_price, 0) + $spec_price;
		$cartData['goods_number'] = min($num, $basic_count_list[$parent_id]);
		$cartData['parent_id'] = $parent_id;
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('cart'), $cartData, 'INSERT');
		$num -= $cartData['goods_number'];
	}

	if (0 < $num) {
		$sql = 'SELECT goods_number FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . (' AND goods_id = \'' . $goods_id . '\' ') . (' AND parent_id = 0 AND goods_attr = \'' . $goods_attr . '\' ') . ' AND extension_code <> \'package_buy\' ' . ' AND rec_type = \'' . $rec_type . ('\' AND group_id=\'\' AND warehouse_id = \'' . $warehouse_id . '\' AND stages_qishu=\'-1\' AND store_id = \'') . $store_id . '\' ';
		$row = $GLOBALS['db']->getRow($sql);

		if ($row) {
			$num += $row['goods_number'];
			if (is_spec($spec) && !empty($prod)) {
				$goods_storage = $product_info['product_number'];
			}
			else {
				$goods_storage = $goods['goods_number'];
			}

			if ($GLOBALS['_CFG']['use_storage'] == 0 || $num <= $goods_storage) {
				$goods_price = get_final_price($goods_id, $num, true, $spec, $warehouse_id, $area_id, $area_city, 0, 0, $add_tocart);
				$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart') . (' SET goods_number = \'' . $num . '\'') . (' , goods_price = \'' . $goods_price . '\'') . (' , area_id = \'' . $area_id . '\'') . ' WHERE ' . $sess_id . (' AND goods_id = \'' . $goods_id . '\' ') . (' AND parent_id = 0 AND goods_attr = \'' . $goods_attr . '\' ') . ' AND extension_code <> \'package_buy\' ' . (' AND warehouse_id = \'' . $warehouse_id . '\' ') . 'AND rec_type = \'' . $rec_type . '\' AND group_id = 0 AND stages_qishu=\'-1\' AND store_id = ' . $store_id;
				$GLOBALS['db']->query($sql);
			}
			else {
				$GLOBALS['err']->add(sprintf(L('shortage'), $num), ERR_OUT_OF_STOCK);
				return false;
			}
		}
		else {
			$goods_price = get_final_price($goods_id, $num, true, $spec, $warehouse_id, $area_id, $area_city, 0, 0, $add_tocart);
			$cartData['goods_price'] = max($goods_price, 0);
			$cartData['goods_number'] = $num;
			$cartData['parent_id'] = $_parent_id;
			$rec_id = dao('cart')->data($cartData)->add();
			return $rec_id;
		}
	}

	return true;
}

function addto_cart_combo($goods_id, $num = 1, $spec = array(), $parent = 0, $group = '', $cart_combo_extends = array(), $parent_goods_attr = '')
{
	if (!is_array($parent_goods_attr)) {
		if (!empty($parent_goods_attr)) {
			$parent_goods_attr = explode(',', $parent_goods_attr);
		}
		else {
			$parent_goods_attr = array();
		}
	}

	$warehouse_id = !empty($cart_combo_extends['warehouse_id']) ? $cart_combo_extends['warehouse_id'] : 0;
	$area_id = !empty($cart_combo_extends['area_id']) ? $cart_combo_extends['area_id'] : 0;
	$area_city = !empty($cart_combo_extends['area_city']) ? $cart_combo_extends['area_city'] : 0;
	$ok_arr = get_insert_group_main($parent, $num, $parent_goods_attr, 0, $group, $warehouse_id, $area_id, $area_city);

	if ($ok_arr) {
		if ($ok_arr['is_ok'] == 1) {
			$GLOBALS['err']->add(L('group_goods_not_exists'), ERR_NOT_EXISTS);
			return false;
		}

		if ($ok_arr['is_ok'] == 2) {
			$GLOBALS['err']->add(L('group_not_on_sale'), ERR_NOT_ON_SALE);
			return false;
		}

		if ($ok_arr['is_ok'] == 3 || $ok_arr['is_ok'] == 4) {
			$GLOBALS['err']->add(sprintf(L('group_shortage')), ERR_OUT_OF_STOCK);
			return false;
		}
	}

	$GLOBALS['err']->clean();
	$_parent_id = $parent;
	$where_area = '';

	if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
		$where_area = ' AND wag.city_id = \'' . $area_city . '\'';
	}

	$leftJoin = '';
	$shop_price = 'wg.warehouse_price, wg.warehouse_promote_price, wg.region_number as wg_number, wag.region_price, wag.region_promote_price, wag.region_number as wag_number, g.model_price, g.model_attr, ';
	$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . (' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $warehouse_id . '\' ');
	$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ') . $where_area;

	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
		$sess = '';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
		$sess = real_cart_mac_ip();
	}

	$sql = 'SELECT wg.w_id, g.goods_name, g.goods_sn, g.is_on_sale, g.is_real, g.user_id as ru_id, g.model_inventory, g.model_attr, ' . $shop_price . 'g.market_price, IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price)) AS org_price, ' . 'IFNULL(IF(g.model_price < 1, g.promote_price, IF(g.model_price < 2, wg.warehouse_promote_price, wag.region_promote_price)), g.promote_price) AS promote_price, ' . ' g.promote_start_date, g.commission_rate, ' . 'g.promote_end_date, g.goods_weight, g.integral, g.extension_code, ' . 'g.goods_number, g.is_alone_sale, g.is_shipping,' . ('IFNULL(IFNULL(mp.user_price, IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price)) * \'' . $_SESSION['discount'] . '\'), g.shop_price * \'' . $_SESSION['discount'] . '\')  AS shop_price ') . ' FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . $leftJoin . ' LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' . ('ON mp.goods_id = g.goods_id AND mp.user_rank = \'' . $_SESSION['user_rank'] . '\' ') . (' WHERE g.goods_id = \'' . $goods_id . '\'') . ' AND g.is_delete = 0';
	$goods = $GLOBALS['db']->getRow($sql);

	if (empty($goods)) {
		$GLOBALS['err']->add(L('goods_not_exists'), ERR_NOT_EXISTS);
		return false;
	}

	if ($goods['is_on_sale'] == 0) {
		$GLOBALS['err']->add(L('not_on_sale'), ERR_NOT_ON_SALE);
		return false;
	}

	if (empty($parent) && $goods['is_alone_sale'] == 0) {
		$GLOBALS['err']->add(L('cannt_alone_sale'), ERR_CANNT_ALONE_SALE);
		return false;
	}

	if ($goods['model_attr'] == 1) {
		$table_products = 'products_warehouse';
		$type_files = ' AND warehouse_id = \'' . $warehouse_id . '\'';
	}
	else if ($goods['model_attr'] == 2) {
		$table_products = 'products_area';
		$type_files = ' AND area_id = \'' . $area_id . '\'';

		if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
			$type_files .= ' AND city_id = \'' . $area_city . '\'';
		}
	}
	else {
		$table_products = 'products';
		$type_files = '';
	}

	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table($table_products) . (' WHERE goods_id = \'' . $goods_id . '\'') . $type_files . ' LIMIT 0, 1';
	$prod = $GLOBALS['db']->getRow($sql);
	if (is_spec($spec) && !empty($prod)) {
		$product_info = get_products_info($goods_id, $spec, $warehouse_id, $area_id, $area_city);
	}

	if (empty($product_info)) {
		$product_info = array('product_number' => 0, 'product_id' => 0);
	}

	if ($goods['model_inventory'] == 1) {
		$goods['goods_number'] = $goods['wg_number'];
	}
	else if ($goods['model_inventory'] == 2) {
		$goods['goods_number'] = $goods['wag_number'];
	}

	if ($GLOBALS['_CFG']['use_storage'] == 1) {
		$is_product = 0;
		if (is_spec($spec) && !empty($prod)) {
			if (!empty($spec)) {
				if ($product_info['product_number'] < $num) {
					$GLOBALS['err']->add(sprintf(L('shortage'), $product_info['product_number']), ERR_OUT_OF_STOCK);
					return false;
				}
			}
		}
		else {
			$is_product = 1;
		}

		if ($is_product == 1) {
			if ($goods['goods_number'] < $num) {
				$GLOBALS['err']->add(sprintf(L('shortage'), $goods['goods_number']), ERR_OUT_OF_STOCK);
				return false;
			}
		}
	}

	$warehouse_area['warehouse_id'] = $warehouse_id;
	$warehouse_area['area_id'] = $area_id;
	$warehouse_area['area_city'] = $area_city;
	$spec_price = spec_price($spec, $goods_id, $warehouse_area);
	$goods_price = get_final_price($goods_id, $num, true, $spec, $warehouse_id, $area_id, $area_city);
	$goods['market_price'] += $spec_price;
	$goods_attr = get_goods_attr_info($spec, 'pice', $warehouse_id, $area_id);

	if (!empty($spec)) {
		$goods_attr_id = join(',', $spec);
	}

	$parent = array('user_id' => $_SESSION['user_id'], 'session_id' => $sess, 'goods_id' => $goods_id, 'goods_sn' => addslashes($goods['goods_sn']), 'product_id' => $product_info['product_id'], 'goods_name' => addslashes($goods['goods_name']), 'market_price' => $goods['market_price'], 'goods_attr' => addslashes($goods_attr), 'goods_attr_id' => $goods_attr_id, 'is_real' => $goods['is_real'], 'model_attr' => $goods['model_attr'], 'warehouse_id' => $warehouse_id, 'area_id' => $area_id, 'area_city' => $area_city, 'ru_id' => $goods['ru_id'], 'extension_code' => $goods['extension_code'], 'is_gift' => 0, 'model_attr' => $goods['model_attr'], 'is_shipping' => $goods['is_shipping'], 'rec_type' => CART_GENERAL_GOODS, 'add_time' => gmtime(), 'group_id' => $group, 'commission_rate' => $goods['commission_rate']);
	$basic_list = array();
	$sql = 'SELECT parent_id, goods_price ' . 'FROM ' . $GLOBALS['ecs']->table('group_goods') . (' WHERE goods_id = \'' . $goods_id . '\'') . (' AND parent_id = \'' . $_parent_id . '\'') . ' ORDER BY goods_price';
	$res = $GLOBALS['db']->query($sql);

	foreach ($res as $row) {
		$basic_list[$row['parent_id']] = $row['goods_price'];
	}

	foreach ($basic_list as $parent_id => $fitting_price) {
		$attr_info = get_goods_attr_info($spec, 'pice', $warehouse_id, $area_id);
		$sql = 'SELECT goods_number FROM ' . $GLOBALS['ecs']->table('cart_combo') . ' WHERE ' . $sess_id . (' AND goods_id = \'' . $goods_id . '\' ') . (' AND parent_id = \'' . $parent_id . '\' ') . ' AND extension_code <> \'package_buy\' ' . ' AND rec_type = ' . CART_GENERAL_GOODS . (' AND group_id=\'' . $group . '\'');
		$row = $GLOBALS['db']->getRow($sql);

		if ($row) {
			$num = 1;
			if (is_spec($spec) && !empty($prod)) {
				$goods_storage = $product_info['product_number'];
			}
			else {
				$goods_storage = $goods['goods_number'];
			}

			if ($GLOBALS['_CFG']['use_storage'] == 0 || $num <= $goods_storage) {
				$fittAttr_price = max($fitting_price, 0) + $spec_price;
				$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart_combo') . (' SET goods_number = \'' . $num . '\'') . (' , goods_price = \'' . $fittAttr_price . '\'') . ' , product_id = \'' . $product_info['product_id'] . '\'' . (' , goods_attr = \'' . $attr_info . '\'') . (' , goods_attr_id = \'' . $goods_attr_id . '\'') . ' , market_price = \'' . $goods['market_price'] . '\'' . (' , warehouse_id = \'' . $warehouse_id . '\'') . (' , area_id = \'' . $area_id . '\'') . (' , area_city = \'' . $area_city . '\'') . ' WHERE ' . $sess_id . (' AND goods_id = \'' . $goods_id . '\' ') . (' AND parent_id = \'' . $parent_id . '\' ') . ' AND extension_code <> \'package_buy\' ' . 'AND rec_type = ' . CART_GENERAL_GOODS . (' AND group_id=\'' . $group . '\'');
				$GLOBALS['db']->query($sql);
			}
			else {
				$GLOBALS['err']->add(sprintf(L('shortage'), $num), ERR_OUT_OF_STOCK);
				return false;
			}
		}
		else {
			$parent['goods_price'] = max($fitting_price, 0) + $spec_price;
			$parent['goods_number'] = 1;
			$parent['parent_id'] = $parent_id;
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('cart_combo'), $parent, 'INSERT');
		}
	}

	return true;
}

function get_insert_group_main($goods_id, $num = 1, $goods_spec = array(), $parent = 0, $group = '', $warehouse_id = 0, $area_id = 0, $area_city = 0)
{
	$ok_arr['is_ok'] = 0;
	$spec = $goods_spec;
	$GLOBALS['err']->clean();
	$_parent_id = $parent;
	$where_area = '';

	if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
		$where_area = ' AND wag.city_id = \'' . $area_city . '\'';
	}

	$leftJoin = '';
	$shop_price = 'wg.warehouse_price, wg.warehouse_promote_price, wg.region_number as wg_number, wag.region_price, wag.region_promote_price, wag.region_number as wag_number, g.model_price, g.model_attr, ';
	$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . (' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $warehouse_id . '\' ');
	$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ') . $where_area;

	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
		$sess = '';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
		$sess = real_cart_mac_ip();
	}

	$sql = 'SELECT wg.w_id, g.goods_name, g.goods_sn, g.is_on_sale, g.is_real, g.user_id as ru_id, g.model_inventory, g.model_attr, ' . $shop_price . 'g.market_price, IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price)) AS org_price, ' . 'IFNULL(IF(g.model_price < 1, g.promote_price, IF(g.model_price < 2, wg.warehouse_promote_price, wag.region_promote_price)), g.promote_price) AS promote_price, ' . ' g.promote_start_date, g.commission_rate, ' . 'g.promote_end_date, g.goods_weight, g.integral, g.extension_code, ' . 'g.goods_number, g.is_alone_sale, g.is_shipping,' . ('IFNULL(IFNULL(mp.user_price, IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price)) * \'' . $_SESSION['discount'] . '\'), g.shop_price * \'' . $_SESSION['discount'] . '\')  AS shop_price ') . ' FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . $leftJoin . ' LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' . ('ON mp.goods_id = g.goods_id AND mp.user_rank = \'' . $_SESSION['user_rank'] . '\' ') . (' WHERE g.goods_id = \'' . $goods_id . '\'') . ' AND g.is_delete = 0';
	$goods = $GLOBALS['db']->getRow($sql);

	if (empty($goods)) {
		$ok_arr['is_ok'] = 1;
		return $ok_arr;
	}

	if ($goods['is_on_sale'] == 0) {
		$ok_arr['is_ok'] = 2;
		return $ok_arr;
	}

	if ($goods['model_attr'] == 1) {
		$table_products = 'products_warehouse';
		$type_files = ' and warehouse_id = \'' . $warehouse_id . '\'';
	}
	else if ($goods['model_attr'] == 2) {
		$table_products = 'products_area';
		$type_files = ' and area_id = \'' . $area_id . '\'';

		if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
			$type_files .= ' AND city_id = \'' . $area_city . '\'';
		}
	}
	else {
		$table_products = 'products';
		$type_files = '';
	}

	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table($table_products) . (' WHERE goods_id = \'' . $goods_id . '\'') . $type_files . ' LIMIT 0, 1';
	$prod = $GLOBALS['db']->getRow($sql);
	if (is_spec($spec) && !empty($prod)) {
		$product_info = get_products_info($goods_id, $spec, $warehouse_id, $area_id, $area_city);
	}

	if (empty($product_info)) {
		$product_info = array('product_number' => 0, 'product_id' => 0);
	}

	if ($goods['model_inventory'] == 1) {
		$goods['goods_number'] = $goods['wg_number'];
	}
	else if ($goods['model_inventory'] == 2) {
		$goods['goods_number'] = $goods['wag_number'];
	}

	if ($GLOBALS['_CFG']['use_storage'] == 1) {
		$is_product = 0;
		if (is_spec($spec) && !empty($prod)) {
			if (!empty($spec)) {
				if ($product_info['product_number'] < $num) {
					$ok_arr['is_ok'] = 3;
					return $ok_arr;
				}
			}
		}
		else {
			$is_product = 1;
		}

		if ($is_product == 1) {
			if ($goods['goods_number'] < $num) {
				$ok_arr['is_ok'] = 4;
				return $ok_arr;
			}
		}
	}

	$warehouse_area['warehouse_id'] = $warehouse_id;
	$warehouse_area['area_id'] = $area_id;
	$warehouse_area['area_city'] = $area_city;

	if (C('shop.add_shop_price') == 1) {
		$add_tocart = 1;
	}
	else {
		$add_tocart = 0;
	}

	$spec_price = spec_price($spec, $goods_id, $warehouse_area);
	$goods_price = get_final_price($goods_id, $num, true, $spec, $warehouse_id, $area_id, $area_city, 0, 0, $add_tocart);
	$goods['market_price'] += $spec_price;
	$goods_attr = get_goods_attr_info($spec, 'pice', $warehouse_id, $area_id);
	$goods_attr_id = join(',', $spec);
	$parent = array('user_id' => $_SESSION['user_id'], 'session_id' => $sess, 'goods_id' => $goods_id, 'goods_sn' => addslashes($goods['goods_sn']), 'product_id' => $product_info['product_id'], 'goods_name' => addslashes($goods['goods_name']), 'market_price' => $goods['market_price'], 'goods_attr' => addslashes($goods_attr), 'goods_attr_id' => $goods_attr_id, 'is_real' => $goods['is_real'], 'model_attr' => $goods['model_attr'], 'warehouse_id' => $warehouse_id, 'area_id' => $area_id, 'area_city' => $area_city, 'ru_id' => $goods['ru_id'], 'extension_code' => $goods['extension_code'], 'is_gift' => 0, 'is_shipping' => $goods['is_shipping'], 'rec_type' => CART_GENERAL_GOODS, 'add_time' => gmtime(), 'group_id' => $group, 'commission_rate' => $goods['commission_rate']);
	$attr_info = get_goods_attr_info($spec, 'pice', $warehouse_id, $area_id);
	$sql = 'SELECT goods_number FROM ' . $GLOBALS['ecs']->table('cart_combo') . ' WHERE ' . $sess_id . (' AND goods_id = \'' . $goods_id . '\' ') . ' AND parent_id = 0 ' . ' AND extension_code <> \'package_buy\' ' . ' AND rec_type = \'' . CART_GENERAL_GOODS . ('\' AND group_id = \'' . $group . '\' AND warehouse_id = \'' . $warehouse_id . '\'');
	$row = $GLOBALS['db']->getRow($sql);

	if ($row) {
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart_combo') . (' SET goods_number = \'' . $num . '\'') . (' , goods_price = \'' . $goods_price . '\'') . ' , product_id = \'' . $product_info['product_id'] . '\'' . (' , goods_attr = \'' . $attr_info . '\'') . (' , goods_attr_id = \'' . $goods_attr_id . '\'') . ' , market_price = \'' . $goods['market_price'] . '\'' . (' , warehouse_id = \'' . $warehouse_id . '\'') . (' , area_id = \'' . $area_id . '\'') . (' , area_city = \'' . $area_city . '\'') . ' WHERE ' . $sess_id . (' AND goods_id = \'' . $goods_id . '\' ') . ' AND parent_id = 0 ' . ' AND extension_code <> \'package_buy\' ' . 'AND rec_type = \'' . CART_GENERAL_GOODS . ('\' AND group_id=\'' . $group . '\'');
		$GLOBALS['db']->query($sql);
	}
	else {
		$parent['goods_price'] = max($goods_price, 0);
		$parent['goods_number'] = $num;
		$parent['parent_id'] = 0;
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('cart_combo'), $parent, 'INSERT');
	}
}

function get_combo_goods_info($goods_id, $num = 1, $spec = array(), $parent = 0, $warehouse_area)
{
	$result = array();
	$sql = 'SELECT goods_number FROM ' . $GLOBALS['ecs']->table('goods') . (' WHERE goods_id = \'' . $goods_id . '\' AND is_delete = 0');
	$goods = $GLOBALS['db']->getRow($sql);
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('products') . (' WHERE goods_id = \'' . $goods_id . '\' LIMIT 0, 1');
	$prod = $GLOBALS['db']->getRow($sql);
	if (is_spec($spec) && !empty($prod)) {
		$product_info = get_products_info($goods_id, $spec, $warehouse_area['warehouse_id'], $warehouse_area['area_id'], $warehouse_area['area_city']);
	}

	if (empty($product_info)) {
		$product_info = array('product_number' => '', 'product_id' => 0);
	}

	$result['stock'] = $goods['goods_number'];
	if (is_spec($spec) && !empty($prod)) {
		if (!empty($spec)) {
			$result['stock'] = $product_info['product_number'];
		}
	}

	$sql = 'SELECT parent_id, goods_price ' . 'FROM ' . $GLOBALS['ecs']->table('group_goods') . (' WHERE goods_id = \'' . $goods_id . '\'') . (' AND parent_id = \'' . $parent . '\'') . ' ORDER BY goods_price';
	$res = $GLOBALS['db']->query($sql);

	foreach ($res as $row) {
		$result['fittings_price'] = $row['goods_price'];
	}

	if (C('shop.add_shop_price') == 1) {
		$add_tocart = 1;
	}
	else {
		$add_tocart = 0;
	}

	$result['goods_price'] = get_final_price($goods_id, $num, true, $spec, $warehouse_area['warehouse_id'], $warehouse_area['area_id'], $warehouse_area['area_city'], 0, 0, $add_tocart);
	$result['fittings_price'] = isset($result['fittings_price']) ? $result['fittings_price'] : $result['goods_price'];
	$result['spec_price'] = spec_price($spec, $goods_id, $warehouse_area);
	return $result;
}

function clear_cart($type = CART_GENERAL_GOODS, $cart_value = '')
{
	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$goodsIn = '';

	if (!empty($cart_value)) {
		$goodsIn = ' and rec_id in(' . $cart_value . ')';
	}

	$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . (' AND is_checked=1 AND rec_type = \'' . $type . '\'') . $goodsIn;
	$GLOBALS['db']->query($sql);

	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' user_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart_user_info') . ' WHERE ' . $sess_id;
	$GLOBALS['db']->query($sql);
	unset($_SESSION['cart_value']);
}

function get_goods_attr_info($arr, $type = 'pice', $warehouse_id = 0, $area_id = 0)
{
	$attr = '';

	if (!empty($arr)) {
		$fmt = '%s:%s[%s] 
';
		$leftJoin = '';
		$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('goods') . ' as g on g.goods_id = ga.goods_id';
		$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_attr') . (' as wap on ga.goods_id = wap.goods_id and wap.warehouse_id = \'' . $warehouse_id . '\' and ga.goods_attr_id = wap.goods_attr_id ');
		$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_attr') . (' as wa on ga.goods_id = wa.goods_id and wa.area_id = \'' . $area_id . '\' and ga.goods_attr_id = wa.goods_attr_id ');
		$sql = 'SELECT ga.goods_attr_id, a.attr_name, ga.attr_value, ' . ' IF(g.model_attr < 1, ga.attr_price, IF(g.model_attr < 2, wap.attr_price, wa.attr_price)) as attr_price ' . 'FROM ' . $GLOBALS['ecs']->table('goods_attr') . ' AS ga ' . $leftJoin . ' left join ' . $GLOBALS['ecs']->table('attribute') . ' AS a ' . 'on a.attr_id = ga.attr_id ' . 'WHERE ' . db_create_in($arr, 'ga.goods_attr_id') . ' ORDER BY a.sort_order, a.attr_id, ga.goods_attr_id';
		$res = $GLOBALS['db']->query($sql);

		foreach ($res as $row) {
			if ($GLOBALS['_CFG']['goods_attr_price'] == 1) {
				$attr_price = 0;
			}
			else {
				$attr_price = round(floatval($row['attr_price']), 2);
				$attr_price = price_format($attr_price, false);
			}

			$row['attr_name'] = str_replace('<em>', '', $row['attr_name']);
			$row['attr_name'] = str_replace('</em>', '', $row['attr_name']);
			$attr .= sprintf($fmt, $row['attr_name'], $row['attr_value'], $attr_price);
		}

		$attr = str_replace('[0]', '', $attr);
	}

	return $attr;
}

function user_info($user_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('users') . (' WHERE user_id = \'' . $user_id . '\'');
	$user = $GLOBALS['db']->getRow($sql);
	unset($user['question']);
	unset($user['answer']);

	if ($user) {
		$user['formated_user_money'] = price_format($user['user_money'], false);
		$user['formated_frozen_money'] = price_format($user['frozen_money'], false);
	}

	return $user;
}

function update_user($user_id, $user)
{
	return $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'), $user, 'UPDATE', 'user_id = \'' . $user_id . '\'');
}

function address_list($user_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('user_address') . (' WHERE user_id = \'' . $user_id . '\'');
	return $GLOBALS['db']->getAll($sql);
}

function address_info($address_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('user_address') . (' WHERE address_id = \'' . $address_id . '\'');
	return $GLOBALS['db']->getRow($sql);
}

function user_bonus($user_id, $goods_amount = 0, $cart_value = 0, $num = 10, $start = 1)
{
	$where = '';

	if (!empty($cart_value)) {
		$where = 'AND c.rec_id in(' . $cart_value . ')';
	}

	$sql = 'SELECT g.user_id FROM ' . $GLOBALS['ecs']->table('cart') . ' as c,' . $GLOBALS['ecs']->table('goods') . ' as g' . (' WHERE  c.goods_id = g.goods_id ' . $where);
	$goods_list = $GLOBALS['db']->getAll($sql);
	$where = '';
	$goods_user = '';

	if ($goods_list) {
		foreach ($goods_list as $key => $row) {
			$goods_user .= $row['user_id'] . ',';
		}
	}

	if (!empty($goods_user)) {
		$goods_user = substr($goods_user, 0, -1);
		$goods_user = explode(',', $goods_user);
		$goods_user = array_unique($goods_user);
		$goods_user = implode(',', $goods_user);
		$where = ' AND IF(t.usebonus_type > 0, t.usebonus_type = 1, t.user_id in(' . $goods_user . ')) ';
	}

	$day = local_getdate();
	$today = local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);
	$sqlCount = 'SELECT count(*) ';
	$sql = 'SELECT t.type_id, t.type_name, t.type_money, t.user_id, t.usebonus_type, t.use_start_date, t.use_end_date, b.bonus_id ';
	$where = 'FROM ' . $GLOBALS['ecs']->table('bonus_type') . ' AS t,' . $GLOBALS['ecs']->table('user_bonus') . ' AS b ' . 'WHERE t.type_id = b.bonus_type_id ' . ('AND t.use_start_date <= \'' . $today . '\' ') . ('AND t.use_end_date >= \'' . $today . '\' ') . ('AND t.min_goods_amount <= \'' . $goods_amount . '\' ') . 'AND b.user_id<>0 ' . ('AND b.user_id = \'' . $user_id . '\' ') . 'AND b.order_id = 0' . $where;
	$sql .= $where;
	$sqlCount .= $where;
	$count = $GLOBALS['db']->getRow($sqlCount);

	if ($num == 0) {
		$count = $GLOBALS['db']->getRow($sqlCount);
		return $count['count(*)'];
	}

	$res = $GLOBALS['db']->selectLimit($sql, $num, $start);
	return array('list' => $res, 'conut' => $count['count(*)']);
}

function bonus_info($bonus_id, $bonus_sn = '', $cart_value = 0)
{
	$where = '';
	if ($cart_value != 0 || !empty($cart_value)) {
		$sql = 'SELECT g.user_id FROM ' . $GLOBALS['ecs']->table('cart') . ' as c,' . $GLOBALS['ecs']->table('goods') . ' as g' . (' WHERE  c.goods_id = g.goods_id AND c.rec_id in(' . $cart_value . ')');
		$goods_list = $GLOBALS['db']->getAll($sql);
		$where = '';
		$goods_user = '';

		if ($goods_list) {
			foreach ($goods_list as $key => $row) {
				$goods_user .= $row['user_id'] . ',';
			}
		}

		if (!empty($goods_user)) {
			$goods_user = substr($goods_user, 0, -1);
			$goods_user = explode(',', $goods_user);
			$goods_user = array_unique($goods_user);
			$goods_user = implode(',', $goods_user);
			$where = ' AND IF(t.usebonus_type > 0, t.usebonus_type = 1, t.user_id in(' . $goods_user . ')) ';
		}
	}

	$sql = 'SELECT t.*, t.user_id as admin_id, b.* ' . 'FROM ' . $GLOBALS['ecs']->table('bonus_type') . ' AS t,' . $GLOBALS['ecs']->table('user_bonus') . ' AS b ' . 'WHERE t.type_id = b.bonus_type_id ' . $where;

	if (0 < $bonus_id) {
		$sql .= 'AND b.bonus_id = \'' . $bonus_id . '\'';
	}
	else {
		$sql .= 'AND b.bonus_sn = \'' . $bonus_sn . '\'';
	}

	return $GLOBALS['db']->getRow($sql);
}

function bonus_used($bonus_id)
{
	$sql = 'SELECT order_id FROM ' . $GLOBALS['ecs']->table('user_bonus') . (' WHERE bonus_id = \'' . $bonus_id . '\'');
	return 0 < $GLOBALS['db']->getOne($sql);
}

function use_bonus($bonus_id, $order_id)
{
	$sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_bonus') . (' SET order_id = \'' . $order_id . '\', used_time = \'') . gmtime() . '\' ' . ('WHERE bonus_id = \'' . $bonus_id . '\' LIMIT 1');
	return $GLOBALS['db']->query($sql);
}

function unuse_bonus($bonus_id)
{
	$sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_bonus') . ' SET order_id = 0, used_time = 0 ' . ('WHERE bonus_id = \'' . $bonus_id . '\' LIMIT 1');
	return $GLOBALS['db']->query($sql);
}

function value_of_integral($integral)
{
	$scale = floatval($GLOBALS['_CFG']['integral_scale']);
	return 0 < $scale ? round($integral / 100 * $scale, 2) : 0;
}

function integral_of_value($value)
{
	$scale = floatval($GLOBALS['_CFG']['integral_scale']);
	return 0 < $scale ? round($value / $scale * 100) : 0;
}

function order_refund($order, $refund_type, $refund_note, $refund_amount = 0)
{
	$user_id = $order['user_id'];
	if ($user_id == 0 && $refund_type == 1) {
		exit('anonymous, cannot return to account balance');
	}

	$amount = 0 < $refund_amount ? $refund_amount : $order['money_paid'];

	if ($amount <= 0) {
		return true;
	}

	if (!in_array($refund_type, array(1, 2, 3))) {
		exit('invalid params');
	}

	if ($refund_note) {
		$change_desc = $refund_note;
	}
	else {
		include_once LANG_PATH . C('shop.lang') . '/admin/order.php';
		$change_desc = sprintf(L('order_refund'), $order['order_sn']);
	}

	if (1 == $refund_type) {
		log_account_change($user_id, $amount, 0, 0, 0, $change_desc);
		return true;
	}
	else if (2 == $refund_type) {
		if (0 < $user_id) {
			log_account_change($user_id, $amount, 0, 0, 0, $change_desc);
		}

		$account = array('user_id' => $user_id, 'amount' => -1 * $amount, 'add_time' => gmtime(), 'user_note' => $refund_note, 'process_type' => SURPLUS_RETURN, 'admin_user' => $_SESSION['admin_name'], 'admin_note' => sprintf(L('order_refund'), $order['order_sn']), 'is_paid' => 0);
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_account'), $account, 'INSERT');
		return true;
	}
	else {
		return true;
	}
}

function get_cart_goods($cart_value = '', $type = CART_GENERAL_GOODS, $warehouse_id = 0, $area_id = 0, $area_city = 0)
{
	$goods_where = ' AND g.is_delete = 0 ';

	if ($type == CART_PRESALE_GOODS) {
		$goods_where .= ' AND g.is_on_sale = 0 ';
	}

	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$goodsIn = '';

	if (!empty($cart_value)) {
		$goodsIn = ' and c.rec_id in (' . $cart_value . ') ';
	}

	$goods_list = array();
	$total = array('goods_price' => 0, 'market_price' => 0, 'saving' => 0, 'save_rate' => 0, 'goods_amount' => 0, 'store_goods_number' => 0);

	if ($type == CART_EXCHANGE_GOODS) {
		$where = ' AND c.rec_type = \'' . CART_EXCHANGE_GOODS . '\'';
	}
	else {
		$where = ' AND c.rec_type = \'' . CART_GENERAL_GOODS . '\'';
	}

	$sql = 'SELECT c.*, IF(c.parent_id, c.parent_id, c.goods_id) AS pid, g.is_shipping, g.freight, g.tid, g.cat_id, g.brand_id, g.shipping_fee, g.cloud_id ' . ' FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON c.goods_id = g.goods_id ' . $goods_where . ' WHERE ' . $sess_id . $where . ' AND c.stages_qishu =\'-1\' AND c.store_id = 0' . $goodsIn . ' ORDER BY c.parent_id ASC, c.rec_id DESC';
	$res = $GLOBALS['db']->query($sql);
	$virtual_goods_count = 0;
	$real_goods_count = 0;
	$total['subtotal_dis_amount'] = 0;
	$total['subtotal_discount_amount'] = 0;
	$total['cart_goods_number'] = 0;
	$store_type = 0;
	$stages_qishu = 0;
	$cart_value = '';

	if (C('shop.add_shop_price') == 1) {
		$add_tocart = 1;
	}
	else {
		$add_tocart = 0;
	}

	foreach ($res as $row) {
		$nowTime = gmtime();
		$xiangouInfo = get_purchasing_goods_info($row['goods_id']);
		$start_date = $xiangouInfo['xiangou_start_date'];
		$end_date = $xiangouInfo['xiangou_end_date'];
		if ($xiangouInfo['is_xiangou'] == 1 && $start_date < $nowTime && $nowTime < $end_date) {
			$user_id = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
			$orderGoods = get_for_purchasing_goods($start_date, $end_date, $row['goods_id'], $user_id);

			if ($xiangouInfo['xiangou_num'] <= $orderGoods['goods_number']) {
				$goods_number = $xiangouInfo['xiangou_num'] - $orderGoods['goods_number'];
				$row['xiangounum'] = $goods_number;
				$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart') . (' SET goods_number = 0 WHERE rec_id = \'' . $row['rec_id'] . '\'');
				$GLOBALS['db']->query($sql);
			}
			else if (0 < $xiangouInfo['xiangou_num']) {
				if ($xiangouInfo['is_xiangou'] == 1 && $xiangouInfo['xiangou_num'] < $orderGoods['goods_number'] + $row['goods_number']) {
					$goods_number = $xiangouInfo['xiangou_num'] - $orderGoods['goods_number'];
					$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart') . (' SET goods_number = \'' . $cart_Num . '\' WHERE rec_id=\'' . $row['rec_id'] . '\'');
					$GLOBALS['db']->query($sql);
					$row['xiangounum'] = $goods_number;
				}
			}
		}

		$currency_format = !empty($GLOBALS['_CFG']['currency_format']) ? explode('%', $GLOBALS['_CFG']['currency_format']) : '';
		$attr_id = !empty($row['goods_attr_id']) ? explode(',', $row['goods_attr_id']) : '';

		if (1 < count($currency_format)) {
			if ($row['parent_id'] == 0) {
				$goods_price = trim(get_final_price($row['goods_id'], $row['goods_number'], true, $attr_id, $row['warehouse_id'], $row['area_id'], $row['area_city'], 0, 0, $add_tocart), $currency_format[0]);
			}

			$cart_price = trim($row['goods_price'], $currency_format[0]);
		}
		else {
			$goods_price = get_final_price($row['goods_id'], $row['goods_number'], true, $attr_id, $row['warehouse_id'], $row['area_id'], $row['area_city'], 0, 0, $add_tocart);
			$cart_price = $row['goods_price'];
		}

		$goods_price = floatval($goods_price);
		$cart_price = floatval($cart_price);
		if ($goods_price != $cart_price && empty($row['is_gift']) && empty($row['group_id'])) {
			$row['price_is_invalid'] = 1;
		}
		else {
			$row['price_is_invalid'] = 0;
		}

		if ($row['price_is_invalid'] && $row['rec_type'] == 0 && empty($row['is_gift']) && $row['extension_code'] != 'package_buy') {
			if (isset($_SESSION['flow_type']) && $_SESSION['flow_type'] == 0 && 0 < $goods_price) {
				get_update_cart_price($goods_price, $row['rec_id']);
				$row['goods_price'] = $goods_price;
			}
		}

		if ($row['is_invalid'] == 1) {
			dao('cart')->data(array('is_checked' => 0))->where(array('rec_id' => $row['rec_id']))->save();
		}

		if ($row['is_checked'] == 0) {
			$row['goods_amount'] = 0;
		}
		else {
			$row['goods_amount'] = $row['goods_price'] * $row['goods_number'];

			if ($cart_value) {
				$cart_value .= ',' . $row['rec_id'];
			}
			else {
				$cart_value = $row['rec_id'];
			}

			$total['cart_goods_number'] += $row['goods_number'];
		}

		$goods_con = get_con_goods_amount($row['goods_amount'], $row['goods_id'], 0, 0, $row['parent_id']);
		$goods_con['amount'] = explode(',', $goods_con['amount']);
		$row['amount'] = min($goods_con['amount']);
		$row['subtotal'] = $row['goods_amount'];
		$row['dis_amount'] = $row['goods_amount'] - $row['amount'];
		$row['dis_amount_formated'] = price_format($row['dis_amount'], false);
		$total['goods_price'] += $row['amount'];
		$total['subtotal_dis_amount'] += $row['dis_amount'];
		$total['subtotal_discount_amount'] = price_format($total['subtotal_dis_amount'], false);
		$total['market_price'] += $row['market_price'] * $row['goods_number'];
		$row['goods_price_formated'] = price_format($row['goods_price'], false);
		$row['market_price_formated'] = price_format($row['market_price'], false);
		$row['url'] = url('goods/index/index', array('id' => $row['goods_id']));
		$row['region_name'] = $GLOBALS['db']->getOne('select region_name from ' . $GLOBALS['ecs']->table('region_warehouse') . ' where region_id = \'' . $row['warehouse_id'] . '\'');

		if ($row['is_real']) {
			$real_goods_count++;
		}
		else {
			$virtual_goods_count++;
		}

		$sql = 'select count(*) from ' . $GLOBALS['ecs']->table('cart') . ' where parent_id = \'' . $row['goods_id'] . '\'';
		$row['parent_num'] = $GLOBALS['db']->getOne($sql);

		if (trim($row['goods_attr']) != '') {
			$row['goods_attr'] = addslashes($row['goods_attr']);
			$sql = 'SELECT attr_value FROM ' . $GLOBALS['ecs']->table('goods_attr') . ' WHERE goods_attr_id ' . db_create_in($row['goods_attr']);
			$attr_list = $GLOBALS['db']->getCol($sql);

			foreach ($attr_list as $attr) {
				$row['goods_name'] .= ' [' . $attr . '] ';
			}
		}

		if (($GLOBALS['_CFG']['show_goods_in_cart'] == '2' || $GLOBALS['_CFG']['show_goods_in_cart'] == '3') && $row['extension_code'] != 'package_buy') {
			$goods_thumb = $GLOBALS['db']->getOne('SELECT `goods_thumb` FROM ' . $GLOBALS['ecs']->table('goods') . (' WHERE `goods_id`=\'' . $row['goods_id'] . '\''));
			$row['goods_thumb'] = get_image_path($goods_thumb);
		}

		if ($row['extension_code'] == 'package_buy') {
			$activity = get_goods_activity_info($row['goods_id'], array('act_id', 'activity_thumb'));

			if ($activity) {
				$row['goods_thumb'] = $activity['goods_thumb'];
				$row['package_goods_list'] = get_package_goods($activity['act_id']);
			}
		}

		$sql = 'SELECT COUNT(*) FROM' . $GLOBALS['ecs']->table('store_goods') . ' WHERE goods_id =\'' . $row['goods_id'] . '\'';
		$store_count = $GLOBALS['db']->getOne($sql);

		if (0 < $store_count) {
			$store_type++;
			$row['store_type'] = 1;
		}
		else {
			$row['store_type'] = 0;
		}

		$where_area = '';

		if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
			$where_area = ' AND wag.city_id = \'' . $area_city . '\'';
		}

		if ($warehouse_id && $row['extension_code'] != 'package_buy') {
			$leftJoin = ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . (' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $warehouse_id . '\' ');
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ' . $where_area);
			$sql = 'SELECT IF(g.model_price < 1, g.goods_number, IF(g.model_price < 2, wg.region_number, wag.region_number)) AS goods_number, g.user_id, g.model_attr FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . $leftJoin . ' WHERE g.goods_id = \'' . $row['goods_id'] . '\' LIMIT 0, 1';
			$goodsInfo = $GLOBALS['db']->getRow($sql);
			$products = get_warehouse_id_attr_number($row['goods_id'], $row['goods_attr_id'], $goodsInfo['user_id'], $warehouse_id, $area_id);
			$attr_number = $products['product_number'];

			if ($goodsInfo['model_attr'] == 1) {
				$table_products = 'products_warehouse';
				$type_files = ' and warehouse_id = \'' . $warehouse_id . '\'';
			}
			else if ($goodsInfo['model_attr'] == 2) {
				$table_products = 'products_area';
				$type_files = ' and area_id = \'' . $area_id . '\'';

				if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
					$type_files .= ' AND city_id = \'' . $area_city . '\'';
				}
			}
			else {
				$table_products = 'products';
				$type_files = '';
			}

			$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table($table_products) . ' WHERE goods_id = \'' . $row['goods_id'] . '\'' . $type_files . ' LIMIT 0, 1';
			$prod = $GLOBALS['db']->getRow($sql);

			if (empty($prod)) {
				$attr_number = $GLOBALS['_CFG']['use_storage'] == 1 ? $goodsInfo['goods_number'] : 1;
			}

			if (0 < $row['cloud_id']) {
				$sql = 'SELECT product_number,cloud_product_id FROM' . $GLOBALS['ecs']->table('products') . 'WHERE product_id = \'' . $products['product_id'] . '\'';
				$product = $GLOBALS['db']->getRow($sql);
				$attr_number = get_jigon_products_stock($product);
			}

			$row['attr_number'] = isset($attr_number) && !empty($attr_number) ? $attr_number : 0;
		}
		else if ($row['extension_code'] == 'package_buy') {
			$row['attr_number'] = 1;
		}
		else {
			$row['attr_number'] = $row['goods_number'];
		}

		if (0 < $row['store_id']) {
			$row['stores_name'] = $GLOBALS['db']->getOne('SELECT stores_name FROM' . $GLOBALS['ecs']->table('offline_store') . ' WHERE id = \'' . $row['store_id'] . '\'');
		}

		$goods_list[] = $row;
	}

	$total['goods_amount'] = $total['goods_price'];
	$total['saving'] = price_format($total['market_price'] - $total['goods_price'], false);

	if (0 < $total['market_price']) {
		$total['save_rate'] = $total['market_price'] ? round(($total['market_price'] - $total['goods_price']) * 100 / $total['market_price']) . '%' : 0;
	}

	$total['goods_price'] = price_format($total['goods_price'], false);
	$total['market_price'] = price_format($total['market_price'], false);
	$total['real_goods_count'] = $real_goods_count;
	$total['virtual_goods_count'] = $virtual_goods_count;
	$total['cart_value'] = $cart_value;

	if ($type == 1) {
		$goods_list = get_cart_goods_ru_list($goods_list, $type);
		$goods_list = get_cart_ru_goods_list($goods_list);
	}

	$total['store_type'] = $store_type;
	$total['stages_qishu'] = $stages_qishu;
	return array('goods_list' => $goods_list, 'total' => $total);
}

function get_update_cart_price($goods_price = 0, $rec_id = 0)
{
	if (0 < $goods_price && 0 < $rec_id) {
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart') . (' SET goods_price = \'' . $goods_price . '\' WHERE rec_id = \'' . $rec_id . '\'');
		$GLOBALS['db']->query($sql);
	}
}

function get_cart_ru_goods_list($goods_list, $cart_value = '', $consignee = '', $store_id = 0)
{
	if (!empty($_SESSION['user_id'])) {
		$sess = $_SESSION['user_id'];
	}
	else {
		$sess = real_cart_mac_ip();
	}

	$point_id = isset($_SESSION['flow_consignee']['point_id']) ? intval($_SESSION['flow_consignee']['point_id']) : 0;
	$consignee_district_id = isset($_SESSION['flow_consignee']['district']) ? intval($_SESSION['flow_consignee']['district']) : 0;
	$arr = array();

	foreach ($goods_list as $key => $row) {
		$shipping_type = isset($_SESSION['merchants_shipping'][$key]['shipping_type']) ? intval($_SESSION['merchants_shipping'][$key]['shipping_type']) : 0;
		$ru_name = get_shop_name($key, 1);
		$arr[$key]['ru_id'] = $key;
		$arr[$key]['shipping_type'] = $shipping_type;
		$arr[$key]['ru_name'] = $ru_name;
		$arr[$key]['url'] = url('store/index/shop_about', array('ru_id' => $key));
		$arr[$key]['goods_amount'] = 0;

		foreach ($row as $gkey => $grow) {
			$arr[$key]['goods_amount'] += $grow['goods_price'] * $grow['goods_number'];
		}

		if ($cart_value) {
			$ru_shippng = get_ru_shippng_info($row, $cart_value, $key, $consignee);
			$arr[$key]['shipping'] = $ru_shippng['shipping_list'];
			$arr[$key]['is_freight'] = $ru_shippng['is_freight'];
			$arr[$key]['shipping_rec'] = $ru_shippng['shipping_rec'];
			$arr[$key]['shipping_count'] = !empty($arr[$key]['shipping']) ? count($arr[$key]['shipping']) : 0;

			if (!empty($arr[$key]['shipping'])) {
				$arr[$key]['tmp_shipping_id'] = isset($arr[$key]['shipping'][0]['shipping_id']) ? $arr[$key]['shipping'][0]['shipping_id'] : 0;

				foreach ($arr[$key]['shipping'] as $kk => $vv) {
					if ($vv['default'] == 1) {
						$arr[$key]['tmp_shipping_id'] = $vv['shipping_id'];
						continue;
					}
				}
			}
		}

		if ($key == 0 && 0 < $consignee_district_id) {
			$self_point = get_self_point($consignee_district_id, $point_id);

			if (!empty($self_point)) {
				$arr[$key]['self_point'] = $self_point;
			}
		}

		if (0 < $store_id) {
			$sql = 'SELECT o.id,o.stores_name,o.stores_address,o.stores_opening_hours,o.stores_tel,o.stores_traffic_line,p.region_name as province ,' . 'c.region_name as city ,d.region_name as district,o.stores_img FROM ' . $GLOBALS['ecs']->table('offline_store') . ' AS o ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('region') . ' AS p ON p.region_id = o.province ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('region') . ' AS c ON c.region_id = o.city ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('region') . ' AS d ON d.region_id = o.district ' . ('WHERE o.id = \'' . $store_id . '\'  LIMIT 1');
			$arr[$key]['offline_store'] = $GLOBALS['db']->getRow($sql);
		}

		if ($row) {
			$shipping_rec = isset($ru_shippng['shipping_rec']) && !empty($ru_shippng['shipping_rec']) ? $ru_shippng['shipping_rec'] : array();

			foreach ($row as $k => $v) {
				if ($shipping_rec && in_array($v['rec_id'], $shipping_rec)) {
					$row[$k]['rec_shipping'] = 0;
				}
				else {
					$row[$k]['rec_shipping'] = 1;
				}
			}
		}

		$arr[$key]['goods_list'] = $row;
		$arr[$key]['shop_goods_num'] = count($arr[$key]['goods_list']);
		$arr[$key]['coupuns_num'] = get_ru_coupuns_num($arr[$key]['ru_id']);
	}

	return array_values($arr);
}

function get_ru_shippng_info($cart_goods, $cart_value, $ru_id, $consignee = '')
{
	$cart_value_arr = array();
	$cart_freight = array();
	$shipping_rec = array();
	$freight = '';

	foreach ($cart_goods as $cgk => $cgv) {
		if ($cgv['ru_id'] != $ru_id) {
			unset($cart_goods[$cgk]);
		}
		else {
			$cart_value_list = explode(',', $cart_value);

			if (in_array($cgv['rec_id'], $cart_value_list)) {
				$cart_value_arr[] = $cgv['rec_id'];

				if ($cgv['freight'] == 2) {
					if (empty($cgv['tid'])) {
						$shipping_rec[] = $cgv['rec_id'];
					}

					@$cart_freight[$cgv['rec_id']][$cgv['freight']] = $cgv['tid'];
				}

				$freight .= $cgv['freight'] . ',';
			}
		}
	}

	if ($freight) {
		$freight = get_del_str_comma($freight);
	}

	$is_freight = 0;

	if ($freight) {
		$freight = explode(',', $freight);
		$freight = array_unique($freight);

		if (in_array(2, $freight)) {
			$is_freight = 1;
		}
	}

	$cart_value = implode(',', $cart_value_arr);

	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
	$order = flow_order_info();
	$seller_shipping = get_seller_shipping_type($ru_id);
	$shipping_id = $seller_shipping['shipping_id'];
	$consignee = isset($_SESSION['flow_consignee']) ? $_SESSION['flow_consignee'] : $consignee;
	$consignee['street'] = isset($consignee['street']) ? $consignee['street'] : 0;
	$region = array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district'], $consignee['street']);
	$insure_disabled = true;
	$cod_disabled = true;
	$where = '';

	if ($cart_value) {
		$where .= ' AND rec_id IN(' . $cart_value . ')';
	}

	$sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . ' AND `extension_code` != \'package_buy\' AND `is_shipping` = 0 AND ru_id = \'' . $ru_id . '\'' . $where;
	$shipping_count = $GLOBALS['db']->getOne($sql);
	$shipping_list = array();
	$shipping_list1 = array();
	$shipping_list2 = array();

	if ($is_freight) {
		if ($cart_freight) {
			$list1 = array();
			$list2 = array();

			foreach ($cart_freight as $key => $row) {
				if (isset($row[2]) && $row[2]) {
					$sql = 'SELECT gt.* FROM ' . $GLOBALS['ecs']->table('goods_transport') . ' AS gt WHERE gt.tid = \'' . $row[2] . '\'';
					$transport_list = $GLOBALS['db']->getAll($sql);

					foreach ($transport_list as $tkey => $trow) {
						if ($trow['freight_type'] == 1) {
							$sql = 'SELECT s.shipping_id, s.shipping_code, s.shipping_name, shipping_order FROM ' . $GLOBALS['ecs']->table('shipping') . ' AS s ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods_transport_tpl') . ' AS gtt ON s.shipping_id = gtt.shipping_id' . (' WHERE gtt.user_id = \'' . $ru_id . '\' AND s.enabled = 1 AND gtt.tid = \'') . $trow['tid'] . '\'' . ' AND (FIND_IN_SET(\'' . $region[1] . '\', gtt.region_id) OR FIND_IN_SET(\'' . $region[2] . '\', gtt.region_id) OR FIND_IN_SET(\'' . $region[3] . '\', gtt.region_id) OR FIND_IN_SET(\'' . $region[4] . '\', gtt.region_id))' . ' GROUP BY s.shipping_id';
							$shipping_list1 = $GLOBALS['db']->getAll($sql);

							if (empty($shipping_list1)) {
								$shipping_rec[] = $key;
							}

							$list1[] = $shipping_list1;
						}
						else {
							$sql = 'SELECT s.shipping_id, s.shipping_code, s.shipping_name, shipping_order FROM ' . $GLOBALS['ecs']->table('shipping') . ' AS s ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods_transport_extend') . ' AS gted ON gted.tid = \'' . $trow['tid'] . ('\' AND gted.ru_id = \'' . $ru_id . '\'') . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods_transport_express') . (' AS gte ON gted.tid = gte.tid AND gte.ru_id = \'' . $ru_id . '\'') . ' WHERE FIND_IN_SET(s.shipping_id, gte.shipping_id) ' . ' AND ((FIND_IN_SET(\'' . $region[1] . '\', gted.top_area_id)) AND (FIND_IN_SET(\'' . $region[2] . '\', gted.area_id) OR FIND_IN_SET(\'' . $region[3] . '\', gted.area_id) OR FIND_IN_SET(\'' . $region[4] . '\', gted.area_id)))' . ' GROUP BY s.shipping_id';
							$shipping_list2 = $GLOBALS['db']->getAll($sql);

							if (empty($shipping_list2)) {
								$shipping_rec[] = $key;
							}

							$list2[] = $shipping_list2;
						}
					}
				}
			}

			$shipping_list1 = get_three_to_two_array($list1);
			$shipping_list2 = get_three_to_two_array($list2);
			if ($shipping_list1 && $shipping_list2) {
				$shipping_list = array_merge($shipping_list1, $shipping_list2);
			}
			else if ($shipping_list1) {
				$shipping_list = $shipping_list1;
			}
			else if ($shipping_list2) {
				$shipping_list = $shipping_list2;
			}

			if ($shipping_list) {
				$new_shipping = array();

				foreach ($shipping_list as $key => $val) {
					@$new_shipping[$val['shipping_code']][] = $key;
				}

				foreach ($new_shipping as $key => $val) {
					if (1 < count($val)) {
						for ($i = 1; $i < count($val); $i++) {
							unset($shipping_list[$val[$i]]);
						}
					}
				}

				$shipping_list = get_array_sort($shipping_list, 'shipping_order');
			}
		}

		$configure_value = 0;
		$configure_type = 0;

		if ($shipping_list) {
			$str_shipping = '';

			foreach ($shipping_list as $key => $row) {
				$str_shipping .= $row['shipping_id'] . ',';
			}

			$str_shipping = get_del_str_comma($str_shipping);
			$str_shipping = explode(',', $str_shipping);

			if (in_array($shipping_id, $str_shipping)) {
				$have_shipping = 1;
			}
			else {
				$have_shipping = 0;
			}

			foreach ($shipping_list as $key => $val) {
				if (substr($val['shipping_code'], 0, 5) != 'ship_') {
					if ($GLOBALS['_CFG']['freight_model'] == 0) {
						if ($cart_goods) {
							if (count($cart_goods) == 1) {
								$cart_goods = array_values($cart_goods);
								if (!empty($cart_goods[0]['freight']) && $cart_goods[0]['is_shipping'] == 0) {
									if ($cart_goods[0]['freight'] == 1) {
										$configure_value = $cart_goods[0]['shipping_fee'] * $cart_goods[0]['goods_number'];
									}
									else {
										$trow = get_goods_transport($cart_goods[0]['tid']);

										if ($trow['freight_type']) {
											$cart_goods[0]['user_id'] = $cart_goods[0]['ru_id'];
											$transport_tpl = get_goods_transport_tpl($cart_goods[0], $region, $val, $cart_goods[0]['goods_number']);
											$configure_value = isset($transport_tpl['shippingFee']) ? $transport_tpl['shippingFee'] : 0;
										}
										else {
											$custom_shipping = get_goods_custom_shipping($cart_goods);
											$transport = array('top_area_id', 'area_id', 'tid', 'ru_id', 'sprice');
											$transport_where = ' AND ru_id = \'' . $cart_goods[0]['ru_id'] . '\' AND tid = \'' . $cart_goods[0]['tid'] . '\'';
											$goods_transport = $GLOBALS['ecs']->get_select_find_in_set(2, $consignee['city'], $transport, $transport_where, 'goods_transport_extend', 'area_id');
											$ship_transport = array('tid', 'ru_id', 'shipping_fee');
											$ship_transport_where = ' AND ru_id = \'' . $cart_goods[0]['ru_id'] . '\' AND tid = \'' . $cart_goods[0]['tid'] . '\'';
											$goods_ship_transport = $GLOBALS['ecs']->get_select_find_in_set(2, $val['shipping_id'], $ship_transport, $ship_transport_where, 'goods_transport_express', 'shipping_id');
											$goods_transport['sprice'] = isset($goods_transport['sprice']) ? $goods_transport['sprice'] : 0;
											$goods_ship_transport['shipping_fee'] = isset($goods_ship_transport['shipping_fee']) ? $goods_ship_transport['shipping_fee'] : 0;
											if ($custom_shipping && $trow['free_money'] <= $custom_shipping[$cart_goods[0]['tid']]['amount'] && 0 < $trow['free_money']) {
												$is_shipping = 1;
											}
											else {
												$is_shipping = 0;
											}

											if ($is_shipping == 0) {
												if ($trow['type'] == 1) {
													$configure_value = $goods_transport['sprice'] * $cart_goods[0]['goods_number'] + $goods_ship_transport['shipping_fee'] * $cart_goods[0]['goods_number'];
												}
												else {
													$configure_value = $goods_transport['sprice'] + $goods_ship_transport['shipping_fee'];
												}
											}
										}
									}
								}
								else {
									$configure_type = 1;
								}
							}
							else {
								$order_transpor = get_order_transport($cart_goods, $consignee, $val['shipping_id'], $val['shipping_code']);

								if ($order_transpor['freight']) {
									$configure_type = 1;
								}

								$configure_value = isset($order_transpor['sprice']) ? $order_transpor['sprice'] : 0;
							}
						}

						$shipping_fee = $shipping_count == 0 ? 0 : $configure_value;
						$shipping_list[$key]['free_money'] = price_format(0, false);
					}

					if ($val['shipping_code'] == 'cac') {
						$shipping_fee = 0;
					}

					$shipping_list[$key]['shipping_id'] = $val['shipping_id'];
					$shipping_list[$key]['shipping_name'] = $val['shipping_name'];
					$shipping_list[$key]['shipping_code'] = $val['shipping_code'];
					$shipping_list[$key]['format_shipping_fee'] = price_format($shipping_fee, false);
					$shipping_list[$key]['shipping_fee'] = $shipping_fee;
					if (isset($val['insure']) && $val['insure']) {
						$shipping_list[$key]['insure_formated'] = strpos($val['insure'], '%') === false ? price_format($val['insure'], false) : $val['insure'];
					}

					if ($val['shipping_id'] == $order['shipping_id']) {
						if (isset($val['insure']) && $val['insure']) {
							$insure_disabled = $val['insure'] == 0;
						}

						if (isset($val['support_cod']) && $val['support_cod']) {
							$cod_disabled = $val['support_cod'] == 0;
						}
					}

					if ($have_shipping == 1) {
						$shipping_list[$key]['default'] = 0;

						if ($shipping_id == $val['shipping_id']) {
							$shipping_list[$key]['default'] = 1;
						}
					}
					else if ($key == 0) {
						$shipping_list[$key]['default'] = 1;
					}

					$shipping_list[$key]['insure_disabled'] = $insure_disabled;
					$shipping_list[$key]['cod_disabled'] = $cod_disabled;
				}

				if (substr($val['shipping_code'], 0, 5) == 'ship_') {
					unset($shipping_list[$key]);
				}
			}

			$shipping_type = array();

			foreach ($shipping_list as $key => $val) {
				@$shipping_type[$val['shipping_code']][] = $key;
			}

			foreach ($shipping_type as $key => $val) {
				if (1 < count($val)) {
					for ($i = 1; $i < count($val); $i++) {
						unset($shipping_list[$val[$i]]);
					}
				}
			}
		}
	}
	else {
		$configure_value = 0;

		if ($cart_goods) {
			if (count($cart_goods) == 1) {
				$cart_goods = array_values($cart_goods);
				if (!empty($cart_goods[0]['freight']) && $cart_goods[0]['is_shipping'] == 0) {
					$configure_value = $cart_goods[0]['shipping_fee'] * $cart_goods[0]['goods_number'];
				}
				else {
					$configure_type = 1;
				}
			}
			else {
				$sprice = 0;

				foreach ($cart_goods as $key => $row) {
					if ($row['is_shipping'] == 0) {
						$sprice += $row['shipping_fee'] * $row['goods_number'];
					}
				}

				$configure_value = $sprice;
			}
		}

		$shipping_fee = $shipping_count == 0 ? 0 : $configure_value;

		if ($seller_shipping['shipping_code'] == 'cac') {
			$shipping_fee = 0;
		}

		$shipping_list[0]['free_money'] = price_format(0, false);
		$shipping_list[0]['format_shipping_fee'] = price_format($shipping_fee, false);
		$shipping_list[0]['shipping_fee'] = $shipping_fee;
		$shipping_list[0]['shipping_id'] = isset($seller_shipping['shipping_id']) && !empty($seller_shipping['shipping_id']) ? $seller_shipping['shipping_id'] : 0;
		$shipping_list[0]['shipping_name'] = isset($seller_shipping['shipping_name']) && !empty($seller_shipping['shipping_name']) ? $seller_shipping['shipping_name'] : '';
		$shipping_list[0]['shipping_code'] = isset($seller_shipping['shipping_code']) && !empty($seller_shipping['shipping_code']) ? $seller_shipping['shipping_code'] : '';
		$shipping_list[0]['default'] = 1;
	}

	$arr = array('is_freight' => $is_freight, 'shipping_list' => $shipping_list, 'shipping_rec' => $shipping_rec);
	return $arr;
}

function get_configure_order($configure, $value = 0, $type = 0)
{
	if ($configure) {
		foreach ($configure as $key => $val) {
			if ($val['name'] === 'base_fee') {
				if ($type == 1) {
					$configure[$key]['value'] += $value;
				}
				else {
					$configure[$key]['value'] = $value;
				}
			}
		}
	}

	return $configure;
}

function get_buy_cart_goods_number($type = CART_GENERAL_GOODS, $cart_value = '', $ru_type = 0)
{
	if ($type == CART_PRESALE_GOODS) {
		$where = ' g.is_on_sale = 0 AND g.is_delete = 0 AND ';
	}
	else {
		$where = ' g.is_on_sale = 1 AND g.is_delete = 0 AND ';
	}

	if (!empty($_SESSION['user_id'])) {
		$c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$goodsIn = '';

	if (!empty($cart_value)) {
		$goodsIn = ' and c.rec_id in(' . $cart_value . ')';
	}

	$sql = 'SELECT SUM(c.goods_number) FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . (' AS g ON c.goods_id = g.goods_id WHERE ' . $where . ' ') . $c_sess . ('AND rec_type = \'' . $type . '\'') . $goodsIn;
	return $GLOBALS['db']->getOne($sql);
}

function get_order_post_shipping($shipping, $shippingCode = array(), $shippingType = array(), $ru_id = 0)
{
	$shipping_list = array();

	if ($shipping) {
		$shipping_id = '';

		foreach ($shipping as $k1 => $v1) {
			$v1 = !empty($v1) ? intval($v1) : 0;
			$shippingCode[$k1] = !empty($shippingCode[$k1]) ? addslashes($shippingCode[$k1]) : '';
			$shippingType[$k1] = empty($shippingType[$k1]) ? 0 : intval($shippingType[$k1]);
			$shippingInfo = shipping_info($v1);

			foreach ($ru_id as $k2 => $v2) {
				if ($k1 == $k2) {
					$shipping_id .= $v2 . '|' . $v1 . ',';
					$shipping_name .= $v2 . '|' . $shippingInfo['shipping_name'] . ',';
					$shipping_code .= $v2 . '|' . $shippingCode[$k1] . ',';
					$shipping_type .= $v2 . '|' . $shippingType[$k1] . ',';
				}
			}
		}

		$shipping_id = substr($shipping_id, 0, -1);
		$shipping_name = substr($shipping_name, 0, -1);
		$shipping_code = substr($shipping_code, 0, -1);
		$shipping_type = substr($shipping_type, 0, -1);
		$shipping_list = array('shipping_id' => $shipping_id, 'shipping_name' => $shipping_name, 'shipping_code' => $shipping_code, 'shipping_type' => $shipping_type);
	}

	return $shipping_list;
}

function get_order_post_postscript($postscript, $ru_id = array())
{
	$postscript_value = '';

	if ($postscript) {
		foreach ($postscript as $k1 => $v1) {
			$v1 = !empty($v1) ? trim($v1) : '';

			foreach ($ru_id as $k2 => $v2) {
				if ($k1 == $k2) {
					$postscript_value .= $v2 . '|' . $v1 . ',';
				}
			}
		}

		$postscript_value = substr($postscript_value, 0, -1);
	}

	return $postscript_value;
}

function get_order_points($point_id_arr, $shipping_dateStr_arr, $ru_id = array())
{
	$points_info = array();

	if ($point_id_arr) {
		$point_id = '';
		$shipping_dateStr = '';

		foreach ($point_id_arr as $k1 => $v1) {
			$v1 = !empty($v1) ? $v1 : 0;
			$shipping_dateStr_arr[$k1] = !empty($shipping_dateStr_arr[$k1]) ? addslashes($shipping_dateStr_arr[$k1]) : '';

			foreach ($ru_id as $k2 => $v2) {
				if ($k1 == $k2) {
					$point_id .= $v2 . '|' . $v1 . ',';
					$shipping_dateStr .= $v2 . '|' . $shipping_dateStr_arr[$k1] . ',';
				}
			}
		}

		$point_id = substr($point_id, 0, -1);
		$shipping_dateStr = substr($shipping_dateStr, 0, -1);
		$points_info = array('point_id' => $point_id, 'shipping_dateStr' => $shipping_dateStr);
	}

	return $points_info;
}

function get_consignee($user_id)
{
	if (isset($_SESSION['flow_consignee']) && !empty($_SESSION['flow_consignee']) && isset($_SESSION['flow_consignee']['address_id'])) {
		return $_SESSION['flow_consignee'];
	}
	else {
		$arr = array();

		if (0 < $user_id) {
			$sql = 'SELECT ua.*' . ' FROM ' . $GLOBALS['ecs']->table('user_address') . 'AS ua, ' . $GLOBALS['ecs']->table('users') . ' AS u ' . (' WHERE u.user_id=\'' . $user_id . '\' AND ua.address_id = u.address_id');
			$arr = $GLOBALS['db']->getRow($sql);
		}

		return $arr;
	}
}

function exist_real_goods($order_id = 0, $flow_type = CART_GENERAL_GOODS, $cart_value = '')
{
	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	if ($order_id <= 0) {
		$where = '';

		if ($cart_value) {
			$where .= ' AND rec_id IN(' . $cart_value . ')';
		}

		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . ' AND is_real = 1 ' . ('AND rec_type = \'' . $flow_type . '\' ' . $where);
	}
	else {
		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('order_goods') . (' WHERE order_id = \'' . $order_id . '\' AND is_real = 1');
	}

	return 0 < $GLOBALS['db']->getOne($sql);
}

function check_consignee_info($consignee, $flow_type)
{
	if (exist_real_goods(0, $flow_type)) {
		$res = !empty($consignee['consignee']) && (!empty($consignee['tel']) || !empty($consignee['mobile']));

		if ($res) {
			if (empty($consignee['province'])) {
				$pro = get_regions(1, $consignee['country']);
				$res = empty($pro);
			}
			else if (empty($consignee['city'])) {
				$city = get_regions(2, $consignee['province']);
				$res = empty($city);
			}
		}

		return $res;
	}
	else {
		return !empty($consignee['consignee']) && (!empty($consignee['tel']) || !empty($consignee['mobile']));
	}
}

function last_shipping_and_payment()
{
	$sql = 'SELECT shipping_id, pay_id ' . ' FROM ' . $GLOBALS['ecs']->table('order_info') . (' WHERE user_id = \'' . $_SESSION['user_id'] . '\' ') . ' ORDER BY order_id DESC LIMIT 1';
	$row = $GLOBALS['db']->getRow($sql);

	if (empty($row)) {
		$row = array('shipping_id' => 0, 'pay_id' => 0);
	}

	return $row;
}

function get_total_bonus()
{
	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
		$c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
		$c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$day = getdate();
	$today = local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);
	$sql = 'SELECT SUM(c.goods_number * t.type_money)' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c, ' . $GLOBALS['ecs']->table('bonus_type') . ' AS t, ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . 'WHERE ' . $c_sess . 'AND c.is_gift = 0 ' . 'AND c.goods_id = g.goods_id ' . 'AND g.bonus_type_id = t.type_id ' . 'AND t.send_type = \'' . SEND_BY_GOODS . '\' ' . ('AND t.send_start_date <= \'' . $today . '\' ') . ('AND t.send_end_date >= \'' . $today . '\' ') . 'AND c.rec_type = \'' . CART_GENERAL_GOODS . '\'';
	$goods_total = floatval($GLOBALS['db']->getOne($sql));
	$sql = 'SELECT SUM(goods_price * goods_number) ' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . ' AND is_gift = 0 ' . ' AND rec_type = \'' . CART_GENERAL_GOODS . '\'';
	$amount = floatval($GLOBALS['db']->getOne($sql));
	$sql = 'SELECT FLOOR(\'' . $amount . '\' / min_amount) * type_money ' . 'FROM ' . $GLOBALS['ecs']->table('bonus_type') . ' WHERE send_type = \'' . SEND_BY_ORDER . '\' ' . (' AND send_start_date <= \'' . $today . '\' ') . ('AND send_end_date >= \'' . $today . '\' ') . 'AND min_amount > 0 ';
	$order_total = floatval($GLOBALS['db']->getOne($sql));
	return $goods_total + $order_total;
}

function change_user_bonus($bonus_id, $order_id, $is_used = true)
{
	if ($is_used) {
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_bonus') . ' SET ' . 'used_time = ' . gmtime() . ', ' . ('order_id = \'' . $order_id . '\' ') . ('WHERE bonus_id = \'' . $bonus_id . '\'');
	}
	else {
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_bonus') . ' SET ' . 'used_time = 0, ' . 'order_id = 0 ' . ('WHERE bonus_id = \'' . $bonus_id . '\'');
	}

	$GLOBALS['db']->query($sql);
}

function flow_order_info()
{
	$order = isset($_SESSION['flow_order']) ? $_SESSION['flow_order'] : array();
	if (!isset($order['shipping_id']) || !isset($order['pay_id'])) {
		if (0 < $_SESSION['user_id']) {
			$arr = last_shipping_and_payment();

			if (!isset($order['shipping_id'])) {
				$order['shipping_id'] = $arr['shipping_id'];
			}

			if (!isset($order['pay_id'])) {
				$order['pay_id'] = $arr['pay_id'];
			}
		}
		else {
			if (!isset($order['shipping_id'])) {
				$order['shipping_id'] = 0;
			}

			if (!isset($order['pay_id'])) {
				$order['pay_id'] = 0;
			}
		}
	}

	if (!isset($order['pack_id'])) {
		$order['pack_id'] = 0;
	}

	if (!isset($order['card_id'])) {
		$order['card_id'] = 0;
	}

	if (!isset($order['bonus'])) {
		$order['bonus'] = 0;
	}

	if (!isset($order['integral'])) {
		$order['integral'] = 0;
	}

	if (!isset($order['surplus'])) {
		$order['surplus'] = 0;
	}

	if (!isset($order['cou_id'])) {
		$order['cou_id'] = 0;
	}

	if (!isset($order['vc_id'])) {
		$order['vc_id'] = 0;
	}

	if (isset($_SESSION['flow_type']) && intval($_SESSION['flow_type']) != CART_GENERAL_GOODS) {
		$order['extension_code'] = $_SESSION['extension_code'];
		$order['extension_id'] = $_SESSION['extension_id'];
	}

	return $order;
}

function merge_order($from_order_sn, $to_order_sn)
{
	if (trim($from_order_sn) == '' || trim($to_order_sn) == '') {
		return L('order_sn_not_null');
	}

	if ($from_order_sn == $to_order_sn) {
		return L('two_order_sn_same');
	}

	$from_order = order_info(0, $from_order_sn);
	$to_order = order_info(0, $to_order_sn);

	if (!$from_order) {
		return sprintf(L('order_not_exist'), $from_order_sn);
	}
	else if (!$to_order) {
		return sprintf(L('order_not_exist'), $to_order_sn);
	}

	if ($from_order['extension_code'] != '' || $to_order['extension_code'] != 0) {
		return L('merge_invalid_order');
	}

	if ($from_order['order_status'] != OS_UNCONFIRMED && $from_order['order_status'] != OS_CONFIRMED) {
		return sprintf(L('os_not_unconfirmed_or_confirmed'), $from_order_sn);
	}
	else if ($from_order['pay_status'] != PS_UNPAYED) {
		return sprintf(L('ps_not_unpayed'), $from_order_sn);
	}
	else if ($from_order['shipping_status'] != SS_UNSHIPPED) {
		return sprintf(L('ss_not_unshipped'), $from_order_sn);
	}

	if ($to_order['order_status'] != OS_UNCONFIRMED && $to_order['order_status'] != OS_CONFIRMED) {
		return sprintf(L('os_not_unconfirmed_or_confirmed'), $to_order_sn);
	}
	else if ($to_order['pay_status'] != PS_UNPAYED) {
		return sprintf(L('ps_not_unpayed'), $to_order_sn);
	}
	else if ($to_order['shipping_status'] != SS_UNSHIPPED) {
		return sprintf(L('ss_not_unshipped'), $to_order_sn);
	}

	if ($from_order['user_id'] != $to_order['user_id']) {
		return L('order_user_not_same');
	}

	$order = $to_order;
	$order['order_id'] = '';
	$order['add_time'] = gmtime();
	$order['goods_amount'] += $from_order['goods_amount'];
	$order['discount'] += $from_order['discount'];

	if (0 < $order['shipping_id']) {
		$weight_price = order_weight_price($to_order['order_id']);
		$from_weight_price = order_weight_price($from_order['order_id']);
		$weight_price['weight'] += $from_weight_price['weight'];
		$weight_price['amount'] += $from_weight_price['amount'];
		$weight_price['number'] += $from_weight_price['number'];
		$region_id_list = array($order['country'], $order['province'], $order['city'], $order['district']);
		$shipping_area = shipping_area_info($order['shipping_id'], $region_id_list);
		$order['shipping_fee'] = shipping_fee($shipping_area['shipping_code'], unserialize($shipping_area['configure']), $weight_price['weight'], $weight_price['amount'], $weight_price['number']);

		if (0 < $order['insure_fee']) {
			$order['insure_fee'] = shipping_insure_fee($shipping_area['shipping_code'], $order['goods_amount'], $shipping_area['insure']);
		}
	}

	if (0 < $order['pack_id']) {
		$pack = pack_info($order['pack_id']);
		$order['pack_fee'] = $order['goods_amount'] < $pack['free_money'] ? $pack['pack_fee'] : 0;
	}

	if (0 < $order['card_id']) {
		$card = card_info($order['card_id']);
		$order['card_fee'] = $order['goods_amount'] < $card['free_money'] ? $card['card_fee'] : 0;
	}

	$order['integral'] += $from_order['integral'];
	$order['integral_money'] = value_of_integral($order['integral']);
	$order['surplus'] += $from_order['surplus'];
	$order['money_paid'] += $from_order['money_paid'];
	$order['order_amount'] = $order['goods_amount'] - $order['discount'] + $order['shipping_fee'] + $order['insure_fee'] + $order['pack_fee'] + $order['card_fee'] - $order['bonus'] - $order['integral_money'] - $order['surplus'] - $order['money_paid'];

	if (0 < $order['pay_id']) {
		$cod_fee = $shipping_area ? $shipping_area['pay_fee'] : 0;
		$order['pay_fee'] = pay_fee($order['pay_id'], $order['order_amount'], $cod_fee);
		$order['order_amount'] += $order['pay_fee'];
	}

	do {
		$order['order_sn'] = get_order_sn();

		if ($GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), addslashes_deep($order), 'INSERT')) {
			break;
		}
		else if ($GLOBALS['db']->errno() != 1062) {
			exit($GLOBALS['db']->errorMsg());
		}
	} while (true);

	$order_id = $GLOBALS['db']->insert_id();
	$sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_goods') . (' SET order_id = \'' . $order_id . '\' ') . 'WHERE order_id ' . db_create_in(array($from_order['order_id'], $to_order['order_id']));
	$GLOBALS['db']->query($sql);
	insert_pay_log($order_id, $order['order_amount'], PAY_ORDER);
	$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE order_id ' . db_create_in(array($from_order['order_id'], $to_order['order_id']));
	$GLOBALS['db']->query($sql);
	$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('pay_log') . ' WHERE order_id ' . db_create_in(array($from_order['order_id'], $to_order['order_id']));
	$GLOBALS['db']->query($sql);

	if (0 < $from_order['bonus_id']) {
		unuse_bonus($from_order['bonus_id']);
	}

	return true;
}

function get_agency_by_regions($regions)
{
	if (!is_array($regions) || empty($regions)) {
		return 0;
	}

	$arr = array();
	$sql = 'SELECT region_id, agency_id ' . 'FROM ' . $GLOBALS['ecs']->table('region') . ' WHERE region_id ' . db_create_in($regions) . ' AND region_id > 0 AND agency_id > 0';
	$res = $GLOBALS['db']->query($sql);

	foreach ($res as $row) {
		$arr[$row['region_id']] = $row['agency_id'];
	}

	if (empty($arr)) {
		return 0;
	}

	$agency_id = 0;

	for ($i = count($regions) - 1; 0 <= $i; $i--) {
		if (isset($arr[$regions[$i]])) {
			return $arr[$regions[$i]];
		}
	}
}

function& get_shipping_object($shipping_id)
{
	$shipping = shipping_info($shipping_id);

	if (!$shipping) {
		$object = new stdClass();
		return $object;
	}

	$file_path = ADDONS_PATH . 'shipping/' . $shipping['shipping_code'] . '.php';
	include_once $file_path;
	$object = new $shipping['shipping_code']();
	return $object;
}

function change_order_goods_storage($order_id, $is_dec = true, $storage = 0, $use_storage = 0, $admin_id = 0, $store_id = 0)
{
	switch ($storage) {
	case 0:
		$sql = 'SELECT goods_id, send_number AS num, extension_code, product_id, warehouse_id, area_id FROM ' . $GLOBALS['ecs']->table('order_goods') . (' WHERE order_id = \'' . $order_id . '\' AND is_real = 1 GROUP BY goods_id, product_id');
		break;

	case 1:
	case 2:
		$sql = 'SELECT goods_id, goods_number AS num, extension_code, product_id, warehouse_id, area_id FROM ' . $GLOBALS['ecs']->table('order_goods') . (' WHERE order_id = \'' . $order_id . '\' AND is_real = 1 GROUP BY goods_id, product_id');
		break;
	}

	$res = $GLOBALS['db']->query($sql);

	foreach ($res as $row) {
		if ($row['extension_code'] != 'package_buy') {
			if ($is_dec) {
				change_goods_storage($row['goods_id'], $row['product_id'], 0 - $row['num'], $row['warehouse_id'], $row['area_id'], $order_id, $use_storage, $admin_id, $store_id);
			}
			else {
				change_goods_storage($row['goods_id'], $row['product_id'], $row['num'], $row['warehouse_id'], $row['area_id'], $order_id, $use_storage, $admin_id, $store_id);
			}

			$GLOBALS['db']->query($sql);
		}
		else {
			$sql = 'SELECT goods_id, goods_number FROM ' . $GLOBALS['ecs']->table('package_goods') . ' WHERE package_id = \'' . $row['goods_id'] . '\'';
			$res_goods = $GLOBALS['db']->query($sql);

			foreach ($res_goods as $row_goods) {
				$sql = 'SELECT is_real FROM ' . $GLOBALS['ecs']->table('goods') . ' WHERE goods_id = \'' . $row_goods['goods_id'] . '\'';
				$is_goods = $GLOBALS['db']->getRow($sql);

				if ($is_dec) {
					change_goods_storage($row_goods['goods_id'], $row['product_id'], 0 - $row['num'] * $row_goods['goods_number'], $row['warehouse_id'], $row['area_id'], $order_id, $use_storage, $admin_id);
				}
				else if ($is_goods['is_real']) {
					change_goods_storage($row_goods['goods_id'], $row['product_id'], $row['num'] * $row_goods['goods_number'], $row['warehouse_id'], $row['area_id'], $order_id, $use_storage, $admin_id);
				}
			}
		}
	}
}

function change_goods_storage($goods_id = 0, $product_id = 0, $number = 0, $warehouse_id = 0, $area_id = 0, $order_id = 0, $use_storage = 0, $admin_id = 0, $store_id = 0)
{
	if ($number == 0) {
		return true;
	}

	if (empty($goods_id) || empty($number)) {
		return false;
	}

	$number = 0 < $number ? '+ ' . $number : $number;
	$sql = 'select model_inventory, model_attr from ' . $GLOBALS['ecs']->table('goods') . (' where goods_id = \'' . $goods_id . '\'');
	$goods = $GLOBALS['db']->getRow($sql);
	$sql = ' SELECT extension_code FROM ' . $GLOBALS['ecs']->table('order_goods') . (' WHERE order_id = \'' . $order_id . '\' ');
	$extension_code = $GLOBALS['db']->getOne($sql);

	if (substr($extension_code, 0, 7) == 'seckill') {
		$is_seckill = true;
		$sec_id = substr($extension_code, 7);
	}
	else {
		$is_seckill = false;
	}

	$seckill_set_update = '';
	$where = '';
	$products_query = true;
	$abs_number = abs($number);

	if (!empty($product_id)) {
		if (isset($store_id) && 0 < $store_id) {
			$table_products = 'store_products';
			$where = ' AND store_id = \'' . $store_id . '\' ';
		}
		else if ($goods['model_attr'] == 1) {
			$table_products = 'products_warehouse';
		}
		else if ($goods['model_attr'] == 2) {
			$table_products = 'products_area';
		}
		else {
			$table_products = 'products';
		}

		if ($is_seckill === true) {
			$seckill_set_update = 'IF(sec_num >= ' . $abs_number . ', sec_num ' . $number . ', 0)';
		}

		if ($number < 0) {
			$set_update = 'IF(product_number >= ' . $abs_number . ', product_number ' . $number . ', 0)';
		}
		else {
			$set_update = 'product_number ' . $number;
		}

		if ($is_seckill === true) {
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('seckill_goods') . (' SET  sec_num = ' . $seckill_set_update . ' 
                WHERE id = \'' . $sec_id . '\' 
                LIMIT 1');
		}
		else {
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table($table_products) . ('
                    SET product_number = ' . $set_update . ' 
                    WHERE goods_id = \'' . $goods_id . '\'
                    AND product_id = \'' . $product_id . '\' 
                    ' . $where . '
                    LIMIT 1');
		}

		$products_query = $GLOBALS['db']->query($sql);
	}
	else {
		if ($number < 0) {
			if (0 < $store_id) {
				$set_update = 'IF(goods_number >= ' . $abs_number . ', goods_number ' . $number . ', 0)';
			}
			else {
				if ($is_seckill === true) {
					$seckill_set_update = 'IF(sec_num >= ' . $abs_number . ', sec_num ' . $number . ', 0)';
				}

				if ($goods['model_inventory'] == 1 || $goods['model_inventory'] == 2) {
					$set_update = 'IF(region_number >= ' . $abs_number . ', region_number ' . $number . ', 0)';
				}
				else {
					$set_update = 'IF(goods_number >= ' . $abs_number . ', goods_number ' . $number . ', 0)';
				}
			}
		}
		else {
			if ($is_seckill === true) {
				$seckill_set_update = ' sec_num ' . $number . ' ';
			}

			if (0 < $store_id) {
				$set_update = 'goods_number ' . $number;
			}
			else {
				if ($goods['model_inventory'] == 1 || $goods['model_inventory'] == 2) {
					$set_update = 'region_number ' . $number;
				}
				else {
					$set_update = 'goods_number ' . $number;
				}
			}
		}

		if (0 < $store_id) {
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('store_goods') . (' SET  goods_number = ' . $set_update . ' WHERE goods_id = \'' . $goods_id . '\' AND store_id = \'' . $store_id . '\' LIMIT 1');
		}
		else if ($goods['model_inventory'] == 1) {
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('warehouse_goods') . (' SET  region_number = ' . $set_update . ' WHERE goods_id = \'' . $goods_id . '\' and region_id = \'' . $warehouse_id . '\' LIMIT 1');
		}
		else if ($goods['model_inventory'] == 2) {
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' SET  region_number = ' . $set_update . ' WHERE goods_id = \'' . $goods_id . '\' and region_id = \'' . $area_id . '\' LIMIT 1');
		}
		else {
			if ($is_seckill === true) {
				$seckill_sql = 'UPDATE ' . $GLOBALS['ecs']->table('seckill_goods') . (' SET  sec_num = ' . $seckill_set_update . ' 
                            WHERE id = \'' . $sec_id . '\' 
                            LIMIT 1');
				$GLOBALS['db']->query($seckill_sql);
			}

			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('goods') . (' SET  goods_number = ' . $set_update . ' WHERE goods_id = \'' . $goods_id . '\' LIMIT 1');
		}

		$query = $GLOBALS['db']->query($sql);
	}

	$logs_other = array('goods_id' => $goods_id, 'order_id' => $order_id, 'use_storage' => $use_storage, 'admin_id' => $admin_id, 'number' => $number, 'model_inventory' => $goods['model_inventory'], 'model_attr' => $goods['model_attr'], 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'area_id' => $area_id, 'add_time' => gmtime());
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
	if ($query && $products_query) {
		return true;
	}
	else {
		return false;
	}
}

function payment_id_list($is_cod)
{
	$sql = 'SELECT pay_id FROM ' . $GLOBALS['ecs']->table('payment');

	if ($is_cod) {
		$sql .= ' WHERE is_cod = 1';
	}
	else {
		$sql .= ' WHERE is_cod = 0';
	}

	return $GLOBALS['db']->getCol($sql);
}

function order_query_sql($type = 'finished', $alias = '')
{
	if ($type == 'finished') {
		return ' AND ' . $alias . 'order_status ' . db_create_in(array(OS_CONFIRMED, OS_SPLITED)) . (' AND ' . $alias . 'shipping_status ') . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) . (' AND ' . $alias . 'pay_status ') . db_create_in(array(PS_PAYED, PS_PAYING)) . ' ';
	}
	else if ($type == 'await_ship') {
		return ' AND   ' . $alias . 'order_status ' . db_create_in(array(OS_CONFIRMED, OS_SPLITED, OS_SPLITING_PART)) . (' AND   ' . $alias . 'shipping_status ') . db_create_in(array(SS_UNSHIPPED, SS_PREPARING, SS_SHIPPED_ING)) . (' AND ( ' . $alias . 'pay_status ') . db_create_in(array(PS_PAYED, PS_PAYING)) . (' OR ' . $alias . 'pay_id ') . db_create_in(payment_id_list(true)) . ') ';
	}
	else if ($type == 'await_pay') {
		return ' AND   ' . $alias . 'order_status ' . db_create_in(array(OS_CONFIRMED, OS_SPLITED)) . (' AND   ' . $alias . 'pay_status = \'') . PS_UNPAYED . '\'' . (' AND ( ' . $alias . 'shipping_status ') . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) . (' OR ' . $alias . 'pay_id ') . db_create_in(payment_id_list(false)) . ') ';
	}
	else if ($type == 'unconfirmed') {
		return ' AND ' . $alias . 'order_status = \'' . OS_UNCONFIRMED . '\' ';
	}
	else if ($type == 'unprocessed') {
		return ' AND ' . $alias . 'order_status ' . db_create_in(array(OS_UNCONFIRMED, OS_CONFIRMED)) . (' AND ' . $alias . 'shipping_status = \'') . SS_UNSHIPPED . '\'' . (' AND ' . $alias . 'pay_status = \'') . PS_UNPAYED . '\' ';
	}
	else if ($type == 'unpay_unship') {
		return ' AND ' . $alias . 'order_status ' . db_create_in(array(OS_UNCONFIRMED, OS_CONFIRMED)) . (' AND ' . $alias . 'shipping_status ') . db_create_in(array(SS_UNSHIPPED, SS_PREPARING)) . (' AND ' . $alias . 'pay_status = \'') . PS_UNPAYED . '\' ';
	}
	else if ($type == 'shipped') {
		return ' AND ' . $alias . 'order_status = \'' . OS_CONFIRMED . '\'' . (' AND ' . $alias . 'shipping_status ') . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) . ' ';
	}
	else {
		exit('函数 order_query_sql 参数错误');
	}
}

function order_amount_field($alias = '', $ru_id = 0)
{
	return '   ' . $alias . 'goods_amount + ' . $alias . 'tax + ' . $alias . 'shipping_fee' . (' + ' . $alias . 'insure_fee + ' . $alias . 'pay_fee + ' . $alias . 'pack_fee') . (' + ' . $alias . 'card_fee ');
}

function order_due_field($alias = '')
{
	return order_amount_field($alias) . (' - ' . $alias . 'money_paid - ' . $alias . 'surplus - ' . $alias . 'integral_money') . (' - ' . $alias . 'bonus - ' . $alias . 'discount ');
}

function compute_discount($type = 0, $newInfo = array(), $use_type = 0, $ru_id = 0, $flow_type = CART_GENERAL_GOODS)
{
	if (!empty($_SESSION['user_id'])) {
		$c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$now = gmtime();
	$user_rank = ',' . $_SESSION['user_rank'] . ',';
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('favourable_activity') . (' WHERE start_time <= \'' . $now . '\'') . (' AND end_time >= \'' . $now . '\'') . ' AND review_status = 3 ' . ' AND CONCAT(\',\', user_rank, \',\') LIKE \'%' . $user_rank . '%\'' . ' AND act_type ' . db_create_in(array(FAT_DISCOUNT, FAT_PRICE));
	$favourable_list = $GLOBALS['db']->getAll($sql);

	if (!$favourable_list) {
		return 0;
	}

	if ($type == 0 || $type == 3) {
		$where = '';

		if ($type == 3) {
			if (!empty($newInfo)) {
				$where = ' AND c.rec_id in(' . $newInfo . ')';
			}
		}

		$sql = 'SELECT c.goods_id, c.goods_price * c.goods_number AS subtotal, g.cat_id, g.brand_id, c.ru_id, c.act_id ' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c, ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . 'WHERE c.goods_id = g.goods_id ' . 'AND ' . $c_sess . 'AND c.parent_id = 0 ' . 'AND c.is_gift = 0 ' . 'AND c.is_checked = 1 ' . 'AND c.rec_type = \'' . $flow_type . '\'  ' . $where;
		$goods_list = $GLOBALS['db']->getAll($sql);
	}
	else if ($type == 2) {
		$goods_list = array();

		foreach ($newInfo as $key => $row) {
			$order_goods = $GLOBALS['db']->getRow('SELECT cat_id, brand_id FROM' . $GLOBALS['ecs']->table('goods') . ' WHERE goods_id = \'' . $row['goods_id'] . '\'');
			$goods_list[$key]['goods_id'] = $row['goods_id'];
			$goods_list[$key]['cat_id'] = $order_goods['cat_id'];
			$goods_list[$key]['brand_id'] = $order_goods['brand_id'];
			$goods_list[$key]['ru_id'] = $row['ru_id'];
			$goods_list[$key]['subtotal'] = $row['goods_price'] * $row['goods_number'];
		}
	}

	if (!$goods_list) {
		return 0;
	}

	$discount = 0;
	$favourable_name = array();

	foreach ($favourable_list as $favourable) {
		$total_amount = 0;

		if ($favourable['act_range'] == FAR_ALL) {
			foreach ($goods_list as $goods) {
				if ($goods['act_id'] == $favourable['act_id']) {
					if ($use_type == 1) {
						if ($favourable['user_id'] == $goods['ru_id']) {
							$total_amount += $goods['subtotal'];
						}
					}
					else if ($favourable['userFav_type'] == 1) {
						$total_amount += $goods['subtotal'];
					}
					else if ($favourable['user_id'] == $goods['ru_id']) {
						$total_amount += $goods['subtotal'];
					}
				}
			}
		}
		else if ($favourable['act_range'] == FAR_CATEGORY) {
			$id_list = array();
			$raw_id_list = explode(',', $favourable['act_range_ext']);

			foreach ($raw_id_list as $id) {
				$cat_keys = get_array_keys_cat(intval($id));
				$id_list = array_merge($id_list, $cat_keys);
				array_unshift($id_list, $id);
			}

			$ids = join(',', array_unique($id_list));

			foreach ($goods_list as $goods) {
				if ($goods['act_id'] == $favourable['act_id']) {
					if (strpos(',' . $ids . ',', ',' . $goods['cat_id'] . ',') !== false) {
						if ($use_type == 1) {
							if ($favourable['user_id'] == $goods['ru_id'] && $favourable['userFav_type'] == 0) {
								$total_amount += $goods['subtotal'];
							}
						}
						else if ($favourable['userFav_type'] == 1) {
							$total_amount += $goods['subtotal'];
						}
						else if ($favourable['user_id'] == $goods['ru_id']) {
							$total_amount += $goods['subtotal'];
						}
					}
				}
			}
		}
		else if ($favourable['act_range'] == FAR_BRAND) {
			foreach ($goods_list as $goods) {
				if ($goods['act_id'] == $favourable['act_id']) {
					if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['brand_id'] . ',') !== false) {
						if ($use_type == 1) {
							if ($favourable['user_id'] == $goods['ru_id']) {
								$total_amount += $goods['subtotal'];
							}
						}
						else if ($favourable['userFav_type'] == 1) {
							$total_amount += $goods['subtotal'];
						}
						else if ($favourable['user_id'] == $goods['ru_id']) {
							$total_amount += $goods['subtotal'];
						}
					}
				}
			}
		}
		else if ($favourable['act_range'] == FAR_GOODS) {
			foreach ($goods_list as $goods) {
				if ($goods['act_id'] == $favourable['act_id']) {
					if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['goods_id'] . ',') !== false) {
						if ($use_type == 1) {
							if ($favourable['user_id'] == $goods['ru_id']) {
								$total_amount += $goods['subtotal'];
							}
						}
						else if ($favourable['userFav_type'] == 1) {
							$total_amount += $goods['subtotal'];
						}
						else if ($favourable['user_id'] == $goods['ru_id']) {
							$total_amount += $goods['subtotal'];
						}
					}
				}
			}
		}
		else {
			continue;
		}

		if (0 < $total_amount && $favourable['min_amount'] <= $total_amount && ($total_amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0)) {
			if ($favourable['act_type'] == FAT_DISCOUNT) {
				$discount += $total_amount * (1 - $favourable['act_type_ext'] / 100);
				$favourable_name[] = $favourable['act_name'];
			}
			else if ($favourable['act_type'] == FAT_PRICE) {
				$discount += $favourable['act_type_ext'];
				$favourable_name[] = $favourable['act_name'];
			}
		}
	}

	return array('discount' => $discount, 'name' => $favourable_name);
}

function get_give_integral($goods = array(), $cart_value)
{
	if (!empty($_SESSION['user_id'])) {
		$c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$where = '';

	if (!empty($cart_value)) {
		$where = ' AND c.rec_id in(' . $cart_value . ')';
	}

	$sql = 'SELECT SUM(c.goods_number * IF(g.give_integral > -1, g.give_integral, c.goods_price))' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c, ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . 'WHERE c.goods_id = g.goods_id ' . 'AND ' . $c_sess . 'AND c.goods_id > 0 ' . 'AND c.parent_id = 0 ' . 'AND c.rec_type = 0 ' . 'AND c.is_gift = 0' . $where;
	return intval($GLOBALS['db']->getOne($sql));
}

function integral_to_give($order)
{
	if ($order['extension_code'] == 'group_buy') {
		include_once BASE_PATH . 'Helpers/goods_helper.php';
		$group_buy = group_buy_info(intval($order['extension_id']));
		return array('custom_points' => $group_buy['gift_integral'], 'rank_points' => $order['goods_amount']);
	}
	else {
		$sql = 'SELECT SUM(og.goods_number * IF(g.give_integral > -1, g.give_integral, og.goods_price)) AS custom_points, SUM(og.goods_number * IF(g.rank_integral > -1, g.rank_integral, og.goods_price)) AS rank_points ' . 'FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS og, ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . 'WHERE og.goods_id = g.goods_id ' . ('AND og.order_id = \'' . $order['order_id'] . '\' ') . 'AND og.goods_id > 0 ' . 'AND og.parent_id = 0 ' . 'AND og.is_gift = 0 AND og.extension_code != \'package_buy\'';
		return $GLOBALS['db']->getRow($sql);
	}
}

function send_order_bonus($order_id)
{
	$bonus_list = order_bonus($order_id);

	if ($bonus_list) {
		$sql = 'SELECT u.user_id, u.user_name, u.email ' . 'FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o, ' . $GLOBALS['ecs']->table('users') . ' AS u ' . ('WHERE o.order_id = \'' . $order_id . '\' ') . 'AND o.user_id = u.user_id ';
		$user = $GLOBALS['db']->getRow($sql);
		$count = 0;
		$money = '';

		foreach ($bonus_list as $bonus) {
			$count += $bonus['number'];
			$money .= price_format($bonus['type_money']) . ' [' . $bonus['number'] . '], ';
			$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('user_bonus') . ' (bonus_type_id, user_id) ' . ('VALUES(\'' . $bonus['type_id'] . '\', \'' . $user['user_id'] . '\')');

			for ($i = 0; $i < $bonus['number']; $i++) {
				if (!$GLOBALS['db']->query($sql)) {
					return $GLOBALS['db']->errorMsg();
				}
			}
		}

		if (0 < $count) {
			$tpl = get_mail_template('send_bonus');
			$GLOBALS['smarty']->assign('user_name', $user['user_name']);
			$GLOBALS['smarty']->assign('count', $count);
			$GLOBALS['smarty']->assign('money', $money);
			$GLOBALS['smarty']->assign('shop_name', $GLOBALS['_CFG']['shop_name']);
			$GLOBALS['smarty']->assign('send_date', local_date($GLOBALS['_CFG']['date_format']));
			$GLOBALS['smarty']->assign('sent_date', local_date($GLOBALS['_CFG']['date_format']));
			$content = $GLOBALS['smarty']->fetch('', $tpl['template_content']);
			send_mail($user['user_name'], $user['email'], $tpl['template_subject'], $content, $tpl['is_html']);
		}
	}

	return true;
}

function send_order_coupons($order_id)
{
	$order = order_info($order_id);
	$coupons_buy_info = get_coupons_type_info2(2);
	$user_rank = get_one_user_rank($order['user_id']);

	foreach ($coupons_buy_info as $k => $v) {
		$cou_ok_user = !empty($v['cou_ok_user']) ? explode(',', $v['cou_ok_user']) : '';

		if ($cou_ok_user) {
			if (!in_array($user_rank, $cou_ok_user)) {
				continue;
			}
		}
		else {
			continue;
		}

		$num = $GLOBALS['db']->getOne(' SELECT COUNT(uc_id) FROM ' . $GLOBALS['ecs']->table('coupons_user') . ' WHERE cou_id=\'' . $v['cou_id'] . '\'');

		if ($v['cou_total'] <= $num) {
			continue;
		}

		$cou_user_num = $GLOBALS['db']->getOne('SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('coupons_user') . ' WHERE user_id=\'' . $order['user_id'] . '\' AND cou_id =\'' . $v['cou_id'] . '\' AND is_use = 0');

		if ($cou_user_num < $v['cou_user_num']) {
			$sql = ' SELECT GROUP_CONCAT(og.goods_id) AS goods_id, GROUP_CONCAT(g.cat_id) AS cat_id FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS og,' . $GLOBALS['ecs']->table('goods') . ' AS g' . ' WHERE og.goods_id = g.goods_id AND order_id=\'' . $order['order_id'] . '\'';
			$goods = $GLOBALS['db']->getRow($sql);
			$goods_ids = !empty($goods['goods_id']) ? array_unique(explode(',', $goods['goods_id'])) : array();
			$goods_cats = !empty($goods['cat_id']) ? array_unique(explode(',', $goods['cat_id'])) : array();
			$flag = false;

			if ($v['cou_get_man'] <= $order['goods_amount']) {
				if ($v['cou_ok_goods']) {
					$cou_ok_goods = explode(',', $v['cou_ok_goods']);

					if ($goods_ids) {
						foreach ($goods_ids as $m => $n) {
							if (in_array($n, $cou_ok_goods)) {
								$flag = true;
								break;
							}
						}
					}
				}
				else if ($v['cou_ok_cat']) {
					$cou_ok_cat = get_cou_children($v['cou_ok_cat']);
					$cou_ok_cat = explode(',', $cou_ok_cat);

					if ($goods_cats) {
						foreach ($goods_cats as $m => $n) {
							if (in_array($n, $cou_ok_cat)) {
								$flag = true;
								break;
							}
						}
					}
				}
				else {
					$flag = true;
				}

				if ($flag) {
					$other['user_id'] = $order['user_id'];
					$other['cou_id'] = $v['cou_id'];
					$other['cou_money'] = $v['cou_money'];
					$other['uc_sn'] = $v['uc_sn'];
					$other['is_use'] = 0;
					$other['order_id'] = 0;
					$other['is_use_time'] = 0;
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('coupons_user'), $other, 'INSERT');
				}
			}
		}
	}
}

function get_one_user_rank($user_id)
{
	if (!$user_id) {
		return false;
	}

	$time = date('Y-m-d');
	$sql = 'SELECT u.user_money,u.email, u.pay_points, u.user_rank, u.rank_points, ' . ' IFNULL(b.type_money, 0) AS user_bonus, u.last_login, u.last_ip' . ' FROM ' . $GLOBALS['ecs']->table('users') . ' AS u ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('user_bonus') . ' AS ub' . ' ON ub.user_id = u.user_id AND ub.used_time = 0 ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('bonus_type') . ' AS b' . (' ON b.type_id = ub.bonus_type_id AND b.use_start_date <= \'' . $time . '\' AND b.use_end_date >= \'' . $time . '\' ') . (' WHERE u.user_id = \'' . $user_id . '\'');

	if ($row = $GLOBALS['db']->getRow($sql)) {
		if (0 < $row['user_rank']) {
			$sql = 'SELECT special_rank from ' . $GLOBALS['ecs']->table('user_rank') . ('where rank_id=\'' . $row['user_rank'] . '\'');
			if ($GLOBALS['db']->getOne($sql) === '0' || $GLOBALS['db']->getOne($sql) === NULL) {
				$sql = 'update ' . $GLOBALS['ecs']->table('users') . ('set user_rank=\'0\' where user_id=\'' . $user_id . '\'');
				$GLOBALS['db']->query($sql);
				$row['user_rank'] = 0;
			}
		}

		if ($row['user_rank'] == 0) {
			$sql = 'SELECT rank_id, discount FROM ' . $GLOBALS['ecs']->table('user_rank') . ' WHERE special_rank = \'0\' AND min_points <= \'' . intval($row['rank_points']) . '\' AND max_points > \'' . intval($row['rank_points']) . '\' LIMIT 1';

			if ($row = $GLOBALS['db']->getRow($sql)) {
				return $row['rank_id'];
			}
			else {
				return false;
			}
		}
		else {
			$sql = 'SELECT rank_id, discount FROM ' . $GLOBALS['ecs']->table('user_rank') . (' WHERE rank_id = \'' . $row['user_rank'] . '\' LIMIT 1');

			if ($row = $GLOBALS['db']->getRow($sql)) {
				return $row['rank_id'];
			}
			else {
				return false;
			}
		}
	}

	$sql = 'UPDATE ' . $GLOBALS['ecs']->table('users') . ' SET' . ' visit_count = visit_count + 1, ' . ' last_ip = \'' . real_ip() . '\',' . ' last_login = \'' . gmtime() . '\'' . ' WHERE user_id = \'' . $_SESSION['user_id'] . '\'';
	$GLOBALS['db']->query($sql);
}

function return_order_bonus($order_id)
{
	$bonus_list = order_bonus($order_id);

	if ($bonus_list) {
		$order = order_info($order_id);
		$user_id = $order['user_id'];

		foreach ($bonus_list as $bonus) {
			$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('user_bonus') . (' WHERE bonus_type_id = \'' . $bonus['type_id'] . '\' ') . ('AND user_id = \'' . $user_id . '\' ') . 'AND order_id = \'0\' LIMIT ' . $bonus['number'];
			$GLOBALS['db']->query($sql);
		}
	}
}

function order_bonus($order_id)
{
	$day = getdate();
	$today = local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);
	$sql = 'SELECT b.type_id, b.type_money, SUM(o.goods_number) AS number ' . 'FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS o, ' . $GLOBALS['ecs']->table('goods') . ' AS g, ' . $GLOBALS['ecs']->table('bonus_type') . ' AS b ' . (' WHERE o.order_id = \'' . $order_id . '\' ') . ' AND o.is_gift = 0 ' . ' AND o.goods_id = g.goods_id ' . ' AND g.bonus_type_id = b.type_id ' . ' AND b.send_type = \'' . SEND_BY_GOODS . '\' ' . (' AND b.send_start_date <= \'' . $today . '\' ') . (' AND b.send_end_date >= \'' . $today . '\' ') . ' GROUP BY b.type_id ';
	$list = $GLOBALS['db']->getAll($sql);
	$amount = order_amount($order_id, false);
	$sql = 'SELECT add_time ' . ' FROM ' . $GLOBALS['ecs']->table('order_info') . (' WHERE order_id = \'' . $order_id . '\' LIMIT 1');
	$order_time = $GLOBALS['db']->getOne($sql);
	$sql = 'SELECT type_id, type_money, IFNULL(FLOOR(\'' . $amount . '\' / min_amount), 1) AS number ' . 'FROM ' . $GLOBALS['ecs']->table('bonus_type') . 'WHERE send_type = \'' . SEND_BY_ORDER . '\' ' . ('AND send_start_date <= \'' . $order_time . '\' ') . ('AND send_end_date >= \'' . $order_time . '\' ');
	$list = array_merge($list, $GLOBALS['db']->getAll($sql));
	return $list;
}

function compute_discount_amount($cart_value = '', $flow_type = CART_GENERAL_GOODS)
{
	if (!empty($_SESSION['user_id'])) {
		$c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$now = gmtime();
	$user_rank = ',' . $_SESSION['user_rank'] . ',';
	$sql = 'SELECT *' . 'FROM ' . $GLOBALS['ecs']->table('favourable_activity') . (' WHERE start_time <= \'' . $now . '\'') . (' AND end_time >= \'' . $now . '\'') . ' AND review_status = 3 ' . ' AND CONCAT(\',\', user_rank, \',\') LIKE \'%' . $user_rank . '%\'' . ' AND act_type ' . db_create_in(array(FAT_DISCOUNT, FAT_PRICE));
	$favourable_list = $GLOBALS['db']->getAll($sql);

	if (!$favourable_list) {
		return 0;
	}

	$where = '';

	if (!empty($cart_value)) {
		$where = ' AND c.rec_id in(' . $cart_value . ')';
	}

	$sql = 'SELECT c.goods_id, c.goods_price * c.goods_number AS subtotal, g.cat_id, g.brand_id, c.ru_id ' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c, ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . 'WHERE c.goods_id = g.goods_id ' . 'AND ' . $c_sess . 'AND c.parent_id = 0 ' . 'AND c.is_gift = 0 ' . 'AND c.is_checked = 1 ' . 'AND c.rec_type = \'' . $flow_type . '\' ' . $where;
	$goods_list = $GLOBALS['db']->getAll($sql);

	if (!$goods_list) {
		return 0;
	}

	$discount = 0;
	$favourable_name = array();

	foreach ($favourable_list as $favourable) {
		$total_amount = 0;

		if ($favourable['act_range'] == FAR_ALL) {
			foreach ($goods_list as $goods) {
				if ($favourable['userFav_type'] == 1) {
					$total_amount += $goods['subtotal'];
				}
				else if ($favourable['user_id'] == $goods['ru_id']) {
					$total_amount += $goods['subtotal'];
				}
			}
		}
		else if ($favourable['act_range'] == FAR_CATEGORY) {
			$id_list = array();
			$raw_id_list = explode(',', $favourable['act_range_ext']);

			foreach ($raw_id_list as $id) {
				$id_list = array_merge($id_list, array_keys(cat_list($id, 0)));
			}

			$ids = join(',', array_unique($id_list));

			foreach ($goods_list as $goods) {
				if (strpos(',' . $ids . ',', ',' . $goods['cat_id'] . ',') !== false) {
					if ($favourable['userFav_type'] == 1) {
						$total_amount += $goods['subtotal'];
					}
					else if ($favourable['user_id'] == $goods['ru_id']) {
						$total_amount += $goods['subtotal'];
					}
				}
			}
		}
		else if ($favourable['act_range'] == FAR_BRAND) {
			foreach ($goods_list as $goods) {
				if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['brand_id'] . ',') !== false) {
					if ($favourable['userFav_type'] == 1) {
						$total_amount += $goods['subtotal'];
					}
					else if ($favourable['user_id'] == $goods['ru_id']) {
						$total_amount += $goods['subtotal'];
					}
				}
			}
		}
		else if ($favourable['act_range'] == FAR_GOODS) {
			foreach ($goods_list as $goods) {
				if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['goods_id'] . ',') !== false) {
					if ($favourable['userFav_type'] == 1) {
						$total_amount += $goods['subtotal'];
					}
					else if ($favourable['user_id'] == $goods['ru_id']) {
						$total_amount += $goods['subtotal'];
					}
				}
			}
		}
		else {
			continue;
		}

		if (0 < $total_amount && $favourable['min_amount'] <= $total_amount && ($total_amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0)) {
			if ($favourable['act_type'] == FAT_DISCOUNT) {
				$discount += $total_amount * (1 - $favourable['act_type_ext'] / 100);
			}
			else if ($favourable['act_type'] == FAT_PRICE) {
				$discount += $favourable['act_type_ext'];
			}
		}
	}

	return $discount;
}

function add_package_to_cart($package_id, $num = 1, $warehouse_id, $area_id)
{
	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
		$sess = '';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
		$sess = real_cart_mac_ip();
	}

	$GLOBALS['err']->clean();
	$package = get_package_info($package_id);

	if (empty($package)) {
		$GLOBALS['err']->add(L('goods_not_exists'), ERR_NOT_EXISTS);
		return false;
	}

	if ($package['is_on_sale'] == 0) {
		$GLOBALS['err']->add(L('not_on_sale'), ERR_NOT_ON_SALE);
		return false;
	}

	if ($GLOBALS['_CFG']['use_storage'] == '1' && judge_package_stock($package_id)) {
		$GLOBALS['err']->add(sprintf(L('shortage'), 1), ERR_OUT_OF_STOCK);
		return false;
	}

	$parent = array('user_id' => $_SESSION['user_id'], 'session_id' => $sess, 'goods_id' => $package_id, 'goods_sn' => '', 'goods_name' => addslashes($package['package_name']), 'market_price' => $package['market_package'], 'goods_price' => $package['package_price'], 'goods_number' => $num, 'goods_attr' => '', 'goods_attr_id' => '', 'warehouse_id' => $warehouse_id, 'area_id' => $area_id, 'ru_id' => $package['user_id'], 'is_real' => $package['is_real'], 'extension_code' => 'package_buy', 'is_gift' => 0, 'rec_type' => CART_GENERAL_GOODS, 'add_time' => gmtime());

	if (0 < $num) {
		$sql = 'SELECT goods_number FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . ' AND goods_id = \'' . $package_id . '\' ' . ' AND parent_id = 0 AND extension_code = \'package_buy\' ' . ' AND rec_type = \'' . CART_GENERAL_GOODS . '\' AND stages_qishu=\'-1\' AND store_id = 0 ';
		$row = $GLOBALS['db']->getRow($sql);

		if ($row) {
			$num += $row['goods_number'];
			if ($GLOBALS['_CFG']['use_storage'] == 0 || 0 < $num) {
				$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart') . ' SET goods_number = \'' . $num . '\'' . ' WHERE ' . $sess_id . (' AND goods_id = \'' . $package_id . '\' ') . ' AND parent_id = 0 AND extension_code = \'package_buy\' ' . ' AND rec_type = \'' . CART_GENERAL_GOODS . '\' AND stages_qishu=\'-1\' AND store_id = 0 ';
				$GLOBALS['db']->query($sql);
			}
			else {
				$GLOBALS['err']->add(sprintf(L('shortage'), $num), ERR_OUT_OF_STOCK);
				return false;
			}
		}
		else {
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('cart'), $parent, 'INSERT');
		}
	}

	$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . ' AND is_gift <> 0';
	$GLOBALS['db']->query($sql);
	return true;
}

function get_delivery_sn()
{
	mt_srand((double) microtime() * 1000000);
	return date('YmdHi') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

function judge_package_stock($package_id, $package_num = 1)
{
	$sql = 'SELECT goods_id, product_id, goods_number
            FROM ' . $GLOBALS['ecs']->table('package_goods') . '
            WHERE package_id = \'' . $package_id . '\'';
	$row = $GLOBALS['db']->getAll($sql);

	if (empty($row)) {
		return true;
	}

	$goods = array('product_ids' => '', 'goods_ids' => '');

	foreach ($row as $value) {
		if (0 < $value['product_id']) {
			$goods['product_ids'] .= ',' . $value['product_id'];
			continue;
		}

		$goods['goods_ids'] .= ',' . $value['goods_id'];
	}

	$model_attr = get_table_date('goods', 'goods_id = \'' . $goods_id . '\'', array('model_attr'), 2);

	if ($model_attr == 1) {
		$table_products = 'products_warehouse';
	}
	else if ($model_attr == 2) {
		$table_products = 'products_area';
	}
	else {
		$table_products = 'products';
	}

	if ($goods['product_ids'] != '') {
		$sql = 'SELECT p.product_id
                FROM ' . $GLOBALS['ecs']->table($table_products) . ' AS p, ' . $GLOBALS['ecs']->table('package_goods') . (' AS pg
                WHERE pg.product_id = p.product_id
                AND pg.package_id = \'' . $package_id . '\'
                AND pg.goods_number * ' . $package_num . ' > p.product_number
                AND p.product_id IN (') . trim($goods['product_ids'], ',') . ')';
		$row = $GLOBALS['db']->getAll($sql);

		if (!empty($row)) {
			return true;
		}
	}

	$model_inventory = get_table_date('goods', 'goods_id = \'' . $goods_id . '\'', array('model_inventory'), 2);

	if ($model_inventory == 1) {
		$table_products = 'warehouse_goods';
		$goods_number = 'g.region_number';
	}
	else if ($model_inventory == 2) {
		$table_products = 'warehouse_area_goods';
		$goods_number = 'g.region_number';
	}
	else {
		$table_products = 'goods';
		$goods_number = 'g.goods_number';
	}

	if ($goods['goods_ids'] != '') {
		$sql = 'SELECT g.goods_id
                FROM ' . $GLOBALS['ecs']->table($table_products) . 'AS g, ' . $GLOBALS['ecs']->table('package_goods') . (' AS pg
                WHERE pg.goods_id = g.goods_id
                AND pg.goods_number * ' . $package_num . ' > ') . $goods_number . '
                AND pg.package_id = \'' . $package_id . '\'
                AND pg.goods_id IN (' . trim($goods['goods_ids'], ',') . ')';
		$row = $GLOBALS['db']->getAll($sql);

		if (!empty($row)) {
			return true;
		}
	}

	return false;
}

function free_price($shipping_config)
{
	$shipping_config = unserialize($shipping_config);
	$arr = array();

	if (is_array($shipping_config)) {
		foreach ($shipping_config as $key => $value) {
			foreach ($value as $k => $v) {
				$arr['configure'][$value['name']] = $value['value'];
			}
		}
	}

	return $arr;
}

function return_order_info_byId($order_id, $refound = true)
{
	if (!$refound) {
		$sql = ' SELECT count(*) FROM ' . $GLOBALS['ecs']->table('order_return') . ' WHERE order_id=' . $order_id . ' AND refound_status = 0';
		$res = $GLOBALS['db']->getOne($sql);
	}
	else {
		$sql = ' SELECT * FROM ' . $GLOBALS['ecs']->table('order_return') . ' WHERE order_id=' . $order_id;
		$res = $GLOBALS['db']->getAll($sql);
	}

	return $res;
}

function return_order_info($ret_id, $order_sn = '', $order_id = 0)
{
	$ret_id = intval($ret_id);

	if (0 < $ret_id) {
		$sql = 'SELECT og.extension_code FROM ' . $GLOBALS['ecs']->table('order_return') . ' o' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('order_goods') . ' og ON og.rec_id = o.rec_id' . (' WHERE o.ret_id = \'' . $ret_id . '\'');
		$res = $GLOBALS['db']->getRow($sql);
		$field = '';
		$where = '';

		if ($res['extension_code'] == 'package_buy') {
			$field = 'g.activity_thumb as goods_thumb , g.act_name as goods_name,';
			$where = ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods_activity') . ' as g ON g.act_id=r.goods_id ';
		}
		else {
			$field = 'g.goods_thumb , g.goods_name ,g.shop_price , g.user_id as ru_id, ';
			$where = ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' as g ON g.goods_id=r.goods_id ';
		}

		$sql = 'SELECT r.* ,' . $field . '  o.order_sn ,o.add_time ,  d.delivery_sn , d.update_time , d.how_oos ,d.shipping_fee, d.insure_fee ,' . ' rg.return_number ' . '  FROM' . $GLOBALS['ecs']->table('order_return') . ' as r LEFT JOIN  ' . $GLOBALS['ecs']->table('goods_attr') . ' as ga ON r.goods_id = ga.goods_id ' . $where . ' LEFT JOIN ' . $GLOBALS['ecs']->table('return_goods') . ' as rg ON r.rec_id=rg.rec_id ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('order_info') . ' as o ON o.order_id = r.order_id' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('delivery_order') . ' as d ON d.order_id = o.order_id ' . (' WHERE r.ret_id = \'' . $ret_id . '\'');
	}
	else {
		if ($order_id) {
			$where2 = 'order_id = \'' . $order_id . '\'';
		}
		else {
			$where2 = 'order_sn = \'' . $order_sn . '\'';
		}

		$sql = 'SELECT *  FROM ' . $GLOBALS['ecs']->table('order_return') . ' WHERE ' . $where2;
	}

	$lang_ff = L('ff');
	$lang_rf = L('rf');
	$order = $GLOBALS['db']->getRow($sql);

	if (empty($order)) {
		show_message('找不到退换单', '退换货列表', url('index'));
	}

	if (!empty($order)) {
		if (isset($res['extension_code']) && $res['extension_code'] == 'package_buy') {
			$order['extension_code'] = 'package_buy';
		}

		if (0 < $order['discount']) {
			$discount_percent = $order['discount'] / $order['goods_amount'];
			$order['discount_percent_decimal'] = number_format($discount_percent, 2, '.', '');
			$order['discount_percent'] = $order['discount_percent_decimal'] * 100;
		}
		else {
			$order['discount_percent_decimal'] = 0;
			$order['discount_percent'] = 0;
		}

		$order['apply_time'] = local_date($GLOBALS['_CFG']['time_format'], $order['apply_time']);
		$order['formated_update_time'] = local_date($GLOBALS['_CFG']['time_format'], $order['update_time']);
		$order['formated_add_time'] = local_date($GLOBALS['_CFG']['time_format'], $order['add_time']);
		$order['insure_yn'] = empty($order['insure_fee']) ? 0 : 1;
		$order['discount_amount'] = number_format($order['should_return'] * $order['discount_percent_decimal'], 2, '.', '');
		$order['should_return1'] = number_format($order['should_return'] - $order['discount_amount'], 2, '.', '');
		$order['formated_goods_amount'] = price_format($order['should_return'], false);
		$order['formated_discount_amount'] = price_format($order['discount_amount'], false);
		$order['formated_should_return'] = price_format($order['should_return'] - $order['discount_amount'], false);
		$order['formated_return_shipping_fee'] = price_format($order['return_shipping_fee'], false);
		$order['formated_return_amount'] = price_format($order['should_return'] + $order['return_shipping_fee'] - $order['discount_amount'], false);
		$order['formated_actual_return'] = price_format($order['actual_return'], false);
		$order['return_status1'] = $lang_rf[$order['return_status']];
		$order['shop_price'] = price_format($order['shop_price'], false);

		if ($order['return_type'] == 0) {
			if ($order['return_status'] == 4) {
				$order['refound_status'] = FF_MAINTENANCE;
			}
			else {
				$order['refound_status'] = FF_NOMAINTENANCE;
			}
		}
		else if ($order['return_type'] == 1) {
			if ($order['refound_status'] == 1) {
				$order['refound_status'] = FF_REFOUND;
			}
			else {
				$order['refound_status'] = FF_NOREFOUND;
			}
		}
		else if ($order['return_type'] == 2) {
			if ($order['return_status'] == 4) {
				$order['refound_status'] = FF_EXCHANGE;
			}
			else {
				$order['refound_status'] = FF_NOEXCHANGE;
			}
		}
		else if ($order['return_type'] == 3) {
			if ($order['refound_status'] == 1) {
				$order['refound_status'] = FF_REFOUND;
			}
			else {
				$order['refound_status'] = FF_NOREFOUND;
			}
		}

		$order['refound_status1'] = $lang_ff[$order['refound_status']];
		$order['address_detail'] = str_replace('[', '', str_replace(']', '', get_consignee_info($order['order_id'], $order['address'])));
		$order['goods_thumb'] = get_image_path($order['goods_thumb']);
		$sql = 'SELECT cause_name ' . 'FROM ' . $GLOBALS['ecs']->table('return_cause') . ' WHERE cause_id=( SELECT parent_id FROM  ' . $GLOBALS['ecs']->table('return_cause') . ' WHERE cause_id = \'' . $order['cause_id'] . '\')';
		$parent = $GLOBALS['db']->getOne($sql);
		$sql = 'SELECT c.cause_name ' . 'FROM ' . $GLOBALS['ecs']->table('return_cause') . ' AS c ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('return_cause') . ' AS s ON s.parent_id=c.cause_id WHERE c.cause_id=' . $order['cause_id'];
		$child = $GLOBALS['db']->getOne($sql);

		if ($parent) {
			$order['return_cause'] = $parent . '-' . $child;
		}
		else {
			$order['return_cause'] = $child;
		}

		if ($order['return_status'] == REFUSE_APPLY) {
			$order['action_note'] = $GLOBALS['db']->getOne('SELECT action_note FROM ' . $GLOBALS['ecs']->table('return_action') . 'WHERE ret_id = \'' . $order['ret_id'] . '\' AND return_status=\'' . REFUSE_APPLY . '\' order by log_time DESC LIMIT 1');
		}

		if (!empty($order['back_other_shipping'])) {
			$order['back_shipp_shipping'] = $order['back_other_shipping'];
		}
		else {
			$order['back_shipp_shipping'] = !empty($order['back_shipping_name']) ? get_shipping_name($order['back_shipping_name']) : '';
		}

		if ($order['out_shipping_name']) {
			$order['out_shipp_shipping'] = get_shipping_name($order['out_shipping_name']);
		}

		$goods_price = $GLOBALS['db']->getOne('SELECT goods_price FROM ' . $GLOBALS['ecs']->table('order_goods') . (' WHERE order_id = \'' . $order['order_id'] . '\' AND goods_id = \'' . $order['goods_id'] . '\''));
		$order['goods_price'] = price_format($goods_price, false);
		$sql = 'select img_file from ' . $GLOBALS['ecs']->table('return_images') . ' where user_id = \'' . $order['user_id'] . '\' and rec_id = \'' . $order['rec_id'] . '\' order by id desc';

		if ($getImage = $GLOBALS['db']->getCol($sql)) {
			foreach ($getImage as $key => $val) {
				$getImage[$key] = get_image_path($val);
			}
		}

		$order['img_list'] = $getImage;
		$order['img_count'] = count($order['img_list']);

		if ($GLOBALS['_CFG']['customer_service'] == 0) {
			$ru_id = 0;
		}
		else {
			$ru_id = $order['ru_id'];
		}

		$shop_information = get_shop_name($ru_id);
		$order['is_IM'] = $shop_information['is_IM'];
		$order['shop_name'] = get_shop_name($ru_id, 1);
	}

	return $order;
}

function get_shipping_name($shipping_id = 0)
{
	$sql = 'SELECT shipping_name FROM ' . $GLOBALS['ecs']->table('shipping') . ' WHERE shipping_id =' . $shipping_id;
	$shipping_name = $GLOBALS['db']->getOne($sql);
	return $shipping_name;
}

function get_return_goods($ret_id)
{
	$ret_id = intval($ret_id);
	$sql = 'SELECT rg.*, g.goods_thumb, g.brand_id FROM ' . $GLOBALS['ecs']->table('return_goods') . ' as rg  LEFT JOIN ' . $GLOBALS['ecs']->table('order_return') . 'as r ON rg.rec_id = r.rec_id ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = rg.goods_id ' . ' WHERE r.ret_id = ' . $ret_id;
	$res = $GLOBALS['db']->query($sql);

	foreach ($res as $row) {
		$row['refound'] = price_format($row['refound'], false);
		$goods_list[] = $row;
	}

	return $goods_list;
}

function get_return_order_goods($rec_id)
{
	$sql = 'select * FROM ' . $GLOBALS['ecs']->table('order_goods') . ' WHERE rec_id =' . $rec_id;
	$goods_list = $GLOBALS['db']->getAll($sql);
	return $goods_list;
}

function get_return_order_goods1($rec_id)
{
	$sql = 'select * FROM ' . $GLOBALS['ecs']->table('order_goods') . ' WHERE rec_id =' . $rec_id;
	$goods_list = $GLOBALS['db']->getRow($sql);
	return $goods_list;
}

function get_return_refound($order_id, $rec_id, $num)
{
	$orders = $GLOBALS['db']->getRow(' SELECT money_paid, goods_amount, surplus, shipping_fee FROM ' . $GLOBALS['ecs']->table('order_info') . (' WHERE order_id = \'' . $order_id . '\''));
	$return_orders = $GLOBALS['db']->getRow('SELECT SUM(return_shipping_fee) AS return_shipping_fee FROM ' . $GLOBALS['ecs']->table('order_return') . (' WHERE order_id = \'' . $order_id . '\' AND return_type IN(1, 3)'));
	$sql = 'SELECT goods_number, goods_price, (goods_number * goods_price) AS goods_amount FROM ' . $GLOBALS['ecs']->table('order_goods') . (' WHERE rec_id = \'' . $rec_id . '\'');
	$res = $GLOBALS['db']->getRow($sql);
	if ($res && $res['goods_number'] < $num || empty($num)) {
		$num = $res['goods_number'];
	}

	$return_price = $num * $res['goods_price'];
	$return_shipping_fee = $orders['shipping_fee'] - $return_orders['return_shipping_fee'];

	if (0 < $return_price) {
		$return_price = number_format($return_price, 2, '.', '');
	}

	if (0 < $return_shipping_fee) {
		$return_shipping_fee = number_format($return_shipping_fee, 2, '.', '');
	}

	$arr = array('return_price' => $return_price, 'return_shipping_fee' => $return_shipping_fee);
	return $arr;
}

function return_order($order_id = 0, $size = 10, $page = 1)
{
	$where = '';

	if (0 < $order_id) {
		$where = ' AND o.order_id = ' . $order_id;
	}

	$map = array('user_id' => $_SESSION['user_id']);

	if (!empty($order_id)) {
		$map['order_id'] = $order_id;
	}

	$total = dao('order_return')->where($map)->count();
	$goodsSql = '(SELECT CONCAT(goods_thumb, \',\', goods_name) FROM ' . $GLOBALS['ecs']->table('goods') . ' WHERE goods_id = o.goods_id)';
	$actSql = '(SELECT CONCAT(activity_thumb, \',\', act_name)  FROM ' . $GLOBALS['ecs']->table('goods_activity') . ' WHERE act_id = o.goods_id) ';
	$sql = 'SELECT o.ret_id , o.rec_id, o.goods_id , o.order_sn, o.order_id, o.apply_time, o.agree_apply , o.should_return, o.return_status , o.refound_status, o.return_type, o.return_sn, rg.return_number, o.activation_number, IF(og.extension_code = \'package_buy\', ' . $actSql . ', ' . $goodsSql . ') AS goods_info ' . ' FROM ' . $GLOBALS['ecs']->table('order_return') . ' AS o' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('order_goods') . ' AS og ON og.rec_id = o.rec_id' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('return_goods') . ' AS rg ON rg.ret_id = o.ret_id' . ' WHERE o.user_id = \'' . $_SESSION['user_id'] . '\' ' . $where . ' GROUP BY rg.ret_id order by o.ret_id DESC';
	$res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);
	$lang_ff = L('ff');
	$lang_rf = L('rf');

	foreach ($res as $row) {
		$goodsInfo = explode(',', $row['goods_info']);
		$row['goods_thumb'] = $goodsInfo[0];
		$row['goods_name'] = $goodsInfo[1];
		$row['apply_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['apply_time']);
		$row['should_return'] = price_format($row['should_return'], false);
		$row['order_status'] = '';
		if ($row['return_status'] == 0 && $row['refound_status'] == 0) {
			$row['order_status'] .= '<span>' . L('user_return') . '</span>';
			$row['refound_cancel_url'] = url('user/refound/cancel', array('ret_id' => $row['ret_id']));
		}
		else if ($row['return_status'] == 1) {
			$row['order_status'] .= '<span>' . L('get_goods') . '</span>';
		}
		else if ($row['return_status'] == 2) {
			$row['order_status'] .= '<span>' . L('send_alone') . '</span>';
		}
		else if ($row['return_status'] == 3) {
			$row['order_status'] .= '<span>' . L('send') . '</span>';
		}
		else if ($row['return_status'] == 4) {
			$row['order_status'] .= '<span>' . L('complete') . '</span>';
		}
		else if ($row['return_status'] == 6) {
			$row['order_status'] .= '<span>' . $lang_rf[$row['return_status']] . '</span>';
		}

		$row['reimburse_status'] = '';

		if ($row['return_type'] == 0) {
			if ($row['return_status'] == 4) {
				$row['reimburse_status'] = $lang_ff[FF_MAINTENANCE];
			}
			else {
				$row['reimburse_status'] = $lang_ff[FF_NOMAINTENANCE];
			}
		}
		else if ($row['return_type'] == 1) {
			if ($row['refound_status'] == 1) {
				$row['reimburse_status'] = $lang_ff[FF_REFOUND];
			}
			else {
				$row['reimburse_status'] = $lang_ff[FF_NOREFOUND];
			}
		}
		else if ($row['return_type'] == 2) {
			if ($row['return_status'] == 4) {
				$row['reimburse_status'] = $lang_ff[FF_EXCHANGE];
			}
			else {
				$row['reimburse_status'] = $lang_ff[FF_NOEXCHANGE];
			}
		}
		else if ($row['return_type'] == 3) {
			if ($row['refound_status'] == 1) {
				$row['reimburse_status'] = $lang_ff[FF_REFOUND];
			}
			else {
				$row['reimburse_status'] = $lang_ff[FF_NOREFOUND];
			}
		}

		if ($row['return_status'] == 6) {
			$row['reimburse_status'] = $lang_rf[$row['return_status']];
		}

		$activation_number_type = 0 < C('shop.activation_number_type') ? C('shop.activation_number_type') : 2;
		$row['activation_type'] = 0;

		if ($row['return_status'] == 6) {
			if ($row['activation_number'] < $activation_number_type) {
				$row['activation_type'] = 1;
			}

			$row['agree_apply'] = -1;
		}

		$row['goods_thumb'] = get_image_path($row['goods_thumb']);
		$row['goods_url'] = url('goods/index/index', array('id' => $row['goods_id']));
		$row['refound_detail_url'] = url('user/refound/detail', array('ret_id' => $row['ret_id'], 'order_id' => $row['order_id']));
		$goods_list[] = $row;
	}

	return array('list' => $goods_list, 'totalpage' => ceil($total / $size));
}

function get_return_action($ret_id)
{
	$act_list = array();
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('return_action') . ' WHERE ret_id = \'' . $ret_id . '\'  ORDER BY log_time DESC,ret_id DESC';
	$res = $GLOBALS['db']->query($sql);
	$lang_rf = L('rf');
	$lang_ff = L('ff');

	foreach ($res as $row) {
		$row['return_status'] = $lang_rf[$row['return_status']];
		$row['refound_status'] = $lang_ff[$row['refound_status']];
		$row['action_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['log_time']);
		$act_list[] = $row;
	}

	return $act_list;
}

function rec_goods($rec_id)
{
	$sql = 'SELECT rec_id, goods_id, goods_name, goods_sn, market_price, goods_number, ' . 'goods_price, goods_attr, is_real, parent_id, is_gift, ' . 'goods_price * goods_number AS subtotal, extension_code ' . 'FROM ' . $GLOBALS['ecs']->table('order_goods') . (' WHERE rec_id = \'' . $rec_id . '\'');
	$res = $GLOBALS['db']->getRow($sql);

	if ($res['extension_code'] == 'package_buy') {
		$res['package_goods_list'] = get_package_goods($res['goods_id']);
	}

	$res['market_price'] = price_format($res['market_price'], false);
	$res['goods_price1'] = $res['goods_price'];
	$res['goods_price'] = price_format($res['goods_price'], false);
	$res['subtotal'] = price_format($res['subtotal'], false);
	$sql = 'select goods_img, goods_thumb, user_id from ' . $GLOBALS['ecs']->table('goods') . ' where goods_id = \'' . $res['goods_id'] . '\'';
	$goods = $GLOBALS['db']->getRow($sql);
	$data = array('shoprz_brandName', 'shop_class_keyWords', 'shopNameSuffix');
	$shop_info = get_table_date('merchants_shop_information', 'user_id = \'' . $goods['user_id'] . '\'', $data);
	$res['user_name'] = $shop_info['shoprz_brandName'] . $shop_info['shopNameSuffix'];
	$sql = 'select * from ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' where ru_id=\'' . $goods['user_id'] . '\'';
	$basic_info = $GLOBALS['db']->getRow($sql);
	$res['kf_type'] = $basic_info['kf_type'];
	$res['kf_ww'] = $basic_info['kf_ww'];
	$res['kf_qq'] = $basic_info['kf_qq'];
	$res['goods_img'] = get_image_path($goods['goods_img']);
	$res['goods_thumb'] = get_image_path($goods['goods_thumb']);
	return $res;
}

function get_is_refound($rec_id)
{
	$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('order_return') . ' WHERE rec_id=' . $rec_id;
	$is_refound = 0;

	if ($GLOBALS['db']->getOne($sql)) {
		$is_refound = 1;
	}

	return $is_refound;
}

function get_refound($rec_id)
{
	$sql = 'SELECT ret_id FROM ' . $GLOBALS['ecs']->table('order_return') . ' WHERE rec_id=' . $rec_id;
	$ret_id = $GLOBALS['db']->getOne($sql);
	return $ret_id;
}

function order_refund1($order, $refund_type, $refound_amount, $refund_note, $refund_amount = 0)
{
	$user_id = $order['user_id'];
	if ($user_id == 0 && $refund_type == 1) {
		exit('anonymous, cannot return to account balance');
	}

	$amount = 0 < $refound_amount ? $refound_amount : $order['money_paid'];

	if ($amount <= 0) {
		return true;
	}

	if (!in_array($refund_type, array(1, 2, 3))) {
		exit('invalid params');
	}

	if ($refund_note) {
		$change_desc = $refund_note;
	}
	else {
		include_once LANG_PATH . C('shop.lang') . '/admin/order.php';
		$change_desc = sprintf(L('order_refund'), $order['order_sn']);
	}

	if (1 == $refund_type) {
		log_account_change($user_id, $amount, 0, 0, 0, $change_desc);
		return true;
	}
	else if (2 == $refund_type) {
		return true;
	}
	else if (22222 == $refund_type) {
		if (0 < $user_id) {
			log_account_change($user_id, $amount, 0, 0, 0, $change_desc);
		}

		$account = array('user_id' => $user_id, 'amount' => -1 * $amount, 'add_time' => gmtime(), 'user_note' => $refund_note, 'process_type' => SURPLUS_RETURN, 'admin_user' => $_SESSION['admin_name'], 'admin_note' => sprintf(L('order_refund'), $order['order_sn']), 'is_paid' => 0);
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_account'), $account, 'INSERT');
		return true;
	}
	else {
		return true;
	}
}

function return_surplus_integral_bonus($user_id, $goods_price, $return_goods_price)
{
	$sql = ' SELECT pay_points  FROM ' . $GLOBALS['ecs']->table('users') . ' WHERE user_id=' . $user_id;
	$pay = $GLOBALS['db']->getOne($sql);
	$pay = $pay - $goods_price + $return_goods_price;

	if (0 < $pay) {
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('users') . ' SET pay_points =' . $pay;
		$GLOBALS['db']->query($sql);
	}
}

function update_zc_project($order_id = 0)
{
	$sql = ' SELECT user_id, is_zc_order, zc_goods_id FROM ' . $GLOBALS['ecs']->table('order_info') . (' WHERE order_id = \'' . $order_id . '\' ');
	$order_info = $GLOBALS['db']->getRow($sql);
	$user_id = $order_info['user_id'];
	$is_zc_order = $order_info['is_zc_order'];
	$zc_goods_id = $order_info['zc_goods_id'];
	if ($is_zc_order == 1 && 0 < $zc_goods_id) {
		$sql = ' select * from ' . $GLOBALS['ecs']->table('zc_goods') . (' where id = \'' . $zc_goods_id . '\' ');
		$zc_goods_info = $GLOBALS['db']->getRow($sql);
		$pid = $zc_goods_info['pid'];
		$goods_price = $zc_goods_info['price'];
		$sql = ' UPDATE ' . $GLOBALS['ecs']->table('zc_goods') . (' SET backer_num = backer_num+1 WHERE id = \'' . $zc_goods_id . '\' ');
		$GLOBALS['db']->query($sql);
		$sql = 'SELECT backer_list FROM ' . $GLOBALS['ecs']->table('zc_goods') . (' WHERE id = \'' . $zc_goods_id . '\'');
		$backer_list = $GLOBALS['db']->getOne($sql);

		if (empty($backer_list)) {
			$backer_list = $user_id;
		}
		else {
			$backer_list = $backer_list . ',' . $user_id;
		}

		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('zc_goods') . (' SET backer_list=\'' . $backer_list . '\' WHERE id = \'' . $zc_goods_id . '\'');
		$GLOBALS['db']->query($sql);
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('zc_project') . (' SET join_num=join_num+1, join_money=join_money+' . $goods_price . ' WHERE id = \'' . $pid . '\'');
		$GLOBALS['db']->query($sql);
	}
}

function getStoresName($id)
{
	$sql = 'SELECT stores_name FROM ' . $GLOBALS['ecs']->table('offline_store') . ' WHERE id = ' . $id;
	$res = $GLOBALS['db']->getRow($sql);
	return $res['stores_name'];
}

function get_virtual_goods_info($rec_id = 0)
{
	$sql = ' SELECT vc.* FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS og ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('order_info') . ' AS oi ON oi.order_id = og.order_id ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('virtual_card') . ' AS vc ON vc.order_sn = oi.order_sn ' . (' WHERE og.goods_id = vc.goods_id AND vc.is_saled = 1  AND og.rec_id = \'' . $rec_id . '\' ');
	$virtual_info = $GLOBALS['db']->getAll($sql);
	$res = array();

	if ($virtual_info) {
		foreach ($virtual_info as $row) {
			$res['card_sn'] = _decrypt($row['card_sn']);
			$res['card_password'] = _decrypt($row['card_password']);
			$res['end_date'] = local_date($GLOBALS['_CFG']['date_format'], $row['end_date']);
			$virtual[] = $res;
		}
	}

	return $virtual;
}

function get_user_value_card($user_id, $cart_goods, $cart_value)
{
	$arr = array();
	$sql = ' SELECT v.vid, t.use_merchants FROM ' . $GLOBALS['ecs']->table('value_card_type') . ' AS t LEFT JOIN ' . $GLOBALS['ecs']->table('value_card') . (' AS v ON v.tid = t.id WHERE v.user_id = \'' . $user_id . '\' ');
	$use_merchants = $GLOBALS['db']->getAll($sql);
	$shop_ids = array();

	if ($use_merchants) {
		foreach ($use_merchants as $val) {
			if ($val['use_merchants'] == 'all') {
				$sql = ' SELECT user_id FROM ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' WHERE merchants_audit = 1 ';
				$res = $GLOBALS['db']->getAll($sql);
				$shop_ids[$val['vid']][0] = 0;

				if ($res) {
					foreach ($res as $v) {
						$shop_ids[$val['vid']][] = $v['user_id'];
					}
				}
			}
			else if ($val['use_merchants'] == 'self') {
				$sql = ' SELECT user_id FROM ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' WHERE merchants_audit = 1 AND self_run = 1 ';
				$res = $GLOBALS['db']->getAll($sql);
				$shop_ids[$val['vid']][0] = 0;

				if ($res) {
					foreach ($res as $v) {
						$shop_ids[$val['vid']][] = $v['user_id'];
					}
				}
			}
			else if ($val['use_merchants'] != '') {
				$shop_ids[$val['vid']] = explode(',', $val['use_merchants']);
			}
			else if ($val['use_merchants'] == '') {
				$shop_ids[$val['vid']][] = 0;
			}
		}
	}

	foreach ($cart_goods as $val) {
		foreach ($shop_ids as $k => $v) {
			if (!in_array($val['ru_id'], $v)) {
				unset($shop_ids[$k]);
			}
		}
	}

	if (empty($shop_ids)) {
		return array('is_value_cart' => 0);
	}
	else {
		$value_card_ids = implode(',', array_keys($shop_ids));
	}

	if (0 < $user_id) {
		$where = ' WHERE 1 ';
		$where .= ' AND vc.user_id = \'' . $user_id . '\' AND vc.card_money > 0 AND vid IN (' . $value_card_ids . ') ';
		$sql = ' SELECT t.name, t.use_condition, t.spec_goods, t.spec_cat ,t.vc_dis, vc.* FROM ' . $GLOBALS['ecs']->table('value_card') . ' AS vc ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('value_card_type') . ' AS t ON vc.tid = t.id ' . $where;
		$result = $GLOBALS['db']->getAll($sql);

		foreach ($result as $k => $v) {
			if (empty($v['use_condition'])) {
				$arr[$k]['vc_id'] = $v['vid'];
				$arr[$k]['vc_dis'] = $v['vc_dis'];
				$arr[$k]['name'] = $v['name'];
				$arr[$k]['card_money'] = $v['card_money'];
				$arr[$k]['card_money_format'] = price_format($v['card_money']);
				$arr[$k]['value_card_sn'] = $v['value_card_sn'];
				$arr[$k]['vc_value'] = price_format($v['vc_value']);
				$arr[$k]['bind_time'] = local_date(C('shop.time_format'), $v['bind_time']);
				$arr[$k]['end_time'] = local_date(C('shop.time_format'), $v['end_time']);
			}
			else if ($v['use_condition'] == 1) {
				if (comparison_cat($cart_goods, $v['spec_cat'])) {
					$arr[$k]['vc_id'] = $v['vid'];
					$arr[$k]['name'] = $v['name'];
					$arr[$k]['card_money'] = $v['card_money'];
					$arr[$k]['card_money_format'] = price_format($v['card_money']);
					$arr[$k]['value_card_sn'] = $v['value_card_sn'];
					$arr[$k]['vc_value'] = price_format($v['vc_value']);
					$arr[$k]['bind_time'] = local_date(C('shop.time_format'), $v['bind_time']);
					$arr[$k]['end_time'] = local_date(C('shop.time_format'), $v['end_time']);
				}
			}
			else if ($v['use_condition'] == 2) {
				if (comparison_goods($cart_goods, $v['spec_goods'])) {
					$arr[$k]['vc_id'] = $v['vid'];
					$arr[$k]['name'] = $v['name'];
					$arr[$k]['card_money'] = $v['card_money'];
					$arr[$k]['card_money_format'] = price_format($v['card_money']);
					$arr[$k]['value_card_sn'] = $v['value_card_sn'];
					$arr[$k]['vc_value'] = price_format($v['vc_value']);
					$arr[$k]['bind_time'] = local_date(C('shop.time_format'), $v['bind_time']);
					$arr[$k]['end_time'] = local_date(C('shop.time_format'), $v['end_time']);
				}
			}
		}
	}

	return $arr;
}

function comparison_goods($cart_goods, $spec_goods)
{
	$spec_goods = explode(',', $spec_goods);
	$error = 0;

	foreach ($cart_goods as $v) {
		if (!in_array($v['goods_id'], $spec_goods)) {
			$error += 1;
		}
	}

	if (0 < $error) {
		return false;
	}
	else {
		return true;
	}
}

function comparison_cat($cart_goods, $spec_cat)
{
	$spec_cat = explode(',', $spec_cat);
	$error = 0;

	foreach ($spec_cat as $v) {
		$cat_keys = get_array_keys_cat($v);
		$cat[] = array_unique(array_merge(array($v), $cat_keys));
	}

	foreach ($cat as $v) {
		foreach ($v as $val) {
			$arr[] = $val;
		}
	}

	$arr = array_unique($arr);

	foreach ($cart_goods as $v) {
		if (!in_array($v['cat_id'], $arr)) {
			$error += 1;
		}
	}

	if (0 < $error) {
		return false;
	}
	else {
		return true;
	}
}

function value_card_info($value_card_id, $value_card_psd = '', $cart_value = 0)
{
	$where = '';
	$sql = 'SELECT t.*, vc.user_id as admin_id, vc.* ' . 'FROM ' . $GLOBALS['ecs']->table('value_card_type') . ' AS t,' . $GLOBALS['ecs']->table('value_card') . ' AS vc ' . 'WHERE t.id = vc.tid ' . $where;

	if (0 < $value_card_id) {
		$sql .= 'AND vc.vid = \'' . $value_card_id . '\'';
	}
	else {
		$sql .= ' AND vc.value_card_password = \'' . $value_card_psd . '\' AND vc.user_id = 0 ';
	}

	return $GLOBALS['db']->getRow($sql);
}

function get_cost_price($goods_id)
{
	$sql = ' SELECT cost_price FROM ' . $GLOBALS['ecs']->table('goods') . (' WHERE goods_id = \'' . $goods_id . '\' ');
	return $GLOBALS['db']->getOne($sql);
}

function goods_cost_price($order_id)
{
	$sql = ' SELECT og.goods_id,og.goods_number FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS oi LEFT JOIN ' . $GLOBALS['ecs']->table('order_goods') . (' AS og ON og.order_id = oi.order_id  WHERE oi.order_id = \'' . $order_id . '\' ');
	$res = $GLOBALS['db']->getAll($sql);
	$cost_amount = 0;

	foreach ($res as $v) {
		$cost_amount += get_cost_price($v['goods_id']) * $v['goods_number'];
	}

	return $cost_amount;
}

function get_team_info($team_id = 0, $order_id = 0)
{
	$time = gmtime();
	$sql = 'select tg.team_num,tg.validity_time,tg.is_team,tl.start_time,tl.status,oi.order_status,oi.pay_status from ' . $GLOBALS['ecs']->table('team_log') . ' as tl LEFT JOIN' . $GLOBALS['ecs']->table('order_info') . 'as oi ON tl.team_id = oi.team_id' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('team_goods') . ' AS tg ON tl.t_id = tg.id  where tl.team_id = \'' . $team_id . '\' and oi.order_id = \'' . $order_id . '\'';
	$team_info = $GLOBALS['db']->getRow($sql);
	$end_time = $team_info['start_time'] + $team_info['validity_time'] * 3600;
	if ($time < $end_time && $team_info['status'] == 1 && $team_info['pay_status'] != 2 && $team_info['order_status'] != 4) {
		$failure = 1;
	}
	else {
		if ($end_time < $time && $team_info['status'] == 1 && $team_info['pay_status'] != 2 && $team_info['order_status'] != 4) {
			$failure = 1;
		}
		else {
			if (($end_time < $time || $time < $end_time) && $team_info['status'] != 1 && $team_info['order_status'] == 2) {
				$failure = 1;
			}
			else {
				if ($end_time < $time && $team_info['status'] != 1 && $team_info['pay_status'] != 2 && $team_info['order_status'] != 2) {
					$failure = 1;
				}
				else {
					if ($team_info['status'] != 1 && ($end_time < $time || $team_info['is_team'] != 1)) {
						$failure = 1;
					}
					else {
						$failure = 0;
					}
				}
			}
		}
	}

	return $failure;
}

function order_refund_online($order, $action_type = 0, $refund_note = '', $refund_amount = 0)
{
	$ret_id = $order['ret_id'];
	$rec_id = $order['rec_id'];
	$user_id = $order['user_id'];

	if ($user_id == 0) {
		exit('anonymous, cannot return to account balance');
	}

	$amount = 0 < $refund_amount ? $refund_amount : $order['money_paid'];

	if ($amount <= 0) {
		return true;
	}

	if ($refund_note) {
		$change_desc = $refund_note;
	}
	else {
		$change_desc = sprintf('订单退款：%s', $order['order_sn']);
	}

	if (1 == $action_type) {
		dao('order_return')->data(array('agree_apply' => 1))->where(array('rec_id' => $order['rec_id']))->save();
		return_action($ret_id, RF_AGREE_APPLY, '', $change_desc, L('buyer'));
		return true;
	}
	else if (2 == $action_type) {
		$order_goods = order_goods($order['order_id']);

		foreach ($order_goods as $key => $value) {
			$array_rec_id[] = $value['rec_id'];
		}

		$return_info = return_order_info_byid($order['order_id']);

		foreach ($return_info as $key => $value) {
			$array_rec_id1[] = $value['rec_id'];
		}

		$order_info = get_order_detail($order['order_id']);

		if (!array_diff($array_rec_id, $array_rec_id1)) {
			$return_count = return_order_info_byid($order['order_id'], 0);

			if ($return_count == 1) {
				if ($order_info['bonus']) {
					$sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_bonus') . ' SET used_time = \'\' , order_id = \'\' WHERE order_id = ' . $order['order_id'];
					$GLOBALS['db']->query($sql);
				}

				unuse_coupons($order_info);
				return_card_money($order['order_id']);
			}
		}

		if (1 < count($order_goods)) {
			foreach ($order_goods as $k => $v) {
				$all_goods_id[] = $v['goods_id'];
			}

			$count_integral = $GLOBALS['db']->getOne(' SELECT sum(integral) FROM' . $GLOBALS['ecs']->table('goods') . ' WHERE  goods_id' . db_create_in($all_goods_id));
			$return_integral = $GLOBALS['db']->getOne(' SELECT g.integral FROM' . $GLOBALS['ecs']->table('goods') . ' as g LEFT JOIN ' . $GLOBALS['ecs']->table('order_return') . (' as o on o.goods_id = g.goods_id  WHERE o.ret_id = \'' . $ret_id . '\''));
			$count_integral = !empty($count_integral) ? $count_integral : 1;
			$return_ratio = $return_integral / $count_integral;
			$return_price = (empty($order_info['pay_points']) ? '' : $order_info['pay_points']) * $return_ratio;
		}
		else {
			$return_price = empty($order_info['pay_points']) ? '' : $order_info['pay_points'];
		}

		$goods_number = $GLOBALS['db']->getOne(' SELECT goods_number FROM ' . $GLOBALS['ecs']->table('order_goods') . (' WHERE rec_id = \'' . $rec_id . '\''));
		$return_number = $GLOBALS['db']->getOne(' SELECT return_number FROM ' . $GLOBALS['ecs']->table('order_return_extend') . (' WHERE ret_id = \'' . $ret_id . '\''));

		if ($return_number < $goods_number) {
			$refound_pay_points = intval($return_price * ($return_number / $goods_number));
		}
		else {
			$refound_pay_points = intval($return_price);
		}

		if (0 < $refound_pay_points) {
			log_account_change($order['user_id'], 0, 0, 0, $refound_pay_points, ' 订单退款，退回订单 ' . $order['order_sn'] . ' 购买的积分');
		}

		return_integral_rank($ret_id, $order['user_id'], $order['order_sn'], $rec_id, $refound_pay_points);
		$is_shipping_money = false;
		$shippingFee = $is_shipping_money && isset($order_info['shipping_fee']) ? $order_info['shipping_fee'] : '';

		if ($order['pay_status'] != PS_UNPAYED) {
			$return_goods = get_return_order_goods1($rec_id);
			$return_info = return_order_info($ret_id);
			$refund_amount = $amount + $shippingFee;
			$get_order_arr = get_order_arr($return_info['return_number'], $return_info['rec_id'], $order_goods, $order, $refund_amount);
			update_order($order['order_id'], $get_order_arr);
			$return_status = array('refound_status' => 1, 'actual_return' => $refund_amount, 'return_shipping_fee' => $shippingFee, 'return_time' => gmtime());
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_return'), $return_status, 'UPDATE', 'rec_id = \'' . $rec_id . '\'');
		}

		if (C('shop.use_storage') == '1') {
			$admin_id = dao('admin_user')->where(array('parent_id' => 0, 'ru_id' => 0))->getField('user_id');

			if (C('shop.stock_dec_time') == SDT_SHIP) {
				change_order_goods_storage($order['order_id'], false, SDT_SHIP, 6, $admin_id);
			}
			else if (C('shop.stock_dec_time') == SDT_PLACE) {
				change_order_goods_storage($order['order_id'], false, SDT_PLACE, 6, $admin_id);
			}
		}

		return_action($ret_id, '', FF_REFOUND, $refund_note);
		return true;
	}
	else {
		return true;
	}
}

function unuse_coupons($order_info)
{
	if ($order_info['coupons']) {
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('coupons_user') . ' SET order_id = 0, is_use_time = 0, is_use=0 ' . 'WHERE order_id = \'' . $order_info['order_id'] . '\' LIMIT 1';
		return $GLOBALS['db']->query($sql);
	}
}

function return_card_money($order_id)
{
	$sql = ' SELECT use_val,vc_id FROM ' . $GLOBALS['ecs']->table('value_card_record') . (' WHERE order_id = \'' . $order_id . '\' LIMIT 1 ');
	$row = $GLOBALS['db']->getRow($sql);

	if ($row) {
		$sql = ' UPDATE ' . $GLOBALS['ecs']->table('value_card') . (' SET card_money = card_money + \'' . $row['use_val'] . '\' WHERE vid = \'' . $row['vc_id'] . '\' ');
	}

	return $GLOBALS['db']->query($sql);
}

function return_integral_rank($ret_id = 0, $user_id = 0, $order_sn = 0, $rec_id = 0, $refound_pay_points = 0)
{
	$sql = 'SELECT IF(g.give_integral != -1,g.give_integral*o.return_number,org.goods_price*o.return_number) as give_integral , IF(g.rank_integral != -1,g.rank_integral*o.return_number,org.goods_price*o.return_number) as rank_integral FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('order_return') . ' AS ord ON ord.goods_id = g.goods_id ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('order_goods') . (' AS org ON org.rec_id = \'' . $rec_id . '\' ') . 'LEFT JOIN ' . $GLOBALS['ecs']->table('order_return_extend') . (' AS o ON o.ret_id = ord.ret_id  WHERE o.ret_id = \'' . $ret_id . '\'');
	$return_integral = $GLOBALS['db']->getRow($sql);
	$gave_custom_points = $return_integral['give_integral'];

	if (!empty($return_integral)) {
		$lang_return_order_gift_integral = '由于退货或未发货操作，退回订单 %s 赠送的积分';
		log_account_change($user_id, 0, 0, '-' . $return_integral['rank_integral'], '-' . $gave_custom_points, sprintf($lang_return_order_gift_integral, $order_sn), ACT_OTHER, 1);
		return NULL;
	}
}

function clear_store_goods()
{
	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $sess_id . ' AND store_id > 0';
	$GLOBALS['db']->query($sql);
}

function get_order_arr($goods_number_return = 0, $rec_id = 0, $order_goods = array(), $order_info = array(), $refund_amount = 0)
{
	$goods_number = 0;
	$goods_count = count($order_goods);
	$i = 1;

	foreach ($order_goods as $k => $v) {
		if ($rec_id == $v['rec_id']) {
			$goods_number = $v['goods_number'];
		}

		$sql = 'SELECT ret_id FROM' . $GLOBALS['ecs']->table('order_return') . ' WHERE rec_id = \'' . $v['rec_id'] . '\' AND order_id = \'' . $v['order_id'] . '\' AND refound_status = 1';

		if (0 < $GLOBALS['db']->getOne($sql)) {
			$i++;
		}
	}

	if ($goods_number_return < $goods_number || $i < $goods_count) {
		$arr = array('order_status' => OS_RETURNED_PART);
	}
	else {
		$arr = array('order_status' => OS_RETURNED, 'pay_status' => PS_UNPAYED, 'shipping_status' => SS_UNSHIPPED, 'money_paid' => 0, 'invoice_no' => '', 'order_amount' => 0);
	}

	return $arr;
}

function get_user_order_coupons($order_id, $ru_id = 0, $type = 0)
{
	$where = '';

	if ($type) {
		$where .= ' AND ru_id = \'' . $ru_id . '\'';
	}

	$sql = 'SELECT cu.*, c.cou_name, c.cou_money FROM ' . $GLOBALS['ecs']->table('coupons_user') . ' AS cu, ' . $GLOBALS['ecs']->table('coupons') . ' AS c ' . (' WHERE cu.cou_id = c.cou_id AND order_id = \'' . $order_id . '\' ' . $where . ' LIMIT 1');
	return $GLOBALS['db']->getRow($sql);
}

function get_goods_custom_shipping($goods_list)
{
	$tid_arr1 = array();

	foreach ($goods_list as $key => $row) {
		$tid_arr1[$row['tid']][$key] = $row;
	}

	$tid_arr2 = array();

	foreach ($tid_arr1 as $key => $row) {
		$row = !empty($row) ? array_values($row) : $row;
		$tid_arr2[$key]['weight'] = 0;
		$tid_arr2[$key]['number'] = 0;
		$tid_arr2[$key]['amount'] = 0;

		foreach ($row as $gkey => $grow) {
			$tid_arr2[$key]['weight'] += $grow['goodsweight'] * $grow['goods_number'];
			$tid_arr2[$key]['number'] += $grow['goods_number'];
			$tid_arr2[$key]['amount'] += $grow['goods_price'] * $grow['goods_number'];
		}
	}

	return $tid_arr2;
}

function cart_by_favourable($merchant_goods, $act_sel_id = array())
{
	$id_list = array();
	$list_array = array();
	$cart_value['act_sel_id'] = !empty($act_sel_id) ? explode(',', $act_sel_id) : array();

	foreach ($merchant_goods as $key => $row) {
		$user_cart_goods = isset($row['goods_list']) && !empty($row['goods_list']) ? $row['goods_list'] : array();
		$favourable_list = favourable_list($_SESSION['user_rank'], $row['ru_id']);
		$sort_favourable = sort_favourable($favourable_list);

		if ($user_cart_goods) {
			foreach ($user_cart_goods as $goods_key => $goods_row) {
				$goods_row['original_price'] = $goods_row['goods_price'] * $goods_row['goods_number'];
				if (isset($sort_favourable['by_all']) && $goods_row['extension_code'] != 'package_buy' && substr($goods_row['extension_code'], 0, 7) != 'seckill') {
					foreach ($sort_favourable['by_all'] as $fav_key => $fav_row) {
						if ($goods_row['act_id'] == $fav_row['act_id'] || empty($goods_row['act_id'])) {
							$mer_ids = true;

							if ($GLOBALS['_CFG']['region_store_enabled']) {
								$mer_ids = get_favourable_merchants($fav_row['userFav_type'], $fav_row['userFav_type_ext'], $fav_row['rs_id'], 1, $goods_row['ru_id']);
							}

							if ($fav_row['userFav_type'] == 1 || $mer_ids) {
								if (isset($goods_row) && $goods_row) {
									if ($goods_row['is_gift'] == 0 && $goods_row['parent_id'] == 0) {
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_id'] = $fav_row['act_id'];
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_name'] = $fav_row['act_name'];
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type'] = $fav_row['act_type'];

										switch ($fav_row['act_type']) {
										case 0:
											$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('with_a_gift');
											$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = intval($fav_row['act_type_ext']);
											break;

										case 1:
											$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('full_reduction');
											$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = number_format($fav_row['act_type_ext'], 2);
											break;

										case 2:
											$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('discount');
											$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = floatval($fav_row['act_type_ext'] / 10);
											break;

										default:
											break;
										}

										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['min_amount'] = $fav_row['min_amount'];
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext'] = intval($fav_row['act_type_ext']);
										@$merchant_goods[$key]['new_list'][$fav_row['act_id']]['cart_fav_amount'] += $goods_row['subtotal'];
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['available'] = favourable_available($fav_row, $cart_value, $goods_row['ru_id']);
										$cart_favourable = cart_favourable($goods_row['ru_id']);
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$fav_row['act_id']]) ? 0 : intval($cart_favourable[$fav_row['act_id']]);
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['favourable_used'] = favourable_used($fav_row, $cart_favourable);
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['left_gift_num'] = intval($fav_row['act_type_ext']) - (empty($cart_favourable[$fav_row['act_id']]) ? 0 : intval($cart_favourable[$fav_row['act_id']]));

										if ($fav_row['gift']) {
											$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_gift_list'] = $fav_row['gift'];
										}

										if (empty($goods_row['act_id']) && $goods_row['rec_type'] == CART_ONESTEP_GOODS) {
											update_cart_goods_fav($goods_row['rec_id'], $fav_row['act_id']);
										}

										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_goods_list'][$goods_row['rec_id']] = $goods_row;
									}

									if ($goods_row['is_gift'] == $fav_row['act_id']) {
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_cart_gift'][$goods_row['rec_id']] = $goods_row;
									}

									unset($goods_row);
								}
							}
							else if ($GLOBALS['_CFG']['region_store_enabled']) {
								$merchant_goods[$key]['new_list'][0]['act_goods_list'][$goods_row['rec_id']] = $goods_row;
							}

							break;
						}

						continue;
					}
				}

				if (isset($sort_favourable['by_category']) && $goods_row['extension_code'] != 'package_buy' && substr($goods_row['extension_code'], 0, 7) != 'seckill') {
					$get_act_range_ext = get_act_range_ext($_SESSION['user_rank'], $row['ru_id'], 1);
					$str_cat = '';

					foreach ($get_act_range_ext as $id) {
						$cat_keys = get_array_keys_cat(intval($id));

						if ($cat_keys) {
							$str_cat .= implode(',', $cat_keys);
						}
					}

					if ($str_cat) {
						$list_array = explode(',', $str_cat);
					}

					$list_array = !empty($list_array) ? array_merge($get_act_range_ext, $list_array) : $get_act_range_ext;
					$id_list = arr_foreach($list_array);
					$id_list = array_unique($id_list);
					$cat_id = $goods_row['cat_id'];
					$favourable_id_list = get_favourable_id($sort_favourable['by_category']);
					if (in_array($cat_id, $id_list) && $goods_row['is_gift'] == 0 || in_array($goods_row['is_gift'], $favourable_id_list)) {
						foreach ($sort_favourable['by_category'] as $fav_key => $fav_row) {
							if (isset($goods_row) && $goods_row && ($goods_row['act_id'] == $fav_row['act_id'] || empty($goods_row['act_id']))) {
								$fav_act_range_ext = !empty($fav_row['act_range_ext']) ? explode(',', $fav_row['act_range_ext']) : array();

								foreach ($fav_act_range_ext as $id) {
									$cat_keys = get_array_keys_cat(intval($id));
									$fav_act_range_ext = array_merge($fav_act_range_ext, $cat_keys);
								}

								if ($goods_row['is_gift'] == 0 && in_array($cat_id, $fav_act_range_ext)) {
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_id'] = $fav_row['act_id'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_name'] = $fav_row['act_name'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type'] = $fav_row['act_type'];

									switch ($fav_row['act_type']) {
									case 0:
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('with_a_gift');
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = intval($fav_row['act_type_ext']);
										break;

									case 1:
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('full_reduction');
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = number_format($fav_row['act_type_ext'], 2);
										break;

									case 2:
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('discount');
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = floatval($fav_row['act_type_ext'] / 10);
										break;

									default:
										break;
									}

									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['min_amount'] = $fav_row['min_amount'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext'] = intval($fav_row['act_type_ext']);
									@$merchant_goods[$key]['new_list'][$fav_row['act_id']]['cart_fav_amount'] += $goods_row['subtotal'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['available'] = favourable_available($fav_row, $cart_value, $goods_row['ru_id']);
									$cart_favourable = cart_favourable($goods_row['ru_id']);
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$fav_row['act_id']]) ? 0 : intval($cart_favourable[$fav_row['act_id']]);
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['favourable_used'] = favourable_used($fav_row, $cart_favourable);
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['left_gift_num'] = intval($fav_row['act_type_ext']) - (empty($cart_favourable[$fav_row['act_id']]) ? 0 : intval($cart_favourable[$fav_row['act_id']]));

									if ($fav_row['gift']) {
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_gift_list'] = $fav_row['gift'];
									}

									if (empty($goods_row['act_id']) && $goods_row['rec_type'] == CART_ONESTEP_GOODS) {
										update_cart_goods_fav($goods_row['rec_id'], $fav_row['act_id']);
									}

									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_goods_list'][$goods_row['rec_id']] = $goods_row;
									unset($goods_row);
								}

								if (isset($goods_row) && $goods_row && $goods_row['is_gift'] == $fav_row['act_id']) {
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_cart_gift'][$goods_row['rec_id']] = $goods_row;
								}
							}

							continue;
						}
					}
				}

				if (isset($sort_favourable['by_brand']) && $goods_row['extension_code'] != 'package_buy' && substr($goods_row['extension_code'], 0, 7) != 'seckill') {
					$get_act_range_ext = get_act_range_ext($_SESSION['user_rank'], $row['ru_id'], 2);
					$brand_id = $goods_row['brand_id'];
					$favourable_id_list = get_favourable_id($sort_favourable['by_brand']);
					if (in_array(trim($brand_id), $get_act_range_ext) && $goods_row['is_gift'] == 0 || in_array($goods_row['is_gift'], $favourable_id_list)) {
						foreach ($sort_favourable['by_brand'] as $fav_key => $fav_row) {
							$act_range_ext_str = ',' . $fav_row['act_range_ext'] . ',';
							$brand_id_str = ',' . $brand_id . ',';
							if (isset($goods_row) && $goods_row && ($goods_row['act_id'] == $fav_row['act_id'] || empty($goods_row['act_id']))) {
								if ($goods_row['is_gift'] == 0 && strstr($act_range_ext_str, trim($brand_id_str))) {
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_id'] = $fav_row['act_id'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_name'] = $fav_row['act_name'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type'] = $fav_row['act_type'];

									switch ($fav_row['act_type']) {
									case 0:
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('with_a_gift');
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = intval($fav_row['act_type_ext']);
										break;

									case 1:
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('full_reduction');
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = number_format($fav_row['act_type_ext'], 2);
										break;

									case 2:
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('discount');
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = floatval($fav_row['act_type_ext'] / 10);
										break;

									default:
										break;
									}

									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['min_amount'] = $fav_row['min_amount'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext'] = intval($fav_row['act_type_ext']);
									@$merchant_goods[$key]['new_list'][$fav_row['act_id']]['cart_fav_amount'] += $goods_row['subtotal'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['available'] = favourable_available($fav_row, $cart_value);
									$cart_favourable = cart_favourable($goods_row['ru_id']);
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$fav_row['act_id']]) ? 0 : intval($cart_favourable[$fav_row['act_id']]);
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['favourable_used'] = favourable_used($fav_row, $cart_favourable);
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['left_gift_num'] = intval($fav_row['act_type_ext']) - (empty($cart_favourable[$fav_row['act_id']]) ? 0 : intval($cart_favourable[$fav_row['act_id']]));

									if ($fav_row['gift']) {
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_gift_list'] = $fav_row['gift'];
									}

									if (empty($goods_row['act_id']) && $goods_row['rec_type'] == CART_ONESTEP_GOODS) {
										update_cart_goods_fav($goods_row['rec_id'], $fav_row['act_id']);
									}

									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_goods_list'][$goods_row['rec_id']] = $goods_row;
									unset($goods_row);
								}

								if (isset($goods_row) && $goods_row && $goods_row['is_gift'] == $fav_row['act_id']) {
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_cart_gift'][$goods_row['rec_id']] = $goods_row;
								}
							}
						}

						continue;
					}
				}

				if (isset($sort_favourable['by_goods']) && $goods_row['extension_code'] != 'package_buy' && substr($goods_row['extension_code'], 0, 7) != 'seckill') {
					$get_act_range_ext = get_act_range_ext($_SESSION['user_rank'], $row['ru_id'], 3);
					$favourable_id_list = get_favourable_id($sort_favourable['by_goods']);
					if (in_array($goods_row['goods_id'], $get_act_range_ext) || in_array($goods_row['is_gift'], $favourable_id_list)) {
						foreach ($sort_favourable['by_goods'] as $fav_key => $fav_row) {
							$act_range_ext_str = ',' . $fav_row['act_range_ext'] . ',';
							$goods_id_str = ',' . $goods_row['goods_id'] . ',';
							if (isset($goods_row) && $goods_row && ($goods_row['act_id'] == $fav_row['act_id'] || empty($goods_row['act_id']))) {
								if (strstr($act_range_ext_str, $goods_id_str) && $goods_row['is_gift'] == 0) {
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_id'] = $fav_row['act_id'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_name'] = $fav_row['act_name'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type'] = $fav_row['act_type'];

									switch ($fav_row['act_type']) {
									case 0:
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('with_a_gift');
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = intval($fav_row['act_type_ext']);
										break;

									case 1:
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('full_reduction');
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = number_format($fav_row['act_type_ext'], 2);
										break;

									case 2:
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_txt'] = L('discount');
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext_format'] = floatval($fav_row['act_type_ext'] / 10);
										break;

									default:
										break;
									}

									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['min_amount'] = $fav_row['min_amount'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_type_ext'] = intval($fav_row['act_type_ext']);
									@$merchant_goods[$key]['new_list'][$fav_row['act_id']]['cart_fav_amount'] += $goods_row['subtotal'];
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['available'] = favourable_available($fav_row, $cart_value);
									$cart_favourable = cart_favourable($goods_row['ru_id']);
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$fav_row['act_id']]) ? 0 : intval($cart_favourable[$fav_row['act_id']]);
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['favourable_used'] = favourable_used($fav_row, $cart_favourable);
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['left_gift_num'] = intval($fav_row['act_type_ext']) - (empty($cart_favourable[$fav_row['act_id']]) ? 0 : intval($cart_favourable[$fav_row['act_id']]));

									if ($fav_row['gift']) {
										$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_gift_list'] = $fav_row['gift'];
									}

									if (empty($goods_row['act_id']) && $goods_row['rec_type'] == CART_ONESTEP_GOODS) {
										update_cart_goods_fav($goods_row['rec_id'], $fav_row['act_id']);
									}

									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_goods_list'][$goods_row['rec_id']] = $goods_row;
									unset($goods_row);
									break;
								}

								if (isset($goods_row) && $goods_row && $goods_row['is_gift'] == $fav_row['act_id']) {
									$merchant_goods[$key]['new_list'][$fav_row['act_id']]['act_cart_gift'][$goods_row['rec_id']] = $goods_row;
								}
							}

							continue;
						}
					}
				}

				if ($goods_row && $goods_row['is_gift'] == 0) {
					$merchant_goods[$key]['new_list'][0]['act_goods_list'][$goods_row['rec_id']] = $goods_row;
				}
			}
		}
	}

	return $merchant_goods;
}

function favourable_list($user_rank, $user_id = -1, $fav_id = 0, $act_sel_id = array(), $ru_id = -1)
{
	$where = '';

	if (0 <= $user_id) {
		$where .= ' AND IF(userFav_type = 0, user_id = \'' . $user_id . '\', 1 = 1) ';
	}

	if (0 < $fav_id) {
		$where .= ' AND act_id = \'' . $fav_id . '\' ';
	}

	$used_list = cart_favourable($ru_id);
	$favourable_list = array();
	$user_rank = ',' . $user_rank . ',';
	$now = gmtime();
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('favourable_activity') . ' WHERE CONCAT(\',\', user_rank, \',\') LIKE \'%' . $user_rank . '%\'' . (' AND review_status = 3 AND start_time <= \'' . $now . '\' AND end_time >= \'' . $now . '\' ') . $where . ' ORDER BY sort_order';
	$res = $GLOBALS['db']->query($sql);
	$fat_ext_lang = L('fat_ext');

	if ($res) {
		foreach ($res as $favourable) {
			$favourable['start_time'] = local_date(C('shop.time_format'), $favourable['start_time']);
			$favourable['end_time'] = local_date(C('shop.time_format'), $favourable['end_time']);
			$favourable['formated_min_amount'] = price_format($favourable['min_amount'], false);
			$favourable['formated_max_amount'] = price_format($favourable['max_amount'], false);
			$favourable['gift'] = unserialize($favourable['gift']);

			foreach ($favourable['gift'] as $key => $value) {
				$goods = dao('goods')->field('goods_id, goods_thumb, user_id as ru_id')->where(array('goods_id' => $value['id'], 'is_on_sale' => 1, 'is_delete' => 0))->find();

				if (!empty($goods)) {
					$favourable['gift'][$key]['formated_price'] = price_format($value['price'], false);
					$favourable['gift'][$key]['thumb_img'] = get_image_path($goods['goods_thumb']);
					$favourable['gift'][$key]['url'] = url('goods/index/index', array('id' => $value['id']));
				}
				else {
					unset($favourable['gift'][$key]);
				}
			}

			$favourable['act_range_desc'] = act_range_desc($favourable);
			$favourable['act_type_desc'] = sprintf($fat_ext_lang[$favourable['act_type']], $favourable['act_type_ext']);
			$favourable['available'] = favourable_available($favourable, $act_sel_id);

			if ($favourable['available']) {
				$favourable['available'] = !favourable_used($favourable, $used_list);
			}

			$favourable['act_range_ext'] = return_act_range_ext($favourable['act_range_ext'], $favourable['userFav_type'], $favourable['act_range']);
			$favourable_list[] = $favourable;
		}
	}

	return $favourable_list;
}

function cart_favourable($ru_id = '-1')
{
	$where = '';

	if (-1 < $ru_id) {
		$where .= ' AND ru_id = \'' . $ru_id . '\'';
	}

	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$sql = 'SELECT is_gift, COUNT(*) AS num ' . 'FROM {pre}cart  WHERE ' . $sess_id . ' AND rec_type = \'' . CART_GENERAL_GOODS . '\'' . ' AND is_gift > 0 ' . $where . ' GROUP BY is_gift';
	$res = $GLOBALS['db']->getAll($sql);
	$list = array();

	if ($res) {
		foreach ($res as $row) {
			$list[$row['is_gift']] = $row['num'];
		}
	}

	return $list;
}

function favourable_used($favourable, $cart_favourable)
{
	if ($favourable['act_type'] == FAT_GOODS) {
		return isset($cart_favourable[$favourable['act_id']]) && $favourable['act_type_ext'] <= $cart_favourable[$favourable['act_id']] && 0 < $favourable['act_type_ext'];
	}
	else {
		return isset($cart_favourable[$favourable['act_id']]);
	}
}

function act_range_desc($favourable)
{
	if ($favourable['act_range'] == FAR_BRAND) {
		$sql = 'SELECT brand_name FROM ' . $GLOBALS['ecs']->table('brand') . ' WHERE brand_id ' . db_create_in($favourable['act_range_ext']);
		return join(',', $GLOBALS['db']->getCol($sql));
	}
	else if ($favourable['act_range'] == FAR_CATEGORY) {
		$sql = 'SELECT cat_name FROM ' . $GLOBALS['ecs']->table('category') . ' WHERE cat_id ' . db_create_in($favourable['act_range_ext']);
		return join(',', $GLOBALS['db']->getCol($sql));
	}
	else if ($favourable['act_range'] == FAR_GOODS) {
		$sql = 'SELECT goods_name FROM ' . $GLOBALS['ecs']->table('goods') . ' WHERE goods_id ' . db_create_in($favourable['act_range_ext']);
		return join(',', $GLOBALS['db']->getCol($sql));
	}
	else {
		return '';
	}
}

function favourable_available($favourable, $act_sel_id = array(), $ru_id = -1)
{
	$user_rank = $_SESSION['user_rank'];

	if (strpos(',' . $favourable['user_rank'] . ',', ',' . $user_rank . ',') === false) {
		return false;
	}

	$amount = cart_favourable_amount($favourable, $act_sel_id, $ru_id);
	return $favourable['min_amount'] <= $amount && ($amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0);
}

function cart_favourable_amount($favourable, $act_sel_id = array('act_sel_id' => '', 'act_pro_sel_id' => '', 'act_sel' => ''), $ru_id = -1)
{
	if (!empty($_SESSION['user_id'])) {
		$c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$fav_where = '';

	if ($favourable['userFav_type'] == 0) {
		$fav_where = ' AND g.user_id = \'' . $favourable['user_id'] . '\' ';
	}
	else if (-1 < $ru_id) {
		$fav_where = ' AND g.user_id = \'' . $ru_id . '\' ';
	}

	if (!empty($act_sel_id['act_sel']) && $act_sel_id['act_sel'] == 'cart_sel_flag') {
		$sel_id_list = explode(',', $act_sel_id['act_sel_id']);
		$fav_where .= 'AND c.rec_id ' . db_create_in($sel_id_list);
	}

	$sql = 'SELECT SUM(c.goods_price * c.goods_number) ' . ' FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c, ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . ' WHERE c.goods_id = g.goods_id ' . ' AND ' . $c_sess . ' AND c.rec_type = \'' . CART_GENERAL_GOODS . '\' ' . ' AND c.is_gift = 0 ' . ' AND c.is_checked = 1 ' . ' AND c.goods_id > 0 ' . $fav_where;
	$id_list = array();
	$list_array = array();
	$amount = 0;

	if ($favourable) {
		if ($favourable['act_range'] == FAR_ALL) {
		}
		else if ($favourable['act_range'] == FAR_CATEGORY) {
			$cat_list = explode(',', $favourable['act_range_ext']);

			foreach ($cat_list as $id) {
				$id_list = array_merge($id_list, array_keys(cat_list(intval($id), 0)));
			}

			$sql .= 'AND g.cat_id ' . db_create_in($id_list);
		}
		else if ($favourable['act_range'] == FAR_BRAND) {
			$id_list = explode(',', $favourable['act_range_ext']);
			$sql .= 'AND g.brand_id ' . db_create_in($id_list);
		}
		else if ($favourable['act_range'] == FAR_GOODS) {
			$id_list = explode(',', $favourable['act_range_ext']);
			$sql .= 'AND g.goods_id ' . db_create_in($id_list);
		}

		if ($favourable['act_id']) {
			$sql .= ' AND c.act_id = \'' . $favourable['act_id'] . '\' ';
		}

		$amount = $GLOBALS['db']->getOne($sql);
	}

	return $amount;
}

function sort_favourable($favourable_list)
{
	$arr = array();

	foreach ($favourable_list as $key => $value) {
		switch ($value['act_range']) {
		case FAR_ALL:
			$arr['by_all'][$key] = $value;
			break;

		case FAR_CATEGORY:
			$arr['by_category'][$key] = $value;
			break;

		case FAR_BRAND:
			$arr['by_brand'][$key] = $value;
			break;

		case FAR_GOODS:
			$arr['by_goods'][$key] = $value;
			break;

		default:
			break;
		}
	}

	return $arr;
}

function get_act_range_ext($user_rank, $user_id = 0, $act_range)
{
	if (0 <= $user_id) {
		$ext_where = '';

		if ($GLOBALS['_CFG']['region_store_enabled']) {
			$ext_where = ' AND userFav_type_ext = \'\' ';
		}

		$u_id = ' AND IF(userFav_type = 0 ' . $ext_where . ', user_id = \'' . $user_id . '\', 1 = 1)';
	}

	if (0 < $act_range) {
		$a_range = ' AND act_range = \'' . $act_range . '\' ';
	}

	$res = array();
	$user_rank = ',' . $user_rank . ',';
	$now = gmtime();
	$ext_select = '';

	if ($GLOBALS['_CFG']['region_store_enabled']) {
		$ext_select = ' , userFav_type_ext, rs_id ';
	}

	$sql = 'SELECT act_range_ext, userFav_type, act_range ' . $ext_select . ' ' . 'FROM ' . $GLOBALS['ecs']->table('favourable_activity') . ' WHERE CONCAT(\',\', user_rank, \',\') LIKE \'%' . $user_rank . '%\'' . (' AND review_status = 3 AND start_time <= \'' . $now . '\' AND end_time >= \'' . $now . '\' ') . $u_id . $a_range . ' ORDER BY sort_order';
	$res = $GLOBALS['db']->getAll($sql);
	$arr = array();

	foreach ($res as $key => $row) {
		if ($row['act_range'] == FAR_GOODS && $GLOBALS['_CFG']['region_store_enabled']) {
			$mer_ids = get_favourable_merchants($row['userFav_type'], $row['userFav_type_ext'], $row['rs_id'], 1);
			$where = '';

			if ($mer_ids) {
				$where = ' AND user_id ' . db_create_in($mer_ids);
			}

			$sql = ' SELECT goods_id FROM ' . $GLOBALS['ecs']->table('goods') . ' WHERE goods_id ' . db_create_in($row['act_range_ext']) . $where;
			$res = $GLOBALS['db']->getCol($sql);

			if ($res) {
				$arr = array_merge($arr, $res);
			}
		}
		else {
			$row['act_range_ext'] = return_act_range_ext($row['act_range_ext'], $row['userFav_type'], $row['act_range']);
			$id_list = explode(',', $row['act_range_ext']);
			$arr = array_merge($arr, $id_list);
		}
	}

	return array_unique($arr);
}

function get_favourable_id($favourable)
{
	$arr = array();

	foreach ($favourable as $key => $value) {
		$arr[$key] = $value['act_id'];
	}

	return $arr;
}

function get_sc_str_replace($str1, $str2, $type = 0)
{
	$str1 = !empty($str1) ? explode(',', $str1) : array();
	$str2 = !empty($str2) ? explode(',', $str2) : array();
	$str = '';
	if ($str1 && $str2) {
		if ($type) {
			$str = array_diff($str1, $str2);
		}
		else {
			$str = array_intersect($str1, $str2);
		}

		$str = implode(',', $str);
	}

	return $str;
}

function cmp_favourable($a, $b)
{
	if ($a['available'] == $b['available']) {
		if ($a['sort_order'] == $b['sort_order']) {
			return 0;
		}
		else {
			return $a['sort_order'] < $b['sort_order'] ? -1 : 1;
		}
	}
	else {
		return $a['available'] ? -1 : 1;
	}
}

function update_team($team_id = 0, $team_parent_id = 0)
{
	if (0 < $team_id) {
		$sql = 'select g.id,g.goods_id,g.team_price,g.limit_num, g.team_num,g.validity_time,og.goods_name from ' . $GLOBALS['ecs']->table('team_log') . ' as tl LEFT JOIN ' . $GLOBALS['ecs']->table('team_goods') . ' as g ON tl.t_id = g.id LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . (' as og ON tl.goods_id = og.goods_id  where tl.team_id =' . $team_id . ' ');
		$res = $GLOBALS['db']->getRow($sql);
		$sql = 'SELECT count(order_id) as num  FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE team_id = \'' . $team_id . '\' AND extension_code = \'team_buy\'  and pay_status = \'' . PS_PAYED . '\' ';
		$team_count = $GLOBALS['db']->getRow($sql);

		if ($res['team_num'] <= $team_count['num']) {
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('team_log') . ' SET status = \'1\' ' . ' WHERE team_id = \'' . $team_id . '\' ';
			$GLOBALS['db']->query($sql);

			if (is_dir(APP_WECHAT_PATH)) {
				$sql = 'SELECT order_sn,user_id FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE team_id = \'' . $team_id . '\' AND extension_code = \'team_buy\'  and pay_status = \'' . PS_PAYED . '\' ';
				$team_order = $GLOBALS['db']->query($sql);

				foreach ($team_order as $key => $vo) {
					$pushData = array(
						'keyword1' => array('value' => $vo['order_sn'], 'color' => '#173177'),
						'keyword2' => array('value' => $res['goods_name'], 'color' => '#173177')
						);
					$url = __HOST__ . url('team/goods/teamwait', array('team_id' => $team_id, 'user_id' => $vo['user_id']));
					push_template('OPENTM407456411', $pushData, $url, $vo['user_id']);
				}
			}
		}

		$limit_num = $res['limit_num'] + 1;
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('team_goods') . (' SET limit_num = \'' . $limit_num . '\' ') . ' WHERE goods_id = \'' . $res['goods_id'] . '\' and id = \'' . $res['id'] . '\' ';
		$GLOBALS['db']->query($sql);

		if (is_dir(APP_WECHAT_PATH)) {
			if (0 < $team_parent_id) {
				$pushData = array(
					'keyword1' => array('value' => $res['goods_name'], 'color' => '#173177'),
					'keyword2' => array('value' => $res['team_price'] . '元', 'color' => '#173177'),
					'keyword3' => array('value' => $res['team_num'], 'color' => '#173177'),
					'keyword4' => array('value' => '普通', 'color' => '#173177'),
					'keyword5' => array('value' => $res['validity_time'] . '小时', 'color' => '#173177')
					);
				$url = __HOST__ . url('team/goods/teamwait', array('team_id' => $team_id, 'user_id' => $_SESSION['user_id']));
				push_template('OPENTM407307456', $pushData, $url, $_SESSION['user_id']);
			}
			else {
				$pushData = array(
					'first'    => array('value' => '您好，您已参团成功'),
					'keyword1' => array('value' => $res['goods_name'], 'color' => '#173177'),
					'keyword2' => array('value' => $res['team_price'] . '元', 'color' => '#173177'),
					'keyword3' => array('value' => $res['validity_time'] . '小时', 'color' => '#173177')
					);
				$url = __HOST__ . url('team/goods/teamwait', array('team_id' => $team_id, 'user_id' => $_SESSION['user_id']));
				push_template('OPENTM400048581', $pushData, $url, $_SESSION['user_id']);
			}
		}
	}
}

function get_ru_coupuns_num($ru_id = 0)
{
	$time = gmtime();
	$sql = 'SELECT count(*) FROM {pre}coupons WHERE (cou_type = 3 OR cou_type = 4 ) AND cou_end_time > ' . $time . ' AND (( instr(cou_ok_user, ' . $_SESSION['user_rank'] . ') ) or (cou_goods = 0)) AND  review_status = 3 and ru_id = ' . $ru_id;
	$num = $GLOBALS['db']->getOne($sql);
	return $num;
}

function update_cart_goods_fav($rec_id, $act_id)
{
	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart') . (' SET act_id = \'' . $act_id . '\' WHERE ') . $sess_id . (' AND parent_id = 0 AND group_id = \'\' AND rec_id = \'' . $rec_id . '\' ');
	return $GLOBALS['db']->query($sql);
}


?>
