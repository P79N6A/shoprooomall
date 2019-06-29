<?php
//SHOPROOO
namespace app\model;

abstract class shippingModel extends \app\func\common
{
	private $alias_config;

	public function __construct()
	{
		$this->shippingModel();
	}

	public function shippingModel($table = '')
	{
		$this->alias_config = array('shipping' => 'r');

		if ($table) {
			return $this->alias_config[$table];
		}
		else {
			return $this->alias_config;
		}
	}

	public function get_where($val = array(), $alias = '')
	{
		$where = 1;
		$where .= \app\func\base::get_where($val['shipping_id'], $alias . 'shipping_id');
		$where .= \app\func\base::get_where($val['shipping_code'], $alias . 'shipping_code');
		$where .= \app\func\base::get_where($val['shipping_name'], $alias . 'shipping_name');
		return $where;
	}

	public function get_select_list($table, $select, $where, $page_size, $page, $sort_by, $sort_order)
	{
		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE ' . $where;
		$result['record_count'] = $GLOBALS['db']->getOne($sql);

		if ($sort_by) {
			$where .= ' ORDER BY ' . $sort_by . ' ' . $sort_order . ' ';
		}

		$where .= ' LIMIT ' . ($page - 1) * $page_size . (',' . $page_size);
		$sql = 'SELECT ' . $select . ' FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE ' . $where;
		$result['list'] = $GLOBALS['db']->getAll($sql);
		return $result;
	}

	public function get_select_info($table, $select, $where)
	{
		$sql = 'SELECT ' . $select . ' FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE ' . $where . ' LIMIT 1';
		$result = $GLOBALS['db']->getRow($sql);
		return $result;
	}

	public function get_insert($table, $select, $format)
	{
		$shippingLang = \languages\shippingLang::lang_shipping_insert();
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $select, 'INSERT');
		$id = $GLOBALS['db']->insert_id();
		$info = $select;

		if ($id) {
			$info['shipping_id'] = $id;
		}

		$common_data = array('result' => 'success', 'msg' => $shippingLang['msg_success']['success'], 'error' => $shippingLang['msg_success']['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_update($table, $select, $where, $format, $info = array())
	{
		$shippingLang = \languages\shippingLang::lang_shipping_update();
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $select, 'UPDATE', $where);

		if ($info) {
			foreach ($info as $key => $row) {
				if (isset($select[$key])) {
					$info[$key] = $select[$key];
				}
			}
		}
		else {
			$info = $select;
		}

		$common_data = array('result' => 'success', 'msg' => $shippingLang['msg_success']['success'], 'error' => $shippingLang['msg_success']['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_delete($table, $where, $format)
	{
		$shippingLang = \languages\shippingLang::lang_shipping_delete();
		$return = false;

		if (strlen($where) != 1) {
			$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE ' . $where;
			$GLOBALS['db']->query($sql);
			$return = true;
		}
		else {
			$error = \languages\shippingLang::DEL_NULL_PARAM_FAILURE;
		}

		$common_data = array('result' => $return ? 'success' : 'failure', 'msg' => $return ? $shippingLang['msg_success']['success'] : $shippingLang['msg_failure'][$error]['failure'], 'error' => $return ? $shippingLang['msg_success']['error'] : $shippingLang['msg_failure'][$error]['error'], 'format' => $format);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_list_common_data($result, $page_size, $page, $shippingLang, $format)
	{
		$common_data = array('page_size' => $page_size, 'page' => $page, 'result' => empty($result) ? 'failure' : 'success', 'msg' => empty($result) ? $shippingLang['msg_failure']['failure'] : $shippingLang['msg_success']['success'], 'error' => empty($result) ? $shippingLang['msg_failure']['error'] : $shippingLang['msg_success']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		$result = \app\func\common::data_back($result, 1);
		return $result;
	}

	public function get_info_common_data_fs($result, $shippingLang, $format)
	{
		$common_data = array('result' => empty($result) ? 'failure' : 'success', 'msg' => empty($result) ? $shippingLang['msg_failure']['failure'] : $shippingLang['msg_success']['success'], 'error' => empty($result) ? $shippingLang['msg_failure']['error'] : $shippingLang['msg_success']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		$result = \app\func\common::data_back($result);
		return $result;
	}

	public function get_info_common_data_f($shippingLang, $format)
	{
		$result = array();
		$common_data = array('result' => 'failure', 'msg' => $shippingLang['where_failure']['failure'], 'error' => $shippingLang['where_failure']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		$result = \app\func\common::data_back($result);
		return $result;
	}
}

?>
