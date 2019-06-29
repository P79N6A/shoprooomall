<?php
//SHOPROOO商城资源
namespace App\Services;

class WxappService
{
	private $WxappConfigRepository;

	public function __construct(\App\Repositories\Wechat\WxappConfigRepository $WxappConfigRepository)
	{
		$this->WxappConfigRepository = $WxappConfigRepository;
	}

	public function getWxappConfig()
	{
		return $this->WxappConfigRepository->getWxappConfig();
	}

	public function getWxappConfigByCode($code)
	{
		return $this->WxappConfigRepository->getWxappConfigByCode($code);
	}
}


?>
