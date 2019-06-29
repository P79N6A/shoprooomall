<?php
//SHOPROOO
namespace app\controller;

class user extends \app\model\userModel
{
	private $table;
	private $alias;
	private $user_select = array();
	private $select;
	private $user_id = 0;
	private $user_name = 0;
	private $mobile = '';
	private $rank_id = '';
	private $rank_name = '';
	private $address_id = '';
	private $format = 'json';
	private $page_size = 10;
	private $page = 1;
	private $wehre_val;
	private $userLangList;
	private $sort_by;
	private $sort_order;

	public function __construct($where = array())
	{
		$this->user($where);
		$this->wehre_val = array('user_id' => $this->user_id, 'user_name' => $this->user_name, 'mobile' => $this->mobile, 'rank_id' => $this->rank_id, 'rank_name' => $this->rank_name, 'address_id' => $this->address_id);
		$this->where = \app\model\userModel::get_where($this->wehre_val);
		$this->select = \app\func\base::get_select_field($this->user_select);
	}

	public function user($where = array())
	{
		$this->user_id = $where['user_id'];
		$this->user_name = $where['user_name'];
		$this->mobile = $where['mobile'];
		$this->rank_id = $where['rank_id'];
		$this->rank_name = $where['rank_name'];
		$this->address_id = $where['address_id'];
		$this->user_select = $where['user_select'];
		$this->format = $where['format'];
		$this->page_size = $where['page_size'];
		$this->page = $where['page'];
		$this->sort_by = $where['sort_by'];
		$this->sort_order = $where['sort_order'];
		$this->userLangList = \languages\userLang::lang_user_request();
	}

	public function get_user_list($table)
	{
		$this->table = $table['users'];
		$result = \app\model\userModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\userModel::get_list_common_data($result, $this->page_size, $this->page, $this->userLangList, $this->format);
		return $result;
	}

	public function get_user_info($table)
	{
		$this->table = $table['users'];
		$result = \app\model\userModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\userModel::get_info_common_data_fs($result, $this->userLangList, $this->format);
		}
		else {
			$result = \app\model\userModel::get_info_common_data_f($this->userLangList, $this->format);
		}

