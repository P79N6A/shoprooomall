<?php
//SHOPROOO商城资源
namespace app\repositories\v2;

class Foundation
{
	const SUCCESS = 0;
	const UNKNOWN_ERROR = 10000;
	const INVALID_SESSION = 10001;
	const EXPIRED_SESSION = 10002;
	const BAD_REQUEST = 400;
	const UNAUTHORIZED = 401;
	const NOT_FOUND = 404;

	static public function formatPaged($page, $size, $total)
	{
		return array('total' => $total, 'page' => $page, 'size' => $size, 'more' => ($page * $size) < $total ? 1 : 0);
	}

	static public function formatBody(array $data = array())
	{
		$data['error_code'] = 0;
		return $data;
	}

	static public function formatError($code, $message = NULL)
	{
		switch ($code) {
		case self::UNKNOWN_ERROR:
			$message = 'unknown error';
			break;

		case self::NOT_FOUND:
			$message = 'error 404';
			break;
		}

		$body['error'] = true;
		$body['error_code'] = $code;
		$body['error_desc'] = $message;
		return $body;
	}
}


?>
