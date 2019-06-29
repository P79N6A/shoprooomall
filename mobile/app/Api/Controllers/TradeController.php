<?php
//SHOPROOO商城资源
namespace App\Api\Controllers;

class TradeController extends \App\Api\Foundation\Controller
{
	protected $trade;
	protected $tradeTransformer;

	public function __construct(\App\Repositories\Trade\TradeRepository $trade, \App\Api\V2\Trade\Transformer\TradeTransformer $tradeTransformer)
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
