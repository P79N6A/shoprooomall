<?php
//SHOPROOO 禁止倒卖 一经发现停止任何服务
namespace app\controller;

class brand extends \app\model\brandModel
{
	private $table;
	private $alias;
	private $brand_select = array();
	private $select;
	private $brand_id = 0;
	private $brand_name = '';
	private $format = 'json';
	private $page_size = 10;
	private $page = 1;
	private $wehre_val;
	private $brandLangList;
	private $sort_by;
	private $sort_order;

	public function __construct($where = array())
	{
		$this->brand($where);
		$this->wehre_val = array('brand_id' => $this->brand_id, 'brand_name' => $this->brand_name);
		$this->where = \app\model\brandModel::get_where($this->wehre_val);
		$this->select = \app\func\base::get_select_field($this->brand_select);
	}

	public function brand($where = array())
	{
		$this->brand_id = $where['brand_id'];
		$this->brand_name = $where['brand_name'];
		$this->brand_select = $where['brand_select'];
		$this->format = $where['format'];
		$this->page_size = $where['page_size'];
		$this->page = $where['page'];
		$this->sort_by = $where['sort_by'];
		$this->sort_order = $where['sort_order'];
		$this->brandLangList = \languages\brandLang::lang_brand_request();
	}

	public function get_brand_list($table)
	{
		$this->table = $table['brand'];
		$result = \app\model\brandModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\brandModel::get_list_common_data($result, $this->page_size, $this->page, $this->brandLangList, $this->format);
		return $result;
	}

	public function get_brand_info($table)
	{
		$this->table = $table['brand'];
		$result = \app\model\brandModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\brandModel::get_info_common_data_fs($result, $this->brandLangList, $this->format);
		}
		else {
			$result = \app\model\brandModel::get_info_common_data_f($this->brandLangList, $this->format);
		}

		return $result;
	}

	public function get_brand_insert($table)
	{
		$this->table = $table['brand'];
		$brandLang = \languages\brandLang::lang_brand_insert();
		$select = $this->brand_select;

		if ($select) {
			if (!isset($select['brand_id'])) {
				if (isset($select['brand_name']) && !empty($select['brand_name'])) {
					$where = ' brand_name = \'' . $select['brand_name'] . '\'';
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						return \app\model\brandModel::get_insert($this->table, $this->brand_select, $this->format);
					}
					else {
						$error = \languages\brandLang::INSERT_DATA_EXIST_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\brandLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'brand_name';
				}
			}
			else {
				$info = $select;
				$error = \languages\brandLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'brand_id';
			}
		}
		else {
			$error = \languages\brandLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\brandLang::INSERT_CANNOT_PARAM_FAILURE, \languages\brandLang::INSERT_KEY_PARAM_FAILURE))) {
			$brandLang['msg_failure'][$error]['failure'] = sprintf($brandLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $brandLang['msg_failure'][$error]['failure'], 'error' => $brandLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_brand_update($table)
	{
		$this->table = $table['brand'];
		$brandLang = \languages\brandLang::lang_brand_insert();
		$select = $this->brand_select;

		if ($select) {
			if (!isset($select['goods_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\brandLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$goods_sn = 0;
						if (isset($select['brand_name']) && !empty($select['brand_name'])) {
							$where = 'brand_name = \'' . $select['brand_name'] . '\' AND brand_id <> \'' . $info['brand_id'] . '\'';
							$goods_sn = $this->get_select_info($this->table, '*', $where);
						}

						if ($goods_sn) {
							$error = \languages\brandLang::UPDATE_DATA_EXIST_FAILURE;
							$info = $select;
						}
						else {
							return \app\model\brandModel::get_update($this->table, $this->brand_select, $this->where, $this->format, $info);
						}
					}
				}
				else {
					$error = \languages\brandLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\brandLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'goods_id';
			}
		}
		else {
			$error = \languages\brandLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\brandLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$brandLang['msg_failure'][$error]['failure'] = sprintf($brandLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $brandLang['msg_failure'][$error]['failure'], 'error' => $brandLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_brand_delete($table)
	{
		$this->table = $table['brand'];
		return \app\model\brandModel::get_delete($this->table, $this->where, $this->format);
	}
}

?>
