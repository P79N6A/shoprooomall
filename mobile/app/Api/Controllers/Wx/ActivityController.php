<?php
//SHOPROOO
namespace App\Api\Controllers\Wx;

class ActivityController extends \App\Api\Controllers\Controller
{
	private $activityService;
	private $authService;
	private $goodsService;

	public function __construct(\App\Services\ActivityService $activityService, \App\Services\AuthService $authService, \App\Services\GoodsService $goodsService)
	{
		$this->activityService = $activityService;
		$this->authService = $authService;
		$this->goodsService = $goodsService;
	}

	public function index(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array());
		$list['list'] = $this->activityService->activityList();
		return $this->apiReturn($list);
	}

	public function detail(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('act_id' => 'required|integer'));
		$list = $this->activityService->detail($request->get('act_id'));
		return $this->apiReturn($list);
	}

	public function activityGoods(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('page' => 'required|integer', 'size' => 'required|integer', 'act_id' => 'required|integer'));
		$list = $this->activityService->activityGoods($request->get('act_id'), $request->get('page'), $request->get('size'));
		return $this->apiReturn($list);
	}

	public function coudan(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('act_id' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			$uid = 0;
		}

		$info = $this->activityService->coudan($uid, $request->get('act_id'));
		return $this->apiReturn($info);
	}

	public function coudanList(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('page' => 'required|integer', 'size' => 'required|integer', 'act_id' => 'required|integer'));
		$list = $this->activityService->activityGoods($request->get('act_id'), $request->get('page'), $request->get('size'));
		return $this->apiReturn($list);
	}
}

?>
