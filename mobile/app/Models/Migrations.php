<?php
//SHOPROOO商城资源
namespace App\Models;

class Migrations extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'migrations';
	public $timestamps = false;
	protected $fillable = array('migration', 'batch');
	protected $guarded = array();
}

?>
