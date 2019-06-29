<?php

/**
 * shoprooo 批发搜索程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: search.php 17217 2011-01-19 06:29:08Z liubo $
 */
define('IN_ECS', true);

if (!function_exists("htmlspecialchars_decode")) {

    function htmlspecialchars_decode($string, $quote_style = ENT_COMPAT) {
        return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
    }

}

if (empty($_GET['encode'])) {
    $string = array_merge($_GET, $_POST);
    if (get_magic_quotes_gpc()) {
        require(dirname(__FILE__) . '/includes/lib_base.php');
        $string = stripslashes_deep($string);
    }
    $string['search_encode_time'] = time();
    $string = str_replace('+', '%2b', base64_encode(serialize($string)));

    header("Location: wholesale_search.php?encode=$string\n");

    exit;
} else {
    $string = base64_decode(trim($_GET['encode']));
    if ($string !== false) {
        $string = unserialize($string);
        if ($string !== false) {
            /* 用户在重定向的情况下当作一次访问 */
            if (!empty($string['search_encode_time'])) {
                if (time() > $string['search_encode_time'] + 2) {
                    define('INGORE_VISIT_STATS', true);
                }
            } else {
                define('INGORE_VISIT_STATS', true);
            }
        } else {
            $string = array();
        }
    } else {
        $string = array();
    }
}

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . '/includes/lib_area.php');
require(ROOT_PATH . '/includes/lib_wholesale.php');

if($GLOBALS['_CFG']['wholesale_user_rank'] == 0){
    $is_seller = get_is_seller();
    if($is_seller == 0){
        ecs_header("Location: " .$ecs->url(). "\n");
    }
}

$_REQUEST = array_merge($_REQUEST, addslashes_deep($string));

$_REQUEST['act'] = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : '';

/* 过滤 XSS 攻击和SQL注入 */
get_request_filter();

/* ------------------------------------------------------ */
//-- 搜索结果
/* ------------------------------------------------------ */

$_REQUEST['keywords'] = !empty($_REQUEST['keywords']) ? strip_tags(htmlspecialchars(trim($_REQUEST['keywords']))) : '';
$_REQUEST['keywords'] = !empty($_REQUEST['keywords'])   ? addslashes_deep(trim($_REQUEST['keywords'])) : '';
$_REQUEST['category'] = !empty($_REQUEST['category']) ? intval($_REQUEST['category']) : 0;
$_REQUEST['goods_type'] = !empty($_REQUEST['goods_type']) ? intval($_REQUEST['goods_type']) : 0;
$action = '';
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'form') {
    /* 要显示高级搜索栏 */
    $adv_value['keywords'] = htmlspecialchars(stripcslashes($_REQUEST['keywords']));
    $adv_value['category'] = $_REQUEST['category'];

    $attributes = get_seachable_attributes($_REQUEST['goods_type']);

    /* 将提交数据重新赋值 */
    foreach ($attributes['attr'] AS $key => $val) {
        if (!empty($_REQUEST['attr'][$val['id']])) {
            if ($val['type'] == 2) {
                $attributes['attr'][$key]['value']['from'] = !empty($_REQUEST['attr'][$val['id']]['from']) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]['from']))) : '';
                $attributes['attr'][$key]['value']['to'] = !empty($_REQUEST['attr'][$val['id']]['to']) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]['to']))) : '';
            } else {
                $attributes['attr'][$key]['value'] = !empty($_REQUEST['attr'][$val['id']]) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]))) : '';
            }
        }
    }

	
    $smarty->assign('adv_val', $adv_value);
    $smarty->assign('goods_type_list', $attributes['cate']);
    $smarty->assign('goods_attributes', $attributes['attr']);
    $smarty->assign('goods_type_selected', $_REQUEST['goods_type']);
    $smarty->assign('cat_list', cat_list(0, $adv_value['category'], true, 2, false));
    $smarty->assign('action', 'form');
    $smarty->assign('use_storage', $_CFG['use_storage']);

    $action = 'form';
}

