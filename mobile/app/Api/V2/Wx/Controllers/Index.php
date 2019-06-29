<?php
//SHOPROOO商城资源
namespace app\api\v2\wx\controllers;

class Index extends \app\api\foundation\Controller
{
	/** @var  $shopRepository */
	protected $shopRepository;
	/** @var  $shopRepository */
	protected $indexService;

	public function __construct(\app\services\IndexService $indexService)
	{
		parent::__construct();
		$this->indexService = $indexService;
	}

	public function actionIndex()
	{
		$banners = $this->indexService->getBanners();
		$data['banner'] = $banners;
		$adsense = $this->indexService->getAdsense();
		$data['adsense'] = $adsense;
		$goodsList = $this->indexService->bestGoodsList();
		$data['goods_list'] = $goodsList;
		return $this->apiReturn($data);
	}
}

?>
