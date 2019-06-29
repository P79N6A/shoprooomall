<?php

namespace App\Modules\Drp\Controllers;

use App\Modules\Base\Controllers\FrontendController;

class ShopController extends FrontendController
{
    private $region_id;
    private $area_id;
    //自营
    private $isself = 0;
    //促销
    private $promotion = 0;

    public function __construct()
    {
        parent::__construct();
        $this->assign('custom', C(custom)); //原分销
        $this->assign('customs', C(customs)); //原分销商
        //L(require(LANG_PATH  . C('shop.lang') . '/drp.php'));
    }

    /**
     * 分销店铺
     */
    public function actionIndex()
    {
        $province_id = isset($_COOKIE['province']) ? $_COOKIE['province'] : 0;
        $area_info = get_area_info($province_id);
        $this->area_id = $area_info['region_id'];

        $where = "regionId = '$province_id'";
        $date = ['parent_id'];
        $this->region_id = get_table_date('region_warehouse', $where, $date, 2);

        if (isset($_COOKIE['region_id']) && !empty($_COOKIE['region_id'])) {
            $this->region_id = $_COOKIE['region_id'];
        }
        $shop_id = intval(I('id'));  // 获取参数
        // 查询分销店铺
        $shop_info = $this->getShop($shop_id);
        $size = 10;
        $page = I('page', 1, 'intval');
        $status = I('status', 1, 'intval');
        $this->cat_id = I('cat_id', 0, 'intval');
        if ($this->cat_id > 0) {
            $status = 4;
        }
        $type = drp_type($shop_info['user_id']);//选择分销商品类型
        if ($type == 2) {
            $goodsid = drp_type_goods($shop_info['user_id'], $type);//选中分销商品
            foreach ($goodsid as $key) {
                $goods_id .= $key['goods_id'] . ',';
            }
            $goods_id = substr($goods_id, 0, -1);
            if ($this->cat_id > 0) {
                $where = " AND g.goods_id " . db_create_in($goods_id) . ' and ' . get_children($this->cat_id);
            } else {
                $where = " AND g.goods_id " . db_create_in($goods_id);
            }
        } elseif ($type == 1) {
            $catid = drp_type_cat($shop_info['user_id'], $type);//选中分销商品分类
            foreach ($catid as $key) {
                $cat_id .= $key['cat_id'] . ',';
            }
            $cat_id = substr($cat_id, 0, -1);
            if ($this->cat_id > 0) {
                $where = " AND g.cat_id " . db_create_in($cat_id) . ' and ' . get_children($this->cat_id);
            } else {
                $where = " AND g.cat_id " . db_create_in($cat_id);
            }
        } else {
            $where = '';
        }
        if (IS_AJAX) {
            $this->order = C('shop.sort_order_method') == '0' ? 'desc' : 'asc';
            $this->sort = C('shop.sort_order_type') == '0' ? 'goods_id' : (C('shop.sort_order_type') == '1' ? 'shop_price' : 'last_update');
            $goodslist = get_goods($where, $this->region_id, $this->area_id, $size, $page, $status, $type, $this->sort, $this->order, $this->cat_id);
            exit(json_encode(['list' => $goodslist['list'], 'totalPage' => $goodslist['totalpage']]));
        }

        //获取分类列表
        $category = drp_get_child_tree(0, $shop_id);
        $this->assign("cat_id", $this->cat_id);
        $this->assign('category', $category);

        $this->assign('shop_info', $shop_info);
        $res = $this->checkShop($shop_id);    // 检测店铺状态$shop_id
        $this->assign('status', $status);
        $this->assign('shop_id', $shop_id);

        // 微信JSSDK分享
        $description = '快来参观我的店铺吧，惊喜多多优惠多多';
        $share_data = [
            'title' => $shop_info['shop_name'],
            'desc' => $description,
            'link' => '',
            'img' => $shop_info['headimgurl'],
        ];
        $this->assign('share_data', $this->get_wechat_share_content($share_data));
		$this->drp = get_drp($_SESSION['user_id']);
		if($this->drp){
			$this->assign('is_drp', '1');
		}
        $this->assign('page_title', $shop_info['shop_name']);
        $this->assign('description', $description);
        $this->display();
    }

