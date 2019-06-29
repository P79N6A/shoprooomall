<?php
//SHOPROOO
$shipping_id = isset($_REQUEST['shipping_id']) ? $base->get_intval($_REQUEST['shipping_id']) : -1;
$shipping_name = isset($_REQUEST['shipping_name']) ? $base->get_addslashes($_REQUEST['shipping_name']) : -1;
$shipping_code = isset($_REQUEST['shipping_code']) ? $base->get_addslashes($_REQUEST['shipping_code']) : -1;
$val = array('shipping_id' => $shipping_id, 'shipping_code' => $shipping_code, 'shipping_name' => $shipping_name, 'shipping_select' => $data, 'page_size' => $page_size, 'page' => $page, 'sort_by' => $sort_by, 'sort_order' => $sort_order, 'format' => $format);
$shipping = new \app\controller\shipping($val);

switch ($method) {
case 'dsc.shipping.list.get':
	$table = array('shipping' => 'shipping');
	$result = $shipping->get_shipping_list($table);
	exit($result);
	break;

case 'dsc.shipping.info.get':
	$table = array('shipping' => 'shipping');
	$result = $shipping->get_shipping_details($table);
	exit($result);
	break;

case 'dsc.shipping.insert.post':
	$table = array('shipping' => 'shipping');
	$result = $shipping->get_shipping_insert($table);
	exit($result);
	break;

case 'dsc.shipping.update.post':
	$table = array('shipping' => 'shipping');
	$result = $shipping->get_shipping_update($table);
	exit($result);
	break;

case 'dsc.shipping.del.get':
	$table = array('shipping' => 'shipping');
	$result = $shipping->get_shipping_delete($table);
	exit($result);
	break;

default:
	echo '非法接口连接';
	break;
}

?>
