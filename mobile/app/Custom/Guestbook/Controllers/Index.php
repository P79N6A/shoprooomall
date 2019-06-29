<?php
//SHOPROOO商城资源
namespace App\Custom\Guestbook\Controllers;

class Index extends \App\Http\Base\Controllers\Frontend
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
