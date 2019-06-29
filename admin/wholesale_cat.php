<?php

/**
 * shoprooo 商品分类管理程序
 * ============================================================================
 * * 版权所有 2005-2016 shoprooo公司，并保留所有权利。
 * 
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: li $
 * $Id: presale_cat.php 17217 2015-11-18 li $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/lib_wholesale.php');
$exc = new exchange($ecs->table("wholesale_cat"), $db, 'cat_id', 'cat_name');

/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

/*------------------------------------------------------ */
//-- 商品分类列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list') {
    $parent_id = isset($_GET['parent_id']) ? intval($_GET['parent_id']) : 0;
	$level = isset($_REQUEST['level']) ? $_REQUEST['level'] + 1 : 0;

    if ($parent_id) {
        $cat_list = wholesale_child_cat($parent_id);
		$smarty->assign('parent_id', $parent_id);
    } else {
        $cat_list = wholesale_cat_list(0, 0, false, 0, true, 'admin');
		// var_dump($cat_list);
    }
    /* 获取分类列表 */

    //ecmoban模板堂 --zhuo start
    $adminru = get_admin_ru_id();
    $smarty->assign('ru_id', $adminru['ru_id']);

    if ($adminru['ru_id'] == 0) {
        $smarty->assign('action_link', array('href' => 'wholesale_cat.php?act=add', 'text' => $_LANG['add_wholesale_cat']));
    }
    //ecmoban模板堂 --zhuo end

    /* 模板赋值 */
    $smarty->assign('ur_here', $_LANG['wholesale_cat']);
    $smarty->assign('full_page', 1);
	$smarty->assign('level', $level);
    $smarty->assign('cat_info', $cat_list);

    //区分自营和店铺
    self_seller(BASENAME($_SERVER['PHP_SELF']));      
    
    /* 列表页面 */
    assign_query_info();
    $smarty->display('wholesale_cat_list.dwt');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $cat_list = wholesale_cat_list(0, 0, false);
    $smarty->assign('cat_info',     $cat_list);

    //ecmoban模板堂 --zhuo start
    $adminru = get_admin_ru_id();
    $smarty->assign('ru_id',     $adminru['ru_id']);
    //ecmoban模板堂 --zhuo end

    make_json_result($smarty->fetch('wholesale_cat_list.dwt'));
}
/*------------------------------------------------------ */
//-- 添加商品分类
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'add') {
    /* 权限检查 */
    admin_priv('whole_sale');

    /* 模板赋值 */
    $smarty->assign('ur_here', $_LANG['add_wholesale_cat']);
    $smarty->assign('action_link', array('href' => 'wholesale_cat.php?act=list', 'text' => $_LANG['wholesale_cat_list']));

    $parent_id = isset($_GET['parent_id']) ? intval($_GET['parent_id']) : 0;

    $cat_select = wholesale_cat_list(0, 0, false, 0, true, '', 1);
	
    /* 简单处理缩进 */
    foreach ($cat_select as $k => $v) {
        if ($v['level']) {
            $level = str_repeat('&nbsp;', $v['level'] * 4);
            $cat_select[$k]['name'] = $level . $v['name'];
        }
    }

    $smarty->assign('cat_select', $cat_select);
    $smarty->assign('form_act', 'insert');
    $smarty->assign('cat_info', array('is_show' => 1, 'parent_id' => $parent_id));

    //ecmoban模板堂 --zhuo start
    $adminru = get_admin_ru_id();
    $smarty->assign('ru_id', $adminru['ru_id']);
    //ecmoban模板堂 --zhuo end

    /* 显示页面 */
    assign_query_info();
    $smarty->display('wholesale_cat_info.dwt');
}

