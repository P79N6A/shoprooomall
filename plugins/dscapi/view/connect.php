<?php
//SHOPROOO
$user_id = isset($_REQUEST['user_id']) ? $base->get_intval($_REQUEST['user_id']) : -1;
$open_id = isset($_REQUEST['open_id']) ? $base->get_addslashes($_REQUEST['open_id']) : -1;
$val = array('user_id' => $user_id, 'open_id' => $open_id, 'connect_select' => $data, 'page_size' => $page_size, 'page' => $page, 'sort_by' => $sort_by, 'sort_order' => $sort_order, 'format' => $format);
$connect = new \app\controller\connect($val);

switch ($method) {
case 'dsc.connect.list.get':
	$table = array('connect_user' => 'connect_user');
	$result = $connect->get_connect_list($table);
	exit($result);
	break;

case 'dsc.connect.info.get':
	$table = array('connect_user' => 'connect_user');
	$result = $connect->get_connect_details($table);
	exit($result);
	break;

case 'dsc.connect.insert.post':
	$table = array('connect_user' => 'connect_user');
	$result = $connect->get_connect_insert($table);
	exit($result);
	break;

case 'dsc.connect.update.post':
	$table = array('connect_user' => 'connect_user');
	$result = $connect->get_connect_update($table);
	exit($result);
	break;

case 'dsc.connect.del.get':
	$table = array('connect_user' => 'connect_user');
	$result = $connect->get_connect_delete($table);
	exit($result);
	break;

default:
	echo '非法接口连接';
	break;
}

?>
