<?php
//SHOPROOO商城资源
namespace App\Http\Team\Controllers;

class Goods extends \App\Http\Base\Controllers\Frontend
{
	private $user_id = 0;
	private $goods_id = 0;
	private $region_id = 0;
	private $area_info = array();

	public function __construct()
	{
		parent::__construct();
		L(require LANG_PATH . C('shop.lang') . '/team.php');
		$files = array('order', 'clips', 'payment', 'transaction');
		$this->load_helper($files);
		$this->user_id = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
		$this->keywords = I('request.keywords');
		$this->goods_id = I('id', 0, 'intval');
		$this->team_id = I('team_id', 0, 'intval');
		$this->page = 1;
		$this->size = 10;
	}

	public function actionIndex()
	{
		if ($this->goods_id == 0) {
			ecs_header("Location: ./\n");
			exit();
		}

		$goods = get_goods_info($this->goods_id, $this->region_id, $this->area_info['region_id']);
		$team = $this->db->table('team_goods')->field('team_price,team_num,limit_num,astrict_num,is_team')->where(array('goods_id' => $this->goods_id))->find();
		$goods['team_price'] = price_format($team['team_price']);
		$goods['team_num'] = $team['team_num'];
		$goods['limit_num'] = $team['limit_num'];
		$goods['astruct_num'] = $team['astrict_num'];

		if ($team['is_team'] == 0) {
			show_message('该品团活动已结束，去查看新的活动吧', '查看新的活动', url('team/index/userranking'), 'success');
			exit();
		}

		$info = $this->db->table('goods')->field('goods_desc')->where(array('goods_id' => $this->goods_id))->find();
		$start_date = $goods['xiangou_start_date'];
		$end_date = $goods['xiangou_end_date'];
		$nowTime = gmtime();
		if (($start_date < $nowTime) && ($nowTime < $end_date)) {
			$xiangou = 1;
		}
		else {
			$xiangou = 0;
		}

		$order_goods = get_for_purchasing_goods($start_date, $end_date, $this->goods_id, $this->user_id);
		$this->assign('xiangou', $xiangou);
		$this->assign('orderG_number', $order_goods['goods_number']);
		$properties = get_goods_properties($this->goods_id, $this->region_id, $this->area_info['region_id']);
		$sql = 'SELECT ld.goods_desc FROM {pre}link_desc_goodsid AS dg, {pre}link_goods_desc AS ld WHERE dg.goods_id = ' . $this->goods_id . '  AND dg.d_id = ld.id';
		$link_desc = $this->db->getOne($sql);

		if (empty($info['goods_desc'])) {
			$info['goods_desc'] = $link_desc;
		}

		$info['goods_desc'] = str_replace('src="images/upload', 'src="' . __STATIC__ . '/images/upload', $info['goods_desc']);
		$this->assign('goods_desc', $info['goods_desc']);
		$default_spe = '';

		if ($properties['spe']) {
			foreach ($properties['spe'] as $k => $v) {
				if ($v['attr_type'] == 1) {
					if (0 < $v['is_checked']) {
						foreach ($v['values'] as $key => $val) {
							$default_spe .= ($val['checked'] ? $val['label'] . '、' : '');
						}
					}
					else {
						foreach ($v['values'] as $key => $val) {
							if ($key == 0) {
								$default_spe .= $val['label'] . '、';
							}
						}
					}
				}
			}
		}

		$this->assign('default_spe', $default_spe);
		$this->assign('properties', $properties['pro']);
		$this->assign('specification', $properties['spe']);
		$this->assign('pictures', get_goods_gallery($this->goods_id));
		$cart_num = cart_number();
		$this->assign('cart_num', $cart_num);
		$mc_all = ments_count_all($this->goods_id);
		$mc_one = ments_count_rank_num($this->goods_id, 1);
		$mc_two = ments_count_rank_num($this->goods_id, 2);
		$mc_three = ments_count_rank_num($this->goods_id, 3);
		$mc_four = ments_count_rank_num($this->goods_id, 4);
		$mc_five = ments_count_rank_num($this->goods_id, 5);
		$comment_all = get_conments_stars($mc_all, $mc_one, $mc_two, $mc_three, $mc_four, $mc_five);

		if (0 < $goods['user_id']) {
			$merchants_goods_comment = get_merchants_goods_comment($goods['user_id']);
			$this->assign('merch_cmt', $merchants_goods_comment);
		}

		$this->assign('comment_all', $comment_all);
		$good_comment = get_good_comment($this->goods_id, 4, 1, 0, 1);
		$this->assign('good_comment', $good_comment);

		if ($_SESSION['user_id']) {
			$where['user_id'] = $_SESSION['user_id'];
			$where['goods_id'] = $this->goods_id;
			$rs = $this->db->table('collect_goods')->where($where)->count();

			if (0 < $rs) {
				$this->assign('goods_collect', 1);
			}
		}

		$sql = 'SELECT count(*) FROM ' . $this->ecs->table('collect_store') . ' WHERE ru_id = ' . $goods['user_id'];
		$collect_number = $this->db->getOne($sql);
		$this->assign('collect_number', $collect_number ? $collect_number : 0);
		$sql = 'select b.is_IM,a.ru_id,a.province, a.city, a.kf_type, a.kf_ww, a.kf_qq, a.meiqia, a.shop_name, a.kf_appkey,kf_secretkey from {pre}seller_shopinfo as a left join {pre}merchants_shop_information as b on a.ru_id=b.user_id where a.ru_id=\'' . $goods['user_id'] . '\' ';
		$basic_info = $this->db->getRow($sql);
		$info_ww = ($basic_info['kf_ww'] ? explode("\r\n", $basic_info['kf_ww']) : '');
		$info_qq = ($basic_info['kf_qq'] ? explode("\r\n", $basic_info['kf_qq']) : '');
		$kf_ww = ($info_ww ? $info_ww[0] : '');
		$kf_qq = ($info_qq ? $info_qq[0] : '');
		$basic_ww = ($kf_ww ? explode('|', $kf_ww) : '');
		$basic_qq = ($kf_qq ? explode('|', $kf_qq) : '');
		$basic_info['kf_ww'] = $basic_ww ? $basic_ww[1] : '';
		$basic_info['kf_qq'] = $basic_qq ? $basic_qq[1] : '';
		if ((($basic_info['is_im'] == 1) || ($basic_info['ru_id'] == 0)) && !empty($basic_info['kf_appkey'])) {
			$basic_info['kf_appkey'] = $basic_info['kf_appkey'];
		}
		else {
			$basic_info['kf_appkey'] = '';
		}

		$basic_date = array('region_name');
		$basic_info['province'] = get_table_date('region', 'region_id = \'' . $basic_info['province'] . '\'', $basic_date, 2);
		$basic_info['city'] = get_table_date('region', 'region_id= \'' . $basic_info['city'] . '\'', $basic_date, 2) . '市';
		$this->assign('basic_info', $basic_info);
		$new_goods = team_new_goods('is_new', $goods['user_id']);
		$this->assign('new_goods', $new_goods);
		$team_log = team_goods_log($this->goods_id);
		$this->assign('team_log', $team_log);
		$this->assign('team_id', $this->team_id);
		$this->assign('goods', $goods);
		$this->assign('goods_id', $goods['goods_id']);
		$this->assign('keywords', $goods['keywords']);
		$this->assign('description', $goods['goods_brief']);

		if (strtolower(substr($goods['goods_img'], 0, 4)) == 'http') {
			$page_img = $goods['goods_img'];
		}
		else {
			$page_img = __HOST__ . $goods['goods_img'];
		}

		$this->assign('page_img', $page_img);
		$this->assign('page_title', $goods['goods_name']);
		$this->display();
	}

