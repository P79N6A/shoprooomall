<?php
//SHOPROOO
namespace app\controller;

class product extends \app\model\productModel
{
	private $table;
	private $alias;
	private $product_select = array();
	private $select;
	private $product_id = 0;
	private $goods_id = 0;
	private $product_sn = '';
	private $bar_code = 0;
	private $warehouse_id = 0;
	private $area_id = 0;
	private $format = 'json';
	private $page_size = 10;
	private $page = 1;
	private $wehre_val;
	private $productLangList;
	private $sort_by;
	private $sort_order;

	public function __construct($where = array())
	{
		$this->product($where);
		$this->wehre_val = array('product_id' => $this->product_id, 'goods_id' => $this->goods_id, 'product_sn' => $this->product_sn, 'bar_code' => $this->bar_code, 'warehouse_id' => $this->warehouse_id, 'area_id' => $this->area_id);
		$this->where = \app\model\productModel::get_where($this->wehre_val);
		$this->select = \app\func\base::get_select_field($this->product_select);
	}

	public function product($where = array())
	{
		$this->product_id = $where['product_id'];
		$this->goods_id = $where['goods_id'];
		$this->product_sn = $where['product_sn'];
		$this->bar_code = $where['bar_code'];
		$this->warehouse_id = $where['warehouse_id'];
		$this->area_id = $where['area_id'];
		$this->product_select = $where['product_select'];
		$this->format = $where['format'];
		$this->page_size = $where['page_size'];
		$this->page = $where['page'];
		$this->sort_by = $where['sort_by'];
		$this->sort_order = $where['sort_order'];
		$this->productLangList = \languages\productLang::lang_product_request();
	}

	public function get_product_list($table)
	{
		$this->table = $table['product'];
		$result = \app\model\productModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
		$result = \app\model\productModel::get_list_common_data($result, $this->page_size, $this->page, $this->productLangList, $this->format);
		return $result;
	}

	public function get_product_info($table)
	{
		$this->table = $table['product'];
		$result = \app\model\productModel::get_select_info($this->table, $this->select, $this->where);

		if (strlen($this->where) != 1) {
			$result = \app\model\productModel::get_info_common_data_fs($result, $this->productLangList, $this->format);
		}
		else {
			$result = \app\model\productModel::get_info_common_data_f($this->productLangList, $this->format);
		}

		return $result;
	}

	public function get_product_insert($table)
	{
		$this->table = $table['product'];
		$productLang = \languages\productLang::lang_product_insert();
		$select = $this->product_select;

		if ($select) {
			if (!isset($select['product_id'])) {
				if (isset($select['goods_id']) && !empty($select['goods_id']) && isset($select['product_sn']) && !empty($select['product_sn'])) {
					$where = '';

					if (isset($select['user_id'])) {
						if (!empty($select['user_id'])) {
							$user_id = $select['user_id'];
						}
						else {
							$user_id = 0;
						}

						$where = ' AND (SELECT user_id FROM ' . $GLOBALS['ecs']->table($table['goods']) . ' WHERE goods_id = \'' . $select['goods_id'] . ('\') = \'' . $user_id . '\'');
					}

					if (isset($select['warehouse_id']) && !empty($select['warehouse_id'])) {
						$where .= ' AND warehouse_id = \'' . $select['warehouse_id'] . '\'';
					}
					else {
						if (isset($select['area_id']) && !empty($select['area_id'])) {
							$where .= ' AND area_id = \'' . $select['area_id'] . '\'';
						}
					}

					$where = 'product_sn = \'' . $select['product_sn'] . ('\' ' . $where);
					$info = $this->get_select_info($this->table, '*', $where);

					if (!$info) {
						return \app\model\productModel::get_insert($this->table, $this->product_select, $this->format);
					}
					else {
						$error = \languages\productLang::INSERT_DATA_EXIST_FAILURE;
						$info = $select;
					}
				}
				else {
					$error = \languages\productLang::INSERT_KEY_PARAM_FAILURE;
					$string = 'goods_id, product_sn';
				}
			}
			else {
				$info = $select;
				$error = \languages\productLang::INSERT_CANNOT_PARAM_FAILURE;
				$string = 'product_id';
			}
		}
		else {
			$error = \languages\productLang::INSERT_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\productLang::INSERT_CANNOT_PARAM_FAILURE, \languages\productLang::INSERT_KEY_PARAM_FAILURE))) {
			$productLang['msg_failure'][$error]['failure'] = sprintf($productLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $productLang['msg_failure'][$error]['failure'], 'error' => $productLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_product_update($table)
	{
		$this->table = $table['product'];
		$productLang = \languages\productLang::lang_goods_update();
		$select = $this->goods_select;

		if ($select) {
			if (!isset($select['goods_id'])) {
				if (strlen($this->where) != 1) {
					$info = $this->get_select_info($this->table, '*', $this->where);

					if (!$info) {
						$error = \languages\productLang::UPDATE_DATA_NULL_FAILURE;
					}
					else {
						$where = '';

						if (isset($select['user_id'])) {
							if (!empty($select['user_id'])) {
								$user_id = $select['user_id'];
							}
							else {
								$user_id = 0;
							}

							$where = ' AND (SELECT user_id FROM ' . $GLOBALS['ecs']->table($table['goods']) . ' WHERE goods_id = \'' . $select['goods_id'] . ('\') = \'' . $user_id . '\'');
						}

						if (isset($select['warehouse_id']) && !empty($select['warehouse_id'])) {
							$where .= ' AND warehouse_id = \'' . $select['warehouse_id'] . '\'';
						}
						else {
							if (isset($select['area_id']) && !empty($select['area_id'])) {
								$where .= ' AND area_id = \'' . $select['area_id'] . '\'';
							}
						}

						$where = 'product_sn = \'' . $select['product_sn'] . ('\' ' . $where);
						$info = $this->get_select_info($this->table, '*', $where);

						if ($info) {
							$error = \languages\productLang::UPDATE_DATA_EXIST_FAILURE;
							$info = $select;
						}
						else {
							return \app\model\productModel::get_update($this->table, $this->product_select, $this->where, $this->format, $info);
						}
					}
				}
				else {
					$error = \languages\productLang::UPDATE_NULL_PARAM_FAILURE;
				}
			}
			else {
				$error = \languages\productLang::UPDATE_CANNOT_PARAM_FAILURE;
				$string = 'goods_id';
			}
		}
		else {
			$error = \languages\productLang::UPDATE_NOT_PARAM_FAILURE;
		}

		if (in_array($error, array(\languages\productLang::UPDATE_CANNOT_PARAM_FAILURE))) {
			$productLang['msg_failure'][$error]['failure'] = sprintf($productLang['msg_failure'][$error]['failure'], $string);
		}

		$common_data = array('result' => 'failure', 'msg' => $productLang['msg_failure'][$error]['failure'], 'error' => $productLang['msg_failure'][$error]['error'], 'format' => $format, 'info' => $info);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_product_delete($table)
	{
		$this->table = $table['product'];
		return \app\model\productModel::get_delete($this->table, $this->where, $this->format);
	}
}

?>
