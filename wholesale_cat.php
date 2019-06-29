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
require(ROOT_PATH . 'includes/lib_wholesale.php');

if($GLOBALS['_CFG']['wholesale_user_rank'] == 0){
    $is_seller = get_is_seller();
    if($is_seller == 0){
        ecs_header("Location: " .$ecs->url(). "\n");
    }
}

$page = !empty($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;
$size = !empty($_CFG['page_size']) && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 10;
$sort = (isset($_REQUEST['sort']) && in_array(trim(strtolower($_REQUEST['sort'])), array('sort_order'))) ? trim($_REQUEST['sort']) : $default_sort_order_type;
$order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC'))) ? trim($_REQUEST['order']) : $default_sort_order_method;

/* ------------------------------------------------------ */
//-- act 操作项的初始化
/* ------------------------------------------------------ */
if (empty($_REQUEST['act'])) {
    $_REQUEST['act'] = 'list';
}
/* ------------------------------------------------------ */
//-- 分类列表页
/* ------------------------------------------------------ */
if ($_REQUEST['act'] == 'list') { //批发分类页
    $cat_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);

    if ($cat_id) {
        $sql = " SELECT cat_name FROM " . $ecs->table('wholesale_cat') . " WHERE cat_id = '$cat_id' ";
        $smarty->assign('cat_name', $db->getOne($sql));
    }

    if (defined('THEME_EXTENSION')) {
        $wholesale_cat = get_wholesale_child_cat();
        $smarty->assign('wholesale_cat', $wholesale_cat);
    }
    $position = assign_ur_here();
    $goods_list = get_wholesale_list($cat_id, $size, $page, $sort);
    $children = get_children($cat_id, 3, 0, 'wholesale_cat');
    $count = get_wholesale_cat_goodsCount($children, $cat_id);

    $get_wholsale_navigator = get_wholsale_navigator();
    $smarty->assign('get_wholsale_navigator', $get_wholsale_navigator);

    $smarty->assign('goods_list', $goods_list);
    $smarty->assign('page_title', $position['title']);    // 页面标题
    $smarty->assign('ur_here', $position['ur_here']);  // 当前位置
    $smarty->assign('helps', get_shop_help());       // 网店帮助
    assign_cat_pager('wholesale_cat', $cat_id, $count, $size, $sort, $order, $page);
    assign_template('wholesale');
    /* 显示模板 */
    $smarty->display('wholesale_cat.dwt');
}

/**
 * 取得某页的批发商品
 * @param   int     $size   每页记录数
 * @param   int     $page   当前页
 * @param   string  $where  查询条件
 * @return  array
 */
