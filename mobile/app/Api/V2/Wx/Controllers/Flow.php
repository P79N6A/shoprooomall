<?php
//SHOPROOO商城资源
namespace app\api\v2\wx\controllers;

class Flow extends \app\api\foundation\Controller
{
	private $flowService;

	public function __construct(\app\services\FlowService $flowService)
	{
		parent::__construct();
		$this->flowService = $flowService;
	}

	public function actionIndex()
	{
		$flowInfo = $this->flowService->flowInfo();
		return $this->apiReturn($flowInfo);
	}

	public function actionDown(array $args)
	{
		$pattern = array('consignee' => 'required|integer');

		if (true !== ($result = $this->validate($args, $pattern))) {
			return $this->apiReturn($result, 1);
		}

		$res = $this->flowService->submitOrder($args);
		return $this->apiReturn($res);
	}
}

?>
