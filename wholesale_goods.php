<?php

/**
 * DSC 批发前台文件
 * ============================================================================
 * 版权所有 2005-2016 shoprooo公司，并保留所有权利。
 * 
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Zhuo $
 * $Id: common.php 2016-01-04 Zhuo $
 */
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

require(ROOT_PATH . '/includes/lib_area.php');  //ecmoban模板堂 --zhuo
require(ROOT_PATH . '/includes/lib_wholesale.php');
require(ROOT_PATH . '/includes/lib_publicfunc.php');

if($GLOBALS['_CFG']['wholesale_user_rank'] == 0){
    $is_seller = get_is_seller();
    if($is_seller == 0){
        ecs_header("Location: " .$ecs->url(). "\n");
    }
}

//ecmoban模板堂 地区 begin
$area_info = get_area_info($province_id);
$area_id = $area_info['region_id'];

$smarty->assign('helps', get_shop_help());       // 网店帮助


$where = "regionId = '$province_id'";
$date = array('parent_id');
$region_id = get_table_date('region_warehouse', $where, $date, 2);

if (isset($_COOKIE['region_id']) && !empty($_COOKIE['region_id'])) {
    $region_id = $_COOKIE['region_id'];
}
//ecmoban模板堂 地区 end

$user_id = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

/* 如果没登录，提示登录 */
/* if ($_SESSION['user_rank'] <= 0)
  {
  show_message($_LANG['ws_user_rank'], $_LANG['ws_return_home'], 'index.php');
  } */

/* 过滤 XSS 攻击和SQL注入 */
get_request_filter();

/* ------------------------------------------------------ */
//-- 改变属性、数量时重新计算商品价格
/* ------------------------------------------------------ */

if (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'get_select_record') {
    include('includes/cls_json.php');

    $json = new JSON;
    $result = array('error' => '', 'message' => 0, 'content' => '');

    //处理数据
    $goods_id = empty($_REQUEST['goods_id']) ? 0 : intval($_REQUEST['goods_id']);
    //判断商品是否设置属性
    $goods_type = get_table_date('wholesale', "goods_id='$goods_id'", array('goods_type'), 2);
    if ($goods_type > 0) { //有属性的时候
        $attr_array = empty($_REQUEST['attr_array']) ? array() : $_REQUEST['attr_array'];
        $num_array = empty($_REQUEST['num_array']) ? array() : $_REQUEST['num_array'];
        $result['total_number'] = array_sum($num_array);
        //格式化属性数组
        $attr_num_array = array();
        foreach ($attr_array as $key => $val) {
            $arr = array();
            $arr['attr'] = $val;
            $arr['num'] = $num_array[$key];
            $attr_num_array[] = $arr;
        }
        //生成记录表格
        $record_data = get_select_record_data($goods_id, $attr_num_array);
        $smarty->assign('record_data', $record_data);
        $result['record_data'] = $smarty->fetch('library/wholesale_select_record_data.lbi');
    } else { //无属性的时候
        $goods_number = empty($_REQUEST['goods_number']) ? 0 : intval($_REQUEST['goods_number']); //购买数量	
        $result['total_number'] = $goods_number;
    }
    //计算价格
    $data = calculate_goods_price($goods_id, $result['total_number']);
    $result['data'] = $data;

    die($json->encode($result));
}

/* ------------------------------------------------------ */
//-- 改变属性、数量时重新计算商品价格
/* ------------------------------------------------------ */

if (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'price') {
    include('includes/cls_json.php');

    $json = new JSON;
    $res = array('err_msg' => '', 'err_no' => 0, 'result' => '', 'qty' => 1);

    $attr_id = isset($_REQUEST['attr']) && !empty($_REQUEST['attr']) ? explode(',', $_REQUEST['attr']) : array();
    $number = (isset($_REQUEST['number'])) ? intval($_REQUEST['number']) : 1;
    $warehouse_id = (isset($_REQUEST['warehouse_id'])) ? intval($_REQUEST['warehouse_id']) : 0;
    $area_id = (isset($_REQUEST['area_id'])) ? intval($_REQUEST['area_id']) : 0; //仓库管理的地区ID
    $goods_id = (isset($_REQUEST['id'])) ? intval($_REQUEST['id']) : 0;
    $onload = (isset($_REQUEST['onload'])) ? trim($_REQUEST['onload']) : ''; //仓库管理的地区ID

    $goods_attr = isset($_REQUEST['goods_attr']) && !empty($_REQUEST['goods_attr']) ? explode(',', $_REQUEST['goods_attr']) : array();
    $attr_ajax = get_wholesale_goods_attr_ajax($goods_id, $goods_attr, $attr_id);

    //获取主属性列表
    $act_id = get_table_date('wholesale', "goods_id='$goods_id'", array('act_id'), 2);
    $smarty->assign('goods', get_wholesale_goods_info($act_id));
    $main_attr_list = get_wholesale_main_attr_list($goods_id, $attr_id);
    $smarty->assign('main_attr_list', $main_attr_list);
    $res['main_attr_list'] = $smarty->fetch('library/wholesale_main_attr_list.lbi');
    
    die($json->encode($res));
}


$act_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$goods = get_wholesale_goods_info($act_id);
assign_template();
$position = assign_ur_here($goods['cat_id'], $goods['goods_name'], array(), '', $goods['user_id']);
$properties = get_wholesale_goods_properties($goods['goods_id'], $region_id, $area_id);  // 获得商品的规格和属性

