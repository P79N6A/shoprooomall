<?php
/**
 * shoprooo 贝宝插件
 * ============================================================================
 * * 版权所有 2005-2016 shoprooo公司，并保留所有权利。
 * 
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: omipay.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/omipay.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'omipay_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECMOBAN TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.omipay.com.cn';

    /* 版本号 */
    $modules[$i]['version'] = '2.0.0';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'omipay_account', 'type' => 'text', 'value' => ''),
        array('name' => 'omipay_secret_key', 'type' => 'text', 'value' => ''),
        array('name' => 'omipay_currency', 'type' => 'select', 'value' => 'USD')
    );

    return;
}

require_once ROOT_PATH . "/OmiPayApi/OmiPayApi.php";
require_once ROOT_PATH . "/OmiPayApi/OmiPayData.php";
/**
 * 类
 */
class omipay
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function __construct()
    {
    }

    /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        $data_order_id      = $order['log_id'];
        $data_amount        = $order['order_amount'];
        $data_return_url    = return_omiurl($data_order_id);
        $data_pay_account   = $payment['omipay_account'];
        $data_pay_secret_key   = $payment['omipay_secret_key'];
        $currency_code      = "AUD";
        $data_notify_url    = return_omiurl($data_order_id);
        $cancel_return      = $GLOBALS['ecs']->url();

        // 获取扫码
        $input = new MakeJSAPIOrderQueryData();
        
        // 设置'CN'为访问国内的节点 ,设置为'AU'为访问香港的节点
        $domain = 'AU';
        
        $input = new MakeJSAPIOrderQueryData();
        $time_no = OmiData::getMillisecondPublic();  // 获取毫秒的时间戳
        $nonce_str = OmiData::getNonceStrPublic(8);  // 获得8位随机字符串， 时间戳+8位随机字符可生成外部订单号
        
        $input -> setMerchantNo($data_pay_account);
        $input -> setSercretKey($data_pay_secret_key);
        $input -> setNotifyUrl($data_notify_url);
        $input -> setCurrency($currency_code);
        $input -> setOrderName('在线充值');
        $input -> setAmount($data_amount*100);
        $input -> setOutOrderNo($data_order_id);
        $input -> setPcPay('1');        //  show_pc_pay_url=1
        $input->setDirectPay('1');
        
        $currency = $input->getCurrency();
        
        // 调用接口支付下单
        $result = OmiPayApi::jsApiOrder($input);
        
        $def_url  = '';
        $sql = "SELECT log_id, transid, is_paid FROM " . $GLOBALS['ecs']->table('pay_log') . " WHERE log_id = '$data_order_id'";
        $pay_order = $GLOBALS['db']->getRow($sql);
        if(empty($pay_order)){
            header('Location: /user.php?act=account_log');
            exit();
        }
        
        if($pay_order['is_paid'] == '1'){
            return '支付成功';
        }
        if ($result['return_code'] == 'FAIL') {
            
            if(empty($pay_order['transid'])){
                return '支付订单号不存在,请联系管理员';
            }
            
            $inputData = new QueryOrderQueryData();
            
            $inputData->setMerchantNo($data_pay_account);
            $inputData->setSercretKey($data_pay_secret_key);
            $inputData->setOrderNo($pay_order['transid']);      // 订单编号Order No
            $result = OmiPayApi::orderQuery($inputData);  //调用接口，查询订单
            
            if ($result['return_code'] == 'SUCCESS') {
                
                if($result['result_code'] == 'PAID'){
                    order_paid($data_order_id, PS_PAYED);
                    
                    return '支付成功';
                }else {
                    return '支付未完成请稍后操作';
                }
                
            }else {
                return $result['error_msg'];
            }
        }else{
            $inputObj = new OmiPayRedirect();
            $inputObj -> setMerchantNo($data_pay_account);
            $inputObj -> setSercretKey($data_pay_secret_key);
    
            $url_param = parse_url($result['pc_pay_url']);
    
            $queryParts = explode('&', $url_param['query']);
    
            $params = array();
            foreach ($queryParts as $param)
                {
                    $item = explode('=', $param);
                    $params[$item[0]] = $item[1];
                }
    
            $orderNo = $params['paycode'];
    
            //更新订单号
            $sql = 'UPDATE ' . $GLOBALS['ecs']->table('pay_log') . (' SET transid = \'' . $orderNo . '\' WHERE log_id = \'') . $order['log_id'] . '\'';
            $GLOBALS['db']->query($sql);
    
            $jump = OmiPayApi::getQRRedirectUrl($result['pc_pay_url'], $inputObj); // 这个是跳转到omipay网页扫码支付，要加上签名验证
            $jump .= "&redirect_url=".$data_return_url;   // 支付完成之后跳转地址
    
            $link = $result['pay_url']; // 接口SUCCESS返回的pay_url就是支付地址, 需要再支付宝或者微信中打开
    
            $def_url  = '<form style="text-align:center;" action= method="post" target="_blank">' .   // 不能省略
            "<a href='".$jump."' target='_blank'><input type='button' value='" . $GLOBALS['_LANG']['omipay_button'] . "'></a>" .                      // 按钮
                "</form>";
        }

        return $def_url;
    }

    /**
     * 响应操作
     */
    function respond()
    {
       
    }
}

?>