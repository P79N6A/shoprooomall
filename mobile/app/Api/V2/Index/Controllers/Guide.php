<?php
//SHOPROOO商城资源
namespace app\api\v2\index\controllers;

class Guide extends \App\Api\Foundation\Controller
{
	private $testAllApis;

	public function __construct(\App\Api\Foundation\TestAllApis $testAllApis)
	{
		parent::__construct();
		$this->testAllApis = $testAllApis;
	}

	public function actionIndex()
	{
	}

	public function actionTest()
	{
		$this->testAllApis->addApis(array('method' => 'ecapi.brand.list2'));
		$res = $this->testAllApis->test();
		return $this->apiReturn($res);
	}
}

?>
