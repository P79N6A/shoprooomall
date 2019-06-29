<?php
//SHOPROOO 
class paypal
{
	private $API_UserName = '';
	private $API_Password = '';
	private $API_Signature = '';
	private $API_Endpoint = '';
	private $PAYPAL_URL = '';
	private $version = '65.1';
	private $nvpHeader = '';

	public function __construct()
	{
		include_once BASE_PATH . 'Helpers/payment_helper.php';
		$payment = get_payment('paypal');
// 		$this->API_UserName = $payment['paypal_username'];
// 		$this->API_Password = $payment['paypal_password'];
// 		$this->API_Signature = $payment['paypal_signature'];

		$this->API_Endpoint = 'https://api-3t.paypal.com/nvp';
		$this->PAYPAL_URL = 'https://www.paypal.com/cgi-bin/webscr&cmd=_express-checkout&useraction=commit&token=';
// 		if ($payment['paypal_sandbox'] == 1) {
// 		}
// 		else {
// 			$this->API_Endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
// 			$this->PAYPAL_URL = 'https://www.sandbox.paypal.com/cgi-bin/webscr&cmd=_express-checkout&useraction=commit&token=';
// 		}

		$this->nvpHeader = '&VERSION=' . urlencode($this->version) . '&PWD=' . urlencode($this->API_Password) . '&USER=' . urlencode($this->API_UserName) . '&SIGNATURE=' . urlencode($this->API_Signature);
	}

	public function get_code($order, $payment)
	{
		$token = '';
		$serverName = $_SERVER['SERVER_NAME'];
		$serverPort = $_SERVER['SERVER_PORT'];
		$url = dirname('http://' . $serverName . ':' . $serverPort . $_SERVER['REQUEST_URI']);
		$nvpstr = '';
		$paymentAmount = $order['order_amount'];
		$currencyCodeType = $payment['paypal_currency'];
		$paymentType = 'Sale';
		$data_order_id = $order['log_id'];
		$nvpstr .= '&PAYMENTREQUEST_0_AMT=' . $paymentAmount;
		$nvpstr .= '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType;
		$nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType;
		$nvpstr .= '&PAYMENTREQUEST_0_INVNUM=' . $data_order_id;
		$nvpstr .= '&ButtonSource=ECTouch';
		$nvpstr .= '&NOSHIPPING=1';
		$returnURL = urlencode($url . '/respond.php?code=paypal');
		$cancelURL = urlencode($url . '/respond.php?code=paypal');
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

		$payPalURL = $this->PAYPAL_URL . $token;
		$button = '<a type="button" class="box-flex btn-submit min-two-btn" onclick="window.open(\'' . $payPalURL . '\')">PAYPAL支付</a>';
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
