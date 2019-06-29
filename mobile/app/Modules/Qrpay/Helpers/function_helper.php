<?php
 //SHOPROOO
function IsWeixinOrAlipay()
{
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		return 'wxpay';
	}

	return 'alipay';
}

function get_payment_info($code)
{
	$payment = dao('payment')->where(array('pay_code' => $code, 'enabled' => 1))->find();

	if ($payment) {
		$config_list = unserialize($payment['pay_config']);

		foreach ($config_list as $config) {
			$payment[$config['name']] = $config['value'];
		}
	}

	return $payment;
}

function get_qrpay_info($id)
{
	$res = dao('qrpay_manage')->where(array('id' => $id))->find();
	return $res;
}

function do_discount_fee($qrpay_id, $pay_amount)
{
	$discount_fee = 0;
	$res = dao('qrpay_manage')->where(array('id' => $qrpay_id))->find();
	if (!empty($res) && 0 < $res['discount_id']) {
		$dis = dao('qrpay_discounts')->where(array('id' => $res['discount_id'], 'status' => 1))->find();

		if (!empty($dis)) {
			if (0 < $pay_amount && $dis['min_amount'] <= $pay_amount) {
				$per = intval($pay_amount / $dis['min_amount']);
				$discount_fee = $dis['discount_amount'] * $per;

				if (!!floatval($dis['max_discount_amount'])) {
					$discount_fee = $dis['max_discount_amount'] < $discount_fee ? $dis['max_discount_amount'] : $discount_fee;
				}

				$discount_fee = number_format($discount_fee, 2, '.', '');
			}
		}
	}

	return $discount_fee;
}

function get_discounts_name($id = 0)
{
	$res = dao('qrpay_discounts')->field('min_amount, discount_amount, max_discount_amount')->where(array('status' => 1, 'id' => $id))->find();
	return !empty($res) && 0 < $res['min_amount'] ? '优惠满' . $res['min_amount'] . '元减' . $res['discount_amount'] : '';
}

function qrpay_order_paid($log_id, $pay_status = 0)
{
	$log_id = intval($log_id);

	if (0 < $log_id) {
		$pay_log = dao('qrpay_log')->where(array('id' => $log_id, 'pay_status' => 0))->find();

		if (!empty($pay_log)) {
			dao('qrpay_log')->data(array('pay_status' => $pay_status))->where(array('id' => $log_id))->save();
		}
	}
}

function update_trade_data($log_id, $data = array())
{
	$data = array('trade_no' => $data['transaction_id'], 'notify_data' => serialize($data));
	dao('qrpay_log')->data($data)->where(array('id' => $log_id))->save();
}

function insert_seller_account_log($order_id)
{
	$res = dao('qrpay_log')->where(array('id' => $order_id, 'is_settlement' => 0, 'pay_status' => 1))->find();

	if (!empty($res)) {
		if (0 < $res['ru_id']) {
			$nowTime = gmtime();
			$other['admin_id'] = 0;
			$other['ru_id'] = $res['ru_id'];
			$other['order_id'] = 0;
			$other['amount'] = $res['pay_amount'];
			$other['add_time'] = $nowTime;
			$other['log_type'] = 2;
			$other['is_paid'] = 1;
			$other['pay_id'] = dao('payment')->where(array('pay_code' => $res['payment_code']))->getField('pay_id');
			$other['apply_sn'] = '【收款码订单】' . $res['pay_order_sn'];
			dao('qrpay_log')->data(array('is_settlement' => 1))->where(array('id' => $order_id, 'ru_id' => $res['ru_id']))->save();
			dao('seller_account_log')->data($other)->add();
			dao('seller_shopinfo')->where(array('ru_id' => $res['ru_id']))->setInc('seller_money', $res['pay_amount']);
			$change_desc = '收款码自动结算商家应结金额';
			$user_account_log = array('user_id' => $res['ru_id'], 'user_money' => $res['pay_amount'], 'change_time' => $nowTime, 'change_desc' => $change_desc, 'change_type' => 2);
			dao('merchants_account_log')->data($user_account_log)->add();
		}
		else {
			dao('qrpay_log')->data(array('is_settlement' => 1))->where(array('id' => $order_id))->save();
		}

		return true;
	}

	return false;
}


?>