/*------------------------------------------------------ */
//-- 商品分类添加时的处理
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'insert')
{
    /* 权限检查 */
    admin_priv('whole_sale');

    /* 初始化变量 */
    $cat['parent_id']   	= !empty($_POST['parent_id'])    	? intval($_POST['parent_id'])  	: 0;
    $cat['sort_order']  	= !empty($_POST['sort_order'])   	? intval($_POST['sort_order']) 	: 0;
    $cat['cat_name']    	= !empty($_POST['cat_name'])     	? trim($_POST['cat_name'])     	: '';
    $cat['keywords']		= !empty($_POST['keywords']) 		? trim($_POST['keywords']) 		: '';
    $cat['cat_desc'] 		= !empty($_POST['cat_desc']) 		? $_POST['cat_desc'] 			: '';
    $cat['cat_alias_name'] 	= !empty($_POST['cat_alias_name']) 	? trim($_POST['cat_alias_name']): '';
    $cat['show_in_nav'] 	= !empty($_POST['show_in_nav']) 	? intval($_POST['show_in_nav']) : 0;
    $cat['is_show'] 		= !empty($_POST['is_show']) 		? intval($_POST['is_show']) 	: 0;
	$cat['style_icon'] 		= !empty($_POST['style_icon']) 		? trim($_POST['style_icon']) 	: 'other'; //分类菜单图标
	
    $pin = new pin();
    $pinyin = $pin->Pinyin($cat['cat_name'], 'UTF8');
    $cat['pinyin_keyword'] = $pinyin;

    if (cname_exists($cat['cat_name'], $cat['parent_id']))
    {
        /* 同级别下不能有重复的分类名称 */
       $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
       sys_msg($_LANG['catname_exist'], 0, $link);
    }
	
	//上传分类菜单图标 begin
	if (!empty($_FILES['cat_icon']['name'])) {
		if ($_FILES["cat_icon"]["size"] > 200000) {
			sys_msg('上传图片不得大于200kb', 0, $link);
		}

		$icon_name = explode('.', $_FILES['cat_icon']['name']);
		$key = count($icon_name);
		$type = $icon_name[$key - 1];
		
		if ($type != 'jpg' && $type != 'png' && $type != 'gif') {
			sys_msg('请上传jpg,gif,png格式图片', 0, $link);
		}
		$imgNamePrefix = time() . mt_rand(1001, 9999);
		//文件目录
		$imgDir = ROOT_PATH . "images/cat_icon";
		if (!file_exists($imgDir)) {
			mkdir($imgDir);
		}
		//保存文件
		$imgName = $imgDir . "/" . $imgNamePrefix . '.' . $type;
		$saveDir = "images/cat_icon" . "/" . $imgNamePrefix . '.' . $type;
		move_uploaded_file($_FILES["cat_icon"]["tmp_name"], $imgName);
		$cat['cat_icon'] = $saveDir;
	}
	//上传分类菜单图标 end

    /* 入库的操作 */
    if ($db->autoExecute($ecs->table('wholesale_cat'), $cat) !== false)
    {
        $cat_id = $db->insert_id();

        admin_log($_POST['cat_name'], 'add', 'wholesale_cat');   // 记录管理员操作
        clear_cache_files();    // 清除缓存

        /*添加链接*/
        $link[0]['text'] = $_LANG['continue_add'];
        $link[0]['href'] = 'wholesale_cat.php?act=add';

        $link[1]['text'] = $_LANG['back_list'];
        $link[1]['href'] = 'wholesale_cat.php?act=list';

        sys_msg($_LANG['catadd_succed'], 0, $link);
    }
 }

/*------------------------------------------------------ */
//-- 编辑商品分类信息
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit') {
    admin_priv('whole_sale');   // 权限检查
    
    $cat_id = intval($_REQUEST['cat_id']);
    
    $cat_info = get_cat_info($cat_id, array(), 'wholesale_cat');  // 查询分类信息数据

    $smarty->assign('ur_here', $_LANG['edit_wholesale_cat']);
    $smarty->assign('action_link', array('text' => $_LANG['wholesale_cat_list'], 'href' => 'wholesale_cat.php?act=list'));

    //ecmoban模板堂 --zhuo start
    $smarty->assign('cat_id', $cat_id);

    $adminru = get_admin_ru_id();
    $smarty->assign('ru_id', $adminru['ru_id']);
    //ecmoban模板堂 --zhuo end
    $smarty->assign('cat_info', $cat_info);
    $smarty->assign('form_act', 'update');

    $cat_select = wholesale_cat_list(0, $cat_info['parent_id'], false, 0, true, '', 1);
    /* 简单处理缩进 */
    foreach ($cat_select as $k => $v) {
        if ($v['level']) {
            $level = str_repeat('&nbsp;', $v['level'] * 4);
            $cat_select[$k]['name'] = $level . $v['name'];
        }
    }

    $smarty->assign('cat_select', $cat_select);

    /* 显示页面 */
    assign_query_info();
    $smarty->display('wholesale_cat_info.dwt');
}

