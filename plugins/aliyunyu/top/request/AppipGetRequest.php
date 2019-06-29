<?php
//SHOPROOO商城资源
class AppipGetRequest
{
	private $apiParas = array();

	public function getApiMethodName()
	{
		return 'taobao.appip.get';
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function check()
	{
	}

	public function putOtherTextParam($key, $value)
	{
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}


?>
