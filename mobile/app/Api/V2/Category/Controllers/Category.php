<?php
//SHOPROOO商城资源
namespace app\api\v2\category\controllers;

class Category extends \App\Api\Foundation\Controller
{
	/** @var  $category */
	protected $category;
	/** @var  $categoryTransformer */
	protected $categoryTransformer;

	public function __construct(\App\Repositories\category\CategoryRepository $category, \app\api\v2\category\transformer\CategoryTransformer $categoryTransformer)
	{
		parent::__construct();
		$this->category = $category;
		$this->categoryTransformer = $categoryTransformer;
	}

	public function actionList()
	{
		$data = $this->category->getAllCategorys();
		return $this->apiReturn($data);
	}

	public function actionGet(array $args)
	{
		$pattern = array(
			array('id', 'require')
			);
		$this->validate($args, $pattern);
		$data = $this->category->getCategoryGetGoods($args['id']);
		return $this->apiReturn($data);
	}
}

?>
