<?php
//SHOPROOO商城资源
namespace App\Http\Team\Controllers;

class User extends \App\Http\Base\Controllers\Frontend
{
	private $region_id = 0;
	private $area_info = array();

	public function __construct()
	{
		parent::__construct();
		L(require LANG_PATH . C('shop.lang') . '/team.php');
		$files = array('order', 'clips', 'payment', 'transaction');
		$this->load_helper($files);
		$this->user_id = $_SESSION['user_id'];
		$this->page = 1;
		$this->size = 10;
		$this->check_login();
	}

	public function actionIndex()
	{
		$this->page = I('page', 1, 'intval');
		$type = I('type', 2, 'intval');

		if (IS_AJAX) {
			$goods_list = my_team_goods($this->user_id, $type, $this->page, $this->size);
			exit(json_encode(array('list' => $goods_list['list'], 'totalPage' => $goods_list['totalpage'])));
		}

		$this->assign('type', $type);
		$this->assign('page_title', L('my_team_order'));
		$this->display();
	}

	private function check_login()
	{
		if (!(0 < $_SESSION['user_id'])) {
			$url = urlencode(__HOST__ . $_SERVER['REQUEST_URI']);

			if (IS_POST) {
				$url = urlencode($_SERVER['HTTP_REFERER']);
			}

			ecs_header('Location: ' . url('user/login/index', array('back_act' => $url)));
			exit();
		}
	}
}

?>
