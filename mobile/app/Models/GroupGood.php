<?php
//SHOPROOO商城资源
namespace app\models;

class GroupGood extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'group_goods';
	public $timestamps = false;
	protected $fillable = array('parent_id', 'goods_id', 'goods_price', 'admin_id', 'group_id');
	protected $guarded = array();
}

?>
