<?php
//SHOPROOO
namespace app\controller;

class category extends \app\model\categoryModel
{
	private $table;
	private $alias;
	private $category_select = array();
	private $select;
	private $seller_id = 0;
	private $cat_id = 0;
	private $cat_name = '';
	private $parent_id = 0;
	private $region_type = 0;
	private $format = 'json';
	private $page_size = 10;
	private $page = 1;
	private $wehre_val;
	private $categoryLangList;
	private $sort_by;
	private $sort_order;

	public function __construct($where = array())
	{
		$this->category($where);
		$this->wehre_val = array('seller_id' => $this->seller_id, 'cat_id' => $this->cat_id, 'parent_id' => $this->parent_id, 'cat_name' => $this->cat_name);
		$this->where = \app\model\categoryModel::get_where($this->wehre_val);
		$this->select = \app\func\base::get_select_field($this->category_select);
	}

	public function category($where = array())
	{
		$this->seller_id = $where['seller_id'];
		$this->cat_id = $where['cat_id'];
		$this->parent_id = $where['parent_id'];
		$this->cat_name = $where['cat_name'];
		$this->category_select = $where['category_select'];
		$this->format = $where['format'];
		$this->page_size = $where['page_size'];
		$this->page = $where['page'];
		$this->sort_by = $where['sort_by'];
		$this->sort_order = $where['sort_order'];
		$this->categoryLangList = \languages\categoryLang::lang_category_request();
	}

	public function get_category_list($table)
	{
		$this->table = $table['category'];
		$result = \app\model\categoryModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\categoryModel::get_list_common_data($result, $this->page_size, $this->page, $this->categoryLangList, $this->format);
		return $result;
	}

	public function get_category_info($table)
	{
		$this->table = $table['category'];
		$result = \app\model\categoryModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\categoryModel::get_info_common_data_fs($result, $this->categoryLangList, $this->format);
		}
		else {
			$result = \app\model\categoryModel::get_info_common_data_f($this->categoryLangList, $this->format);
		}

