<?php
//SHOPROOO
namespace App\Repositories\Category;

class CategoryRepository
{
	public function getAllCategorys($uid)
	{
		$category_list = \Illuminate\Support\Facades\Cache::get('category_list');

		if (empty($category_list)) {
			$category_list = $this->getTree($uid, 0);
			\Illuminate\Support\Facades\Cache::put('category_list', $category_list, 60);
		}

		return $category_list;
	}

	public function getDrpCategorys($uid)
	{
		$category_list = $this->getTree($uid, 0);
		return $category_list;
	}

	public function getCategoryGetGoods($id)
	{
		$goods = \App\Models\Goods::select('goods_id', 'goods_sn', 'goods_name')->where('cat_id', $id)->get()->toArray();
		return $goods;
	}

	private function getTree($uid, $tree_id = 0, $top = 0)
	{
		$three_arr = array();
		$count = \App\Models\Category::where('parent_id', $tree_id)->where('is_show', 1)->count();
		if (0 < $count || $tree_id == 0) {
			$res = \App\Models\Category::select('cat_id', 'cat_name', 'touch_icon', 'parent_id', 'cat_alias_name', 'is_show')->where('parent_id', $tree_id)->where('is_show', 1)->with(array('goods' => function($query) {
				$query->select('goods_id', 'cat_id', 'goods_thumb')->where('is_on_sale', 1)->where('is_delete', 0)->orderBy('sort_order', 'ASC')->orderBy('goods_id', 'DESC');
			}))->orderBy('sort_order', 'ASC')->orderBy('cat_id', 'ASC')->get()->toArray();

			foreach ($res as $k => $row) {
				if ($row['is_show']) {
					$type = \App\Models\DrpType::select('id')->where('cat_id', $row['cat_id'])->where('user_id', $uid)->first();

					if (!empty($type)) {
						$three_arr[$k]['drp_type'] = true;
					}
					else {
						$three_arr[$k]['drp_type'] = false;
					}

					$three_arr[$k]['id'] = $row['cat_id'];
					$three_arr[$k]['name'] = $row['cat_alias_name'] ? $row['cat_alias_name'] : $row['cat_name'];

					if (isset($row['goods'][0]['goods_thumb'])) {
						$three_arr[$k]['cat_img'] = !empty($row['touch_icon']) ? get_image_path($row['touch_icon']) : get_image_path($row['goods'][0]['goods_thumb']);
					}
					else {
						$three_arr[$k]['cat_img'] = !empty($row['touch_icon']) ? get_image_path($row['touch_icon']) : get_image_path();
					}

					$three_arr[$k]['haschild'] = 0;
				}

				if (isset($row['cat_id'])) {
					$child_tree = $this->getTree($uid, $row['cat_id']);

					if ($child_tree) {
						$three_arr[$k]['cat_id'] = $child_tree;
						$three_arr[$k]['haschild'] = 1;
					}
				}
			}
		}

		return $three_arr;
	}

	public function catList($cat_id = 0)
	{
		$arr = array();
		$count = \App\Models\Category::where('parent_id', $cat_id)->where('is_show', 1)->count();

		if (0 < $count) {
			$res = \App\Models\Category::select('cat_id', 'cat_name', 'touch_icon', 'parent_id', 'cat_alias_name', 'is_show')->where('parent_id', $cat_id)->where('is_show', 1)->orderBy('sort_order', 'ASC')->orderBy('cat_id', 'ASC')->get()->toArray();

			if ($res === null) {
				return array();
			}

			foreach ($res as $key => $row) {
				$arr[$row['cat_id']]['cat_id'] = $row['cat_id'];

				if (isset($row['cat_id'])) {
					$child_tree = $this->catList($row['cat_id']);

					if ($child_tree) {
						$arr[$row['cat_id']]['child_tree'] = $child_tree;
					}
				}
			}
		}

		return $arr;
	}

	public function arr_foreach($arr)
	{
		$tmp = array();

		if (!is_array($arr)) {
			return false;
		}

		foreach ($arr as $val) {
			if (is_array($val)) {
				$tmp = array_merge($tmp, $this->arr_foreach($val));
			}
			else {
				$tmp[] = $val;
			}
		}

		return $tmp;
	}
}


?>
