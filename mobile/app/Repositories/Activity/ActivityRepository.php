<?php
//SHOPROOO
namespace App\Repositories\Activity;

class ActivityRepository
{
	protected $goods;
	private $field;
	private $authService;
	private $goodsAttrRepository;
	private $shopConfigRepository;
	private $userRankRepository;
	private $goodsRepository;
	private $userRepository;
	private $categoryRepository;

	public function __construct(\App\Services\AuthService $authService, \App\Repositories\Goods\GoodsAttrRepository $goodsAttrRepository, \App\Repositories\ShopConfig\ShopConfigRepository $shopConfigRepository, \App\Repositories\User\UserRankRepository $userRankRepository, \App\Repositories\Goods\GoodsRepository $goodsRepository, \App\Repositories\User\UserRepository $userRepository, \App\Repositories\Category\CategoryRepository $categoryRepository)
	{
		$this->authService = $authService;
		$this->goodsAttrRepository = $goodsAttrRepository;
		$this->shopConfigRepository = $shopConfigRepository;
		$this->userRankRepository = $userRankRepository;
		$this->goodsRepository = $goodsRepository;
		$this->userRepository = $userRepository;
		$this->categoryRepository = $categoryRepository;
	}

	public function activityList()
	{
		$list = \App\Models\FavourableActivity::select('*')->where('review_status', 3)->orderBy('sort_order', 'ASC')->orderBy('end_time', 'DESC')->get()->toArray();

		if ($list === null) {
			return array();
		}

		return $list;
	}

	public function activityListAll($ru_id = 0)
	{
		$gmtime = gmtime();
		$favourable_list = array();
		$shopconfig = app('App\\Repositories\\ShopConfig\\ShopConfigRepository');
		$timeFormat = $shopconfig->getShopConfigByCode('time_format');
		$user_rank = $this->userRankRepository->getUserRankByUid();
		$user_rank = ',' . $user_rank['rank_id'] . ',';
		$activity = \App\Models\FavourableActivity::select('*')->where('start_time', '<=', $gmtime)->where('end_time', '>=', $gmtime);

		if (0 < $ru_id) {
			$activity->Where('user_id', '=', $ru_id);
			$activity->orWhere('userFav_type', '=', 1);
		}
		else {
			$activity->where('user_id', $ru_id);
		}

		$list = $activity->where('review_status', 3)->whereraw('CONCAT(\',\', user_rank, \',\') LIKE \'%' . $user_rank . '%\'')->get()->toArray();

		if ($list === null) {
			return array();
		}

		return $list;
	}

	public function activityRangeExt($ru_id = 0, $act_range)
	{
		$gmtime = gmtime();
		$user_rank = $this->userRankRepository->getUserRankByUid();
		$user_rank = ',' . $user_rank['rank_id'] . ',';
		$activity = \App\Models\FavourableActivity::select('*');

		if (0 < $ru_id) {
			$activity->Where('user_id', '=', $ru_id);
			$activity->orWhere('userFav_type', '=', 1);
		}
		else {
			$activity->where('user_id', $ru_id);
		}

		$res = $activity->where('review_status', 3)->where('start_time', '<=', $gmtime)->where('end_time', '>=', $gmtime)->where('act_range', $act_range)->whereraw('CONCAT(\',\', user_rank, \',\') LIKE \'%' . $user_rank . '%\'')->get()->toArray();

		if ($res === null) {
			return array();
		}

		$arr = array();

		foreach ($res as $key => $row) {
			$id_list = explode(',', $row['act_range_ext']);
			$arr = array_merge($arr, $id_list);
		}

		return array_unique($arr);
	}

	public function returnActRangeExt($act_range_ext, $userFav_type, $act_range)
	{
		if ($act_range_ext) {
			if ($userFav_type == 1 && $act_range == FAR_BRAND) {
				$id_list = explode(',', $act_range_ext);
				$brand_sql = 'SELECT brand_id FROM ' . $GLOBALS['ecs']->table('brand') . ' WHERE brand_id ' . db_create_in($id_list);
				$brand = $GLOBALS['db']->getCol($brand_sql);
				$id_list = !empty($brand) ? array_merge($id_list, $brand) : '';
				$id_list = array_unique($id_list);
				$act_range_ext = implode(',', $id_list);
			}
		}

		return $act_range_ext;
	}

	public function detail($id = 0)
	{
		$list = \App\Models\FavourableActivity::select('*')->where('review_status', 3)->where('act_id', $id)->first()->toArray();

		if ($list === null) {
			return array();
		}

		return $list;
	}

	public function activityGoods($filter = array('goods_ids' => '', 'cat_ids' => '', 'brand_ids' => '', 'user_id' => 0), $page, $size)
	{
		$begin = ($page - 1) * $size;
		$goods = \App\Models\Goods::from('goods as g')->select('*');

		if (!empty($filter['cat_ids'])) {
			$cat_id = explode(',', $filter['cat_ids']);
			$goods->wherein('g.cat_id', $cat_id);
		}

		if (isset($filter['brand_ids']) && !empty($filter['brand_ids'])) {
			$goods->leftjoin('brand as b', 'b.brand_id', '=', 'g.brand_id');
			$brand_id = explode(',', $filter['brand_ids']);
			$goods->wherein('g.brand_id', $brand_id);
		}

		if (isset($filter['goods_ids']) && !empty($filter['goods_ids'])) {
			$goods_id = explode(',', $filter['goods_ids']);
			$goods->wherein('g.goods_id', $goods_id);
		}

		if (isset($filter['user_id'])) {
			$goods->where('g.user_id', $filter['user_id']);
		}

		$list = $goods->where('g.is_on_sale', 1)->where('g.review_status', 1)->where('g.is_alone_sale', 1)->where('g.is_delete', 0)->offset($begin)->orderBy('g.sort_order', 'ASC')->limit($size)->get()->toArray();

		if ($list === null) {
			return array();
		}

		return $list;
	}

