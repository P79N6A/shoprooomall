<?php
//SHOPROOO商城资源
namespace app\api\v2\wx\controllers;

class Goods extends \app\api\foundation\Controller
{
	private $goodsService;

	public function __construct(\app\services\GoodsService $goodsService)
	{
		parent::__construct();
		$this->goodsService = $goodsService;
	}

	public function actionList(array $args)
	{
		$pattern = array('id' => 'required|integer', 'page' => 'integer');

		if (true !== ($result = $this->validate($args, $pattern))) {
			return $this->apiReturn($result, 1);
		}

		$list = $this->goodsService->getGoodsList($args['id'], $args['page']);
		return $this->apiReturn($list);
	}

	public function actionDetail(array $args)
	{
		$pattern = array('id' => 'required|integer');

		if (true !== ($result = $this->validate($args, $pattern))) {
			return $this->apiReturn($result, 1);
		}

		$list = $this->goodsService->goodsDetail($args['id']);
		return $this->apiReturn($list);
	}

	public function actionProperty(array $args)
	{
		$pattern = array('id' => 'required|integer', 'attr_id' => 'required', 'num' => 'required|integer');

		if (true !== ($result = $this->validate($args, $pattern))) {
			return $this->apiReturn($result, 1);
		}

		$price = $this->goodsService->goodsPropertiesPrice($args);
		return $this->apiReturn(array('total_price' => $price));
	}
}

?>
