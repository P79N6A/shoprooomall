<?php
//SHOPROOO商城资源
namespace app\models;

class Coupon extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'coupons';
	protected $primaryKey = 'cou_id';
	public $timestamps = false;
	protected $fillable = array('cou_name', 'cou_total', 'cou_man', 'cou_money', 'cou_user_num', 'cou_goods', 'cou_start_time', 'cou_end_time', 'cou_type', 'cou_get_man', 'cou_ok_user', 'cou_ok_goods', 'cou_intro', 'cou_add_time', 'ru_id', 'cou_order', 'cou_title');
	protected $guarded = array();
}

?>
