<?php
//SHOPROOO
namespace App\Services;

class AccountService
{
	private $accountRepository;
	private $userRepository;
	private $root_url;

	public function __construct(\App\Repositories\Account\AccountRepository $accountRepository, \App\Repositories\User\UserRepository $userRepository, \Illuminate\Http\Request $request)
	{
		$this->accountRepository = $accountRepository;
		$this->userRepository = $userRepository;
		$this->root_url = dirname(dirname($request->root())) . '/';
	}

	public function logAccountChange($user_id, $shop_money = 0, $frozen_money = 0, $rank_points = 0, $pay_points = 0, $change_desc = '', $change_type)
	{
		if ($change_type == ACT_TRANSFERRED) {
			$account_log = array('user_id' => $user_id, 'user_money' => 0 - $shop_money, 'frozen_money' => $frozen_money, 'rank_points' => 0 - $rank_points, 'pay_points' => 0 - $pay_points, 'change_time' => gmtime(), 'change_desc' => $change_desc, 'change_type' => $change_type);
			$this->accountRepository->addAccountLog($account_log);
		}

		if ($change_type == ACT_TRANSFERRED) {
			$user_info = $this->userRepository->userInfo($user_id);
			$user_log = array('user_money' => $user_info['user_money'] - $shop_money, 'frozen_money' => $user_info['frozen_money'] - $frozen_money, 'rank_points' => $user_info['rank_points'] - $rank_points, 'pay_points' => $user_info['pay_points'] - $pay_points);
			$this->accountRepository->updateuser($user_id, $user_log);
		}
	}
}


?>
