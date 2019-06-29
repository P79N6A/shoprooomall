<?php
//zend SHOPROOO商城资源  禁止倒卖 一经发现停止任何服务
namespace App\Services;

class RegionService
{
	private $regionRepository;

	public function __construct(\App\Repositories\Region\RegionRepository $regionRepository)
	{
		$this->regionRepository = $regionRepository;
	}

	public function regionList($args)
	{
		$list = $this->regionRepository->getRegionAll($args);
		return $list;
	}
}


?>
