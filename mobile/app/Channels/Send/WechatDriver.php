<?php
//SHOPROOO商城资源
namespace App\Channels\Send;

class WechatDriver implements SendInterface
{
	protected $config = array();
	protected $wechat;

	public function __construct($config = array())
	{
		$this->config = array_merge($this->config, $config);
		$this->wechat = new \App\Channels\Wechat\Wechat($this->config);
	}

	public function push($to, $title, $content, $data = array())
	{
		return $this->wechat->setData($to, $title, $content, $data)->send($to, $title);
	}

	public function getError()
	{
		return $this->wechat->getError();
	}
}

?>
