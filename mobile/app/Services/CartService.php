<?php
//SHOPROOO 禁止倒卖 一经发现停止任何服务
namespace App\Services;

class CartService
{
	private $cartRepository;
	private $goodsRepository;
	private $authService;
	private $goodsAttrRepository;
	private $activityRepository;
	private $userRankRepository;
	private $categoryRepository;
	private $goodsService;

	public function __construct(\App\Repositories\Cart\CartRepository $cartRepository, \App\Repositories\Goods\GoodsRepository $goodsRepository, AuthService $authService, \App\Repositories\Goods\GoodsAttrRepository $goodsAttrRepository, \App\Repositories\Activity\ActivityRepository $activityRepository, \App\Repositories\User\UserRankRepository $userRankRepository, \App\Repositories\Category\CategoryRepository $categoryRepository, GoodsService $goodsService)
	{
		$this->cartRepository = $cartRepository;
		$this->goodsRepository = $goodsRepository;
		$this->authService = $authService;
		$this->goodsAttrRepository = $goodsAttrRepository;
		$this->activityRepository = $activityRepository;
		$this->userRankRepository = $userRankRepository;
		$this->categoryRepository = $categoryRepository;
		$this->goodsService = $goodsService;
	}

	public function getCart()
	{
		$cart = $this->getCartGoods();
		$merchant_goods_list = $this->cartByFavourable($cart['goods_list']);
		$result = array();
		$result['cart_list'] = $merchant_goods_list;
		$result['total'] = array_map('strip_tags', $cart['total']);
		$result['best_goods'] = $this->getBestGoods();
		return $result;
	}

	private function getCartGoods()
	{
		$userId = $this->authService->authorization();
		$list = $this->cartRepository->getGoodsInCartByUser($userId);
		return $list;
	}