	public function actionInfo()
	{
		$info = $this->db->table('goods')->field('goods_desc')->where(array('goods_id' => $this->goods_id))->find();
		$properties = get_goods_properties($this->goods_id, $this->region_id, $this->area_info['region_id']);
		$sql = 'SELECT ld.goods_desc FROM {pre}link_desc_goodsid AS dg, {pre}link_goods_desc AS ld WHERE dg.goods_id = ' . $this->goods_id . '  AND dg.d_id = ld.id';
		$link_desc = $this->db->getOne($sql);

		if (empty($info['goods_desc'])) {
			$info['goods_desc'] = $link_desc;
		}

		$info['goods_desc'] = str_replace('src="images/upload', 'src="' . __STATIC__ . '/images/upload', $info['goods_desc']);
		$this->assign('goods_desc', $info['goods_desc']);
		$this->assign('properties', $properties['pro']);
		$this->assign('page_title', L('team_goods_info'));
		$this->assign('goods_id', $this->goods_id);
		$this->display();
	}

	public function actionComment($img = 0)
	{
		if (IS_AJAX) {
			$rank = I('rank', '');
			$page = I('page');
			$page = ($page - 1) * $this->size;

			if ($rank == 'img') {
				$rank = 5;
				$img = 1;
			}

			$arr = get_good_comment_as($this->goods_id, $rank, 1, $page, $this->size);
			$comments = $arr['arr'];

			if ($img) {
				foreach ($comments as $key => $val) {
					if ($val['thumb'] == 0) {
						unset($comments[$key]);
					}
				}

				$rank = 'img';
			}

			$show = (0 < count($comments) ? 1 : 0);
			$max = (0 < $page ? 0 : 1);
			exit(json_encode(array('comments' => $comments, 'rank' => $rank, 'show' => $show, 'reset' => $max, 'totalPage' => $arr['max'], 'top' => 1)));
		}

		$this->assign('img', $img);
		$this->assign('info', commentCol($this->goods_id));
		$this->assign('id', $this->goods_id);
		$this->assign('page_title', L('team_goods_comment'));
		$this->display();
	}

