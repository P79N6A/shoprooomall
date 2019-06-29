<?php
//SHOPROOO 
namespace App\Repositories\Article;

class ArticleRepository
{
	public function all($cat_id, $columns = array('*'), $page, $size)
	{
		$res = array();
		$current = ($page - 1) * $size;
		$article = \App\Models\Article::where('is_open', '=', 1);

		if ($cat_id == '-1') {
			$article = $article->where('cat_id', '>', 0);
		}
		else {
			$cat_str = $this->get_article_children($cat_id);

			if ($cat_str) {
				array_unshift($cat_str, $cat_id);
				$article = $article->whereIn('cat_id', $cat_str);
			}
			else {
				$article = $article->where('cat_id', $cat_id);
			}
		}

		$res['article'] = $article->offset($current)->limit($size)->orderBy('sort_order', 'ASC')->orderBy('add_time', 'DESC')->get()->toArray();
		$res['num'] = $article->count('article_id');
		return $res;
	}

	public function detail($id)
	{
		if (is_array($id)) {
			$field = key($id);
			$value = $id[$field];
			$model = \App\Models\Article::where($field, '=', $value)->first();
		}
		else {
			$model = \App\Models\Article::find($id);
		}

		if (is_null($model)) {
			return false;
		}

		$article = $model->toArray();

		if (is_null($model->extend)) {
			$data = array('article_id' => $model->article_id, 'click' => 1, 'likenum' => 0, 'hatenum' => 0);
			\App\Models\ArticleExtend::create($data);
		}
		else {
			$data = $model->extend->toArray();
			unset($data['id']);
		}

		$article = array_merge($article, $data);

		foreach ($model->comment as $vo) {
			$model->comment->push($vo->user);
		}

		$article['comment'] = $model->comment->where('id_value', '=', $id)->where('status', '=', 1)->toArray();
		$article['goods'] = $model->goods->toArray();
		return $article;
	}

	public function articleCatInfo($id)
	{
		$where = array('article_id' => $id);
		$article_cat = \App\Models\Article::join('article_cat', 'article.cat_id', '=', 'article_cat.cat_id')->where($where)->select('article_cat.cat_id', 'article_cat.cat_name')->first();

		if ($article_cat === null) {
			return array();
		}

		return $article_cat->toArray();
	}

	public function get_article_children($id)
	{
		$res = \App\Models\ArticleCat::select('cat_id')->where('parent_id', $id)->get()->toArray();

		foreach ($res as $k => $v) {
			$cat[$k] = $v['id'];
		}

		foreach ($cat as $key => $val) {
			$tree = $this->get_article_children($val);

			if (!empty($tree)) {
				$result = array_merge($cat, $tree);
			}
			else {
				$result = $cat;
			}
		}

		return $result;
	}
}


?>
