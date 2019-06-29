<?php
//SHOPROOO
namespace languages;

class orderLang
{
	const REQUEST_DATA_SUSCCESS = 0;
	const REQUEST_NOT_DATA_FAILURE = 1;
	const REQUEST_NULL_PARAM_FAILURE = 2;
	const INSERT_DATA_SUSCCESS = 0;
	const INSERT_NOT_PARAM_FAILURE = 1;
	const INSERT_CANNOT_PARAM_FAILURE = 2;
	const INSERT_DATA_EXIST_FAILURE = 3;
	const INSERT_KEY_PARAM_FAILURE = 4;
	const INSERT_SAME_NAME_FAILURE = 5;
	const INSERT_NULL_NAME_FAILURE = 6;
	const UPDATE_DATA_SUSCCESS = 0;
	const UPDATE_NOT_PARAM_FAILURE = 1;
	const UPDATE_NULL_PARAM_FAILURE = 2;
	const UPDATE_DATA_EXIST_FAILURE = 3;
	const UPDATE_DATA_NULL_FAILURE = 4;
	const UPDATE_CANNOT_PARAM_FAILURE = 5;
	const UPDATE_SAME_NAME_FAILURE = 6;
	const DEL_DATA_SUSCCESS = 0;
	const DEL_DATA_FAILURE = 1;
	const DEL_NULL_PARAM_FAILURE = 2;

	static private $lang_insert_conf;
	static private $lang_update_conf;
	static private $lang_del_conf;

	public function __construct()
	{
	}

	static public function lang_order_request()
	{
		self::$lang_insert_conf = array(
			'msg_success'   => array('success' => '成功获取数据', 'error' => self::REQUEST_DATA_SUSCCESS),
			'msg_failure'   => array('failure' => '数据为空值', 'error' => self::REQUEST_NOT_DATA_FAILURE),
			'where_failure' => array('failure' => '条件为空或参数不存在', 'error' => self::REQUEST_NULL_PARAM_FAILURE)
			);
		return self::$lang_insert_conf;
	}

	static public function lang_order_insert()
	{
		self::$lang_insert_conf = array(
			'msg_success' => array('success' => '数据提交成功', 'error' => self::INSERT_DATA_SUSCCESS),
			'msg_failure' => array(
				self::INSERT_NOT_PARAM_FAILURE    => array('failure' => '数据提交失败,参数不存在', 'error' => self::INSERT_NOT_PARAM_FAILURE),
				self::INSERT_CANNOT_PARAM_FAILURE => array('failure' => '存在不可传参的参数：%s', 'error' => self::INSERT_CANNOT_PARAM_FAILURE),
				self::INSERT_DATA_EXIST_FAILURE   => array('failure' => '已存在相同数据，无法添加', 'error' => self::INSERT_DATA_EXIST_FAILURE),
				self::INSERT_KEY_PARAM_FAILURE    => array('failure' => '关键参数不存在或值为空：%s', 'error' => self::INSERT_KEY_PARAM_FAILURE),
				self::INSERT_SAME_NAME_FAILURE    => array('failure' => '当前名称数据已存在，无法添加', 'error' => self::INSERT_SAME_NAME_FAILURE),
				self::INSERT_NULL_NAME_FAILURE    => array('failure' => '名称值不能为空或传参名称不存在', 'error' => self::INSERT_NULL_NAME_FAILURE)
				)
			);
		return self::$lang_insert_conf;
	}

	static public function lang_order_update()
	{
		self::$lang_update_conf = array(
			'msg_success' => array('success' => '数据更新成功', 'error' => self::UPDATE_DATA_SUSCCESS),
			'msg_failure' => array(
				self::UPDATE_NOT_PARAM_FAILURE    => array('failure' => '数据提交失败,参数不存在', 'error' => self::UPDATE_NOT_PARAM_FAILURE),
				self::UPDATE_NULL_PARAM_FAILURE   => array('failure' => '条件为空或参数不存在', 'error' => self::UPDATE_NULL_PARAM_FAILURE),
				self::UPDATE_DATA_EXIST_FAILURE   => array('failure' => '已存在相同数据，无法更新', 'error' => self::UPDATE_DATA_EXIST_FAILURE),
				self::UPDATE_DATA_NULL_FAILURE    => array('failure' => '当前数据不存在', 'error' => self::UPDATE_DATA_NULL_FAILURE),
				self::UPDATE_CANNOT_PARAM_FAILURE => array('failure' => '存在不可传参的参数：%s', 'error' => self::UPDATE_CANNOT_PARAM_FAILURE),
				self::UPDATE_SAME_NAME_FAILURE    => array('failure' => '当前名称数据已存在,无法更新', 'error' => self::UPDATE_SAME_NAME_FAILURE)
				)
			);
		return self::$lang_update_conf;
	}

	static public function lang_order_delete()
	{
		self::$lang_update_conf = array(
			'msg_success' => array('success' => '删除成功', 'error' => self::DEL_DATA_SUSCCESS),
			'msg_failure' => array(
				self::DEL_DATA_FAILURE       => array('failure' => '删除失败', 'error' => self::DEL_DATA_FAILURE),
				self::DEL_NULL_PARAM_FAILURE => array('failure' => '条件为空或参数不存在', 'error' => self::DEL_NULL_PARAM_FAILURE)
				)
			);
		return self::$lang_del_conf;
	}

	static public function lang_order_confirmorder()
	{
		self::$lang_update_conf = array(
			'msg_success'       => array('code' => 10000, 'message' => '发货成功'),
			'ordersn_failure'   => array('code' => 1, 'message' => '订单号不能为空'),
			'expressno_failure' => array('message' => '快递单号不能为空', 'code' => 2),
			'code_failure'      => array('message' => '快递不能为空', 'code' => 3),
			'shipping_failure'  => array('message' => '快递不支持', 'code' => 4),
			'delivery_failure'  => array('message' => '发货失败，请重试', 'code' => 5),
			'data_null'         => array('message' => '发货信息不能为空', 'code' => 6),
			'conf_message'      => array('split_action_note' => '【商品货号：%s，发货：%s件】', 'order_ship_delivery' => '发货单流水号：【%s】', 'action_user' => '第三方商家操作', 'order_gift_integral' => '订单 %s 赠送的积分')
			);
		return self::$lang_update_conf;
	}

	static public function __callStatic($method, $arguments)
	{
		return call_user_func_array(array(self, $method), $arguments);
	}
}


?>
