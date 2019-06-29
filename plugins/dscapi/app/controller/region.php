<?php
//SHOPROOO
namespace app\controller;

class region extends \app\model\regionModel
{
	private $table;
	private $alias;
	private $region_select = array();
	private $select;
	private $region_id = 0;
	private $parent_id = 0;
	private $region_name = '';
	private $region_type = 0;
	private $format = 'json';
	private $page_size = 10;
	private $page = 1;
	private $wehre_val;
	private $regionLangList;
	private $sort_by;
	private $sort_order;

	public function __construct($where = array())
	{
		$this->region($where);
		$this->wehre_val = array('region_id' => $this->region_id, 'parent_id' => $this->parent_id, 'region_type' => $this->region_type, 'region_name' => $this->region_name);
		$this->where = \app\model\regionModel::get_where($this->wehre_val);
		$this->select = \app\func\base::get_select_field($this->region_select);
	}

	public function region($where = array())
	{
		$this->region_id = $where['region_id'];
		$this->parent_id = $where['parent_id'];
		$this->region_type = $where['region_type'];
		$this->region_name = $where['region_name'];
		$this->region_select = $where['region_select'];
		$this->format = $where['format'];
		$this->page_size = $where['page_size'];
		$this->page = $where['page'];
		$this->sort_by = $where['sort_by'];
		$this->sort_order = $where['sort_order'];
		$this->regionLangList = \languages\regionLang::lang_region_request();
	}

	public function get_region_list($table)
	{
		$this->table = $table['region'];
		$result = \app\model\regionModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\regionModel::get_list_common_data($result, $this->page_size, $this->page, $this->regionLangList, $this->format);
		return $result;
	}

	public function get_region_info($table)
	{
		$this->table = $table['region'];
		$result = \app\model\regionModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\regionModel::get_info_common_data_fs($result, $this->regionLangList, $this->format);
		}
		else {
			$result = \app\model\regionModel::get_info_common_data_f($this->regionLangList, $this->format);
		}

		return $result;
	}

	public function get_region_insert($table)
	{
		$this->table = $table['region'];
		$regionLang = \languages\regionLang::lang_region_insert();
		$select = $this->region_select;

		if ($select) {
			if (!isset($select['region_id'])) {
				if (isset($select['region_name']) && !empty($select['region_name'])) {
					if (isset($select['region_name']) && !empty($select['region_name'])) {
						$where = 'region_name = \'' . $select['region_name'] . '\'';
						$info = $this->get_select_info($this->table, '*', $where);

						if (!$info) {
							return \app\model\regionModel::get_insert($this->table, $this->region_select, $this->format);
						}
						else {
							$error = \languages\regionLang::INSERT_SAME_NAME_FAILURE;
							$info = $select;
						}
					}
					else {
						$error = \languages\regionLang::INSERT_NULL_NAME_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\regionLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'region_name';
				}
			}
			else {
				$info = $select;
				$error = \languages\regionLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'region_id';
			}
		}
		else {
			$error = \languages\regionLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\regionLang::INSERT_CANNOT_PARAM_FAILURE, \languages\regionLang::INSERT_KEY_PARAM_FAILURE))) {
			$regionLang['msg_failure'][$error]['failure'] = sprintf($regionLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $regionLang['msg_failure'][$error]['failure'], 'error' => $regionLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_region_update($table)
	{
		$this->table = $table['region'];
		$regionLang = \languages\regionLang::lang_region_update();
		$select = $this->region_select;

		if ($select) {
			if (!isset($select['region_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\regionLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$region_name = 0;
						if (isset($select['region_name']) && !empty($select['region_name'])) {
							$where = 'region_name = \'' . $select['region_name'] . '\' AND region_id <> \'' . $info['region_id'] . '\'';
							$region_name = $this->get_select_info($this->table, '*', $where);
						}

						if ($region_name) {
							$error = \languages\regionLang::UPDATE_DATA_EXIST_FAILURE;
							$info = $select;
						}
						else {
							return \app\model\regionModel::get_update($this->table, $this->region_select, $this->where, $this->format, $info);
						}
					}
				}
				else {
					$error = \languages\regionLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\regionLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'region_id';
			}
		}
		else {
			$error = \languages\regionLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\regionLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$regionLang['msg_failure'][$error]['failure'] = sprintf($regionLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $regionLang['msg_failure'][$error]['failure'], 'error' => $regionLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_region_delete($table)
	{
		$this->table = $table['region'];
		return \app\model\regionModel::get_delete($this->table, $this->where, $this->format);
	}
}

?>
