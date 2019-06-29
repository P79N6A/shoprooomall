<?php
//SHOPROOO
namespace App\Repositories\Coupons;

class CouponsRepository
{
	public function getCouponsLists($status = 0, $uid)
	{
		$time = gmtime();

		if ($status == 0) {
			$where = 'where cu.is_use = 0  and cu.user_id = \'' . $uid . '\' and c.cou_end_time>\'' . $time . '\' ';
		}
		else if ($status == 1) {
			$where = 'where cu.is_use = 1  and cu.user_id = \'' . $uid . '\' ';
		}
		else if ($status == 2) {
			$where = 'where  \'' . $time . '\' > c.cou_end_time and  cu.is_use = 0  and cu.user_id = \'' . $uid . '\'';
		}

		$prefix = \Illuminate\Support\Facades\Config::get('database.connections.mysql.prefix');
		$sql = 'SELECT COUNT(*) FROM ' . $prefix . 'coupons_user  AS cu LEFT JOIN  ' . $prefix . 'coupons AS c ON c.cou_id = cu.cou_id  ' . $where . 'AND c.review_status = 3';
		$total = \Illuminate\Support\Facades\DB::select($sql);
		$total = get_object_vars($total[0]);
		$left_join = ' LEFT JOIN ' . $prefix . 'order_info AS o ON cu.order_id = o.order_id ';
		$sql = 'SELECT c.*, cu.is_use, cu.is_use_time, cu.user_id, o.order_sn, o.add_time FROM ' . $prefix . 'coupons_user AS cu LEFT JOIN ' . $prefix . 'coupons AS c ON c.cou_id = cu.cou_id ' . $left_join . $where . ' AND c.review_status = 3 ';
		$tab = \Illuminate\Support\Facades\DB::select($sql);

		foreach ($tab as $k => $v) {
			$tab[$k] = get_object_vars($v);
			$tab[$k]['cou_start_time'] = date('Y-m-d', $tab[$k]['cou_start_time']);
			$tab[$k]['cou_end_time'] = date('Y-m-d', $tab[$k]['cou_end_time']);
			$tab[$k]['cou_add_time'] = date('Y-m-d H:i:s', $tab[$k]['cou_add_time']);
		}

		$result['tab'] = $tab;
		$result['status'] = $status;
		$result['total'] = $total;
		return $result;
	}

	public function goodsCoupont($id = 0, $ruId, $uid)
	{
		$time = gmtime();
		$prefix = \Illuminate\Support\Facades\Config::get('database.connections.mysql.prefix');
		$sql = 'SELECT * FROM ' . $prefix . ('coupons WHERE (`cou_type` = 3 OR `cou_type` = 4 ) AND `cou_end_time` >' . $time . ' AND (( instr(`cou_goods`, ' . $id . ') ) or (`cou_goods`=0)) AND  review_status = 3 and ru_id=') . $ruId;
		$total = \Illuminate\Support\Facades\DB::select($sql);

		foreach ($total as $key => $val) {
			$total[$key] = get_object_vars($val);
			$total[$key]['cou_start_time'] = date('Y-m-d', $total[$key]['cou_start_time']);
			$total[$key]['cou_end_time'] = date('Y-m-d', $total[$key]['cou_end_time']);
			$total[$key]['cou_add_time'] = date('Y-m-d H:i:s', $total[$key]['cou_add_time']);
			$pick = \App\Models\CouponsUser::select()->where('user_id', $uid)->where('cou_id', $total[$key]['cou_id'])->count();
			$cou_num = \App\Models\CouponsUser::select()->where('cou_id', $total[$key]['cou_id'])->count();
			if ($pick < $total[$key]['cou_user_num'] && $cou_num < $total[$key]['cou_total']) {
				$total[$key]['pick'] = 1;
			}
			else {
				$total[$key]['pick'] = 2;
			}
		}

		return $total;
	}

	public function getCoutype($cou_id)
	{
		$res = \App\Models\Coupons::select('cou_type', 'cou_ok_user')->where('cou_id', $cou_id)->get()->toArray();
		return $res[0];
	}

