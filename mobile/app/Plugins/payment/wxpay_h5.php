<?php
/**
 * shoprooo 微信支付
 * ============================================================================
 * 版权所有 2014 shoprooo公司，并保留所有权利。
 * 
 * ============================================================================
 * $Author: z1988.com $
 * $Id: upop_wap.php 17063 2010-03-25 06:35:46Z douqinghua $
 */
if (!defined('BASE_PATH')) {
    die('Hacking attempt');
}

// 包含配置文件
$payment_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/payment/'. basename(__FILE__);

if (file_exists($payment_lang)) {
    global $_LANG;

    include_once($payment_lang);
}


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE) {
    $i = isset($modules) ? count($modules) : 0;
    /* 代码 */
    $modules[$i]['code'] = basename(__FILE__, '.php');
    /* 描述对应的语言项 */
    $modules[$i]['desc'] = 'wxpay_h5_desc';
    /* 是否支持货到付款 */
    $modules[$i]['is_cod'] = '0';
    /* 是否支持在线支付 */
    $modules[$i]['is_online'] = '1';
    /* 作者 */
    $modules[$i]['author'] = 'z1988.com';
    /* 网址 */
    $modules[$i]['website'] = 'http://mp.weixin.qq.com/';
    /* 版本号 */
    $modules[$i]['version'] = '3.3';
    /* 配置信息 */
    $modules[$i]['config'] = array(
        // 微信公众号身份的唯一标识
        array(
            'name' => 'wxpay_h5_appid',
            'type' => 'text',
            'value' => ''
        ),
        // JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
        array(
            'name' => 'wxpay_h5_appsecret',
            'type' => 'text',
            'value' => ''
        ),
        // 商户支付密钥Key
        array(
            'name' => 'wxpay_h5_key',
            'type' => 'text',
            'value' => ''
        ),
        // 受理商ID
        array(
            'name' => 'wxpay_h5_mchid',
            'type' => 'text',
            'value' => ''
        )
    );
    
    return;
}

$lib_path	= dirname(__FILE__).'/wxpay/';
require_once $lib_path."WxPay.Config.php";
require_once $lib_path."WxPay.Api.php";
require_once $lib_path."WxPay.Notify.php";
require_once $lib_path."WxPay.JsApiPay.php";
require_once $lib_path."log.php";

include_once(BASE_PATH.'Helpers/payment_helper.php');
/**
 * 微信支付类
 */
class wxpay_h5
{
	private $dir  ;
	private $site_url;


	function _config( $payment )
	{
		\WxPayConfig::set_appid( $payment['wxpay_h5_appid'] );
		\WxPayConfig::set_mchid( $payment['wxpay_h5_mchid'] );
		\WxPayConfig::set_key( $payment['wxpay_h5_key'] );
		\WxPayConfig::set_appsecret( $payment['wxpay_h5_appsecret']);	
	}
	
	/**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
	function get_code($order, $payment, $go = 0)
	{

		//user&c=account&a=account
		/*if ( $go != 1 ) {
			$url = url('z1988com/index/index', array('id'=>$order['log_id'],'pay_code'=>'wxpay_h5' ));
			$html = '<a type="button" class="box-flex btn-submit" href="'. $url  .'" >微信安全支付</a>';
			return $html;
        }*/

		if ( $go == 0 && ( strtolower(MODULE_NAME) == 'flow' || ( strtolower(MODULE_NAME) == 'user' && strtolower(CONTROLLER_NAME) == 'account' && strtolower(ACTION_NAME) == 'account'  )) ){
			$url = url('z1988com/index/index', array('id'=>$order['log_id'],'pay_code'=>'wxpay_h5' ));
			Header("Location:$url");
			exit();
		}
		
		$this->_config($payment);
		$root_url = __URL__;
		$root_url = str_replace('/mobile', '/', $root_url);
		$notify_url = $root_url.'wxpay_h5_notify.php';
		$return_url	= $GLOBALS['ecs']->url().'respond.php?code='.basename(__FILE__, '.php');
		
