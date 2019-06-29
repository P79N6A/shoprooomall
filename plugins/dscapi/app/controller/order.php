<?php
//SHOPROOO 禁止倒卖 一经发现停止任何服务
namespace app\controller;

class order extends \app\model\orderModel
{
	private $method = '';
	private $table;
	private $alias = '';
	private $order_select = array();
	private $select;
	private $seller_id = 0;
	private $order_id = 0;
	private $order_sn = 0;
	private $start_add_time = 0;
	private $end_add_time = 0;
	private $start_confirm_time = 0;
	private $end_confirm_time = 0;
	private $start_pay_time = 0;
	private $end_pay_time = 0;
	private $start_shipping_time = 0;
	private $end_shipping_time = 0;
	private $start_take_time = 0;
	private $end_take_time = 0;
	private $order_status = 0;
	private $shipping_status = 0;
	private $pay_status = 0;
	private $mobile = 0;
	private $goods_sn = '';
	private $goods_id = 0;
	private $rec_id = 0;
	private $format = 'json';
	private $page_size = 10;
	private $page = 1;
	private $wehre_val;
	private $orderLangList;
	private $sort_by = 'order_id';
	private $sort_order;

	public function __construct($where = array())
	{
		$this->order($where);
		$this->wehre_val = array('method' => $this->method, 'seller_id' => $this->seller_id, 'order_id' => $this->order_id, 'order_sn' => $this->order_sn, 'mobile' => $this->mobile, 'rec_id' => $this->rec_id, 'goods_sn' => $this->goods_sn, 'goods_id' => $this->goods_id, 'start_add_time' => $this->start_add_time, 'end_add_time' => $this->end_add_time, 'start_confirm_time' => $this->start_confirm_time, 'end_confirm_time' => $this->end_confirm_time, 'start_pay_time' => $this->start_pay_time, 'end_pay_time' => $this->end_pay_time, 'start_shipping_time' => $this->start_shipping_time, 'end_shipping_time' => $this->end_shipping_time, 'start_take_time' => $this->start_take_time, 'end_take_time' => $this->end_take_time, 'order_status' => $this->order_status, 'shipping_status' => $this->shipping_status, 'pay_status' => $this->pay_status);
		if (0 < $this->seller_id || 0 < $this->mobile || (-1 < $this->start_take_time || -1 < $this->end_take_time)) {
			$this->alias = 'o.';
		}

		$this->where = \app\model\orderModel::get_where($this->wehre_val, $this->alias);
		$this->select = \app\func\base::get_select_field($this->order_select);
	}

	public function order($where = array())
	{
		$this->method = $where['method'];
		$this->seller_id = $where['seller_id'];
		$this->order_id = $where['order_id'];
		$this->order_sn = $where['order_sn'];
		$this->start_add_time = $where['start_add_time'];
		$this->end_add_time = $where['end_add_time'];
		$this->start_confirm_time = $where['start_confirm_time'];
		$this->end_confirm_time = $where['end_confirm_time'];
		$this->start_pay_time = $where['start_pay_time'];
		$this->end_pay_time = $where['end_pay_time'];
		$this->start_shipping_time = $where['start_shipping_time'];
		$this->end_shipping_time = $where['end_shipping_time'];
		$this->start_take_time = $where['start_take_time'];
		$this->end_take_time = $where['end_take_time'];
		$this->order_status = $where['order_status'];
		$this->shipping_status = $where['shipping_status'];
		$this->pay_status = $where['pay_status'];
		$this->mobile = $where['mobile'];
		$this->rec_id = $where['rec_id'];
		$this->goods_sn = $where['goods_sn'];
		$this->goods_id = $where['goods_id'];
		$this->order_select = $where['order_select'];
		$this->format = $where['format'];
		$this->page_size = $where['page_size'];
		$this->page = $where['page'];
		$this->sort_by = !empty($where['sort_by']) ? $where['sort_by'] : $this->sort_by;
		$this->sort_order = $where['sort_order'];
		$this->orderLangList = \languages\orderLang::lang_order_request();
	}

