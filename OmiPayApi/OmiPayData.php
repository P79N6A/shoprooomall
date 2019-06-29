
<?php

require_once "OmiPayConfig.php";
require_once "OmiPayException.php";

/**
 *
 *  Omipay Data Class
 *  数据类
 *
 */
class OmiData
{
    protected $parameters = array();

    protected $serectKey = array();    

    protected $receivedData = array();

    protected $bodyValues = array();

    /**
     * 卡支付设置token_id
     * */ 
    public function setTokenId($value)
    {
        $this->parameters['token_id'] = $value;
        return $this->parameters['token_id'];
    }

    public function getTokenId()
    {
        $val = !empty($this->parameters['token_id']) ? $this->parameters['token_id'] : null;
        return $this->parameters['token_id'];
    }

     /**
     * 设置商户号
     * @param string $value
     **/
    public function setMerchantNo($value)
    {
        $this->parameters['m_number'] = $value;
        return $this->parameters['m_number'];
    }

    /**
     * 获取商户号
     * @return 值
     **/
    public function getMerchantNo()
    {
        // echo $this->parameters['m_number'];
        // $get = $this->parameters['m_number'];
        $val = !empty($this->parameters['m_number']) ? $this->parameters['m_number'] : null;
        return $val;
        // if($this->parameters['m_number']){
        //     return $this->parameters['m_number'];
        // }
    }


      /**
     * 设置商户密钥
     * @param string $value
     **/
    public function setSercretKey($value)
    {
        $this->serectKey['SECRET_KEY'] = $value;
        return $this->serectKey['SECRET_KEY'];
    }

    /**
     * 获取商户密钥
     * @return 值
     **/
    public function getSercretKey()
    {
        // return $this->parameters['SECRET_KEY'];
        $val = !empty($this->serectKey['SECRET_KEY']) ? $this->serectKey['SECRET_KEY'] : null;
        return $val;
    }

    /**
     * 用于生成签名的随机字符串
     * @param string $value
     **/
    public function setNonceStr($value)
    {
        $this->parameters['nonce_str'] = $value;
    }

    /**
     * 用于生成签名的随机字符串
     * @return nonce_str
     **/
    public function getNonceStr()
    {
        return $this->parameters['nonce_str'];
    }

    /**
     * 设置时间戳
     * @param long $value
     **/
    public function setTime($value)
    {
        $this->parameters['timestamp'] = $value;
    }

    /**
     * 获取时间戳
     * @return 值
     **/
    public function getTime()
    {
        return $this->parameters['timestamp'];
    }

    /**
     * 设置签名
     * @param string $value
     **/
    public function setSign()
    {
        $sign = $this->makeSign();
        $this->parameters['sign'] = $sign;
        return $sign;
    }

    /**
     * 获取签名
     * @return 值
     **/
    public function getSign()
    {
        return $this->parameters['sign'];
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function toQueryParameters()
    {
        $queryString = "";
        
        foreach ($this->parameters as $key => $value) {
            if ($value != "" && !is_array($value)) {
                $queryString .=  $key . '=' . $value . '&';
            }
        }
        $queryString = (trim($queryString, "&"));
        return $queryString;
    }

    /**
     * 格式化参数格式化成json参数
     */
     public function toBodyParams()
     {
         return json_encode($this->bodyValues);
     }

    /**
    *商户号
    **/
    public function setMerchant_no($value)
    {
        $this->parameters['m_number'] = $value;
        // return $this->parameters['m_number'];
    }

    public function getMerchant_no()
    {   
        return $this->parameters['m_number'];
        
    }

    /**
     * 生成签名
     * @return 签名字符串
     */
    public function makeSign()
    {
        $originString = $this->getMerchantNo(). '&' . $this->getTime() . '&' . $this->getNonceStr() . "&" . $this->getSercretKey();
        $sign = strtoupper(md5($originString));
        return $sign;
    }

    /**
     * 获取设置的query参数值
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    public function getReceivedData()
    {
        return $this->receivedData;
    }

    /**
     * 设置的platform参数值
     */
    public function setPlatform($plat = "WECHATPAY")
    {
        $value = $plat;
        // echo $value."<br>";
        //判断是不是微信
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {  
            $value = 'WECHATPAY';
            // break;
        }elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
            $value = 'ALIPAY';
            // break;
        }
        $this->parameters['platform'] = $value;
        // echo $value.'<br>';
    }

    public function getPlatform()
    {
        return $this->parameters['platform'];
    }

    /**
     * 设置的getNonceStr参数值
     */
    public static function getNonceStrPublic($length = 30)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 设置的getMillisecond参数值
     */
    public static function getMillisecondPublic()
    {
        //获取毫秒的时间戳
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

}

/**
 *
 * 接口调用结果类
 *
 */
class OmiPayResults extends OmiData
{
    public function fromArray($array)
    {
        $this->receivedData = json_decode($array, true);
    }

