<?php
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . 'includes/lib_payment.php';

require_once "OmiPayApi/OmiPayApi.php";
require_once "OmiPayApi/OmiPayData.php";

$payment        = get_payment('omipay');
$merchant_id    = $payment['omipay_account'];               ///获取商户编号
$merchant_secret_key = $payment['omipay_secret_key'];               ///获取商户密钥

$orderNo = $_GET['code'];

$sql = "SELECT transid, is_paid FROM " . $GLOBALS['ecs']->table('pay_log') . " WHERE log_id = '$orderNo'";
$pay_order = $GLOBALS['db']->getRow($sql);
if(empty($pay_order)){
    exit("\n\t\t<script language=javascript>\n\t\t\t alert('订单不存在');</script>");
}

if($pay_order['is_paid'] == '1'){
    header('Location: /user.php?act=account_log');
    exit();
}

$input = new QueryOrderQueryData();

$input->setMerchantNo($merchant_id);
$input->setSercretKey($merchant_secret_key);
$input->setOrderNo($pay_order['transid']);      // 订单编号Order No
$result = OmiPayApi::orderQuery($input);  //调用接口，查询订单

if ($result['return_code'] == 'SUCCESS') {
    
    if($result['result_code'] == 'PAID'){
        order_paid($orderNo, PS_PAYED);
        
        header('Location: /user.php?act=account_log');
        exit();
    }else {
        exit("\n\t\t<script language=javascript>\n\t\t\t alert('支付未完成请稍后操作');</script>");
    }
    
}else {
    exit("\n\t\t<script language=javascript>\n\t\t\t alert('".$result['error_msg']."');</script>");
}
?>