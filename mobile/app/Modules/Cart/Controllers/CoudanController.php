<?php
//SHOPROOO 
namespace App\Modules\Cart\Controllers;

class CoudanController extends IndexController
{
	public function __construct()
	{
		parent::__construct();
		$this->assign('area_id', $this->area_info['region_id']);
		$this->assign('warehouse_id', $this->region_id);
		$this->assign('user_id', $_SESSION['user_id']);
	}

	public function actionIndex()
	{
		$active_id = input('id', 0, 'intval');
		$size = input('request.size', 10);
		$page = input('request.page', 1, 'intval');
		$default_display_type = C('shop.show_order_type') == '0' ? 'list' : (C('shop.show_order_type') == '1' ? 'grid' : 'text');
		$default_sort_order_type = C('shop.sort_order_type') == '0' ? 'goods_id' : (C('shop.sort_order_type') == '1' ? 'shop_price' : 'last_update');
		$default_sort_order_method = C('shop.sort_order_method') == '0' ? 'desc' : 'asc';
		$sort_array = array('goods_id', 'shop_price', 'last_update', 'sales_volume');
		$order_array = array('asc', 'desc');
		$display_array = array('list', 'grid', 'text');
		$goods_sort = input('request.sort', '', 'trim');
		$goods_order = input('request.order', '', 'trim');
		$goods_display = input('request.display', '', 'trim');
		$sort = in_array($goods_sort, $sort_array) ? $goods_sort : $default_sort_order_type;
		$order = in_array($goods_order, $order_array) ? $goods_order : $default_sort_order_method;
		$display = in_array($goods_display, $display_array) ? $goods_display : (isset($_COOKIE['ECS']['display']) ? $_COOKIE['ECS']['display'] : $default_display_type);
		cookie('ECS[display]', $this->display);

		if (IS_AJAX) {
			$active_id = input('request.id', 0, 'intval');
			$favourable_goods_list = favourable_goods_list($_SESSION['user_rank'], $active_id, $sort, $order, $size, $page, $this->region_id, $this->area_info['region_id']);
			exit(json_encode(array('list' => $favourable_goods_list['list'], 'totalPage' => $favourable_goods_list['totalpage'])));
		}

		$active_num = dao('favourable_activity')->where(array('act_id' => $active_id, 'review_status' => 3))->count();

		if (empty($active_num)) {
			show_message(L('activity_error'));
		}

		$this->assign('act_type_txt', get_act_type($_SESSION['user_rank'], $active_id));
		$cart_fav_goods = cart_favourable_goods($_SESSION['user_rank'], $active_id);
		$this->assign('cart_favourable_goods', $cart_fav_goods);
		$cart_fav_num = 0;
		$cart_fav_total = 0;

		foreach ($cart_fav_goods as $key => $row) {
			$cart_fav_num += $row['goods_number'];
			$cart_fav_total += $row['shop_price'] * $row['goods_number'];
		}

		$this->assign('cart_fav_num', $cart_fav_num);
		$this->assign('cart_fav_total', price_format($cart_fav_total));
		$this->assign('active_id', $active_id);
		$this->assign('page', $page);
		$this->assign('size', $size);
		$this->assign('sort', $sort);
		$this->assign('order', $order);
		$this->assign('display', $display);
		$this->assign('page_title', L('coudan_title'));
		$this->display();
	}

