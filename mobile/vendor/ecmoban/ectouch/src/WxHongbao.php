<?php

namespace Touch;

class WxHongbao
{
	/**
	 * 微信红包类
	 *
	 */
	private $parameters; //cft 参数
	private $configure; // 配置信息

	// api 接口地址
    const API_SEND_NORMAL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack'; // 普通红包
    const API_SEND_GROUP = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack'; // 裂变红包
    const API_QUERY = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo';  // 查询红包记录
    const API_PREPARE = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/hbpreorder';

    // hongbao type
    const TYPE_NORMAL = 'NORMAL';
    const TYPE_GROUP = 'GROUP';

    // const ATTACHMENT_ROOT = ROOT_PATH . 'storage'; //证书目录


    /**
     * 构造函数
     */
    public function __construct($configure)
    {
    	// 配置参数等 appid, partner_key
    	$this->configure = $configure;
	}

	// 生成红包接口XML信息
	/*
	<xml>
		<sign>![CDATA[E1EE61A91C8E90F299DE6AE075D60A2D]]</sign>
		<mch_billno>![CDATA[0010010404201411170000046545]]</mch_billno>
		<mch_id>![CDATA[888]]</mch_id>
		<wxappid>![CDATA[wxcbda96de0b165486]]</wxappid>
		<nick_name>![CDATA[nick_name]]</nick_name>
		<send_name>![CDATA[send_name]]</send_name>
		<re_openid>![CDATA[onqOjjmM1tad-3ROpncN-yUfa6uI]]</re_openid>
		<total_amount>![CDATA[200]]</total_amount>
		<min_value>![CDATA[200]]</min_value>
		<max_value>![CDATA[200]]</max_value>
		<total_num>![CDATA[1]]</total_num>
		<wishing>![CDATA[恭喜发财]]</wishing>
		<client_ip>![CDATA[127.0.0.1]]</client_ip>
		<act_name>![CDATA[新年红包]]</act_name>
		<act_id>![CDATA[act_id]]</act_id>
		<remark>![CDATA[新年红包]]</remark>
		<logo_imgurl>![CDATA[https://xx/img/wxpaylogo.png]]</logo_imgurl>
		<share_content>![CDATA[share_content]]</share_content>
		<share_url>![CDATA[https://xx/img/wxpaylogo.png]]</share_url>
		<share_imgurl>![CDATA[https:/xx/img/wxpaylogo.png]]</share_imgurl>
		<nonce_str>![CDATA[50780e0cca98c8c8e814883e5caa672e]]</nonce_str>
	</xml>
	*/
	/**
	 * 发送红包
	 * @param  [type] $type 红包类型：普通红包、裂变红包
	 * @return
	 */
	public function creat_sendredpack($type = self::TYPE_NORMAL)
	{
		// logResult($this->parameters);
		// if($this->check_sign_parameters() == false) {
			//检查生成签名参数
			//throw new Exception("生成签名参数缺失！" . "<br>");
	    // }

	    $this->setParameter('sign', $this->get_sign($this->parameters));  // 签名

        $api = ($type == self::TYPE_NORMAL) ? self::API_SEND_NORMAL : self::API_SEND_GROUP;
        // logResult('parameters');
        // logResult($this->parameters);
        $postXml = $this->arrayToXml($this->parameters);  // xml
        // logResult('postXml');
        // logResult($postXml);
		$result = $this->curl_post_ssl($api, $postXml);
	    $json = (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);

	    return $json;
	}

    /**
     * 查询红包记录
     * @return [type]
     */
    /*
    <xml>
    <nonce_str><![CDATA[50780e0cca98c8c8e814883e5caa672e]]></nonce_str>
    <mch_billno>0010010404201411170000046545</mch_billno>
    <mch_id>10000097</mch_id>
    <appid><![CDATA[wxe062425f740c30d8]]></appid>
    <bill_type><![CDATA[MCHT]]></bill_type>
    <sign><![CDATA[0D34F454EC3EE1D1CDCE47E6F6C6ADF3]]></sign>
    </xml>
     */
    public function query_redpack()
    {
        $this->setParameter('sign', $this->get_sign($this->parameters));  // 签名
        // logResult('parameters');
        // logResult($this->parameters);
        $postXml = $this->arrayToXml($this->parameters);  // xml
        // logResult('postXml');
        // logResult($postXml);
        $result = $this->curl_post_ssl(self::API_QUERY, $postXml);
        $json = (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);

        return $json;
    }

    /**
     * Parameter setter
     * 作用：设置请求参数 清空多余空字符串
     * @param [type] $parameter
     * @param [type] $parameterValue
     */
	public function setParameter($parameter, $parameterValue)
	{
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}

	/**
	 * Parameter getter
	 * @param  [type] $parameter
	 * @return [type]
	 */
	public function getParameter($parameter)
	{
		return $this->parameters[$parameter];
	}

