<?php
//zend SHOPROOO在线更新版  禁止倒卖 一经发现停止任何服务
namespace App\Modules\Flow\Controllers;

class AjaxController extends IndexController
{
	public function __construct()
	{
		parent::__construct();
		$this->assign('area_id', $this->area_info['region_id']);
		$this->assign('warehouse_id', $this->region_id);
		$this->assign('area_city', $this->area_city);
	}

	public function actionShippingPrompt()
	{
		if (IS_AJAX) {
			$shipping_prompt = input('shipping_prompt', '', array('html_in', 'trim'));

			if ($shipping_prompt) {
				$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
				$flow_type = $_SESSION['flow_type'] == CART_ONESTEP_GOODS ? CART_ONESTEP_GOODS : $flow_type;
				$cart_value = get_sc_str_replace($_SESSION['cart_value'], $shipping_prompt, 1);
				$cart_goods_list = cart_goods($flow_type, $shipping_prompt, 1, $this->region_id, $this->area_id, $this->area_city);
				$cart_goods_list_new = cart_by_favourable($cart_goods_list);
				dao('cart')->data(array('is_checked' => 0))->where(array(
	'rec_id' => array('in', $shipping_prompt)
	))->save();
				$GLOBALS['smarty']->assign('goods_list', $cart_goods_list_new);
				$result['error'] = 1;
				$result['cart_value'] = $cart_value;
				$result['cart_content'] = $this->fetch('goods_shipping_prompt');
				exit(json_encode($result));
			}
		}
	}
}

?>
