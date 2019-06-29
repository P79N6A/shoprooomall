<?php
//SHOPROOO商城资源
namespace App\Notifications\Send;

interface SendInterface
{
	public function __construct($config);

	public function push($to, $title, $content, $data = array());

	public function getError();
}


?>