	public function actionAddToCartCoudan()
	{
		$goods = input('goods', '', 'stripcslashes');
		$goods_id = input('post.goods_id', 0, 'intval');
		$result = array('error' => 0, 'message' => '', 'content' => '', 'goods_id' => '', 'url' => '');
		if (!empty($goods_id) && empty($goods)) {
			if (!is_numeric($goods_id) || intval($goods_id) <= 0) {
				$result['error'] = 1;
				$result['url'] = url('/');
				exit(json_encode($result));
			}
		}

		if (empty($goods)) {
			$result['error'] = 1;
			$result['url'] = url('/');
			exit(json_encode($result));
		}

		$goods = json_decode($goods);
		$warehouse_id = intval($goods->warehouse_id);
		$area_id = intval($goods->area_id);
		$area_city = intval($goods->area_city);
		$store_id = intval($goods->store_id);
		$take_time = trim($goods->take_time);
		$store_mobile = trim($goods->store_mobile);
		$active_id = intval($goods->active_id);
		$active_num = dao('favourable_activity')->where(array('act_id' => $active_id, 'review_status' => 3))->count();

		if (empty($active_num)) {
			$result['error'] = 1;
			$result['message'] = L('activity_error');
			exit(json_encode($result));
		}

		$_SESSION['flow_type'] = $goods->cart_type == 2 ? CART_ONESTEP_GOODS : CART_GENERAL_GOODS;

		if (0 < $store_id) {
			clear_store_goods();
		}

		if (C('shop.open_area_goods') == 1) {
			$leftJoin = '';
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . (' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $warehouse_id . '\' ');
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ');
			$sql = 'SELECT g.user_id, g.review_status, g.model_attr, ' . ' IF(g.model_price < 1, g.goods_number, IF(g.model_price < 2, wg.region_number, wag.region_number)) AS goods_number ' . ' FROM ' . $GLOBALS['ecs']->table('goods') . ' as g ' . $leftJoin . ' WHERE g.goods_id = \'' . $goods->goods_id . '\'';
			$goodsInfo = $GLOBALS['db']->getRow($sql);
			$area_list = get_goods_link_area_list($goods->goods_id, $goodsInfo['user_id']);

			if ($area_list['goods_area']) {
				if (!in_array($area_id, $area_list['goods_area'])) {
					$no_area = 2;
				}
			}
			else {
				$no_area = 2;
			}

			if ($goodsInfo['model_attr'] == 1) {
				$table_products = 'products_warehouse';
				$type_files = ' and warehouse_id = \'' . $warehouse_id . '\'';
			}
			else if ($goodsInfo['model_attr'] == 2) {
				$table_products = 'products_area';
				$type_files = ' and area_id = \'' . $area_id . '\'';
			}
			else {
				$table_products = 'products';
				$type_files = '';
			}

			$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table($table_products) . ' WHERE goods_id = \'' . $goods->goods_id . '\'' . $type_files . ' LIMIT 0, 1';
			$prod = $GLOBALS['db']->getRow($sql);

			if (empty($prod)) {
				$prod = 1;
			}
			else {
				$prod = 0;
			}

			if ($no_area == 2) {
				$result['error'] = 1;
				$result['message'] = L('not_support_delivery');
				exit(json_encode($result));
			}
			else if ($goodsInfo['review_status'] <= 2) {
				$result['error'] = 1;
				$result['message'] = L('down_shelves');
				exit(json_encode($result));
			}
		}

		if (empty($goods->spec) && empty($goods->quick)) {
			$groupBy = ' group by ga.goods_attr_id ';
			$leftJoin = '';
			$shop_price = 'wap.attr_price, wa.attr_price, g.model_attr, ';
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('goods') . ' as g on g.goods_id = ga.goods_id';
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_attr') . (' as wap on ga.goods_id = wap.goods_id and wap.warehouse_id = \'' . $warehouse_id . '\' and ga.goods_attr_id = wap.goods_attr_id ');
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_attr') . (' as wa on ga.goods_id = wa.goods_id and wa.area_id = \'' . $area_id . '\' and ga.goods_attr_id = wa.goods_attr_id ');
			$sql = 'SELECT a.attr_id, a.attr_name, a.attr_type, ' . 'ga.goods_attr_id, ga.attr_value, IF(g.model_attr < 1, ga.attr_price, IF(g.model_attr < 2, wap.attr_price, wa.attr_price)) as attr_price ' . 'FROM ' . $GLOBALS['ecs']->table('goods_attr') . ' AS ga ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('attribute') . ' AS a ON a.attr_id = ga.attr_id ' . $leftJoin . 'WHERE a.attr_type != 0 AND ga.goods_id = \'' . $goods->goods_id . '\' ' . $groupBy . 'ORDER BY a.sort_order, ga.attr_id';
			$res = $this->db->query($sql);

