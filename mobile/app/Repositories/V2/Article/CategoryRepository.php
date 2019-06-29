<?php
//SHOPROOO商城资源
namespace app\repositories\v2\article;

class CategoryRepository implements \app\classes\interfaces\RepositoryInterface
{
	public function all($columns = array('*'))
	{
		return \app\models\ArticleCat::orderBy('sort_order')->orderBy('cat_id')->get($columns)->toArray();
	}

	public function paginate($perPage = 10, $columns = array('*'))
	{
		return \app\models\ArticleCat::orderBy('sort_order')->orderBy('cat_id')->paginate($perPage, $columns)->toArray();
	}

	public function create(array $data)
	{
		return \app\models\ArticleCat::create($data);
	}

	public function update(array $data, $id, $field = 'id')
	{
		return \app\models\ArticleCat::where($field, '=', $id)->update($data);
	}

	public function delete($id)
	{
		return \app\models\ArticleCat::destroy($id);
	}

	public function find($id, $columns = array('*'))
	{
		return \app\models\ArticleCat::find($id, $columns)->toArray();
	}

	public function findBy($field, $value, $columns = array('*'))
	{
		return \app\models\ArticleCat::where($field, '=', $value)->first($columns)->toArray();
	}

	public function getTop()
	{
		return \app\models\ArticleCat::where('parent_id', 0)->where('cat_type', 1)->orderBy('sort_order')->orderBy('cat_id')->get()->toArray();
	}

	public function getList($id, $perPage = 10, $columns = array('*'))
	{
		if (0 < \app\models\ArticleCat::where('parent_id', $id)->count()) {
			$model = \app\models\ArticleCat::where('parent_id', $id)->orderBy('sort_order')->orderBy('cat_id');
		}
		else {
			$model = \app\models\Article::where('cat_id', $id)->orderBy('add_time', 'DESC')->orderBy('article_id');
		}

		return $model->paginate($perPage, $columns)->toArray();
	}
}

?>
