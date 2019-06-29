<?php
//SHOPROOO商城资源
namespace App\Modules\Api\Transformers;

class UserTransformer
{
	public function transform(\App\Models\Users $user)
	{
		return array('id' => $user->user_id, 'name' => $user->user_name);
	}
}


?>
