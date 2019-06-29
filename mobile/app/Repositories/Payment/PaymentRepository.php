<?php
//SHOPROOO
namespace App\Repositories\Payment;

class PaymentRepository
{
	public function paymentList()
	{
		$payment = \App\Models\Payment::select('pay_id', 'pay_code', 'pay_name', 'pay_fee', 'pay_desc', 'pay_config', 'is_cod')->where('enabled', 1)->get()->toArray();
		return $payment;
	}

	public function payment($code = NULL)
	{
		$payment = \App\Models\Payment::where('pay_code', $code)->where('enabled', 1)->first();
		return is_null($payment) ? null : $payment->toArray();
	}
}


?>
