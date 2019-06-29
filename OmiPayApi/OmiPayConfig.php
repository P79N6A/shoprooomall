<?php

/**
 *
 *  Configuration Class
 *  配置类
 *
 */
class OmiPayConfig
{

    // API版本
    const API_VERSION = "/omipay/api/v2";

    // // 正式环境
    const DOMAIN = "https://www.omipay.com.au";
    const DOMAINCN = "https://g.omipay.com.cn";

    const IpayLinksUrl = "https://g.omipay.com.cn/cardpay/";

    // =======【curl代理设置】===================================
    /**
     * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     * @var unknown_type
     */
    const CURL_PROXY_HOST = "0.0.0.0";//"192.168.0.1";  
    const CURL_PROXY_PORT = 0;//8080;

    const merchant_no = "";   // 设置商户号，不要加M  如 "000034" 
    const merchant_key = "";   // 商户密钥

    const notify_url = "";  // 支付完成通知地址

    const DOMAIN_TYPE = "CN";   // 默认为CN / AU

}

