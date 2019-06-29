<?php

namespace App\Custom\Z1988com\Controllers;

class Index extends \App\Http\Base\Controllers\Frontend
{
	
	
	public function actionIndex()
	{
		
		$id = I('id', 0, 'intval');
		$pay_code = I('pay_code');
		$pay_code = $pay_code ? $pay_code : 'wxpay_jspay';

		if ( $id < 1){
			show_message('参数错误');
			exit();
		}
		if( $pay_code == 'wxpay_jspay'){
			if ( empty($_SESSION['wxpay_jspay_openid'])  ){
			
			$this->init_wxpay_jspay();
			}
		}
		
		
		$sql = 'SELECT *  FROM' . $this->ecs->table('pay_log') . ' WHERE log_id = \'' . $id . '\'';
		$pay_log = $this->db->getRow($sql);
		
		if ( empty( $pay_log ) ){
			show_message('该支付订单不存在');
			exit();
		}
		if ( $pay_log['is_paid'] == 1 ){
			
			show_message('该订单已支付', '返回个人中心', url('user/index/index'));
			show_message('该订单已支付');
			exit();
		}
		
		$order= array();
		$order['log_id'] 		= $id;
		$order['order_amount'] 	= $pay_log['order_amount'];
		$order['order_type'] 		= $pay_log['order_type'];
		//url('add')
		$z1988comUrl = '';
		if ($pay_log['order_type'] == PAY_ORDER) {
			$sql = "select order_sn from " . $GLOBALS['ecs']->table('order_info') . "  WHERE order_id = '". $pay_log['order_id'] ."' ";
			$order['order_sn'] = $GLOBALS['db']->getOne($sql);
			$z1988comUrl = url('user/order/detail', array('order_id'=>$pay_log['order_id']));
		}
		else if ($pay_log['order_type'] == PAY_SURPLUS) {
			$order['order_sn'] = $pay_log['order_id'];
			$z1988comUrl = url('user/account/index');
		}	
		else if ($pay_log['order_type'] == PAY_REGISTERED) {
			$order['order_sn'] = $pay_log['order_id'];
			$z1988comUrl = url('drp/index/index');
		}
	
		include_once(BASE_PATH.'Helpers/payment_helper.php');
		
		$payment  = get_payment($pay_code);
		if ( empty( $payment ) ){
			show_message('该支付方式不存在');
			exit();
		}
		
		
		include_once ADDONS_PATH . 'payment/' . $payment['pay_code'] . '.php';
		$pay_obj = new $payment['pay_code']();
		$pay_online = $pay_obj->get_code($order, unserialize_config($payment['pay_config']), 1);
		$order['pay_desc'] = $payment['pay_desc'];
		$this->assign('pay_online', $pay_online);
		$this->assign('order', $order);
		$this->assign('page_title', '在线支付');
		$this->assign('z1988comUrl', $z1988comUrl);

		$this->display();
	}

	

	public function actionOrderQuery() {
	 
		$log_id = intval($_GET['id']);
		
		$result = array('error'=>0, 'message'=>'', 'content'=>'');

		if(isset($_SESSION['last_order_query']))
		{
			if(time() - $_SESSION['last_order_query'] < 1)
			{
				$result['error'] = 1;
				$result['message'] = 'order_query_toofast';
				die(json_encode($result));
			}
		}
		$_SESSION['last_order_query'] = time();

		if (empty($log_id))
		{
			$result['error'] = 1;
			$result['message'] = 'invalid_order_sn';
			die(json_encode($result));
		}

		$sql = 'SELECT *  FROM' . $this->ecs->table('pay_log') . ' WHERE log_id = \'' . $log_id . '\'';
		$pay_log = $this->db->getRow($sql);

		if (empty($pay_log))
		{
			$result['error'] = 1;
			$result['message'] = 'invalid_order_sn';
			die(json_encode($result));
		}
		$order_type = $pay_log['order_type'];
		$url = 'user.php?act=order_detail&order_id='.$pay_log['order_id'];
		$url = url('respond/index/index',array('code'=>'wxpay_h5','status'=>1));
		if ( $order_type == 1  ){
			//$url = 'user.php?act=account_log';
			//$url = url('default/respond',array('code'=>'wxpay_h5'));
		}
		if( $pay_log['is_paid'] == 1){
			$result['url'] 		= $url;
		}
		$result['url'] 		= $url;
		$result['is_paid'] 	= $pay_log['is_paid'];
		die(json_encode($result));

	}

		//start by tb.z1988.com
	private function init_wxpay_jspay(){


		//error_reporting(E_ERROR | E_WARNING | E_PARSE);
		//error_reporting(E_ALL | E_NOTICE);	//Notice 以上的错误会显示
		//error_reporting(0);	
		if ( empty($_SESSION['wxpay_jspay_openid'])  ){
			if(isset($_COOKIE["wxpay_jspay_openid"]) && !empty($_COOKIE["wxpay_jspay_openid"]))
			{
				//$_SESSION["wxpay_jspay_openid"]= $_COOKIE["wxpay_jspay_openid"];
				//return true;
			}
			//获取openid
			include_once(BASE_PATH.'Helpers/payment_helper.php');
			$plugin_file = ADDONS_PATH.'payment/wxpay_jspay.php';
			require_once( $plugin_file );
			$payment  = get_payment('wxpay_jspay');
			if( empty($payment)  && $payment['enabled']  != 1 ){
				return false;
			}
			$wxpay_jspay = new \wxpay_jspay();
			$wxpay_jspay->_config( $payment );
			$tools = new \JsApiPay();
			$data = $tools->GetOpenid();
			if ( isset( $data['errcode'] ) && $data['errcode'] >0){
				echo $data['errmsg'];exit();
			}
			$openid = $data['openid'];
			$_SESSION['wxpay_jspay_openid'] = $openid;
			setcookie("wxpay_jspay_openid", $openid, time()+3600*24*7);
			unset($_GET['code']);
			
			$wxpay_jspay_redirect_url = $_SESSION['wxpay_jspay_redirect_url'];
			unset($_SESSION['wxpay_jspay_redirect_url']);
			if ( !empty($wxpay_jspay_redirect_url)){
				Header("Location: ". urldecode( $wxpay_jspay_redirect_url ) ."");
				exit();
			}

		}
	}
	//end by tb.z1988.com	
	
}

?>
