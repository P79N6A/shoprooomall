<?php
//zend SHOPROOO在线更新  禁止倒卖 一经发现停止任何服务
function random($length = 6, $numeric = 0)
{
	PHP_VERSION < '4.2.0' && mt_srand((double) microtime() * 1000000);

	if ($numeric) {
		$hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
	}
	else {
		$hash = '';
		$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
		$max = strlen($chars) - 1;

		for ($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}

	return $hash;
}

function write_file($file_name, $content)
{
	mkdirs('sms/' . date('Ymd'));
	$filename = 'sms/' . date('Ymd') . '/' . $file_name . '.log';
	$Ts = fopen($filename, 'a+');
	fputs($Ts, "\r\n" . $content);
	fclose($Ts);
}

function mkdirs($dir, $mode = 511)
{
	if (is_dir($dir) || @mkdir($dir, $mode)) {
		return true;
	}

	if (!mkdirs(dirname($dir), $mode)) {
		return false;
	}

	return @mkdir($dir, $mode);
}

function read_file($file_name)
{
	$content = '';
	$filename = 'sms/' . date('Ymd') . '/' . $file_name . '.log';

	if (function_exists('file_get_contents')) {
		@$content = file_get_contents($filename);
	}
	else if (@$fp = fopen($filename, 'r')) {
		@$content = fread($fp, filesize($filename));
		@fclose($fp);
	}

	$content = explode("\r\n", $content);
	return end($content);
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
$mobile = $_POST['mobile'] + 0;
$mobile_code = $_POST['mobile_code'] + 0;
$security_code = $_POST['seccode'] + 0;
$username = !empty($_POST['username']) ? trim($_POST['username']) : '';
$sms_value = isset($_POST['sms_value']) ? trim($_POST['sms_value']) : '';

if ($_GET['act'] == 'check') {
	if ($mobile != $_SESSION['sms_mobile'] || $mobile_code != $_SESSION['sms_mobile_code']) {
		exit(json_encode(array('msg' => $_LANG['mobile_verify_code_wrong'])));
	}
	else {
		exit(json_encode(array('code' => '2')));
	}
}

if ($_GET['act'] == 'send') {
	if (empty($mobile)) {
		exit(json_encode(array('msg' => $_LANG['mobile_not_null'])));
	}

	$preg = '/^1[0-9]{10}$/';

	if (!preg_match($preg, $mobile)) {
		exit(json_encode(array('msg' => $_LANG['mobile_wrong_reinput'])));
	}

	if ($_SESSION['sms_security_code'] != $security_code) {
		exit(json_encode(array('msg' => 'you are lost.')));
	}

	if ($_SESSION['sms_mobile']) {
		if (gmtime() - 60 < local_strtotime(read_file($mobile))) {
			exit(json_encode(array('msg' => $_LANG['get_verify_too_much'])));
		}
	}

	$sql = 'select user_id,user_name from ' . $ecs->table('users') . ' where mobile_phone=\'' . $mobile . '\'';
	$row = $db->getRow($sql);

	if ($_GET['flag'] == 'register') {
		if (!empty($row['user_id'])) {
			exit(json_encode(array('msg' => $_LANG['mobile_exist_reinput'])));
		}
	}
	else if ($_GET['flag'] == 'forget') {
		if (empty($row['user_id'])) {
			exit(json_encode(array('msg' => $_LANG['mobile_not_exist_cant_found_pwd'])));
		}
	}

	$mobile_code = random(6, 1);

	if ($GLOBALS['_CFG']['sms_type'] == 0) {
		$message = $_LANG['sms_verify_tpl'][0] . $mobile_code . $_LANG['sms_verify_tpl'][1];
	}
	else {
		$message = array('mobile_code' => $mobile_code, 'user_name' => $username, 'sms_value' => $sms_value);
	}

	include ROOT_PATH . 'includes/cls_sms.php';
	$sms = new sms();
	$sms_error = '';
	$send_result = $sms->send($mobile, $message, '', 1, '', '', $sms_error, $mobile_code);
	write_file($mobile, 'sms/' . date('Y-m-d H:i:s'));
	if (isset($send_result) && $send_result) {
		$_SESSION['sms_mobile'] = $mobile;
		$_SESSION['sms_mobile_code'] = $mobile_code;
		$sms_security_code = rand(1000, 9999);
		$_SESSION['sms_security_code'] = $sms_security_code;
		exit(json_encode(array('code' => 2, 'flag' => htmlspecialchars($_GET['flag']), 'sms_security_code' => $sms_security_code)));
	}
	else {
		if (empty($username)) {
			$error = 1;
			$sms_error = $_LANG['input_user_name'];
		}

		exit(json_encode(array('msg' => $sms_error, 'error' => $error)));
	}
}

?>
