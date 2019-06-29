<?php
//SHOPROOO商城资源
namespace app\models;

class Stat extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'stats';
	public $timestamps = false;
	protected $fillable = array('access_time', 'ip_address', 'visit_times', 'browser', 'system', 'language', 'area', 'referer_domain', 'referer_path', 'access_url');
	protected $guarded = array();
}

?>
