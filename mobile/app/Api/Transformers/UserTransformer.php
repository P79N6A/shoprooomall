<?php
//SHOPROOO商城资源
namespace App\Api\Transformers;

class UserTransformer extends \League\Fractal\TransformerAbstract
{
	public function transform(\App\Models\Users $user)
	{
		return array('id' => $user->user_id, 'name' => $user->user_name);
	}
}

?>
