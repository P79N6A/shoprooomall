<?php
//SHOPROOO 禁止倒卖 一经发现停止任何服务
namespace app\controller;

class warehouse extends \app\model\warehouseModel
{
	private $table;
	private $alias;
	private $warehouse_select = array();
	private $select;
	private $region_id = 0;
	private $region_code = 0;
	private $parent_id = 0;
	private $region_name = '';
	private $region_type = 0;
	private $format = 'json';
	private $page_size = 10;
	private $page = 1;
	private $wehre_val;
	private $warehouseLangList;
	private $sort_by;
	private $sort_order;

	public function __construct($where = array())
	{
		$this->warehouse($where);
		$this->wehre_val = array('region_id' => $this->region_id, 'region_code' => $this->region_code, 'parent_id' => $this->parent_id, 'region_type' => $this->region_type, 'region_name' => $this->region_name);
		$this->where = \app\model\warehouseModel::get_where($this->wehre_val);
		$this->select = \app\func\base::get_select_field($this->warehouse_select);
	}

	public function warehouse($where = array())
	{
		$this->region_id = $where['region_id'];
		$this->region_code = $where['region_code'];
		$this->parent_id = $where['parent_id'];
		$this->region_type = $where['region_type'];
		$this->region_name = $where['region_name'];
		$this->warehouse_select = $where['warehouse_select'];
		$this->format = $where['format'];
		$this->page_size = $where['page_size'];
		$this->page = $where['page'];
		$this->sort_by = $where['sort_by'];
		$this->sort_order = $where['sort_order'];
		$this->warehouseLangList = \languages\warehouseLang::lang_warehouse_request();
	}

	public function get_warehouse_list($table)
	{
		$this->table = $table['warehouse'];
		$result = \app\model\warehouseModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\warehouseModel::get_list_common_data($result, $this->page_size, $this->page, $this->warehouseLangList, $this->format);
		return $result;
	}

	public function get_warehouse_info($table)
	{
		$this->table = $table['warehouse'];
		$result = \app\model\warehouseModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\warehouseModel::get_info_common_data_fs($result, $this->warehouseLangList, $this->format);
		}
		else {
			$result = \app\model\warehouseModel::get_info_common_data_f($this->warehouseLangList, $this->format);
		}

		return $result;
	}

	public function get_warehouse_insert($table)
	{
		$this->table = $table['warehouse'];
		$warehouseLang = \languages\warehouseLang::lang_warehouse_insert();
		$select = $this->warehouse_select;

		if ($select) {
			if (!isset($select['region_id'])) {
				if (isset($select['region_code']) && !empty($select['region_code'])) {
					$where = ' region_code = \'' . $select['region_code'] . '\'';
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						if (isset($select['region_name']) && !empty($select['region_name'])) {
							$where = 'region_name = \'' . $select['region_name'] . '\'';
							$info = $this->get_select_info($this->table, '*', $where);

							if (!$info) {
								return \app\model\warehouseModel::get_insert($this->table, $this->warehouse_select, $this->format);
							}
							else {
								$error = \languages\warehouseLang::INSERT_SAME_NAME_FAILURE;
								$info = $select;
							}
						}
						else {
							$error = \languages\warehouseLang::INSERT_NULL_NAME_FAILURE;
							$info = $select;
						}
					}
					else {
						$error = \languages\warehouseLang::INSERT_DATA_EXIST_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\warehouseLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'region_code';
				}
			}
			else {
				$info = $select;
				$error = \languages\warehouseLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'region_id';
			}
		}
		else {
			$error = \languages\warehouseLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\warehouseLang::INSERT_CANNOT_PARAM_FAILURE, \languages\warehouseLang::INSERT_KEY_PARAM_FAILURE))) {
			$warehouseLang['msg_failure'][$error]['failure'] = sprintf($warehouseLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $warehouseLang['msg_failure'][$error]['failure'], 'error' => $warehouseLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_warehouse_update($table)
	{
		$this->table = $table['warehouse'];
		$warehouseLang = \languages\warehouseLang::lang_warehouse_update();
		$select = $this->warehouse_select;

		if ($select) {
			if (!isset($select['region_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\warehouseLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$region_code = 0;
						if (isset($select['region_code']) && !empty($select['region_code'])) {
							$where = 'region_code = \'' . $select['region_code'] . '\' AND region_id <> \'' . $info['region_id'] . '\'';
							$region_code = $this->get_select_info($this->table, '*', $where);
						}

						if ($region_code) {
							$error = \languages\warehouseLang::UPDATE_DATA_EXIST_FAILURE;
							$info = $select;
						}
						else {
							$region_name = '';
							if (isset($select['region_name']) && !empty($select['region_name'])) {
								$where = 'region_name = \'' . $select['region_name'] . '\' AND region_id <> \'' . $info['region_id'] . '\'';
								$region_name = $this->get_select_info($this->table, '*', $where);
							}

							if ($region_name) {
								$error = \languages\warehouseLang::UPDATE_SAME_NAME_FAILURE;
							}
							else {
								return \app\model\warehouseModel::get_update($this->table, $this->warehouse_select, $this->where, $this->format, $info);
							}
						}
					}
				}
				else {
					$error = \languages\warehouseLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\warehouseLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'region_id';
			}
		}
		else {
			$error = \languages\warehouseLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\warehouseLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$warehouseLang['msg_failure'][$error]['failure'] = sprintf($warehouseLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $warehouseLang['msg_failure'][$error]['failure'], 'error' => $warehouseLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_warehouse_delete($table)
	{
		$this->table = $table['warehouse'];
		return \app\model\warehouseModel::get_delete($this->table, $this->where, $this->format);
	}
}

?>
