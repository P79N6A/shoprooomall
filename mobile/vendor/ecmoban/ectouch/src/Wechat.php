<?php

namespace Touch;

use Touch\Wechat\SDK;

class Wechat extends SDK
{

    /**
     * 微信支付(公众号JSSDK支付)
     *
     * 官方文档：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
     * 微信支付：http://pay.weixin.qq.com/wiki/doc/api/index.php?chapter=9_1#
     * 官方示例：http://demo.open.weixin.qq.com/jssdk/sample.zip
     *
     */
    const PAY_PREFIX = 'https://api.mch.weixin.qq.com';
    const PAY_UNIFIEDORDER = '/pay/unifiedorder?';
    private $appid;
    private $mch_id;
    private $key;
    /**
     * 微信公众号用户标签
     */
    const TAGS_CREATE_URL = '/tags/create?';  // 创建标签
    const TAGS_GET_URL = '/tags/get?';  // 获取公众号已创建的标签
    const TAGS_UPDATE_URL = '/tags/update?';  // 编辑标签
    const TAGS_DELETE_URL = '/tags/delete?'; // 删除标签
    const USER_TAG_URL = '/user/tag/get?';  // 标签下粉丝列表
    const TAGS_MEMBER_BATCHTAGGING_URL = '/tags/members/batchtagging?';  // 批量为用户打标签
    const TAGS_MEMBER_BATCHUNTAGGING_URL = '/tags/members/batchuntagging?'; // 批量为用户取消标签
    const TAGS_GETIDLIST_URL = '/tags/getidlist?'; // 获取用户身上的标签列表

    public function __construct($options)
    {
        $this->appid = isset($options['appid']) ? $options['appid'] : '';
        $this->mch_id = isset($options['mch_id']) ? $options['mch_id'] : '';
        $this->key = isset($options['key']) ? $options['key'] : '';
        parent::__construct($options);
    }

    /**
     * 公众号支付签名
     * @param array $arr 需要签名的数据
     * @return array|bool 返回签名字串
     */
    public function getPaySign($arr = array())
    {
        if (empty($arr)) return false;
        $arr['appid'] = $this->appid;
        $arr['mch_id'] = $this->mch_id;
        $arr['nonce_str'] = $this->generateNonceStr();
        $paySign = $this->getPaySignature($arr);
        $arr['sign'] = $paySign;
        return $arr;
    }

    /**
     * 公众号支付JSSDK签名
     * @param array $arr 需要签名的数据
     * @return array|bool 返回签名字串
     */
    public function getPayJssdkSign($str)
    {
        if (empty($str)) return false;
        $arr = array();
        $arr['appId'] = $this->appid;
        $arr['timeStamp'] = " " . time();
        $arr['nonceStr'] = $this->generateNonceStr();
        $arr['package'] = "prepay_id=" . $str;
        $arr['signType'] = "MD5";
        $paySign = $this->getPaySignature($arr);
        $arr['paySign'] = $paySign;
        return $arr;
    }

    /**
     * 公众号支付 统一下单接口 （默认交易类型JSAPI）
     * @param array $arr 请求下单参数
     * @param boolean $jsSign false返回下单参数，ture返回H5签名
     * @return boolean|array
     *
     * 字段说明:
     * 商品描述   body
     * 商户订单号 out_trade_no
     * 总金额     total_fee
     * 终端IP     spbill_create_ip
     * 通知地址   notify_url
     * 用户标识   openid
     *
     *   $options = array(
     *            'appid'=>'wxdk1234567890', //填写高级调用功能的app id
     *            'mch_id'=>'xxxxxxxxxxxxxxxxxxx', //微信支付商户号
     *            'key'=>'xxxxxxxxxxxxxxxxxxx' //微信支付API密钥
     *        );
     *     $weObj = new Wechat($options);
     *
     *   $arr=array();
     *   $arr['spbill_create_ip'] = '终端IP';
     *   $arr['out_trade_no'] = '商户订单号';
     *   $arr['total_fee'] = '总金( 135 = 1.35元)';
     *   $arr['notify_url'] = "http://xxxx/PayNotify.php";
     *   $arr['body'] = '商品描述';
     *   $arr['openid'] = '用户标识';
     *   $ret = $weObj->PayUnifiedOrder($arr,true);
     *
     */
    public function PayUnifiedOrder($arr = array(), $jsSign = false)
    {
        if (empty($arr)) return false;
        $arr['device_info'] = isset($arr['device_info']) ? $arr['device_info'] : "WEB";
        $arr['fee_type'] = isset($arr['fee_type']) ? $arr['fee_type'] : "CNY";
        $arr['trade_type'] = isset($arr['trade_type']) ? $arr['trade_type'] : "JSAPI";
        $arrdata = $this->getPaySign($arr);
        $xmldata = $this->xml_encode($arrdata);
        $result = $this->http_post(self::PAY_PREFIX . self::PAY_UNIFIEDORDER, $xmldata);
        if ($result) {
            $json = (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);

            if ($json['return_code'] != "SUCCESS") { //通信失败
                $this->errCode = $json['return_code'];
                $this->errMsg = $json['return_msg'];
                return false;
            } else if ($json['result_code'] != "SUCCESS") { //下单失败
                $this->errCode = $json['err_code'];
                $this->errMsg = $json['err_code_des'];
                return false;
            }
            //生成微信签名
            return $jsSign == false ? $json : $this->getPayJssdkSign($json['prepay_id']);
        }
        return false;
    }