/*------------------------------------------------------ */
//-- 编辑商品分类信息
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'update')
{
    /* 权限检查 */
    admin_priv('whole_sale');

    /* 初始化变量 */
    $old_cat_name        	= $_POST['old_cat_name'];
    $cat_id              	= !empty($_POST['cat_id'])       	? intval($_POST['cat_id'])     : 0;
	
    $cat['parent_id']   	= !empty($_POST['parent_id'])    	? intval($_POST['parent_id'])  	: 0;
    $cat['sort_order']  	= !empty($_POST['sort_order'])   	? intval($_POST['sort_order']) 	: 0;
    $cat['cat_name']    	= !empty($_POST['cat_name'])     	? trim($_POST['cat_name'])     	: '';
    $cat['keywords']		= !empty($_POST['keywords']) 		? trim($_POST['keywords']) 		: '';
    $cat['cat_desc'] 		= !empty($_POST['cat_desc']) 		? $_POST['cat_desc'] 			: '';
    $cat['cat_alias_name'] 	= !empty($_POST['cat_alias_name']) 	? trim($_POST['cat_alias_name']): '';
    $cat['show_in_nav'] 	= !empty($_POST['show_in_nav']) 	? intval($_POST['show_in_nav']) : 0;
    $cat['is_show'] 		= !empty($_POST['is_show']) 		? intval($_POST['is_show']) 	: 0;
	$cat['style_icon'] 		= !empty($_POST['style_icon']) 		? trim($_POST['style_icon']) 	: 'other'; //分类菜单图标
    
    $adminru = get_admin_ru_id();
	
    /* 判断分类名是否重复 */   
    if ($cat['cat_name'] != $old_cat_name)
    {
        if (wholesale_cat_exists($cat['cat_name'],$cat['parent_id'], $cat_id))
        {
           $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
           sys_msg($_LANG['catname_exist'], 0, $link);
        }
    }

    $pin = new pin();
    $pinyin = $pin->Pinyin($cat['cat_name'], 'UTF8');
    $cat['pinyin_keyword'] = $pinyin;	
	
	//上传分类菜单图标 begin
	if (!empty($_FILES['cat_icon']['name'])) {
		if ($_FILES["cat_icon"]["size"] > 200000) {
			sys_msg('上传图片不得大于200kb', 0, $link);
		}

		$icon_name = explode('.', $_FILES['cat_icon']['name']);
		$key = count($icon_name);
		$type = $icon_name[$key - 1];
		
		if ($type != 'jpg' && $type != 'png' && $type != 'gif') {
			sys_msg('请上传jpg,gif,png格式图片', 0, $link);
		}
		$imgNamePrefix = time() . mt_rand(1001, 9999);
		//文件目录
		$imgDir = ROOT_PATH . "images/cat_icon";
		if (!file_exists($imgDir)) {
			mkdir($imgDir);
		}
		//保存文件
		$imgName = $imgDir . "/" . $imgNamePrefix . '.' . $type;
		$saveDir = "images/cat_icon" . "/" . $imgNamePrefix . '.' . $type;
		move_uploaded_file($_FILES["cat_icon"]["tmp_name"], $imgName);
		$cat['cat_icon'] = $saveDir;
	}
	//上传分类菜单图标 end
	
    $dat = $db->getRow("SELECT cat_name FROM ". $ecs->table('wholesale_cat') . " WHERE cat_id = '$cat_id'");

    if ($db->autoExecute($ecs->table('wholesale_cat'), $cat, 'UPDATE', "cat_id = '$cat_id'"))
    {	
        clear_cache_files(); // 清除缓存
        admin_log($_POST['cat_name'], 'edit', 'wholesale_cat'); // 记录管理员操作

        /* 提示信息 */
        $link[] = array('text' => $_LANG['back_list'], 'href' => 'wholesale_cat.php?act=list');
        sys_msg($_LANG['catedit_succed'], 0, $link);
    }
}

