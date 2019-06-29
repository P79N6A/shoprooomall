<?php
//SHOPROOO 禁止倒卖 一经发现停止任何服务
namespace app\controller;

class goods extends \app\model\goodsModel
{
	private $method = '';
	private $table;
	private $alias = '';
	private $goods_select = array();
	private $select;
	private $seller_id = 0;
	private $brand_id = 0;
	private $cat_id = 0;
	private $user_cat = 0;
	private $goods_id = 0;
	private $goods_sn = '';
	private $is_delete = 0;
	private $bar_code = '';
	private $w_id = 0;
	private $a_id = 0;
	private $region_id = 0;
	private $province_name = '';
	private $city_name = '';
	private $region_sn = '';
	private $img_id = 0;
	private $attr_id = 0;
	private $goods_attr_id = 0;
	private $tid = '';
	private $seller_type = 0;
	private $format = 'json';
	private $page_size = 10;
	private $page = 1;
	private $wehre_val;
	private $goodsLangList;
	private $sort_by;
	private $sort_order;

	public function __construct($where = array())
	{
		$this->goods($where);
		$this->wehre_val = array('seller_id' => $this->seller_id, 'brand_id' => $this->brand_id, 'cat_id' => $this->cat_id, 'user_cat' => $this->user_cat, 'goods_id' => $this->goods_id, 'goods_sn' => $this->goods_sn, 'is_delete' => $this->is_delete, 'bar_code' => $this->bar_code, 'w_id' => $this->w_id, 'a_id' => $this->a_id, 'region_id' => $this->region_id, 'province_name' => $this->province_name, 'city_name' => $this->city_name, 'region_sn' => $this->region_sn, 'img_id' => $this->img_id, 'attr_id' => $this->attr_id, 'goods_attr_id' => $this->goods_attr_id, 'tid' => $this->tid, 'seller_type' => $this->seller_type);

		if ($this->method == 'dsc.goods.area.info.get') {
			$this->alias = 'wag.';
		}

		$this->where = \app\model\goodsModel::get_where($this->wehre_val, $this->alias);
		$this->select = \app\func\base::get_select_field($this->goods_select);
	}

	public function goods($where = array())
	{
		$this->seller_type = $where['seller_type'];
		$this->table = $where['table'];
		$this->seller_id = $where['seller_id'];
		$this->brand_id = $where['brand_id'];
		$this->cat_id = $where['cat_id'];
		$this->user_cat = $where['user_cat'];
		$this->goods_id = $where['goods_id'];
		$this->goods_sn = $where['goods_sn'];
		$this->is_delete = $where['is_delete'];
		$this->bar_code = $where['bar_code'];
		$this->w_id = $where['w_id'];
		$this->a_id = $where['a_id'];
		$this->region_id = $where['region_id'];
		$this->province_name = $where['province_name'];
		$this->city_name = $where['city_name'];
		$this->region_sn = $where['region_sn'];
		$this->img_id = $where['img_id'];
		$this->attr_id = $where['attr_id'];
		$this->goods_attr_id = $where['goods_attr_id'];
		$this->tid = $where['tid'];
		$this->goods_select = $where['goods_select'];
		$this->method = $where['method'];
		$this->format = $where['format'];
		$this->page_size = $where['page_size'];
		$this->page = $where['page'];
		$this->sort_by = $where['sort_by'];
		$this->sort_order = $where['sort_order'];
		$this->goodsLangList = \languages\goodsLang::lang_goods_request();
	}

	public function get_goods_list($table)
	{
		$this->table = $table['goods'];
		$result = \app\model\goodsModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
		return $result;
	}

	public function get_goods_info($table)
	{
		$this->table = $table['goods'];
		$result = \app\model\goodsModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
		}
		else {
			$result = \app\model\goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
		}

