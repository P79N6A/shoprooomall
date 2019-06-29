<?php

/**
 * shoprooo 求购管理程序
 * ============================================================================
 * * 版权所有 2005-2016 shoprooo公司，并保留所有权利。
 * 
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: wholesale_purchase.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . '/includes/lib_wholesale.php');

include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);
$exc   = new exchange($ecs->table("wholesale_purchase"), $db, 'purchase_id', 'subject');
$exc_goods   = new exchange($ecs->table("wholesale_purchase_goods"), $db, 'goods_id', 'goods_name');

/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

//ecmoban模板堂 --zhuo start
$adminru = get_admin_ru_id();
$ruCat = '';
if($adminru['ru_id'] == 0){
        $smarty->assign('priv_ru',   1);
}else{
        $smarty->assign('priv_ru',   0);
}
//ecmoban模板堂 --zhuo end

include_once(ROOT_PATH . 'languages/' . $_CFG['lang'] . '/wholesale_purchase.php');
$smarty->assign('lang', $_LANG);
/*------------------------------------------------------ */
//-- 求购列表页面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
	admin_priv('wholesale_purchase');
	
    $smarty->assign('ur_here',     $_LANG['01_wholesale_purchase']);
    $smarty->assign('full_page',  1);
	
    $purchase_list = purchase_list();

    $smarty->assign('purchase_list',     $purchase_list['purchase_list']);
    $smarty->assign('filter',       $purchase_list['filter']);
    $smarty->assign('record_count', $purchase_list['record_count']);
    $smarty->assign('page_count',   $purchase_list['page_count']);
	
    $sort_flag  = sort_flag($purchase_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('purchase_list.dwt');
}

/*------------------------------------------------------ */
//-- 求购信息页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    admin_priv('wholesale_purchase');	

	$purchase_id = empty($_REQUEST['purchase_id'])? 0:intval($_REQUEST['purchase_id']);

    $smarty->assign('ur_here',       $_LANG['purchase_info']);
    $smarty->assign('action_link',   array('href' => 'wholesale_purchase.php?act=list', 'text' => $_LANG['01_wholesale_purchase']));
    $smarty->assign('form_act',      'update');
    $smarty->assign('action',        'edit');
    $smarty->assign('purchase_info', get_purchase_info($purchase_id));

    assign_query_info();
    $smarty->display('purchase_info.dwt');
}

/*------------------------------------------------------ */
//-- 广告编辑的处理
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'update')
{
    admin_priv('ad_manage');

    /* 初始化变量 */
    $purchase_id = empty($_REQUEST['purchase_id'])? 0:intval($_REQUEST['purchase_id']);

	//clear_cache_files(); // 清除模版缓存

    /* 提示信息 */
    $href[] = array('text' => $_LANG['back_list'], 'href' => 'wholesale_purchase.php?act=list');
    sys_msg($_LANG['edit_success'], 0, $href);
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $purchase_list = purchase_list();

    $smarty->assign('purchase_list',     $purchase_list['purchase_list']);
    $smarty->assign('filter',       $purchase_list['filter']);
    $smarty->assign('record_count', $purchase_list['record_count']);
    $smarty->assign('page_count',   $purchase_list['page_count']);

    $sort_flag  = sort_flag($purchase_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('purchase_list.dwt'), '',
        array('filter' => $purchase_list['filter'], 'page_count' => $purchase_list['page_count']));
}

/*------------------------------------------------------ */
//-- 批量操作
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch')
{
    /* 取得要操作的记录编号 */
    if (empty($_POST['checkboxes']))
    {
        sys_msg($_LANG['no_record_selected']);
    }
    else
    {
        /* 检查权限 */
        admin_priv('whole_sale');

        $ids = $_POST['checkboxes'];

        if (isset($_POST['drop']))
        {
            /* 删除记录 */
            $sql = "DELETE w FROM " . $ecs->table('wholesale_purchase') . " AS W " .
                    " WHERE purchase_id " . db_create_in($ids);
            $db->query($sql);

            /* 记日志 */
            admin_log('', 'batch_remove', 'wholesale_purchase');

            /* 清除缓存 */
            clear_cache_files();

            $links[] = array('text' => $_LANG['back_list'], 'href' => 'wholesale_purchase.php?act=list&' . list_link_postfix());
            sys_msg($_LANG['batch_drop_ok'], 0, $links);
        }
    }
}


/*------------------------------------------------------ */
//-- 删除求购信息
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('wholesale_purchase');

    $id = intval($_GET['id']);

    $exc->drop($id);
	
	//删除商品信息和图片
	$goods_list = get_table_date('wholesale_purchase_goods', "purchase_id='$id'", array('goods_id', 'goods_img'), 1);
	foreach($goods_list as $key=>$val){
		if(!empty($val['goods_img'])){
			$goods_img = unserialize($val['goods_img']);
			foreach($goods_img as $k=>$v){
				@unlink(ROOT_PATH . $v);
			}
		}
		$exc_goods->drop($val['goods_id']);
	}

    $url = 'wholesale_purchase.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 切换审核状态
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'toggle_review_status')
{
    check_authz_json('wholesale_purchase');

    $id = intval($_POST['id']);
    $val = intval($_POST['val']);

	$sql = " UPDATE ".$GLOBALS['ecs']->table('wholesale_purchase')." SET review_status = '$val' WHERE purchase_id = '$id' ";
    if ($GLOBALS['db']->query($sql))
    {
        //clear_cache_files();
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}

/* 获取求购数据列表 */
function purchase_list()
{	
    /* 过滤查询 */
    $filter = array();
	
    //ecmoban模板堂 --zhuo start
    $filter['keyword'] = !empty($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
    if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
    {
		$filter['keyword'] = json_str_iconv($filter['keyword']);
    }
    //ecmoban模板堂 --zhuo end
    
    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'purchase_id' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

    $where = 'WHERE 1 ';
    
    /* 关键字 */
    if (!empty($filter['keyword']))
    {
        $where .= " AND subject LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";  
    }

    /* 获得总记录数据 */
    $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('wholesale_purchase') . $where;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    $filter = page_and_size($filter);

    /* 获得广告数据 */
    $arr = array();
    $sql = 'SELECT * FROM ' .$GLOBALS['ecs']->table('wholesale_purchase').$where.
		'ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];

    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $idx = 0;
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        /* 格式化日期 */
        $rows['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $rows['add_time']);
        $rows['end_time'] = local_date($GLOBALS['_CFG']['time_format'], $rows['end_time']);
        $rows['user_name'] = get_table_date('users', "user_id = '$rows[user_id]'", array('user_name'), 2);
        //$rows['user_name'] = get_shop_name($rows['user_id'], 1); //ecmoban模板堂 --zhuo
		 
        $arr[$idx] = $rows;

        $idx++;
    }

    return array('purchase_list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}


?>