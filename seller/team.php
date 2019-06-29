<?php
//zend SHOPROOO在线更新  禁止倒卖 一经发现停止任何服务
function team_goods_list($ru_id)
{
	$result = get_filter();

	if ($result === false) {
		$filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
		$filter['is_audit'] = empty($_REQUEST['is_audit']) ? '' : trim($_REQUEST['is_audit']);
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1) {
			$filter['keyword'] = json_str_iconv($filter['keyword']);
		}

		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'tg.id' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
		$where = ' g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND g.review_status>2 and tg.is_team = 1 ';
		$where .= !empty($filter['keyword']) ? ' AND (g.goods_name LIKE \'%' . mysql_like_quote($filter['keyword']) . '%\') ' : '';

		if (!empty($filter['is_audit'])) {
			if ($filter['is_audit'] == 3) {
				$where .= ' AND tg.is_audit = 0 ';
			}
			else {
				$where .= ' AND tg.is_audit = \'' . $filter['is_audit'] . '\' ';
			}
		}
		else {
			$where .= '';
		}

		if (0 < $ru_id) {
			$where .= ' and g.user_id = \'' . $ru_id . '\'';
		}

		$filter['store_search'] = !isset($_REQUEST['store_search']) ? -1 : intval($_REQUEST['store_search']);
		$filter['merchant_id'] = isset($_REQUEST['merchant_id']) ? intval($_REQUEST['merchant_id']) : 0;
		$filter['store_keyword'] = isset($_REQUEST['store_keyword']) ? trim($_REQUEST['store_keyword']) : '';
		$store_where = '';
		$store_search_where = '';

		if (-1 < $filter['store_search']) {
			if ($ru_id == 0) {
				if (0 < $filter['store_search']) {
					if ($_REQUEST['store_type']) {
						$store_search_where = 'AND msi.shopNameSuffix = \'' . $_REQUEST['store_type'] . '\'';
					}

					if ($filter['store_search'] == 1) {
						$where .= ' AND g.user_id = \'' . $filter['merchant_id'] . '\' ';
					}
					else if ($filter['store_search'] == 2) {
						$store_where .= ' AND msi.rz_shopName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\'';
					}
					else if ($filter['store_search'] == 3) {
						$store_where .= ' AND msi.shoprz_brandName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\' ' . $store_search_where;
					}

					if (1 < $filter['store_search']) {
						$where .= ' AND (SELECT msi.user_id FROM ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' as msi ' . (' WHERE msi.user_id = ga.user_id ' . $store_where . ') > 0 ');
					}
				}
				else {
					$where .= ' AND g.user_id = 0';
				}
			}
		}

		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('team_goods') . ' AS tg left join ' . $GLOBALS['ecs']->table('goods') . ' as g on tg.goods_id = g.goods_id' . (' WHERE ' . $where);
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter = page_and_size($filter);
		$sql = 'SELECT tg.*,g.user_id,g.goods_sn,g.goods_name,g.shop_price,g.market_price,g.goods_number,g.goods_img,g.goods_thumb ' . 'FROM ' . $GLOBALS['ecs']->table('team_goods') . ' AS tg ' . ' left join ' . $GLOBALS['ecs']->table('goods') . ' as g on tg.goods_id=g.goods_id' . (' WHERE  ' . $where . ' ') . (' ORDER BY ' . $filter['sort_by'] . ' ' . $filter['sort_order'] . ' ') . ' LIMIT ' . $filter['start'] . (', ' . $filter['page_size']);
		$filter['keyword'] = stripslashes($filter['keyword']);
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$res = $GLOBALS['db']->query($sql);
	$list = array();

	while ($row = $GLOBALS['db']->fetchRow($res)) {
		$arr = array_merge($row);
		$arr['shop_price'] = price_format($arr['shop_price']);
		$arr['team_price'] = price_format($arr['team_price']);
		$arr['user_name'] = get_shop_name($arr['user_id'], 1);

		if ($arr['is_audit'] == 1) {
			$is_audit = $GLOBALS['_LANG']['audited_not_adopt'];
		}
		else if ($arr['is_audit'] == 2) {
			$is_audit = $GLOBALS['_LANG']['audited_yes_adopt'];
		}
		else {
			$is_audit = $GLOBALS['_LANG']['not_audited'];
		}

		$arr['is_audit'] = $is_audit;
		$list[] = $arr;
	}

	$arr = array('item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

function team_goods_info($id)
{
	$sql = 'SELECT tg.*,g.user_id,g.goods_sn,g.goods_name,g.shop_price,g.market_price,g.goods_number,g.goods_img,g.goods_thumb ' . 'FROM ' . $GLOBALS['ecs']->table('team_goods') . ' AS tg ' . ' left join ' . $GLOBALS['ecs']->table('goods') . ' as g on tg.goods_id=g.goods_id' . (' WHERE tg.is_team = \'1\' and tg.id = \'' . $id . '\' ') . ' LIMIT 1 ';
	$goods = $GLOBALS['db']->getRow($sql);
	return $goods;
}

function team_info_list($ru_id)
{
	$result = get_filter();

	if ($result === false) {
		$filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1) {
			$filter['keyword'] = json_str_iconv($filter['keyword']);
		}

		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'tl.start_time' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
		$where = !empty($filter['keyword']) ? ' AND (g.goods_name LIKE \'%' . mysql_like_quote($filter['keyword']) . '%\')' : '';

		if (0 < $ru_id) {
			$where .= ' and g.user_id = \'' . $ru_id . '\'';
		}

		$filter['store_search'] = !isset($_REQUEST['store_search']) ? -1 : intval($_REQUEST['store_search']);
		$filter['merchant_id'] = isset($_REQUEST['merchant_id']) ? intval($_REQUEST['merchant_id']) : 0;
		$filter['store_keyword'] = isset($_REQUEST['store_keyword']) ? trim($_REQUEST['store_keyword']) : '';
		$store_where = '';
		$store_search_where = '';

		if (-1 < $filter['store_search']) {
			if ($ru_id == 0) {
				if (0 < $filter['store_search']) {
					if ($_REQUEST['store_type']) {
						$store_search_where = 'AND msi.shopNameSuffix = \'' . $_REQUEST['store_type'] . '\'';
					}

					if ($filter['store_search'] == 1) {
						$where .= ' AND g.user_id = \'' . $filter['merchant_id'] . '\' ';
					}
					else if ($filter['store_search'] == 2) {
						$store_where .= ' AND msi.rz_shopName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\'';
					}
					else if ($filter['store_search'] == 3) {
						$store_where .= ' AND msi.shoprz_brandName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\' ' . $store_search_where;
					}

					if (1 < $filter['store_search']) {
						$where .= ' AND (SELECT msi.user_id FROM ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' as msi ' . (' WHERE msi.user_id = ga.user_id ' . $store_where . ') > 0 ');
					}
				}
				else {
					$where .= ' AND g.user_id = 0';
				}
			}
		}

		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('team_log') . ' as tl LEFT JOIN ' . $GLOBALS['ecs']->table('team_goods') . ' as tg ON tl.goods_id = tg.goods_id LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . (' as g ON tl.goods_id = g.goods_id where tl.is_show = 1 ' . $where);
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter = page_and_size($filter);
		$sql = 'SELECT tl.team_id, tl.start_time,tl.goods_id,tl.status,tg.team_num,tg.validity_time,g.user_id,g.goods_name,g.goods_thumb,g.shop_price  FROM ' . $GLOBALS['ecs']->table('team_log') . ' as tl LEFT JOIN ' . $GLOBALS['ecs']->table('team_goods') . ' as tg ON tl.goods_id = tg.goods_id LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' as g ON tl.goods_id = g.goods_id ' . (' WHERE tl.is_show = \'1\' ' . $where . ' ') . (' ORDER BY ' . $filter['sort_by'] . ' ' . $filter['sort_order'] . ' ') . ' LIMIT ' . $filter['start'] . (', ' . $filter['page_size']);
		$filter['keyword'] = stripslashes($filter['keyword']);
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$res = $GLOBALS['db']->query($sql);
	$list = array();
	$time = gmtime();

	while ($row = $GLOBALS['db']->fetchRow($res)) {
		$arr = array_merge($row);
		$arr['surplus'] = $arr['team_num'] - surplus_num($arr['team_id']);
		if ($arr['status'] != 1 && $time < $arr['start_time'] + $arr['validity_time'] * 3600) {
			$arr['status'] = $GLOBALS['_LANG']['ongoing'];
		}
		else {
			if ($arr['status'] != 1 && $arr['start_time'] + $arr['validity_time'] * 3600 < $time) {
				$arr['status'] = $GLOBALS['_LANG']['fail_group'];
			}
			else if ($arr['status'] == 1) {
				$arr['status'] = $GLOBALS['_LANG']['success_group'];
			}
		}

		$endtime = $arr['start_time'] + $arr['validity_time'] * 3600;
		$cle = $endtime - $time;
		$d = floor($cle / 3600 / 24);
		$h = floor($cle % (3600 * 24) / 3600);
		$m = floor($cle % (3600 * 24) % 3600 / 60);
		$arr['time'] = $d . $GLOBALS['_LANG']['tian'] . $h . $GLOBALS['_LANG']['hour_alt'] . $m . $GLOBALS['_LANG']['minute_alt'];
		$arr['cle'] = $cle;
		$arr['start_time'] = local_date($GLOBALS['_CFG']['time_format'], $arr['start_time']);
		$list[] = $arr;
	}

	$arr = array('item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

function team_order_list($ru_id, $team_id = 0)
{
	$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'o.add_time' : trim($_REQUEST['sort_by']);
	$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'asc' : trim($_REQUEST['sort_order']);

	if (0 < $team_id) {
		$where = ' and o.team_id = \'' . $team_id . '\'';
	}

	if (0 < $ru_id) {
		$where .= ' and g.user_id = \'' . $ru_id . '\'';
	}

	$filter['store_search'] = !isset($_REQUEST['store_search']) ? -1 : intval($_REQUEST['store_search']);
	$filter['merchant_id'] = isset($_REQUEST['merchant_id']) ? intval($_REQUEST['merchant_id']) : 0;
	$filter['store_keyword'] = isset($_REQUEST['store_keyword']) ? trim($_REQUEST['store_keyword']) : '';
	$store_where = '';
	$store_search_where = '';

	if (-1 < $filter['store_search']) {
		if ($ru_id == 0) {
			if (0 < $filter['store_search']) {
				if ($_REQUEST['store_type']) {
					$store_search_where = 'AND msi.shopNameSuffix = \'' . $_REQUEST['store_type'] . '\'';
				}

				if ($filter['store_search'] == 1) {
					$where .= ' AND g.user_id = \'' . $filter['merchant_id'] . '\' ';
				}
				else if ($filter['store_search'] == 2) {
					$store_where .= ' AND msi.rz_shopName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\'';
				}
				else if ($filter['store_search'] == 3) {
					$store_where .= ' AND msi.shoprz_brandName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\' ' . $store_search_where;
				}

				if (1 < $filter['store_search']) {
					$where .= ' AND (SELECT msi.user_id FROM ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' as msi ' . (' WHERE msi.user_id = ga.user_id ' . $store_where . ') > 0 ');
				}
			}
			else {
				$where .= ' AND g.user_id = 0';
			}
		}
	}

	$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('order_info') . ' as o LEFT JOIN ' . $GLOBALS['ecs']->table('order_goods') . ' as og ON o.order_id = og.order_id LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . (' as g ON og.goods_id = g.goods_id where o.extension_code =\'team_buy\' ' . $where . ' order by o.order_id desc ');
	$filter['record_count'] = $GLOBALS['db']->getOne($sql);
	$filter = page_and_size($filter);
	$sql = 'SELECT o.*,og.goods_name FROM ' . $GLOBALS['ecs']->table('order_info') . ' as o LEFT JOIN ' . $GLOBALS['ecs']->table('order_goods') . ' as og ON o.order_id = og.order_id LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' as g ON og.goods_id = g.goods_id ' . (' WHERE o.extension_code =\'team_buy\' ' . $where . ' ') . (' ORDER BY ' . $filter['sort_by'] . ' ' . $filter['sort_order'] . ' ') . ' LIMIT ' . $filter['start'] . (', ' . $filter['page_size']);
	set_filter($filter, $sql);
	$res = $GLOBALS['db']->query($sql);
	$list = array();

	while ($row = $GLOBALS['db']->fetchRow($res)) {
		$arr = array_merge($row);
		$arr['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $arr['add_time']);
		$arr['status'] = $GLOBALS['_LANG']['os'][$arr['order_status']] . ',' . $GLOBALS['_LANG']['ps'][$arr['pay_status']] . ',' . $GLOBALS['_LANG']['ss'][$arr['shipping_status']];
		$list[] = $arr;
	}

	$arr = array('item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

function surplus_num($team_id = 0)
{
	$sql = 'SELECT count(order_id) as num  FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE team_id = \'' . $team_id . '\' AND extension_code = \'team_buy\'  and pay_status = \'' . PS_PAYED . '\' ';
	$res = $GLOBALS['db']->getRow($sql);
	return $res['num'];
}

function team_get_tree($tree_id = 0)
{
	$three_arr = array();
	$where = '';
	$sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('team_category') . (' WHERE parent_id = \'' . $tree_id . '\' AND status = 1') . $where;
	if ($GLOBALS['db']->getOne($sql) || $tree_id == 0) {
		$child_sql = 'SELECT id, name, parent_id,status ' . ' FROM ' . $GLOBALS['ecs']->table('team_category') . (' WHERE parent_id = \'' . $tree_id . '\' AND status = 1 ') . $where . ' ORDER BY sort_order ASC, id ASC';
		$res = $GLOBALS['db']->getAll($child_sql);

		foreach ($res as $k => $row) {
			if ($row['status']) {
				$three_arr[$k]['tc_id'] = $row['id'];
				$three_arr[$k]['name'] = $row['name'];
			}

			if (isset($row['id'])) {
				$child_tree = team_get_tree($row['id']);

				if ($child_tree) {
					$three_arr[$k]['id'] = $child_tree;
				}
			}
		}
	}

	return $three_arr;
}

function team_cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0)
{
	$sql = 'SELECT c.*, COUNT(s.id) AS has_children' . ' FROM ' . $GLOBALS['ecs']->table('team_category') . ' AS c LEFT JOIN ' . $GLOBALS['ecs']->table('team_category') . ' AS s ON s.parent_id=c.id' . ' where c.status = 1' . ' GROUP BY c.id ' . ' ORDER BY parent_id, sort_order DESC';
	$res = $GLOBALS['db']->getAll($sql);

	if (empty($res) == true) {
		return $re_type ? '' : array();
	}

	$options = team_cat_options($cat_id, $res);

	if (0 < $level) {
		if ($cat_id == 0) {
			$end_level = $level;
		}
		else {
			$first_item = reset($options);
			$end_level = $first_item['level'] + $level;
		}

		foreach ($options as $key => $val) {
			if ($end_level <= $val['level']) {
				unset($options[$key]);
			}
		}
	}

	$pre_key = 0;

	foreach ($options as $key => $value) {
		$options[$key]['has_children'] = 1;

		if (0 < $pre_key) {
			if ($options[$pre_key]['id'] == $options[$key]['parent_id']) {
				$options[$pre_key]['has_children'] = 1;
			}
		}

		$pre_key = $key;
	}

	if ($re_type == true) {
		$select = '';

		foreach ($options as $var) {
			$select .= '<option value="' . $var['id'] . '" ';
			$select .= $selected == $var['id'] ? 'selected=\'ture\'' : '';
			$select .= '>';

			if (0 < $var['level']) {
				$select .= str_repeat('&nbsp;', $var['level'] * 4);
			}

			$select .= htmlspecialchars(addslashes($var['name'])) . '</option>';
		}

		return $select;
	}
	else {
		foreach ($options as $key => $value) {
			$options[$key]['url'] = build_uri('article_cat', array('acid' => $value['cat_id']), $value['cat_name']);
		}

		return $options;
	}
}

function team_cat_options($spec_cat_id, $arr)
{
	static $cat_options = array();

	if (isset($cat_options[$spec_cat_id])) {
		return $cat_options[$spec_cat_id];
	}

	if (!isset($cat_options[0])) {
		$level = $last_cat_id = 0;
		$options = $cat_id_array = $level_array = array();

		while (!empty($arr)) {
			foreach ($arr as $key => $value) {
				$cat_id = $value['id'];
				if ($level == 0 && $last_cat_id == 0) {
					if (0 < $value['parent_id']) {
						break;
					}

					$options[$cat_id] = $value;
					$options[$cat_id]['level'] = $level;
					$options[$cat_id]['id'] = $cat_id;
					$options[$cat_id]['name'] = $value['name'];
					unset($arr[$key]);

					if ($value['has_children'] == 0) {
						continue;
					}

					$last_cat_id = $cat_id;
					$cat_id_array = array($cat_id);
					$level_array[$last_cat_id] = ++$level;
					continue;
				}

				if ($value['parent_id'] == $last_cat_id) {
					$options[$cat_id] = $value;
					$options[$cat_id]['level'] = $level;
					$options[$cat_id]['id'] = $cat_id;
					$options[$cat_id]['name'] = $value['name'];
					unset($arr[$key]);

					if (0 < $value['has_children']) {
						if (end($cat_id_array) != $last_cat_id) {
							$cat_id_array[] = $last_cat_id;
						}

						$last_cat_id = $cat_id;
						$cat_id_array[] = $cat_id;
						$level_array[$last_cat_id] = ++$level;
					}
				}
				else if ($last_cat_id < $value['parent_id']) {
					break;
				}
			}

			$count = count($cat_id_array);

			if (1 < $count) {
				$last_cat_id = array_pop($cat_id_array);
			}
			else if ($count == 1) {
				if ($last_cat_id != end($cat_id_array)) {
					$last_cat_id = end($cat_id_array);
				}
				else {
					$level = 0;
					$last_cat_id = 0;
					$cat_id_array = array();
					continue;
				}
			}

			if ($last_cat_id && isset($level_array[$last_cat_id])) {
				$level = $level_array[$last_cat_id];
			}
			else {
				$level = 0;
			}
		}

		$cat_options[0] = $options;
	}
	else {
		$options = $cat_options[0];
	}

	if (!$spec_cat_id) {
		return $options;
	}
	else {
		if (empty($options[$spec_cat_id])) {
			return array();
		}

		$spec_cat_id_level = $options[$spec_cat_id]['level'];

		foreach ($options as $key => $value) {
			if ($key != $spec_cat_id) {
				unset($options[$key]);
			}
			else {
				break;
			}
		}

		$spec_cat_id_array = array();

		foreach ($options as $key => $value) {
			if ($spec_cat_id_level == $value['level'] && $value['id'] != $spec_cat_id || $value['level'] < $spec_cat_id_level) {
				break;
			}
			else {
				$spec_cat_id_array[$key] = $value;
			}
		}

		$cat_options[$spec_cat_id] = $spec_cat_id_array;
		return $spec_cat_id_array;
	}
}

function list_link($is_add = true)
{
	$href = 'team.php?act=list';

	if (!$is_add) {
		$href .= '&' . list_link_postfix();
	}

	return array('href' => $href, 'text' => $GLOBALS['_LANG']['team_goods_list'], 'class' => 'icon-reply');
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require_once ROOT_PATH . 'includes/lib_goods.php';
require_once ROOT_PATH . 'includes/lib_order.php';
require_once ROOT_PATH . SELLER_PATH . '/includes/lib_comment.php';
$smarty->assign('menus', $_SESSION['menus']);
$smarty->assign('action_type', 'team');
$adminru = get_admin_ru_id();

if ($adminru['ru_id'] == 0) {
	$smarty->assign('priv_ru', 1);
}
else {
	$smarty->assign('priv_ru', 0);
}

if (empty($_REQUEST['act'])) {
	$_REQUEST['act'] = 'list';
}
else {
	$_REQUEST['act'] = trim($_REQUEST['act']);
}

$smarty->assign('controller', basename(PHP_SELF, '.php'));

if ($_REQUEST['act'] == 'list') {
	$smarty->assign('primary_cat', $_LANG['02_promotion']);
	$smarty->assign('full_page', 1);
	$smarty->assign('ur_here', $_LANG['team_goods_list']);
	$smarty->assign('action_link', array('href' => 'team.php?act=add', 'text' => $_LANG['add_team_goods'], 'class' => 'icon-plus'));
	$tab_menu = array();
	$tab_menu[] = array('curr' => 1, 'text' => $_LANG['team_goods_list'], 'href' => 'team.php?act=list');
	$tab_menu[] = array('curr' => 0, 'text' => $_LANG['team_info'], 'href' => 'team.php?act=team_info');
	$smarty->assign('tab_menu', $tab_menu);
	$list = team_goods_list($adminru['ru_id']);
	$page_count_arr = seller_page($list, $_REQUEST['page']);
	$smarty->assign('page_count_arr', $page_count_arr);
	$smarty->assign('team_goods_list', $list['item']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$sort_flag = sort_flag($list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);
	assign_query_info();
	$smarty->display('team_goods_list.dwt');
}
else if ($_REQUEST['act'] == 'query') {
	$list = team_goods_list($adminru['ru_id']);
	$page_count_arr = seller_page($list, $_REQUEST['page']);
	$smarty->assign('page_count_arr', $page_count_arr);
	$smarty->assign('team_goods_list', $list['item']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$sort_flag = sort_flag($list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);
	make_json_result($smarty->fetch('team_goods_list.dwt'), '', array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}
else {
	if ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit') {
		$smarty->assign('primary_cat', $_LANG['02_promotion']);
		$smarty->assign('menu_select', array('action' => '02_promotion', 'current' => '18_team'));
		set_default_filter(0, 0, $adminru['ru_id']);
		$smarty->assign('filter_brand_list', search_brand_list());
		$goods['tc_id'] = 0;

		if ($_REQUEST['act'] == 'edit') {
			$id = intval($_REQUEST['id']);

			if ($id <= 0) {
				exit('invalid param');
			}

			$goods = team_goods_info($id);
		}

		$smarty->assign('goods', $goods);
		$select_category_html = '';
		$select_category_html .= insert_select_category(0, 0, 0, 'category', 1);
		$smarty->assign('select_category_html', $select_category_html);

		if ($_REQUEST['act'] == 'edit') {
			$smarty->assign('ur_here', $_LANG['edit_team_goods']);
		}
		else {
			$smarty->assign('ur_here', $_LANG['add_team_goods']);
		}

		$smarty->assign('action_link', list_link($_REQUEST['act'] == 'add'));
		$smarty->assign('brand_list', get_brand_list());
		$smarty->assign('ru_id', $adminru['ru_id']);
		$team_list = team_get_tree(0);
		$smarty->assign('team_list', $team_list);
		$sql = 'SELECT value FROM ' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code = \'virtual_limit_nim\'  ';
		$res = $GLOBALS['db']->getRow($sql);
		$smarty->assign('virtual_limit_nim', $res['value']);
		assign_query_info();
		$smarty->display('team_goods_info.dwt');
	}
	else if ($_REQUEST['act'] == 'insert_update') {
		$id = $_REQUEST['id'];
		$goods = $_REQUEST['data'];
		$goods['goods_id'] = intval($_POST['goods_id']);
		$goods['is_audit'] = 0;
		$goods['is_team'] = 1;

		if ($goods['goods_id'] <= 0) {
			$links = array(
				array('href' => 'team.php?act=add&' . list_link_postfix(), 'text' => '返回')
				);
			sys_msg($_LANG['please_add_team_goods'], 0, $links);
		}

		$adminru = get_admin_ru_id();
		clear_cache_files();

		if (0 < $id) {
			$db->autoExecute($ecs->table('team_goods'), $goods, 'UPDATE', 'id = \'' . $id . '\'');
			$links = array(
				array('href' => 'team.php?act=list&' . list_link_postfix(), 'text' => $_LANG['return_team_list'])
				);
			sys_msg($_LANG['edit_success'], 0, $links);
		}
		else {
			$sql = 'SELECT count(goods_id) as num  FROM ' . $GLOBALS['ecs']->table('team_goods') . ' WHERE is_team = 1 and goods_id = \'' . $goods['goods_id'] . '\'  ';
			$res = $GLOBALS['db']->getRow($sql);

			if (1 <= $res['num']) {
				$links = array(
					array('href' => 'team.php?act=add', 'text' => $_LANG['continue_add_team_goods'])
					);
				sys_msg($_LANG['before_team_end_cant_add'], 0, $links);
			}

			$db->autoExecute($ecs->table('team_goods'), $goods, 'INSERT');
			$links = array(
				array('href' => 'team.php?act=add', 'text' => $_LANG['continue_add_team_goods']),
				array('href' => 'team.php?act=list', 'text' => $_LANG['return_team_goods_list'])
				);
			sys_msg($_LANG['team_goods_add_success'], 0, $links);
		}
	}
	else if ($_REQUEST['act'] == 'batch_drop') {
		if (isset($_POST['checkboxes'])) {
			$del_count = 0;

			foreach ($_POST['checkboxes'] as $key => $id) {
				$sql = 'UPDATE ' . $ecs->table('team_goods') . ' SET is_team = 0 ' . (' WHERE id =\'' . $id . '\' LIMIT 1 ');
				$db->query($sql);
				$del_count++;
			}

			if (0 < $del_count) {
				clear_cache_files();
			}

			$links[] = array('text' => $_LANG['return_team_goods_list'], 'href' => 'team.php?act=list');
			sys_msg(sprintf($_LANG['team_goods_delete_success'], $del_count), 0, $links);
		}
		else {
			$links[] = array('text' => $_LANG['back_list'], 'href' => 'team.php?act=list');
			sys_msg($_LANG['no_select_group_buy'], 0, $links);
		}
	}
	else if ($_REQUEST['act'] == 'remove') {
		check_authz_json('team_manage');
		$id = intval($_GET['id']);
		$sql = 'UPDATE ' . $ecs->table('team_goods') . ' SET is_team = 0 ' . (' WHERE id =\'' . $id . '\' LIMIT 1 ');
		$db->query($sql);
		clear_cache_files();
		$url = 'team.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header('Location: ' . $url . "\n");
		exit();
	}
}

if ($_REQUEST['act'] == 'team_info') {
	$smarty->assign('primary_cat', $_LANG['02_promotion']);
	$smarty->assign('full_page', 1);
	$smarty->assign('ur_here', $_LANG['team_info']);
	$smarty->assign('menu_select', array('action' => '02_promotion', 'current' => '18_team'));
	$tab_menu = array();
	$tab_menu[] = array('curr' => 0, 'text' => $_LANG['team_goods_list'], 'href' => 'team.php?act=list');
	$tab_menu[] = array('curr' => 1, 'text' => $_LANG['team_info'], 'href' => 'team.php?act=team_info');
	$smarty->assign('tab_menu', $tab_menu);
	$list = team_info_list($adminru['ru_id']);
	$page_count_arr = seller_page($list, $_REQUEST['page']);
	$smarty->assign('page_count_arr', $page_count_arr);
	$smarty->assign('team_info_list', $list['item']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$sort_flag = sort_flag($list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);
	assign_query_info();
	$smarty->display('team_info_list.dwt');
}
else if ($_REQUEST['act'] == 'team_info_query') {
	$list = team_info_list($adminru['ru_id']);
	$page_count_arr = seller_page($list, $_REQUEST['page']);
	$smarty->assign('page_count_arr', $page_count_arr);
	$smarty->assign('team_info_list', $list['item']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$sort_flag = sort_flag($list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);
	make_json_result($smarty->fetch('team_info_list.dwt'), '', array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}
else if ($_REQUEST['act'] == 'remove_info') {
	$team_id = intval($_GET['id']);
	$sql = 'UPDATE ' . $ecs->table('team_log') . ' SET is_show = 0 ' . (' WHERE team_id =\'' . $team_id . '\' LIMIT 1 ');
	$db->query($sql);
	clear_cache_files();
	$url = 'team.php?act=team_info_query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
	ecs_header('Location: ' . $url . "\n");
	exit();
}
else if ($_REQUEST['act'] == 'remove_team_info') {
	if (isset($_POST['checkboxes'])) {
		$del_count = 0;

		foreach ($_POST['checkboxes'] as $key => $id) {
			$sql = 'UPDATE ' . $ecs->table('team_log') . ' SET is_show = 0 ' . (' WHERE team_id =\'' . $id . '\' LIMIT 1 ');
			$db->query($sql);
			$del_count++;
		}

		if (0 < $del_count) {
			clear_cache_files();
		}

		$links[] = array('text' => $_LANG['return_team_goods_list'], 'href' => 'team.php?act=team_info');
		sys_msg(sprintf($_LANG['team_goods_delete_success'], $del_count), 0, $links);
	}
	else {
		$links[] = array('text' => $_LANG['back_list'], 'href' => 'team.php?act=team_info');
		sys_msg($_LANG['no_select_group_buy'], 0, $links);
	}
}

if ($_REQUEST['act'] == 'team_order') {
	$smarty->assign('primary_cat', $_LANG['02_promotion']);
	$smarty->assign('full_page', 1);
	$smarty->assign('ur_here', $_LANG['team_order']);
	$smarty->assign('menu_select', array('action' => '02_promotion', 'current' => '18_team'));
	$tab_menu = array();
	$tab_menu[] = array('curr' => 0, 'text' => $_LANG['team_goods_list'], 'href' => 'team.php?act=list');
	$tab_menu[] = array('curr' => 0, 'text' => $_LANG['team_info'], 'href' => 'team.php?act=team_info');
	$tab_menu[] = array('curr' => 1, 'text' => $_LANG['team_order'], 'href' => 'team.php?act=team_order');
	$smarty->assign('tab_menu', $tab_menu);
	$team_id = $_REQUEST['team_id'];
	$list = team_order_list($adminru['ru_id'], $team_id);
	$page_count_arr = seller_page($list, $_REQUEST['page']);
	$smarty->assign('page_count_arr', $page_count_arr);
	$smarty->assign('team_order_list', $list['item']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$sort_flag = sort_flag($list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);
	assign_query_info();
	$smarty->display('team_order_list.dwt');
}
else if ($_REQUEST['act'] == 'team_order_query') {
	$list = team_order_list($adminru['ru_id']);
	$page_count_arr = seller_page($list, $_REQUEST['page']);
	$smarty->assign('page_count_arr', $page_count_arr);
	$smarty->assign('team_order_list', $list['item']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$sort_flag = sort_flag($list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);
	make_json_result($smarty->fetch('team_order_list.dwt'), '', array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}
else if ($_REQUEST['act'] == 'group_goods') {
	check_authz_json('team_manage');
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$filter = $json->decode($_GET['JSON']);
	$arr = get_goods_info($filter->goods_id);
	make_json_result($arr);
}
else if ($_REQUEST['act'] == 'search_goods') {
	check_authz_json('team_manage');
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$filter = $json->decode($_GET['JSON']);
	$arr = get_goods_list($filter);
	make_json_result($arr);
}

?>