function get_wholesale_list($cat_id, $size, $page, $sort, $order) {
    $list = array();
    $where = " WHERE 1 ";
    $table = 'wholesale_cat';
    $type = 4;
    $children = get_children($cat_id, $type, 0, $table);
    if ($cat_id) {
        $where .= " AND ($children OR " . get_wholesale_extension_goods($children) . ") ";
    }

    $sql = "SELECT w.*, g.goods_thumb, g.user_id,g.goods_name as goods_name, g.shop_price, market_price, MIN(wvp.volume_number) AS volume_number, MAX(wvp.volume_price) AS volume_price " .
            "FROM " . $GLOBALS['ecs']->table('wholesale') . " AS w, " .
            $GLOBALS['ecs']->table('goods') . " AS g "
            . " LEFT JOIN " . $GLOBALS['ecs']->table('wholesale_volume_price') . " AS wvp ON wvp.goods_id = g.goods_id "
            . $where
            . " AND w.goods_id = g.goods_id AND w.enabled = 1 AND w.review_status = 3 GROUP BY goods_id ";
    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);

    while ($row = $GLOBALS['db']->fetchRow($res)) {
        $row['goods_thumb'] = get_image_path(0, $row['goods_thumb']); //处理图片地址

        /*  判断当前商家是否允许"在线客服" start  */
        $shop_information = get_shop_name($row['user_id']); //通过ru_id获取到店铺信息;
        $row['is_IM'] = $shop_information['is_IM']; //平台是否允许商家使用"在线客服";
        //判断当前商家是平台,还是入驻商家 bylu
        if ($row['user_id'] == 0) {
            //判断平台是否开启了IM在线客服
            if ($GLOBALS['db']->getOne("SELECT kf_im_switch FROM " . $GLOBALS['ecs']->table('seller_shopinfo') . "WHERE ru_id = 0", true)) {
                $row['is_dsc'] = true;
            } else {
                $row['is_dsc'] = false;
            }
        } else {
            $row['is_dsc'] = false;
        }
        /* end  */

        $row['goods_url'] = build_uri('wholesale_goods', array('aid' => $row['act_id']), $row['goods_name']);
        $properties = get_goods_properties($row['goods_id']);
        $row['goods_attr'] = $properties['pro'];
        $row['goods_sale'] = get_sale($row['goods_id']);
        $row['goods_extend'] = get_wholesale_extend($row['goods_id']); //获取批发商品标识
        $row['goods_price'] = $row['goods_price'];
        $row['moq'] = $row['moq'];
        $row['volume_number'] = $row['volume_number'];
        $row['volume_price'] = $row['volume_price'];
        $row['rz_shopName'] = get_shop_name($row['user_id'], 1); //店铺名称
        $build_uri = array(
            'urid' 		=> $row['user_id'],
            'append' 	=> $row['rz_shopName']
        );

        $domain_url = get_seller_domain_url($row['user_id'], $build_uri);
        $row['store_url'] = $domain_url['domain_name'];
        $row['shop_price'] = price_format($row['shop_price']);
        $row['market_price'] = price_format($row['market_price']);
        $list[] = $row;
    }
    return $list;
}

/**
 * 商品价格阶梯
 * @param   int     $goods_id     商品ID
 * @return  array
 */
//function get_price_ladder($goods_id)
//{
//    /* 显示商品规格 */
//    $goods_attr_list = array_values(get_goods_attr($goods_id));
//    $sql = "SELECT prices FROM " . $GLOBALS['ecs']->table('wholesale') .
//            "WHERE review_status = 3 AND goods_id = " . $goods_id;
//    $row = $GLOBALS['db']->getRow($sql);
//
//    $arr = array();
//    $_arr = unserialize($row['prices']);
//    if (is_array($_arr))
//    {
//        foreach(unserialize($row['prices']) as $key => $val)
//        {
//            // 显示属性
//            if (!empty($val['attr']))
//            {
//                foreach ($val['attr'] as $attr_key => $attr_val)
//                {
//                    // 获取当前属性 $attr_key 的信息
//                    $goods_attr = array();
//                    foreach ($goods_attr_list as $goods_attr_val)
//                    {
//                        if ($goods_attr_val['attr_id'] == $attr_key)
//                        {
//                            $goods_attr = $goods_attr_val;
//                            break;
//                        }
//                    }
//
//                    // 重写商品规格的价格阶梯信息
//                    if (!empty($goods_attr))
//                    {
//                        $arr[$key]['attr'][] = array(
//                            'attr_id'       => $goods_attr['attr_id'],
//                            'attr_name'     => $goods_attr['attr_name'],
//                            'attr_val'      => (isset($goods_attr['goods_attr_list'][$attr_val]) ? $goods_attr['goods_attr_list'][$attr_val] : ''),
//                            'attr_val_id'   => $attr_val
//                        );
//                    }
//                }
//            }
//            //显示数量与价格
//            foreach($val['qp_list'] as $v)
//            {
//                $compare[] = $v['quantity'];
//            }
//			$min = is_array($compare) ? min($compare) : 1;
//			
//            foreach($val['qp_list'] as $index => $qp)
//            {
//				if($qp['quantity'] == $min){
//					$arr['qp_list']['qp_num'] = $qp['quantity'];	
//					$arr['qp_list']['qp_price'] = price_format($qp['price']);
//				}
//            }
//        }
//    }
//    return $arr;
//}