    /**
     * ajax获取子分类
     */
    public function actionDrpchildcategory()
    {
        $this->cat_id = I('request.id', 0, 'intval');
        $this->shop_id = I('request.shop_id', 0, 'intval');
        if (IS_AJAX) {
            if (empty($this->cat_id)) {
                exit(json_encode(['code' => 1, 'message' => '请选择分类']));
            }
            if (APP_DEBUG) {
                $category = drp_get_child_tree($this->cat_id, $this->shop_id);
            } else {
                $category = S('categorys' . $this->cat_id);
                if ($category === false) {
                    $category = drp_get_child_tree($this->cat_id, $this->shop_id);
                    S('category' . $this->cat_id, $category);
                }
            }
            exit(json_encode(['category' => $category]));
        }
    }


    /**
     * 获取分销店铺信息
     */
    private function getShop($shop_id = 0)
    {
        $time = gmtime();
        $sql = "SELECT * FROM {pre}drp_shop WHERE id=$shop_id";
        $res = $this->db->getRow($sql);

        $shop_info = '';
        if ($res['shop_portrait']) {
            $shop_info['headimgurl'] = get_image_path($res['shop_portrait']);
        } else {
            //用户名、头像
            $user_nick = get_user_default($res['user_id']);
            $shop_info['headimgurl'] = $user_nick['user_picture'];
        }

        $shop_info['id'] = $res['id'];
        $shop_info['shop_name'] = C('shop_name') . $res['shop_name'];
        $shop_info['real_name'] = $res['real_name'];
        $shop_info['audit'] = $res['audit'];
        $shop_info['status'] = $res['status'];
        if (empty($res['shop_img'])) {
            $shop_info['shop_img'] = elixir('img/user-shop.png');
        } else {
            $shop_info['shop_img'] = get_image_path($res['shop_img']);
        }
        $shop_info['user_id'] = $res['user_id'];
        $shop_info['create_time'] = date("Y-m-d", $res['create_time']);
        if ($res['user_id'] = $_SESSION['user_id']) {
            $shop_info['url'] = url('drp/user/index', ['id' => $res['user_id']]);
        }
        $cat = substr($res['goods_id'], 0, -1);
        $shop_info['goods_id'] = $cat;

        $type = drp_type($_SESSION['user_id']);//选择分销商品类型
        if ($type == 2) {
            $goodsid = drp_type_goods($_SESSION['user_id'], $type);//选中分销商品
            foreach ($goodsid as $key) {
                $goods_id .= $key['goods_id'] . ',';
            }
            $goods_id = substr($goods_id, 0, -1);
            $where = " AND goods_id " . db_create_in($goods_id);
        } elseif ($type == 1) {
            $catid = drp_type_cat($_SESSION['user_id'], $type);//选中分销商品分类
            foreach ($catid as $key) {
                $cat_id .= $key['cat_id'] . ',';
            }
            $cat_id = substr($cat_id, 0, -1);
            $where = " AND cat_id " . db_create_in($cat_id);
        } else {
            $where = "";
        }

        //全部商品数量
        $sql = "SELECT count(goods_id) as sum from {pre}goods WHERE is_on_sale = 1 AND is_distribution = 1 AND dis_commission >0 AND is_alone_sale = 1 AND is_delete = 0 $where";
        $sum['all'] = $this->db->getOne($sql);
        $shop_info['sum'] = $sum['all'];
        //新品商品数量
        $sql = "SELECT count(goods_id) as sum FROM {pre}goods WHERE  is_new = 1 AND is_distribution = 1 AND is_on_sale = 1 AND dis_commission >0 AND is_alone_sale = 1 AND is_delete = 0 $where";
        $sum['new'] = $this->db->getOne($sql);
        $shop_info['new'] = $sum['new'];
        //促销商品数量
        $sql = "SELECT count(goods_id) as sum FROM {pre}goods WHERE is_promote = 1 AND is_distribution = 1 AND dis_commission >0 AND promote_start_date <= '$time' AND promote_end_date >= '$time' AND is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0 $where";
        $sum['promote'] = $this->db->getOne($sql);
        $shop_info['promote'] = $sum['promote'];
        return $shop_info;
    }

    /**
     * 检测店铺状态
     */
    private function checkShop($shop_id = 0)
    {
        $sql = "SELECT * FROM {pre}drp_shop WHERE id='$shop_id'";
        $res = $this->db->getRow($sql);
        if ($res['audit'] != 1) {
            show_message(L('admin_check'), L('in_shop'), url('/'), 'fail');
        }
        if ($res['status'] != 1) {
            show_message(L('shop_close'), L('in_shop'), url('/'), 'fail');
        }
        return ture;
    }
}
