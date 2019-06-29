<?php
//SHOPROOO商城资源
namespace app\models;

class Supplier extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'suppliers';
	protected $primaryKey = 'suppliers_id';
	public $timestamps = false;
	protected $fillable = array('suppliers_name', 'suppliers_desc', 'is_check');
	protected $guarded = array();
}

?>
