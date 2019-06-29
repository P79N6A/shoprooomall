<?php
//SHOPROOO商城资源
namespace app\models;

class BackGood extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'back_goods';
	protected $primaryKey = 'rec_id';
	public $timestamps = false;
	protected $fillable = array('back_id', 'goods_id', 'product_id', 'product_sn', 'goods_name', 'brand_name', 'goods_sn', 'is_real', 'send_number', 'goods_attr');
	protected $guarded = array();
}

?>
