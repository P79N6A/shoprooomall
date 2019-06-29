<?php
//SHOPROOO商城资源
namespace app\models;

class GoodsInventoryLog extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'goods_inventory_logs';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'order_id', 'use_storage', 'admin_id', 'number', 'model_inventory', 'model_attr', 'product_id', 'warehouse_id', 'area_id', 'add_time');
	protected $guarded = array();
}

?>
