<?php
//SHOPROOO商城资源
namespace app\models;

class DeliveryGood extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'delivery_goods';
	protected $primaryKey = 'rec_id';
	public $timestamps = false;
	protected $fillable = array('delivery_id', 'goods_id', 'product_id', 'product_sn', 'goods_name', 'brand_name', 'goods_sn', 'is_real', 'extension_code', 'parent_id', 'send_number', 'goods_attr');
	protected $guarded = array();
}

?>