	public function actionPrice()
	{
		$res = array('err_msg' => '', 'result' => '', 'qty' => 1);
		$attr = I('attr');
		$number = I('number', 1, 'intval');
		$attr_id = (!empty($attr) ? explode(',', $attr) : array());
		$warehouse_id = I('request.warehouse_id', 0, 'intval');
		$area_id = I('request.area_id', 0, 'intval');
		$onload = I('request.onload', '', 'trim');
		$goods_attr = (isset($_REQUEST['goods_attr']) ? explode(',', $_REQUEST['goods_attr']) : array());
		$attr_ajax = get_goods_attr_ajax($this->goods_id, $goods_attr, $attr_id);
		$goods = get_goods_info($this->goods_id, $warehouse_id, $area_id);
		$team = $this->db->table('team_goods')->field('team_price,team_num,astrict_num')->where(array('goods_id' => $this->goods_id))->find();
		$goods['team_price'] = price_format($team['team_price']);
		$goods['team_num'] = $team['team_num'];
		$goods['astruct_num'] = $team['astrict_num'];

		if ($this->goods_id == 0) {
			$res['err_msg'] = L('err_change_attr');
			$res['err_no'] = 1;
		}
		else {
			if ($number == 0) {
				$res['qty'] = $number = 1;
			}
			else {
				$res['qty'] = $number;
			}

			$products = get_warehouse_id_attr_number($this->goods_id, $_REQUEST['attr'], $goods['user_id'], $warehouse_id, $area_id);
			$attr_number = $products['product_number'];

			if ($goods['model_attr'] == 1) {
				$table_products = 'products_warehouse';
				$type_files = ' and warehouse_id = \'' . $warehouse_id . '\'';
			}
			else if ($goods['model_attr'] == 2) {
				$table_products = 'products_area';
				$type_files = ' and area_id = \'' . $area_id . '\'';
			}
			else {
				$table_products = 'products';
				$type_files = '';
			}

			$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table($table_products) . ' WHERE goods_id = \'' . $this->goods_id . '\'' . $type_files . ' LIMIT 0, 1';
			$prod = $GLOBALS['db']->getRow($sql);

			if ($goods['goods_type'] == 0) {
				$attr_number = $goods['goods_number'];
			}
			else {
				if (empty($prod)) {
					$attr_number = $goods['goods_number'];
				}

				if (!empty($prod) && ($GLOBALS['_CFG']['add_shop_price'] == 0)) {
					if (empty($attr_number)) {
						$attr_number = $goods['goods_number'];
					}
				}
			}

			$attr_number = (!empty($attr_number) ? $attr_number : 0);
			$res['attr_number'] = $attr_number;
			$res['limit_number'] = $attr_number < $number ? ($attr_number ? $attr_number : 1) : $number;
			$shop_price = get_final_price($this->goods_id, $number, true, $attr_id, $warehouse_id, $area_id);
			$res['shop_price'] = price_format($shop_price);
			$res['market_price'] = $goods['market_price'];
			$res['show_goods'] = 0;
			if ($goods_attr && ($GLOBALS['_CFG']['add_shop_price'] == 0)) {
				if (count($goods_attr) == count($attr_ajax['attr_id'])) {
					$res['show_goods'] = 1;
				}
			}

			$spec_price = get_final_price($this->goods_id, $number, true, $attr_id, $warehouse_id, $area_id, 1, 0, 0, $res['show_goods']);
			if (($GLOBALS['_CFG']['add_shop_price'] == 0) && empty($spec_price)) {
				$spec_price = $shop_price;
			}

			$res['spec_price'] = price_format($spec_price);
			$martetprice_amount = $spec_price + $goods['marketPrice'];
			$res['marketPrice_amount'] = price_format($spec_price + $goods['marketPrice']);
			$res['discount'] = round($shop_price / $martetprice_amount, 2) * 10;
			$res['result'] = price_format($shop_price * $number);
		}

		$goods_fittings = get_goods_fittings_info($this->goods_id, $warehouse_id, $area_id, '', 1);
		$fittings_list = get_goods_fittings(array($this->goods_id), $warehouse_id, $area_id);

		if ($fittings_list) {
			if (is_array($fittings_list)) {
				foreach ($fittings_list as $vo) {
					$fittings_index[$vo['group_id']] = $vo['group_id'];
				}
			}

			ksort($fittings_index);
			$merge_fittings = get_merge_fittings_array($fittings_index, $fittings_list);
			$fitts = get_fittings_array_list($merge_fittings, $goods_fittings);

			for ($i = 0; $i < count($fitts); $i++) {
				$fittings_interval = $fitts[$i]['fittings_interval'];
				$res['fittings_interval'][$i]['fittings_minMax'] = price_format($fittings_interval['fittings_min']) . '-' . number_format($fittings_interval['fittings_max'], 2, '.', '');
				$res['fittings_interval'][$i]['market_minMax'] = price_format($fittings_interval['market_min']) . '-' . number_format($fittings_interval['market_max'], 2, '.', '');

				if ($fittings_interval['save_minPrice'] == $fittings_interval['save_maxPrice']) {
					$res['fittings_interval'][$i]['save_minMaxPrice'] = price_format($fittings_interval['save_minPrice']);
				}
				else {
					$res['fittings_interval'][$i]['save_minMaxPrice'] = price_format($fittings_interval['save_minPrice']) . '-' . number_format($fittings_interval['save_maxPrice'], 2, '.', '');
				}

				$res['fittings_interval'][$i]['groupId'] = $fittings_interval['groupId'];
			}
		}

		if ($GLOBALS['_CFG']['open_area_goods'] == 1) {
			$area_list = get_goods_link_area_list($this->goods_id, $goods['user_id']);

			if ($area_list['goods_area']) {
				if (!in_array($area_id, $area_list['goods_area'])) {
					$res['err_no'] = 2;
				}
			}
			else {
				$res['err_no'] = 2;
			}
		}

		exit(json_encode($res));
	}

