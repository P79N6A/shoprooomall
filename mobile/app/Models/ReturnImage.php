<?php
//SHOPROOO商城资源
namespace app\models;

class ReturnImage extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'return_images';
	public $timestamps = false;
	protected $fillable = array('rg_id', 'rec_id', 'user_id', 'img_file', 'add_time');
	protected $guarded = array();
}

?>
