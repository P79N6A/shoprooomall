<?php
//SHOPROOO
namespace App\Services;

class ActivityService
{
	private $activityRepository;
	private $userRankRepository;
	private $categoryRepository;
	private $shopRepository;
	private $goodsRepository;
	private $shopConfigRepository;
	private $root_url;

	public function __construct(\App\Repositories\Activity\ActivityRepository $activityRepository, \App\Repositories\User\UserRankRepository $userRankRepository, \App\Repositories\Category\CategoryRepository $categoryRepository, \App\Repositories\Shop\ShopRepository $shopRepository, \App\Repositories\Goods\GoodsRepository $goodsRepository, \App\Repositories\ShopConfig\ShopConfigRepository $shopConfigRepository, \Illuminate\Http\Request $request)
	{
		$this->activityRepository = $activityRepository;
		$this->userRankRepository = $userRankRepository;
		$this->categoryRepository = $categoryRepository;
		$this->shopRepository = $shopRepository;
		$this->goodsRepository = $goodsRepository;
		$this->shopConfigRepository = $shopConfigRepository;
		$this->root_url = dirname(dirname($request->root())) . '/';
	}

	public function activityList()
	{
		$activity_list = $this->activityRepository->activityList();
		$list = array();

		foreach ($activity_list as $row) {
			$row['activity_thumb'] = get_image_path($row['activity_thumb']);
			$row['status'] = $this->activityRepository->getStatus($row['start_time'], $row['end_time']);
			$row['start_time'] = local_date('Y-m-d H:i', $row['start_time']);
			$row['end_time'] = local_date('Y-m-d H:i', $row['end_time']);
			$row['actType'] = $row['act_type'];

			switch ($row['act_type']) {
			case 0:
				$row['act_type'] = '享受赠品';
				$row['activity_name'] = '消费满' . $row['min_amount'] . '享受赠品';
				break;

			case 1:
				$row['act_type'] = '享受现金减免';
				$row['act_type_ext'] .= '元';
				$row['activity_name'] = '消费满' . $row['min_amount'] . '现金减免' . $row['act_type_ext'];
				break;

			case 2:
				$row['act_type'] = '享受价格折扣';
				$row['act_type_ext'] .= '%';
				$row['activity_name'] = '消费满' . $row['min_amount'] . '享受折扣' . $row['act_type_ext'] / 10 . '折';
				break;
			}

			$list[$row['actType']]['activity_list'][] = $row;
		}

		return $list;
	}

	public function detail($id = 0)
	{
		$user_rank_list = array();
		$user_rank_list[0] = '非会员';
		$res = $this->userRankRepository->getUserRank();

		foreach ($res as $row) {
			$user_rank_list[$row['rank_id']] = $row['rank_name'];
		}

		$row = $this->activityRepository->detail($id);
		$user_rank = explode(',', $row['user_rank']);
		$row['user_rank'] = array();

		foreach ($user_rank as $val) {
			if (isset($user_rank_list[$val])) {
				$row['user_rank'][] = $user_rank_list[$val];
			}
		}

		$row['status'] = $this->activityRepository->getStatus($row['start_time'], $row['end_time']);
		$row['start_time'] = local_date('Y-m-d H:i', $row['start_time']);
		$row['end_time'] = local_date('Y-m-d H:i', $row['end_time']);
		$row['activity_thumb'] = get_image_path($row['activity_thumb']);
		$row['act_range_type'] = $row['act_range'];
		$row['actType'] = $row['act_type'];
		if ($row['act_range'] != FAR_ALL && !empty($row['act_range_ext'])) {
			if ($row['act_range'] == FAR_CATEGORY) {
				$row['act_range'] = '以下分类';
			}
			else if ($row['act_range'] == FAR_BRAND) {
				$row['act_range'] = '以下品牌';
			}
			else {
				$row['act_range'] = '以下商品';
			}
		}
		else {
			$row['act_range'] = '全部商品';
		}

		switch ($row['act_type']) {
		case 0:
			$row['act_type'] = '享受赠品';
			$row['gift'] = unserialize($row['gift']);

			if (is_array($row['gift'])) {
				foreach ($row['gift'] as $k => $v) {
					$goods_info = $this->goodsRepository->find($v['id']);
					$row['gift'][$k]['thumb'] = get_image_path($goods_info['goods_thumb']);
					$row['gift'][$k]['price'] = price_format($v['price'], false);
				}
			}

			$row['activity_name'] = '消费满' . $row['min_amount'] . '享受赠品';
			break;

		case 1:
			$row['act_type'] = '享受现金减免';
			$row['act_type_ext'] .= '元';
			$row['activity_name'] = '消费满' . $row['min_amount'] . '现金减免' . $row['act_type_ext'];
			$row['gift'] = array();
			break;

		case 2:
			$row['act_type'] = '享受价格折扣';
			$row['act_type_ext'] .= '%';
			$row['activity_name'] = '消费满' . $row['min_amount'] . '享受折扣' . $row['act_type_ext'] / 10 . '折';
			$row['gift'] = array();
			break;
		}

		return $row;
	}

	public function activityGoods($act_id = 0, $page = 1, $size = 10)
	{
		$row = $this->activityRepository->detail($act_id);
		$goods_list = array();
		$filter = array();
		if ($row['act_range'] != FAR_ALL && !empty($row['act_range_ext'])) {
			if ($row['act_range'] == FAR_CATEGORY) {
				$cat_str = '';
				$cat_rows = explode(',', $row['act_range_ext']);

				if ($cat_rows) {
					foreach ($cat_rows as $v) {
						$cat_children = array_unique(array_merge(array($v), $this->categoryRepository->arr_foreach($this->categoryRepository->catList($v))));

						if ($cat_children) {
							$cat_str .= implode(',', $cat_children) . ',';
						}
					}
				}

				if ($cat_str) {
					$cat_str = substr($cat_str, 0, -1);
				}

				$filter['cat_ids'] = $cat_str;
			}
			else if ($row['act_range'] == FAR_BRAND) {
				$filter['brand_ids'] = $row['act_range_ext'];
			}
			else {
				$filter['goods_ids'] = $row['act_range_ext'];
			}
		}

		if ($row['userFav_type'] == 0) {
			$filter['user_id'] = $row['user_id'];
		}

		$goods_list = $this->activityRepository->activityGoods($filter, $page, $size);
		$list = array();

		foreach ($goods_list as $key => $row) {
			$list[$key]['goods_id'] = $row['goods_id'];
			$list[$key]['goods_name'] = $row['goods_name'];
			$list[$key]['goods_thumb'] = get_image_path($row['goods_thumb']);
			$list[$key]['goods_img'] = get_image_path($row['goods_img']);
			$list[$key]['shop_price'] = price_format($row['shop_price']);
			$list[$key]['market_price'] = price_format($row['market_price']);
			$list[$key]['goods_number'] = $row['goods_number'];
			$list[$key]['sales_volume'] = $row['sales_volume'];
		}

		return $list;
	}

	public function coudan($user_id = 0, $act_id = 0)
	{
		$result['activity_type'] = $this->activityRepository->getActivitytype($act_id);
		$cart_fav_goods = $this->activityRepository->cartFavourableGoods($user_id, $act_id);
		$cart_fav_num = 0;
		$cart_fav_total = 0;

		foreach ($cart_fav_goods as $key => $row) {
			$cart_fav_num += $row['goods_number'];
			$cart_fav_total += $row['shop_price'] * $row['goods_number'];
		}

		$result['num'] = $cart_fav_num;
		$result['total'] = $cart_fav_total;
		$result['act_id '] = $act_id;
		return $result;
	}
}


?>