	public function getStatus($starttime, $endtime)
	{
		$nowtime = gmtime();
		if (!empty($starttime) && !empty($endtime)) {
			if ($nowtime < $starttime) {
				$result = 0;
			}
			else {
				if ($starttime < $nowtime && $nowtime < $endtime) {
					$result = 1;
				}
				else if ($endtime < $nowtime) {
					$result = 2;
				}
			}

			return $result;
		}

		return 0;
	}

	public function getActivitytype($act_id = 0)
	{
		$gmtime = gmtime();
		$user_rank = $this->userRankRepository->getUserRankByUid();
		$user_rank = ',' . $user_rank['rank_id'] . ',';
		$activity = \App\Models\FavourableActivity::select('*')->where('review_status', 3)->where('act_id', $act_id)->where('start_time', '<=', $gmtime)->where('end_time', '>=', $gmtime)->whereraw('CONCAT(\',\', user_rank, \',\') LIKE \'%' . $user_rank . '%\'')->first()->toArray();

		if ($activity === null) {
			return array();
		}

		$row = array();

		switch ($activity['act_type']) {
		case 0:
			$row['act_type'] = '满赠';
			$row['act_name'] = ' 满 ' . $activity['min_amount'] . ' 元可换购赠品';
			break;

		case 1:
			$row['act_type'] = '满减';
			$row['act_name'] = ' 满 ' . $activity['min_amount'] . ' 元可享受减免 ' . $activity['act_type_ext'] . ' 元 ';
			break;

		case 2:
			$row['act_type'] = '折扣';
			$row['act_name'] = ' 满 ' . $activity['min_amount'] . ' 元可享受折扣 ';
			break;

		default:
			break;
		}

		return $row;
	}

	public function cartFavourableGoods($user_id = 0, $act_id = 0)
	{
		$gmtime = gmtime();
		$user_rank = $this->userRankRepository->getUserRankByUid();
		$user_rank = ',' . $user_rank['rank_id'] . ',';
		$favourable = \App\Models\FavourableActivity::select('*')->where('review_status', 3)->where('act_id', $act_id)->where('start_time', '<=', $gmtime)->where('end_time', '>=', $gmtime)->whereraw('CONCAT(\',\', user_rank, \',\') LIKE \'%' . $user_rank . '%\'')->first()->toArray();
		$prefix = \Illuminate\Support\Facades\Config::get('database.connections.mysql.prefix');
		$sql = 'SELECT c.rec_id, c.goods_number, g.goods_id, g.goods_thumb, g.goods_name, c.goods_price AS shop_price ' . ' FROM ' . $prefix . 'cart AS c, ' . $prefix . 'goods AS g ' . ' WHERE c.goods_id = g.goods_id ' . (' AND c.user_id = ' . $user_id . ' AND c.rec_type = \'') . CART_GENERAL_GOODS . '\' ' . ' AND c.is_gift = 0 ' . ' AND c.goods_id > 0 ';
		$id_list = array();
		$list = array();

		if ($favourable) {
			if ($favourable['act_range'] == FAR_ALL) {
			}
			else if ($favourable['act_range'] == FAR_CATEGORY) {
				$cat_list = explode(',', $favourable['act_range_ext']);

				foreach ($cat_list as $id) {
					$cat_list = $this->categoryRepository->arr_foreach($this->categoryRepository->catList($id));
					$id_list = array_merge($id_list, $cat_list);
					array_unshift($id_list, $id);
				}

				$id_list = join(',', array_unique($id_list));
				$sql .= 'AND g.cat_id in (' . $id_list . ')';
			}
			else if ($favourable['act_range'] == FAR_BRAND) {
				$id_list = $favourable['act_range_ext'];
				$sql .= 'AND g.brand_id in (' . $id_list . ')';
			}
			else if ($favourable['act_range'] == FAR_GOODS) {
				$id_list = $favourable['act_range_ext'];
				$sql .= 'AND g.goods_id in (' . $id_list . ')';
			}

			$res = \Illuminate\Support\Facades\DB::select($sql);

			foreach ($res as $key => $row) {
				$list[$key] = get_object_vars($row);
				$list[$key]['rec_id'] = $list[$key]['rec_id'];
				$list[$key]['goods_id'] = $list[$key]['goods_id'];
				$list[$key]['goods_name'] = $list[$key]['goods_name'];
				$list[$key]['goods_thumb'] = get_image_path($list[$key]['goods_thumb']);
				$list[$key]['shop_price'] = number_format($list[$key]['shop_price'], 2, '.', '');
				$list[$key]['goods_number'] = $list[$key]['goods_number'];
			}
		}

		return $list;
	}
}


?>