		return $result;
	}

	public function get_user_insert($table)
	{
		$this->table = $table['users'];
		$userLang = \languages\userLang::lang_user_insert();
		$select = $this->user_select;

		if ($select) {
			if (!isset($select['user_id'])) {
				if (isset($select['mobile_phone']) && !empty($select['mobile_phone'])) {
					$where = 'mobile_phone = \'' . $select['mobile_phone'] . '\'';
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						if (isset($select['user_name']) && !empty($select['user_name'])) {
							$where = 'user_name = \'' . $select['user_name'] . '\'';
							$info = $this->get_select_info($this->table, '*', $where);

							if (!$info) {
								return \app\model\userModel::get_insert($this->table, $this->user_select, $this->format);
							}
							else {
								$error = \languages\userLang::INSERT_SAME_NAME_FAILURE;
								$info = $select;
							}
						}
						else {
							$error = \languages\userLang::INSERT_NULL_NAME_FAILURE;
							$info = $select;
						}
					}
					else {
						$error = \languages\userLang::INSERT_DATA_EXIST_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\userLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'mobile_phone';
				}
			}
			else {
				$info = $select;
				$error = \languages\userLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'user_id';
			}
		}
		else {
			$error = \languages\userLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\userLang::INSERT_CANNOT_PARAM_FAILURE, \languages\userLang::INSERT_KEY_PARAM_FAILURE))) {
			$userLang['msg_failure'][$error]['failure'] = sprintf($userLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $userLang['msg_failure'][$error]['failure'], 'error' => $userLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_user_update($table)
	{
		$this->table = $table['users'];
		$userLang = \languages\userLang::lang_user_update();
		$select = $this->user_select;

		if ($select) {
			if (!isset($select['user_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\userLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$mobile_phone = 0;
						if (isset($select['mobile_phone']) && !empty($select['mobile_phone'])) {
							$where = 'mobile_phone = \'' . $select['mobile_phone'] . '\' AND user_id <> \'' . $info['user_id'] . '\'';
							$mobile_phone = $this->get_select_info($this->table, '*', $where);
						}

						if ($mobile_phone) {
							$error = \languages\userLang::UPDATE_DATA_EXIST_FAILURE;
							$info = $select;
						}
						else {
							$user_name = '';
							if (isset($select['user_name']) && !empty($select['user_name'])) {
								$where = 'user_name = \'' . $select['user_name'] . '\' AND user_id <> \'' . $info['user_id'] . '\'';
								$user_name = $this->get_select_info($this->table, '*', $where);
							}

							if ($user_name) {
								$error = \languages\userLang::UPDATE_SAME_NAME_FAILURE;
							}
							else {
								return \app\model\userModel::get_update($this->table, $this->user_select, $this->where, $this->format, $info);
							}
						}
					}
				}
				else {
					$error = \languages\userLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\userLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'user_id';
			}
		}
		else {
			$error = \languages\userLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\userLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$userLang['msg_failure'][$error]['failure'] = sprintf($userLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $userLang['msg_failure'][$error]['failure'], 'error' => $userLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_user_delete($table)
	{
		$this->table = $table['users'];
		return \app\model\userModel::get_delete($this->table, $this->where, $this->format);
	}

	public function get_user_rank_list($table)
	{
		$this->table = $table['rank'];
		$result = \app\model\userModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\userModel::get_list_common_data($result, $this->page_size, $this->page, $this->userLangList, $this->format);
		return $result;
	}

	public function get_user_rank_info($table)
	{
		$this->table = $table['rank'];
		$result = \app\model\userModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\userModel::get_info_common_data_fs($result, $this->userLangList, $this->format);
		}
		else {
			$result = \app\model\userModel::get_info_common_data_f($this->userLangList, $this->format);
		}

		return $result;
	}

	public function get_user_rank_insert($table)
	{
		$this->table = $table['rank'];
		$userLang = \languages\userLang::lang_user_insert();
		$select = $this->user_select;

		if ($select) {
			if (!isset($select['rank_id'])) {
				if (isset($select['rank_name']) && !empty($select['rank_name'])) {
					$where = 'rank_name = \'' . $select['rank_name'] . '\'';
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						return \app\model\userModel::get_insert($this->table, $this->user_select, $this->format);
					}
					else {
						$error = \languages\userLang::INSERT_SAME_NAME_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\userLang::INSERT_NULL_NAME_FAILURE;
					$info = $select;
				}
			}
			else {
				$info = $select;
				$error = \languages\userLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'rank_id';
			}
		}
		else {
			$error = \languages\userLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\userLang::INSERT_CANNOT_PARAM_FAILURE))) {
			$userLang['msg_failure'][$error]['failure'] = sprintf($userLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $userLang['msg_failure'][$error]['failure'], 'error' => $userLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_user_rank_update($table)
	{
		$this->table = $table['rank'];
		$userLang = \languages\userLang::lang_user_update();
		$select = $this->user_select;

		if ($select) {
			if (!isset($select['rank_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\userLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$rank_name = '';
						if (isset($select['rank_name']) && !empty($select['rank_name'])) {
							$where = 'rank_name = \'' . $select['rank_name'] . '\' AND rank_id <> \'' . $info['rank_id'] . '\'';
							$rank_name = $this->get_select_info($this->table, '*', $where);
						}

						if ($rank_name) {
							$error = \languages\userLang::UPDATE_SAME_NAME_FAILURE;
						}
						else {
							return \app\model\userModel::get_update($this->table, $this->user_select, $this->where, $this->format, $info);
						}
					}
				}
				else {
					$error = \languages\userLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\userLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'rank_id';
			}
		}
		else {
			$error = \languages\userLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\userLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$userLang['msg_failure'][$error]['failure'] = sprintf($userLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $userLang['msg_failure'][$error]['failure'], 'error' => $userLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_user_rank_delete($table)
	{
		$this->table = $table['rank'];
		return \app\model\userModel::get_delete($this->table, $this->where, $this->format);
	}

	public function get_user_address_list($table)
	{
		$this->table = $table['address'];
		$result = \app\model\userModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\userModel::get_list_common_data($result, $this->page_size, $this->page, $this->userLangList, $this->format);
		return $result;
	}

	public function get_user_address_info($table)
	{
		$this->table = $table['address'];
		$result = \app\model\userModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\userModel::get_info_common_data_fs($result, $this->userLangList, $this->format);
		}
		else {
			$result = \app\model\userModel::get_info_common_data_f($this->userLangList, $this->format);
		}

		return $result;
	}

	public function get_user_address_insert($table)
	{
		$this->table = $table['address'];
		$userLang = \languages\userLang::lang_user_insert();
		$select = $this->user_select;

		if ($select) {
			if (!isset($select['address_id'])) {
				if (isset($select['consignee']) && !empty($select['consignee']) && (isset($select['user_id']) && !empty($select['user_id']))) {
					return \app\model\userModel::get_insert($this->table, $this->user_select, $this->format);
				}
				else {
					$error = \languages\userLang::INSERT_NULL_NAME_FAILURE;
					$info = $select;
				}
			}
			else {
				$info = $select;
				$error = \languages\userLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'address_id';
			}
		}
		else {
			$error = \languages\userLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\userLang::INSERT_CANNOT_PARAM_FAILURE))) {
			$userLang['msg_failure'][$error]['failure'] = sprintf($userLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $userLang['msg_failure'][$error]['failure'], 'error' => $userLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_user_address_update($table)
	{
		$this->table = $table['address'];
		$userLang = \languages\userLang::lang_user_update();
		$select = $this->user_select;

		if ($select) {
			if (!isset($select['address_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\userLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						return \app\model\userModel::get_update($this->table, $this->user_select, $this->where, $this->format, $info);
					}
				}
				else {
					$error = \languages\userLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\userLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'address_id';
			}
		}
		else {
			$error = \languages\userLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\userLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$userLang['msg_failure'][$error]['failure'] = sprintf($userLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $userLang['msg_failure'][$error]['failure'], 'error' => $userLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_user_address_delete($table)
	{
		$this->table = $table['address'];
		return \app\model\userModel::get_delete($this->table, $this->where, $this->format);
	}
}

?>
