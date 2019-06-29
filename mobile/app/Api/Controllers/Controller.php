<?php
//SHOPROOO商城资源
namespace App\Api\Controllers;

class Controller extends \Laravel\Lumen\Routing\Controller
{
	use \Dingo\Api\Routing\Helpers;

	protected function apiReturn($data, $code = 0)
	{
		return array('code' => $code, 'data' => $data);
	}
}

?>
