<?php
//zend SHOPROOO在线更新  禁止倒卖 一经发现停止任何服务
namespace App\Repositories\Account;

class AccountRepository
{
	protected $goods;
	private $field;
	private $authService;
	private $goodsAttrRepository;
	private $shopConfigRepository;
	private $goodsRepository;
	private $userRepository;

	public function __construct(\App\Services\AuthService $authService, \App\Repositories\Goods\GoodsAttrRepository $goodsAttrRepository, \App\Repositories\ShopConfig\ShopConfigRepository $shopConfigRepository, \App\Repositories\Goods\GoodsRepository $goodsRepository, \App\Repositories\User\UserRepository $userRepository)
	{
		$this->authService = $authService;
		$this->goodsAttrRepository = $goodsAttrRepository;
		$this->shopConfigRepository = $shopConfigRepository;
		$this->goodsRepository = $goodsRepository;
		$this->userRepository = $userRepository;
	}

	public function addAccountLog($params)
	{
		$add = \App\Models\AccountLog::insertGetId($params);

		if ($add) {
			return $add;
		}
	}

	public function updateuser($user_id, $params)
	{
		\App\Models\Users::where('user_id', $user_id)->update($params);
	}
}


?>
