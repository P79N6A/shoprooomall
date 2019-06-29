<?php
//zend SHOPROOO在线更新版  禁止倒卖 一经发现停止任何服务
namespace App\Modules\Api\Controllers\V3;

class CategoryController extends \App\Modules\Api\Foundation\Controller
{
	private $categoryService;

	public function __construct(\App\Services\CategoryService $categoryService)
	{
		$this->categoryService = $categoryService;
	}

	public function index()
	{
		$list = $this->categoryService->categoryList();
		return $this->apiReturn($list);
	}
}

?>