    public static function init($array)
    {
        $obj = new self();
        $obj->fromArray($array);
        return $obj->getReceivedData();
    }

    public function setLanguage($value)
    {
        $this->parameters['language'] = $value;
    }

    public function getLanguage()
    {
        return $this->parameters['language'];
    }

     /**
     * 设置货币类型
     * 允许值: AUD, CNY
     * @param string $value
     **/
    public function setCurrency($value)
    {
        $this->parameters['currency'] = $value;
    }

     /**
     * 获取货币类型
     **/
    public function getCurrency()
    {
        return $this->parameters['currency'];
    }

}

/**
 *
 * 汇率查询输入对象
 * @author Leijid
 *
 */
class OmiPayExchangeRate extends OmiData
{

    /**
     * 设置货币类型
     * 允许值: AUD, CNY
     * @param string $value
     **/
    public function setCurrency($value)
    {
        $this->parameters['currency'] = $value;
    }

     /**
     * 获取货币类型
     **/
    public function getCurrency()
    {
        return $this->parameters['currency'];
    }

    public function setBaseCurrency($value)
    {
        $this->parameters['base_currency'] = $value;
    }

    public function getBaseCurrency()
    {
        return $this->parameters['base_currency'];
    }


}

/**
 * 创建订单对象基类
 */
class MakeOrderQueryData extends OmiData
{

     /**
     * 设置订单名称
     * @param string $value
     **/
    public function setOrderName($value)
    {
        $this->parameters['order_name'] = $value;
    }

     /**
     * 获取订单名称
     **/
    public function getOrderName()
    {
        return $this->parameters['order_name'];
    }

    /**
     * 设置外部订单号
     * @param string $value
     **/
     public function setOutOrderNo($value)
     {
         $this->parameters['out_order_no'] = $value;
     }
 
      /**
      * 获取外部订单号
      **/
     public function getOutOrderNo()
     {
         return $this->parameters['out_order_no'];
     }

     /**
     * 设置订单金额，单位为当前货币最小单位
     * @param string $value
     **/
    public function setAmount($value)
    {
        $this->parameters['amount'] = $value;
    }

    /**
     * 获取订单金额
     * @return 值
     **/
    public function getAmount()
    {
        return $this->parameters['amount'];
    }

    /**
     * 设置支付通知url,该URL需要为安全域名下。
     * @param string $value
     **/
    public function setNotifyUrl($value)
    {
        $this->parameters['notify_url'] = $value;
    }

    /**
     * 获取支付通知url
     * @return 值
     **/
    public function getNotifyUrl()
    {
        return $this->parameters['notify_url'];
    }

    /**
     * 设置支付成功后跳转页面
     * @param string $value
     **/
     public function setRedirect($value)
     {
         $this->parameters['redirect'] = $value;
     }

     /**
     * 设置跳转地址
     * @param string $value
     **/
     public function setRedirectUrl($value)
     {
        $this->parameters['redirect_url'] = $value;        
     }
 
     /**
      * 获取跳转地址
      * @return 值
      **/
     public function getRedirectUrl()
     {
         return $this->parameters['redirect_url'];
     }

     /**
     * 设置货币类型
     * 允许值: AUD, CNY
     * @param string $value
     **/
    public function setCurrency($value)
    {
        $this->parameters['currency'] = $value;
    }

     /**
     * 获取货币类型
     **/
    public function getCurrency()
    {
        return $this->parameters['currency'];
    }


      /**
     * 指定下单门店编号
     * @param string $value
     **/
    public function setONumber($value)
    {
        $this->parameters['o_number'] = $value;
    }

     /**
     * 获取指定下单门店编号
     **/
    public function getONumber()
    {
        return $this->parameters['o_number'];
    }

    /**
     * 设置POS机编号
     * @param string $value
     **/
    public function setPOSNo($value)
    {
       $this->parameters['pos_no'] = $value;        
    }

    /**
     * 获取POS机编号
     * @return 值
     **/
    public function getPOSNo()
    {
        return $this->parameters['pos_no'];
    }

}

/**
 * 创建QROrder订单对象
 */
class MakeQROrderQueryData extends MakeOrderQueryData
{
    
}

/**
 * 创建JSAPI订单对象
 */
class MakeJSAPIOrderQueryData extends MakeOrderQueryData
{
    /**
      * 设置是否直接支付
      * @param string $value
      **/
      public function setDirectPay($value)
      {
          $this->parameters['direct_pay'] = $value;
      }
  
