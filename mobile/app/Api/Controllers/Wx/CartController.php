<?php
//SHOPROOO 禁止倒卖 一经发现停止任何服务
namespace App\Api\Controllers\Wx;

class CartController extends \App\Api\Controllers\Controller
{
	private $cartService;
	private $authService;

	public function __construct(\App\Services\CartService $cartService, \App\Services\AuthService $authService)
	{
		$this->cartService = $cartService;
		$this->authService = $authService;
	}

	public function cart(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$cart = $this->cartService->getCart();
		return $this->apiReturn($cart);
	}

	public function checked(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$cart = $this->cartService->checked($request->get('rec_id'));
		return $this->apiReturn($cart);
	}

	public function addGoodsToCart(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('id' => 'required|integer', 'num' => 'required|integer'));
		$res = $this->authService->authorization();
		if (isset($res['error']) && 0 < $res['error']) {
			return $this->apiReturn($res, 1);
		}

		$args = array_merge($request->all(), array('uid' => $res));
		$result = $this->cartService->addGoodsToCart($args);
		return $this->apiReturn($result);
	}

	public function addGiftCart(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('act_id' => 'required|integer', 'ru_id' => 'required|integer'));
		$res = $this->authService->authorization();
		if (isset($res['error']) && 0 < $res['error']) {
			return $this->apiReturn($res, 1);
		}

		$args = array_merge($request->all(), array('uid' => $res));
		$result = $this->cartService->addGiftCart($args);
		return $this->apiReturn($result);
	}

	public function updateCartGoods(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('id' => 'required|integer', 'amount' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		$args = $request->all();
		$args['uid'] = $uid;
		return $this->cartService->updateCartGoods($args);
	}

	public function deleteCartGoods(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$rec_id = $request->input('id', array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		$args = array();
		$args['id'] = $rec_id;
		$args['uid'] = $uid;
		$res = $this->cartService->deleteCartGoods($args);
		return $res;
	}
}

?>
