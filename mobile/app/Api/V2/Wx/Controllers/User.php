<?php
//SHOPROOO商城资源
namespace app\api\v2\wx\controllers;

class User extends \app\api\foundation\Controller
{
	private $userService;
	private $authService;

	public function __construct(\app\services\UserService $userService, \app\services\AuthService $authService)
	{
		parent::__construct();
		$this->userService = $userService;
		$this->authService = $authService;
	}

	public function actionLogin(array $args)
	{
		$userInfo = $args['userinfo'];
		$pattern = array('code' => 'required|string');

		if (true !== ($result = $this->validate($userInfo, $pattern))) {
			return $this->apiReturn($result, 1);
		}

		if (false === ($result = $this->authService->loginMiddleWare($userInfo))) {
			return $this->apiReturn('登录失败', 1);
		}

		return $this->apiReturn($result);
	}

	public function actionIndex(array $args)
	{
		$res = $this->authService->authorization();
		$pattern = array('id' => 'required|integer');

		if (true !== ($result = $this->validate($args, $pattern))) {
			return $this->apiReturn($result, 1);
		}

		$userCenterData = $this->userService->userCenter($args);
		return $this->apiReturn($userCenterData);
	}

	public function actionOrderList(array $args)
	{
		$res = $this->authService->authorization();
		$pattern = array('page' => 'required|integer', 'size' => 'required|integer', 'status' => 'required|integer');

		if (true !== ($result = $this->validate($args, $pattern))) {
			return $this->apiReturn($result, 1);
		}

		$userCenterData = $this->userService->orderList(array_merge(array('uid' => $res), $args));
		return $this->apiReturn($userCenterData);
	}
}

?>
