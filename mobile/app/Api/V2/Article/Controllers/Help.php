<?php
//SHOPROOO商城资源
namespace app\api\v2\article\controllers;

class Help extends \app\api\foundation\Controller
{
	/**
     * @var CategoryRepository
     */
	protected $category;
	/**
     * @var ArticleRepository
     */
	protected $article;

	public function __construct(\app\repositories\article\CategoryRepository $category, \app\repositories\article\ArticleRepository $article)
	{
		parent::__construct();
		$this->category = $category;
		$this->article = $article;
	}

	public function actionList(array $args)
	{
		$help = cache('shop_help');

		if (!$help) {
			$help = array();
			$intro = $this->category->detail(array('cat_type' => INFO_CAT), array('cat_id', 'cat_name'));
			$intro['list'] = $this->article->all($intro['id'], array('title'));
			$help[] = $intro;
			$list = $this->category->all(array('cat_type' => HELP_CAT), array('cat_id', 'cat_name'));

			foreach ($list['data'] as $key => $item) {
				$item['list'] = $this->article->all($item['id'], array('title'));
				$help[] = $item;
			}

			cache('shop_help', $help);
		}

		return $help;
	}
}

?>
