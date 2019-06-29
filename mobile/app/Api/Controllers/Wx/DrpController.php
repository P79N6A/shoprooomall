<?php
 //SHOPROOO
namespace App\Api\Controllers\Wx;

class DrpController extends \App\Api\Controllers\Controller
{
	private $drpService;
	private $authService;

	public function __construct(\App\Services\DrpService $drpService, \App\Services\AuthService $authService)
	{
		$this->drpService = $drpService;
		$this->authService = $authService;
	}

	public function index(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->index($uid);
	}

	public function con(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->con($uid);
	}

	public function purchase(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->purchase($uid);
	}

	public function PurchasePay(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->PurchasePay($uid);
	}

	public function register(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('shopname' => 'required|string', 'realname' => 'required|string', 'mobile' => 'required|string'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->drpRegister($uid, $request->input('shopname'), $request->input('realname'), $request->input('mobile'), $request->input('qq'));
	}

	public function regend(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->regEnd($uid);
	}

	public function usercard(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('path' => 'required|string', 'uid' => 'required|integer'));
		return $this->drpService->userCard($request->get('uid'), $request->get('path'));
	}

	public function team(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('uid' => 'required|integer'));
		return $this->drpService->team($request->get('uid'));
	}

	public function teamdetail(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('uid' => 'required|integer'));
		return $this->drpService->teamdetail($request->get('uid'));
	}

	public function OfflineUser(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('uid' => 'required|integer'));
		return $this->drpService->OfflineUser($request->get('uid'));
	}

	public function ranklist(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->ranklist($uid);
	}

	public function buymsg(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->purchase($uid);
	}

	public function shop(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('uid' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->shop($request->get('uid'));
	}

	public function shopgoods(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('id' => 'required|integer', 'uid' => 'required|integer', 'page' => 'required|integer', 'size' => 'required|integer', 'status' => 'required|integer', 'type' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->shopgoods($request->get('uid'), $request->get('id'), $request->get('page'), $request->get('size'), $request->get('status'), $request->get('type'));
	}

	public function order(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('page' => 'required|integer', 'size' => 'required|integer', 'status' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->order($uid, $request->get('page'), $request->get('size'), $request->get('status'));
	}

	public function orderdetail(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('order_id' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->orderdetail($uid, $request->get('order_id'));
	}

	public function settings(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		$args = $request->all();
		$args['uid'] = $uid;
		return $this->drpService->settings($args);
	}

	public function category(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		$args = $request->all();
		$args['uid'] = $uid;
		return $this->drpService->category($args);
	}

	public function add(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('id' => 'required', 'type' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->add($uid, $request->get('id'), $request->get('type'));
	}

	public function showgoods(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('page' => 'required|integer', 'size' => 'required|integer', 'type' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->showgoods($uid, $request->get('page'), $request->get('size'), $request->get('type'));
	}

	public function drplog(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('page' => 'required|integer', 'size' => 'required|integer', 'status' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->drplog($uid, $request->get('page'), $request->get('size'), $request->get('status'));
	}

	public function news(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		return $this->drpService->news($uid);
	}
}

?>
