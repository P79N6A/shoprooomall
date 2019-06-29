<?php
//SHOPROOO商城资源
namespace app\api\v2\wx\controllers;

class Cart extends \app\api\foundation\Controller
{
	private $cartService;
	private $authService;

	public function __construct(\app\services\CartService $cartService, \app\services\AuthService $authService)
	{
		parent::__construct();
		$this->cartService = $cartService;
		$this->authService = $authService;
	}

	public function actionCart()
	{
		$cart = $this->cartService->getCart();
		return $this->apiReturn($cart);
	}

	public function actionAddgoodstocart(array $args)
	{
		$res = $this->authService->authorization();
		if ($res && $res['error' === 0]) {
			$uid = $res['uid'];
		}
		else {
			return $this->apiReturn($res);
		}

		$pattern = array('id' => 'required|integer', 'num' => 'required|integer', 'goods_attr' => 'integer');

		if (true !== ($result = $this->validate($args, $pattern))) {
			return $this->apiReturn($result);
		}

		$args = array_merge($args, array('uid' => $uid));
		$result = $this->cartService->addGoodsToCart($args);
		return $this->apiReturn($result);
	}
}

?>
