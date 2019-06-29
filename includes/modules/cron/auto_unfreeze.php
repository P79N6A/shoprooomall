<?php
 //SHOPROOO
function auto_unfreeze_logResult($word = '', $type = 'auto_unfreeze')
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

$cron_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/cron/auto_unfreeze.php';

if (file_exists($cron_lang)) {
	global $_LANG;
	include_once $cron_lang;
}

if (isset($set_modules) && $set_modules == true) {
	$i = isset($modules) ? count($modules) : 0;
	$modules[$i]['code'] = basename(__FILE__, '.php');
	$modules[$i]['desc'] = 'auto_unfreeze_desc';
	$modules[$i]['author'] = 'ECMOBAN TEAM';
	$modules[$i]['website'] = 'http://www.ecmoban.com';
	$modules[$i]['version'] = '1.0.0';
	$modules[$i]['config'] = array(
	array('name' => 'auto_unfreeze_count', 'type' => 'select', 'value' => '5')
	);
	return NULL;
}

$debug = true;
$log = false;
$time = gmtime();
$limit = !empty($cron['auto_unfreeze_count']) ? $cron['auto_unfreeze_count'] : 10;
$no_main_order = '';
$where = ' WHERE 1 AND frozen_data > 0 AND frozen_time > 0 ';
$orderBy = ' ORDER BY frozen_time ASC, frozen_data ASC ';
$offset = ' LIMIT 0, ' . $limit . ' ';
$sql = ' SELECT id AS bill_id, seller_id, should_amount, chargeoff_status, frozen_money, frozen_data, frozen_time FROM ' . $GLOBALS['ecs']->table('seller_commission_bill') . $where . $orderBy . $offset;
$bill_list = $GLOBALS['db']->getAll($sql);

if (!empty($bill_list)) {
	foreach ($bill_list as $key => $value) {
		$final_unfreeze_time = $value['frozen_time'] + 24 * 3600 * $value['frozen_data'];
		$detail = array();
		$detail['chargeoff_status'] = 2;

		if (!$value['chargeoff_time']) {
			$detail['chargeoff_time'] = $time;
		}

		$detail['frozen_money'] = 0;
		$detail['settleaccounts_time'] = $time;
		$detail['should_amount'] = $value['should_amount'] + $value['frozen_money'];
		$sql = 'UPDATE ' . $ecs->table('seller_shopinfo') . ' SET seller_money = seller_money + \'' . $value['frozen_money'] . '\' WHERE ru_id = \'' . $value['seller_id'] . '\'';
		$db->query($sql);
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('seller_commission_bill'), $detail, 'UPDATE', 'id = \'' . $value['bill_id'] . '\'');
		$change_desc = sprintf($_LANG['seller_bill_unfreeze'], $_SESSION['admin_name']);
		$user_account_log = array('user_id' => $value['seller_id'], 'user_money' => $frozen_money, 'change_time' => $time, 'change_desc' => $change_desc, 'change_type' => 2);
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('merchants_account_log'), $user_account_log, 'INSERT');
		$log = true;
	}
}

if ($debug == true && $log == true) {
	auto_unfreeze_logResult('==================== cron log ====================');
	auto_unfreeze_logResult($bill_list);
}

?>
