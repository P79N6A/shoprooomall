<?php
//zend SHOPROOO在线更新版  禁止倒卖 一经发现停止任何服务
namespace App\Services\V3\Union;

class ArticleService implements \App\Contracts\Services\Article\ArticleServiceInterface, \App\Contracts\Services\Article\CategoryServiceInterface
{
	/**
     * @var ArticleRepository
     */
	private $article;
	/**
     * @var CategoryRepository
     */
	private $category;

	public function __construct(\App\Repositories\Article\CategoryRepository $categoryRepository, \App\Repositories\Article\ArticleRepository $articleRepository)
	{
		$this->category = $categoryRepository;
		$this->article = $articleRepository;
	}

	public function category($id)
	{
		return 'category';
	}

	public function detail($id)
	{
		return $this->category->detail($id);
	}

	public function all($id)
	{
		return 'all';
	}

	public function show($id)
	{
		return 'show';
	}

	public function agreement()
	{
		return 'agreement';
	}

	public function help()
	{
		return 'help';
	}
}

?>