		$notify_url = notify_url(basename(__FILE__, '.php'));
		$return_url	= return_url(basename(__FILE__, '.php'));
		
		$out_trade_no = $order['order_sn'] . 'O' . $order['log_id'] . 'O' . date('is');

		$body = $order['order_sn'];
		
		$sql = "select * from " . $GLOBALS['ecs']->table('pay_log') . "  WHERE log_id = '". $order['log_id'] ."' ";
		$pay_log = $GLOBALS['db']->getRow($sql);		
		if (!empty( $pay_log ) ){
			if ( $pay_log['order_type'] == 0 ){
				//$sql = "select goods_name from " . $GLOBALS['ecs']->table('order_goods') . "  WHERE order_id = '". $pay_log['order_id'] ."' ";
				//$body = $GLOBALS['db']->getOne($sql);	
				//$body = $this->msubstr($body,0, 20);
				$body = '购物订单号：'.$order['order_sn'];
			}
			elseif ( $pay_log['order_type'] == 1 ){
				$body = '在线充值';
			}
		}
		
		
		//统一下单
		$tools = new \JsApiPay();
		$input = new \WxPayUnifiedOrder();
		$input->SetBody( $body );
		$input->SetAttach( $order['log_id'] );		//商户支付日志
		$input->SetOut_trade_no( $out_trade_no );		//商户订单号 
		$input->SetTotal_fee( strval(($order['order_amount']*100)) ); //总金额
		$input->SetTime_start(date("YmdHis"));
		//$input->SetTime_expire(date("YmdHis", time() + 600));
		//$input->SetGoods_tag("test");
		$input->SetSpbill_create_ip( $this->get_real_ip() );
		$input->SetNotify_url( $notify_url );	//通知地址 
		$input->SetTrade_type("MWEB");	//交易类型
		$input->SetProduct_id( $order['order_sn'] );
		

		$input->SetOpenid($openId);
		$result = \WxPayApi::unifiedOrder($input);
		
		//$this->setParameter("scene_info", '{"h5_info": {"type":"Wap","wap_url": "'. $root_url .'","wap_name": "com.z1988"}}');
	
       // $result = $this->unifiedOrder();

		if ( $result['return_code'] == 'FAIL' ){
			$error = $result['return_msg'];

			return $this->return_error($error);
		}
		if ( $result['result_code'] == 'FAIL' ){
			$error = $result['err_code'].' '.$result['err_code_des'];

			return $this->return_error($error);
		}
		
		if ( empty($result['mweb_url']) ){
			
			$error = '获取支付mweb_url失败';
			return $this->return_error($error);
		}
		$mweb_url = $result['mweb_url'];
		//$mweb_url .= '&redirect_url='.urlencode($return_url);
		
		$script .='<script type="text/javascript">
				function get_wxpayZ1988com_status( id ){
					
					jQuery.get("'. url('z1988com/index/order_query') .'", "id="+id,function( result ){
						if ( result.error == 0 && result.is_paid == 1 ){
							window.location.href = result.url;
						}
					}, "json");
		
				}
				function return_wxpay_order_status_z1988com(  result ){
					if ( result.error == 0 && result.is_paid == 1 ){
						window.location.href = result.url;
					}
				}
				window.setInterval(function(){ get_wxpayZ1988com_status("'. $order['log_id'] .'"); }, 2000); 
				
				
			</script>';
		if ( $go == 1 ){
			$script0='<script type="text/javascript">
			
				jQuery.get("'. url('z1988com/index/order_query') .'", "id='. $order['log_id'] .'",function( result ){
						if ( result.error == 0 && result.is_paid == 1 ){
							window.location.href = result.url;
						}else{
							document.getElementById("pay_wxpayz1988com").click();
						}
					}, "json");
				
			</script>';
		}
		
