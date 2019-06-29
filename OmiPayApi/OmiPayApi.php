<?php
// header('Content-type:text/json;charset="utf-8"');


require_once "OmiPayException.php";
require_once "OmiPayConfig.php";
require_once "OmiPayData.php";

require_once 'Log.php';

/**
 *
 * 接口访问类，包含所有Omi支付API列表的封装，类中方法为static方法，
 *
 */
class OmiPayApi
{

    // http://gateway.test.com/v2/MakeAppletOrder
    /**
     * 微信小程序服务端下单
     * */ 
    public static function makeWxMiniProgramOrder($inputObj, $url, $timeOut = 100)
    {
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();

        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = OmiPayResults::init($response);
        return $result;
    }

    /**
     * 微信获取openid
     * */ 
    public static function getOpenId($url, $timeOut = 100)
    {
        $response = self::getJsonCurl($url);
        $result = OmiPayResults::init($response);
        return $result;
    }

    public static function setDomain($name){

        $url = OmiPayConfig::DOMAINCN . OmiPayConfig::API_VERSION;

        if( !empty(OmiPayConfig::DOMAIN_TYPE) && OmiPayConfig::DOMAIN_TYPE == 'AU'){
            $url = OmiPayConfig::DOMAIN . OmiPayConfig::API_VERSION;   
        }

        return $url . "/" . $name;
    }

    /**
     * 信用卡支付服务端
     * */ 
    public static function checkout($inputObj, $timeOut = 100)
    {
        $url = self::setDomain("ConfirmCheckOut"); // ConfirmCheckOut  // ChargeCardOrder

        $inputObj->getMerchantNo();
        $inputObj->getTokenId();

        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();

        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = OmiPayResults::init($response);
        return $result;
    }

    /**
     *
     * 汇率查询，nonce_str、time不需要填入
     * @param OmiPayExchangeRate $inputObj
     * @param int $timeOut
     * @throws OmiPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function exchangeRate($inputObj , $timeOut = 30)
    {
        $url = self::setDomain("GetExchangeRate");

        $inputObj->getMerchantNo();

        $inputObj->setTime(self::getMillisecond());

        $inputObj->setNonceStr(self::getNonceStr());//随机字符串

        $inputObj->setSign();

        $inputObj->setCurrency('AUD');

        $inputObj->setBaseCurrency('CNY'); 

        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        
        $result = OmiPayResults::init($response);
        return $result;
    }

    /**
     *
     * QR下单，nonce_str、time不需要填入
     * @param OmiPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @throws OmiPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function qrOrder($inputObj , $timeOut = 100)
    {
        $url = self::setDomain("MakeQROrder");

        $inputObj->getMerchantNo();
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = OmiPayResults::init($response);
        return $result;
    }

    /**
     *
     * JsApi下单，nonce_str、time不需要填入
     * @param OmiPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @throws OmiPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function jsApiOrder($inputObj , $timeOut = 10)
    {
        $url = self::setDomain("MakeJSAPIOrder");
        
        $inputObj->getMerchantNo();
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = OmiPayResults::init($response);
        return $result;
    }

    /**
     *
     * 获取参数签名
     * @param OmiPayUnifiedOrder $inputObj
     * @param $inputObj
     * @param $timeNo
     * @param $nonceStr
     * @throws OmiPayException
     * @return $result
     */
    public static function getParamSign($inputObj, $timeNo, $nonceStr)
    {
        $inputObj->getMerchantNo();
        $inputObj->setTime($timeNo);//时间戳
        $inputObj->setNonceStr($nonceStr);//随机字符串
        $inputObj->setSign();
        
        return $inputObj->getSign();
    }
    
    public static function onlineOrder($inputObj , $timeOut = 10)
    {
        $url = self::setDomain("MakeOnlineOrder");


        $inputObj->getMerchantNo();
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = OmiPayResults::init($response);
        return $result;
    }


    // https://www.omipay.com.au/omipay/api/v2/MakeScanOrder
    public static function scanOrder($inputObj , $timeOut = 10)
    {
        $url = self::setDomain("MakeScanOrder");

        $inputObj->getMerchantNo();
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = OmiPayResults::init($response);
        return $result;
    }

    /**
     *
     * QR支付跳转，nonce_str、time不需要填入
     * @param string $pay_url
     * @param OmiPayRedirect $inputObj
     * @throws OmiPayException
     * @return $pay_url 成功时返回，其他抛异常
     */
    public static function getQRRedirectUrl($pay_url,  $inputObj)
    {
        $inputObj->getMerchantNo();
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $pay_url .= '&' . $inputObj->toQueryParameters();
        return $pay_url;
    }

