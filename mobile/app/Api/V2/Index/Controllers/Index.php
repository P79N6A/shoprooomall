<?php
//SHOPROOO商城资源
namespace app\api\v2\index\controllers;

class Index extends \App\Api\Foundation\Controller
{
	public function actionIndex()
	{
		$params = file_get_contents('php://input');
		$params = json_decode($params, 1);
		if ((count($params) <= 0) || empty($params['method'])) {
			$params = input('post.');
		}

		$instance = $this->getMethod($params['method']);
		$logger = \App\Api\Foundation\ApiLogger::init('index', 'error');
		$this->checkSign($params);

		if (method_exists($instance['class'], $instance['method'])) {
			try {
				$app = new \Illuminate\Container\Container();
				$module = $app->build($instance['class']);
				$data = $module->$instance['method']($params);
			}
			catch (\Exception $e) {
				$debugLogger = \App\Api\Foundation\ApiLogger::init('Exception', 'debug');
				$debugLogger->debug('debug :' . ' file:' . json_encode($e->getFile()) . ', line:' . json_encode($e->getLine()) . ', code:' . json_encode($e->getCode()) . ', msg:' . json_encode($e->getMessage()));
			}
		}
		else {
			$logger->error('msg : api not found, param:' . json_encode($params) . ' instance: ' . json_encode($instance));
			$data = array('msg' => 'api not found.');
		}

		if (!in_array($params['format'], array('json', 'xml'))) {
			$params['format'] = 'json';
		}

		$this->response($data, $params['format']);
	}

	private function getMethod($method)
	{
		$method = substr($method, stripos($method, '.') + 1);
		$class = '\\';
		$res = explode('.', $method);
		$length = count($res);

		if ($length < 3) {
			array_unshift($res, $res[0]);
			$length = count($res);
		}

		foreach ($res as $key => $vo) {
			if ((0 < $key) && ($key < $length)) {
				$class .= ucfirst($vo);
			}
			else {
				$class .= $vo . '\\controllers\\';
			}

			if (($length - 2) == $key) {
				break;
			}
		}

		return array('class' => 'app\\api\\v2' . $class, 'method' => 'action' . ucfirst(end($res)));
	}

	private function checkSign($params)
	{
	}
}

?>
