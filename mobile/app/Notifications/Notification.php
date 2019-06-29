<?php
//SHOPROOO商城资源
namespace App\Notifications;

abstract class Notification
{
	protected $via = array();

	public function send()
	{
		foreach ($this->via as $via) {
		}
	}
}


?>