	private function cartByFavourable($merchant_goods)
	{
		$id_list = array();
		$list_array = array();

		foreach ($merchant_goods as $key => $row) {
			$user_cart_goods = isset($row['goods']) && !empty($row['goods']) ? $row['goods'] : array();
			$favourable_list = $this->favourable_list($row['user_id'], $row['ru_id']);
			$sort_favourable = $this->sort_favourable($favourable_list);

			if ($user_cart_goods) {
				foreach ($user_cart_goods as $key1 => $row1) {
					$row1['market_price_formated'] = price_format($row1['market_price'], false);
					$row1['goods_price_formated'] = price_format($row1['goods_price'], false);
					$row1['goods_thumb'] = get_image_path($row1['goods_thumb']);
					$row1['original_price'] = $row1['goods_price'] * $row1['goods_number'];
					if (isset($sort_favourable['by_all']) && $row1['extension_code'] != 'package_buy' && substr($row1['extension_code'], 0, 7) != 'seckill') {
						foreach ($sort_favourable['by_all'] as $key2 => $row2) {
							$mer_ids = true;
							if ($row2['userFav_type'] == 1 || $mer_ids) {
								if ($row1['is_gift'] == 0) {
									if (isset($row1) && $row1) {
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_id'] = $row2['act_id'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_name'] = $row2['act_name'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type'] = $row2['act_type'];

										switch ($row2['act_type']) {
										case 0:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满赠';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = intval($row2['act_type_ext']);
											break;

										case 1:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满减';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = number_format($row2['act_type_ext'], 2);
											break;

										case 2:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '折扣';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = floatval($row2['act_type_ext'] / 10);
											break;

										default:
											break;
										}

										$merchant_goods[$key]['new_list'][$row2['act_id']]['min_amount'] = $row2['min_amount'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext'] = intval($row2['act_type_ext']);
										@$merchant_goods[$key]['new_list'][$row2['act_id']]['cart_fav_amount'] += $row1['subtotal'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['available'] = $this->favourableAvailable($row['user_id'], $row2, array(), $row1['ru_id']);
										$cart_favourable = $this->cartRepository->cartFavourable($row['user_id'], $row1['ru_id']);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['favourable_used'] = $this->favourableUsed($row2, $cart_favourable);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['left_gift_num'] = intval($row2['act_type_ext']) - (empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]));

										if ($row2['gift']) {
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_gift_list'] = $row2['gift'];
										}

										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_goods_list'][$row1['rec_id']] = $row1;
										unset($row1);
									}
								}
								else {
									$merchant_goods[$key]['new_list'][$row2['act_id']]['act_cart_gift'][$row1['rec_id']] = $row1;
								}
							}
							else if ($GLOBALS['_CFG']['region_store_enabled']) {
								$merchant_goods[$key]['new_list'][0]['act_goods_list'][$row1['rec_id']] = $row1;
							}

							break;
						}

						continue;
					}

					if (isset($sort_favourable['by_category']) && $row1['extension_code'] != 'package_buy' && substr($row1['extension_code'], 0, 7) != 'seckill') {
						$get_act_range_ext = $this->activityRepository->activityRangeExt($row['ru_id'], 1);
						$str_cat = '';

						foreach ($get_act_range_ext as $id) {
							$cat_keys = $this->categoryRepository->arr_foreach($this->categoryRepository->catList(intval($id)));

							if ($cat_keys) {
								$str_cat .= implode(',', $cat_keys);
							}
						}

						if ($str_cat) {
							$list_array = explode(',', $str_cat);
						}

						$list_array = !empty($list_array) ? array_merge($get_act_range_ext, $list_array) : $get_act_range_ext;
						$id_list = $this->categoryRepository->arr_foreach($list_array);
						$id_list = array_unique($id_list);
						$cat_id = $row1['cat_id'];
						$favourable_id_list = $this->getFavourableId($sort_favourable['by_category']);
						if (in_array($cat_id, $id_list) && $row1['is_gift'] == 0 || in_array($row1['is_gift'], $favourable_id_list)) {
							foreach ($sort_favourable['by_category'] as $key2 => $row2) {
								if (isset($row1) && $row1) {
									$fav_act_range_ext = !empty($row2['act_range_ext']) ? explode(',', $row2['act_range_ext']) : array();

									foreach ($fav_act_range_ext as $id) {
										$cat_keys = $this->categoryRepository->arr_foreach($this->categoryRepository->catList(intval($id)));
										$fav_act_range_ext = array_merge($fav_act_range_ext, $cat_keys);
									}

									if ($row1['is_gift'] == 0 && in_array($cat_id, $fav_act_range_ext)) {
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_id'] = $row2['act_id'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_name'] = $row2['act_name'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type'] = $row2['act_type'];

										switch ($row2['act_type']) {
										case 0:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满赠';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = intval($row2['act_type_ext']);
											break;

										case 1:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满减';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = number_format($row2['act_type_ext'], 2);
											break;

										case 2:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '折扣';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = floatval($row2['act_type_ext'] / 10);
											break;

										default:
											break;
										}

										$merchant_goods[$key]['new_list'][$row2['act_id']]['min_amount'] = $row2['min_amount'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext'] = intval($row2['act_type_ext']);
										@$merchant_goods[$key]['new_list'][$row2['act_id']]['cart_fav_amount'] += $row1['subtotal'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['available'] = $this->favourableAvailable($row['user_id'], $row2, array(), $row1['ru_id']);
										$cart_favourable = $this->cartRepository->cartFavourable($row['user_id'], $row1['ru_id']);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['favourable_used'] = $this->favourableUsed($row2, $cart_favourable);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['left_gift_num'] = intval($row2['act_type_ext']) - (empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]));

										if ($row2['gift']) {
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_gift_list'] = $row2['gift'];
										}

										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_goods_list'][$row1['rec_id']] = $row1;
										unset($row1);
									}

									if (isset($row1) && $row1 && $row1['is_gift'] == $row2['act_id']) {
										if ($this->favourableAvailable($row['user_id'], $row2, array(), $row1['ru_id'])) {
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_cart_gift'][$row1['rec_id']] = $row1;
										}
										else {
											$is_gift = \App\Models\Cart::where('user_id', $row['user_id'])->where('is_gift', $row2['act_id'])->count();

											if ($is_gift) {
												\App\Models\Cart::where('user_id', $row['user_id'])->where('is_gift', $row2['act_id'])->delete();
											}
										}
									}
								}
							}

							continue;
						}
					}

					if (isset($sort_favourable['by_brand']) && $row1['extension_code'] != 'package_buy' && substr($row1['extension_code'], 0, 7) != 'seckill') {
						$get_act_range_ext = $this->activityRepository->activityRangeExt($row['ru_id'], 2);
						$brand_id = $row1['brand_id'];
						$favourable_id_list = $this->getFavourableId($sort_favourable['by_brand']);
						if (in_array(trim($brand_id), $get_act_range_ext) && $row1['is_gift'] == 0 || in_array($row1['is_gift'], $favourable_id_list)) {
							foreach ($sort_favourable['by_brand'] as $key2 => $row2) {
								$act_range_ext_str = ',' . $row2['act_range_ext'] . ',';
								$brand_id_str = ',' . $brand_id . ',';
								if (isset($row1) && $row1) {
									if ($row1['is_gift'] == 0 && strstr($act_range_ext_str, trim($brand_id_str))) {
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_id'] = $row2['act_id'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_name'] = $row2['act_name'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type'] = $row2['act_type'];

										switch ($row2['act_type']) {
										case 0:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满赠';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = intval($row2['act_type_ext']);
											break;

										case 1:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满减';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = number_format($row2['act_type_ext'], 2);
											break;

										case 2:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '折扣';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = floatval($row2['act_type_ext'] / 10);
											break;

										default:
											break;
										}

										$merchant_goods[$key]['new_list'][$row2['act_id']]['min_amount'] = $row2['min_amount'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext'] = intval($row2['act_type_ext']);
										@$merchant_goods[$key]['new_list'][$row2['act_id']]['cart_fav_amount'] += $row1['subtotal'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['available'] = $this->favourableAvailable($row['user_id'], $row2);
										$cart_favourable = $this->cartRepository->cartFavourable($row['user_id'], $row1['ru_id']);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['favourable_used'] = $this->favourableUsed($row2, $cart_favourable);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['left_gift_num'] = intval($row2['act_type_ext']) - (empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]));

										if ($row2['gift']) {
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_gift_list'] = $row2['gift'];
										}

										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_goods_list'][$row1['rec_id']] = $row1;
										unset($row1);
									}

									if (isset($row1) && $row1 && $row1['is_gift'] == $row2['act_id']) {
										if ($this->favourableAvailable($row['user_id'], $row2, array(), $row1['ru_id'])) {
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_cart_gift'][$row1['rec_id']] = $row1;
										}
										else {
											$is_gift = \App\Models\Cart::where('user_id', $row['user_id'])->where('is_gift', $row2['act_id'])->count();

											if ($is_gift) {
												\App\Models\Cart::where('user_id', $row['user_id'])->where('is_gift', $row2['act_id'])->delete();
											}
										}
									}
								}
							}

							continue;
						}
					}

					if (isset($sort_favourable['by_goods']) && $row1['extension_code'] != 'package_buy' && substr($row1['extension_code'], 0, 7) != 'seckill') {
						$get_act_range_ext = $this->activityRepository->activityRangeExt($row['ru_id'], 3);
						$favourable_id_list = $this->getFavourableId($sort_favourable['by_goods']);
						if (in_array($row1['goods_id'], $get_act_range_ext) || in_array($row1['is_gift'], $favourable_id_list)) {
							foreach ($sort_favourable['by_goods'] as $key2 => $row2) {
								$act_range_ext_str = ',' . $row2['act_range_ext'] . ',';
								$goods_id_str = ',' . $row1['goods_id'] . ',';
								if (isset($row1) && $row1) {
									if (strstr($act_range_ext_str, $goods_id_str) && $row1['is_gift'] == 0) {
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_id'] = $row2['act_id'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_name'] = $row2['act_name'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type'] = $row2['act_type'];

										switch ($row2['act_type']) {
										case 0:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满赠';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = intval($row2['act_type_ext']);
											break;

										case 1:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满减';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = number_format($row2['act_type_ext'], 2);
											break;

										case 2:
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '折扣';
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = floatval($row2['act_type_ext'] / 10);
											break;

										default:
											break;
										}

										$merchant_goods[$key]['new_list'][$row2['act_id']]['min_amount'] = $row2['min_amount'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext'] = intval($row2['act_type_ext']);
										@$merchant_goods[$key]['new_list'][$row2['act_id']]['cart_fav_amount'] += $row1['subtotal'];
										$merchant_goods[$key]['new_list'][$row2['act_id']]['available'] = $this->favourableAvailable($row['user_id'], $row2);
										$cart_favourable = $this->cartRepository->cartFavourable($row['user_id'], $row1['ru_id']);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['favourable_used'] = $this->favourableUsed($row2, $cart_favourable);
										$merchant_goods[$key]['new_list'][$row2['act_id']]['left_gift_num'] = intval($row2['act_type_ext']) - (empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]));

										if ($row2['gift']) {
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_gift_list'] = $row2['gift'];
										}

										$merchant_goods[$key]['new_list'][$row2['act_id']]['act_goods_list'][$row1['rec_id']] = $row1;
										break;
										unset($row1);
									}

									if (isset($row1) && $row1 && $row1['is_gift'] == $row2['act_id']) {
										if ($this->favourableAvailable($row['user_id'], $row2, array(), $row1['ru_id'])) {
											$merchant_goods[$key]['new_list'][$row2['act_id']]['act_cart_gift'][$row1['rec_id']] = $row1;
										}
										else {
											$is_gift = \App\Models\Cart::where('user_id', $row['user_id'])->where('is_gift', $row2['act_id'])->count();

											if ($is_gift) {
												\App\Models\Cart::where('user_id', $row['user_id'])->where('is_gift', $row2['act_id'])->delete();
											}
										}
									}
								}
							}
						}
						else {
							$merchant_goods[$key]['new_list'][0]['act_goods_list'][$row1['rec_id']] = $row1;
						}
					}
					else {
						$merchant_goods[$key]['new_list'][0]['act_goods_list'][$row1['rec_id']] = $row1;
					}
				}
			}
		}

