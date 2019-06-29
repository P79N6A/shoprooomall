<?php
//SHOPROOO商城资源
define('IN_ECS', true);
require dirname(__DIR__) . '/../includes/init.php';
require ROOT_PATH . 'includes/lib_payment.php';
require ROOT_PATH . 'includes/lib_order.php';
$_POST['code'] = $pay_code;
$sql = 'SELECT COUNT(*) FROM ' . $ecs->table('payment') . ' WHERE pay_code = \'' . $pay_code . '\' AND enabled = 1';

if ($db->getOne($sql) == 0) {
	$msg = $_LANG['pay_disabled'];
}
else {
	$plugin_file = dirname(__DIR__) . '/../includes/modules/payment/' . $pay_code . '.php';

	if (file_exists($plugin_file)) {
		include_once $plugin_file;
		$payment = new $pay_code();
		$msg = (@$payment->notify() ? $_LANG['pay_success'] : $_LANG['pay_fail']);
	}
	else {
		$msg = $_LANG['pay_not_exist'];
	}
}

?>