	public function actionTeamprice()
	{
		$res = array('err_msg' => '', 'result' => '', 'qty' => 1);
		$attr = I('attr');
		$number = I('number', 1, 'intval');
		$attr_id = (!empty($attr) ? explode(',', $attr) : array());
		$warehouse_id = I('request.warehouse_id', 0, 'intval');
		$area_id = I('request.area_id', 0, 'intval');
		$onload = I('request.onload', '', 'trim');
		$goods_attr = (isset($_REQUEST['goods_attr']) ? explode(',', $_REQUEST['goods_attr']) : array());
		$attr_ajax = get_goods_attr_ajax($this->goods_id, $goods_attr, $attr_id);
		$goods = get_goods_info($this->goods_id, $warehouse_id, $area_id);
		$team = $this->db->table('team_goods')->field('team_price,team_num,astrict_num')->where(array('goods_id' => $this->goods_id))->find();
		$goods['team_price'] = price_format($team['team_price']);
		$goods['team_num'] = $team['team_num'];
		$goods['astruct_num'] = $team['astrict_num'];

		if ($this->goods_id == 0) {
			$res['err_msg'] = L('err_change_attr');
			$res['err_no'] = 1;
		}
		else {
			if ($number == 0) {
				$res['qty'] = $number = 1;
			}
			else {
				$res['qty'] = $number;
			}

			$products = get_warehouse_id_attr_number($this->goods_id, $_REQUEST['attr'], $goods['user_id'], $warehouse_id, $area_id);
			$attr_number = $products['product_number'];

			if ($goods['model_attr'] == 1) {
				$table_products = 'products_warehouse';
				$type_files = ' and warehouse_id = \'' . $warehouse_id . '\'';
			}
			else if ($goods['model_attr'] == 2) {
				$table_products = 'products_area';
				$type_files = ' and area_id = \'' . $area_id . '\'';
			}
			else {
				$table_products = 'products';
				$type_files = '';
			}

			$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table($table_products) . ' WHERE goods_id = \'' . $this->goods_id . '\'' . $type_files . ' LIMIT 0, 1';
			$prod = $GLOBALS['db']->getRow($sql);

			if ($goods['goods_type'] == 0) {
				$attr_number = $goods['goods_number'];
			}
			else {
				if (empty($prod)) {
					$attr_number = $goods['goods_number'];
				}

				if (!empty($prod) && ($GLOBALS['_CFG']['add_shop_price'] == 0)) {
					if (empty($attr_number)) {
						$attr_number = $goods['goods_number'];
					}
				}
			}

			$attr_number = (!empty($attr_number) ? $attr_number : 0);
			$res['attr_number'] = $attr_number;
			$res['limit_number'] = $attr_number < $number ? ($attr_number ? $attr_number : 1) : $number;
			$shop_price = tean_get_final_price($this->goods_id, $number, true, $attr_id, $warehouse_id, $area_id);
			$res['shop_price'] = price_format($shop_price);
			$res['market_price'] = $goods['market_price'];
			$res['show_goods'] = 0;
			if ($goods_attr && ($GLOBALS['_CFG']['add_shop_price'] == 0)) {
				if (count($goods_attr) == count($attr_ajax['attr_id'])) {
					$res['show_goods'] = 1;
				}
			}

			$spec_price = tean_get_final_price($this->goods_id, $number, true, $attr_id, $warehouse_id, $area_id, 1, 0, 0, $res['show_goods']);
			if (($GLOBALS['_CFG']['add_shop_price'] == 0) && empty($spec_price)) {
				$spec_price = $shop_price;
			}

			$res['spec_price'] = price_format($spec_price);
			$martetprice_amount = $spec_price + $goods['marketPrice'];
			$res['marketPrice_amount'] = price_format($spec_price + $goods['marketPrice']);
			$res['discount'] = round($shop_price / $martetprice_amount, 2) * 10;
			$res['result'] = price_format($shop_price * $number);
		}

		$goods_fittings = get_goods_fittings_info($this->goods_id, $warehouse_id, $area_id, '', 1);
		$fittings_list = get_goods_fittings(array($this->goods_id), $warehouse_id, $area_id);

		if ($fittings_list) {
			if (is_array($fittings_list)) {
				foreach ($fittings_list as $vo) {
					$fittings_index[$vo['group_id']] = $vo['group_id'];
				}
			}

			ksort($fittings_index);
			$merge_fittings = get_merge_fittings_array($fittings_index, $fittings_list);
			$fitts = get_fittings_array_list($merge_fittings, $goods_fittings);

			for ($i = 0; $i < count($fitts); $i++) {
				$fittings_interval = $fitts[$i]['fittings_interval'];
				$res['fittings_interval'][$i]['fittings_minMax'] = price_format($fittings_interval['fittings_min']) . '-' . number_format($fittings_interval['fittings_max'], 2, '.', '');
				$res['fittings_interval'][$i]['market_minMax'] = price_format($fittings_interval['market_min']) . '-' . number_format($fittings_interval['market_max'], 2, '.', '');

				if ($fittings_interval['save_minPrice'] == $fittings_interval['save_maxPrice']) {
					$res['fittings_interval'][$i]['save_minMaxPrice'] = price_format($fittings_interval['save_minPrice']);
				}
				else {
					$res['fittings_interval'][$i]['save_minMaxPrice'] = price_format($fittings_interval['save_minPrice']) . '-' . number_format($fittings_interval['save_maxPrice'], 2, '.', '');
				}

				$res['fittings_interval'][$i]['groupId'] = $fittings_interval['groupId'];
			}
		}

		if ($GLOBALS['_CFG']['open_area_goods'] == 1) {
			$area_list = get_goods_link_area_list($this->goods_id, $goods['user_id']);

			if ($area_list['goods_area']) {
				if (!in_array($area_id, $area_list['goods_area'])) {
					$res['err_no'] = 2;
				}
			}
			else {
				$res['err_no'] = 2;
			}
		}

		exit(json_encode($res));
	}

