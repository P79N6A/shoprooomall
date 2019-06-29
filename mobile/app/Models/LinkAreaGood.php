<?php
//SHOPROOO商城资源
namespace app\models;

class LinkAreaGood extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'link_area_goods';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'region_id', 'ru_id');
	protected $guarded = array();
}

?>
