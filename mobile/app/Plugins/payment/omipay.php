<?php
//SHOPROOO 
class omipay
{
	private $API_UserName = '';
	private $API_Password = '';
	private $API_Signature = 'f40778c82f5a4710833527b9fb9dfcd0';
	private $omipay_URL = 'https://www.omipay.com.cn/omipay/api/v2/MakeQROrder';
	private $version = '2.0';

	public function __construct()
	{
		include_once BASE_PATH . 'Helpers/payment_helper.php';
		$payment = get_payment('omipay');
		$this->API_UserName = $payment['omipay_username'];
		$this->API_Password = $payment['omipay_password'];
		$this->API_Signature = $payment['omipay_signature'];
	}

	public function get_code($order, $payment)
	{
		$token = '';
		$serverName = $_SERVER['SERVER_NAME'];
		$serverPort = $_SERVER['SERVER_PORT'];
		$url = dirname('http://' . $serverName . ':' . $serverPort . $_SERVER['REQUEST_URI']);
		$nvpstr = '';
		$paymentAmount = $order['order_amount'];
		$currencyCodeType = $payment['omipay_currency'];
		$paymentType = 'Sale';
		$data_order_id = $order['log_id'];
		$nvpstr .= '&PAYMENTREQUEST_0_AMT=' . $paymentAmount;
		$nvpstr .= '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType;
		$nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType;
		$nvpstr .= '&PAYMENTREQUEST_0_INVNUM=' . $data_order_id;
		$nvpstr .= '&ButtonSource=ECTouch';
		$nvpstr .= '&NOSHIPPING=1';
		$returnURL = urlencode($url . '/respond.php?code=omipay');
		$cancelURL = urlencode($url . '/respond.php?code=omipay');
		$nvpstr .= '&ReturnUrl=' . $returnURL;
		$nvpstr .= '&CANCELURL=' . $cancelURL;
		$nvpstr .= '&SolutionType=Sole';
		$nvpstr .= '&LandingPage=Billing';
		$resArray = $this->hash_call('SetExpressCheckout', $nvpstr);
		$_SESSION['reshash'] = $resArray;

		if (isset($resArray['ACK'])) {
			$ack = strtoupper($resArray['ACK']);
		}

		if (isset($resArray['TOKEN'])) {
			$token = urldecode($resArray['TOKEN']);
		}

		$omipayURL = $this->omipay_URL . $token;
		$button = '<a type="button" class="box-flex btn-submit min-two-btn" onclick="window.open(\'' . $omipayURL . '\')">omipay支付</a>';
		return $button;
	}

	public function callback($data)
	{
		return $this->notify();
	}

	public function notify($data)
	{
		$token = urlencode($_REQUEST['token']);
		$nvpstr = '&TOKEN=' . $token;
		$resArray = $this->hash_call('GetExpressCheckoutDetails', $nvpstr);
		$_SESSION['reshash'] = $resArray;
		$ack = strtoupper($resArray['ACK']);

		if ($ack == 'SUCCESS') {
			$payerID = urlencode($resArray['PAYERID']);
			$currCodeType = urlencode($resArray['PAYMENTREQUEST_0_CURRENCYCODE']);
			$paymentType = urlencode($resArray['PAYMENTREQUEST_0_PAYMENTACTION']);
			$paymentAmount = urlencode($resArray['PAYMENTREQUEST_0_AMT']);
			$order_sn = urlencode($resArray['PAYMENTREQUEST_0_INVNUM']);
			$serverName = urlencode($_SERVER['SERVER_NAME']);
			$nvpstr = '&TOKEN=' . $token;
			$nvpstr .= '&PAYERID=' . $payerID;
			$nvpstr .= '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType;
			$nvpstr .= '&PAYMENTREQUEST_0_AMT=' . $paymentAmount;
			$nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $currCodeType;
			$nvpstr .= '&PAYMENTREQUEST_0_INVNUM=' . $order_sn;
			$nvpstr .= '&IPADDRESS=' . $serverName;
			$nvpstr .= '&ButtonSource=';
			$resArray = $this->hash_call('DoExpressCheckoutPayment', $nvpstr);
			$ack = strtoupper($resArray['ACK']);
			if ($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING') {
				return false;
			}
			else {
				order_paid($order_sn, 2);
				return true;
			}
		}
		else {
			return false;
		}
	}

	private function hash_call($methodName, $nvpStr)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		$nvpreq = 'METHOD=' . urlencode($methodName) . $this->nvpHeader . $nvpStr;
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		$response = curl_exec($ch);
		$nvpResArray = $this->deformatNVP($response);
		$nvpReqArray = $this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray'] = $nvpReqArray;

		if (curl_errno($ch)) {
			$_SESSION['curl_error_no'] = curl_errno($ch);
			$_SESSION['curl_error_msg'] = curl_error($ch);
		}
		else {
			curl_close($ch);
		}

		return $nvpResArray;
	}

	private function deformatNVP($nvpstr)
	{
		$intial = 0;
		$nvpArray = array();

		while (strlen($nvpstr)) {
			$keypos = strpos($nvpstr, '=');
			$valuepos = strpos($nvpstr, '&') ? strpos($nvpstr, '&') : strlen($nvpstr);
			$keyval = substr($nvpstr, $intial, $keypos);
			$valval = substr($nvpstr, $keypos + 1, $valuepos - $keypos - 1);
			$nvpArray[urldecode($keyval)] = urldecode($valval);
			$nvpstr = substr($nvpstr, $valuepos + 1, strlen($nvpstr));
		}

		return $nvpArray;
	}
}


?>
