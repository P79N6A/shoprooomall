<?php
//zend SHOPROOO在线更新  禁止倒卖 一经发现停止任何服务
class wxpay
{
	public $parameters;
	public $payment;

	public function get_code($order, $payment)
	{
		$this->payment = $payment;
		$this->setParameter('body', $order['order_sn']);
		$this->setParameter('out_trade_no', $order['order_sn'] . 'O' . $order['log_id']);
		$this->setParameter('total_fee', $order['order_amount'] * 100);
		$this->setParameter('notify_url', $GLOBALS['ecs']->url() . 'wxpay_native_callback.php');
		$this->setParameter('trade_type', 'NATIVE');
		$result = $this->getResult();

		if ($result['return_code'] == 'FAIL') {
			show_message('支付失败：' . $result['return_msg'], '我的订单', 'user.php?act=order_list');
		}
		else if ($result['result_code'] == 'FAIL') {
			show_message('支付失败：' . $result['err_code'] . $result['err_code_des'], '我的订单', 'user.php?act=order_list');
		}
		else if ($result['code_url'] != NULL) {
			$code_url = $result['code_url'];

			if (file_exists(ROOT_PATH . 'includes/phpqrcode/phpqrcode.php')) {
				include ROOT_PATH . 'includes/phpqrcode/phpqrcode.php';
			}

			$errorCorrectionLevel = 'Q';
			$matrixPointSize = 6;
			$tmp = ROOT_PATH . 'images/qrcode/';

			if (!is_dir($tmp)) {
				@mkdir($tmp);
			}

			$filename = $tmp . $errorCorrectionLevel . $matrixPointSize . '.png';
			QRcode::png($code_url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
			$wxpay_lbi = '<div id="wxpay_dialog" class="hide">' . '<div class="modal-box">' . '<div class="modal-left">' . '<p><span>请使用 </span><span class="orange">微信 </span><i class="icon icon-qrcode"></i><span class="orange"> 扫一扫</span><br>扫描二维码支付</p>' . '<div class="modal-qr">' . '<div class="modal-qrcode"><img src="' . $GLOBALS['ecs']->url() . 'images/qrcode/' . basename($filename) . '?t=' . time() . '" /></div>' . '<div class="model-info">' . '<img src="themes/ecmoban_dsc/images/sj.png" class="icon-clock" />' . '<span>二维码有效时长为2小时, 请尽快支付</span>' . '</div>' . '</div>' . '</div>' . '<div class="modal-right">' . '<img src="themes/ecmoban_dsc/images/weixin-qrcode.jpg" />' . '</div>' . '</div>' . '</div>';
			return '<a href="javascript:;" class="weizf" data-type="wxpay">微信支付</a><div class="wxzf">' . $wxpay_lbi . '</div>';
		}
	}

	public function respond()
	{
		if ($_GET['status'] == 1) {
			return true;
		}
		else {
			return false;
		}
	}

	public function trimString($value)
	{
		$ret = NULL;

		if (NULL != $value) {
			$ret = $value;

			if (strlen($ret) == 0) {
				$ret = NULL;
			}
		}

		return $ret;
	}

	public function createNoncestr($length = 32)
	{
		$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$str = '';

		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}

		return $str;
	}

	public function setParameter($parameter, $parameterValue)
	{
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}

	public function getSign($Obj)
	{
		foreach ($Obj as $k => $v) {
			$Parameters[$k] = $v;
		}

		ksort($Parameters);
		$buff = '';

		foreach ($Parameters as $k => $v) {
			$buff .= $k . '=' . $v . '&';
		}

		if (0 < strlen($buff)) {
			$String = substr($buff, 0, strlen($buff) - 1);
		}

		$String = $String . '&key=' . $this->payment['wxpay_key'];
		$String = md5($String);
		$result_ = strtoupper($String);
		return $result_;
	}

	public function postXmlCurl($xml, $url, $second = 30)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		$data = curl_exec($ch);

