<?php
//SHOPROOO商城资源
namespace app\api\v2\shop\controllers;

class Shop extends \App\Api\Foundation\Controller
{
	/**
     * @var ShopRepository
     */
	protected $shop;
	/**
     * @var ShopTransformer
     */
	protected $shopTransformer;

	public function __construct(\App\Repositories\shop\ShopRepository $shop, \app\api\v2\shop\transformer\ShopTransformer $shopTransformer)
	{
		parent::__construct();
		$this->shop = $shop;
		$this->shopTransformer = $shopTransformer;
	}

	public function actionGet(array $args)
	{
		$pattern = array('id' => 'require');
		$this->validate($args, $pattern);
		$list = $this->shop->get($args['id']);
		return $this->apiReturn($list);
	}
}

?>
