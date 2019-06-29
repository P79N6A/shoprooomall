<?php 
/***************************/
/* Wechat 登录              /
/* by //SHOPROOO 禁止倒卖 一经发现停止任何服务 2017.05.30      /
/***************************/  

if (defined('WEBSITE') || defined('GETINFO'))
{
    //global $_LANG;
	$_LANG['help'] = array();
    $_LANG['help']['APP_KEY'] = '在微信开发者平台申请的AppID';
    $_LANG['help']['APP_SECRET'] = '在微信开发者平台申请的AppSecret';
    
    $_LANG['APP_KEY'] = 'AppID';
    $_LANG['APP_SECRET'] = 'AppSecret';
    
    $i = isset($web) ? count($web) : 0;
    // 类名
    $web[$i]['name'] = '微信扫码登录';
    // 文件名，不包含后缀
    $web[$i]['type'] = 'wechat';
    
    $web[$i]['className'] = 'wechat';
    
    // 作者信息
    $web[$i]['author'] = '//SHOPROOO 禁止倒卖 一经发现停止任何服务';
    
	// 作者QQ
	$web[$i]['qq'] = '1172527557';
	
	// 作者邮箱
	$web[$i]['email'] = '1172527557@qq.com';
    
    // 申请网址
    $web[$i]['website'] = 'http://open.weixin.qq.com';
    
    // 版本号
    $web[$i]['version'] = 'v3.0';
    
    // 更新日期
    $web[$i]['date']  = '2018-5-30';
    
    // 配置信息
    $web[$i]['config'] = array(
        array('type'=>'text' , 'name'=>'APP_KEY', 'value'=>''),
        array('type'=>'text' , 'name' => 'APP_SECRET' , 'value' => ''),
    );
}


if (!defined('WEBSITE'))
{
    include_once(dirname(__FILE__).'/we_oath.class.php');
    class website extends oath2
    {
        function website()
        {
            $this->app_key = APP_KEY;
            $this->app_secret = APP_SECRET;
            
            $this->scope = 'snsapi_login';
            //by tiandi authorizeURL是用来PHP打开微信登录时用,JS调用则不用authorizeURL。
            $this->authorizeURL = 'https://open.weixin.qq.com/connect/qrconnect';

            $this->tokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
            $this->refreshtokenURL = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
            $this->userURL = 'https://api.weixin.qq.com/sns/userinfo';
            $this->meth = 'GET';
        }

        function Code2Token($code)
        {
            $params  = 'appid='.$this->app_key.'&secret='.$this->app_secret.'&code='.$code.
                '&grant_type=authorization_code';
            $tokenurl = $this->tokenURL."?". $params;
            $token = $this->http($tokenurl, 'GET');
            $token = json_decode($token , true);
            return $token;
        }

        function GetRefreshToken($token)
        {
            $params  = 'appid='.$this->app_key.'&grant_type=refresh_token&refresh_token='.$token;
            $tokenurl = $this->refreshtokenURL."?". $params;
            $token = $this->http($tokenurl, 'GET');
            $token = json_decode($token , true);
            return $token;
        }
        
        function Getinfo($token,$openid)
        {
            $params = 'access_token='.$token.'&openid='.$openid;
            $userurl = $this->userURL."?". $params;
            $userinfo = $this->http($userurl, 'GET');
            return json_decode($userinfo , true);
        }
		
		public function message($info)
		{
			if (!$info || !is_array($info)) {
				return false;
			}

			$info['name'] = $info['nickname'];
			$info['sex'] = $info['sex'] ;
			$info['user_id'] = $this->token['unionid'];
			$info['img'] = $info['headimgurl'];
			$info['rank_id'] = RANK_ID;
			return $info;
		}
    }
}