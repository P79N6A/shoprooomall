<?php
//SHOPROOO商城资源
namespace app\repositories\v2\article;

class ArticleRepository extends \app\repositories\v2\Foundation implements \app\classes\interfaces\RepositoryInterface
{
	public function all($columns = array('*'))
	{
		$Article = \app\models\Article::orderBy('add_time', 'DESC')->orderBy('article_id', 'DESC')->get($columns)->toArray();
		return $Article;
	}

	public function paginate($perPage = 10, $columns = array('*'))
	{
		return \app\models\Article::orderBy('add_time', 'DESC')->orderBy('article_id', 'DESC')->paginate($perPage);
	}

	public function create(array $data)
	{
		return \app\models\Article::create($data);
	}

	public function update(array $data, $id, $field = 'id')
	{
		return \app\models\Article::where($field, '=', $id)->update($data);
	}

	public function delete($id)
	{
		return \app\models\Article::destroy($id);
	}

	public function find($id, $columns = array('*'))
	{
		return \app\models\Article::find($id, $columns);
	}

	public function findBy($field, $value, $columns = array('*'))
	{
		return \app\models\Article::where($field, '=', $value)->first($columns);
	}

	public function getCatArt($cat_id, $size = 10)
	{
		$article = \app\models\Article::where('is_open', '=', 1);

		if (!empty($cat_id)) {
			$article = $article->where('cat_id', $cat_id);
		}

		$result = $article->orderBy('add_time', 'DESC')->orderBy('article_id', 'DESC')->paginate($size)->toArray();

		foreach ($result['data'] as $key => $val) {
			$result['data'][$key]['extend'] = \app\models\ArticleExtend::where('article_id', $val['id'])->get()->toArray();
		}

		return $result['data'];
	}

	public function getCatArt_count($cat_id)
	{
		$Article = \app\models\Article::where('is_open', '=', 1);

		if (!empty($cat_id)) {
			$Article = $Article->where('cat_id', $cat_id);
		}

		$Article = $Article->get($columns)->toArray();
		return count($Article);
	}

	public function detail($id, $columns = array('*'))
	{
		$model = $this->find($id, $columns);

		if (!empty($model)) {
			$extend = $model->extend;

			if (empty($extend)) {
				$data = array('article_id' => $model->article_id, 'click' => 1, 'likenum' => 0, 'hatenum' => 0);
				\app\models\ArticleExtend::create($data);
				$article = $model->toArray();
				$article['extend'] = $data;
			}
			else {
				$article = $model->toArray();
				$comment = $model->comment->toArray();
				$res = $this->treeComment($comment, 0);
				$article['comment'] = $res;
				$GoodsArticleid = \app\models\GoodsArticle::where('article_id', $id)->get()->toArray();
				$Goods = \app\models\Goods::where('goods_id', $GoodsArticleid[0]['id'])->get()->toArray();
				$article['Goods'] = $Goods[0];
			}

			return $article;
		}

		return false;
	}

	public function treeComment($data, $parent_id)
	{
		$ret = array();

		foreach ($data as $k => $v) {
			if ($v['parent_id'] == $parent_id) {
				$v['child'] = $this->treeComment($data, $v['comment_id']);
				$ret[] = $v;
			}
		}

		return $ret;
	}
}

?>
