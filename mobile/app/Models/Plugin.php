<?php
//SHOPROOO商城资源
namespace app\models;

class Plugin extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'plugins';
	protected $primaryKey = 'code';
	public $timestamps = false;
	protected $fillable = array('version', 'library', 'assign', 'install_date');
	protected $guarded = array();
}

?>
