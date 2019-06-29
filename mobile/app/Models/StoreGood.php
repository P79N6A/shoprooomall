<?php
//SHOPROOO商城资源
namespace app\models;

class StoreGood extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'store_goods';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'store_id', 'ru_id', 'goods_number', 'extend_goods_number');
	protected $guarded = array();
}

?>
