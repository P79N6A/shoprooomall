<?php
//SHOPROOO商城资源
namespace App\Notifications;

class DrpAccountChecked
{
	public function setVia($via)
	{
		if (!is_array($via)) {
			$this->via = array($via);
		}

		return $this;
	}
}


?>
