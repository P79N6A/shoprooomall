<?php
//SHOPROOO商城资源
namespace App\Modules\Captcha\Controllers;

class IndexController extends \App\Modules\Base\Controllers\FrontendController
{
	public function actionIndex()
	{
		$params = array(
			'fontSize' => 14,
			'length'   => 4,
			'useNoise' => false,
			'fontttf'  => '4.ttf',
			'bg'       => array(255, 255, 255)
			);
		$verify = new \Think\Verify($params);
		$verify->entry();
	}
}

?>