/*------------------------------------------------------ */
//-- 编辑排序序号
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'edit_sort_order')
{
    check_authz_json('whole_sale');

    $id = intval($_POST['id']);
    $val = intval($_POST['val']);

    if (cat_update($id, array('sort_order' => $val)))
    {
        clear_cache_files(); // 清除缓存
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}

/*------------------------------------------------------ */
//-- 切换是否显示
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_show')
{
    check_authz_json('auction');

    $cat_id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("is_show = '$val'", $cat_id);
    clear_cache_files();

    make_json_result($val);
}

/*------------------------------------------------------ */
//-- 删除商品分类
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'remove')
{
    check_authz_json('whole_sale');

    /* 初始化分类ID并取得分类名称 */
    $cat_id   = intval($_GET['id']);
    $cat_name = $db->getOne('SELECT cat_name FROM ' .$ecs->table('wholesale_cat'). " WHERE cat_id = '$cat_id'");

    /* 当前分类下是否有子分类 */
    $cat_count = $db->getOne('SELECT COUNT(*) FROM ' .$ecs->table('wholesale_cat'). " WHERE parent_id = '$cat_id'");

    /* 当前分类下是否存在商品 */
    $goods_count = $db->getOne('SELECT COUNT(*) FROM ' .$ecs->table('wholesale'). " WHERE wholesale_cat_id = '$cat_id'");

    /* 如果不存在下级子分类和商品，则删除 */
    if ($cat_count == 0 && $goods_count == 0)
    {
        /* 删除分类 */
        $sql = 'DELETE FROM ' .$ecs->table('wholesale_cat'). " WHERE cat_id = '$cat_id'";
        if ($db->query($sql))
        {
            clear_cache_files();
            admin_log($cat_name, 'remove', 'wholesale_cat');
        }
    }
    else
    {
        make_json_error($cat_name .' '. $_LANG['cat_isleaf']);
    }

    $url = 'wholesale_cat.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- PRIVATE FUNCTIONS
/*------------------------------------------------------ */
//
///**
// * 检查分类是否已经存在
// *
// * @param   string      $cat_name       分类名称
// * @param   integer     $parent_cat     上级分类
// * @param   integer     $exclude        排除的分类ID
// *
// * @return  boolean
// */
function wholesale_cat_exists($cat_name, $parent_cat, $exclude = 0)
{
    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('wholesale_cat').
           " WHERE parent_id = '$parent_cat' AND cat_name = '$cat_name' AND cat_id <> '$exclude'";
    return ($GLOBALS['db']->getOne($sql) > 0) ? true : false;
}

/**
 * 添加商品分类
 *
 * @param   integer $cat_id
 * @param   array   $args
 *
 * @return  mix
 */
function cat_update($cat_id, $args)
{
    if (empty($args) || empty($cat_id))
    {
        return false;
    }

    return $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('presale_cat'), $args, 'update', "cat_id='$cat_id'");
}

/**
 * 检查分类是否已经存在
 *
 * @param   string      $cat_name       分类名称
 * @param   integer     $parent_cat     上级分类
 * @param   integer     $exclude        排除的分类ID
 *
 * @return  boolean
 */
function cname_exists($cat_name, $parent_cat, $exclude = 0)
{
    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('wholesale_cat').
    " WHERE parent_id = '$parent_cat' AND cat_name = '$cat_name' AND cat_id <> '$exclude'";
    return ($GLOBALS['db']->getOne($sql) > 0) ? true : false;
}

/*预售商品下级分类*/
function wholesale_child_cat($pid){
	$sql = " SELECT cat_id, cat_name, parent_id, sort_order FROM ".$GLOBALS['ecs']->table('wholesale_cat')." WHERE parent_id = '$pid' ";
	return $GLOBALS['db']->getAll($sql);
}

?>