		foreach ($merchant_goods as $key => $val) {
			if ($val['new_list']) {
				foreach ($val['new_list'] as $k => $v) {
					$val['new_list'][$k]['act_goods_list'] = collect($v['act_goods_list'])->values()->all();

					if (isset($v['act_cart_gift'])) {
						$val['new_list'][$k]['act_cart_gift'] = collect($v['act_cart_gift'])->values()->all();
					}
				}

				$merchant_goods[$key]['new_list'] = collect($val['new_list'])->values()->all();
			}
		}

		$merchant_goods = collect($merchant_goods)->values()->all();
		return $merchant_goods;
	}

	public function favourableAvailable($user_id, $favourable, $act_sel_id = array(), $ru_id = -1)
	{
		$user_rank = $this->userRankRepository->getUserRankByUid();

		if (strpos(',' . $favourable['user_rank'] . ',', ',' . $user_rank['rank_id'] . ',') === false) {
			return false;
		}

		$amount = $this->cartRepository->cartFavourableAmount($user_id, $favourable, $act_sel_id, $ru_id);
		return $favourable['min_amount'] <= $amount && ($amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0);
	}

	public function favourableUsed($favourable, $cart_favourable)
	{
		if ($favourable['act_type'] == FAT_GOODS) {
			return isset($cart_favourable[$favourable['act_id']]) && $favourable['act_type_ext'] <= $cart_favourable[$favourable['act_id']] && 0 < $favourable['act_type_ext'];
		}
		else {
			return isset($cart_favourable[$favourable['act_id']]);
		}
	}

	public function favourable_list($user_id = 0, $ru_id = 0, $act_sel_id = array())
	{
		$shopconfig = app('App\\Repositories\\ShopConfig\\ShopConfigRepository');
		$timeFormat = $shopconfig->getShopConfigByCode('time_format');
		$list = $this->activityRepository->activityListAll($ru_id);
		$used_list = $this->cartRepository->cartFavourable($user_id, $ru_id);
		$favourable_list = array();

		if ($list) {
			foreach ($list as $favourable) {
				$favourable['start_time'] = local_date($timeFormat, $favourable['start_time']);
				$favourable['end_time'] = local_date($timeFormat, $favourable['end_time']);
				$favourable['formated_min_amount'] = price_format($favourable['min_amount'], false);
				$favourable['formated_max_amount'] = price_format($favourable['max_amount'], false);
				$favourable['gift'] = unserialize($favourable['gift']);

				foreach ($favourable['gift'] as $key => $value) {
					$goods = $this->goodsRepository->find($value['id']);
					$cart_gift_num = $this->cartRepository->goodsNumInCartGift($user_id, $value['id']);

					if (!empty($goods)) {
						$favourable['gift'][$key]['ru_id'] = $favourable['user_id'];
						$favourable['gift'][$key]['act_id'] = $favourable['act_id'];
						$favourable['gift'][$key]['formated_price'] = price_format($value['price'], false);
						$favourable['gift'][$key]['thumb_img'] = get_image_path($goods['goods_thumb']);
						$favourable['gift'][$key]['is_checked'] = $cart_gift_num ? true : false;
					}
					else {
						unset($favourable['gift'][$key]);
					}
				}

				$favourable['available'] = $this->favourableAvailable($user_id, $favourable, $act_sel_id);

				if ($favourable['available']) {
					$favourable['available'] = !$this->favourableUsed($favourable, $used_list);
				}

				$favourable_list[] = $favourable;
			}
		}

		return $favourable_list;
	}

	public function sort_favourable($favourable_list)
	{
		$arr = array();

		foreach ($favourable_list as $key => $value) {
			switch ($value['act_range']) {
			case FAR_ALL:
				$arr['by_all'][$key] = $value;
				break;

			case FAR_CATEGORY:
				$arr['by_category'][$key] = $value;
				break;

			case FAR_BRAND:
				$arr['by_brand'][$key] = $value;
				break;

			case FAR_GOODS:
				$arr['by_goods'][$key] = $value;
				break;

			default:
				break;
			}
		}

		return $arr;
	}

	public function getFavourableId($favourable)
	{
		$arr = array();

		foreach ($favourable as $key => $value) {
			$arr[$key] = $value['act_id'];
		}

		return $arr;
	}

	private function getBestGoods()
	{
		$list = $this->goodsRepository->findByType('best');
		$bestGoods = array_map(function($v) {
			return array('goods_id' => $v['goods_id'], 'goods_name' => $v['goods_name'], 'market_price' => $v['market_price'], 'market_price_formated' => price_format($v['market_price'], false), 'shop_price' => $v['shop_price'], 'shop_price_formated' => price_format($v['shop_price'], false), 'goods_thumb' => get_image_path($v['goods_thumb']));
		}, $list);
		return $bestGoods;
	}

	public function addGoodsToCart($params)
	{
		$result = array('code' => 0, 'goods_number' => 0, 'total_number' => 0);
		$goods = $this->goodsRepository->find($params['id']);

		if ($goods['is_on_sale'] != 1) {
			return '商品已下架';
		}

		$goodsAttr = empty($params['attr_id']) ? array() : json_decode($params['attr_id'], 1);
		$goodsAttrId = implode(',', $goodsAttr);
		$product = $this->goodsRepository->getProductByGoods($params['id'], implode('|', $goodsAttr));

		if (empty($product)) {
			$product['id'] = 0;
		}

		$attrName = $this->goodsAttrRepository->getAttrNameById($goodsAttr);
		$attrNameStr = '';

		foreach ($attrName as $v) {
			$attrNameStr .= $v['attr_name'] . ':' . $v['attr_value'] . ' 
';
		}

		$goodsPrice = $this->goodsRepository->getFinalPrice($params['id'], $params['num'], 1, $goodsAttr);
		$cart = $this->cartRepository->getCartByGoods($params['uid'], $params['id'], $goodsAttrId);
		$cart_num = isset($cart['goods_number']) ? $cart['goods_number'] : 0;

		if ($goods['goods_number'] < $params['num'] + $cart_num) {
			return '库存不足';
		}

		if (!empty($cart)) {
			$goodsNumber = $params['num'] + $cart['goods_number'];
			$res = $this->cartRepository->update($params['uid'], $cart['rec_id'], $goodsNumber);

			if ($res) {
				$number = $this->cartRepository->goodsNumInCartByUser($params['uid']);
				$result['goods_number'] = $goodsNumber;
				$result['total_number'] = $number;
			}
		}
		else {
			$arguments = array('goods_id' => $params['id'], 'user_id' => $params['uid'], 'goods_sn' => $goods['goods_sn'], 'product_id' => empty($product['id']) ? '' : $product['id'], 'group_id' => '', 'goods_name' => $goods['goods_name'], 'market_price' => $goods['market_price'], 'goods_price' => $goodsPrice, 'goods_number' => $params['num'], 'goods_attr' => $attrNameStr, 'is_real' => $goods['is_real'], 'extension_code' => empty($params['extension_code']) ? '' : $params['extension_code'], 'parent_id' => 0, 'rec_type' => 0, 'is_gift' => 0, 'is_shipping' => $goods['is_shipping'], 'can_handsel' => '', 'model_attr' => $goods['model_attr'], 'goods_attr_id' => $goodsAttrId, 'ru_id' => $goods['user_id'], 'shopping_fee' => '', 'warehouse_id' => '', 'area_id' => '', 'add_time' => gmtime(), 'stages_qishu' => '-1', 'store_id' => '', 'freight' => '', 'tid' => '', 'shipping_fee' => '', 'store_mobile' => '', 'take_time' => '', 'is_checked' => 1);
			$goodsNumber = $this->cartRepository->addGoodsToCart($arguments);
			$number = $this->cartRepository->goodsNumInCartByUser($params['uid']);
			$result['goods_number'] = $goodsNumber;
			$result['total_number'] = $number;
		}

		return $result;
	}

	public function addGiftCart($params)
	{
		$result = array('error' => 0, 'message' => '');
		$select_gift = $params['select_gift'];
		$favourable = $this->activityRepository->detail($params['act_id']);

		if (!empty($favourable)) {
			$favourable['gift'] = unserialize($favourable['gift']);

			if ($favourable['act_type'] == FAT_GOODS) {
				$favourable['act_type_ext'] = round($favourable['act_type_ext']);
			}
		}
		else {
			$result['error'] = 1;
			$result['message'] = '您要加入购物车的优惠活动不存在';
			return $result;
		}

		if (!$this->favourableAvailable($params['uid'], $favourable)) {
			$result['error'] = 1;
			$result['message'] = '您不能享受该优惠';
			return $result;
		}

		$cart_favourable = $this->cartRepository->cartFavourable($params['uid'], $params['ru_id']);

		if ($this->favourableUsed($favourable, $cart_favourable)) {
			$result['error'] = 1;
			$result['message'] = '该优惠活动已加入购物车了';
			return $result;
		}

		if ($favourable['act_type'] == FAT_GOODS) {
			if (empty($params['select_gift'])) {
				$result['error'] = 1;
				$result['message'] = '请选择赠品（特惠品）';
				return $result;
			}

			$gift_name = array();
			$goodsname = $this->cartRepository->getGiftCart($params['uid'], $select_gift, $params['act_id']);

			foreach ($goodsname as $key => $value) {
				$gift_name[$key] = $value['goods_name'];
			}

			if (!empty($gift_name)) {
				$result['error'] = 1;
				$result['message'] = sprintf('您选择的赠品（特惠品）已经在购物车中了：%s', join(',', $gift_name));
				return $result;
			}

			$count = isset($cart_favourable[$params['act_id']]) ? $cart_favourable[$params['act_id']] : 0;
			if (0 < $favourable['act_type_ext'] && $favourable['act_type_ext'] < $count + count($select_gift)) {
				$result['error'] = 1;
				$result['message'] = '您选择的赠品（特惠品）数量超过上限了';
				return $result;
			}

			$success = false;

			foreach ($favourable['gift'] as $gift) {
				if (in_array($gift['id'], $select_gift)) {
					$goods = $this->goodsRepository->find($gift['id']);
					$arguments = array('goods_id' => $gift['id'], 'user_id' => $params['uid'], 'goods_sn' => $goods['goods_sn'], 'product_id' => empty($product['id']) ? '' : $product['id'], 'group_id' => '', 'goods_name' => $goods['goods_name'], 'market_price' => $goods['market_price'], 'goods_price' => $gift['price'], 'goods_number' => 1, 'goods_attr' => '', 'is_real' => $goods['is_real'], 'extension_code' => CART_GENERAL_GOODS, 'parent_id' => 0, 'rec_type' => 0, 'is_gift' => 1, 'is_shipping' => $goods['is_shipping'], 'can_handsel' => '', 'model_attr' => $goods['model_attr'], 'goods_attr_id' => '', 'ru_id' => $goods['user_id'], 'shopping_fee' => '', 'warehouse_id' => '', 'area_id' => '', 'add_time' => gmtime(), 'stages_qishu' => '-1', 'store_id' => '', 'freight' => '', 'tid' => '', 'shipping_fee' => '', 'store_mobile' => '', 'take_time' => '', 'is_checked' => 1);
					$goodsNumber = $this->cartRepository->addGoodsToCart($arguments);
					$success = true;
				}
			}

			if ($success == true) {
				$result['act_id'] = $params['act_id'];
				$result['ru_id'] = $params['ru_id'];
				$result['error'] = 0;
				$result['message'] = '已加入购物车';
				return $result;
			}
			else {
				$result['error'] = 1;
				$result['message'] = '加入失败';
				return $result;
			}
		}

		$result['error'] = 1;
		$result['message'] = '加入失败';
		return $result;
	}

	public function updateCartGoods($args)
	{
		$cart = $this->cartRepository->find($args['id']);
		$goods = $this->goodsRepository->find($cart['goods_id']);

		if ($goods['goods_number'] < $args['amount']) {
			return array('code' => 1, 'msg' => '库存不足');
		}

		$res = $this->cartRepository->update($args['uid'], $args['id'], $args['amount']);

		if ($res) {
			return array('code' => 0, 'msg' => '添加成功');
		}

		return array('code' => 1, 'msg' => '添加失败');
	}

	public function deleteCartGoods($args)
	{
		$result = array();
		$cart_value = !is_array($args['id']) ? explode(',', $args['id']) : $args['id'];

		if ($cart_value) {
			foreach ($cart_value as $ke => $rec_id) {
				$cart = $this->cartRepository->cartInfo($rec_id, $args['uid']);
				$goods_promotion = $this->goodsService->getPromotionInfo($cart['goods_id'], $cart['ru_id']);

				if ($goods_promotion) {
					foreach ($goods_promotion as $key => $val) {
						$this->cartRepository->deleteGift($val['act_id'], $args['uid']);
					}
				}

				$res = $this->cartRepository->deleteOne($rec_id, $args['uid']);
			}

			exit();
			$result['code'] = 0;
			$result['msg'] = '删除成功';
			return $result;
		}
		else {
			$result['code'] = 1;
			$result['msg'] = '删除失败';
			return $result;
		}
	}

	public function checked($rec_id = array())
	{
		$userId = $this->authService->authorization();
		$this->cartRepository->updateChecked($userId, $rec_id);
		$list = $this->cartRepository->getGoodsInCartByUser($userId, 0);
		return $list['total'];
	}
}


?>
