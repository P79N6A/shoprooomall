<?php
//SHOPROOO商城资源
namespace app\behavior;

class CompatibleBehavior
{
	private $fs;
	private $model;

	public function run()
	{
		$nav_path = dirname(ROOT_PATH) . '/data/attached/nav';

		if (!is_dir($nav_path)) {
			$this->model = new \Think\Model();
			$this->fs = new \Symfony\Component\Filesystem\Filesystem();
			$this->fs->mirror(ROOT_PATH . 'public/img/more-nav', $nav_path);
			$this->model->execute('update {pre}touch_nav set `pic` = replace(`pic`, "more-nav/","")');
		}
	}
}


?>
