<?php
//SHOPROOO商城资源
namespace App\Channels\Send;

class SmsDriver implements SendInterface
{
	protected $config = array('sms_name' => '', 'sms_password' => '');
	protected $sms;

	public function __construct($config = array())
	{
		$this->config = array_merge($this->config, $config);
		$this->sms = new \App\Channels\Sms\Sms($this->config);
	}

	public function push($to, $title, $content, $data = array())
	{
		return $this->sms->setSms($title, $content)->sendSms($to);
	}

	public function getError()
	{
		return $this->sms->getError();
	}
}

?>
