<?php
//SHOPROOO商城资源
namespace app\models;

class Product extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'products';
	protected $primaryKey = 'product_id';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'goods_attr', 'product_sn', 'bar_code', 'product_number', 'product_price', 'product_market_price', 'product_warn_number');
	protected $guarded = array();
}

?>
