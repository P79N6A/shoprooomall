<?php
//SHOPROOO商城资源
namespace App\Models;

class AliyunxinConfigure extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'aliyunxin_configure';
	public $timestamps = false;
	protected $fillable = array('temp_id', 'temp_content', 'add_time', 'set_sign', 'send_time', 'signature');
	protected $guarded = array();
}

?>
