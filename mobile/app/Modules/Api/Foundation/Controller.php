<?php
//SHOPROOO商城资源
namespace App\Modules\Api\Foundation;

class Controller extends \App\Modules\Base\Controllers\FrontendController
{
	protected function resp($data, $code = 200)
	{
		$res = array('code' => $code);

		if ($code != 200) {
			$res['message'] = $data;
		}
		else {
			$res['data'] = $data;
		}

		$this->response($res, 'json', $code);
	}

	protected function validate($args, $pattern)
	{
		$validator = Validation::createValidation();
		$rules = Validation::transPattern($pattern);

		if ($validator->validate($rules)->create($args) === false) {
			return $validator->getError();
		}
		else {
			return true;
		}
	}
}

?>
