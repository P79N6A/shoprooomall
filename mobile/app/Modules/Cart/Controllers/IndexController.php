<?php
 //SHOPROOO
namespace App\Modules\Cart\Controllers;

class IndexController extends \App\Modules\Base\Controllers\FrontendController
{
	protected $sess_id = '';
	protected $a_sess = '';
	protected $b_sess = '';
	protected $c_sess = '';
	protected $sess_cart = '';
	protected $region_id = 0;
	protected $area_id = 0;
	protected $area_city = 0;

	public function __construct()
	{
		parent::__construct();
		L(require LANG_PATH . C('shop.lang') . '/user.php');
		L(require LANG_PATH . C('shop.lang') . '/flow.php');
		$this->assign('lang', array_change_key_case(L()));
		$files = array('order');
		$this->load_helper($files);

		if (!empty($_SESSION['user_id'])) {
			$this->sess_id = ' user_id = \'' . $_SESSION['user_id'] . '\' ';
			$this->a_sess = ' a.user_id = \'' . $_SESSION['user_id'] . '\' ';
			$this->b_sess = ' b.user_id = \'' . $_SESSION['user_id'] . '\' ';
			$this->c_sess = ' c.user_id = \'' . $_SESSION['user_id'] . '\' ';
			$this->sess_cart = '';
		}
		else {
			$this->sess_id = ' session_id = \'' . real_cart_mac_ip() . '\' ';
			$this->a_sess = ' a.session_id = \'' . real_cart_mac_ip() . '\' ';
			$this->b_sess = ' b.session_id = \'' . real_cart_mac_ip() . '\' ';
			$this->c_sess = ' c.session_id = \'' . real_cart_mac_ip() . '\' ';
			$this->sess_cart = real_cart_mac_ip();
		}

		$this->init_params();
		$this->area_id = $this->area_info['region_id'];
		$this->assign('area_id', $this->area_id);
		$this->assign('warehouse_id', $this->region_id);
		$this->assign('area_city', $this->area_city);
		$this->assign('user_id', $_SESSION['user_id']);
	}

	public function actionIndex()
	{
		$_SESSION['flow_type'] = CART_GENERAL_GOODS;
		$cart_goods = get_cart_goods('', 1, $this->region_id, $this->area_id, $this->area_city);
		$merchant_goods_list = cart_by_favourable($cart_goods['goods_list'], $cart_goods['total']['cart_value']);
		$discount = compute_discount(3);
		$fav_amount = price_format($discount['discount']);
		$this->assign('fav_amount', $fav_amount);
		$this->assign('province_row', get_region_name($this->province_id));
		$this->assign('city_row', get_region_name($this->city_id));
		$this->assign('district_row', get_region_name($this->district_id));
		$this->assign('town_row', get_region_name($this->town_region_id));
		$best_goods = get_recommend_goods('best', '', $this->region_id, $this->area_id, $this->area_city, 0, 0, '', 10);
		$this->assign('best_goods', $best_goods);
		$guess_goods = get_guess_goods($_SESSION['user_id'], 1, 1, 10, $this->region_id, $this->area_id, $this->area_city);
		$this->assign('guess_goods', $guess_goods);
		if (IS_AJAX && C('shop.wap_category') == '1') {
			$this->response(array('error' => 0, 'goods_list' => $cart_goods['goods_list'], 'total' => $cart_goods['total']));
		}
		else {
			$this->assign('goods_list', $merchant_goods_list);
			$this->assign('total', $cart_goods['total']);
			$this->assign('cart_value', $cart_goods['total']['cart_value']);
			$this->assign('currency_format', sub_str(strip_tags($GLOBALS['_CFG']['currency_format']), 1, false));
			$this->assign('page_title', L('shopping_cart'));
		}

		$this->display();
	}

