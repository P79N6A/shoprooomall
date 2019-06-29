<?php
//SHOPROOO 
function auto_delivery_order_logResult($word = '', $type = 'auto')
{
	$word = is_array($word) ? var_export($word, true) : $word;
	$suffix = '_' . substr(md5(__DIR__), 0, 6);
	$fp = fopen(ROOT_PATH . 'temp/' . $type . $suffix . '.log', 'a');
	flock($fp, LOCK_EX);
	fwrite($fp, '执行日期：' . date('Y-m-d H:i:s', time()) . "\n" . $word . "\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}

if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

$cron_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/cron/auto_delivery_order.php';

if (file_exists($cron_lang)) {
	global $_LANG;
	include_once $cron_lang;
}

if (isset($set_modules) && $set_modules == true) {
	$i = isset($modules) ? count($modules) : 0;
	$modules[$i]['code'] = basename(__FILE__, '.php');
	$modules[$i]['desc'] = 'auto_delivery_order_desc';
	$modules[$i]['author'] = 'ECMOBAN TEAM';
	$modules[$i]['website'] = 'http://www.ecmoban.com';
	$modules[$i]['version'] = '1.0.0';
	$modules[$i]['config'] = array(
	array('name' => 'auto_delivery_order_count', 'type' => 'select', 'value' => '5')
	);
	return NULL;
}

$debug = true;
$time = gmtime();
$limit = !empty($cron['auto_delivery_order_count']) ? $cron['auto_delivery_order_count'] : 10;
$open_delivery_time = isset($_CFG['open_delivery_time']) ? $_CFG['open_delivery_time'] : 0;

if ($open_delivery_time == 1) {
	$no_main_order = '';
	$where = ' WHERE 1 AND o.order_status in (1, 4, 5) AND o.pay_status in (2) AND o.shipping_status in (1) ';
	$orderBy = ' ORDER BY o.order_id ';
	$sort = ' ASC ';
	$offset = ' LIMIT 0, ' . $limit . ' ';
	$no_main_order = ' AND (SELECT count(*) FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS oi2 WHERE oi2.main_order_id = o.order_id) = 0 ';
	$sql = ' SELECT o.order_id, o.order_sn, o.order_status, o.shipping_time, o.auto_delivery_time FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o ' . $where . $no_main_order . $orderBy . $sort . $offset;
	$order_list = $GLOBALS['db']->getAll($sql);

	if (!empty($order_list)) {
		foreach ($order_list as $key => $value) {
			$delivery_time = $value['shipping_time'] + 24 * 3600 * $value['auto_delivery_time'];

			if ($delivery_time <= $time) {
				$sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') . ' SET order_status = \'' . $value['order_status'] . '\', shipping_status = \'' . SS_RECEIVED . '\', pay_status = \'' . PS_PAYED . '\' WHERE order_id = ' . $value['order_id'];
				$GLOBALS['db']->query($sql);
				order_action($value['order_sn'], $value['order_status'], SS_RECEIVED, PS_PAYED, $GLOBALS['_LANG']['self_motion_goods'], $GLOBALS['_LANG']['auto_system'], 0, $time);
			}
		}
	}
}

if ($debug == true) {
	auto_delivery_order_logResult('==================== cron log ====================');
	auto_delivery_order_logResult($order_list);
}

?>