	public function actionTeambuy()
	{
		$this->check_login();
		$number = I('number', 1, 'intval');
		$goods_id = I('goods_id', 0, 'intval');
		$team_id = I('team_id', 0, 'intval');

		if (empty($goods_id)) {
			ecs_header("Location: ./\n");
			exit();
		}

		if (!is_numeric($number) || ($number <= 0)) {
			show_message(L('invalid_number'), '', '', 'error');
		}

		$goods = team_goods_info($goods_id);
		$specs = (isset($_POST['goods_spec']) ? htmlspecialchars(trim($_POST['goods_spec'])) : '');
		$specs = '';

		foreach ($_POST as $key => $value) {
			if (strpos($key, 'spec_') !== false) {
				$specs .= ',' . intval($value);
			}
		}

		$specs = trim($specs, ',');

		if ($specs) {
			$_specs = explode(',', $specs);
			$product_info = get_products_info($goods['goods_id'], $_specs, $warehouse_id, $this->area_id);
		}

		empty($product_info) ? $product_info = array('product_number' => 0, 'product_id' => 0) : '';

		if ($goods['model_attr'] == 1) {
			$table_products = 'products_warehouse';
			$type_files = ' and warehouse_id = \'' . $warehouse_id . '\'';
		}
		else if ($goods['model_attr'] == 2) {
			$table_products = 'products_area';
			$type_files = ' and area_id = \'' . $this->area_id . '\'';
		}
		else {
			$table_products = 'products';
			$type_files = '';
		}

		$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table($table_products) . ' WHERE goods_id = \'' . $goods['goods_id'] . '\'' . $type_files . ' LIMIT 0, 1';
		$prod = $GLOBALS['db']->getRow($sql);

		if ($goods['goods_number'] < $number) {
			show_message(L('gb_error_goods_lacking'), '', '', 'error');
		}

		if ($goods['astrict_num'] < $number) {
			show_message('已超过拼团限购数量', '', '', 'error');
		}

		$attr_list = array();
		$sql = 'SELECT a.attr_name, g.attr_value ' . 'FROM ' . $GLOBALS['ecs']->table('goods_attr') . ' AS g, ' . $GLOBALS['ecs']->table('attribute') . ' AS a ' . 'WHERE g.attr_id = a.attr_id ' . 'AND g.goods_attr_id ' . db_create_in($specs);
		$res = $GLOBALS['db']->query($sql);

		foreach ($res as $row) {
			$attr_list[] = $row['attr_name'] . ': ' . $row['attr_value'];
		}

		$goods_attr = join(chr(13) . chr(10), $attr_list);
		clear_cart(CART_TEAM_GOODS);
		$area_info = get_area_info($this->province_id);
		$this->area_id = $area_info['region_id'];
		$where = 'regionId = \'' . $this->province_id . '\'';
		$date = array('parent_id');
		$this->region_id = get_table_date('region_warehouse', $where, $date, 2);

		if (!empty($_SESSION['user_id'])) {
			$sess = '';
		}
		else {
			$sess = real_cart_mac_ip();
		}

		$shop_price = tean_get_final_price($goods_id, $number, true, $_specs, $warehouse_id, $area_id);
		$goods_price = $shop_price;
		$cart = array('user_id' => $_SESSION['user_id'], 'session_id' => $sess, 'goods_id' => $goods['goods_id'], 'product_id' => $product_info['product_id'], 'goods_sn' => addslashes($goods['goods_sn']), 'goods_name' => addslashes($goods['goods_name']), 'market_price' => $goods['market_price'], 'goods_price' => $goods_price, 'goods_number' => $number, 'goods_attr' => addslashes($goods_attr), 'goods_attr_id' => $specs, 'ru_id' => $goods['user_id'], 'warehouse_id' => $this->region_id, 'area_id' => $this->area_id, 'add_time' => gmtime(), 'is_real' => $goods['is_real'], 'extension_code' => addslashes($goods['extension_code']), 'parent_id' => 0, 'rec_type' => CART_TEAM_GOODS, 'is_gift' => 0, 'is_shipping' => $goods['is_shipping']);
		$this->db->autoExecute($GLOBALS['ecs']->table('cart'), $cart, 'INSERT');
		$_SESSION['flow_type'] = CART_TEAM_GOODS;
		$_SESSION['extension_code'] = 'team_buy';
		$_SESSION['extension_id'] = '';
		$_SESSION['cart_value'] = '';

		if (0 < $team_id) {
			$_SESSION['team_id'] = $team_id;
		}

		$_SESSION['browse_trace'] = 'team_buy';
		$this->redirect('team/flow/index', array('direct_shopping' => 4));
		exit();
	}

