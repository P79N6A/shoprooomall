<?php
//zend by QQ:97908527  SHOPROOO网络  禁止倒卖 一经发现停止任何服务
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require 'includes/lib_goods.php';
require ROOT_PATH . '/includes/lib_wholesale.php';
if ($_REQUEST['act'] == 'add') 
{
	admin_priv('goods_manage');
	$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '15_batch_edit'));
	$smarty->assign('current', '15_batch_edit');
	$goods_id = (isset($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : 0);
	$model = (isset($_REQUEST['model']) ? intval($_REQUEST['model']) : 0);
	$warehouse_id = (isset($_REQUEST['warehouse_id']) ? intval($_REQUEST['warehouse_id']) : 0);
	if (0 < $goods_id) 
	{
	}
	$dir = opendir('../languages');
	$lang_list = array('UTF8' => $_LANG['charset']['utf8'], 'GB2312' => $_LANG['charset']['zh_cn'], 'BIG5' => $_LANG['charset']['zh_tw']);
	$download_list = array();
	while (@$file = readdir($dir)) 
	{
		if (($file != '.') && ($file != '..') && ($file != '.svn') && ($file != '_svn') && (is_dir('../languages/' . $file) == true)) 
		{
			$download_list[$file] = sprintf($_LANG['download_file'], isset($_LANG['charset'][$file]) ? $_LANG['charset'][$file] : $file);
		}
	}
	@closedir($dir);
	$smarty->assign('lang_list', $lang_list);
	$smarty->assign('download_list', $download_list);
	$smarty->assign('goods_id', $goods_id);
	$smarty->assign('model', $model);
	$smarty->assign('warehouse_id', $warehouse_id);
	$goods_date = array('goods_name');
	$where = 'goods_id = \'' . $goods_id . '\'';
	$goods_name = get_table_date('goods', $where, $goods_date, 2);
	$smarty->assign('goods_name', $goods_name);
	$smarty->assign('primary_cat', $_LANG['02_cat_and_goods']);
	$ur_here = '批发货品批量上传';
	$smarty->assign('ur_here', $ur_here);
	assign_query_info();
	$smarty->display('wholesale_goods_produts_batch.dwt');
}
else if ($_REQUEST['act'] == 'upload') 
{
	admin_priv('goods_manage');
	$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '15_batch_edit'));
	if ($_FILES['file']['name']) 
	{
		$attr_names = file($_FILES['file']['tmp_name']);
		$attr_names = explode(',', $attr_names[0]);
		$end = -2;
		$attr_names = array_slice($attr_names, 6, $end);
		foreach ($attr_names as $k => $v ) 
		{
			$attr_names[$k] = ecs_iconv('GBK', 'UTF8', $v);
		}
		$attr_num = count($attr_names);
		$line_number = 0;
		$arr = array();
		$goods_list = array();
		$field_list = array_keys($_LANG['upload_product']);
		for ($i = 0; $i < $attr_num; $i++) 
		{
			$field_list[] = 'goods_attr' . $i;
		}
		$field_list[] = 'product_number';
		$field_list[] = 'product_sn';
		$_POST['charset'] = 'GB2312';
		$data = file($_FILES['file']['tmp_name']);
		if (0 < count($data)) 
		{
			foreach ($data as $line ) 
			{
				if ($line_number == 0) 
				{
					$line_number++;
					continue;
				}
				if (($_POST['charset'] != 'UTF8') && (strpos(strtolower(EC_CHARSET), 'utf') === 0)) 
				{
					$line = ecs_iconv($_POST['charset'], 'UTF8', $line);
				}
				$arr = array();
				$buff = '';
				$quote = 0;
				$len = strlen($line);
				for ($i = 0; $i < $len; $i++) 
				{
					$char = $line[$i];
					if ('\\' == $char) 
					{
						$i++;
						$char = $line[$i];
						switch ($char) 
						{
							case '"': $buff .= '"';
							break;
							case '\'': $buff .= '\'';
							break;
							case ',': $buff .= ',';
							break;
							default: $buff .= '\\' . $char;
							break;
						}
					}
					else if ('"' == $char) 
					{
						if (0 == $quote) 
						{
							$quote++;
						}
						else 
						{
							$quote = 0;
						}
					}
					else if (',' == $char) 
					{
						if (0 == $quote) 
						{
							if (!(isset($field_list[count($arr)]))) 
							{
								continue;
							}
							$field_name = $field_list[count($arr)];
							$arr[$field_name] = trim($buff);
							$buff = '';
							$quote = 0;
						}
						else 
						{
							$buff .= $char;
						}
					}
					else 
					{
						$buff .= $char;
					}
					if ($i == $len - 1) 
					{
						if (!(isset($field_list[count($arr)]))) 
						{
							continue;
						}
						$field_name = $field_list[count($arr)];
						$arr[$field_name] = trim($buff);
					}
				}
				$goods_list[] = $arr;
			}
			$goods_list = get_wholesale_produts_list2($goods_list, $attr_num);
		}
	}
	$_SESSION['goods_list'] = $goods_list;
	$smarty->assign('full_page', 2);
	$smarty->assign('page', 1);
	$smarty->assign('attr_names', $attr_names);
	assign_query_info();
	$smarty->assign('ur_here', '批发货品批量上传');
	$smarty->display('wholesale_goods_produts_batch_add.dwt');
}
else if ($_REQUEST['act'] == 'ajax_insert') 
{
	admin_priv('goods_manage');
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$result = array( 'list' => array(), 'is_stop' => 0 );
	$page = (!(empty($_REQUEST['page'])) ? intval($_REQUEST['page']) : 1);
	$page_size = (isset($_REQUEST['page_size']) ? intval($_REQUEST['page_size']) : 1);
	@set_time_limit(300);
	if (isset($_SESSION['goods_list']) && $_SESSION['goods_list']) 
	{
		$goods_list = $_SESSION['goods_list'];
		$goods_list = $ecs->page_array($page_size, $page, $goods_list);
		$result['list'] = $goods_list['list'][0];
		$result['page'] = $goods_list['filter']['page'] + 1;
		$result['page_size'] = $goods_list['filter']['page_size'];
		$result['record_count'] = $goods_list['filter']['record_count'];
		$result['page_count'] = $goods_list['filter']['page_count'];
		$result['is_stop'] = 1;
		if ($goods_list['filter']['page_count'] < $page) 
		{
			$result['is_stop'] = 0;
		}
		$other['goods_id'] = $result['list']['goods_id'];
		$other['goods_attr'] = $result['list']['goods_attr'];
		$other['product_number'] = $result['list']['product_number'];
		$other['product_sn'] = $result['list']['product_sn'];
		$sql = 'SELECT product_id FROM ' . $GLOBALS['ecs']->table('wholesale_products') . ' WHERE goods_id = \'' . $result['list']['goods_id'] . '\'' . ' AND goods_attr = \'' . $result['list']['goods_attr'] . '\'';
		if ($GLOBALS['db']->getOne($sql, true)) 
		{
			$db->autoExecute($ecs->table('wholesale_products'), $other, 'UPDATE', 'goods_id = \'' . $result['list']['goods_id'] . '\' AND goods_attr = \'' . $result['list']['goods_attr'] . '\'');
			$result['status_lang'] = '<span style="color: red;">已更新数据成功</span>';
		}
		else 
		{
			$other['admin_id'] = $_SESSION['seller_id'];
			$db->autoExecute($ecs->table('wholesale_products'), $other, 'INSERT');
			$result['status_lang'] = '<span style="color: red;">已添加数据成功</span>';
		}
	}
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'download') 
{
	admin_priv('goods_manage');
	$goods_id = (isset($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : 0);
	$model = (isset($_REQUEST['model']) ? intval($_REQUEST['model']) : 0);
	$warehouse_id = (isset($_REQUEST['warehouse_id']) ? intval($_REQUEST['warehouse_id']) : 0);
	$goods_attr = (isset($_REQUEST['goods_attr']) ? explode(',', $_REQUEST['goods_attr']) : array());
	header('Content-type: application/vnd.ms-excel; charset=utf-8');
	Header('Content-Disposition: attachment; filename=goods_produts_list' . $goods_id . '.csv');
	if ($_GET['charset'] != $_CFG['lang']) 
	{
		$lang_file = '../languages/' . $_GET['charset'] . '/' . ADMIN_PATH . '/goods_produts_warehouse_batch.php';
		if (file_exists($lang_file)) 
		{
			unset($_LANG['upload_product']);
			require $lang_file;
		}
	}
	if (isset($_LANG['upload_product'])) 
	{
		if (($_GET['charset'] == 'zh_cn') || ($_GET['charset'] == 'zh_tw')) 
		{
			$to_charset = ($_GET['charset'] == 'zh_cn' ? 'GB2312' : 'BIG5');
			$data = join(',', $_LANG['upload_product']);
			$attribute = get_wholesale_goods_specifications_list($goods_id);
			if (empty($attribute) && $goods_id) 
			{
				$link[] = array('href' => 'goods.php?act=edit&goods_id=' . $goods_id, 'text' => $_LANG['edit_goods']);
				sys_msg($_LANG['not_exist_goods_attr'], 1, $link);
			}
			foreach ($attribute as $attribute_value ) 
			{
				$_attribute[$attribute_value['attr_id']]['attr_values'][] = $attribute_value['attr_value'];
				$_attribute[$attribute_value['attr_id']]['attr_id'] = $attribute_value['attr_id'];
				$_attribute[$attribute_value['attr_id']]['attr_name'] = $attribute_value['attr_name'];
			}
			$attribute_count = count($_attribute);
			if ($_attribute) 
			{
				foreach ($_attribute as $k => $v ) 
				{
					$data .= ',' . $v['attr_name'];
				}
			}
			$data .= ',库存';
			$data .= ',货号' . "\t\n";
			if ($goods_id) 
			{
				$goods_info = get_admin_goods_info($goods_id, array('goods_name', 'goods_sn', 'user_id'));
				$goods_info['shop_name'] = get_shop_name($goods_info['user_id'], 1);
			}
			else 
			{
				$adminru = get_admin_ru_id();
				$goods_info['user_id'] = $adminru['ru_id'];
				$goods_info['shop_name'] = get_shop_name($adminru['ru_id'], 1);
			}
			$attr_info = get_list_download($goods_info['goods_sn'], '', $_attribute, count($_attribute), $model);
			foreach ($attr_info as $k => $v ) 
			{
				$data .= $goods_id . ',';
				$data .= $goods_info['goods_name'] . ',';
				$data .= $goods_info['goods_sn'] . ',';
				$data .= $goods_info['shop_name'] . ',';
				$data .= $goods_info['user_id'] . ',';
				$data .= $attr_info[$k]['region_name'] . ',';
				$data .= implode(',', $v['attr_value']) . ',';
				$data .= $attr_info[$k]['product_number'] . ',';
				$data .= $attr_info[$k]['product_sn'] . "\t\n";
			}
			echo ecs_iconv(EC_CHARSET, $to_charset, $data);
		}
		else 
		{
			echo join(',', $_LANG['upload_product']);
		}
	}
	else 
	{
		echo 'error: $_LANG[upload_product] not exists';
	}
}
function get_list_download($goods_sn = '', $warehouse_info = array(), $attr_info, $attr_num, $model = 0) 
{
	$goods_date = array('model_attr');
	$where = 'goods_sn = \'' . $goods_sn . '\' and is_delete = 0';
	$arr = array();
	if ($model == 0) 
	{
		if ($attr_info) 
		{
			foreach ($attr_info as $k => $v ) 
			{
				if ($v) 
				{
					foreach ($v as $k2 => $v2 ) 
					{
						if ($k2 == 'attr_values') 
						{
							$attr[] = $v2;
						}
					}
				}
			}
		}
		if ($attr) 
		{
			$comb = combination(array_keys($attr), $attr_num);
			$res = array();
			foreach ($comb as $r ) 
			{
				$t = array();
				foreach ($r as $k ) 
				{
					$t[] = $attr[$k];
				}
				$res = array_merge($res, attr_group($t));
			}
			foreach ($res as $k => $v ) 
			{
				$arr[$k]['goods_sn'] = $goods_sn;
				$arr[$k]['region_name'] = '默认模式';
				$arr[$k]['attr_value'] = $v;
				if ($GLOBALS['_CFG']['goods_attr_price'] == 1) 
				{
					$arr[$k]['product_price'] = '';
				}
				$arr[$k]['product_number'] = '';
				$arr[$k]['product_warn_number'] = '';
				$arr[$k]['product_sn'] = '';
				$arr[$k]['bar_code'] = '';
			}
		}
	}
	return $arr;
}
?>