	// 检测请求必填参数
	public function check_sign_parameters()
	{
		if($this->parameters["nonce_str"] == null || $this->parameters["mch_billno"] == null ||	$this->parameters["mch_id"] == null || $this->parameters["wxappid"] == null || $this->parameters["nick_name"] == null || $this->parameters["send_name"] == null || $this->parameters["re_openid"] == null || $this->parameters["total_amount"] == null || $this->parameters["max_value"] == null || $this->parameters["total_num"] == null || $this->parameters["wishing"] == null || $this->parameters["client_ip"] == null || $this->parameters["act_name"] == null || $this->parameters["remark"] == null || $this->parameters["min_value"] == null)
		{
			return false;
		}
		return true;
	}

	/**
	 * post 请求
	 * @param  [type]  $url
	 * @param  [type]  $vars
	 * @param  integer $second
	 * @param  array   $aHeader
	 * @return [type]
	 */
	public function curl_post_ssl($url, $vars, $second = 30,$aHeader = array())
	{
		$ch = curl_init();
		//设置curl默认访问为IPv4
		if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
			curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		}
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

		//以下两种方式需选择一种
		//第一种方法，cert 与 key 分别属于两个.pem文件
		// curl_setopt($ch,CURLOPT_SSLCERT, ATTACHMENT_ROOT . '/certs/' . md5($this->configure['appid']) . 'apiclient_cert.pem');
        // curl_setopt($ch,CURLOPT_SSLKEY, ATTACHMENT_ROOT . '/certs/' . md5($this->configure['appid']) . 'apiclient_key.pem');
		// curl_setopt($ch,CURLOPT_CAINFO, ATTACHMENT_ROOT . '/certs/' . md5($this->configure['appid']) . 'rootca.pem');

        // 上传证书到服务器
        curl_setopt($ch,CURLOPT_SSLCERT, ROOT_PATH . 'storage/certs/apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLKEY, ROOT_PATH . 'storage/certs/apiclient_key.pem');
        curl_setopt($ch,CURLOPT_CAINFO, ROOT_PATH . 'storage/certs/rootca.pem');

		//第二种方式，两个文件合成一个.pem文件
		//curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');

		if(count($aHeader) >= 1){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
        // post提交方式
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		// 运行curl
		$data = curl_exec($ch);
        // 返回结果
		if($data){
			curl_close($ch);
			return $data;
		}else {
			$error = curl_errno($ch);
            echo "curl出错，错误码:$error" . "<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}

    /**
     * 作用：生成签名
     * @param array $paraMap 签名数组
     * @param string $partnerkey 商户key
     * @return boolean|string 签名值
     */
	/**
	  例如：
	 	appid：    wxd930ea5d5a258f4f
		mch_id：    10000100
		device_info：  1000
		Body：    test
		nonce_str：  ibuaiVcKdpRxkhJA
		第一步：对参数按照 key=value 的格式，并按照参数名 ASCII 字典序排序如下：
		stringA="appid=wxd930ea5d5a258f4f&body=test&device_info=1000&mch_i
		d=10000100&nonce_str=ibuaiVcKdpRxkhJA";
		第二步：拼接支付密钥：
		stringSignTemp="stringA&key=192006250b4c09247ec02edce69f6a2d"
		sign=MD5(stringSignTemp).toUpperCase()="9A0A8659F005D6984697E2CA0A
		9CF3B7"
	 */
	protected function get_sign($paraMap)
	{
        // 签名步骤一：按字典序排序参数
        ksort($paraMap);
        $buff = "";
        foreach ($paraMap as $k => $v) {
            $buff .= $k . "=" . $v . "&";
        }
        $String;
        if (strlen($buff) > 0) {
            $String = substr($buff, 0, strlen($buff) - 1);
        }
        // 签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $this->configure['partnerkey'];
        // 签名步骤三：MD5加密
        $String = md5($String);
        // 签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
	}

	/**
	 * trim 过滤空字符串
	 * @param value
	 * @return
	 */
	public function trimString($value)
	{
		$ret = null;
		if (null != $value) {
			$ret = $value;
			if (strlen($ret) == 0) {
				$ret = null;
			}
		}
		return $ret;
	}

    /**
     * 作用：产生随机字符串，不长于32位
     */
	public function create_noncestr($length = 32)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
	}

	public function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v){
		//	if (null != $v && "null" != $v && "sign" != $k) {
			    if($urlencode){
				   $v = urlencode($v);
				}
				$buff .= strtolower($k) . "=" . $v . "&";
			//}
		}
		$reqPar;
		if (strlen($buff) > 0) {
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}

	public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val)
        {
			if (is_numeric($val)){
				$xml .= "<".$key.">".$val."</".$key.">";
			}else{
				$xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
			}
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * configure setter.
     * @param configure $configure
     * @return $this
     */
    public function setConfigure($configure)
    {
        $this->configure = $configure;
    }

    /**
     * configure getter.
     * @return configure
     */
    public function getConfigure()
    {
        return $this->configure;
    }

}