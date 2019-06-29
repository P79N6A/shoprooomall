<?php
//SHOPROOO
namespace app\controller;

class shipping extends \app\model\shippingModel
{
	private $table;
	private $alias;
	private $shipping_select = array();
	private $select;
	private $shipping_id = 0;
	private $shipping_name = '';
	private $shipping_code = '';
	private $format = 'json';
	private $page_size = 10;
	private $page = 1;
	private $wehre_val;
	private $shippingLangList;
	private $sort_by;
	private $sort_order;

	public function __construct($where = array())
	{
		$this->shipping($where);
		$this->wehre_val = array('shipping_id' => $this->shipping_id, 'shipping_code' => $this->shipping_code, 'shipping_name' => $this->shipping_name);
		$this->where = \app\model\shippingModel::get_where($this->wehre_val);
		$this->select = \app\func\base::get_select_field($this->shipping_select);
	}

	public function shipping($where = array())
	{
		$this->shipping_id = $where['shipping_id'];
		$this->shipping_code = $where['shipping_code'];
		$this->shipping_name = $where['shipping_name'];
		$this->shipping_select = $where['shipping_select'];
		$this->format = $where['format'];
		$this->page_size = $where['page_size'];
		$this->page = $where['page'];
		$this->sort_by = $where['sort_by'];
		$this->sort_order = $where['sort_order'];
		$this->shippingLangList = \languages\shippingLang::lang_shipping_request();
	}

	public function get_shipping_list($table)
	{
		$this->table = $table['shipping'];
		$result = \app\model\shippingModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\shippingModel::get_list_common_data($result, $this->page_size, $this->page, $this->shippingLangList, $this->format);
		return $result;
	}

	public function get_shipping_details($table)
	{
		$this->table = $table['shipping'];
		$result = \app\model\shippingModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\shippingModel::get_info_common_data_fs($result, $this->shippingLangList, $this->format);
		}
		else {
			$result = \app\model\shippingModel::get_info_common_data_f($this->shippingLangList, $this->format);
		}

		return $result;
	}

	public function get_shipping_insert($table)
	{
		$this->table = $table['shipping'];
		$shippingLang = \languages\shippingLang::lang_shipping_insert();
		$select = $this->shipping_select;

		if ($select) {
			if (!isset($select['shipping_id'])) {
				if (isset($select['shipping_code']) && !empty($select['shipping_code'])) {
					$where = 'shipping_code = \'' . $select['shipping_code'] . '\'';
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						if (isset($select['shipping_name']) && !empty($select['shipping_name'])) {
							$where = 'shipping_name = \'' . $select['shipping_name'] . '\'';
							$info = $this->get_select_info($this->table, '*', $where);

							if (!$info) {
								return \app\model\shippingModel::get_insert($this->table, $this->shipping_select, $this->format);
							}
							else {
								$error = \languages\shippingLang::INSERT_SAME_NAME_FAILURE;
								$info = $select;
							}
						}
						else {
							$error = \languages\shippingLang::INSERT_NULL_NAME_FAILURE;
							$info = $select;
						}
					}
					else {
						$error = \languages\shippingLang::INSERT_DATA_EXIST_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\shippingLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'shipping_code';
				}
			}
			else {
				$info = $select;
				$error = \languages\shippingLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'shipping_id';
			}
		}
		else {
			$error = \languages\shippingLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\shippingLang::INSERT_CANNOT_PARAM_FAILURE, \languages\shippingLang::INSERT_KEY_PARAM_FAILURE))) {
			$shippingLang['msg_failure'][$error]['failure'] = sprintf($shippingLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $shippingLang['msg_failure'][$error]['failure'], 'error' => $shippingLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_shipping_update($table)
	{
		$this->table = $table['shipping'];
		$shippingLang = \languages\shippingLang::lang_shipping_update();
		$select = $this->shipping_select;

		if ($select) {
			if (!isset($select['shipping_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\shippingLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$shipping_code = '';

						if (isset($select['shipping_code'])) {
							$where = 'shipping_code = \'' . $select['shipping_code'] . '\' AND shipping_id <> \'' . $info['shipping_id'] . '\'';
							$shipping_code = $this->get_select_info($this->table, '*', $where);
						}

						if ($shipping_code) {
							$error = \languages\shippingLang::UPDATE_DATA_EXIST_FAILURE;
							$info = $select;
						}
						else {
							$shipping_name = '';
							if (isset($select['shipping_name']) && !empty($select['shipping_name'])) {
								$where = 'shipping_name = \'' . $select['shipping_name'] . '\' AND shipping_id <> \'' . $info['shipping_id'] . '\'';
								$shipping_name = $this->get_select_info($this->table, '*', $where);
							}

							if ($shipping_name) {
								$error = \app\model\shippingModel::UPDATE_SAME_NAME_FAILURE;
							}
							else {
								return \app\model\shippingModel::get_update($this->table, $this->shipping_select, $this->where, $this->format, $info);
							}
						}
					}
				}
				else {
					$error = \languages\shippingLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\shippingLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'shipping_id';
			}
		}
		else {
			$error = \languages\shippingLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\shippingLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$shippingLang['msg_failure'][$error]['failure'] = sprintf($shippingLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $shippingLang['msg_failure'][$error]['failure'], 'error' => $shippingLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_shipping_delete($table)
	{
		$this->table = $table['shipping'];
		return \app\model\shippingModel::get_delete($this->table, $this->where, $this->format);
	}
}

?>