		if ( $this->is_wechat_browser() ){

			$html = '<a  type="button" id="pay_wxpayz1988com" class="box-flex btn-submit min-two-btn" onclick="javascript:_AP.pay(\'' .$mweb_url . '\')">微信安全支付</a>';
			return $html.$script;

		}
		
		
		$html = '<a id="pay_wxpayz1988com" class="box-flex btn-submit" href="'.$mweb_url.'" >微信安全支付</a>';
		return $html.$script;

		
	}
	
    /**
     * 响应操作
     */
    function callback($data)
    {
         return true;
		if ($data['status'] == 1) {
            return true;
        } else {
            return false;
        }
    }
	
	
	
    function notify()
    {
		$payment  = get_payment('wxpay_h5');
		$this->_config($payment);

		$lib_path	= dirname(__FILE__).'/wxpay/';
		$logHandler= new \CLogFileHandler($lib_path."logs/".date('Y-m-d').'.log');
		$log = \Log::Init($logHandler, 15);
		
		\Log::DEBUG("begin notify");
		$notify = new \H5PayNotifyCallBack( );
		$notify->Handle(true);
		
		$data = $notify->data;

		//判断签名
			if ($data['result_code'] == 'SUCCESS') {
				
					$transaction_id = $data['transaction_id'];
				 // 获取log_id
                    $out_trade_no	= explode('O', $data['out_trade_no']);
                    $order_sn		= $out_trade_no[0];
					$log_id			= (int)$out_trade_no[1]; // 订单号log_id
					$payment_amount = $data['total_fee']/100;
					$openid 		= $data['openid'];
						
					/* 检查支付的金额是否相符 */
					if (!check_money($log_id, $payment_amount))
					{
						exit();
						return false;
					}
					
					// 修改订单信息(openid，tranid)
                    /*dao('pay_log')
                        ->data(array('openid' => $data['openid'], 'transid' => $transaction_id))
                        ->where(array('log_id' => $log_id))
                        ->save();*/
						
					$sql = "update  " . $GLOBALS['ecs']->table('pay_log') . " set openid='$openid',transid='$transaction_id' WHERE log_id = '$log_id' ";
					//$GLOBALS['db']->query($sql);	
					
					
					$action_note = 'result_code' . ':' 
					. $data['result_code']
					. ' return_code:'
					. $data['return_code']
					. ' orderId:'
					. $data['out_trade_no']		
					. ' openid:'
					. $data['openid']
					. ' ' 
					. $transaction_id;
					// 完成订单。

					order_paid($log_id, PS_PAYED, $action_note);
					exit();
	
			}else{

				exit();
			}

		exit();	
		
    }
	
	/**
	 * 字符串截取，支持中文和其他编码
	 * @static
	 * @access public
	 * @param string $str 需要转换的字符串
	 * @param string $start 开始位置
	 * @param string $length 截取长度
	 * @param string $charset 编码格式
	 * @param string $suffix 截断显示字符
	 * @return string
	 */
	function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
		if(function_exists("mb_substr"))
			$slice = mb_substr($str, $start, $length, $charset);
		elseif(function_exists('iconv_substr')) {
			$slice = iconv_substr($str,$start,$length,$charset);
		}else{
			$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
			$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
			$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
			preg_match_all($re[$charset], $str, $match);
			$slice = join("",array_slice($match[0], $start, $length));
		}
		return $suffix ? $slice.'...' : $slice;
	}

	
	function return_error( $error ){
		
		$html = '<a type="button" class="box-flex btn-submit" onclick="javascript:alert(\''. $error  .'\')">微信安全支付</a>';
	
		return $html;
	}
	
	function is_wechat_browser(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false){
		  //echo '非微信浏览器禁止浏览';
		  return false;
		} else {
		  //echo '微信浏览器，允许访问';
		  //preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
		  //echo '<br>你的微信版本号为:'.$matches[2];
		  return true;
		}
	}
	
	function get_real_ip() {
        //static $realip;
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } else if (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        if (strstr($realip, ',')) 
		{
			$realips = explode(',', $realip);
			$realip = $realips[0];
		}	
        return $realip;
    }
	
}

class H5PayNotifyCallBack extends \WxPayNotify
{
	public  $data;
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new \WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = \WxPayApi::orderQuery($input);
		\Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		\Log::DEBUG("call back:" . json_encode($data));
		
		$this->data = $data;
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}

		return true;
	}
}

?>