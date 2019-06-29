<?php
//SHOPROOO商城资源
namespace app\models;

class OrderGood extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'order_goods';
	protected $primaryKey = 'rec_id';
	public $timestamps = false;
	protected $fillable = array('order_id', 'goods_id', 'goods_name', 'goods_sn', 'product_id', 'goods_number', 'market_price', 'goods_price', 'goods_attr', 'send_number', 'is_real', 'extension_code', 'parent_id', 'is_gift', 'model_attr', 'goods_attr_id', 'ru_id', 'shopping_fee', 'warehouse_id', 'area_id', 'is_single', 'freight', 'tid', 'shipping_fee', 'drp_money', 'is_distribution');
	protected $guarded = array();
}

?>