    /**
     * 拼接微信支付签名
     * @param array $arrdata 签名数组
     * @param string $method 签名方法
     * @param string $key 商户key
     * @return boolean|string 签名值
     */
    public function getPaySignature($arrdata, $method = "md5")
    {
        ksort($arrdata);
        $paramstring = "";
        foreach ($arrdata as $key => $value) {
            if (!$value) continue;
            if (strlen($paramstring) == 0)
                $paramstring .= $key . "=" . $value;
            else
                $paramstring .= "&" . $key . "=" . $value;
        }

        $paramstring = $paramstring . "&key=" . $this->key;
        $Sign = $method($paramstring);
        $Sign = strtoupper($Sign);
        return $Sign;
    }

    /**
     * GET 请求
     * @param string $url
     */
    private function http_get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function http_post($url,$param,$post_file=false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }


    /**
     * 创建标签
     * 一个公众号，最多可以创建100个标签。
     * @param string $name 标签名称
     * @return boolean|array
     */
    public function createTags($name){
        if (!$this->get_access_token() && !$this->checkAuth()) return false;
        $data = array(
                'tag' => array('name'=>$name)
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_CREATE_URL.'access_token='.$this->get_access_token(),self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取公众号已创建的标签
     * @return boolean|array
     */
    public function getTags(){
        if (!$this->get_access_token() && !$this->checkAuth()) return false;
        $result = $this->http_get(self::API_URL_PREFIX.self::TAGS_GET_URL.'access_token='.$this->get_access_token());
        if ($result)
        {
            $json = json_decode($result,true);
            if (isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 编辑标签名称
     * @param int $tagid 标签id
     * @param string $name 标签名称
     * @return boolean|array
     */
    public function updateTags($tagid,$name){
        if (!$this->get_access_token() && !$this->checkAuth()) return false;
        $data = array(
                'tag' => array('id'=>$tagid,'name'=>$name)
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_UPDATE_URL.'access_token='.$this->get_access_token(),self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除标签
     * 请注意，当某个标签下的粉丝超过10w时，后台不可直接删除标签。此时，开发者可以对该标签下的openid列表，先进行取消标签的操作，直到粉丝数不超过10w后，才可直接删除该标签。
     * @param int $tagid 标签id
     * @return boolean|array
     */
    public function deleteTags($tagid){
        if (!$this->get_access_token() && !$this->checkAuth()) return false;
        $data = array(
                'tag' => array('id'=>$tagid)
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_DELETE_URL.'access_token='.$this->get_access_token(),self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取标签下粉丝列表
     * @return boolean|array
     */
    public function getUserTag(){
        if (!$this->get_access_token() && !$this->checkAuth()) return false;
        $result = $this->http_get(self::API_URL_PREFIX.self::USER_TAG_URL.'access_token='.$this->get_access_token());
        if ($result)
        {
            $json = json_decode($result,true);
            if (isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取用户身上的标签列表
     * @param string $openid
     * @return boolean|array 成功则返回用户标签list
     */
    public function getUserTaglist($openid){
        if (!$this->get_access_token() && !$this->checkAuth()) return false;
        $data = array(
                'openid'=>$openid
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_GETIDLIST_URL.'access_token='.$this->get_access_token(),self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            } else
                if (isset($json['tagid_list'])) return $json['tagid_list'];
        }
        return false;
    }

    /**
     * 批量为用户打标签
     * @param int $tagid 标签id
     * @param string $openid_list 用户openid数组,一次不能超过50个
     * @return boolean|array
     */
    public function batchtaggingTagsMembers($tagid,$openid_list){
        if (!$this->get_access_token() && !$this->checkAuth()) return false;
        $data = array(
                'openid_list'=>$openid_list,
                'tagid'=>$tagid
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_MEMBER_BATCHTAGGING_URL.'access_token='.$this->get_access_token(),self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 批量为用户取消标签
     * @param int $tagid 标签id
     * @param string $openid_list 用户openid数组,一次不能超过50个
     * @return boolean|array
     */
    public function batchuntaggingTagsMembers($tagid,$openid_list){
        if (!$this->get_access_token() && !$this->checkAuth()) return false;
        $data = array(
                'openid_list'=>$openid_list,
                'tagid'=>$tagid
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_MEMBER_BATCHUNTAGGING_URL.'access_token='.$this->get_access_token(),self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }


    /**
     * 日志记录
     * @param mixed $log 输入日志
     * @return mixed
     */
    public function log($log)
    {
        $log = is_array($log) ? var_export($log, true) : $log;
        if ($this->debug && function_exists('logResult')) {
            logResult($log);
        }
    }

    /**
     * 设置缓存
     * @param string $cachename
     * @param mixed $value
     * @param int $expired
     * @return boolean
     */
    protected function setCache($cachename, $value, $expired)
    {
        return cache($cachename, $value, $expired);
    }

    /**
     * 获取缓存
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename)
    {
        return cache($cachename);
    }

    /**
     * 清除缓存
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename)
    {
        return cache($cachename, null);
    }

}
