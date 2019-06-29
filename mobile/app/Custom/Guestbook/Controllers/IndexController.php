<?php
//SHOPROOO商城资源
namespace App\Custom\Guestbook\Controllers;

class IndexController extends \App\Modules\Base\Controllers\FrontendController
{
	public function actionIndex()
	{
		echo 'this guestbook list. ';
		echo '<a href="' . url('add') . '">Goto Add</a>';
	}

	public function actionAdd()
	{
		$this->display();
	}

	public function actionSave()
	{
		$post = array('title' => I('title'), 'content' => I('content'));
		$this->redirect('index');
	}
}

?>