		return $result;
	}

	public function get_category_insert($table)
	{
		$this->table = $table['category'];
		$categoryLang = \languages\categoryLang::lang_category_insert();
		$select = $this->category_select;

		if ($select) {
			if (!isset($select['cat_id'])) {
				if (isset($select['cat_name']) && !empty($select['cat_name'])) {
					if (isset($select['parent_id']) && !empty($select['parent_id'])) {
						$parent_id = $select['parent_id'];
					}
					else {
						$parent_id = 0;
					}

					$where = 'cat_name = \'' . $select['cat_name'] . ('\' AND parent_id = \'' . $parent_id . '\'');
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						return \app\model\categoryModel::get_insert($this->table, $this->category_select, $this->format);
					}
					else {
						$error = \languages\categoryLang::INSERT_SAME_NAME_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\categoryLang::INSERT_NULL_NAME_FAILURE;
					$info = $select;
				}
			}
			else {
				$info = $select;
				$error = \languages\categoryLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'cat_id';
			}
		}
		else {
			$error = \languages\categoryLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\categoryLang::INSERT_CANNOT_PARAM_FAILURE))) {
			$categoryLang['msg_failure'][$error]['failure'] = sprintf($categoryLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $categoryLang['msg_failure'][$error]['failure'], 'error' => $categoryLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_category_update($table)
	{
		$this->table = $table['category'];
		$categoryLang = \languages\categoryLang::lang_category_update();
		$select = $this->category_select;

		if ($select) {
			if (!isset($select['cat_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\categoryLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$cat_name = '';
						if (isset($select['cat_name']) && !empty($select['cat_name'])) {
							$where = '1';
							if (isset($select['parent_id']) && !empty($select['parent_id'])) {
								$where .= ' AND parent_id = \'' . $select['parent_id'] . '\'';
							}

							$where .= ' AND cat_name = \'' . $select['cat_name'] . '\' AND cat_id <> \'' . $info['cat_id'] . '\'';
							$cat_name = $this->get_select_info($this->table, '*', $where);
						}

						if ($cat_name) {
							$error = \languages\categoryLang::UPDATE_SAME_NAME_FAILURE;
						}
						else {
							return \app\model\categoryModel::get_update($this->table, $this->category_select, $this->where, $this->format, $info);
						}
					}
				}
				else {
					$error = \languages\categoryLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\categoryLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'cat_id';
			}
		}
		else {
			$error = \languages\categoryLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\categoryLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$categoryLang['msg_failure'][$error]['failure'] = sprintf($categoryLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $categoryLang['msg_failure'][$error]['failure'], 'error' => $categoryLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_category_delete($table)
	{
		$this->table = $table['category'];
		return \app\model\categoryModel::get_delete($this->table, $this->where, $this->format);
	}

	public function get_category_seller_list($table)
	{
		$this->table = $table['seller'];
		$result = \app\model\categoryModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\categoryModel::get_list_common_data($result, $this->page_size, $this->page, $this->categoryLangList, $this->format);
		return $result;
	}

	public function get_category_seller_info($table)
	{
		$this->table = $table['seller'];
		$result = \app\model\categoryModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\categoryModel::get_info_common_data_fs($result, $this->categoryLangList, $this->format);
		}
		else {
			$result = \app\model\categoryModel::get_info_common_data_f($this->categoryLangList, $this->format);
		}

		return $result;
	}

	public function get_category_seller_insert($table)
	{
		$this->table = $table['seller'];
		$categoryLang = \languages\categoryLang::lang_category_insert();
		$select = $this->category_select;

		if ($select) {
			if (!isset($select['cat_id'])) {
				if (isset($select['cat_name']) && !empty($select['cat_name'])) {
					if (isset($select['parent_id']) && !empty($select['parent_id'])) {
						$parent_id = $select['parent_id'];
					}
					else {
						$parent_id = 0;
					}

					$where = 'cat_name = \'' . $select['cat_name'] . ('\' AND parent_id = \'' . $parent_id . '\'');
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						return \app\model\categoryModel::get_insert($this->table, $this->category_select, $this->format);
					}
					else {
						$error = \languages\categoryLang::INSERT_SAME_NAME_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\categoryLang::INSERT_NULL_NAME_FAILURE;
					$info = $select;
				}
			}
			else {
				$info = $select;
				$error = \languages\categoryLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'cat_id';
			}
		}
		else {
			$error = \languages\categoryLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\categoryLang::INSERT_CANNOT_PARAM_FAILURE))) {
			$categoryLang['msg_failure'][$error]['failure'] = sprintf($categoryLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $categoryLang['msg_failure'][$error]['failure'], 'error' => $categoryLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_category_seller_update($table)
	{
		$this->table = $table['seller'];
		$categoryLang = \languages\categoryLang::lang_category_update();
		$select = $this->category_select;

		if ($select) {
			if (!isset($select['cat_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\categoryLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$cat_name = '';
						if (isset($select['cat_name']) && !empty($select['cat_name'])) {
							$where = '1';
							if (isset($select['parent_id']) && !empty($select['parent_id'])) {
								$where .= ' AND parent_id = \'' . $select['parent_id'] . '\'';
							}

							$where .= ' AND cat_name = \'' . $select['cat_name'] . '\' AND cat_id <> \'' . $info['cat_id'] . '\' AND user_id = \'' . $info['user_id'] . '\'';
							$cat_name = $this->get_select_info($this->table, '*', $where);
						}

						if ($cat_name) {
							$error = \languages\categoryLang::UPDATE_SAME_NAME_FAILURE;
						}
						else {
							return \app\model\categoryModel::get_update($this->table, $this->category_select, $this->where, $this->format, $info);
						}
					}
				}
				else {
					$error = \languages\categoryLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\categoryLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'cat_id';
			}
		}
		else {
			$error = \languages\categoryLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\categoryLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$categoryLang['msg_failure'][$error]['failure'] = sprintf($categoryLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $categoryLang['msg_failure'][$error]['failure'], 'error' => $categoryLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_category_seller_delete($table)
	{
		$this->table = $table['seller'];
		return \app\model\categoryModel::get_delete($this->table, $this->where, $this->format);
	}
}

?>
