<?php
//SHOPROOO 
namespace App\Services\Category;

class CategoryService
{
	private $categoryRepository;

	public function __construct(\App\Repositories\Category\CategoryRepository $categoryRepository)
	{
		$this->categoryRepository = $categoryRepository;
	}

	public function categoryList($uid)
	{
		$list = $this->categoryRepository->getAllCategorys($uid);
		return $list;
	}

	public function categoryDetail($catId)
	{
		$list = $this->categoryRepository->getCategoryGetGoods($catId);
		return $list;
	}
}


?>