	public function actionTeamwait()
	{
		$this->team_id = I('team_id', 0, 'intval');

		if ($this->team_id <= 0) {
			ecs_header("Location: ./\n");
			exit();
		}

		$sql = 'select order_id, pay_status from ' . $this->ecs->table('order_info') . ' where team_id = ' . $this->team_id . ' ';
		$res = $this->db->getRow($sql);

		if ($res['pay_status'] != PS_PAYED) {
			show_message('亲，您的拼团订单没有支付', '请前去支付', url('user/order/detail', array('order_id' => $res['order_id'])), 'success');
			exit();
		}

		$sql = 'select tl.team_id, tl.start_time,o.team_parent_id,g.goods_id,g.goods_img,g.goods_name,g.goods_brief,tg.validity_time ,tg.team_num ,tg.team_price from ' . $this->ecs->table('team_log') . ' as tl LEFT JOIN ' . $this->ecs->table('order_info') . ' as o ON tl.team_id = o.team_id LEFT JOIN  ' . $this->ecs->table('goods') . ' as g ON tl.goods_id = g.goods_id LEFT JOIN ' . $this->ecs->table('team_goods') . ' AS tg ON tl.goods_id = tg.goods_id  ' . ' where tl.team_id = ' . $this->team_id . '  and o.extension_code =\'team_buy\' and o.team_parent_id > 0 and pay_status = \'' . PS_PAYED . '\'';
		$result = $this->db->query($sql);

		foreach ($result as $vo) {
			$goods['goods_id'] = $vo['goods_id'];
			$goods['goods_name'] = $vo['goods_name'];
			$goods['goods_img'] = get_image_path($vo['goods_img']);
			$goods['goods_brief'] = $vo['goods_brief'];
			$goods['team_id'] = $vo['team_id'];
			$goods['team_num'] = $vo['team_num'];
			$goods['team_price'] = price_format($vo['team_price']);
			$user_nick = get_user_default($vo['team_parent_id']);
			$goods['user_name'] = encrypt_username($user_nick['nick_name']);
			$goods['headerimg'] = $user_nick['user_picture'];
		}

		$sql = 'select tl.team_id, tl.start_time,tl.goods_id,tl.status,g.validity_time ,g.team_num,g.team_price from ' . $this->ecs->table('team_log') . ' as tl LEFT JOIN ' . $this->ecs->table('team_goods') . ' as g ON tl.goods_id = g.goods_id where tl.team_id =' . $this->team_id . '  ';
		$team = $this->db->getRow($sql);
		$team['team_price'] = price_format($team['team_price']);
		$team['end_time'] = $team['start_time'] + ($team['validity_time'] * 3600);
		$surplus = surplus_num($team['team_id']);
		$team['surplus'] = $team['team_num'] - $surplus;
		$team['bar'] = round(($surplus * 100) / $team['team_num'], 0);
		if (($team['status'] != 1) && (gmtime() < ($team['start_time'] + ($team['validity_time'] * 3600)))) {
			$team['status'] = 0;
			$this->assign('page_title', L('waiting_team'));
		}
		else {
			if (($team['status'] != 1) && (($team['start_time'] + ($team['validity_time'] * 3600)) < gmtime())) {
				$team['status'] = 2;
				$this->assign('page_title', L('team_failure'));
			}
			else if ($team['status'] = 1) {
				$team['status'] = 1;
				$this->assign('page_title', L('team_succes'));
			}
		}

		$sql = 'select o.team_id, o.user_id,o.team_parent_id,o.team_user_id from ' . $this->ecs->table('order_info') . ' as o LEFT JOIN ' . $this->ecs->table('users') . ' as u ON o.user_id = u.user_id where o.team_id =' . $this->team_id . ' and o.extension_code =\'team_buy\' and o.pay_status = \'' . PS_PAYED . '\' limit 0,5';
		$team_user = $this->db->query($sql);

		foreach ($team_user as $key => $vo) {
			$user_nick = get_user_default($vo['user_id']);
			$team_user[$key]['user_name'] = encrypt_username($user_nick['nick_name']);
			$team_user[$key]['headerimg'] = $user_nick['user_picture'];
		}

		$team_join = $this->db->table('order_info')->where(array('user_id' => $_SESSION['user_id'], 'team_id' => $this->team_id))->count();

		if (0 < $team_join) {
			$this->assign('team_join', 1);
		}

		$this->assign('team_user', $team_user);
		$this->assign('goods', $goods);
		$this->assign('team', $team);
		$this->assign('cfg', C(shop));
		$this->assign('description', $goods['goods_brief']);

		if (strtolower(substr($goods['goods_img'], 0, 4)) == 'http') {
			$page_img = $goods['goods_img'];
		}
		else {
			$page_img = __HOST__ . $goods['goods_img'];
		}

		$this->assign('page_img', $page_img);
		$this->display();
	}