/* 初始化搜索条件 */
$keywords = '';
$tag_where = '';
if (!empty($_REQUEST['keywords'])) {
    
    $scws_res = scws($_REQUEST['keywords']); //这里可以把关键词分词：诺基亚，耳机
    $arr = explode(',', $scws_res);

    $goods_ids = array();
    foreach ($arr AS $key => $val) {
        if ($key > 0 && $key < count($arr) && count($arr) > 1) {
            $keywords .= $operator;
        }
        $val = mysql_like_quote(trim($val));
        $keywords .= " AND w.goods_name LIKE '%$val%' OR w.goods_price LIKE '%$val%' ";

        $sql = 'SELECT DISTINCT goods_id FROM ' . $ecs->table('tag') . " WHERE tag_words LIKE '%$val%' ";
        $res = $db->query($sql);
        while ($row = $db->FetchRow($res)) {
            $goods_ids[] = $row['goods_id'];
        }

        $db->autoReplace($ecs->table('keywords'), array('date' => local_date('Y-m-d'),
            'searchengine' => 'ecshop', 'keyword' => addslashes(str_replace('%', '', $val)), 'count' => 1), array('count' => 1));
    }

    $goods_ids = array_unique($goods_ids);
    $tag_where = implode(',', $goods_ids);
    if (!empty($tag_where)) {
        $tag_where = 'OR g.goods_id ' . db_create_in($tag_where);
    }
}

$category = !empty($_REQUEST['category']) ? intval($_REQUEST['category']) : 0;
$categories = ($category > 0) ? ' AND ' . get_children($category) : '';

/* 排序、显示方式以及类型 */
$default_display_type = $_CFG['show_order_type'] == '0' ? 'list' : ($_CFG['show_order_type'] == '1' ? 'grid' : 'text');
$default_sort_order_method = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';
$default_sort_order_type = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update');

$sort = (isset($_REQUEST['sort']) && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update'))) ? trim($_REQUEST['sort']) : $default_sort_order_type;
$order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC'))) ? trim($_REQUEST['order']) : $default_sort_order_method;
$display = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), array('list', 'grid', 'text'))) ? trim($_REQUEST['display']) : (isset($_SESSION['display_search']) ? $_SESSION['display_search'] : $default_display_type);

$_SESSION['display_search'] = $display;