	public function actionAddToCart()
	{
		$goods = I('goods', '', 'stripcslashes');
		$goods_id = I('post.goods_id', 0, 'intval');
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
		$_SESSION['flow_type'] = $goods->cart_type == 2 ? CART_ONESTEP_GOODS : CART_GENERAL_GOODS;

		if (0 < $store_id) {
			clear_store_goods();
		}

		if (C('shop.open_area_goods') == 1) {
			$where_area = '';

			if (C('shop.area_pricetype') == 1) {
				$where_area = ' AND wag.city_id = \'' . $area_city . '\'';
			}

			$leftJoin = '';
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . (' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $warehouse_id . '\' ');
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ') . $where_area;
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

				if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
					$type_files .= ' AND city_id = \'' . $area_city . '\'';
				}
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
			$rs = addto_cart($goods->goods_id, $goods_number, $goods->spec, $goods->parent, $cart_extends, $rec_type);

			if ($rs == true) {
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
		exit(json_encode($result));
	}

	public function actionHeart()
	{
		if (IS_AJAX) {
			if (0 < $_SESSION['user_id']) {
				$goods_id = input('id', '', array('html_in', 'trim'));
				$status = input('status', 0, 'intval');
				$goods_arr = explode(',', substr($goods_id, 0, str_len($goods_id) - 1));

				if (0 < count($goods_arr)) {
					if ($status == 0) {
						foreach ($goods_arr as $val) {
							$map['user_id'] = $_SESSION['user_id'];
							$map['goods_id'] = $val;
							$rs = dao('collect_goods')->where($map)->count();

							if ($rs < 1) {
								$data['user_id'] = $_SESSION['user_id'];
								$data['goods_id'] = $val;
								$data['add_time'] = gmtime();
								dao('collect_goods')->data($data)->add();
							}
						}

						exit(json_encode(array('msg' => L('already_attention_check_shop'), 'status' => 1, 'error' => 0)));
					}
					else {
						$map['user_id'] = $_SESSION['user_id'];
						$map['goods_id'] = array('in', $goods_arr);
						dao('collect_goods')->where($map)->delete();
						exit(json_encode(array('msg' => L('cancel_attention'), 'status' => 0, 'error' => 0)));
					}
				}
				else {
					exit(json_encode(array('msg' => L('error_information'), 'error' => 1)));
				}
			}

			exit(json_encode(array('msg' => L('no_login_attention'), 'error' => 2)));
		}
	}

	public function actionDropGoods()
	{
		if (IS_AJAX) {
			$rec_id = input('id', '', array('html_in', 'trim'));

			if (!empty($rec_id)) {
				$rec_id = explode(',', substr($rec_id, 0, str_len($rec_id) - 1));

				if (0 < count($rec_id)) {
					foreach ($rec_id as $key) {
						flow_drop_cart_goods($key);
					}

					exit(json_encode(array('error' => 0, 'msg' => '删除成功')));
				}
			}

			exit(json_encode(array('error' => 1, 'msg' => '删除失败')));
		}
	}

	public function actionDeleteCart()
	{
		if (IS_AJAX) {
			$rec_id = I('id', '', 'intval');

			if ($rec_id) {
				$result = flow_drop_cart_goods($rec_id);

				if ($result) {
					exit(json_encode(array('error' => 0, 'msg' => '已删除')));
				}
				else {
					exit(json_encode(array('error' => 1, 'msg' => '删除失败')));
				}
			}

			exit(json_encode(array('error' => 1, 'msg' => '删除失败')));
		}
	}

	public function actionCartGoodsNumber()
	{
		if (IS_AJAX) {
			$cart_id = input('cart_id', '', array('trim', 'html_in'));
			$goods_number = input('number', 0, 'intval');
			$none = input('none', '', array('trim', 'html_in'));
			$rec_id = input('rec_id', '', array('trim', 'html_in'));
			$act_id = input('act_id', '', array('trim', 'html_in'));
			if (empty($cart_id) || $goods_number <= 0 && !is_numeric($goods_number)) {
				$result['error'] = 99;
				$result['msg'] = '数量不合法';
				exit(json_encode($result));
			}

			$rec_id = substr($rec_id, 0, str_len($rec_id) - 1);
			$warehouse_id = input('warehouse_id', 0, 'intval');
			$area_id = input('area_id', 0, 'intval');
			$area_city = input('area_city', 0, 'intval');
			$sql = 'SELECT `warehouse_id`, `area_id`,`area_city` FROM ' . $GLOBALS['ecs']->table('cart') . (' WHERE rec_id = \'' . $cart_id . '\' AND ') . $this->sess_id;
			$goods = $GLOBALS['db']->getRow($sql);
			$warehouse_id = !empty($warehouse_id) ? $warehouse_id : intval($goods['warehouse_id']);
			$area_id = !empty($area_id) ? $area_id : intval($goods['area_id']);
			$area_city = !empty($area_city) ? $area_city : intval($goods['area_city']);
			$where_area = '';

			if ($GLOBALS['_CFG']['area_pricetype'] == 1) {
				$where_area = ' AND wag.city_id = \'' . $area_city . '\'';
			}

			$leftJoin = ' LEFT JOIN ' . $GLOBALS['ecs']->table('warehouse_goods') . (' AS wg ON g.goods_id = wg.goods_id AND wg.region_id = \'' . $warehouse_id . '\' ');
			$leftJoin .= ' LEFT JOIN ' . $GLOBALS['ecs']->table('warehouse_area_goods') . (' AS wag ON g.goods_id = wag.goods_id AND wag.region_id = \'' . $area_id . '\' ' . $where_area);
			$sql = 'SELECT g.goods_name, wg.region_number as wg_number, wag.region_number as wag_number, g.model_price, g.model_inventory, g.model_attr, g.goods_number, g.group_number, ' . 'c.goods_id, c.goods_attr_id, c.product_id, c.extension_code, c.warehouse_id, c.area_id, c.ru_id, ' . 'c.group_id, c.extension_code, c.goods_name AS act_name, g.freight, g.tid, g.shipping_fee, g.cloud_id ' . 'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('cart') . ' AS c on g.goods_id = c.goods_id ' . $leftJoin . ('WHERE c.rec_id = \'' . $cart_id . '\' LIMIT 1');
			$row = $GLOBALS['db']->getRow($sql);

			if (empty($row)) {
				$result['error'] = 1;
				$result['msg'] = '购物车商品不存在';
				exit(json_encode($result));
			}

			$nowTime = gmtime();
			$xiangouInfo = get_purchasing_goods_info($row['goods_id']);
			$start_date = $xiangouInfo['xiangou_start_date'];
			$end_date = $xiangouInfo['xiangou_end_date'];
			if ($xiangouInfo['is_xiangou'] == 1 && $start_date < $nowTime && $nowTime < $end_date) {
				$user_id = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
				$orderGoods = get_for_purchasing_goods($start_date, $end_date, $row['goods_id'], $user_id);

				if ($row['goods_number'] < $goods_number) {
					$goods_number = $row['goods_number'];
					$result['error'] = 1;
					$result['msg'] = sprintf(L('stock_insufficiency'), $row['goods_name'], $row['goods_number'], $row['goods_number']);
					exit(json_encode($result));
				}

				if ($xiangouInfo['xiangou_num'] <= $orderGoods['goods_number']) {
					$result['msg'] = '该' . $row['goods_name'] . L('cannot_buy');
					$result['num'] = $goods_number;
					$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart') . (' SET goods_number = 0 WHERE rec_id=\'' . $cart_id . '\'');
					$GLOBALS['db']->query($sql);
					$result['error'] = 1;
					exit(json_encode($result));
				}
				else if (0 < $xiangouInfo['xiangou_num']) {
					if ($xiangouInfo['is_xiangou'] == 1 && $xiangouInfo['xiangou_num'] < $orderGoods['goods_number'] + $goods_number) {
						$result['msg'] = '该' . $row['goods_name'] . '商品已经累计超过限购数量';
						$cart_Num = $xiangouInfo['xiangou_num'] - $orderGoods['goods_number'];
						$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart') . (' SET goods_number = \'' . $cart_Num . '\' WHERE rec_id=\'' . $cart_id . '\'');
						$GLOBALS['db']->query($sql);
						$result['error'] = 1;
						$result['num'] = $cart_Num;
						exit(json_encode($result));
					}
				}
			}
			else {
				if (0 < intval($GLOBALS['_CFG']['use_storage']) && $row['extension_code'] != 'package_buy') {
					if ($row['model_inventory'] == 1) {
						$row['goods_number'] = $row['wg_number'];
					}
					else if ($row['model_inventory'] == 2) {
						$row['goods_number'] = $row['wag_number'];
					}

					if (!empty($row['product_id'])) {
						$select = '';

						if ($row['model_attr'] == 1) {
							$table_products = 'products_warehouse';
						}
						else if ($row['model_attr'] == 2) {
							$table_products = 'products_area';
						}
						else {
							$table_products = 'products';
							$select = ',cloud_product_id ';
						}

						$sql = 'SELECT product_number ' . $select . ' FROM ' . $GLOBALS['ecs']->table($table_products) . ' WHERE goods_id = \'' . $row['goods_id'] . '\' and product_id = \'' . $row['product_id'] . '\' LIMIT 1';
						$prod = $GLOBALS['db']->getRow($sql);
						$product_number = $prod['product_number'];

						if (0 < $row['cloud_id']) {
							$product_number = 0;
							$product_number = get_jigon_products_stock($prod);
						}

						if ($product_number < $goods_number) {
							$goods_number = $product_number;
							$result['error'] = 2;
							$result['msg'] = sprintf(L('stock_insufficiency'), $row['goods_name'], $product_number, $product_number);
							exit(json_encode($result));
						}
					}
					else if ($row['goods_number'] < $goods_number) {
						$goods_number = $row['goods_number'];
						$result['error'] = 1;
						$result['msg'] = sprintf(L('stock_insufficiency'), $row['goods_name'], $row['goods_number'], $row['goods_number']);
						exit(json_encode($result));
					}
				}
				else {
					if (0 < intval($GLOBALS['_CFG']['use_storage']) && $row['extension_code'] == 'package_buy') {
						if (judge_package_stock($row['goods_id'], $goods_number)) {
							$result['error'] = 3;
							$result['msg'] = L('package_stock_insufficiency');
							exit(json_encode($result));
						}
					}
				}
			}

