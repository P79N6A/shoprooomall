<?php
//SHOPROOO
namespace app\controller;

class connect extends \app\model\connectModel
{
	private $table;
	private $alias;
	private $connect_select = array();
	private $select;
	private $user_id = 0;
	private $open_id = '';
	private $format = 'json';
	private $page_size = 10;
	private $page = 1;
	private $wehre_val;
	private $connectLangList;
	private $sort_by;
	private $sort_order;

	public function __construct($where = array())
	{
		$this->connect($where);
		$this->wehre_val = array('user_id' => $this->user_id, 'open_id' => $this->open_id);
		$this->where = \app\model\connectModel::get_where($this->wehre_val);
		$this->select = \app\func\base::get_select_field($this->connect_select);
	}

	public function connect($where = array())
	{
		$this->user_id = $where['user_id'];
		$this->open_id = $where['open_id'];
		$this->connect_select = $where['connect_select'];
		$this->format = $where['format'];
		$this->page_size = $where['page_size'];
		$this->page = $where['page'];
		$this->sort_by = $where['sort_by'];
		$this->sort_order = $where['sort_order'];
		$this->connectLangList = \languages\connectLang::lang_connect_request();
	}

	public function get_connect_list($table)
	{
		$this->table = $table['connect_user'];
		$result = \app\model\connectModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\connectModel::get_list_common_data($result, $this->page_size, $this->page, $this->connectLangList, $this->format);
		return $result;
	}

	public function get_connect_details($table)
	{
		$this->table = $table['connect_user'];
		$result = \app\model\connectModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\connectModel::get_info_common_data_fs($result, $this->connectLangList, $this->format);
		}
		else {
			$result = \app\model\connectModel::get_info_common_data_f($this->connectLangList, $this->format);
		}

		return $result;
	}

	public function get_connect_insert($table)
	{
		$this->table = $table['connect_user'];
		$connectLang = \languages\connectLang::lang_connect_insert();
		$select = $this->connect_select;

		if ($select) {
			if (!isset($select['id'])) {
				if (isset($select['open_id']) && !empty($select['open_id'])) {
					$where = 'open_id = \'' . $select['open_id'] . '\'';
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						return \app\model\connectModel::get_insert($this->table, $this->connect_select, $this->format);
					}
					else {
						$error = \languages\connectLang::INSERT_DATA_EXIST_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\connectLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'open_id';
				}
			}
			else {
				$info = $select;
				$error = \languages\connectLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'id';
			}
		}
		else {
			$error = connectgLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\connectLang::INSERT_CANNOT_PARAM_FAILURE, \languages\connectLang::INSERT_KEY_PARAM_FAILURE))) {
			$connectLang['msg_failure'][$error]['failure'] = sprintf($connectLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $connectLang['msg_failure'][$error]['failure'], 'error' => $connectLang['msg_failure'][$error]['error'], 'format' => $this->format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_connect_update($table)
	{
		$this->table = $table['connect_user'];
		$connectLang = \languages\connectLang::lang_connect_update();
		$select = $this->connect_select;

		if ($select) {
			if (!isset($select['id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\connectLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$openid = '';

						if (isset($select['open_id'])) {
							$where = 'open_id = \'' . $select['open_id'] . '\' AND user_id <> \'' . $info['user_id'] . '\'';
							$openid = $this->get_select_info($this->table, '*', $where);
						}

						if ($openid) {
							$error = \languages\connectLang::UPDATE_DATA_EXIST_FAILURE;
							$info = $select;
						}
						else {
							return \app\model\connectModel::get_update($this->table, $this->connect_select, $this->where, $this->format, $info);
						}
					}
				}
				else {
					$error = \languages\connectLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\connectLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'id';
			}
		}
		else {
			$error = \languages\connectLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\connectLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$connectLang['msg_failure'][$error]['failure'] = sprintf($connectLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $connectLang['msg_failure'][$error]['failure'], 'error' => $connectLang['msg_failure'][$error]['error'], 'format' => $this->format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_connect_delete($table)
	{
		$this->table = $table['connect_user'];
		return \app\model\connectModel::get_delete($this->table, $this->where, $this->format);
	}
}

?>
