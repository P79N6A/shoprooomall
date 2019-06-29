<?php
//SHOPROOO
namespace languages;

class userLang
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

	static public function lang_user_request()
	{
		self::$lang_insert_conf = array(
			'msg_success'   => array('success' => '成功获取数据', 'error' => self::REQUEST_DATA_SUSCCESS),
			'msg_failure'   => array('failure' => '数据为空值', 'error' => self::REQUEST_NOT_DATA_FAILURE),
			'where_failure' => array('failure' => '条件为空或参数不存在', 'error' => self::REQUEST_NULL_PARAM_FAILURE)
			);
		return self::$lang_insert_conf;
	}

	static public function lang_user_insert()
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

	static public function lang_user_update()
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

	static public function lang_user_delete()
	{
		self::$lang_del_conf = array(
			'msg_success' => array('success' => '删除成功', 'error' => self::DEL_DATA_SUSCCESS),
			'msg_failure' => array(
				self::DEL_DATA_FAILURE       => array('failure' => '删除失败', 'error' => self::DEL_DATA_FAILURE),
				self::DEL_NULL_PARAM_FAILURE => array('failure' => '条件为空或参数不存在', 'error' => self::DEL_NULL_PARAM_FAILURE)
				)
			);
		return self::$lang_del_conf;
	}

	static public function __callStatic($method, $arguments)
	{
		return call_user_func_array(array(self, $method), $arguments);
	}
}


?>
