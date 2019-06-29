<?php
//SHOPROOO商城资源
namespace app\models;

class Stage extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'stages';
	protected $primaryKey = 'stages_id';
	public $timestamps = false;
	protected $fillable = array('order_sn', 'stages_total', 'stages_one_price', 'yes_num', 'create_date', 'repay_date');
	protected $guarded = array();
}

?>
