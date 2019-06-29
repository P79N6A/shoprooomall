<?php
//SHOPROOO商城资源
namespace app\models;

class WarehouseAreaGood extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'warehouse_area_goods';
	protected $primaryKey = 'a_id';
	public $timestamps = false;
	protected $fillable = array('user_id', 'goods_id', 'region_id', 'region_sn', 'region_number', 'region_price', 'region_promote_price', 'region_sort', 'add_time', 'last_update', 'give_integral', 'rank_integral', 'pay_integral');
	protected $guarded = array();
}

?>