			if (!empty($res)) {
				$spe_arr = array();

				foreach ($res as $row) {
					$spe_arr[$row['attr_id']]['attr_type'] = $row['attr_type'];
					$spe_arr[$row['attr_id']]['name'] = $row['attr_name'];
					$spe_arr[$row['attr_id']]['attr_id'] = $row['attr_id'];
					$spe_arr[$row['attr_id']]['values'][] = array('label' => $row['attr_value'], 'price' => $row['attr_price'], 'format_price' => price_format($row['attr_price'], false), 'id' => $row['goods_attr_id']);
				}

				$i = 0;
				$spe_array = array();

				foreach ($spe_arr as $row) {
					$spe_array[] = $row;
				}

				$result['error'] = ERR_NEED_SELECT_ATTR;
				$result['goods_id'] = $goods->goods_id;
				$result['warehouse_id'] = $warehouse_id;
				$result['area_id'] = $area_id;
				$result['parent'] = $goods->parent;
				$result['message'] = $spe_array;
				$result['goods_number'] = cart_number();
				exit(json_encode($result));
			}
		}

		if (!empty($goods->cart_type) && $goods->cart_type == 2) {
			clear_cart(CART_ONESTEP_GOODS);
		}

		$goods_number = intval($goods->number);
		if (!is_numeric($goods_number) || $goods_number <= 0) {
			$result['error'] = 1;
			$result['message'] = L('invalid_number');
		}
		else {
			$xiangouInfo = get_purchasing_goods_info($goods->goods_id);

			if ($xiangouInfo['is_xiangou'] == 1) {
				$user_id = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
				$sql = 'SELECT goods_number FROM ' . $this->ecs->table('cart') . 'WHERE goods_id = ' . $goods->goods_id . ' and ' . $this->sess_id;
				$cartGoodsNumInfo = $this->db->getRow($sql);
				$start_date = $xiangouInfo['xiangou_start_date'];
				$end_date = $xiangouInfo['xiangou_end_date'];
				$orderGoods = get_for_purchasing_goods($start_date, $end_date, $goods->goods_id, $user_id);
				$nowTime = gmtime();
				if ($start_date < $nowTime && $nowTime < $end_date) {
					if ($xiangouInfo['xiangou_num'] <= $orderGoods['goods_number']) {
						$result['error'] = 1;
						$max_num = $xiangouInfo['xiangou_num'] - $orderGoods['goods_number'];
						$result['message'] = L('cannot_buy');
						exit(json_encode($result));
					}
					else if (0 < $xiangouInfo['xiangou_num']) {
						if ($xiangouInfo['xiangou_num'] < $cartGoodsNumInfo['goods_number'] + $orderGoods['goods_number'] + $goods_number) {
							$result['error'] = 1;
							$result['message'] = L('beyond_quota_limit');
							exit(json_encode($result));
						}
					}
				}
			}

			$cart_extends = array('warehouse_id' => $warehouse_id, 'area_id' => $area_id, 'area_city' => $area_city, 'store_id' => $store_id, 'take_time' => $take_time, 'store_mobile' => $store_mobile);
			$rec_type = $_SESSION['flow_type'];
			$rec_id = addto_cart($goods->goods_id, $goods_number, $goods->spec, $goods->parent, $cart_extends, $rec_type);

			if ($rec_id) {
				if (2 < C('shop.cart_confirm')) {
					$result['message'] = '';
				}
				else {
					$result['message'] = C('shop.cart_confirm') == 1 ? L('addto_cart_success_1') : L('addto_cart_success_2');
				}

				if (0 < $store_id) {
					$cart_value = $GLOBALS['db']->getOne('SELECT rec_id FROM ' . $GLOBALS['ecs']->table('cart') . (' WHERE goods_id=\'' . $goods->goods_id . '\' AND user_id=\'') . $_SESSION['user_id'] . '\' AND store_id=' . $store_id);
					$result['cart_value'] = $cart_value;
					$result['store_id'] = $store_id;
				}

				update_cart_goods_fav($rec_id, $active_id);
				$result['content'] = insert_cart_info();
			}
			else {
				$result['message'] = $this->err->last_message();
				$result['error'] = $this->err->error_no;
				$result['goods_id'] = stripslashes($goods->goods_id);

				if (is_array($goods->spec)) {
					$result['product_spec'] = implode(',', $goods->spec);
				}
				else {
					$result['product_spec'] = $goods->spec;
				}
			}
		}

		$result['confirm_type'] = C('shop.cart_confirm') ? C('shop.cart_confirm') : 2;
		$result['parent'] = $goods->parent;
		$result['goods_number'] = cart_number();
		$result['cart_type'] = $goods->cart_type;
		$cart_fav_goods = cart_favourable_goods($_SESSION['user_rank'], $active_id);
		$cart_fav_num = 0;
		$cart_fav_total = 0;

		foreach ($cart_fav_goods as $key => $row) {
			$cart_fav_num += $row['goods_number'];
			$cart_fav_total += $row['shop_price'] * $row['goods_number'];
		}

		$result['cart_fav_num'] = $cart_fav_num;
		$result['cart_fav_total'] = price_format($cart_fav_total);
		exit(json_encode($result));
	}
}

?>