		if ($data) {
			curl_close($ch);
			return $data;
		}
		else {
			$error = curl_errno($ch);
			echo 'curl出错，错误码:' . $error . '<br>';
			echo '<a href=\'http://curl.haxx.se/libcurl/c/libcurl-errors.html\'>错误原因查询</a></br>';
			curl_close($ch);
			return false;
		}
	}

	public function refund($order, $payment)
	{
		$amount = $order['money_paid'] * 100;
		$input = new WxPayRefund($payment);
		$input->SetOut_trade_no($order['order_sn'] . 'A' . $amount . 'B' . $order['log_id']);
		$input->SetTotal_fee($amount);
		$input->SetRefund_fee($amount);
		$input->SetOut_refund_no($order['order_sn'] . 'A' . $amount . 'B' . $order['log_id']);
		$input->SetOp_user_id($payment['wxpay_mchid']);
		$result = WxPayApi::refund($payment, $input);

		if ($result['return_code'] == 'SUCCESS') {
			return true;
		}
		else {
			return false;
		}
	}

	public function getResult()
	{
		$url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

		try {
			if ($this->parameters['out_trade_no'] == NULL) {
				throw new Exception('缺少统一支付接口必填参数out_trade_no！' . '<br>');
			}
			else if ($this->parameters['body'] == NULL) {
				throw new Exception('缺少统一支付接口必填参数body！' . '<br>');
			}
			else if ($this->parameters['total_fee'] == NULL) {
				throw new Exception('缺少统一支付接口必填参数total_fee！' . '<br>');
			}
			else if ($this->parameters['notify_url'] == NULL) {
				throw new Exception('缺少统一支付接口必填参数notify_url！' . '<br>');
			}
			else if ($this->parameters['trade_type'] == NULL) {
				throw new Exception('缺少统一支付接口必填参数trade_type！' . '<br>');
			}
			else {
				if ($this->parameters['trade_type'] == 'JSAPI' && $this->parameters['openid'] == NULL) {
					throw new Exception('统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！' . '<br>');
				}
			}

			$this->parameters['appid'] = $this->payment['wxpay_appid'];
			$this->parameters['mch_id'] = $this->payment['wxpay_mchid'];
			if (isset($this->payment['wxpay_sub_mch_id']) && !empty($this->payment['wxpay_sub_mch_id'])) {
				$this->parameters['sub_mch_id'] = $this->payment['wxpay_sub_mch_id'];
			}

			$this->parameters['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
			$this->parameters['nonce_str'] = $this->createNoncestr();
			$this->parameters['sign'] = $this->getSign($this->parameters);
			$xml = '<xml>';

			foreach ($this->parameters as $key => $val) {
				if (is_numeric($val)) {
					$xml .= '<' . $key . '>' . $val . '</' . $key . '>';
				}
				else {
					$xml .= '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
				}
			}

			$xml .= '</xml>';
		}
		catch (Exception $e) {
			exit($e->getMessage());
		}

		$response = $this->postXmlCurl($xml, $url, 30);
		$result = json_decode(json_encode(simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $result;
	}
}

if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/payment/wxpay.php';

if (file_exists($payment_lang)) {
	global $_LANG;
	include_once $payment_lang;
}

if (isset($set_modules) && $set_modules == true) {
	$i = isset($modules) ? count($modules) : 0;
	$modules[$i]['code'] = basename(__FILE__, '.php');
	$modules[$i]['desc'] = 'wxpay_desc';
	$modules[$i]['is_cod'] = '0';
	$modules[$i]['is_online'] = '1';
	$modules[$i]['author'] = 'ECTOUCH TEAM';
	$modules[$i]['website'] = 'http://mp.weixin.qq.com/';
	$modules[$i]['version'] = '3.0';
	$modules[$i]['config'] = array(
	array('name' => 'wxpay_appid', 'type' => 'text', 'value' => ''),
	array('name' => 'wxpay_appsecret', 'type' => 'text', 'value' => ''),
	array('name' => 'wxpay_key', 'type' => 'text', 'value' => ''),
	array('name' => 'wxpay_mchid', 'type' => 'text', 'value' => ''),
	array('name' => 'wxpay_sub_mch_id', 'type' => 'text', 'value' => ''),
	array('name' => 'is_h5', 'type' => 'select', 'value' => ''),
	array('name' => 'sslcert', 'type' => 'textarea', 'value' => ''),
	array('name' => 'sslkey', 'type' => 'textarea', 'value' => '')
	);
	return NULL;
}

?>
