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
 * $Id: paypal.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/paypal.php';

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
    $modules[$i]['desc']    = 'paypal_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECMOBAN TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.paypal.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'paypal_account', 'type' => 'text', 'value' => ''),
        array('name' => 'paypal_currency', 'type' => 'select', 'value' => 'USD')
    );

    return;
}

/**
 * 类
 */
class paypal
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
        $data_return_url    = return_url(basename(__FILE__, '.php'));
        $data_pay_account   = $payment['paypal_account'];
        $currency_code      = $payment['paypal_currency'];
        $data_notify_url    = return_url(basename(__FILE__, '.php'));
        $cancel_return      = $GLOBALS['ecs']->url();

        $def_url  = '<form style="text-align:center;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">' .   // 不能省略
            "<input type='hidden' name='cmd' value='_xclick'>" .                             // 不能省略
            "<input type='hidden' name='business' value='$data_pay_account'>" .                 // 贝宝帐号
            "<input type='hidden' name='item_name' value='$order[order_sn]'>" .                 // payment for
            "<input type='hidden' name='amount' value='$data_amount'>" .                        // 订单金额
            "<input type='hidden' name='currency_code' value='$currency_code'>" .            // 货币
            "<input type='hidden' name='return' value='$data_return_url'>" .                    // 付款后页面
            "<input type='hidden' name='invoice' value='$data_order_id'>" .                      // 订单号
            "<input type='hidden' name='charset' value='utf-8'>" .                              // 字符集
            "<input type='hidden' name='no_shipping' value='1'>" .                              // 不要求客户提供收货地址
            "<input type='hidden' name='no_note' value=''>" .                                  // 付款说明
            "<input type='hidden' name='notify_url' value='$data_notify_url'>" .
            "<input type='hidden' name='rm' value='2'>" .
            "<input type='hidden' name='cancel_return' value='$cancel_return'>" .
            "<input type='submit' value='" . $GLOBALS['_LANG']['paypal_button'] . "'>" .                      // 按钮
            "</form>";

        return $def_url;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        $pp_hostname = "www.paypal.com"; // Change to www.sandbox.paypal.com to test against sandbox
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-synch';
        
        $tx_token = $_GET['tx'];
        $auth_token = "doNYrV75vJuTDZ7aheZK6zns-W_MmWd-OTS_byzYAJM8jgduZ7JyU7u-4j4";
        $req .= "&tx=$tx_token&at=$auth_token";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        //set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
        //if your server does not bundled with default verisign certificates.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $pp_hostname"));
        $res = curl_exec($ch);
        curl_close($ch);
        
        if(!$res){
            return false;
        }else{
            // parse the data
            $lines = explode("\n", trim($res));
            $keyarray = array();
            if (strcmp ($lines[0], "SUCCESS") == 0) {
                for ($i = 1; $i < count($lines); $i++) {
                    $temp = explode("=", $lines[$i],2);
                    $keyarray[urldecode($temp[0])] = urldecode($temp[1]);
                }
                
                $orderId = $keyarray['invoice'];
                $orderInfo = '&amt=' . $_GET['amt'] . '&cc=' . $_GET['cc'] . '&item_name=' . $_GET['item_name'] . '&st=' . $_GET['st'] . '&tx=' . $_GET['tx'];
                //更新订单信息
                $sql = 'UPDATE ' . $GLOBALS['ecs']->table('pay_log') . (' SET transid = \'' . $orderInfo . '\' WHERE log_id = \'') . $orderId . '\'';
                $GLOBALS['db']->query($sql);
                
                if($keyarray['payment_status'] == 'Completed'){
                    order_paid($orderId, PS_PAYED, '');
                    
                    return true;
                }
                
                return false;
            }else if (strcmp ($lines[0], "FAIL") == 0) {
                return false;
            }
        }
//         $payment        = get_payment('paypal');
//         $merchant_id    = $payment['paypal_account'];               ///获取商户编号

//         // read the post from PayPal system and add 'cmd'
//         $req = 'cmd=_notify-synch';
//         foreach ($_POST as $key => $value)
//         {
//             $value = urlencode(stripslashes($value));
//             $req .= "&$key=$value";
//         }

//         print_r($_GET['tx']);
//         // post back to PayPal system to validate
//         $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
//         $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
//         $header .= "Content-Length: " . strlen($req) ."\r\n\r\n";
//         $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

//         // assign posted variables to local variables
//         $item_name = $_POST['item_name'];
//         $item_number = $_POST['item_number'];
//         $payment_status = $_POST['payment_status'];
//         $payment_amount = $_POST['mc_gross'];
//         $payment_currency = $_POST['mc_currency'];
//         $txn_id = $_POST['txn_id'];
//         $receiver_email = $_POST['receiver_email'];
//         $payer_email = $_POST['payer_email'];
//         $order_sn = $_POST['invoice'];
//         $memo = !empty($_POST['memo']) ? $_POST['memo'] : '';
//         $action_note = $txn_id . '（' . $GLOBALS['_LANG']['paypal_txn_id'] . '）' . $memo;

//         if (!$fp)
//         {
//             fclose($fp);

//             return false;
//         }
//         else
//         {
//             fputs($fp, $header . $req);
//             while (!feof($fp))
//             {
//                 $res = fgets($fp, 1024);
//                 if (strcmp($res, 'VERIFIED') == 0)
//                 {
//                     // check the payment_status is Completed
//                     if ($payment_status != 'Completed' && $payment_status != 'Pending')
//                     {
//                         fclose($fp);

//                         return false;
//                     }

//                     // check that txn_id has not been previously processed
//                     /*$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('order_action') . " WHERE action_note LIKE '" . mysql_like_quote($txn_id) . "%'";
//                     if ($GLOBALS['db']->getOne($sql) > 0)
//                     {
//                         fclose($fp);

//                         return false;
//                     }*/

//                     // check that receiver_email is your Primary PayPal email
//                     if ($receiver_email != $merchant_id)
//                     {
//                         fclose($fp);

//                         return false;
//                     }

//                     // check that payment_amount/payment_currency are correct
//                     $sql = "SELECT order_amount FROM " . $GLOBALS['ecs']->table('pay_log') . " WHERE log_id = '$order_sn'";
//                     if ($GLOBALS['db']->getOne($sql) != $payment_amount)
//                     {
//                         fclose($fp);

//                         return false;
//                     }
//                     if ($payment['paypal_currency'] != $payment_currency)
//                     {
//                         fclose($fp);

//                         return false;
//                     }

//                     // process payment
//                     order_paid($order_sn, PS_PAYED, $action_note);
//                     fclose($fp);

//                     return true;
//                 }
//                 elseif (strcmp($res, 'INVALID') == 0)
//                 {
//                     // log for manual investigation
//                     fclose($fp);

//                     return false;
//                 }
//             }
//         }
    }
}

?>