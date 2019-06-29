<?php
//SHOPROOO
function get_consignee_list_p($user_id, $id = 0, $num = 10, $start = 0)
{
	if ($id) {
		$where['address_id'] = $id;
		$GLOBALS['db']->table = 'user_address';
		return $GLOBALS['db']->find($where);
	}
	else {
		$sql = 'select * from {pre}user_address where user_id = ' . $user_id . ' order by address_id limit ' . $start . ', ' . $num;
		return $GLOBALS['db']->query($sql);
	}
}

function flow_available_points($cart_value, $flow_type = 0, $warehouse_id = 0, $area_id = 0, $area_city = 0)
{
	if (!empty($_SESSION['user_id'])) {
		$c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	$where = '';

	if (!empty($cart_value)) {
		$where = ' AND c.rec_id ' . db_create_in($cart_value);
	}

	$where_area = '';

	if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
		$where_area = ' AND wag.city_id = \'' . $area_city . '\'';
	}

	$leftJoin = ' LEFT JOIN ' . $GLOBALS['ecs']->table('warehouse_goods') . (' as wg ON g.goods_id = wg.goods_id and wg.region_id = \'' . $warehouse_id . '\' ');
	$leftJoin .= ' LEFT JOIN ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' as wag ON g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ' . $where_area . ' ');
	$sql = 'SELECT SUM(IF(g.model_price < 1, g.integral, IF(g.model_price < 2, wg.pay_integral, wag.pay_integral)) * c.goods_number ) ' . 'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON c.goods_id = g.goods_id ' . $leftJoin . 'WHERE IF(g.model_price < 1, g.integral, IF(g.model_price < 2, wg.pay_integral, wag.pay_integral)) > 0 AND ' . $c_sess . (' AND c.is_gift = 0 ' . $where) . 'AND c.rec_type = \'' . $flow_type . '\'';
	$val = intval($GLOBALS['db']->getOne($sql));
	return integral_of_value($val);
}

function get_cart_value($flow_type = 0, $store_id = 0)
{
	$where = '';

	if (!empty($_SESSION['user_id'])) {
		$c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	if (0 < $store_id) {
		$where .= ' c.store_id = ' . $store_id . ' AND ';
	}

	if ($_REQUEST['stages_qishu']) {
		$where .= 'AND c.stages_qishu = ' . trim($_REQUEST['stages_qishu']);
	}

	$sql = 'SELECT c.rec_id FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . (' AS g ON c.goods_id = g.goods_id WHERE ' . $where . ' ') . $c_sess . (' AND c.is_checked = 1 AND c.is_invalid = 0 AND c.rec_type = \'' . $flow_type . '\' order by c.rec_id asc');
	$goods_list = $GLOBALS['db']->getAll($sql);
	$rec_id = '';

	if ($goods_list) {
		foreach ($goods_list as $key => $row) {
			$rec_id .= $row['rec_id'] . ',';
		}

		$rec_id = substr($rec_id, 0, -1);
	}

	return $rec_id;
}