	public function get_order_list($table)
	{
		$this->table = $table['order'];
		$result = \app\model\orderModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order, $this->alias);
		$result = \app\model\orderModel::get_list_common_data($result, $this->page_size, $this->page, $this->orderLangList, $this->format);
		return $result;
	}

	public function get_order_info($table)
	{
		$this->table = $table['order'];
		$result = \app\model\orderModel::get_select_info($this->table, $this->select, $this->where, $this->alias);

		if (strlen($this->where) != 1) {
			$result = \app\model\orderModel::get_info_common_data_fs($result, $this->orderLangList, $this->format);
		}
		else {
			$result = \app\model\orderModel::get_info_common_data_f($this->orderLangList, $this->format);
		}

		return $result;
	}

	public function get_order_insert($table)
	{
		$this->table = $table['order'];
		$orderLang = \languages\orderLang::lang_order_insert();
		$select = $this->order_select;

		if ($select) {
			if (!isset($select['order_id'])) {
				if (isset($select['order_sn']) && !empty($select['order_sn'])) {
					$where = 'order_sn = \'' . $select['order_sn'] . '\'';
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						return \app\model\orderModel::get_insert($this->table, $this->order_select, $this->format);
					}
					else {
						$error = \languages\orderLang::INSERT_DATA_EXIST_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\orderLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'order_sn';
				}
			}
			else {
				$info = $select;
				$error = \languages\orderLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'order_id';
			}
		}
		else {
			$error = \languages\orderLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\orderLang::INSERT_CANNOT_PARAM_FAILURE, \languages\orderLang::INSERT_KEY_PARAM_FAILURE))) {
			$orderLang['msg_failure'][$error]['failure'] = sprintf($orderLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $orderLang['msg_failure'][$error]['failure'], 'error' => $orderLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_order_update($table)
	{
		$this->table = $table['order'];
		$orderLang = \languages\orderLang::lang_order_update();
		$select = $this->order_select;

		if ($select) {
			if (!isset($select['order_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\orderLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$order_sn = 0;
						if (isset($select['order_sn']) && !empty($select['order_sn'])) {
							$where = 'order_sn = \'' . $select['order_sn'] . '\' AND order_id <> \'' . $info['order_id'] . '\'';
							$order_sn = $this->get_select_info($this->table, '*', $where);
						}

						if ($order_sn) {
							$error = \languages\orderLang::UPDATE_DATA_EXIST_FAILURE;
							$info = $select;
						}
						else {
							return \app\model\orderModel::get_update($this->table, $this->order_select, $this->where, $this->format, $info);
						}
					}
				}
				else {
					$error = \languages\orderLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\orderLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'order_id';
			}
		}
		else {
			$error = \languages\orderLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\orderLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$orderLang['msg_failure'][$error]['failure'] = sprintf($orderLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $orderLang['msg_failure'][$error]['failure'], 'error' => $orderLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_order_delete($table)
	{
		$this->table = $table['order'];
		return \app\model\orderModel::get_delete($this->table, $this->where, $this->format);
	}

	public function get_order_goods_list($table)
	{
		if (isset($this->order_sn) && $this->order_sn != -1) {
			$order_id = $this->get_order_id($table['order']);
			$this->where .= ' AND order_id ' . db_create_in($order_id);
		}

		$this->table = $table['goods'];
		$result = \app\model\orderModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\orderModel::get_list_common_data($result, $this->page_size, $this->page, $this->orderLangList, $this->format);
		return $result;
	}

	public function get_order_goods_info($table)
	{
		if (isset($this->order_sn) && !empty($this->order_sn)) {
			$order_id = $this->get_order_id($table['order']);
			$this->where .= ' AND order_id ' . db_create_in($order_id);
		}

		$this->table = $table['goods'];
		if (isset($this->order_sn) && !empty($this->order_sn)) {
			$result = \app\model\orderModel::get_select_list($this->table, $this->select, $this->where);
		}
		else {
			$result = \app\model\orderModel::get_select_info($this->table, $this->select, $this->where);
		}

		if (strlen($this->where) != 1) {
			$result = \app\model\orderModel::get_info_common_data_fs($result, $this->orderLangList, $this->format);
		}
		else {
			$result = \app\model\orderModel::get_info_common_data_f($this->orderLangList, $this->format);
		}

		return $result;
	}

	public function get_order_goods_insert($table)
	{
		$this->table = $table['goods'];
		$orderLang = \languages\orderLang::lang_order_insert();
		$select = $this->order_select;

		if ($select) {
			if (!isset($select['rec_id'])) {
				if (isset($select['order_id']) && !empty($select['order_id'])) {
					$where = ' order_id = \'' . $select['order_id'] . '\'';
					$order = $this->get_select_info($table['order'], '*', $where);

					if (!$order) {
						$error = \languages\orderLang::INSERT_DATA_EXIST_FAILURE;
						$info = $select;
					}
					else {
						return \app\model\orderModel::get_insert($this->table, $this->order_select, $this->format);
					}
				}
				else {
					$error = \languages\orderLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'order_id';
				}
			}
			else {
				$info = $select;
				$error = \languages\orderLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'rec_id';
			}
		}
		else {
			$error = \languages\orderLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\orderLang::INSERT_CANNOT_PARAM_FAILURE, \languages\orderLang::INSERT_KEY_PARAM_FAILURE))) {
			$orderLang['msg_failure'][$error]['failure'] = sprintf($orderLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $orderLang['msg_failure'][$error]['failure'], 'error' => $orderLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_order_goods_update($table)
	{
		if (isset($this->order_sn) && !empty($this->order_sn)) {
			$order_id = $this->get_order_id($table['order']);
			$this->where .= ' AND order_id ' . db_create_in($order_id);
		}

		$this->table = $table['goods'];
		$orderLang = \languages\orderLang::lang_order_update();
		$select = $this->order_select;

		if ($select) {
			if (!isset($select['rec_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\orderLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						if (isset($select['order_id']) && !empty($select['order_id'])) {
							$error = \languages\orderLang::UPDATE_CANNOT_PARAM_FAILURE;
							$string = 'order_id';
							$info = $select;
						}
						else {
							return \app\model\orderModel::get_update($this->table, $this->order_select, $this->where, $this->format, $info);
						}
					}
				}
				else {
					$error = \languages\orderLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\orderLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'rec_id';
			}
		}
		else {
			$error = \languages\orderLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\orderLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$orderLang['msg_failure'][$error]['failure'] = sprintf($orderLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $orderLang['msg_failure'][$error]['failure'], 'error' => $orderLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_order_goods_delete($table)
	{
		if (isset($this->order_sn) && !empty($this->order_sn)) {
			$order_id = $this->get_order_id($table['order']);
			$this->where .= ' AND order_id ' . db_create_in($order_id);
			$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table($table['order']) . ' WHERE order_id ' . db_create_in($order_id);
			$GLOBALS['db']->query($sql);
		}

		$this->table = $table['goods'];
		return \app\model\orderModel::get_delete($this->table, $this->where, $this->format);
	}

	public function get_order_confirmorder($table)
	{
		$this->table = $table['goods'];
		return \app\model\orderModel::get_confirmorder($this->table, $this->order_select, $this->format);
	}

	private function get_order_id($table)
	{
		$sql = 'SELECT GROUP_CONCAT(order_id) AS order_id FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE order_sn ' . db_create_in($this->order_sn);
		return $GLOBALS['db']->getOne($sql);
	}
}

?>