$page = !empty($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;
$size = !empty($_CFG['page_size']) && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 10;

$intromode = '';    //方式，用于决定搜索结果页标题图片

if (empty($ur_here)) {
    $ur_here = $_LANG['search_goods'];
}

/* ------------------------------------------------------ */
//-- 属性检索
/* ------------------------------------------------------ */
$attr_in = '';
$attr_num = 0;
$attr_url = '';
$attr_arg = array();

if (!empty($_REQUEST['attr'])) {
    $sql = "SELECT goods_id, COUNT(*) AS num FROM " . $ecs->table("goods_attr") . " WHERE 0 ";
    foreach ($_REQUEST['attr'] AS $key => $val) {
        if (is_not_null($val) && is_numeric($key)) {
            $attr_num++;
            $sql .= " OR (1 ";

            if (is_array($val)) {
                $sql .= " AND attr_id = '$key'";

                if (!empty($val['from'])) {
                    $sql .= is_numeric($val['from']) ? " AND attr_value >= " . floatval($val['from']) : " AND attr_value >= '$val[from]'";
                    $attr_arg["attr[$key][from]"] = $val['from'];
                    $attr_url .= "&amp;attr[$key][from]=$val[from]";
                }

                if (!empty($val['to'])) {
                    $sql .= is_numeric($val['to']) ? " AND attr_value <= " . floatval($val['to']) : " AND attr_value <= '$val[to]'";
                    $attr_arg["attr[$key][to]"] = $val['to'];
                    $attr_url .= "&amp;attr[$key][to]=$val[to]";
                }
            } else {
                /* 处理选购中心过来的链接 */
                $sql .= isset($_REQUEST['pickout']) ? " AND attr_id = '$key' AND attr_value = '" . $val . "' " : " AND attr_id = '$key' AND attr_value LIKE '%" . mysql_like_quote($val) . "%' ";
                $attr_url .= "&amp;attr[$key]=$val";
                $attr_arg["attr[$key]"] = $val;
            }

            $sql .= ')';
        }
    }

    /* 如果检索条件都是无效的，就不用检索 */
    if ($attr_num > 0) {
        $sql .= " GROUP BY goods_id HAVING num = '$attr_num'";

        $row = $db->getCol($sql);
        if (count($row)) {
            $attr_in = " AND " . db_create_in($row, 'g.goods_id');
        } else {
            $attr_in = " AND 0 ";
        }
    }
} elseif (isset($_REQUEST['pickout'])) {
    /* 从选购中心进入的链接 */
    $sql = "SELECT DISTINCT(goods_id) FROM " . $ecs->table('goods_attr');
    $col = $db->getCol($sql);
    //如果商店没有设置商品属性,那么此检索条件是无效的
    if (!empty($col)) {
        $attr_in = " AND " . db_create_in($col, 'g.goods_id');
    }
}

/* 获得符合条件的商品总数 */
$sql = "SELECT COUNT(*) FROM " . $ecs->table('wholesale') . " AS w " .
        "WHERE w.enabled = 1 AND w.review_status = 3 $attr_in " .
        $categories . $keywords . $tag_where;
$count = $db->getOne($sql);

$max_page = ($count > 0) ? ceil($count / $size) : 1;
if ($page > $max_page) {
    $page = $max_page;
}

/* 查询商品 */
$sql = "SELECT w.*, g.goods_thumb, g.goods_img, MIN(wvp.volume_number) AS volume_number, wvp.volume_price " .
        "FROM " . $ecs->table('wholesale') . " AS w "
        . " LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " AS g ON w.goods_id = g.goods_id "
        . " LEFT JOIN " . $GLOBALS['ecs']->table('wholesale_volume_price') . " AS wvp ON wvp.goods_id = g.goods_id "
        . "WHERE w.enabled = 1 AND w.review_status = 3 $attr_in " .
        $categories . $keywords . $tag_where .
        " GROUP BY w.goods_id ORDER BY w.goods_id DESC ";
$res = $db->SelectLimit($sql, $size, ($page - 1) * $size);
$arr = array();
while ($row = $db->FetchRow($res)) {
    /* 处理商品水印图片 */
    $watermark_img = '';

    if ($watermark_img != '') {
        $arr[$row['goods_id']]['watermark_img'] = $watermark_img;
    }

    $arr[$row['goods_id']]['goods_id'] = $row['goods_id'];
    if ($display == 'grid') {
        $arr[$row['goods_id']]['goods_name'] = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
    } else {
        $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
    }

    $arr[$row['goods_id']]['goods_extend'] = get_wholesale_extend($row['goods_id']);
    $arr[$row]['goods_price'] = $row['goods_price'];
    $arr[$row]['goods_sale'] = get_sale($row['goods_id']);
    $arr[$row]['moq'] = $row['moq'];
    $arr[$row]['volume_number'] = $row['volume_number'];
    $arr[$row]['volume_price'] = $row['volume_price'];
    $arr[$row['goods_id']]['rz_shopName'] = get_shop_name($row['user_id'], 1); //店铺名称
    $build_uri = array(
        'urid' => $row['user_id'],
        'append' => $row['rz_shopName']
    );

    $domain_url = get_seller_domain_url($row['user_id'], $build_uri);
    $arr[$row['goods_id']]['store_url'] = $domain_url['domain_name'];

    $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
    $arr[$row['goods_id']]['goods_price'] = $row['goods_price'];
    $arr[$row['goods_id']]['moq'] = $row['moq'];
    $arr[$row['goods_id']]['volume_number'] = $row['volume_number'];
    $arr[$row['goods_id']]['volume_price'] = $row['volume_price'];
    $arr[$row['goods_id']]['goods_sale'] = get_sale($row['goods_id']);
    $arr[$row['goods_id']]['price_model'] = $row['price_model'];

    $arr[$row['goods_id']]['promote_price'] = ($promote_price > 0) ? price_format($promote_price) : '';
    $arr[$row['goods_id']]['goods_thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
    $arr[$row['goods_id']]['goods_img'] = get_image_path($row['goods_id'], $row['goods_img']);
    $arr[$row['goods_id']]['url'] = build_uri('wholesale_goods', array('aid' => $row['act_id']), $row['goods_name']);
}

if ($display == 'grid') {
    if (count($arr) % 2 != 0) {
        $arr[] = array();
    }
}
//var_dump($arr);

$get_wholsale_navigator = get_wholsale_navigator();
$smarty->assign('get_wholsale_navigator', $get_wholsale_navigator);

$wholesale_cat = get_wholesale_child_cat();
$smarty->assign('wholesale_cat', $wholesale_cat);
$smarty->assign('goods_list', $arr);
$smarty->assign('category', $category);
$smarty->assign('keywords', htmlspecialchars(stripslashes($_REQUEST['keywords'])));
$smarty->assign('search_keywords', stripslashes(htmlspecialchars_decode($_REQUEST['keywords'])));

/* 分页 */
$url_format = "wholesale_search.php?category=$category&amp;keywords=" . urlencode(stripslashes($_REQUEST['keywords'])) . "&amp;action=" . $action . "&amp;goods_type=" . $_REQUEST['goods_type'] . "&amp;sc_ds=" . $_REQUEST['sc_ds'];
if (!empty($intromode)) {
    $url_format .= "&amp;intro=" . $intromode;
}
if (isset($_REQUEST['pickout'])) {
    $url_format .= '&amp;pickout=1';
}
$url_format .= "&amp;sort=$sort";

$url_format .= "$attr_url&amp;order=$order&amp;page=";
$pager['search'] = array(
    'keywords' => stripslashes(urlencode($_REQUEST['keywords'])),
    'category' => $category,
    'sort' => $sort,
    'order' => $order,
    'action' => $action,
    'goods_type' => $_REQUEST['goods_type'],
);
$pager['search'] = array_merge($pager['search'], $attr_arg);

$pager = get_pager('wholesale_search.php', $pager['search'], $count, $page, $size);
$pager['display'] = $display;

$smarty->assign('url_format', $url_format);
$smarty->assign('pager', $pager);


assign_template();
assign_dynamic('search');
$position = assign_ur_here(0, $ur_here . ($_REQUEST['keywords'] ? '_' . $_REQUEST['keywords'] : ''));
$smarty->assign('page_title', $position['title']);    // 页面标题
$smarty->assign('ur_here', $position['ur_here']);  // 当前位置
$smarty->assign('intromode', $intromode);
$smarty->assign('categories', get_categories_tree()); // 分类树
$smarty->assign('helps', get_shop_help());      // 网店帮助
//$smarty->assign('top_goods', get_top10());           // 销售排行
$smarty->assign('promotion_info', get_promotion_info());

$smarty->display('wholesale_search.dwt');


/* ------------------------------------------------------ */
//-- PRIVATE FUNCTION
/* ------------------------------------------------------ */

/**
 *
 *
 * @access public
 * @param
 *
 * @return void
 */
function is_not_null($value) {
    if (is_array($value)) {
        return (!empty($value['from'])) || (!empty($value['to']));
    } else {
        return !empty($value);
    }
}

/**
 * 获得可以检索的属性
 *
 * @access  public
 * @params  integer $cat_id
 * @return  void
 */
function get_seachable_attributes($cat_id = 0) {
    $attributes = array(
        'cate' => array(),
        'attr' => array()
    );

    /* 获得可用的商品类型 */
    $sql = "SELECT t.cat_id, cat_name FROM " . $GLOBALS['ecs']->table('goods_type') . " AS t, " .
            $GLOBALS['ecs']->table('attribute') . " AS a" .
            " WHERE t.cat_id = a.cat_id AND t.enabled = 1 AND a.attr_index > 0 ";
    $cat = $GLOBALS['db']->getAll($sql);

    /* 获取可以检索的属性 */
    if (!empty($cat)) {
        foreach ($cat AS $val) {
            $attributes['cate'][$val['cat_id']] = $val['cat_name'];
        }
        $where = $cat_id > 0 ? ' AND a.cat_id = ' . $cat_id : " AND a.cat_id = " . $cat[0]['cat_id'];

        $sql = 'SELECT attr_id, attr_name, attr_input_type, attr_type, attr_values, attr_index, sort_order ' .
                ' FROM ' . $GLOBALS['ecs']->table('attribute') . ' AS a ' .
                ' WHERE a.attr_index > 0 ' . $where .
                ' ORDER BY cat_id, sort_order ASC';
        $res = $GLOBALS['db']->query($sql);

        while ($row = $GLOBALS['db']->FetchRow($res)) {
            if ($row['attr_index'] == 1 && $row['attr_input_type'] == 1) {
                $row['attr_values'] = str_replace("\r", '', $row['attr_values']);
                $options = explode("\n", $row['attr_values']);

                $attr_value = array();
                foreach ($options AS $opt) {
                    $attr_value[$opt] = $opt;
                }
                $attributes['attr'][] = array(
                    'id' => $row['attr_id'],
                    'attr' => $row['attr_name'],
                    'options' => $attr_value,
                    'type' => 3
                );
            } else {
                $attributes['attr'][] = array(
                    'id' => $row['attr_id'],
                    'attr' => $row['attr_name'],
                    'type' => $row['attr_index']
                );
            }
        }
    }

    return $attributes;
}

?>