function flow_cart_stock($arr, $store_id = 0, $warehouse_id = 0, $area_id = 0, $area_city = 0)
{
	if (!empty($_SESSION['user_id'])) {
		$sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
	}
	else {
		$sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
	}

	foreach ($arr as $key => $val) {
		$val = intval(make_semiangle($val));
		if ($val <= 0 || !is_numeric($key)) {
			continue;
		}

		$sql = 'SELECT `goods_id`, `goods_attr_id`, `extension_code`, `warehouse_id` FROM' . $GLOBALS['ecs']->table('cart') . (' WHERE rec_id=\'' . $key . '\' AND ') . $sess_id;
		$goods = $GLOBALS['db']->getRow($sql);
		$sql = 'SELECT g.goods_name, g.goods_number, g.goods_id, c.product_id, g.model_attr, c.goods_attr_id, g.cloud_id  ' . 'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g, ' . $GLOBALS['ecs']->table('cart') . ' AS c ' . ('WHERE g.goods_id = c.goods_id AND c.rec_id = \'' . $key . '\'');
		$row = $GLOBALS['db']->getRow($sql);

		if (0 < $store_id) {
			$sql = 'SELECT  goods_number FROM' . $GLOBALS['ecs']->table('store_goods') . (' WHERE goods_id = \'' . $goods_id . '\' AND store_id = \'' . $store_id . '\'');
		}
		else {
			$where = '';

			if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
				$where .= ' AND wag.city_id = \'' . $area_city . '\'';
			}

			$leftJoin = ' LEFT JOIN ' . $GLOBALS['ecs']->table('warehouse_goods') . (' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $warehouse_id . '\' ');
			$leftJoin .= ' LEFT JOIN ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ') . $where;
			$sql = 'SELECT IF(g.model_inventory < 1, g.goods_number, IF(g.model_inventory < 2, wg.region_number, wag.region_number)) AS goods_number  FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . $leftJoin . ' WHERE g.goods_id = \'' . $row['goods_id'] . '\'';
		}

		$goods_number = $GLOBALS['db']->getOne($sql);
		$row['goods_number'] = $goods_number;
		if (0 < intval($GLOBALS['_CFG']['use_storage']) && $goods['extension_code'] != 'package_buy' && $store_id == 0 && $row['cloud_id'] == 0) {
			$row['product_id'] = trim($row['product_id']);

			if (!empty($row['product_id'])) {
				if ($row['model_attr'] == 1) {
					$table_products = 'products_warehouse';
				}
				else if ($row['model_attr'] == 2) {
					$table_products = 'products_area';
				}
				else {
					$table_products = 'products';
				}

				$sql = 'SELECT product_number FROM ' . $GLOBALS['ecs']->table($table_products) . ' WHERE goods_id = \'' . $row['goods_id'] . '\' and product_id = \'' . $row['product_id'] . '\'';
				$product_number = $GLOBALS['db']->getOne($sql);

				if ($product_number < $val) {
					show_message(sprintf(L('stock_insufficiency'), $row['goods_name'], $product_number, $product_number), '', '', 'warning');
					exit();
				}
			}
			else if ($row['goods_number'] < $val) {
				show_message(sprintf(L('stock_insufficiency'), $row['goods_name'], $row['goods_number'], $row['goods_number']), '', '', 'warning');
				exit();
			}
		}
		else {
			if (0 < intval($GLOBALS['_CFG']['use_storage']) && 0 < $store_id && $row['cloud_id'] == 0) {
				$sql = 'SELECT goods_number,ru_id FROM' . $GLOBALS['ecs']->table('store_goods') . (' WHERE store_id = \'' . $store_id . '\' AND goods_id = \'') . $row['goods_id'] . '\' ';
				$goodsInfo = $GLOBALS['db']->getRow($sql);
				$products = get_warehouse_id_attr_number($row['goods_id'], $goods['goods_attr_id'], $goodsInfo['ru_id'], 0, 0, 0, '', $store_id);
				$attr_number = $products['product_number'];

				if ($goods['goods_attr_id']) {
					$row['goods_number'] = $attr_number;
				}
				else {
					$row['goods_number'] = $goodsInfo['goods_number'];
				}

				if ($row['goods_number'] < $val) {
					show_message(sprintf(L('stock_store_shortage'), $row['goods_name'], $row['goods_number'], $row['goods_number']), '', '', 'warning');
					exit();
				}
			}
			else {
				if (0 < intval($GLOBALS['_CFG']['use_storage']) && $goods['extension_code'] == 'package_buy') {
					if (judge_package_stock($goods['goods_id'], $val)) {
						show_message(L('package_stock_insufficiency'), '', '', 'warning');
						exit();
					}
				}
				else if (0 < $row['cloud_id']) {
					$sql = 'SELECT product_number, cloud_product_id FROM ' . $GLOBALS['ecs']->table('products') . ' WHERE goods_id = \'' . $row['goods_id'] . '\' and product_id = \'' . $row['product_id'] . '\'';
					$cloud_product = $GLOBALS['db']->getRow($sql);
					$cloud_number = get_jigon_products_stock($cloud_product);

					if ($cloud_number < $val) {
						show_message(sprintf(L('stock_insufficiency'), $row['goods_name'], $cloud_number, $cloud_number), '', '', 'warning');
						exit();
					}
				}
			}
		}
	}
}


?>