			$sql = 'SELECT b.goods_number,b.rec_id FROM ' . $GLOBALS['ecs']->table('cart') . ' a, ' . $GLOBALS['ecs']->table('cart') . (' b
                    WHERE a.rec_id = \'' . $cart_id . '\' AND ') . $this->a_sess . ' AND a.extension_code <>\'package_buy\' AND b.parent_id = a.goods_id AND ' . $this->b_sess;
			$offers_accessories_res = $GLOBALS['db']->getAll($sql);

			if (0 < $goods_number) {
				if (0 < $row['group_number'] && $row['group_number'] < $goods_number && !empty($row['group_id'])) {
					$result['error'] = 1;
					$result['msg'] = sprintf(L('group_stock_insufficiency'), $row['goods_name'], $row['group_number'], $row['group_number']);
					exit(json_encode($result));
				}

				for ($i = 0; $i < count($offers_accessories_res); $i++) {
					$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart') . (' SET goods_number = \'' . $goods_number . '\'') . ' WHERE rec_id =\'' . $offers_accessories_res[$i]['rec_id'] . '\'';
					$GLOBALS['db']->query($sql);
				}

				if ($row['extension_code'] == 'package_buy') {
					$ext_info = $GLOBALS['db']->getOne('SELECT ext_info FROM ' . $GLOBALS['ecs']->table('goods_activity') . ' WHERE review_status = 3 AND act_name = \'' . $row['act_name'] . '\'');

					if (!empty($ext_info)) {
						$ext_arr = unserialize($ext_info);
						$goods_price = $ext_arr['package_price'];
					}

					$sql = 'UPDATE {pre}cart SET goods_number = \'' . $goods_number . '\' WHERE rec_id = \'' . $cart_id . '\' ';
					$this->db->query($sql);
				}
				else {
					if ($GLOBALS['_CFG']['add_shop_price'] == 1) {
						$add_tocart = 1;
					}
					else {
						$add_tocart = 0;
					}

					$attr_id = empty($row['goods_attr_id']) ? array() : explode(',', $row['goods_attr_id']);
					$goods_price = get_final_price($row['goods_id'], $goods_number, true, $attr_id, $warehouse_id, $area_id, $area_city, 0, 0, $add_tocart);
					$set = '';

					if (0 < $goods_price) {
						$set = 'goods_price = \'' . $goods_price . '\', ';
					}

					$sql = 'UPDATE {pre}cart SET goods_number = \'' . $goods_number . '\', ' . $set . ' freight = \'' . $row['freight'] . '\', tid = \'' . $row['tid'] . '\', shipping_fee = \'' . $row['shipping_fee'] . ('\' WHERE rec_id = \'' . $cart_id . '\'');
					$GLOBALS['db']->query($sql);
				}
			}
			else {
				for ($i = 0; $i < count($offers_accessories_res); $i++) {
					$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE rec_id =\'' . $offers_accessories_res[$i]['rec_id'] . '\'';
					$GLOBALS['db']->query($sql);
				}

				$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart') . (' WHERE rec_id = \'' . $cart_id . '\'');
				$GLOBALS['db']->query($sql);
			}

			$result['rec_id'] = $cart_id;
			$result['goods_number'] = $goods_number;
			$result['max_number'] = $product_number < $row['goods_number'] ? $row['goods_number'] : $product_number;
			$result['ru_id'] = $row['ru_id'];
			$result['none'] = $none;
			$result['shop_price'] = price_format($goods_price);
			$result['error'] = 0;
			$cart_goods = get_cart_goods($rec_id, CART_GENERAL_GOODS, $this->region_id, $this->area_id, $area_city);

			foreach ($cart_goods['goods_list'] as $goods) {
				if ($goods['rec_id'] == $cart_id) {
					$result['rec_goods'] = $goods['goods_id'];
					break;
				}
			}

			$discount = compute_discount(3, $rec_id);
			$goods_amount = get_cart_check_goods($cart_goods['goods_list'], $rec_id, 1);
			$fav_amount = $discount['discount'];
			$result['save_total_amount'] = price_format($fav_amount + $goods_amount['save_amount']);
			$result['dis_amount'] = $goods_amount['save_amount'];

			if ($goods_amount['subtotal_amount']) {
				$goods_amount['subtotal_amount'] = $goods_amount['subtotal_amount'] - $fav_amount;
			}
			else {
				$result['save_total_amount'] = 0;
			}

			$result['goods_amount'] = price_format($goods_amount['subtotal_amount'], false);
			$result['cart_number'] = $goods_amount['subtotal_number'];
			$result['discount'] = price_format($fav_amount, false);
			$result['act_id'] = $act_id;
			$result['group'] = array();
			$subtotal_number = 0;

			foreach ($cart_goods['goods_list'] as $goods) {
				$subtotal_number += $goods['goods_number'];
				if (isset($result['rec_goods']) && 0 < $goods['parent_id'] && $result['rec_goods'] == $goods['parent_id']) {
					if ($goods['rec_id'] != $cart_id) {
						$result['group'][$goods['rec_id']]['rec_group'] = $goods['group_id'] . '_' . $goods['rec_id'];
						$result['group'][$goods['rec_id']]['rec_group_number'] = $goods['goods_number'];
						$result['group'][$goods['rec_id']]['rec_group_talId'] = $goods['group_id'] . '_' . $goods['rec_id'] . '_subtotal';
						$result['group'][$goods['rec_id']]['rec_group_subtotal'] = price_format($goods['goods_amount'], false);
					}
				}
			}

			$result['subtotal_number'] = $subtotal_number;

			if ($result['group']) {
				$result['group'] = array_values($result['group']);
			}

			if ($act_id) {
				$sel_flag = input('sel_flag', '', array('trim', 'addslashes'));
				$act_sel = array('act_sel_id' => $cart_id, 'act_sel' => $sel_flag);
				$sql = 'SELECT rec_id FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $this->sess_id . (' AND is_gift = \'' . $act_id . '\'');
				$is_gift = $GLOBALS['db']->getAll($sql);
				$favourable = favourable_info($act_id);
				$favourable_available = favourable_available($favourable, $act_sel);
				$is_delete_gift = 0;
				if ($is_gift && !$favourable_available) {
					$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $this->sess_id . (' AND is_gift = \'' . $act_id . '\' AND ru_id = \'') . $row['ru_id'] . '\' ';
					$GLOBALS['db']->query($sql);
					$is_delete_gift = 1;
				}

				$cart_fav_box = cart_favourable_box($act_id, $act_sel);
				$result['cart_fav_box'] = $cart_fav_box;
				$result['is_gift'] = $is_gift;
				$result['is_delete_gift'] = $is_delete_gift;
			}

			exit(json_encode($result));
		}
	}

	public function actionCartLabelCount()
	{
		if (IS_POST) {
			$type = input('type', 0, 'intval');
			$rec_id = input('rec_id', '', array('trim', 'addslashes'));
			$cart_id = input('cart_id', '', array('trim', 'addslashes'));
			$act_id = input('act_id', '', array('trim', 'addslashes'));
			$status = input('status', 1, 'intval');
			$rec_id = substr($rec_id, 0, str_len($rec_id) - 1);
			$cart_id = substr($cart_id, 0, str_len($cart_id) - 1);
			$act_id = substr($act_id, 0, str_len($act_id) - 1);
			if (($type == 1 || $type == 2) && $status == 1) {
				$sql = 'UPDATE {pre}cart SET `is_checked` = 1 WHERE rec_id in (' . $cart_id . ')';
				$this->db->query($sql);
			}
			else {
				if ($type == 1 && $status == 0) {
					$sql = 'UPDATE {pre}cart SET `is_checked` = 0 WHERE rec_id in (' . $cart_id . ')';
					$this->db->query($sql);
				}
				else {
					if (($type == 1 || $type == 2) && $status == 2) {
						$sql = 'UPDATE {pre}cart SET `is_checked` = 0 WHERE rec_id in (' . $cart_id . ')';
						$this->db->query($sql);
					}
					else {
						$sql = 'SELECT is_checked FROM {pre}cart WHERE rec_id = ' . $cart_id;
						$is_checked = $this->db->getOne($sql);

						if ($is_checked == 0) {
							dao('cart')->data(array('is_checked' => 1))->where(array('rec_id' => $cart_id))->save();
						}

						if ($is_checked == 1) {
							dao('cart')->data(array('is_checked' => 0))->where(array('rec_id' => $cart_id))->save();
						}
					}
				}
			}

			$cart_goods = cart_goods(CART_GENERAL_GOODS, $rec_id, 0, $this->region_id, $this->area_id, $this->area_city);
			$discount = compute_discount(3, $rec_id);
			$fav_amount = $discount['discount'];
			$goods_amount = get_cart_check_goods($cart_goods, $rec_id);
			$save_total_amount = price_format($fav_amount + $goods_amount['save_amount']);
			$result['save_total_amount'] = $save_total_amount;
			$result['dis_amount'] = $goods_amount['save_amount'];

			if ($goods_amount['subtotal_amount']) {
				$goods_amount['subtotal_amount'] = $goods_amount['subtotal_amount'] - $fav_amount;
			}
			else {
				$result['save_total_amount'] = 0;
			}

			$result['goods_amount'] = price_format($goods_amount['subtotal_amount'], false);
			$result['cart_number'] = $goods_amount['subtotal_number'];
			$result['discount'] = price_format($fav_amount, false);
			$result['act_id'] = $act_id;
			$result['cart_id'] = $cart_id;

			if ($act_id) {
				$sel_flag = input('sel_flag', '', array('trim', 'addslashes'));
				$act_sel = array('act_sel_id' => $cart_id, 'act_sel' => $sel_flag);
				$sql = 'SELECT rec_id FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $this->sess_id . (' AND is_gift = \'' . $act_id . '\'');
				$is_gift = $GLOBALS['db']->getAll($sql);
				$is_delete_gift = 0;
				if ($is_gift && $status == 0) {
					$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $this->sess_id . (' AND is_gift = \'' . $act_id . '\'');
					$GLOBALS['db']->query($sql);
					$is_delete_gift = 1;
				}

				$cart_fav_box = cart_favourable_box($act_id, $act_sel);
				$result['cart_fav_box'] = $cart_fav_box;
				$result['is_gift'] = $is_gift;
				$result['is_delete_gift'] = $is_delete_gift;
			}

			if (0 < $type) {
				$cart_goods = get_cart_goods(CART_GENERAL_GOODS, 1, $this->region_id, $this->area_id, $this->area_city);
				$merchant_goods_list = cart_by_favourable($cart_goods['goods_list'], $cart_goods['total']['cart_value']);
				$this->assign('goods_list', $merchant_goods_list);
				$this->assign('total', $cart_goods['total']);
				$this->assign('cart_value', $cart_goods['total']['cart_value']);
				$result['content'] = $this->fetch('cart_box');
			}

			exit(json_encode($result));
		}
	}

	public function actionCartValue()
	{
		if (IS_AJAX) {
			$rec_id = input('rec_id', '', array('trim', 'html_in'));
			$act_id = input('act_id', '', array('trim', 'html_in'));
			$result = array('error' => 0, 'cart_number' => 0, 'goods_amount' => 0, 'rec_id' => $rec_id, 'act_id' => $act_id);
			$cart_goods = cart_goods(CART_GENERAL_GOODS, $rec_id, 0, $this->region_id, $this->area_id, $this->area_city);

			if (!empty($cart_goods)) {
				$rec_id_arr = explode(',', $rec_id);
				$act_id_arr = explode(',', $act_id);

				foreach ($rec_id_arr as $key => $value) {
					foreach ($act_id_arr as $k => $val) {
						if ($key == $k) {
							update_cart_goods_fav($value, $val);
						}
					}
				}

				$discount = compute_discount(3, $rec_id);
				$fav_amount = $discount['discount'];
				$goods_amount = get_cart_check_goods($cart_goods, $rec_id);
				$save_total_amount = price_format($fav_amount + $goods_amount['save_amount']);
				$result['save_total_amount'] = $save_total_amount;
				$result['dis_amount'] = $goods_amount['save_amount'];

				if ($goods_amount['subtotal_amount']) {
					$goods_amount['subtotal_amount'] = $goods_amount['subtotal_amount'] - $fav_amount;
				}
				else {
					$result['save_total_amount'] = 0;
				}

				$result['goods_amount'] = price_format($goods_amount['subtotal_amount'], false);
				$result['cart_number'] = $goods_amount['subtotal_number'];
				$result['discount'] = price_format($fav_amount, false);
				$result['act_id'] = $act_id;
				exit(json_encode($result));
			}

			$result['error'] = 1;
			exit(json_encode($result));
		}
	}

	public function actionAddGiftToCart()
	{
		$act_id = input('act_id', 0, 'intval');
		$ru_id = input('ru_id', 0, 'intval');
		$select_gift = input('select_gift', '', array('trim', 'html_in'));
		$sess = $this->sess_cart;
		$favourable = favourable_info($act_id);

		if (empty($favourable)) {
			$result['error'] = 1;
			$result['message'] = L('favourable_not_exist');
			exit(json_encode($result));
		}

		if (!favourable_available($favourable)) {
			$result['error'] = 1;
			$result['message'] = L('favourable_not_available');
			exit(json_encode($result));
		}

		$cart_favourable = cart_favourable($ru_id);

		if (favourable_used($favourable, $cart_favourable)) {
			$result['error'] = 1;
			$result['message'] = L('gift_count_exceed');
			exit(json_encode($result));
		}

		if ($favourable['act_type'] == FAT_GOODS) {
			if (empty($select_gift)) {
				$result['error'] = 1;
				$result['message'] = L('pls_select_gift');
				exit(json_encode($result));
			}

			$sql = 'SELECT goods_name FROM {pre}cart' . ' WHERE ' . $this->sess_id . ' AND rec_type = \'' . CART_GENERAL_GOODS . '\'' . (' AND is_gift = \'' . $act_id . '\'') . ' AND goods_id ' . db_create_in($select_gift);
			$gift_name = $this->db->getCol($sql);

			if (!empty($gift_name)) {
				$result['error'] = 1;
				$result['message'] = sprintf(L('gift_in_cart'), join(',', $gift_name));
				exit(json_encode($result));
			}

			$count = isset($cart_favourable[$act_id]) ? $cart_favourable[$act_id] : 0;
			if (0 < $favourable['act_type_ext'] && $favourable['act_type_ext'] < $count + count($select_gift)) {
				$result['error'] = 1;
				$result['message'] = L('gift_count_exceed');
				exit(json_encode($result));
			}

			$select_gift = explode(',', $select_gift);
			$success = false;

			foreach ($favourable['gift'] as $gift) {
				if (in_array($gift['id'], $select_gift)) {
					add_gift_to_cart($act_id, $gift['id'], $gift['price'], $ru_id);
					$success = true;
				}
			}

			if ($success == true) {
				$cart_fav_box = cart_favourable_box($act_id);
				$result['goods_amount'] = $cart_fav_box['goods_amount'];
				$result['act_id'] = $act_id;
				$result['ru_id'] = $ru_id;
				$result['error'] = 0;
				$result['message'] = L('in_shopping_cart');
				exit(json_encode($result));
			}
			else {
				$result['error'] = 1;
				$result['message'] = '加入失败';
				exit(json_encode($result));
			}
		}

		$result['error'] = 1;
		$result['message'] = '加入失败';
		exit(json_encode($result));
	}

	public function actionAddToCartCombo()
	{
		if (IS_POST) {
			$result = array('error' => 0, 'message' => '', 'content' => '', 'goods_id' => '', 'url' => '');
			$goods = input('goods', '', 'stripcslashes');

			if (empty($goods)) {
				$result['error'] = 1;
				$result['url'] = url('/');
				exit(json_encode($result));
			}

			$goods = json_decode($goods);
			$goods_id = intval($goods->goods_id);
			$number = intval($goods->number);
			$attr = trim($goods->spec);
			$goods->spec = !empty($attr) ? explode(',', $attr) : array();
			$parent_attr = trim($goods->parent_attr);
			$goods_attr = trim($goods->goods_attr);
			$goods_attr = isset($goods_attr) ? explode(',', $goods_attr) : array();
			$warehouse_id = intval($goods->warehouse_id);
			$area_id = intval($goods->area_id);
			$area_city = intval($goods->area_city);
			$parent_id = intval($goods->parent);
			$group_id = trim($goods->group_id);
			if (!is_numeric($goods->number) || intval($goods->number) <= 0) {
				$result['error'] = 1;
				$result['message'] = L('invalid_number');
				exit(json_encode($result));
			}
			else {
				$xiangouInfo = get_purchasing_goods_info($goods_id);

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

				$cart_combo_extends = array('warehouse_id' => $warehouse_id, 'area_id' => $area_id, 'area_city' => $area_city);
				$res = addto_cart_combo($goods_id, $number, $goods->spec, $parent_id, $goods->group_id, $cart_combo_extends, $parent_attr);

				if ($res == true) {
					if (2 < C('shop.cart_confirm')) {
						$result['message'] = '';
					}
					else {
						$result['message'] = C('shop.cart_confirm') == 1 ? L('addto_cart_success_1') : L('addto_cart_success_2');
					}

					$result['group_id'] = $goods->group_id;
					$result['goods_id'] = stripslashes($goods_id);
					$result['content'] = '';
					$result['one_step_buy'] = C('shop.one_step_buy');
					$warehouse_area['warehouse_id'] = $warehouse_id;
					$warehouse_area['area_id'] = $area_id;
					$warehouse_area['area_city'] = $area_city;
					$combo_goods_info = get_combo_goods_info($goods_id, $number, $goods->spec, $parent_id, $warehouse_area);
					$result['fittings_price'] = $combo_goods_info['fittings_price'];
					$result['spec_price'] = $combo_goods_info['spec_price'];
					$result['goods_price'] = $combo_goods_info['goods_price'];
					$result['stock'] = $combo_goods_info['stock'];
					$result['parent'] = $parent_id;
				}
				else {
					$result['message'] = $this->err->last_message();
					$result['error'] = $this->err->error_no;
					$result['goods_id'] = stripslashes($goods_id);

					if (is_array($goods->spec)) {
						$result['product_spec'] = implode(',', $goods->spec);
					}
					else {
						$result['product_spec'] = $goods->spec;
					}
				}
			}

			$combo_goods = get_cart_combo_goods_list($goods_id, $parent_id, $goods->group_id);

			if (!empty($combo_goods)) {
				$result['combo_amount'] = $combo_goods['combo_amount'];
				$result['combo_number'] = $combo_goods['combo_number'];
			}

			$fitt_goods = isset($goods->fitt_goods) ? $goods->fitt_goods : array();

			if (!in_array($goods->goods_id, $fitt_goods)) {
				array_unshift($fitt_goods, $goods->goods_id);
			}

			$fittings = get_goods_fittings(array($parent_id), $warehouse_id, $area_id, $area_city, $goods->group_id, 1);

			if ($fittings) {
				$goods_info = get_goods_fittings_info($parent_id, $warehouse_id, $area_id, $area_city, $goods->group_id);
				$fittings = array_merge($goods_info, $fittings);
				$fittings = array_values($fittings);
				$fittings_interval = get_choose_goods_combo_cart($fittings);

				if (0 < $combo_goods['combo_number']) {
					$result['fittings_minMax'] = price_format($fittings_interval['all_price_ori']);
					$result['market_minMax'] = price_format($fittings_interval['all_market_price']);
					$result['save_minMaxPrice'] = price_format($fittings_interval['save_price_amount']);
				}
				else {
					$result['fittings_minMax'] = price_format($fittings_interval['fittings_min']) . '-' . number_format($fittings_interval['fittings_max'], 2, '.', '');
					$result['market_minMax'] = price_format($fittings_interval['market_min']) . '-' . number_format($fittings_interval['market_max'], 2, '.', '');

					if ($fittings_interval['save_minPrice'] == $fittings_interval['save_maxPrice']) {
						$result['save_minMaxPrice'] = price_format($fittings_interval['save_minPrice']);
					}
					else {
						$result['save_minMaxPrice'] = price_format($fittings_interval['save_minPrice']) . '-' . number_format($fittings_interval['save_maxPrice'], 2, '.', '');
					}
				}
			}

			$goodsGroup = explode('_', $goods->group_id);
			$result['groupId'] = $goodsGroup[2];
			$result['fitt_goods'] = $fitt_goods;
			$result['confirm_type'] = !!C('shop.cart_confirm') ? C('shop.cart_confirm') : 2;
			$result['warehouse_id'] = $warehouse_id;
			$result['area_id'] = $area_id;
			$result['area_city'] = $area_city;
			$result['goods_group'] = str_replace('_' . $parent_id, '', $goods->group_id);
			$result['add_group'] = $goods->add_group;
			exit(json_encode($result));
		}
	}

	public function actionDelInCartCombo()
	{
		if (IS_POST) {
			$result = array('error' => 0, 'message' => '', 'content' => '', 'goods_id' => '', 'url' => '');
			$goods = input('goods', '', 'stripcslashes');
			$goods_id = input('goods_id', 0, 'intval');
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
			$parent_id = intval($goods->parent);
			$group_id = trim($goods->group_id);
			$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart_combo') . ' WHERE ' . $this->sess_id . ' AND goods_id = \'' . $goods->goods_id . '\' AND group_id = \'' . $group_id . '\'';
			$GLOBALS['db']->query($sql);
			$sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('cart_combo') . ' WHERE ' . $this->sess_id . ' AND parent_id = \'' . $parent_id . '\' AND group_id = \'' . $group_id . '\'';
			$rec_count = $GLOBALS['db']->getOne($sql);

			if ($rec_count < 1) {
				$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart_combo') . ' WHERE ' . $this->sess_id . ' AND goods_id = \'' . $parent_id . '\' AND parent_id = 0  AND group_id = \'' . $group_id . '\'';
				$GLOBALS['db']->query($sql);
			}

			$combo_goods = get_cart_combo_goods_list($goods->goods_id, $parent_id, $group_id);

			if (empty($combo_goods['shop_price'])) {
				$shop_price = get_final_price($parent_id, 1, true, $goods->goods_attr, $warehouse_id, $area_id, $area_city);
				$combo_goods['combo_amount'] = price_format($shop_price, false);
			}

			$result['combo_amount'] = $combo_goods['combo_amount'];
			$result['combo_number'] = $combo_goods['combo_number'];

			if (0 < $combo_goods['combo_number']) {
				$goods_info = get_goods_fittings_info($parent_id, $warehouse_id, $area_id, $area_city, $group_id);
				$fittings = get_goods_fittings(array($parent_id), $warehouse_id, $area_id, $area_city, $group_id, 1);
			}
			else {
				$goods_info = get_goods_fittings_info($parent_id, $warehouse_id, $area_id, $area_city, '', 1);
				$fittings = get_goods_fittings(array($parent_id), $warehouse_id, $area_id, $area_city);
			}

			$fittings = array_merge($goods_info, $fittings);
			$fittings = array_values($fittings);
			$fittings_interval = get_choose_goods_combo_cart($fittings);

			if (0 < $combo_goods['combo_number']) {
				$result['fittings_minMax'] = price_format($fittings_interval['all_price_ori']);
				$result['market_minMax'] = price_format($fittings_interval['all_market_price']);
				$result['save_minMaxPrice'] = price_format($fittings_interval['save_price_amount']);
			}
			else {
				$result['fittings_minMax'] = price_format($fittings_interval['fittings_min']) . '-' . number_format($fittings_interval['fittings_max'], 2, '.', '');
				$result['market_minMax'] = price_format($fittings_interval['market_min']) . '-' . number_format($fittings_interval['market_max'], 2, '.', '');

				if ($fittings_interval['save_minPrice'] == $fittings_interval['save_maxPrice']) {
					$result['save_minMaxPrice'] = price_format($fittings_interval['save_minPrice']);
				}
				else {
					$result['save_minMaxPrice'] = price_format($fittings_interval['save_minPrice']) . '-' . number_format($fittings_interval['save_maxPrice'], 2, '.', '');
				}
			}

			$goodsGroup = explode('_', $group_id);
			$result['groupId'] = $goodsGroup[2];
			$result['error'] = 0;
			$result['group'] = substr($group_id, 0, strrpos($group_id, '_'));
			$result['parent'] = $parent_id;
			exit(json_encode($result));
		}
	}

	public function actionAddToCartGroup()
	{
		if (IS_POST) {
			$result = array('error' => 0, 'message' => '', 'content' => '', 'goods_id' => '', 'url' => '');
			$goods = input('goods', '', 'stripcslashes');

			if (empty($goods)) {
				$result['error'] = 1;
				$result['url'] = url('/');
				exit(json_encode($result));
			}

			$goods = json_decode($goods);
			$group_name = trim($goods->group_name);
			$group_id = $group_name . '_' . $goods->goods_id;
			$sql = 'SELECT rec_id FROM ' . $GLOBALS['ecs']->table('cart_combo') . ' WHERE ' . $this->sess_id . ' AND group_id = \'' . $group_id . '\' ORDER BY parent_id ASC limit 1';
			$res = $GLOBALS['db']->query($sql);

			if ($res) {
				$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $this->sess_id . ' AND group_id = \'' . $group_id . '\'';
				$GLOBALS['db']->query($sql);
				$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('cart') . '(' . 'user_id, session_id, goods_id, goods_sn, product_id, group_id, goods_name, market_price, goods_price, goods_number, goods_attr, is_real, ' . 'extension_code, parent_id, rec_type, is_gift, is_shipping, can_handsel, model_attr, goods_attr_id, warehouse_id, area_id, area_city, add_time' . ')' . ' SELECT ' . 'user_id, session_id, goods_id, goods_sn, product_id, group_id, goods_name, market_price, goods_price, goods_number, goods_attr, is_real, ' . 'extension_code, parent_id, rec_type, is_gift, is_shipping, can_handsel, model_attr, goods_attr_id, warehouse_id, area_id, area_city, add_time' . ' FROM ' . $GLOBALS['ecs']->table('cart_combo') . ' WHERE ' . $this->sess_id . ' AND group_id = \'' . $group_id . '\' ORDER BY parent_id ASC ';
				$GLOBALS['db']->query($sql);
				$sql = ' SELECT user_id FROM ' . $GLOBALS['ecs']->table('goods') . ' WHERE goods_id = \'' . $goods->goods_id . '\' ';
				$ru_id = $GLOBALS['db']->getOne($sql, true);
				$sql = 'UPDATE ' . $GLOBALS['ecs']->table('cart') . (' SET goods_number = \'' . $goods->number . '\', ru_id = \'' . $ru_id . '\' WHERE ') . $this->sess_id . ' AND group_id = \'' . $group_id . '\'';
				$GLOBALS['db']->query($sql);
				$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart_combo') . ' WHERE ' . $this->sess_id . ' AND group_id = \'' . $group_id . '\'';
				$GLOBALS['db']->query($sql);
				exit(json_encode($result));
			}
			else {
				$result['error'] = 1;
				$result['message'] = L('data_null');
				exit(json_encode($result));
			}
		}
	}

	public function actionGetCoupons()
	{
		if (IS_AJAX) {
			$ru_id = input('ru_id', 0, 'intval');
			$time = gmtime();
			$sql = 'SELECT * FROM {pre}coupons WHERE (cou_type = 3 OR cou_type = 4 ) AND cou_end_time > ' . $time . ' AND (( instr(cou_ok_user, ' . $_SESSION['user_rank'] . ') ) or (cou_goods = 0)) AND  review_status = 3 and ru_id = ' . $ru_id . ' ORDER BY cou_id DESC LIMIT 0, 10 ';
			$coupons = $GLOBALS['db']->getAll($sql);

			if ($coupons) {
				foreach ($coupons as $key => $value) {
					$coupons[$key]['cou_end_time'] = local_date('Y.m.d', $value['cou_end_time']);
					$coupons[$key]['cou_start_time'] = local_date('Y.m.d', $value['cou_start_time']);

					if (0 < $_SESSION['user_id']) {
						$user_num = dao('coupons_user')->where(array('cou_id' => $value['cou_id'], 'user_id' => $_SESSION['user_id']))->count();
						if (0 < $user_num && $value['cou_user_num'] <= $user_num) {
							$coupons[$key]['cou_is_receive'] = 1;
						}
						else {
							$coupons[$key]['cou_is_receive'] = 0;
						}
					}

					$cou_num = dao('coupons_user')->where(array('cou_id' => $value['cou_id']))->count();
					$coupons[$key]['enable_ling'] = !empty($cou_num) && $value['cou_total'] <= $cou_num ? 1 : 0;
				}

				$GLOBALS['smarty']->assign('coupons_list', $coupons);
				$result['coupons_content'] = $this->fetch('ajax_coupons');
				exit(json_encode($result));
			}

			$result['coupons_content'] = '';
			exit(json_encode($result));
		}
	}

	public function actionGetFavourable()
	{
		if (IS_AJAX) {
			$goods_id = input('goods_id', 0, 'intval');
			$ru_id = input('ru_id', 0, 'intval');
			$act_id = input('act_id', 0, 'intval');
			$rec_id = input('rec_id', 0, 'intval');
			$result = array('content' => '', 'error' => 0);
			$gmtime = gmtime();

			if (0 < $ru_id) {
				$ext_where = '';

				if ($GLOBALS['_CFG']['region_store_enabled']) {
					$ext_where = ' OR userFav_type_ext <> \'\' ';
				}

				$fav_where = '(user_id = \'' . $ru_id . '\' OR userFav_type = 1 ' . $ext_where . ' )';
			}
			else {
				$fav_where = 'user_id = \'' . $ru_id . '\'';
			}

			$user_rank = ',' . $_SESSION['user_rank'] . ',';
			$favourable = array();
			$ext_where = '';

			if ($GLOBALS['_CFG']['region_store_enabled']) {
				$ext_where = ', userFav_type_ext, rs_id ';
			}

			$sql = 'SELECT act_id, act_range, act_range_ext, act_name, start_time, end_time, act_type, userFav_type $ext_where FROM ' . $GLOBALS['ecs']->table('favourable_activity') . (' WHERE review_status = 3 AND start_time <= \'' . $gmtime . '\' AND end_time >= \'' . $gmtime . '\' AND ') . $fav_where;

			if (!empty($goods_id)) {
				$sql .= ' AND CONCAT(\',\', user_rank, \',\') LIKE \'%' . $user_rank . '%\'';
			}

			$sql .= ' LIMIT 15';
			$res = $GLOBALS['db']->getAll($sql);

			if (empty($goods_id)) {
				foreach ($res as $rows) {
					$favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
					$favourable[$rows['act_id']]['url'] = 'activity.php';
					$favourable[$rows['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $rows['start_time']), local_date('Y-m-d', $rows['end_time']));
					$favourable[$rows['act_id']]['sort'] = $rows['start_time'];
					$favourable[$rows['act_id']]['type'] = 'favourable';
					$favourable[$rows['act_id']]['act_type'] = $rows['act_type'];
				}
			}
			else {
				if ($goods) {
					$category_id = isset($goods['cat_id']) && !empty($goods['cat_id']) ? $goods['cat_id'] : 0;
					$brand_id = isset($goods['brand_id']) && !empty($goods['brand_id']) ? $goods['brand_id'] : 0;
				}
				else {
					$sql = 'SELECT g.cat_id, g.brand_id FROM ' . $GLOBALS['ecs']->table('goods') . ' as g' . (' WHERE g.goods_id = \'' . $goods_id . '\' LIMIT 1');
					$row = $GLOBALS['db']->getRow($sql);
					$category_id = $row['cat_id'];
					$brand_id = $row['brand_id'];
				}

				foreach ($res as $rows) {
					if ($rows['act_range'] == FAR_ALL) {
						$mer_ids = true;

						if ($GLOBALS['_CFG']['region_store_enabled']) {
							$mer_ids = get_favourable_merchants($rows['userFav_type'], $rows['userFav_type_ext'], $rows['rs_id'], 1, $ru_id);
						}

						if ($mer_ids) {
							$favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
							$favourable[$rows['act_id']]['url'] = 'activity.php';
							$favourable[$rows['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $rows['start_time']), local_date('Y-m-d', $rows['end_time']));
							$favourable[$rows['act_id']]['sort'] = $rows['start_time'];
							$favourable[$rows['act_id']]['type'] = 'favourable';
							$favourable[$rows['act_id']]['act_type'] = $rows['act_type'];
						}
					}
					else if ($rows['act_range'] == FAR_CATEGORY) {
						$id_list = array();
						$raw_id_list = explode(',', $rows['act_range_ext']);

						foreach ($raw_id_list as $id) {
							$cat_keys = get_array_keys_cat(intval($id));
							$list_array[$rows['act_id']][$id] = $cat_keys;
						}

						$list_array = !empty($list_array) ? array_merge($raw_id_list, $list_array[$rows['act_id']]) : $raw_id_list;
						$id_list = arr_foreach($list_array);
						$id_list = array_unique($id_list);
						$ids = join(',', array_unique($id_list));

						if (strpos(',' . $ids . ',', ',' . $category_id . ',') !== false) {
							$favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
							$favourable[$rows['act_id']]['url'] = 'activity.php';
							$favourable[$rows['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $rows['start_time']), local_date('Y-m-d', $rows['end_time']));
							$favourable[$rows['act_id']]['sort'] = $rows['start_time'];
							$favourable[$rows['act_id']]['type'] = 'favourable';
							$favourable[$rows['act_id']]['act_type'] = $rows['act_type'];
						}
					}
					else if ($rows['act_range'] == FAR_BRAND) {
						$rows['act_range_ext'] = return_act_range_ext($rows['act_range_ext'], $rows['userFav_type'], $rows['act_range']);

						if (strpos(',' . $rows['act_range_ext'] . ',', ',' . $brand_id . ',') !== false) {
							$favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
							$favourable[$rows['act_id']]['url'] = 'activity.php';
							$favourable[$rows['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $rows['start_time']), local_date('Y-m-d', $rows['end_time']));
							$favourable[$rows['act_id']]['sort'] = $rows['start_time'];
							$favourable[$rows['act_id']]['type'] = 'favourable';
							$favourable[$rows['act_id']]['act_type'] = $rows['act_type'];
						}
					}
					else if ($rows['act_range'] == FAR_GOODS) {
						if (strpos(',' . $rows['act_range_ext'] . ',', ',' . $goods_id . ',') !== false) {
							$mer_ids = true;

							if ($GLOBALS['_CFG']['region_store_enabled']) {
								$mer_ids = get_favourable_merchants($rows['userFav_type'], $rows['userFav_type_ext'], $rows['rs_id'], 1, $ru_id);
							}

							if ($mer_ids) {
								$favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
								$favourable[$rows['act_id']]['url'] = 'activity.php';
								$favourable[$rows['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $rows['start_time']), local_date('Y-m-d', $rows['end_time']));
								$favourable[$rows['act_id']]['sort'] = $rows['start_time'];
								$favourable[$rows['act_id']]['type'] = 'favourable';
								$favourable[$rows['act_id']]['act_type'] = $rows['act_type'];
							}
						}
					}
				}
			}

			if ($favourable) {
				foreach ($favourable as $key => $val) {
					if ($key == $act_id) {
						$favourable[$key]['is_checked'] = 1;
					}

					continue;
				}
			}

			$GLOBALS['smarty']->assign('rec_id', $rec_id);
			$GLOBALS['smarty']->assign('goods_id', $goods_id);
			$GLOBALS['smarty']->assign('favourable', $favourable);
			$result['content'] = $this->fetch('ajax_favourable');
			exit(json_encode($result));
		}
	}

	public function actionChangeFav()
	{
		if (IS_AJAX) {
			$act_id = input('aid', 0, 'intval');
			$goods_id = input('gid', 0, 'intval');
			$rec_id = input('rid', 0, 'intval');
			$result = array('content' => '');
			$sql = 'SELECT act_id FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $this->sess_id . (' AND rec_id = \'' . $rec_id . '\'');
			$old_act_id = $GLOBALS['db']->getOne($sql);

			if ($old_act_id) {
				$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE ' . $this->sess_id . (' AND is_gift = \'' . $old_act_id . '\'');
				$GLOBALS['db']->query($sql);
			}

			update_cart_goods_fav($rec_id, $act_id);
			$_SESSION['flow_type'] = CART_GENERAL_GOODS;
			$cart_goods = get_cart_goods('', 1, $this->region_id, $this->area_id, $this->area_city);
			$merchant_goods_list = cart_by_favourable($cart_goods['goods_list'], $cart_goods['total']['cart_value']);
			$discount = compute_discount(3);
			$fav_amount = price_format($discount['discount']);
			$this->assign('fav_amount', $fav_amount);
			$this->assign('goods_list', $merchant_goods_list);
			$this->assign('total', $cart_goods['total']);
			$this->assign('cart_value', $cart_goods['total']['cart_value']);
			$result['content'] = $this->fetch('cart_box');
			exit(json_encode($result));
		}
	}

	protected function init_params()
	{
		if (!isset($_COOKIE['province'])) {
			$area_array = get_ip_area_name();

			if ($area_array['county_level'] == 2) {
				$date = array('region_id', 'parent_id', 'region_name');
				$where = 'region_name = \'' . $area_array['area_name'] . '\' AND region_type = 2';
				$city_info = get_table_date('region', $where, $date, 1);
				$date = array('region_id', 'region_name');
				$where = 'region_id = \'' . $city_info[0]['parent_id'] . '\'';
				$province_info = get_table_date('region', $where, $date);
				$where = 'parent_id = \'' . $city_info[0]['region_id'] . '\' order by region_id asc limit 0, 1';
				$district_info = get_table_date('region', $where, $date, 1);
			}
			else if ($area_array['county_level'] == 1) {
				$area_name = $area_array['area_name'];
				$date = array('region_id', 'region_name');
				$where = 'region_name = \'' . $area_name . '\'';
				$province_info = get_table_date('region', $where, $date);
				$where = 'parent_id = \'' . $province_info['region_id'] . '\' order by region_id asc limit 0, 1';
				$city_info = get_table_date('region', $where, $date, 1);
				$where = 'parent_id = \'' . $city_info[0]['region_id'] . '\' order by region_id asc limit 0, 1';
				$district_info = get_table_date('region', $where, $date, 1);
			}
		}

		$order_area = get_user_order_area($this->user_id);
		$user_area = get_user_area_reg($this->user_id);
		if ($order_area['province'] && 0 < $this->user_id) {
			$this->province_id = $order_area['province'];
			$this->city_id = $order_area['city'];
			$this->district_id = $order_area['district'];
		}
		else {
			if (0 < $user_area['province']) {
				$this->province_id = $user_area['province'];
				cookie('province', $user_area['province']);
				$this->region_id = get_province_id_warehouse($this->province_id);
			}
			else {
				$sql = 'select region_name from ' . $this->ecs->table('region_warehouse') . ' where regionId = \'' . $province_info['region_id'] . '\'';
				$warehouse_name = $this->db->getOne($sql);
				$this->province_id = $province_info['region_id'];
				$cangku_name = $warehouse_name;
				$this->region_id = get_warehouse_name_id(0, $cangku_name);
			}

			if (0 < $user_area['city']) {
				$this->city_id = $user_area['city'];
				cookie('city', $user_area['city']);
			}
			else {
				$this->city_id = $city_info[0]['region_id'];
			}

			if (0 < $user_area['district']) {
				$this->district_id = $user_area['district'];
				cookie('district', $user_area['district']);
			}
			else {
				$this->district_id = $district_info[0]['region_id'];
			}
		}

		$this->province_id = isset($_COOKIE['province']) ? $_COOKIE['province'] : $this->province_id;
		$child_num = get_region_child_num($this->province_id);

		if (0 < $child_num) {
			$this->city_id = isset($_COOKIE['city']) ? $_COOKIE['city'] : $this->city_id;
		}
		else {
			$this->city_id = '';
		}

		$child_num = get_region_child_num($this->city_id);

		if (0 < $child_num) {
			$this->district_id = isset($_COOKIE['district']) ? $_COOKIE['district'] : $this->district_id;
		}
		else {
			$this->district_id = '';
		}

		$this->region_id = !isset($_COOKIE['region_id']) ? $this->region_id : $_COOKIE['region_id'];
		$goods_warehouse = get_warehouse_goods_region($this->province_id);

		if ($goods_warehouse) {
			$this->regionId = $goods_warehouse['region_id'];
			if ($_COOKIE['region_id'] && $_COOKIE['regionid']) {
				$gw = 0;
			}
			else {
				$gw = 1;
			}
		}

		if ($gw) {
			$this->region_id = $this->regionId;
			cookie('area_region', $this->region_id);
		}

		cookie('goodsId', $this->goods_id);
		$sellerInfo = get_seller_info_area();

		if (empty($this->province_id)) {
			$this->province_id = $sellerInfo['province'];
			$this->city_id = $sellerInfo['city'];
			$this->district_id = 0;
			cookie('province', $this->province_id);
			cookie('city', $this->city_id);
			cookie('district', $this->district_id);
			$this->region_id = get_warehouse_goods_region($this->province_id);
		}

		$other = array('province_id' => $this->province_id, 'city_id' => $this->city_id);
		$warehouse_area_info = get_warehouse_area_info($other);
		$this->area_city = $warehouse_area_info['city_id'];
		cookie('area_city ', $this->area_city);
		$this->area_info = get_area_info($this->province_id);
	}
}

?>
