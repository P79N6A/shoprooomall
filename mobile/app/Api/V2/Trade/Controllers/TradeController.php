<?php
//SHOPROOO商城资源
namespace app\api\v2\trade;

class TradeController extends \App\Api\Foundation\Controller
{
	protected $trade;
	protected $tradeTransformer;

	public function __construct(\App\Repositories\trade\TradeRepository $trade, transformer\TradeTransformer $tradeTransformer)
	{
		parent::__construct();
		$this->trade = $trade;
		$this->tradeTransformer = $tradeTransformer;
	}

	public function actionGet()
	{
	}
}

?>