		return $result;
	}

	public function get_goods_insert($table)
	{
		$this->table = $table['goods'];
		$goodsLang = \languages\goodsLang::lang_goods_insert();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['goods_id'])) {
				if (isset($select['goods_sn']) && !empty($select['goods_sn'])) {
					if (isset($select['user_id']) && !empty($select['user_id'])) {
						$user_id = $select['user_id'];
					}
					else {
						$user_id = 0;
					}

					$where = ' goods_sn = \'' . $select['goods_sn'] . ('\' AND user_id = \'' . $user_id . '\'');
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						if (isset($select['goods_name']) && !empty($select['goods_name'])) {
							$where = 'goods_name = \'' . $select['goods_name'] . '\'';
							$info = $this->get_select_info($this->table, '*', $where);

							if (!$info) {
								return \app\model\goodsModel::get_insert($this->table, $this->goods_select, $this->format);
							}
							else {
								$error = \languages\goodsLang::INSERT_SAME_NAME_FAILURE;
								$info = $select;
							}
						}
						else {
							$error = \languages\goodsLang::INSERT_NULL_NAME_FAILURE;
							$info = $select;
						}
					}
					else {
						$error = \languages\goodsLang::INSERT_DATA_EXIST_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\goodsLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'goods_sn';
				}
			}
			else {
				$info = $select;
				$error = \languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'goods_id';
			}
		}
		else {
			$error = \languages\goodsLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE, \languages\goodsLang::INSERT_KEY_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_update($table)
	{
		$this->table = $table['goods'];
		$goodsLang = \languages\goodsLang::lang_goods_update();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['goods_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\goodsLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$goods_sn = 0;
						if (isset($select['goods_sn']) && !empty($select['goods_sn'])) {
							$where = 'goods_sn = \'' . $select['goods_sn'] . '\' AND goods_id <> \'' . $info['goods_id'] . '\' AND user_id = \'' . $info['user_id'] . '\'';
							$goods_sn = $this->get_select_info($this->table, '*', $where);
						}

						if ($goods_sn) {
							$error = \languages\goodsLang::UPDATE_DATA_EXIST_FAILURE;
							$info = $select;
						}
						else {
							$goods_name = '';
							if (isset($select['goods_name']) && !empty($select['goods_name'])) {
								$where = 'goods_name = \'' . $select['goods_name'] . '\' AND goods_id <> \'' . $info['goods_id'] . '\'';
								$goods_name = $this->get_select_info($this->table, '*', $where);
							}

							if ($goods_name) {
								$error = \languages\goodsLang::UPDATE_SAME_NAME_FAILURE;
							}
							else {
								return \app\model\goodsModel::get_update($this->table, $this->goods_select, $this->where, $this->format, $info);
							}
						}
					}
				}
				else {
					$error = \languages\goodsLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'goods_id';
			}
		}
		else {
			$error = \languages\goodsLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_delete($table)
	{
		$this->table = $table['goods'];
		return \app\model\goodsModel::get_delete($this->table, $this->where, $this->format);
	}

	public function get_goods_warehouse_list($table)
	{
		$this->table = $table['warehouse'];
		$result = \app\model\goodsModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
		return $result;
	}

	public function get_goods_warehouse_info($table)
	{
		$this->table = $table['warehouse'];
		$result = \app\model\goodsModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
		}
		else {
			$result = \app\model\goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
		}

		return $result;
	}

	public function get_goods_warehouse_insert($table)
	{
		$this->table = $table['warehouse'];
		$goodsLang = \languages\goodsLang::lang_goods_insert();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['w_id'])) {
				if (isset($select['region_sn']) && !empty($select['region_sn'])) {
					if (isset($select['user_id']) && !empty($select['user_id'])) {
						$user_id = $select['user_id'];
					}
					else {
						$user_id = 0;
					}

					$where = 'region_sn = \'' . $select['region_sn'] . ('\' AND user_id = \'' . $user_id . '\'');
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						return \app\model\goodsModel::get_insert($this->table, $this->goods_select, $this->format);
					}
					else {
						$error = \languages\goodsLang::INSERT_SAME_NAME_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\goodsLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'region_sn';
				}
			}
			else {
				$info = $select;
				$error = \languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'w_id';
			}
		}
		else {
			$error = \languages\goodsLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE, \languages\goodsLang::INSERT_KEY_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_warehouse_update($table)
	{
		$this->table = $table['warehouse'];
		$goodsLang = \languages\goodsLang::lang_goods_update();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['w_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\goodsLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$region_sn = 0;
						if (isset($select['region_sn']) && !empty($select['region_sn'])) {
							$where = 'region_sn = \'' . $select['region_sn'] . '\' AND w_id <> \'' . $info['w_id'] . '\' AND user_id = \'' . $info['user_id'] . '\'';
							$region_sn = $this->get_select_info($this->table, '*', $where);
						}

						if ($region_sn) {
							$error = \languages\goodsLang::UPDATE_DATA_EXIST_FAILURE;
							$info = $select;
						}
						else {
							return \app\model\goodsModel::get_update($this->table, $this->goods_select, $this->where, $this->format, $info);
						}
					}
				}
				else {
					$error = \languages\goodsLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'w_id';
			}
		}
		else {
			$error = \languages\goodsLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_warehouse_delete($table)
	{
		$this->table = $table['warehouse'];
		return \app\model\goodsModel::get_delete($this->table, $this->where, $this->format);
	}

	public function get_goods_area_list($table)
	{
		$this->table = $table['area'];
		$result = \app\model\goodsModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
		return $result;
	}

	public function get_goods_area_info($table)
	{
		$this->table = $table['area'];
		$result = \app\model\goodsModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
		}
		else {
			$result = \app\model\goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
		}

		return $result;
	}

	public function get_goods_area_insert($table)
	{
		$this->table = $table['area'];
		$goodsLang = \languages\goodsLang::lang_goods_insert();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['a_id'])) {
				if (isset($select['region_sn']) && !empty($select['region_sn'])) {
					if (isset($select['user_id']) && !empty($select['user_id'])) {
						$user_id = $select['user_id'];
					}
					else {
						$user_id = 0;
					}

					$where = 'region_sn = \'' . $select['region_sn'] . ('\' AND user_id = \'' . $user_id . '\'');
					$is_region = 1;
					if (isset($select['province_name']) && $select['province_name']) {
						$sql = 'SELECT region_id FROM ' . $GLOBALS['ecs']->table('region_warehouse') . ' WHERE region_type = 1 AND region_name = \'' . $select['province_name'] . '\'';
						$region_id = $GLOBALS['db']->getOne($sql);

						if ($region_id) {
							$is_region = 1;
							$select['region_id'] = $region_id;
						}
						else {
							$is_region = 0;
						}
					}
					else {
						if (isset($select['city_name']) && $select['city_name']) {
							$sql = 'SELECT region_id FROM ' . $GLOBALS['ecs']->table('region_warehouse') . ' WHERE region_type = 2 AND region_name = \'' . $select['city_name'] . '\'';
							$region_id = $GLOBALS['db']->getOne($sql);

							if ($region_id) {
								$is_region = 1;
								$select['region_id'] = $region_id;
							}
							else {
								$is_region = 0;
							}
						}
					}

					if ($is_region == 1) {
						$info = $this->get_select_info($this->table, '*', $where);

						if (!$info) {
							return \app\model\goodsModel::get_insert($this->table, $select, $this->format);
						}
						else {
							$error = \languages\goodsLang::INSERT_SAME_NAME_FAILURE;
							$info = $select;
						}
					}
					else {
						$error = \languages\goodsLang::INSERT_DATA_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\goodsLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'region_sn';
				}
			}
			else {
				$info = $select;
				$error = \languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'a_id';
			}
		}
		else {
			$error = \languages\goodsLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE, \languages\goodsLang::INSERT_KEY_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_area_update($table)
	{
		$this->table = $table['area'];
		$goodsLang = \languages\goodsLang::lang_goods_update();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['a_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\goodsLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$region_sn = 0;
						if (isset($select['region_sn']) && !empty($select['region_sn'])) {
							$where = 'region_sn = \'' . $select['region_sn'] . '\' AND a_id <> \'' . $info['a_id'] . '\' AND user_id = \'' . $info['user_id'] . '\'';
							$region_sn = $this->get_select_info($this->table, '*', $where);
						}

						$is_region = 1;
						if (isset($select['province_name']) && $select['province_name']) {
							$sql = 'SELECT region_id FROM ' . $GLOBALS['ecs']->table('region_warehouse') . ' WHERE region_type = 1 AND province_name = \'' . $select['province_name'] . '\'';
							$region_id = $GLOBALS['db']->getOne($sql);

							if ($region_id) {
								$is_region = 1;
								$select['region_id'] = $region_id;
								$info['province_name'] = $select['province_name'];
							}
							else {
								$is_region = 0;
							}
						}
						else {
							if (isset($select['city_name']) && $select['city_name']) {
								$sql = 'SELECT region_id FROM ' . $GLOBALS['ecs']->table('region_warehouse') . ' WHERE region_type = 2 AND region_name = \'' . $select['city_name'] . '\'';
								$region_id = $GLOBALS['db']->getOne($sql);

								if ($region_id) {
									$is_region = 1;
									$select['region_id'] = $region_id;
									$info['city_name'] = $select['city_name'];
								}
								else {
									$is_region = 0;
								}
							}
						}

						if ($is_region == 1) {
							if ($region_sn) {
								$error = \languages\goodsLang::UPDATE_DATA_EXIST_FAILURE;
								$info = $select;
							}
							else {
								return \app\model\goodsModel::get_update($this->table, $select, $this->where, $this->format, $info);
							}
						}
						else {
							$error = \languages\goodsLang::UPDATE_DATA_FAILURE;
							$info = $select;
						}
					}
				}
				else {
					$error = \languages\goodsLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'a_id';
			}
		}
		else {
			$error = \languages\goodsLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_area_delete($table)
	{
		$this->table = $table['area'];
		return \app\model\goodsModel::get_delete($this->table, $this->where, $this->format);
	}

	public function get_goods_gallery_list($table)
	{
		$this->table = $table['gallery'];
		$result = \app\model\goodsModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
		return $result;
	}

	public function get_goods_gallery_info($table)
	{
		$this->table = $table['gallery'];
		$result = \app\model\goodsModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
		}
		else {
			$result = \app\model\goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
		}

		return $result;
	}

	public function get_goods_gallery_insert($table)
	{
		$this->table = $table['gallery'];
		$goodsLang = \languages\goodsLang::lang_goods_insert();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['img_id'])) {
				if (isset($select['goods_id']) && !empty($select['goods_id'])) {
					return \app\model\goodsModel::get_insert($this->table, $this->goods_select, $this->format);
				}
				else {
					$error = \languages\goodsLang::INSERT_NULL_NAME_FAILURE;
					$info = $select;
				}
			}
			else {
				$info = $select;
				$error = \languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'img_id';
			}
		}
		else {
			$error = \languages\goodsLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_gallery_update($table)
	{
		$this->table = $table['gallery'];
		$goodsLang = \languages\goodsLang::lang_goods_update();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['img_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\goodsLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						return \app\model\goodsModel::get_update($this->table, $this->goods_select, $this->where, $this->format, $info);
					}
				}
				else {
					$error = \languages\goodsLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'img_id';
			}
		}
		else {
			$error = \languages\goodsLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_gallery_delete($table)
	{
		$this->table = $table['gallery'];
		return \app\model\goodsModel::get_delete($this->table, $this->where, $this->format);
	}

	public function get_goods_attr_list($table)
	{
		$this->table = $table['attr'];
		$result = \app\model\goodsModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
		return $result;
	}

	public function get_goods_attr_info($table)
	{
		$this->table = $table['attr'];
		$result = \app\model\goodsModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
		}
		else {
			$result = \app\model\goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
		}

		return $result;
	}

	public function get_goods_attr_insert($table)
	{
		$this->table = $table['attr'];
		$goodsLang = \languages\goodsLang::lang_goods_insert();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['goods_attr_id'])) {
				if (isset($select['goods_id']) && !empty($select['goods_id']) && isset($select['attr_id']) && !empty($select['attr_id'])) {
					return \app\model\goodsModel::get_insert($this->table, $this->goods_select, $this->format);
				}
				else {
					$error = \languages\goodsLang::INSERT_NULL_NAME_FAILURE;
					$info = $select;
				}
			}
			else {
				$info = $select;
				$error = \languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'goods_attr_id';
			}
		}
		else {
			$error = \languages\goodsLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_attr_update($table)
	{
		$this->table = $table['attr'];
		$goodsLang = \languages\goodsLang::lang_goods_update();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['goods_attr_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\goodsLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						return \app\model\goodsModel::get_update($this->table, $this->goods_select, $this->where, $this->format, $info);
					}
				}
				else {
					$error = \languages\goodsLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'goods_attr_id';
			}
		}
		else {
			$error = \languages\goodsLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_attr_delete($table)
	{
		$this->table = $table['attr'];
		return \app\model\goodsModel::get_delete($this->table, $this->where, $this->format);
	}

	public function get_goods_freight_list($table)
	{
		if ($this->seller_id != -1) {
			$this->where = 'gt.ru_id = ' . $this->seller_id . ' GROUP BY gt.tid';
		}

		$join_on = array('', 'tid|tid', 'tid|tid');
		$this->table = $table;
		$result = \app\model\goodsModel::get_join_select_list($this->table, $this->select, $this->where, $join_on);
		$result = \app\model\goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
		return $result;
	}

	public function get_goods_freight_info($table)
	{
		if ($this->tid != -1) {
			$this->where = 'gt.tid = ' . $this->tid . ' GROUP BY gt.tid';
		}

		$join_on = array('', 'tid|tid', 'tid|tid');
		$this->table = $table;
		$result = \app\model\goodsModel::get_join_select_info($this->table, $this->select, $this->where, $join_on);

		if (strlen($this->where) != 1) {
			$result = \app\model\goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
		}
		else {
			$result = \app\model\goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
		}

		return $result;
	}

	public function get_goods_freight_insert($table)
	{
		$this->table = $table;
		$goodsLang = \languages\goodsLang::lang_goods_insert();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['tid'])) {
				return \app\model\goodsModel::get_more_insert($this->table, $this->goods_select, $this->format);
			}
			else {
				$info = $select;
				$error = \languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'tid';
			}
		}
		else {
			$error = \languages\goodsLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::INSERT_CANNOT_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_freight_update($table)
	{
		$this->table = $table;
		$goodsLang = \languages\goodsLang::lang_goods_update();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['tid'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\goodsLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						return \app\model\goodsModel::get_more_update($this->table, $this->goods_select, $this->where, $this->format, $info);
					}
				}
				else {
					$error = \languages\goodsLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'tid';
			}
		}
		else {
			$error = \languages\goodsLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\goodsLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$goodsLang['msg_failure'][$error]['failure'] = sprintf($goodsLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $goodsLang['msg_failure'][$error]['failure'], 'error' => $goodsLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_goods_freight_delete($table)
	{
		$this->table = $table;
		return \app\model\goodsModel::get_more_delete($this->table, $this->where, $this->format);
	}

	public function get_goods_batchinsert($table)
	{
		$this->table = $table['goods'];
		return \app\model\goodsModel::get_goods_batch_insert($this->table, $this->goods_select, $this->format);
	}

	public function get_goods_notification_update($table)
	{
		$this->table = $table;
		return \app\model\goodsModel::get_goodsnotification_update($this->table, $this->goods_select, $this->format);
	}
}

?>