	public function actionTeamuser()
	{
		$this->team_id = I('team_id', 0, 'intval');
		$sql = 'select o.team_id, o.user_id,o.team_parent_id,o.team_user_id,o.add_time ,u.user_name from ' . $this->ecs->table('order_info') . ' as o LEFT JOIN ' . $this->ecs->table('users') . ' as u ON o.user_id = u.user_id where o.team_id =' . $this->team_id . ' and o.extension_code =\'team_buy\' and o.pay_status = \'' . PS_PAYED . '\' order by o.add_time asc ';
		$team_user = $this->db->query($sql);

		foreach ($team_user as $key => $vo) {
			$team_user[$key]['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $vo['add_time']);
			$user_nick = get_user_default($vo['user_id']);
			$team_user[$key]['user_name'] = encrypt_username($user_nick['nick_name']);
			$team_user[$key]['headerimg'] = $user_nick['user_picture'];
		}

		$this->assign('team_user', $team_user);
		$this->assign('page_title', L('team_user_list'));
		$this->display();
	}

	public function actionTeamlist()
	{
		$this->page = I('page', 1, 'intval');
		$type = (isset($_REQUEST['type']) ? $_REQUEST['type'] : 'is_best');

		if (IS_AJAX) {
			$goods_list = team_goods($this->size, $this->page, $type);
			exit(json_encode(array('list' => $goods_list['list'], 'totalPage' => $goods_list['totalpage'])));
		}
	}

	private function check_login()
	{
		if (!(0 < $_SESSION['user_id'])) {
			$url = urlencode(__HOST__ . $_SERVER['REQUEST_URI']);

			if (IS_POST) {
				$url = urlencode($_SERVER['HTTP_REFERER']);
			}

			ecs_header('Location: ' . url('user/login/index', array('back_act' => $url)));
			exit();
		}
	}
}

?>