	public function getCoups($cou_id, $uid, $ticket)
	{
		$time = gmtime();
		$prefix = \Illuminate\Support\Facades\Config::get('database.connections.mysql.prefix');
		$sql = 'SELECT c.*,c.cou_total-COUNT(cu.cou_id) cou_surplus FROM ' . $prefix . 'coupons c LEFT JOIN ' . $prefix . 'coupons_user cu ON c.cou_id=cu.cou_id GROUP BY c.cou_id  HAVING cou_surplus>0 AND c.review_status = 3 AND c.cou_id=\'' . $cou_id . ('\' AND c.cou_end_time>' . $time . ' limit 1');
		$total = \Illuminate\Support\Facades\DB::select($sql);
		$total = get_object_vars($total[0]);

		if (!empty($total)) {
			$num = \App\Models\CouponsUser::where('user_id', $uid)->where('cou_id', $cou_id)->count('cou_id');
			$res = \App\Models\Coupons::select('cou_user_num')->where('cou_id', $cou_id)->first();
			$res = $res['cou_user_num'];

			if ($num < $res) {
				$add = \App\Models\CouponsUser::insertGetId(array('user_id' => $uid, 'cou_id' => $cou_id, 'uc_sn' => $time));

				if ($add) {
					$result['msg'] = '领取成功！感谢您的参与，祝您购物愉快';
					$result['error'] = 2;
					return $result;
				}
			}
			else {
				$result['msg'] = '领取失败,您已经领取过该券了!每人限领取' . $res . '张';
				$result['error'] = 3;
				return $result;
			}
		}
		else {
			$result['msg'] = '优惠券已领完';
			$result['error'] = 4;
			return $result;
		}
	}

	public function UserCoupons($user_id = '', $is_use = false, $total = '', $cart_goods = false, $user = true, $cart_ru_id = -1, $act_type = 'user')
	{
		$time = gmtime();
		$prefix = \Illuminate\Support\Facades\Config::get('database.connections.mysql.prefix');
		$cart_where = '';

		if (-1 < $cart_ru_id) {
			$cart_where .= ' AND cu.is_use = 0';
		}

		if ($is_use && isset($total) && $cart_goods) {
			$res = array();

			foreach ($cart_goods['shop_list'] as $k => $v) {
				$res[$v['ru_id']] = array(
	'order_total' => 0,
	'seller_id'   => null,
	'goods_id'    => '',
	'cat_id'      => '',
	'goods'       => array()
	);
			}

			foreach ($cart_goods['shop_list'] as $k => $v) {
				$v['subtotal'] = $v['goods_price'] * $v['goods_number'];
				$res[$v['ru_id']]['order_total'] += $v['goods_price'] * $v['goods_number'];
				$res[$v['ru_id']]['seller_id'] = $v['ru_id'];
				$res[$v['ru_id']]['goods_id'] = $v['goods_id'];
				$res[$v['ru_id']]['cat_id'] = $v['cat_id'];
				$res[$v['ru_id']]['goods'][$v['goods_id']] = $v;
			}

			$arr = array();
			$couarr = array();

			foreach ($res as $key => $row) {
				$row['goods_id'] = get_del_str_comma($row['goods_id']);
				$row['cat_id'] = get_del_str_comma($row['cat_id']);
				$ru_where = ' AND c.ru_id = \'' . $row['seller_id'] . '\'';
				$sql = 'SELECT c.*, cu.uc_id, cr.region_list, cu.cou_money AS uc_money FROM ' . $prefix . 'coupons_user AS cu ' . ' LEFT JOIN ' . $prefix . 'coupons AS c ON cu.cou_id = c.cou_id ' . ' LEFT JOIN ' . $prefix . 'coupons_region AS cr ON cr.cou_id = c.cou_id ' . (' WHERE c.review_status = 3 AND c.cou_end_time > ' . $time . ' AND ' . $time . ' > c.cou_start_time') . ' AND ' . $row['order_total'] . ' >= c.cou_man' . (' AND cu.order_id = 0 AND cu.user_id = \'' . $user_id . '\'') . $cart_where . $ru_where . ' GROUP BY cu.uc_id';
				$couarr[$key] = \Illuminate\Support\Facades\DB::select($sql);

				if ($couarr[$key]) {
					foreach ($couarr[$key] as $ckey => $crow) {
						$crow = get_object_vars($crow);
						if ($crow['cou_type'] == 5 && $crow['region_list']) {
							$sql = 'SELECT region_name FROM' . $prefix . 'region WHERE region_id' . db_create_in($crow['region_list']);
							$region_list = $GLOBALS['db']->getCol($sql);

							if ($region_list) {
								$couarr[$key][$ckey]['region_list'] = implode(',', $region_list);
							}
						}
					}
				}

				$goods_ids = array();
				if (isset($row['goods_id']) && $row['goods_id'] && !is_array($row['goods_id'])) {
					$goods_ids = explode(',', $row['goods_id']);
					$goods_ids = array_unique($goods_ids);
				}

				$goods_cats = array();
				if (isset($row['cat_id']) && $row['cat_id'] && !is_array($row['cat_id'])) {
					$goods_cats = explode(',', $row['cat_id']);
					$goods_cats = array_unique($goods_cats);
				}

				if (($goods_ids || $goods_cats) && $couarr[$key]) {
					foreach ($couarr[$key] as $rk => $rrow) {
						$rrow = get_object_vars($rrow);

						if ($rrow['cou_goods']) {
							$cou_goods = explode(',', $rrow['cou_goods']);
							$cou_goods_prices = 0;

							foreach ($goods_ids as $m => $n) {
								if (in_array($n, $cou_goods)) {
									$cou_goods_prices += $row['goods'][$n]['subtotal'];

									if ($rrow['cou_man'] < $cou_goods_prices) {
										$arr[] = $rrow;
										break;
									}
								}
							}
						}
						else if ($rrow['spec_cat']) {
							$spec_cat = get_cou_children($rrow['spec_cat']);
							$spec_cat = explode(',', $spec_cat);
							$cou_goods_prices = 0;

							foreach ($goods_cats as $m => $n) {
								if (in_array($n, $spec_cat)) {
									foreach ($row['goods'] as $key => $val) {
										if ($n == $val['cat_id']) {
											$cou_goods_prices += $val['subtotal'];
										}
									}

									if ($rrow['cou_man'] < $cou_goods_prices) {
										$arr[] = $rrow;
										continue;
									}
								}
							}
						}
						else {
							$arr[] = $rrow;
						}
					}
				}
			}

			return $arr;
		}
		else {
			if (!empty($user_id) && $user) {
				$where = ' WHERE cu.user_id IN(' . $user_id . ') AND c.review_status = 3';
			}
			else if (!empty($user_id)) {
				$where = ' WHERE cu.user_id IN(' . $user_id . ') AND c.review_status = 3';
			}

			$select = '';
			$leftjoin = '';

			if ($act_type == 'cart') {
				$where .= ' AND c.cou_end_time > ' . $time . ' AND ' . $time;
			}
			else {
				$select = ', o.order_sn, o.add_time, o.coupons AS order_coupons';
				$leftjoin = ' LEFT JOIN ' . $prefix . 'order_info AS o ON cu.order_id = o.order_id ';
			}

			$sql = ' SELECT c.*, cu.*, c.cou_money AS cou_money, cu.cou_money AS uc_money, cr.region_list ' . $select . ' FROM ' . $prefix . 'coupons_user AS cu ' . ' LEFT JOIN ' . $prefix . 'coupons AS c ON c.cou_id = cu.cou_id ' . ' LEFT JOIN ' . $prefix . 'coupons_region AS cr ON cr.cou_id = c.cou_id ' . $leftjoin . $where . $cart_where . ' GROUP BY cu.uc_id';
			$res = \Illuminate\Support\Facades\DB::select($sql);

			if ($res) {
				foreach ($res as $key => $row) {
					$row = get_object_vars($row);
					if ($row['cou_type'] == 5 && $row['region_list']) {
						$sql = 'SELECT region_name FROM' . $prefix . 'region WHERE region_id' . db_create_in($row['region_list']);
						$region_list = $GLOBALS['db']->getCol($sql);

						if ($region_list) {
							$res[$key]['region_list'] = implode(',', $region_list);
						}
					}
				}
			}

			return $res;
		}
	}

