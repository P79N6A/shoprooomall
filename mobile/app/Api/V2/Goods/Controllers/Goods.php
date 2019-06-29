<?php
//SHOPROOO商城资源
namespace app\api\v2\goods\controllers;

class Goods extends \App\Api\Foundation\Controller
{
	/** @var  $goods */
	protected $goods;
	/** @var  $goodsTransport */
	protected $goodsTransport;

	public function __construct(\App\Repositories\goods\GoodsRepository $goods, \App\Models\GoodsTransport $goodsTransport)
	{
		parent::__construct();
		$this->goods = $goods;
		$this->goodsTransport = $goodsTransport;
	}

	public function actionList()
	{
	}

	public function actionDetail()
	{
	}

	public function actionSku()
	{
	}

	public function actionFittings()
	{
	}
}

?>