$basic_info = get_shop_info_content($goods['user_id']);
$wholesale_rank = get_user_wholesale_rank($goods['rank_ids']);

$smarty->assign('wholesale_rank',$wholesale_rank);
/*  @author-bylu 判断当前商家是否允许"在线客服" start  */
$shop_information = get_shop_name($goods['user_id']);
$shop_information['kf_tel'] = $db->getOne("SELECT kf_tel FROM " . $ecs->table('seller_shopinfo') . "WHERE ru_id = '" . $goods['user_id'] . "'");
$business_practice = $db->getOne("SELECT business_practice FROM " . $ecs->table('seller_shopinfo') . "WHERE ru_id = '" . $goods['user_id'] . "'");
if ($business_practice == 1) {
    $shop_information['business_practice'] = '代购代销';
} else {
    $shop_information['business_practice'] = '厂家直销';
}
//判断当前商家是平台,还是入驻商家 bylu
if ($goods['user_id'] == 0) {
    //判断平台是否开启了IM在线客服
    if ($db->getOne("SELECT kf_im_switch FROM " . $ecs->table('seller_shopinfo') . "WHERE ru_id = 0")) {
        $shop_information['is_dsc'] = true;
    } else {
        $shop_information['is_dsc'] = false;
    }
} else {
    $shop_information['is_dsc'] = false;
}

$get_wholsale_navigator = get_wholsale_navigator();
$smarty->assign('get_wholsale_navigator', $get_wholsale_navigator);

$smarty->assign('shop_information', $shop_information);
$smarty->assign('kf_appkey', $basic_info['kf_appkey']); //应用appkey;
$smarty->assign('im_user_id', 'dsc' . $_SESSION['user_id']); //登入用户ID;
/*  @author-bylu  end  */

$basic_date = array('region_name');
$basic_info['province'] = get_table_date('region', "region_id = '" . $basic_info['province'] . "'", $basic_date, 2);
$basic_info['city'] = get_table_date('region', "region_id= '" . $basic_info['city'] . "'", $basic_date, 2) . "市";

$smarty->assign('basic_info', $basic_info);

//ecmoban模板堂 --zhuo start
$shop_info = get_merchants_shop_info('merchants_steps_fields', $goods['user_id']);
$adress = get_license_comp_adress($shop_info['license_comp_adress']);

$smarty->assign('shop_info', $shop_info);
$smarty->assign('adress', $adress);
//ecmoban模板堂 --zhuo end	

$goods_price = " IF(w.price_model=0, w.goods_price, (SELECT MIN(vp.volume_price) FROM " . $GLOBALS['ecs']->table('wholesale_volume_price') . " AS vp WHERE vp.goods_id = '$goods_id')) AS price ";
$sql = " SELECT g.goods_thumb, g.goods_name, w.act_id, $goods_price FROM " . $ecs->table('wholesale') . " AS w LEFT JOIN " .
        $ecs->table('goods') . " AS g ON g.goods_id = w.goods_id WHERE w.is_recommend = 1 AND g.user_id = '$goods[user_id]' ";
$seller_recommend = $db->getRow($sql);
if ($seller_recommend) {
    $seller_recommend['goods_url'] = build_uri('wholesale_goods', array('aid' => $seller_recommend['act_id']), $seller_recommend['goods_name']);
    $smarty->assign('seller_recommend', $seller_recommend);
}


//买家还在看
$see_more_goods = see_more_goods($goods['user_id'], $act_id);
$smarty->assign('see_more_goods', $see_more_goods);

//商品运费by wu start
$region = array(1, $province_id, $city_id, $district_id);
$shippingFee = goodsShippingFee($goods_id, $region_id, $area_id, $region);
$smarty->assign('shippingFee', $shippingFee);
//商品运费by wu end

$area = array(
    'region_id' => $region_id, //仓库ID
    'province_id' => $province_id,
    'city_id' => $city_id,
    'district_id' => $district_id,
    'goods_id' => $goods_id,
    'user_id' => $user_id,
    'area_id' => $area_id,
    'merchant_id' => $goods['user_id'],
);

if(defined('THEME_EXTENSION')){
		$wholesale_cat = get_wholesale_child_cat();
		$smarty->assign('wholesale_cat', $wholesale_cat);
	}

$smarty->assign('properties', $properties['pro']);      // 商品规格	
$smarty->assign('specification', $properties['spe']);      // 商品属性	
$smarty->assign('page_title', $position['title']);      // 页面标题
$smarty->assign('ur_here', $position['ur_here']);    // 当前位置
$smarty->assign('now_time', gmtime());             // 当前系统时间
$smarty->assign('seller_id', $seller_id);
$smarty->assign('goods', $goods);
$smarty->assign('cfg', $_CFG);
$smarty->assign('goods_id', $goods['goods_id']);
$smarty->assign('wholesale_param', $goods['price_ladder']);
$smarty->assign('area', $area);
$smarty->assign('act_id', $act_id);
$pictures = get_goods_gallery($goods['goods_id']);
$smarty->assign('pictures',            $pictures);                    // 商品相册

//属性列表 by wu
$main_attr_list = get_wholesale_main_attr_list($goods['goods_id']);
$smarty->assign('main_attr_list', $main_attr_list);

$smarty->display('wholesale_goods.dwt', $cache_id);