	public function getcoupons($userId, $uc_id = 0, $select = array())
	{
		$time = gmtime();
		if ($select && is_array($select)) {
			$select = implode(',', $select);
		}
		else {
			$select = 'c.*, cu.*';
		}

		$prefix = \Illuminate\Support\Facades\Config::get('database.connections.mysql.prefix');
		$sql = ' SELECT ' . $select . ' FROM ' . $prefix . 'coupons_user cu ' . ' LEFT JOIN ' . $prefix . 'coupons c ON c.cou_id = cu.cou_id ' . (' WHERE cu.uc_id = \'' . $uc_id . '\' AND cu.user_id = \'') . $userId . ('\' AND c.cou_end_time > ' . $time . '  ');
		$total = \Illuminate\Support\Facades\DB::select($sql);
		$total = !empty($total) ? get_object_vars($total[0]) : '';
		return $total;
	}

	public function getupcoutype($uc_id, $time)
	{
		if (!empty($uc_id)) {
			$array = array('is_use' => 1, 'is_use_time' => $time);
			$total = \App\Models\CouponsUser::where('uc_id', $uc_id)->update($array);
		}

		return $total;
	}

	public function getcouponsregion($cou_id = 0)
	{
		$region = \App\Models\CouponsRegion::select('region_list')->where('cou_id', $cou_id)->get()->toArray();
		return $region;
	}

	public function get_del_str_comma($str = '')
	{
		if ($str && is_array($str)) {
			return $str;
		}
		else {
			if ($str) {
				$str = str_replace(',,', ',', $str);
				$str1 = substr($str, 0, 1);
				$str2 = substr($str, str_len($str) - 1);
				if ($str1 === ',' && $str2 !== ',') {
					$str = substr($str, 1);
				}
				else {
					if ($str1 !== ',' && $str2 === ',') {
						$str = substr($str, 0, -1);
					}
					else {
						if ($str1 === ',' && $str2 === ',') {
							$str = substr($str, 1);
							$str = substr($str, 0, -1);
						}
					}
				}
			}

			return $str;
		}
	}
}


?>
