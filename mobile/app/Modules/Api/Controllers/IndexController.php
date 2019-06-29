<?php
//SHOPROOO商城资源
namespace App\Modules\Api\Controllers;

class IndexController extends \App\Modules\Api\Foundation\Controller
{
	public function actionIndex()
	{
		$this->resp(array('foo' => 'bar'));
	}
}

?>