    /**
     *
     * JsApi支付跳转，nonce_str、time不需要填入
     * @param string $pay_url
     * @param OmiPayJsApiRedirect $inputObj
     * @throws OmiPayException
     * @return $pay_url 成功时返回，其他抛异常
     */
    public static function getJsApiRedirectUrl($pay_url, $inputObj)
    {
        $directPay = $inputObj->getDirectPay();
        if (empty($directPay)) {
            $inputObj->setDirectPay('false');
        }
        return $pay_url;
    }

    /**
     *
     * 查询订单，nonce_str、time不需要填入
     * @param OmiPayOrderQuery $inputObj
     * @param int $timeOut
     * @throws OmiPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function orderQuery($inputObj , $timeOut = 10)
    {
        $url = self::setDomain("QueryOrder");

        $inputObj->getMerchantNo();
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = OmiPayResults::init($response);
        return $result;
    }

    /**
     *
     * 申请退款，nonce_str、time不需要填入
     * @param OmiPayApplyRefund $inputObj
     * @param int $timeOut
     * @throws OmiPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function refund($inputObj , $timeOut = 10)
    {
        $url = self::setDomain("Refund");

        $inputObj->getMerchantNo();
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = OmiPayResults::init($response);
        return $result;
    }

    /**
     *
     * 查询退款状态，nonce_str、time不需要填入
     * @param OmiPayQueryRefund $inputObj
     * @param int $timeOut
     * @throws OmiPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function refundQuery($inputObj , $timeOut = 10)
    {
        $url = self::setDomain("QueryRefund");

        $inputObj->getMerchantNo();
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = OmiPayResults::init($response);
        return $result;
    }

    /**
     *
     * 产生随机字符串，不长于30位
     * @param int $length
     * @return $str 产生的随机字符串
     */
    public static function getNonceStr($length = 30)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /***
    *判断时不时移动设备
    */
    public static function isMobile() { 
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        } 
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        { 
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        } 
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
                ); 
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            } 
        } 
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        { 
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            } 
        } 
        return false;
      }

    /**
     * 以get方式提交json到对应的接口url
     *
     * @param string $url
     * @param object $inputObj
     * @param int $second url执行超时时间，默认30s
     * @throws OmiPayException
     */
    private static function getJsonCurl($url, $inputObj, $second = 100)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //如果有配置代理这里就设置代理
        if (OmiPayConfig::CURL_PROXY_HOST != "0.0.0.0"
            && OmiPayConfig::CURL_PROXY_PORT != 0
        ) {
            curl_setopt($ch, CURLOPT_PROXY, OmiPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch, CURLOPT_PROXYPORT, OmiPayConfig::CURL_PROXY_PORT);
        }

        if(isset($inputObj)){
            $url .= '?' . $inputObj->toQueryParameters();
        }
        
        Log::DEBUG('GET:'.$url);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        //GET提交方式
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        
        //运行curl
        $data = curl_exec($ch);

        Log::INFO('DATA:'.$data);

        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            // throw new OmiPayException("curl出错，错误码:$error");
            return json_encode(array("curl error code"=> $error));
        }
    }

    /**
     * 以put方式提交json到对应的接口url
     *
     * @param string $url
     * @param object $inputObj
     * @param int $second url执行超时时间，默认30s
     * @throws OmiPayException
     */
    private static function postJsonCurl($url, $inputObj, $second = 30)
    {
        if (empty($url) || empty($inputObj)) {
            return false;
        }
        
        $postUrl = $url;
        $curlPost = $inputObj->toQueryParameters();
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验

        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl

        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            // throw new OmiPayException("curl出错，错误码:$error");
            return json_encode(array("curl error code"=> $error));
        }

    }

    /**
     * 获取毫秒级别的时间戳
     */
    private static function getMillisecond()
    {
        $time = explode(" ", microtime());
        $time1 =explode(".", $time[0] * 1000);//  $time[0] * 1000;
        if($time1[0] < 10){
            $time2 = $time[1] ."00".$time1[0];
        }else if($time1[0] < 100){
            $time2 = $time[1] ."0".$time1[0];
            
        }else{
            $time2 = $time[1].$time1[0];
        }
        return $time2;
    }

    public static function mkdirs($dirs, $mode = 0777)
    {
        return TRUE;
        $dir = iconv("UTF-8", "GBK", $dirs); // 防止中文乱码
        if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
        if (!mkdirs(dirname($dir), $mode)) return FALSE;
     
        return @mkdir($dir, $mode);
    }

}


// 初始化日志
$dir = "../logs";
if(OmiPayApi::mkdirs($dir) == true){
    $logHandler = new CLogFileHandler($dir . "/" . date('Y-m-d') . '.log');  
    $log = Log::Init($logHandler, 15);
}