      /**
       * 获取是否直接支付
       * @return 值
       **/
      public function getDirectPay()
      {
          return $this->parameters['direct_pay'];
      }

      /**
      * 设置是否PC支付
      * @param string $value
      **/
      public function setPcPay($value)
      {
          $this->parameters['show_pc_pay_url'] = $value;
      }
  
      /**
       * 获取是否PC支付
       * @return 值
       **/
      public function getPcPay()
      {
          return $this->parameters['show_pc_pay_url'];
      }

}

/**
 * 创建onlineOrder订单对象
 */
class MakeOnlineOrderQueryData extends MakeOrderQueryData
{
    /**
      * 设置支付类型
      * @param string $value
      **/
      public function setPayType($value)
      {
          $this->parameters['type'] = $value;
      }
  
      /**
       * 获取支付类型
       * @return 值
       **/
      public function getPayType()
      {
          return $this->parameters['type'];
      }

      /**
      * 设置支付类型
      * @param string $value
      **/
      public function setReturnUrl($value)
      {
          $this->parameters['return_url'] = $value;
      }
  
      /**
       * 获取支付类型
       * @return 值
       **/
      public function getReturnUrl()
      {
          return $this->parameters['return_url'];
      }

}

/**
 * QRCode支付跳转对象
 * @author Leijid
 */
 class OmiPayRedirect extends OmiData
 {
 
     /**
      * 判断支付成功后跳转页面是否存在
      * @return true 或 false
      **/
     public function isRedirectSet()
     {
         return array_key_exists('redirect', $this->queryValues);
     }
 }
 

/**
 * jsapi支付跳转对象
 * @author tony.t
 */
 class OmiPayJsApiRedirect extends MakeJSAPIOrderQueryData
 {

     /**
      * 判断直接支付是否存在
      * @return true 或 false
      **/
     public function isDirectPaySet()
     {
         return array_key_exists('direct_pay', $this->parameters);
     }

     
 }

/**
 * 创建ScanOrder订单对象
 */
class MakeScanOrderQueryData extends MakeOrderQueryData
{
    /**
     * 设置付款码
     * @param string $value
     **/
     public function setQRCode($value)
     {
        $this->parameters['qrcode'] = $value;        
     }
 
     /**
      * 获取付款码
      * @return 值
      **/
     public function getQRCode()
     {
         return $this->parameters['qrcode'];
     }

}

/**
 * 查询订单对象
 */
class QueryOrderQueryData extends OmiData
{
    /**
     * 设置Omipay订单编号
     * @param string $value
     **/
     public function setOrderNo($value)
     {
        $this->parameters['order_no'] = $value;        
     }
 
     /**
      * 获取付款码
      * @return 值
      **/
     public function getOrderNo()
     {
         return $this->parameters['order_no'];
     }
}

/**
 * 退款申请对象
 */
class RefundQueryData extends OmiData
{
    /**
     * 设置Omipay订单编号
     * @param string $value
     **/
    public function setOrderNo($value)
    {
        $this->parameters['order_no'] = $value;
    }

    /**
     * 获取Omipay订单编号
     * @return 值
     **/
    public function getOrderNo()
    {
        return $this->parameters['order_no'];
    }

    /**
     * 设置外部退款单号
     * @param string $value
     **/
    public function setOutRefundNo($value)
    {
        $this->parameters['out_refund_no'] = $value;
    }

    /**
     * 获取外部退款单号
     * @return 值
     **/
    public function getOutRefundNo()
    {
        return $this->parameters['out_refund_no'];
    }

    /**
     * 设置退款金额，货币类型为订单货币类型。单位是货币最小单位
     * @param string $value
     **/
    public function setAmount($value)
    {
        $this->parameters['amount'] = $value;
    }

    /**
     * 获取退款金额
     * @return 值
     **/
    public function getAmount()
    {
        return $this->parameters['amount'];
    }
}

/**
 * 查询退款状态对象
 */
class QueryRefundQueryData extends OmiData
{
    /**
     * 设置Omipay退款单号
     * @param string $value
     **/
    public function setRefundNo($value)
    {
        $this->parameters['refund_no'] = $value;
    }

    /**
     * 获取Omipay退款单号
     * @return 值
     **/
    public function getRefundNo()
    {
        return $this->parameters['refund_no'];
    }
}

/**
 * 微信小程序下单
 */
class makeWxMiniProgramOrder extends MakeOrderQueryData
{
    /**
     * appid
     * @param string $value
     **/
     public function setWxAppid($value)
     {
        $this->parameters['app_id'] = $value;        
     }
 
     /**
      * customer_id  openid
      * @return 值
      **/
     public function setCustomerId($value)
     {
        $this->parameters['customer_id'] = $value;  
     }
}