/**
 * 创建分页信息
 *
 * @access  public
 * @param   string  $app            程序名称，如category
 * @param   string  $cat            分类ID
 * @param   string  $record_count   记录总数
 * @param   string  $size           每页记录数
 * @param   string  $sort           排序类型
 * @param   string  $order          排序顺序
 * @param   string  $page           当前页
 * @return  void
 */
function assign_cat_pager($app, $cat, $record_count, $size, $sort, $order, $page = 1) {
    $sch = array('sort' => $sort,
        'order' => $order,
        'cat' => $cat,
    );

    $page = intval($page);
    if ($page < 1) {
        $page = 1;
    }

    $page_count = $record_count > 0 ? intval(ceil($record_count / $size)) : 1;

    $pager['page'] = $page;
    $pager['size'] = $size;
    $pager['sort'] = $sort;
    $pager['order'] = $order;
    $pager['record_count'] = $record_count;
    $pager['page_count'] = $page_count;

    switch ($app) {
        case 'wholesale_cat':
            $uri_args = array('act' => 'list', 'cid' => $cat, 'sort' => $sort, 'order' => $order);
            break;
    }

    $page_prev = ($page > 1) ? $page - 1 : 1;
    $page_next = ($page < $page_count) ? $page + 1 : $page_count;

    $_pagenum = 10;     // 显示的页码
    $_offset = 2;       // 当前页偏移值
    $_from = $_to = 0;  // 开始页, 结束页
    if ($_pagenum > $page_count) {
        $_from = 1;
        $_to = $page_count;
    } else {
        $_from = $page - $_offset;
        $_to = $_from + $_pagenum - 1;
        if ($_from < 1) {
            $_to = $page + 1 - $_from;
            $_from = 1;
            if ($_to - $_from < $_pagenum) {
                $_to = $_pagenum;
            }
        } elseif ($_to > $page_count) {
            $_from = $page_count - $_pagenum + 1;
            $_to = $page_count;
        }
    }
    if (!empty($url_format)) {
        $pager['page_first'] = ($page - $_offset > 1 && $_pagenum < $page_count) ? $url_format . 1 : '';
        $pager['page_prev'] = ($page > 1) ? $url_format . $page_prev : '';
        $pager['page_next'] = ($page < $page_count) ? $url_format . $page_next : '';
        $pager['page_last'] = ($_to < $page_count) ? $url_format . $page_count : '';
        $pager['page_kbd'] = ($_pagenum < $page_count) ? true : false;
        $pager['page_number'] = array();
        for ($i = $_from; $i <= $_to;  ++$i) {
            $pager['page_number'][$i] = $url_format . $i;
        }
    } else {
        $pager['page_first'] = ($page - $_offset > 1 && $_pagenum < $page_count) ? build_uri($app, $uri_args, '', 1, $keywords) : '';
        $pager['page_prev'] = ($page > 1) ? build_uri($app, $uri_args, '', $page_prev, $keywords) : '';
        $pager['page_next'] = ($page < $page_count) ? build_uri($app, $uri_args, '', $page_next, $keywords) : '';
        $pager['page_last'] = ($_to < $page_count) ? build_uri($app, $uri_args, '', $page_count, $keywords) : '';
        $pager['page_kbd'] = ($_pagenum < $page_count) ? true : false;
        $pager['page_number'] = array();
        for ($i = $_from; $i <= $_to;  ++$i) {
            $pager['page_number'][$i] = build_uri($app, $uri_args, '', $i, $keywords);
        }
    }
    $GLOBALS['smarty']->assign('pager', $pager);
}

function get_wholesale_cat_goodsCount($children, $cat_id, $ext = '') {
	
    $where = " wc.is_show = 1 AND $children AND w.review_status = 3 ";
    // if ($cat_id) {
        // $where .= " AND w.wholesale_cat_id = '$cat_id' ";
    // }
    $leftJoin = '';
    $leftJoin .= " LEFT JOIN " . $GLOBALS['ecs']->table('wholesale_cat') . " as wc on w.wholesale_cat_id = wc.cat_id ";
    $leftJoin .= " LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " as g on g.goods_id = w.goods_id ";
    return $GLOBALS['db']->getOne('SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('wholesale') . " AS w " . $leftJoin . " WHERE $where $ext");
}

?>
