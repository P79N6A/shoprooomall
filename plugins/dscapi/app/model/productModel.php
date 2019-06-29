<?php
//SHOPROOO
namespace app\model;

abstract class productModel extends \app\func\common
{
	private $alias_config;

	public function __construct()
	{
		$this->productModel();
	}

	public function productModel($table = '')
	{
		$this->alias_config = array('product' => 'p', 'products_warehouse' => 'pw', 'products_area' => 'pa');

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
		$where .= \app\func\base::get_where($val['product_id'], $alias . 'product_id');
		$where .= \app\func\base::get_where($val['goods_id'], $alias . 'goods_id');
		$where .= \app\func\base::get_where($val['product_sn'], $alias . 'product_sn');
		$where .= \app\func\base::get_where($val['bar_code'], $alias . 'bar_code');
		$where .= \app\func\base::get_where($val['warehouse_id'], $alias . 'warehouse_id');
		$where .= \app\func\base::get_where($val['area_id'], $alias . 'area_id');
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
		$productLang = \languages\productLang::lang_product_insert();
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $select, 'INSERT');
		$id = $GLOBALS['db']->insert_id();
		$info = $select;

		if ($id) {
			$info['product_id'] = $id;
		}

		$common_data = array('result' => 'success', 'msg' => $productLang['msg_success']['success'], 'error' => $productLang['msg_success']['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_update($table, $select, $where, $format, $info = array())
	{
		$productLang = \languages\productLang::lang_product_update();
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

		$common_data = array('result' => 'success', 'msg' => $productLang['msg_success']['success'], 'error' => $productLang['msg_success']['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_delete($table, $where, $format)
	{
		$productLang = \languages\productLang::lang_product_delete();
		$return = false;

		if (strlen($where) != 1) {
			$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE ' . $where;
			$GLOBALS['db']->query($sql);
			$return = true;
		}
		else {
			$error = \languages\productLang::DEL_NULL_PARAM_FAILURE;
		}

		$common_data = array('result' => $return ? 'success' : 'failure', 'msg' => $return ? $productLang['msg_success']['success'] : $productLang['msg_failure'][$error]['failure'], 'error' => $return ? $productLang['msg_success']['error'] : $productLang['msg_failure'][$error]['error'], 'format' => $format);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_list_common_data($result, $page_size, $page, $productLang, $format)
	{
		$common_data = array('page_size' => $page_size, 'page' => $page, 'result' => empty($result) ? 'failure' : 'success', 'msg' => empty($result) ? $productLang['msg_failure']['failure'] : $productLang['msg_success']['success'], 'error' => empty($result) ? $productLang['msg_failure']['error'] : $productLang['msg_success']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		$result = \app\func\common::data_back($result, 1);
		return $result;
	}

	public function get_info_common_data_fs($result, $productLang, $format)
	{
		$common_data = array('result' => empty($result) ? 'failure' : 'success', 'msg' => empty($result) ? $productLang['msg_failure']['failure'] : $productLang['msg_success']['success'], 'error' => empty($result) ? $productLang['msg_failure']['error'] : $productLang['msg_success']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		$result = \app\func\common::data_back($result);
		return $result;
	}

	public function get_info_common_data_f($productLang, $format)
	{
		$result = array();
		$common_data = array('result' => 'failure', 'msg' => $productLang['where_failure']['failure'], 'error' => $productLang['where_failure']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		$result = \app\func\common::data_back($result);
		return $result;
	}
}

?>
