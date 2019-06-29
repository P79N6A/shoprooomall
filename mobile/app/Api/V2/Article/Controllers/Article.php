<?php
//SHOPROOO商城资源
namespace app\api\v2\article\controllers;

class Article extends \app\api\foundation\Controller
{
	/**
     * @var ArticleRepository
     */
	protected $article;
	/**
     * @var ArticleTransformer
     */
	protected $articleTransformer;

	public function __construct(\app\repositories\article\ArticleRepository $article, \app\api\v2\article\transformer\ArticleTransformer $articleTransformer)
	{
		parent::__construct();
		$this->article = $article;
		$this->articleTransformer = $articleTransformer;
	}

	public function actionList(array $args)
	{
		$result = $this->article->all($args['id']);
		$result['data'] = $this->articleTransformer->transformCollection($result['data']);
		return $result;
	}

	public function actionGet(array $args)
	{
		$result = $this->article->detail($args['id']);
		$result = $this->articleTransformer->transform($result);
		return $result;
	}

	public function actionAgreement(array $args)
	{
		return $this->article->detail(array('cat_id' => '-1'));
	}
}

?>
